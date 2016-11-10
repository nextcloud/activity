<?php
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

use OCA\Activity\UserSettings;
use OCP\Activity\IManager;
use OCP\Activity\ISetting;
use OCP\IConfig;

class UserSettingsTest extends TestCase {
	/** @var UserSettings */
	protected $userSettings;

	/** @var IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $activityManager;
	/** @var IConfig|\PHPUnit_Framework_MockObject_MockObject */
	protected $config;

	protected function setUp() {
		parent::setUp();
		$this->activityManager = $this->createMock(IManager::class);
		$this->config = $this->createMock(IConfig::class);
		$this->userSettings = new UserSettings($this->activityManager, $this->config);
	}

	public function getDefaultSettingData() {
		return [
			['stream', 'type1', true],
			['email', 'type1', false],
			['setting', 'self', true],
			['setting', 'selfemail', false],
			['setting', 'batchtime', 3600],
			['setting', 'not-exists', false],
		];
	}

	/**
	 * @dataProvider getDefaultSettingData
	 *
	 * @param string $method
	 * @param string $type
	 * @param mixed $expected
	 */
	public function testGetDefaultSetting($method, $type, $expected) {
		if ($method !== 'setting') {
			if ($type === 'not-exists') {
				$this->activityManager->expects($this->once())
					->method('getSettingById')
					->with($type)
					->willThrowException(new \InvalidArgumentException());
			} else {
				$s = $this->createMock(ISetting::class);
				$s->expects($method === 'stream' ? $this->once() : $this->never())
					->method('isDefaultEnabledStream')
					->willReturn($expected);
				$s->expects($method === 'email' ? $this->once() : $this->never())
					->method('isDefaultEnabledMail')
					->willReturn($expected);
				$this->activityManager->expects($this->once())
					->method('getSettingById')
					->with($type)
					->willReturn($s);
			}

		}
		$this->assertEquals($expected, $this->userSettings->getDefaultSetting($method, $type));
	}
}
