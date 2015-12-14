<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace OCA\Activity\Tests;

use OC\ActivityManager;
use OCA\Activity\Data;
use OCA\Activity\Tests\Mock\Extension;
use OCP\Activity\IExtension;
use OCP\IUser;

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

	/** @var ActivityManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $activityManager;

	/** @var \OCP\IUserSession|\PHPUnit_Framework_MockObject_MockObject */
	protected $session;

	protected function setUp() {
		parent::setUp();

		$this->activityLanguage = $activityLanguage = \OCP\Util::getL10N('activity', 'en');
		$this->activityManager = new ActivityManager(
			$this->getMock('OCP\IRequest'),
			$this->getMock('OCP\IUserSession'),
			$this->getMock('OCP\IConfig')
		);
		$this->session = $this->getMockBuilder('OCP\IUserSession')
			->disableOriginalConstructor()
			->getMock();

		$this->activityManager->registerExtension(function() use ($activityLanguage) {
			return new Extension($activityLanguage, $this->getMock('\OCP\IURLGenerator'));
		});
		$this->data = new Data(
			$this->activityManager,
			\OC::$server->getDatabaseConnection(),
			$this->session
		);
	}

	protected function tearDown() {
		$this->restoreService('UserSession');
		parent::tearDown();
	}

	public function dataGetNotificationTypes() {
		return [
			['type1'],
		];
	}

	/**
	 * @dataProvider dataGetNotificationTypes
	 * @param string $typeKey
	 */
	public function testGetNotificationTypes($typeKey) {
		$this->assertArrayHasKey($typeKey, $this->data->getNotificationTypes($this->activityLanguage));
		// Check cached version aswell
		$this->assertArrayHasKey($typeKey, $this->data->getNotificationTypes($this->activityLanguage));
	}

	public function validateFilterData() {
		return array(
			// Default filters
			array('all', 'all'),
			array('by', 'by'),
			array('self', 'self'),

			// Filter from extension
			array('filter1', 'filter1'),

			// Inexistent or empty filter
			array('test', 'all'),
			array(null, 'all'),
		);
	}

	/**
	 * @dataProvider validateFilterData
	 *
	 * @param string $filter
	 * @param string $expected
	 */
	public function testValidateFilter($filter, $expected) {
		$this->assertEquals($expected, $this->data->validateFilter($filter));
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
			->setAffectedUser($affectedUser)
			->setSubject('subject', []);
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
			->setAffectedUser($affectedUser)
			->setSubject('subject', [])
			->setTimestamp($time);

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

	protected function getUserMock() {
		$user = $this->getMockBuilder('\OCP\IUser')
			->disableOriginalConstructor()
			->getMock();
		$user->expects($this->any())
			->method('getUID')
			->willReturn('username');
		return $user;
	}

	public function dataRead() {
		return [
			[null, 0, 10, 'all', '', null, [], '', 0, null, null, [], null, null],
			[$this->getUserMock(), 0, 10, 'all', '', 'username', [], '', 0, null, null, [], null, null],
			[$this->getUserMock(), 0, 10, 'all', 'test', 'test', [], '', 0, null, null, [], null, null],
			[$this->getUserMock(), 0, 10, 'all', '', 'username', ['file_created'], false, '', 0, null, [], ' AND `type` IN (?) AND `user` <> ?', ['username', 'file_created', 'username']],
			[$this->getUserMock(), 0, 10, 'all', '', 'username', ['file_created'], true, '', 0, null, [], ' AND `type` IN (?)', ['username', 'file_created']],
			[$this->getUserMock(), 0, 10, 'by', '', 'username', ['file_created'], null, '', 0, null, [], ' AND `type` IN (?) AND `user` <> ?', ['username', 'file_created', 'username']],
			[$this->getUserMock(), 0, 10, 'self', '', 'username', ['file_created'], null, '', 0, null, [], ' AND `type` IN (?) AND `user` = ?', ['username', 'file_created', 'username']],
			[$this->getUserMock(), 0, 10, 'all', '', 'username', ['file_created'], true, '', 0, 'OR `cond` = 1', null, ' AND `type` IN (?) OR `cond` = 1', ['username', 'file_created']],
			[$this->getUserMock(), 0, 10, 'all', '', 'username', ['file_created'], true, '', 0, 'OR `cond` = ?', ['con1'], ' AND `type` IN (?) OR `cond` = ?', ['username', 'file_created', 'con1']],
			[$this->getUserMock(), 0, 10, 'filter', '', 'username', ['file_created'], false, 'files', 42, null, [], ' AND `type` IN (?) AND `user` <> ? AND `object_type` = ? AND `object_id` = ?', ['username', 'file_created', 'username', 'files', 42]],
			[$this->getUserMock(), 0, 10, 'filter', '', 'username', ['file_created'], true, 'files', 42, null, [], ' AND `type` IN (?) AND `object_type` = ? AND `object_id` = ?', ['username', 'file_created', 'files', 42]],
		];
	}

	/**
	 * @dataProvider dataRead
	 *
	 * @param IUser $sessionUser
	 * @param int $start
	 * @param int $count
	 * @param string $filter
	 * @param string $user
	 * @param string $expectedUser
	 * @param array $notificationTypes
	 * @param bool $selfSetting
	 * @param string $objectType
	 * @param int $objectId
	 * @param array $conditions
	 * @param array $params
	 * @param string $limitActivities
	 * @param array $parameters
	 */
	public function removedtestRead($sessionUser, $start, $count, $filter, $user, $expectedUser, $notificationTypes, $selfSetting, $objectType, $objectId, $conditions, $params, $limitActivities, $parameters) {

		/** @var \OCA\Activity\GroupHelper|\PHPUnit_Framework_MockObject_MockObject $groupHelper */
		$groupHelper = $this->getMockBuilder('OCA\Activity\GroupHelper')
			->disableOriginalConstructor()
			->getMock();
		$groupHelper->expects(($expectedUser === null) ? $this->never() : $this->once())
			->method('setUser')
			->with($expectedUser);

		/** @var \OCA\Activity\UserSettings|\PHPUnit_Framework_MockObject_MockObject $settings */
		$settings = $this->getMockBuilder('OCA\Activity\UserSettings')
			->disableOriginalConstructor()
			->getMock();
		$settings->expects(($expectedUser === null) ? $this->never() : $this->once())
			->method('getNotificationTypes')
			->with($expectedUser, 'stream')
			->willReturn(['settings']);
		$settings->expects(($selfSetting === null) ? $this->never() : $this->any())
			->method('getUserSetting')
			->with($expectedUser, 'setting', 'self')
			->willReturn($selfSetting);

		/** @var ActivityManager|\PHPUnit_Framework_MockObject_MockObject $activityManager */
		$activityManager = $this->getMockBuilder('OCP\Activity\IManager')
			->disableOriginalConstructor()
			->getMock();
		$activityManager->expects($this->any())
			->method('filterNotificationTypes')
			->with(['settings'], $filter)
			->willReturn($notificationTypes);
		$activityManager->expects($this->any())
			->method('getQueryForFilter')
			->with($filter)
			->willReturn([$conditions, $params]);

		/** @var \OCA\Activity\Data|\PHPUnit_Framework_MockObject_MockObject $data */
		$data = $this->getMockBuilder('OCA\Activity\Data')
			->setConstructorArgs([
				$activityManager,
				\OC::$server->getDatabaseConnection(),
				$this->session
			])
			->setMethods(['getActivities'])
			->getMock();
		$data->expects(($parameters === null && $limitActivities === null) ? $this->never() : $this->once())
			->method('getActivities')
			->with($count, $start, $limitActivities, $parameters, $groupHelper)
			->willReturn([]);

		$this->session->expects($this->any())
			->method('getUser')
			->willReturn($sessionUser);

		$this->assertEquals([], $data->read(
			$groupHelper, $settings,
			$start, $count, $filter, $user,
			$objectType, $objectId
		));
	}

	/**
	 * @expectedException \BadMethodCallException
	 * @expectedExceptionMessage No settings enabled
	 * @expectedExceptionCode 3
	 */
	public function testGetNoSettings() {
		/** @var \OCA\Activity\GroupHelper|\PHPUnit_Framework_MockObject_MockObject $groupHelper */
		$groupHelper = $this->getMockBuilder('OCA\Activity\GroupHelper')
			->disableOriginalConstructor()
			->getMock();
		$groupHelper->expects($this->once())
			->method('setUser')
			->with('user1');

		/** @var \OCA\Activity\UserSettings|\PHPUnit_Framework_MockObject_MockObject $settings */
		$settings = $this->getMockBuilder('OCA\Activity\UserSettings')
			->disableOriginalConstructor()
			->getMock();
		$settings->expects($this->once())
			->method('getNotificationTypes')
			->with('user1', 'stream')
			->willReturn(['settings']);

		/** @var ActivityManager|\PHPUnit_Framework_MockObject_MockObject $activityManager */
		$activityManager = $this->getMockBuilder('OCP\Activity\IManager')
			->disableOriginalConstructor()
			->getMock();
		$activityManager->expects($this->any())
			->method('filterNotificationTypes')
			->with(['settings'], 'filter1')
			->willReturn([]);
		$activityManager->expects($this->never())
			->method('getQueryForFilter');

		/** @var \OCA\Activity\Data|\PHPUnit_Framework_MockObject_MockObject $data */
		$data = new \OCA\Activity\Data(
			$activityManager,
			\OC::$server->getDatabaseConnection(),
			$this->session
		);

		$data->get($groupHelper, $settings, 'user1', 0, 0, 'asc', 'filter1');
	}

	/**
	 * @expectedException \OutOfBoundsException
	 * @expectedExceptionMessage Invalid user
	 * @expectedExceptionCode 1
	 */
	public function testGetNoUser() {
		/** @var \OCA\Activity\GroupHelper|\PHPUnit_Framework_MockObject_MockObject $groupHelper */
		$groupHelper = $this->getMockBuilder('OCA\Activity\GroupHelper')
			->disableOriginalConstructor()
			->getMock();

		/** @var \OCA\Activity\UserSettings|\PHPUnit_Framework_MockObject_MockObject $settings */
		$settings = $this->getMockBuilder('OCA\Activity\UserSettings')
			->disableOriginalConstructor()
			->getMock();

		$this->data->get($groupHelper, $settings, '', 0, 0, 'asc', '');
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
			$this->setExpectedException('OutOfBoundsException', 'Invalid since', 2);
		}

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('test'),
				'affecteduser' => $query->createNamedParameter($user),
				'timestamp' => 123465789,
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
	public function deleteTestActivities() {
		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('DELETE FROM `*PREFIX*activity` WHERE `app` = ?');
		$query->execute(['test']);
	}

	/**
	 * Delete all testing mails
	 */
	public function deleteTestMails() {
		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('DELETE FROM `*PREFIX*activity_mq` WHERE `amq_appid` = ?');
		$query->execute(['test']);
	}
}
