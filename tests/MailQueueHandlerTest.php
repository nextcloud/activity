<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Citharel <nextcloud@tcit.fr>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
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

use OC\Mail\Message;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\UserSettings;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IEMailTemplate;
use OCP\Mail\IMailer;
use OCP\RichObjectStrings\IValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

/**
 * Class MailQueueHandlerTest
 *
 * @group DB
 * @package OCA\Activity\Tests
 */
class MailQueueHandlerTest extends TestCase {
	/** @var MailQueueHandler */
	protected $mailQueueHandler;

	/** @var MockObject|IMailer */
	protected $mailer;

	/** @var MockObject */
	protected $message;

	/** @var IUserManager */
	protected $oldUserManager;

	/** @var MockObject|IUserManager */
	protected $userManager;

	/** @var MockObject|IFactory */
	protected $lFactory;

	/** @var MockObject|IManager */
	protected $activityManager;

	/** @var IValidator|MockObject */
	protected $richObjectValidator;

	/** @var IConfig|MockObject */
	protected $config;

	/** @var LoggerInterface|MockObject */
	protected $logger;

	/** @var IDateTimeFormatter|MockObject */
	protected $dateTimeFormatter;

	/** @var MockObject|Data */
	protected Data $data;

	/** @var MockObject|GroupHelper */
	protected GroupHelper $groupHelper;

	/** @var MockObject|UserSettings */
	protected UserSettings $userSettings;


	protected function setUp(): void {
		parent::setUp();

		$app = self::getUniqueID('MailQueueHandlerTest', 10);
		$this->userManager = $this->createMock(IUserManager::class);
		$this->lFactory = $this->createMock(IFactory::class);
		$this->config = $this->createMock(IConfig::class);
		$this->logger = $this->createMock(LoggerInterface::class);
		$this->dateTimeFormatter = $this->createMock(IDateTimeFormatter::class);
		$this->data = $this->createMock(Data::class);
		$this->groupHelper = $this->createMock(GroupHelper::class);
		$this->userSettings = $this->createMock(UserSettings::class);

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('INSERT INTO `*PREFIX*activity_mq` '
			. ' (`amq_appid`, `amq_subject`, `amq_subjectparams`, `amq_affecteduser`, `amq_timestamp`, `amq_type`, `amq_latest_send`) '
			. ' VALUES(?, ?, ?, ?, ?, ?, ?)');

		$query->execute([$app, 'Test data', json_encode(['Param1']), 'user1', 150, 'phpunit', 152]);
		$query->execute([$app, 'Test data', json_encode(['Param1']), 'user1', 150, 'phpunit', 153]);
		$query->execute([$app, 'Test data', json_encode(['Param1']), 'user2', 150, 'phpunit', 150]);
		$query->execute([$app, 'Test data', json_encode(['Param1']), 'user2', 150, 'phpunit', 151]);
		$query->execute([$app, 'Test data', json_encode(['Param1']), 'user3', 150, 'phpunit', 154]);
		$query->execute([$app, 'Test data', json_encode(['Param1']), 'user3', 150, 'phpunit', 155]);

		$event = $this->createMock(IEvent::class);
		$event->expects($this->any())
			->method('setApp')
			->willReturnSelf();
		$event->expects($this->any())
			->method('setType')
			->willReturnSelf();
		$event->expects($this->any())
			->method('setAffectedUser')
			->willReturnSelf();
		$event->expects($this->any())
			->method('setTimestamp')
			->willReturnSelf();
		$event->expects($this->any())
			->method('setSubject')
			->willReturnSelf();
		$event->expects($this->any())
			->method('getIcon')
			->willReturn('');
		$event->expects($this->any())
			->method('getParsedSubject')
			->willReturn('');
		$event->expects($this->any())
			->method('getRichSubject')
			->willReturn('');
		$event->expects($this->any())
			->method('getRichSubjectParameters')
			->willReturn([]);

		$this->activityManager = $this->createMock(IManager::class);
		$this->activityManager->expects($this->any())
			->method('generateEvent')
			->willReturn($event);
		$this->activityManager->expects($this->any())
			->method('getProviders')
			->willReturn([]);

		$this->message = $this->createMock(Message::class);
		$this->richObjectValidator = $this->createMock(IValidator::class);
		$this->mailer = $this->createMock(IMailer::class);
		$this->mailer->expects($this->any())
			->method('createMessage')
			->willReturn($this->message);
		$this->mailQueueHandler = new MailQueueHandler(
			$this->dateTimeFormatter,
			$connection,
			$this->mailer,
			$this->createMock(IURLGenerator::class),
			$this->userManager,
			$this->lFactory,
			$this->activityManager,
			$this->richObjectValidator,
			$this->config,
			$this->logger,
			$this->data,
			$this->groupHelper,
			$this->userSettings,
		);
	}

	protected function tearDown(): void {
		$query = \OC::$server->getDatabaseConnection()->prepare('DELETE FROM `*PREFIX*activity_mq` WHERE `amq_timestamp` < 500');
		$query->execute();

		parent::tearDown();
	}

	public function getAffectedUsersData(): array {
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
	public function testGetAffectedUsers(?int $limit, array $affected, array $untouched): void {
		$maxTime = 200;

		$this->assertRemainingMailEntries($untouched, $maxTime, 'before doing anything');
		$users = self::invokePrivate($this->mailQueueHandler, 'getAffectedUsers', [$limit, $maxTime, false, null]);
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after getting the affected users');

		$this->assertEquals($affected, $users);
		foreach ($users as $user) {
			[$data, $skipped] = self::invokePrivate($this->mailQueueHandler, 'getItemsForUser', [$user, $maxTime]);
			$this->assertNotEmpty($data, 'Failed asserting that each user has a mail entry');
			$this->assertSame(0, $skipped);
		}
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after getting the affected items');

		self::invokePrivate($this->mailQueueHandler, 'deleteSentItems', [$users, $maxTime]);

		foreach ($users as $user) {
			[$data, $skipped] = self::invokePrivate($this->mailQueueHandler, 'getItemsForUser', [$user, $maxTime]);
			$this->assertEmpty($data, 'Failed to assert that all entries for the affected users have been deleted');
			$this->assertSame(0, $skipped);
		}
		$this->assertRemainingMailEntries($untouched, $maxTime, 'after deleting the affected items');
	}

	public function testGetItemsForUser(): void {
		[$data, $skipped] = self::invokePrivate($this->mailQueueHandler, 'getItemsForUser', ['user1', 200]);
		$this->assertCount(2, $data, 'Failed to assert the user has 2 entries');
		$this->assertSame(0, $skipped);

		$connection = \OC::$server->getDatabaseConnection();
		$query = $connection->prepare('INSERT INTO `*PREFIX*activity_mq` '
			. ' (`amq_appid`, `amq_subject`, `amq_subjectparams`, `amq_affecteduser`, `amq_timestamp`, `amq_type`, `amq_latest_send`) '
			. ' VALUES(?, ?, ?, ?, ?, ?, ?)');

		$app = self::getUniqueID('MailQueueHandlerTest', 10);
		for ($i = 0; $i < 15; $i++) {
			$query->execute([$app, 'Test data', 'Param1', 'user1', 150, 'phpunit', 160 + $i]);
		}

		[$data, $skipped] = self::invokePrivate($this->mailQueueHandler, 'getItemsForUser', ['user1', 200, 5]);
		$this->assertCount(5, $data, 'Failed to assert the user has 2 entries');
		$this->assertSame(12, $skipped);
	}

	public function testPurgeItemsForUser(): void {
		[$data, $skipped] = self::invokePrivate($this->mailQueueHandler, 'getItemsForUser', ['user1', 200]);
		$this->assertCount(2, $data, 'Failed to assert the user has 2 entries');
		$this->assertSame(0, $skipped);

		$this->mailQueueHandler->purgeItemsForUser('user1');

		[$data, $skipped] = self::invokePrivate($this->mailQueueHandler, 'getItemsForUser', ['user1', 200]);
		$this->assertCount(0, $data, 'Failed to assert the user has no entries left');
		$this->assertSame(0, $skipped);
	}

	public function testSendEmailToUser(): void {
		$maxTime = 200;
		$user = 'user2';
		$userDisplayName = 'user two';
		$email = $user . '@localhost';

		$template = $this->createMock(IEMailTemplate::class);
		$this->mailer->expects($this->once())
			->method('send')
			->with($this->message);
		$this->mailer->expects($this->once())
			->method('createEMailTemplate')
			->willReturn($template);
		$this->mailer->expects($this->once())
			->method('validateMailAddress')
			->with($email)
			->willReturn(true);

		$template->expects($this->once())
			->method('addHeader');
		$template->expects($this->once())
			->method('addHeading');
		$template->expects($this->exactly(2))
			->method('addBodyText');
		$template->expects($this->once())
			->method('addFooter');

		$this->message->expects($this->once())
			->method('setTo')
			->with([$email => $userDisplayName]);
		$this->message->expects($this->once())
			->method('useTemplate')
			->with($template);

		$userObject = $this->createMock(IUser::class);
		$userObject->expects($this->any())
			->method('getDisplayName')
			->willReturn($userDisplayName);
		$this->userManager->expects($this->any())
			->method('get')
			->willReturnMap([
				[$user, $userObject],
				[$user . $user, null],
			]);
		$this->lFactory->expects($this->once())
			->method('get')
			->with('activity', 'en')
			->willReturn($this->getMockBuilder(IL10N::class)->getMock());

		$this->activityManager->expects($this->exactly(2))
			->method('setCurrentUserId')
			->withConsecutive(
				[$user],
				[null]
			);

		$this->dateTimeFormatter->expects($this->any())
			->method('formatDateTimeRelativeDay')
			->willReturn('relative');

		$users = self::invokePrivate($this->mailQueueHandler, 'getAffectedUsers', [1, $maxTime, false, null]);
		$this->assertEquals([$user], $users);
		self::invokePrivate($this->mailQueueHandler, 'sendEmailToUser', [$user, $email, 'en', 'UTC', $maxTime]);

		// Invalid user, no object no email
		self::invokePrivate($this->mailQueueHandler, 'sendEmailToUser', [$user . $user, $email, 'en', 'UTC', $maxTime]);
	}

	/**
	 * @param array $users
	 * @param int $maxTime
	 * @param string $explain
	 */
	protected function assertRemainingMailEntries(array $users, int $maxTime, string $explain): void {
		foreach ($users as $user) {
			[$data,] = self::invokePrivate($this->mailQueueHandler, 'getItemsForUser', [$user, $maxTime]);
			$this->assertNotEmpty(
				$data,
				'Failed asserting that the remaining user ' . $user . ' still has mails in the queue ' . $explain
			);
		}
	}
}
