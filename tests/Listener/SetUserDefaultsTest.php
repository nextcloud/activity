<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2021, Thomas Citharel <nextcloud@tcit.fr>
 *
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

namespace OCA\Activity\Tests\Listener;

use OCA\Activity\Listener\SetUserDefaults;
use OCP\Config\IUserConfig;
use OCP\IAppConfig;
use OCP\IUser;
use OCP\User\Events\PostLoginEvent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SetUserDefaultsTest extends TestCase {
	private IUserConfig&MockObject $userConfig;
	private IAppConfig&MockObject $appConfig;
	private SetUserDefaults $listener;
	private PostLoginEvent $event;

	public const UID = 'myuser';

	public function setUp(): void {
		$this->userConfig = $this->createMock(IUserConfig::class);
		$this->appConfig = $this->createMock(IAppConfig::class);
		$user = $this->createMock(IUser::class);
		$user->expects($this->atLeast(1))->method('getUID')->willReturn(self::UID);
		$this->event = new PostLoginEvent($user, self::UID, 'somepassword', true);

		$this->listener = new SetUserDefaults($this->appConfig, $this->userConfig);
	}

	public function testSettingUserDefaultsAlreadyConfigured(): void {
		$this->userConfig->expects($this->once())->method('getValueString')->with(self::UID, 'activity', 'configured', 'no')->willReturn('yes');
		$this->appConfig->expects($this->never())->method('getKeys');
		$this->listener->handle($this->event);
	}

	#[DataProvider('dataForTestSettingUserDefaultsNotConfigured')]
	public function testSettingUserDefaultsNotConfigured(string $key, ?bool $hasKey, array $setValueStringArgs): void {
		$this->userConfig
			->expects($this->once())
			->method('getValueString')
			->with(self::UID, 'activity', 'configured', 'no')
			->willReturn('no');
		$this->userConfig
			->expects($hasKey === null ? $this->never() : $this->once())
			->method('hasKey')
			->with(self::UID, 'activity', $key)
			->willReturn((bool)$hasKey);
		$calls = [];
		$this->userConfig
			->expects($this->exactly(count($setValueStringArgs)))
			->method('setValueString')
			->willReturnCallback(function (string $userId, string $app, string $key, string $value) use (&$calls): bool {
				$calls[] = [$userId, $app, $key, $value];
				return true;
			});
		$this->appConfig
			->expects($this->exactly(count($setValueStringArgs) > 1 ? 1 : 0))
			->method('getValueString')
			->with('activity', $key, '')
			->willReturn('defaultAppValue');
		$this->appConfig
			->expects($this->once())
			->method('getKeys')
			->willReturn([$key]);

		$this->listener->handle($this->event);

		$this->assertSame($setValueStringArgs, $calls);
	}

	public static function dataForTestSettingUserDefaultsNotConfigured(): array {
		return [
			[
				'not_notify',
				null,
				[[self::UID, 'activity', 'configured', 'yes']]
			],
			[
				'notify_something',
				true,
				[[self::UID, 'activity', 'configured', 'yes']]
			],
			[
				'notify_something',
				false,
				[[self::UID, 'activity', 'notify_something', 'defaultAppValue'], [self::UID, 'activity', 'configured', 'yes']]
			],
		];
	}
}
