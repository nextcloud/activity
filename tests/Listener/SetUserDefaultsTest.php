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
use OCP\IAppConfig;
use OCP\IConfig;
use OCP\IUser;
use OCP\User\Events\PostLoginEvent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SetUserDefaultsTest extends TestCase {
	private IConfig&MockObject $config;
	private IAppConfig&MockObject $appConfig;
	private SetUserDefaults $listener;
	private PostLoginEvent $event;

	public const UID = 'myuser';

	public function setUp(): void {
		parent::setUp();
		$this->config = $this->createMock(IConfig::class);
		$this->appConfig = $this->createMock(IAppConfig::class);
		$user = $this->createMock(IUser::class);
		$user->expects($this->atLeast(1))->method('getUID')->willReturn(self::UID);
		$this->event = new PostLoginEvent($user, self::UID, 'somepassword', true);

		$this->listener = new SetUserDefaults($this->config, $this->appConfig);
	}

	public function testSettingUserDefaultsAlreadyConfigured(): void {
		$this->config->expects($this->once())->method('getUserValue')->with(self::UID, 'activity', 'configured', 'no')->willReturn('yes');
		$this->appConfig->expects($this->never())->method('getKeys');
		$this->listener->handle($this->event);
	}

	#[DataProvider('dataForTestSettingUserDefaultsNotConfigured')]
	public function testSettingUserDefaultsNotConfigured(string $key, array $getUserValueArgs, array $getUserValueReturns, array $setUserValuesArgs): void {
		$matcher = $this->exactly(count($getUserValueArgs));
		$this->config
			->expects($matcher)
			->method('getUserValue')
			->willReturnCallback(function () use ($matcher, $getUserValueReturns) {
				$invocation = $matcher->numberOfInvocations();
				return $getUserValueReturns[$invocation - 1];
			});
		$matcher = $this->exactly(count($setUserValuesArgs));
		$this->config
			->expects($matcher)
			->method('setUserValue')
			->willReturnCallback(function () use ($matcher, $setUserValuesArgs) {
				$invocation = $matcher->numberOfInvocations();
				return $setUserValuesArgs[$invocation - 1];
			});
		$this->appConfig
			->expects($this->exactly(count($setUserValuesArgs) > 1 ? 1 : 0))
			->method('getValueString')
			->with('activity', $key, '')
			->willReturn('defaultAppValue');
		$this->appConfig
			->expects($this->once())
			->method('getKeys')
			->willReturn([$key]);

		$this->listener->handle($this->event);
	}

	public static function dataForTestSettingUserDefaultsNotConfigured(): array {
		return [
			[
				'not_notify',
				[[self::UID, 'activity', 'configured', 'no']],
				['no'],
				[[self::UID, 'activity', 'configured', 'yes']]
			],
			[
				'notify_something',
				[[self::UID, 'activity', 'configured', 'no'], [self::UID, 'activity', 'notify_something', null]],
				['no', 'not_null'],
				[[self::UID, 'activity', 'configured', 'yes']]
			],
			[
				'notify_something',
				[[self::UID, 'activity', 'configured', 'no'], [self::UID, 'activity', 'notify_something', null]],
				['no', null],
				[[self::UID, 'activity', 'notify_something', 'defaultAppValue'], [self::UID, 'activity', 'configured', 'yes']]
			],
		];
	}
}
