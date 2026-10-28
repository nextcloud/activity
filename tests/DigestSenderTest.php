<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests;

use OCA\Activity\Data;
use OCA\Activity\DigestSender;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Defaults;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IMailer;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class DigestSenderTest extends TestCase {
	protected IConfig&MockObject $config;
	protected Data&MockObject $data;
	protected IMailer&MockObject $mailer;
	protected IManager&MockObject $activityManager;
	protected IUserManager&MockObject $userManager;
	protected LoggerInterface&MockObject $logger;
	protected DigestSender $digestSender;

	protected function setUp(): void {
		parent::setUp();

		$this->config = $this->createMock(IConfig::class);
		$this->data = $this->createMock(Data::class);
		$this->mailer = $this->createMock(IMailer::class);
		$this->activityManager = $this->createMock(IManager::class);
		$this->userManager = $this->createMock(IUserManager::class);
		$this->logger = $this->createMock(LoggerInterface::class);

		$l10nFactory = $this->createMock(IFactory::class);
		$l10nFactory->method('get')
			->willReturn($this->createMock(IL10N::class));

		$this->digestSender = new DigestSender(
			$this->config,
			$this->data,
			$this->createMock(UserSettings::class),
			$this->createMock(GroupHelper::class),
			$this->mailer,
			$this->activityManager,
			$this->userManager,
			$this->createMock(IURLGenerator::class),
			$this->createMock(Defaults::class),
			$l10nFactory,
			$this->createMock(IDateTimeFormatter::class),
			$this->logger,
		);
	}

	protected function expectDigestUsers(array $users, array $timezones): void {
		$this->config->method('getUsersForUserValue')
			->willReturn($users);
		$this->config->method('getUserValueForUsers')
			->willReturnCallback(static function (string $app, string $key) use ($timezones) {
				return ($app === 'core' && $key === 'timezone') ? $timezones : [];
			});
	}

	protected function createUser(string $uid, string $email): IUser&MockObject {
		$user = $this->createMock(IUser::class);
		$user->method('getUID')->willReturn($uid);
		$user->method('getEMailAddress')->willReturn($email);
		$user->method('isEnabled')->willReturn(true);
		return $user;
	}

	public function testInvalidTimezoneDoesNotAbortTheRun(): void {
		$this->expectDigestUsers(
			['brokenUser', 'goodUser'],
			['brokenUser' => 'Not/AZone', 'goodUser' => 'UTC'],
		);

		$this->userManager->expects($this->once())
			->method('get')
			->with('goodUser')
			->willReturn($this->createUser('goodUser', ''));
		$this->logger->expects($this->once())
			->method('warning');

		$this->digestSender->sendDigests(1700000000);
	}

	public function testInvalidTimezoneIsOnlyReportedOnce(): void {
		$this->expectDigestUsers(
			['userA', 'userB', 'userC'],
			['userA' => 'Not/AZone', 'userB' => 'Not/AZone', 'userC' => 'Not/AZone'],
		);

		$this->userManager->expects($this->never())
			->method('get');
		$this->logger->expects($this->once())
			->method('warning');

		$this->digestSender->sendDigests(1700000000);
	}

	public function testUserWithoutEmailIsSkippedBeforeBuildingTheDigest(): void {
		$this->expectDigestUsers(['noMailUser'], ['noMailUser' => 'UTC']);

		$user = $this->createMock(IUser::class);
		$user->method('getUID')->willReturn('noMailUser');
		$user->method('getEMailAddress')->willReturn('');
		// Reached only if the missing address is not caught first
		$user->expects($this->never())
			->method('isEnabled');

		$this->userManager->method('get')
			->willReturn($user);
		$this->data->method('getActivitySince')
			->willReturn(['count' => 0, 'max' => 0]);

		$this->mailer->expects($this->never())
			->method('createEMailTemplate');
		$this->mailer->expects($this->never())
			->method('send');
		// The marker still moves so enabling an address later does not flood them
		$this->config->expects($this->once())
			->method('setUserValue')
			->with('noMailUser', 'activity', 'activity_digest_last_send', $this->anything());

		$this->digestSender->sendDigests(1700000000);
	}

	public function testCurrentUserIsResetWhenThereIsNothingToSend(): void {
		$user = $this->createUser('someUser', 'user@example.com');

		$this->config->method('getUserValue')
			->willReturn('5');
		$this->data->method('getActivitySince')
			->willReturn(['count' => 0, 'max' => 5]);

		$resetUsers = [];
		$this->activityManager->method('setCurrentUserId')
			->willReturnCallback(static function (?string $uid) use (&$resetUsers): void {
				$resetUsers[] = $uid;
			});

		$this->digestSender->sendDigestForUser($user, 1700000000, 'UTC', 'en');

		// The identity must not survive the early return
		$this->assertSame(['someUser', null], $resetUsers);
	}

	public function testGetHTMLSubjectEscapesParameters(): void {
		$this->assertSame(
			'Shared <strong>&lt;b&gt;secret&lt;/b&gt;.txt</strong>',
			$this->formatSubject(['file' => ['type' => 'file', 'path' => '<b>secret</b>.txt']]),
		);
		$this->assertSame(
			'Shared <a href="https://example.com/&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;">secret.txt</a>',
			$this->formatSubject(['file' => [
				'type' => 'file',
				'path' => 'secret.txt',
				'link' => 'https://example.com/"><script>alert(1)</script>',
			]]),
		);
	}

	protected function formatSubject(array $parameters): string {
		$event = $this->createMock(IEvent::class);
		$event->method('getRichSubject')->willReturn('Shared {file}');
		$event->method('getRichSubjectParameters')->willReturn($parameters);

		return self::invokePrivate($this->digestSender, 'getHTMLSubject', [$event]);
	}
}
