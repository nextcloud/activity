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
use OCP\IURLGenerator;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IMailer;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LoggerInterface;

class DigestSenderTest extends TestCase {
	private DigestSender $digestSender;

	protected function setUp(): void {
		parent::setUp();

		$this->digestSender = new DigestSender(
			$this->createMock(IConfig::class),
			$this->createMock(Data::class),
			$this->createMock(UserSettings::class),
			$this->createMock(GroupHelper::class),
			$this->createMock(IMailer::class),
			$this->createMock(IManager::class),
			$this->createMock(IUserManager::class),
			$this->createMock(IURLGenerator::class),
			$this->createMock(Defaults::class),
			$this->createMock(IFactory::class),
			$this->createMock(IDateTimeFormatter::class),
			$this->createMock(LoggerInterface::class),
		);
	}

	public static function provideGetHTMLSubjectData(): array {
		return [
			'no rich subject escapes parsed subject' => [
				'', 'Hello <World>', [],
				'Hello &lt;World&gt;',
			],
			'linked parameter renders anchor' => [
				'File {file} was shared', '',
				['file' => ['type' => 'file', 'path' => 'photo.jpg', 'name' => 'photo.jpg', 'link' => 'https://cloud.example.com/f/123']],
				'File <a href="https://cloud.example.com/f/123">photo.jpg</a> was shared',
			],
			'double-quote in link is escaped' => [
				'File {file} was shared', '',
				['file' => ['type' => 'file', 'path' => 'photo.jpg', 'name' => 'photo.jpg', 'link' => 'https://cloud.example.com/f/123"onmouseover="alert(1)']],
				'File <a href="https://cloud.example.com/f/123&quot;onmouseover=&quot;alert(1)">photo.jpg</a> was shared',
			],
			'parameter without link uses strong tag' => [
				'File {file} was shared', '',
				['file' => ['type' => 'file', 'path' => 'photo.jpg', 'name' => 'photo.jpg']],
				'File <strong>photo.jpg</strong> was shared',
			],
		];
	}

	#[DataProvider('provideGetHTMLSubjectData')]
	public function testGetHTMLSubject(string $richSubject, string $parsedSubject, array $richParams, string $expected): void {
		$event = $this->createMock(IEvent::class);
		$event->method('getRichSubject')->willReturn($richSubject);
		$event->method('getParsedSubject')->willReturn($parsedSubject);
		$event->method('getRichSubjectParameters')->willReturn($richParams);

		$result = self::invokePrivate($this->digestSender, 'getHTMLSubject', [$event]);
		$this->assertSame($expected, $result);
	}
}
