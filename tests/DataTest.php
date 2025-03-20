<?php

/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Citharel <nextcloud@tcit.fr>
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
use OCP\Activity\IManager;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IL10N;
use OCP\IUserSession;
use OCP\Util;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;

/**
 * Class DataTest
 *
 * @group DB
 * @package OCA\Activity\Tests
 */
class DataTest extends TestCase {
	/** @var Data */
	protected $data;

	/** @var IL10N */
	protected $activityLanguage;

	/** @var IUserSession|MockObject */
	protected $session;

	/** @var IDBConnection */
	protected $dbConnection;

	/** @var IManager */
	protected $realActivityManager;

	/** @var NullLogger */
	protected $logger;

	protected IConfig&MockObject $config;

	protected function setUp(): void {
		parent::setUp();

		$this->activityLanguage = Util::getL10N('activity', 'en');
		$activityManager = $this->createMock(IManager::class);
		$this->dbConnection = \OC::$server->get(IDBConnection::class);
		$this->realActivityManager = \OC::$server->get(IManager::class);
		$this->logger = \OC::$server->get(NullLogger::class);
		$this->config = $this->createMock(IConfig::class);

		$this->data = new Data(
			$activityManager,
			$this->dbConnection,
			$this->logger,
			$this->config
		);
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	public function dataSend(): array {
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
	public function testSend(string $actionUser, string $affectedUser, string $expectedAuthor, string $expectedAffected, bool $expectedActivity): void {
		$this->deleteTestActivities();

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setSubject('subject', []);

		if ($affectedUser !== '') {
			$event->setAffectedUser($affectedUser);
		}

		if ($actionUser !== '') {
			$event->setAuthor($actionUser);
		}

		$this->assertSame($expectedActivity, $this->data->send($event) !== 0);

		$qb = $this->dbConnection->getQueryBuilder();
		$query = $qb->select('user', 'affecteduser')
			->from('activity')
			->where($qb->expr()->eq('app', $qb->createNamedParameter('test')))
			->orderBy('activity_id', 'DESC');
		$result = $query->executeQuery();
		$row = $result->fetch();

		if ($expectedActivity) {
			$this->assertEquals(['user' => $expectedAuthor, 'affecteduser' => $expectedAffected], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestActivities();
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
	public function testStoreMail(string $actionUser, string $affectedUser, string $expectedAuthor, string $expectedAffected, bool $expectedActivity): void {
		$this->deleteTestMails();

		$time = time();

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setSubject('subject', [])
			->setTimestamp($time);

		if ($affectedUser !== '') {
			$event->setAffectedUser($affectedUser);
		}

		$this->assertSame($expectedActivity, $this->data->storeMail($event, $time + 10));

		$qb = $this->dbConnection->getQueryBuilder();
		$query = $qb->select('amq_latest_send', 'amq_affecteduser')
			->from('activity_mq')
			->where($qb->expr()->eq('amq_appid', $qb->createNamedParameter('test')))
			->orderBy('mail_id', 'DESC');
		$result = $query->executeQuery();
		$row = $result->fetch();

		if ($expectedActivity) {
			$this->assertEquals(['amq_latest_send' => $time + 10, 'amq_affecteduser' => $expectedAffected], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestMails();
	}

	public function dataSetOffsetFromSince(): array {
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
	 * @param string|null $timestampWhere
	 * @param string|null $idWhere
	 * @param string|null $offsetUser
	 * @param int|bool|null $offsetId
	 * @param string|null $expectedHeader
	 * @throws \OCP\DB\Exception
	 */
	public function testSetOffsetFromSince(string $sort, ?string $timestampWhere, ?string $idWhere, ?string $offsetUser, $offsetId, ?string $expectedHeader): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('testing');
		if ($offsetUser === null) {
			$offsetUser = $user;
		} elseif ($offsetUser === 'invalid-user') {
			$this->expectException('OutOfBoundsException');
			$this->expectExceptionMessage('Invalid since');
			$this->expectExceptionCode(2);
		}

		$query = $this->dbConnection->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('test'),
				'affecteduser' => $query->createNamedParameter($user),
				'timestamp' => 123465789,
				'subject' => $query->createNamedParameter('subject'),
				'subjectparams' => $query->createNamedParameter('subjectparams'),
				'priority' => 1,
			])
			->executeStatement();
		$id = $query->getLastInsertId();

		$mock = $this->getMockBuilder(IQueryBuilder::class)
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
//				->withConsecutive(
//					[$timestampWhere],
//					[str_replace('{id}', $id, $idWhere)]
//				)
			;
		} else {
			$mock->expects($this->never())
				->method('andWhere');
		}

		if ($offsetId === null) {
			$offsetId = $id;
		} elseif ($offsetId === false) {
			$offsetId = 0;
		} else {
			$offsetId += $id;
		}

		$headers = self::invokePrivate($this->data, 'setOffsetFromSince', [$mock, $offsetUser, $offsetId, $sort]);

		if ($expectedHeader) {
			$this->assertArrayHasKey($expectedHeader, $headers);
			$this->assertEquals($id, $headers[$expectedHeader]);
		} else {
			$this->assertCount(0, $headers);
		}

		$this->deleteTestActivities();
	}

	public function testDeleteAffectedUserActivities(): void {
		$this->deleteTestActivities();
		$user1 = self::getUniqueID('testing');
		$user2 = self::getUniqueID('testing');
		$this->insertActivityForAffectedUser($user1);
		$this->insertActivityForAffectedUser($user2);

		$this->assertEquals(1, $this->countActivitiesForAffectedUser($user1));
		$this->assertEquals(1, $this->countActivitiesForAffectedUser($user2));
		$this->data->deleteActivities(['affecteduser' => $user1]);
		$this->assertEquals(0, $this->countActivitiesForAffectedUser($user1));
		$this->assertEquals(1, $this->countActivitiesForAffectedUser($user2));
		$this->deleteTestActivities();
	}

	/**
	 * Delete all testing activities
	 */
	protected function deleteTestActivities(): void {
		$query = $this->dbConnection->getQueryBuilder();
		$query->delete('activity')
			->where($query->expr()->eq('app', $query->createNamedParameter('test')));
		$query->executeStatement();
	}

	/**
	 * Delete all testing mails
	 */
	protected function deleteTestMails(): void {
		$query = $this->dbConnection->getQueryBuilder();
		$query->delete('activity_mq')
			->where($query->expr()->eq('amq_appid', $query->createNamedParameter('test')));
		$query->executeStatement();
	}

	private function insertActivityForAffectedUser(string $user): void {
		$query = $this->dbConnection->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('test'),
				'affecteduser' => $query->createNamedParameter($user),
				'timestamp' => 123465789,
				'subject' => $query->createNamedParameter('subject'),
				'subjectparams' => $query->createNamedParameter('subjectparams'),
				'priority' => 1,
			])
			->executeStatement();
	}

	private function countActivitiesForAffectedUser(string $user): int {
		$query = $this->dbConnection->getQueryBuilder();
		$query->select($query->func()->count('activity_id', 'count'))
			->from('activity')
			->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)));
		$result = $query->executeQuery();
		$row = $result->fetch();
		return $row['count'];
	}
}
