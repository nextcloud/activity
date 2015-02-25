<?php

/**
 * ownCloud - Activity App
 *
 * @author Thomas Müller
 * @copyright 2014 Thomas Müller deepdiver@owncloud.com
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

use OCA\Activity\MailQueueHandler;
use OCA\Activity\UserSettings;

class MailQueueHandlerTest extends TestCase {
	/** @var MailQueueHandler */
	protected $mailQueueHandler;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $mailer;

	protected function setUp() {
		parent::setUp();

		$app = $this->getUniqueID('MailQueueHandlerTest');

		$query = \OCP\DB::prepare('INSERT INTO `*PREFIX*activity_mq` '
			. ' (`amq_appid`, `amq_subject`, `amq_subjectparams`, `amq_affecteduser`, `amq_timestamp`, `amq_type`, `amq_latest_send`) '
			. ' VALUES(?, ?, ?, ?, ?, ?, ?)');

		$query->execute(array($app, 'Test data', 'Param1', 'user1', 150, 'phpunit', 152));
		$query->execute(array($app, 'Test data', 'Param1', 'user1', 150, 'phpunit', 153));
		$query->execute(array($app, 'Test data', 'Param1', 'user2', 150, 'phpunit', 150));
		$query->execute(array($app, 'Test data', 'Param1', 'user2', 150, 'phpunit', 151));
		$query->execute(array($app, 'Test data', 'Param1', 'user3', 150, 'phpunit', 154));
		$query->execute(array($app, 'Test data', 'Param1', 'user3', 150, 'phpunit', 155));

		$this->mailer = $this->getMock('\OCA\Activity\MockUtilSendMail');
		$this->mailQueueHandler = new MailQueueHandler(
			$this->getMock('\OCP\IDateTimeFormatter'),
			\OC::$server->getDatabaseConnection(),
			$this->getMockBuilder('\OCA\Activity\DataHelper')
				->disableOriginalConstructor()
				->getMock(),
			$this->mailer
		);
	}

	protected function tearDown() {
		$query = \OCP\DB::prepare('DELETE FROM `*PREFIX*activity_mq` WHERE `amq_timestamp` < 200');
		$query->execute();

		parent::tearDown();
	}

	public function getAffectedUsersData()
	{
		return [
			[null, ['user2', 'user1', 'user3'], []],
			[5, ['user2', 'user1', 'user3'], []],
			[3, ['user2', 'user1', 'user3'], []],
			[2, ['user2', 'user1'], ['user3']],
			[1, ['user2'], ['user1', 'user3']],
		];
	}

	/**
	 * @dataProvider getAffectedUsersData
	 *
	 * @param int $limit
	 * @param array $affected
	 * @param array $untouched
	 */
	public function testGetAffectedUsers($limit, $affected, $untouched) {
		$maxTime = 200;

		$this->assertRemainingMailEntries($untouched, $maxTime, 'before doing anything');
		$users = $this->mailQueueHandler->getAffectedUsers($limit, $maxTime);
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after getting the affected users');

		$this->assertEquals($affected, $users);

		$items = $this->mailQueueHandler->getItemsForUsers($users, $maxTime);

		$this->assertSameSize($users, $items, 'Failed asserting that each user has a mail entry');
		foreach ($users as $user) {
			$this->assertArrayHasKey($user, $items);
		}
		foreach ($untouched as $user) {
			$this->assertArrayNotHasKey($user, $items);
		}
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after getting the affected items');

		$this->mailQueueHandler->deleteSentItems($users, $maxTime);

		$this->assertEmpty(
			$this->mailQueueHandler->getItemsForUsers($users, $maxTime),
			'Failed to assert that all entries for the affected users have been deleted'
		);
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after deleting the affected items');
	}

	public function testSendEmailToUser() {
		$maxTime = 200;
		$user = 'user2';
		$email = $user . '@localhost';

		$this->mailer->expects($this->any())
			->method('sendMail')
			->with(
				$email,
				$user,
				$this->anything(),
				$this->stringContains($user),
				$this->anything(),
				$this->anything()
			)
			->willReturn(null);

		$users = $this->mailQueueHandler->getAffectedUsers(1, $maxTime);
		$this->assertEquals([$user], $users);
		$items = $this->mailQueueHandler->getItemsForUsers($users, $maxTime);
		$this->assertArrayHasKey($user, $items);
		$this->mailQueueHandler->sendEmailToUser($user, $email, 'en', 'UTC', $items[$user]);
	}

	/**
	 * @param array $users
	 * @param int $maxTime
	 * @param string $explain
	 */
	protected function assertRemainingMailEntries(array $users, $maxTime, $explain) {
		if (!empty($untouched)) {
			$this->assertNotEmpty(
				$this->mailQueueHandler->getItemsForUsers($users, $maxTime),
				'Failed asserting that the remaining users still have mails in the queue ' . $explain
			);
		}
	}

	public function getLangForApproximatedTimeFrameData() {
		return [
			[time() - 3900, UserSettings::EMAIL_SEND_HOURLY],
			[time() - 89900, UserSettings::EMAIL_SEND_DAILY],
			[time() - 90100, UserSettings::EMAIL_SEND_WEEKLY],
		];
	}

	/**
	 * @dataProvider getLangForApproximatedTimeFrameData
	 * @param int $time
	 * @param int $expected
	 */
	public function testGetLangForApproximatedTimeFrame($time, $expected) {
		$this->assertEquals(
			$expected,
			\Test_Helper::invokePrivate($this->mailQueueHandler, 'getLangForApproximatedTimeFrame', [$time]),
			'Failed asserting to receive the right timeframe'
		);
	}
}
