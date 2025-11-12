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
use OCA\Activity\AppInfo\Application;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\UserSettings;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IDBConnection;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IEMailTemplate;
use OCP\Mail\IEmailValidator;
use OCP\Mail\IMailer;
use OCP\RichObjectStrings\IValidator;
use OCP\Server;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

/**
 * Class MailQueueHandlerTest
 * @package OCA\Activity\Tests
 */
#[Group('DB')]
class MailQueueHandlerTest extends TestCase {
	protected MailQueueHandler $mailQueueHandler;
	protected IMailer&MockObject $mailer;
	protected MockObject $message;
	protected IUserManager&MockObject $userManager;
	protected IFactory&MockObject $lFactory;
	protected IManager&MockObject $activityManager;
	protected IValidator&MockObject $richObjectValidator;
	protected IConfig&MockObject $config;
	protected MockObject&LoggerInterface $logger;

	protected IDateTimeFormatter&MockObject $dateTimeFormatter;
	protected Data&MockObject $data;
	protected GroupHelper&MockObject $groupHelper;
	protected UserSettings&MockObject $userSettings;
	protected IEmailValidator&MockObject $emailValidator;


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
		$this->emailValidator = $this->createMock(IEmailValidator::class);

		$connection = Server::get(IDBConnection::class);
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
		$event->method('setApp')
			->willReturnSelf();
		$event->method('setType')
			->willReturnSelf();
		$event->method('setAffectedUser')
			->willReturnSelf();
		$event->method('setTimestamp')
			->willReturnSelf();
		$event->method('setSubject')
			->willReturnSelf();
		$event->method('getIcon')
			->willReturn('');
		$event->method('getParsedSubject')
			->willReturn('');
		$event->method('getRichSubject')
			->willReturn('');
		$event->method('getRichSubjectParameters')
			->willReturn([]);

		$this->activityManager = $this->createMock(IManager::class);
		$this->activityManager->method('generateEvent')
			->willReturn($event);
		$this->activityManager->method('getProviders')
			->willReturn([]);

		$this->message = $this->createMock(Message::class);
		$this->richObjectValidator = $this->createMock(IValidator::class);
		$this->mailer = $this->createMock(IMailer::class);
		$this->mailer->method('createMessage')
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
			$this->emailValidator,
		);
	}

	protected function tearDown(): void {
		$query = Server::get(IDBConnection::class)
			->prepare('DELETE FROM `*PREFIX*activity_mq` WHERE `amq_timestamp` < 500');
		$query->execute();

		parent::tearDown();
	}

	public static function getAffectedUsersData(): array {
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
	 * @param int|null $limit
	 * @param array $affected
	 * @param array $untouched
	 */
	#[DataProvider('getAffectedUsersData')]
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

		$connection = Server::get(IDBConnection::class);
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
		$this->emailValidator->expects($this->once())
			->method('isValid')
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
		$userObject
			->method('getDisplayName')
			->willReturn($userDisplayName);
		$this->userManager
			->method('get')
			->willReturnMap([
				[$user, $userObject],
				[$user . $user, null],
			]);
		$this->lFactory->expects($this->once())
			->method('get')
			->with(Application::APP_ID, 'en')
			->willReturn($this->getMockBuilder(IL10N::class)->getMock());

		$this->activityManager->expects($this->exactly(2))
			->method('setCurrentUserId');

		$this->dateTimeFormatter
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
