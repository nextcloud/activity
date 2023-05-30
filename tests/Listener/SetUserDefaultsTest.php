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
use OCP\IConfig;
use OCP\IUser;
use OCP\User\Events\PostLoginEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SetUserDefaultsTest extends TestCase {
	/**
	 * @var IConfig|MockObject
	 */
	private $config;

	/**
	 * @var SetUserDefaults
	 */
	private $listener;

	/** @var IUser|MockObject */
	private $user;

	/** @var PostLoginEvent */
	private $event;

	public const UID = 'myuser';

	public function setUp(): void {
		parent::setUp();
		$this->config = $this->createMock(IConfig::class);
		$this->user = $this->createMock(IUser::class);
		$this->user->expects($this->atLeast(1))->method('getUID')->willReturn(self::UID);
		$this->event = new PostLoginEvent($this->user, self::UID, 'somepassword', true);

		$this->listener = new SetUserDefaults($this->config);
	}

	public function testSettingUserDefaultsAlreadyConfigured(): void {
		$this->config->expects($this->once())->method('getUserValue')->with(self::UID, 'activity', 'configured', 'no')->willReturn('yes');
		$this->config->expects($this->never())->method('getAppKeys');
		$this->listener->handle($this->event);
	}

	/**
	 * @dataProvider dataForTestSettingUserDefaultsNotConfigured
	 */
	public function testSettingUserDefaultsNotConfigured(string $key, array $getUserValueArgs, array $getUserValueReturns, array $setUserValuesArgs): void {
		$this->config->expects($this->exactly(count($getUserValueArgs)))->method('getUserValue')->withConsecutive(...$getUserValueArgs)->willReturnOnConsecutiveCalls(...$getUserValueReturns);
		$this->config->expects($this->exactly(count($setUserValuesArgs)))->method('setUserValue')->withConsecutive(...$setUserValuesArgs);
		$this->config->expects($this->exactly(count($setUserValuesArgs) > 1 ? 1 : 0))->method('getAppValue')->with('activity', $key)->willReturn('defaultAppValue');
		$this->config->expects($this->once())->method('getAppKeys')->willReturn([$key]);

		$this->listener->handle($this->event);
	}

	public function dataForTestSettingUserDefaultsNotConfigured(): array {
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
