<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests;

use OCA\Activity\Data;
use OCA\Activity\NotificationGenerator;
use OCA\Activity\UserSettings;
use OCP\Activity\IEvent;
use OCP\Activity\IManager as ActivityManager;
use OCP\Activity\IProvider;
use OCP\IL10N;
use OCP\Notification\IManager as NotificationManager;
use OCP\Notification\INotification;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class NotificationGeneratorTest extends TestCase {
	protected Data&MockObject $data;
	protected ActivityManager&MockObject $activityManager;
	protected NotificationGenerator $generator;

	protected function setUp(): void {
		parent::setUp();

		$this->data = $this->createMock(Data::class);
		$this->activityManager = $this->createMock(ActivityManager::class);

		$this->generator = new NotificationGenerator(
			$this->data,
			$this->activityManager,
			$this->createMock(NotificationManager::class),
			$this->createMock(UserSettings::class),
			$this->createMock(IL10N::class),
			$this->createMock(LoggerInterface::class),
		);
	}

	public function testCurrentUserIsResetWhenAProviderThrows(): void {
		$notification = $this->createMock(INotification::class);
		$notification->method('getObjectType')->willReturn('activity_notification');
		$notification->method('getObjectId')->willReturn('42');
		$notification->method('getUser')->willReturn('affected');

		$event = $this->createMock(IEvent::class);
		$event->method('getAffectedUser')->willReturn('affected');
		$this->data->method('getById')->willReturn($event);

		$provider = $this->createMock(IProvider::class);
		$provider->method('parse')
			->willThrowException(new \RuntimeException('provider exploded'));
		$this->activityManager->method('getProviders')
			->willReturn([$provider]);

		$seenUsers = [];
		$this->activityManager->method('setCurrentUserId')
			->willReturnCallback(static function (?string $uid) use (&$seenUsers): void {
				$seenUsers[] = $uid;
			});

		try {
			$this->generator->prepare($notification, 'en');
			$this->fail('Expected the provider exception to propagate');
		} catch (\RuntimeException) {
		}

		// The identity must not survive an exploding provider
		$this->assertSame(['affected', null], $seenUsers);
	}
}
