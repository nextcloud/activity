<?php

declare(strict_types=1);
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

use OCA\Activity\Consumer;
use OCA\Activity\Data;
use OCA\Activity\NotificationGenerator;
use OCA\Activity\UserSettings;
use OCP\Activity\ActivitySettings;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Config\IUserConfig;
use OCP\IDBConnection;
use OCP\IL10N;
use OCP\L10N\IFactory;
use OCP\Server;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ConsumerTest
 *
 * @group DB
 * @package OCA\Activity\Tests
 */
class ConsumerTest extends TestCase {
	protected Data&MockObject $data;
	protected MockObject&IFactory $l10nFactory;
	protected IManager&MockObject $activityManager;
	protected NotificationGenerator&MockObject $notificationGenerator;
	protected UserSettings $userSettings;
	private IUserConfig&MockObject $userConfig;
	private IEvent $event;
	private Consumer $consumer;

	protected function setUp(): void {
		parent::setUp();
		$this->deleteTestActivities();

		$this->activityManager = $this->createMock(IManager::class);
		$this->data = $this->createMock(Data::class);
		$this->userSettings = $this->getMockBuilder(UserSettings::class)
			->onlyMethods(['getUserSetting'])
			->disableOriginalConstructor()
			->getMock();
		$l10n = $this->createMock(IL10N::class);
		$this->notificationGenerator = $this->createMock(NotificationGenerator::class);
		$this->l10nFactory = $this->createMock(IFactory::class);
		$this->userConfig = $this->createMock(IUserConfig::class);

		$this->data->method('send')
			->willReturn(1);
		$this->l10nFactory
			->method('get')
			->with('activity')
			->willReturn($l10n);
		$this->userSettings
			->method('getUserSetting')
			->with($this->stringContains('affectedUser'), $this->anything(), $this->anything())
			->willReturnMap([
				['affectedUser', 'notification', 'type', true],
				['affectedUser2', 'notification', 'type', true],
				['affectedUser', 'email', 'type', true],
				['affectedUser2', 'email', 'type', true],
				['affectedUser', 'setting', 'batchtime', 10],
				['affectedUser2', 'setting', 'batchtime', 10],
			]);

		$this->consumer = new Consumer(
			$this->data,
			$this->activityManager,
			$this->userSettings,
			$this->notificationGenerator,
			$this->userConfig,
		);

		$this->event = Server::get(IManager::class)->generateEvent();
	}

	protected function tearDown(): void {
		$this->deleteTestActivities();
		parent::tearDown();
	}

	protected function deleteTestActivities(): void {
		$query = Server::get(IDBConnection::class)->getQueryBuilder();
		$query->delete('activity')
			->where($query->expr()->eq(
				'app', $query->createNamedParameter('test')
			));
		$query->executeStatement();

		$query = Server::get(IDBConnection::class)->getQueryBuilder();
		$query->delete('activity_mq')
			->where($query->expr()->eq(
				'amq_appid', $query->createNamedParameter('test')
			));
		$query->executeStatement();
	}

	public static function receiveData(): array {
		return [
			['type', 'author', 'affectedUser', 'subject', 'affectedUser'],
			['type2', 'author', 'affectedUser', 'subject', false],
			['type', 'author', 'affectedUser', 'subject_self', 'affectedUser'],
			['type', 'author', 'affectedUser2', 'subject_self', 'affectedUser2'],
			['type', 'author', 'affectedUser', 'subject2', 'affectedUser'],
			['type', 'author', 'affectedUser2', 'subject2', 'affectedUser2'],
			['type', 'affectedUser', 'affectedUser', 'subject_self', 'affectedUser'],
			['type', 'affectedUser2', 'affectedUser2', 'subject_self', false],
			['type', 'affectedUser', 'affectedUser', 'subject2', 'affectedUser'],
			['type', 'affectedUser2', 'affectedUser2', 'subject2', false],
		];
	}

	/**
	 * @dataProvider receiveData
	 *
	 * @param string $type
	 * @param string $author
	 * @param string $affectedUser
	 * @param string $subject
	 * @param array|false $expected
	 */
	public function testReceiveStream(string $type, string $author, string $affectedUser, string $subject, $expected): void {
		$consumer = new Consumer($this->data,
			$this->activityManager,
			$this->userSettings,
			$this->notificationGenerator,
			$this->userConfig,
		);
		$event = Server::get(IManager::class)->generateEvent();
		$event->setApp('test')
			->setType($type)
			->setAffectedUser($affectedUser)
			->setAuthor($author)
			->setTimestamp(time())
			->setSubject($subject, ['subjectParam1', 'subjectParam2'])
			->setMessage('message', ['messageParam1', 'messageParam2'])
			->setObject('', 0, 'file')
			->setLink('link');
		$this->deleteTestActivities();

		$this->data->expects($this->once())
			->method('send');

		$consumer->receive($event);
	}

	/**
	 * @dataProvider receiveData
	 *
	 * @param string $type
	 * @param string $author
	 * @param string $affectedUser
	 * @param string $subject
	 * @param array|false $expected
	 */
	public function testReceiveEmail(string $type, string $author, string $affectedUser, string $subject, $expected): void {
		$time = time();
		$this->event->setApp('test')
			->setType($type)
			->setAffectedUser($affectedUser)
			->setAuthor($author)
			->setTimestamp($time)
			->setSubject($subject, ['subjectParam1', 'subjectParam2'])
			->setMessage('message', ['messageParam1', 'messageParam2'])
			->setObject('', 0, 'file')
			->setLink('link');

		if ($expected === false || $author === $affectedUser) {
			$this->data->expects($this->never())
				->method('storeMail');
		} else {
			$this->data->expects($this->once())
				->method('storeMail')
				->with($this->event, $time + 10);
		}

		$this->consumer->receive($this->event);
	}

	/**
	 * @dataProvider receiveData
	 *
	 * @param string $type
	 * @param string $author
	 * @param string $affectedUser
	 * @param string $subject
	 * @param array|false $expected
	 */
	public function testReceiveNotification(string $type, string $author, string $affectedUser, string $subject, $expected): void {
		$this->event->setApp('test')
			->setType($type)
			->setAffectedUser($affectedUser)
			->setAuthor($author)
			->setTimestamp(time())
			->setSubject($subject, ['subjectParam1', 'subjectParam2'])
			->setMessage('message', ['messageParam1', 'messageParam2'])
			->setObject('', 0, 'file')
			->setLink('link');
		$this->deleteTestActivities();

		if ($expected === false || $author === $affectedUser) {
			$this->notificationGenerator->expects($this->never())
				->method('sendNotificationForEvent');
		} else {
			$this->notificationGenerator->expects($this->once())
				->method('sendNotificationForEvent');
		}

		$this->consumer->receive($this->event);
	}

	public function receiveBulkData(): array {
		return [
			'empty affected users' => ['type', 'author', 'subject', [], null, false],
			'empty activity ids' => ['type', 'author', 'subject', ['affectedUser', 'affectedUser1'], [], false],
			'notification for non-author user' => ['type', 'author', 'subject', ['affectedUser'], [1 => 'affectedUser'], true],
			'no notification when author is the affected user' => ['type', 'author', 'subject', ['author'], [1 => 'author'], false],
		];
	}

	/**
	 * @dataProvider receiveBulkData
	 */
	public function testBulkReceiveNotification(string $type, string $author, string $subject, array $affectedUsers, ?array $activityIds, bool $expectNotification): void {
		$this->event->setApp('activity')
			->setType($type)
			->setAuthor($author)
			->setTimestamp(time())
			->setSubject($subject, ['subjectParam1', 'subjectParam2'])
			->setMessage('message', ['messageParam1', 'messageParam2'])
			->setObject('', 0, 'file')
			->setLink('link');

		$settings = $this->createMock(ActivitySettings::class);
		$settings->method('canChangeMail')->willReturn(false);
		$settings->method('isDefaultEnabledMail')->willReturn(false);
		$settings->method('canChangeNotification')->willReturn(true);

		if (empty($affectedUsers)) {
			$this->data->expects($this->never())
				->method('bulkSend');
			$this->data->expects($this->never())
				->method('storeMail');
			$this->notificationGenerator->expects($this->never())
				->method('sendNotificationForEvent');
		} else {
			$this->data->expects($this->once())
				->method('bulkSend')
				->willReturn($activityIds ?? []);

			if ($expectNotification) {
				$this->userConfig->method('getValuesByUsers')
					->willReturnCallback(function (string $app, string $key, mixed $type, array $users): array {
						return array_fill_keys($users, true);
					});
				$this->notificationGenerator->expects($this->atLeastOnce())
					->method('sendNotificationForEvent');
			} else {
				$this->notificationGenerator->expects($this->never())
					->method('sendNotificationForEvent');
			}
		}

		$this->consumer->bulkReceive($this->event, $affectedUsers, $settings);
	}

}
