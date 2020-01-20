<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity\Tests;

use OCA\Activity\Data;
use OCA\Activity\Tests\Mock\Extension;
use OCP\Activity\IManager;
use OCP\IUserSession;
use OCP\RichObjectStrings\IValidator;

/**
 * Class DataTest
 *
 * @group DB
 * @package OCA\Activity\Tests
 */
class DataTest extends TestCase {
	/** @var \OCA\Activity\Data */
	protected $data;

	/** @var \OCP\IL10N */
	protected $activityLanguage;

	/** @var \OC\Activity\Manager|\PHPUnit_Framework_MockObject_MockObject */
	protected $activityManager;

	/** @var \OCP\IUserSession|\PHPUnit_Framework_MockObject_MockObject */
	protected $session;

	protected function setUp(): void {
		parent::setUp();

		$this->activityLanguage = $activityLanguage = \OCP\Util::getL10N('activity', 'en');
		$this->activityManager = $this->createMock(IManager::class);
		$this->session = $this->createMock(IUserSession::class);

		$this->data = new Data(
			$this->activityManager,
			\OC::$server->getDatabaseConnection(),
			$this->session
		);
	}

	protected function tearDown(): void {
		$this->restoreService('UserSession');
		parent::tearDown();
	}

	public function dataSend() {
		return [
			// Default case
			['author', 'affectedUser', 'author', 'affectedUser', true],
			// Public page / Incognito mode
			['', 'affectedUser', '', 'affectedUser', true],
			// No affected user => no activity
			['author', '', 'author', '', false],
			// No affected user and no author => no activity
			['', '', '', '', false],
		];
	}

	/**
	 * @dataProvider dataSend
	 *
	 * @param string $actionUser
	 * @param string $affectedUser
	 * @param string $expectedAuthor
	 * @param string $expectedAffected
	 * @param bool $expectedActivity
	 */
	public function testSend($actionUser, $affectedUser, $expectedAuthor, $expectedAffected, $expectedActivity) {
		$mockSession = $this->getMockBuilder('\OC\User\Session')
			->disableOriginalConstructor()
			->getMock();

		$this->overwriteService('UserSession', $mockSession);
		$this->deleteTestActivities();

		$event = \OC::$server->getActivityManager()->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setSubject('subject', []);

		if ($affectedUser !== '') {
			$event->setAffectedUser($affectedUser);
		}

		if ($actionUser !== '') {
			$event->setAuthor($actionUser);
		}

		$this->assertSame($expectedActivity, $this->data->send($event));

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('SELECT `user`, `affecteduser` FROM `*PREFIX*activity` WHERE `app` = ? ORDER BY `activity_id` DESC');
		$query->execute(['test']);
		$row = $query->fetch();

		if ($expectedActivity) {
			$this->assertEquals(['user' => $expectedAuthor, 'affecteduser' => $expectedAffected], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestActivities();
		$this->restoreService('UserSession');
	}

	/**
	 * @dataProvider dataSend
	 *
	 * @param string $actionUser
	 * @param string $affectedUser
	 * @param string $expectedAuthor
	 * @param string $expectedAffected
	 * @param bool $expectedActivity
	 */
	public function testStoreMail($actionUser, $affectedUser, $expectedAuthor, $expectedAffected, $expectedActivity) {
		$mockSession = $this->getMockBuilder('\OC\User\Session')
			->disableOriginalConstructor()
			->getMock();

		$this->overwriteService('UserSession', $mockSession);
		$this->deleteTestMails();

		$time = time();

		$event = \OC::$server->getActivityManager()->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setSubject('subject', [])
			->setTimestamp($time);

		if ($affectedUser !== '') {
			$event->setAffectedUser($affectedUser);
		}

		$this->assertSame($expectedActivity, $this->data->storeMail($event, $time + 10));

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('SELECT `amq_latest_send`, `amq_affecteduser` FROM `*PREFIX*activity_mq` WHERE `amq_appid` = ? ORDER BY `mail_id` DESC');
		$query->execute(['test']);
		$row = $query->fetch();

		if ($expectedActivity) {
			$this->assertEquals(['amq_latest_send' => $time + 10, 'amq_affecteduser' => $expectedAffected], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestMails();
		$this->restoreService('UserSession');
	}

	public function dataSetOffsetFromSince() {
		return [
			['ASC', '`timestamp` >= \'123465789\'', '`activity_id` > \'{id}\'', null, null, null],
			['DESC', '`timestamp` <= \'123465789\'', '`activity_id` < \'{id}\'', null, null, null],
			['DESC', null, null, 'invalid-user', null, null],
			['DESC', null, null, null, 1, 'X-Activity-First-Known'],
			['DESC', null, null, 'user', false, null],
		];
	}

	/**
	 * @dataProvider dataSetOffsetFromSince
	 *
	 * @param string $sort
	 * @param string $timestampWhere
	 * @param string $idWhere
	 * @param string $offsetUser
	 * @param int $offsetId
	 * @param string $expectedHeader
	 */
	public function testSetOffsetFromSince($sort, $timestampWhere, $idWhere, $offsetUser, $offsetId, $expectedHeader) {
		$this->deleteTestActivities();
		$user = $this->getUniqueID('testing');
		if ($offsetUser === null) {
			$offsetUser = $user;
		} else if ($offsetUser === 'invalid-user') {
			$this->expectException('OutOfBoundsException');
			$this->expectExceptionMessage('Invalid since');
			$this->expectExceptionCode(2);
		}

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('test'),
				'affecteduser' => $query->createNamedParameter($user),
				'timestamp' => 123465789,
				'subject' => $query->createNamedParameter('subject'),
				'subjectparams' => $query->createNamedParameter('subjectparams'),
				'priority' => 1,
			])
			->execute();
		$id = $query->getLastInsertId();

		$mock = $this->getMockBuilder('OCP\DB\QueryBuilder\IQueryBuilder')
			->disableOriginalConstructor()
			->getMock();
		$mock->expects($this->any())
			->method('expr')
			->willReturn($query->expr());
		$mock->expects($this->any())
			->method('createNamedParameter')
			->willReturnCallback(function ($arg) use ($query) {
				return $query->expr()->literal($arg);
			});
		if ($timestampWhere !== null && $idWhere !== null) {
			$mock->expects($this->exactly(2))
				->method('andWhere')
				->withConsecutive(
					[$timestampWhere],
					[str_replace('{id}', $id, $idWhere)]
				);
		} else {
			$mock->expects($this->never())
				->method('andWhere');
		}

		if ($offsetId === null) {
			$offsetId = $id;
		} else if ($offsetId === false) {
			$offsetId = 0;
		} else {
			$offsetId += $id;
		}

		$headers = $this->invokePrivate($this->data, 'setOffsetFromSince', [$mock, $offsetUser, $offsetId, $sort]);

		if ($expectedHeader) {
			$this->assertArrayHasKey($expectedHeader, $headers);
			$this->assertEquals($id, $headers[$expectedHeader]);
		} else {
			$this->assertCount(0, $headers);
		}

		$this->deleteTestActivities();
	}

	/**
	 * Delete all testing activities
	 */
	protected function deleteTestActivities() {
		$query = \OC::$server->getDatabaseConnection()->getQueryBuilder();
		$query->delete('activity')
			->where($query->expr()->eq('app', $query->createNamedParameter('test')));
		$query->execute();
	}

	/**
	 * Delete all testing mails
	 */
	protected function deleteTestMails() {
		$query = \OC::$server->getDatabaseConnection()->getQueryBuilder();
		$query->delete('activity_mq')
			->where($query->expr()->eq('amq_appid', $query->createNamedParameter('test')));
		$query->execute();
	}
}
