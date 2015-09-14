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

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $message;

	/** @var \OCP\IUserManager */
	protected $oldUserManager;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $userManager;

	protected function setUp() {
		parent::setUp();

		$app = $this->getUniqueID('MailQueueHandlerTest');
		$this->userManager = $this->getMock('OCP\IUserManager');

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('INSERT INTO `*PREFIX*activity_mq` '
			. ' (`amq_appid`, `amq_subject`, `amq_subjectparams`, `amq_affecteduser`, `amq_timestamp`, `amq_type`, `amq_latest_send`) '
			. ' VALUES(?, ?, ?, ?, ?, ?, ?)');

		$query->execute(array($app, 'Test data', 'Param1', 'user1', 150, 'phpunit', 152));
		$query->execute(array($app, 'Test data', 'Param1', 'user1', 150, 'phpunit', 153));
		$query->execute(array($app, 'Test data', 'Param1', 'user2', 150, 'phpunit', 150));
		$query->execute(array($app, 'Test data', 'Param1', 'user2', 150, 'phpunit', 151));
		$query->execute(array($app, 'Test data', 'Param1', 'user3', 150, 'phpunit', 154));
		$query->execute(array($app, 'Test data', 'Param1', 'user3', 150, 'phpunit', 155));

		$this->message = $this->getMockBuilder('\OC\Mail\Message')
			->disableOriginalConstructor()
			->getMock();
		$this->mailer = $this->getMock('\OCP\Mail\IMailer');
		$this->mailer->expects($this->any())
			->method('createMessage')
			->willReturn($this->message);
		$this->mailQueueHandler = new MailQueueHandler(
			$this->getMock('\OCP\IDateTimeFormatter'),
			$connection,
			$this->getMockBuilder('\OCA\Activity\DataHelper')
				->disableOriginalConstructor()
				->getMock(),
			$this->mailer,
			$this->getMockBuilder('\OCP\IURLGenerator')
				->disableOriginalConstructor()
				->getMock(),
			$this->userManager
		);
	}

	protected function tearDown() {
		$query = \OC::$server->getDatabaseConnection()->prepare('DELETE FROM `*PREFIX*activity_mq` WHERE `amq_timestamp` < 500');
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
		foreach ($users as $user) {
			list($data, $skipped) = $this->invokePrivate($this->mailQueueHandler, 'getItemsForUser', [$user, $maxTime]);
			$this->assertNotEmpty($data, 'Failed asserting that each user has a mail entry');
			$this->assertSame(0, $skipped);
		}
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after getting the affected items');

		$this->mailQueueHandler->deleteSentItems($users, $maxTime);

		foreach ($users as $user) {
			list($data, $skipped) = $this->invokePrivate($this->mailQueueHandler, 'getItemsForUser', [$user, $maxTime]);
			$this->assertEmpty($data, 'Failed to assert that all entries for the affected users have been deleted');
			$this->assertSame(0, $skipped);
		}
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after deleting the affected items');
	}

	public function testGetItemsForUser() {
		list($data, $skipped) = $this->invokePrivate($this->mailQueueHandler, 'getItemsForUser', ['user1', 200]);
		$this->assertCount(2, $data, 'Failed to assert the user has 2 entries');
		$this->assertSame(0, $skipped);

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('INSERT INTO `*PREFIX*activity_mq` '
			. ' (`amq_appid`, `amq_subject`, `amq_subjectparams`, `amq_affecteduser`, `amq_timestamp`, `amq_type`, `amq_latest_send`) '
			. ' VALUES(?, ?, ?, ?, ?, ?, ?)');

		$app = $this->getUniqueID('MailQueueHandlerTest');
		for ($i = 0; $i < 15; $i++) {
			$query->execute(array($app, 'Test data', 'Param1', 'user1', 150, 'phpunit', 160 + $i));
		}

		list($data, $skipped) = $this->invokePrivate($this->mailQueueHandler, 'getItemsForUser', ['user1', 200, 5]);
		$this->assertCount(5, $data, 'Failed to assert the user has 2 entries');
		$this->assertSame(12, $skipped);
	}

	public function testSendEmailToUser() {
		$maxTime = 200;
		$user = 'user2';
		$userDisplayName = 'user two';
		$email = $user . '@localhost';

		$this->mailer->expects($this->once())
			->method('send')
			->with($this->message);

		$this->message->expects($this->once())
			->method('setTo')
			->with([$email => $userDisplayName]);
		$this->message->expects($this->once())
			->method('setSubject');
		$this->message->expects($this->once())
			->method('setPlainBody');
		$this->message->expects($this->once())
			->method('setFrom');

		$userObject = $this->getMock('OCP\IUser');
		$userObject->expects($this->any())
			->method('getDisplayName')
			->willReturn($userDisplayName);
		$this->userManager->expects($this->any())
			->method('get')
			->willReturnMap([
				[$user, $userObject],
				[$user . $user, null],
			]);

		$users = $this->mailQueueHandler->getAffectedUsers(1, $maxTime);
		$this->assertEquals([$user], $users);
		$this->mailQueueHandler->sendEmailToUser($user, $email, 'en', 'UTC', $maxTime);

		// Invalid user, no object no email
		$this->mailQueueHandler->sendEmailToUser($user . $user, $email, 'en', 'UTC', $maxTime);
	}

	/**
	 * @param array $users
	 * @param int $maxTime
	 * @param string $explain
	 */
	protected function assertRemainingMailEntries(array $users, $maxTime, $explain) {
		if (!empty($untouched)) {
			foreach ($users as $user) {
				list($data,) = $this->invokePrivate($this->mailQueueHandler, 'getItemsForUser', [$user, $maxTime]);
				$this->assertNotEmpty(
					$data,
					'Failed asserting that the remaining user ' . $user. ' still has mails in the queue ' . $explain
				);
			}
		}
	}
}
