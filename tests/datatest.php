<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace OCA\Activity\Tests;

use OC\ActivityManager;
use OCA\Activity\Data;
use OCA\Activity\Extension\Files;

class DataTest extends TestCase {
	/** @var \OCA\Activity\Data */
	protected $data;

	/** @var \OCP\IL10N */
	protected $activityLanguage;

	protected function setUp() {
		parent::setUp();

		$this->activityLanguage = $activityLanguage = \OCP\Util::getL10N('activity', 'en');
		$activityManager = new ActivityManager();
		$activityManager->registerExtension(function() use ($activityLanguage) {
			return new Files($activityLanguage, $this->getMock('\OCP\IURLGenerator'));
		});
		$this->data = new Data($activityManager);
	}

	public function dataGetNotificationTypes() {
		return [
			[Data::TYPE_SHARE_CREATED],
			[Data::TYPE_SHARE_CHANGED],
			[Data::TYPE_SHARE_DELETED],
			[Data::TYPE_SHARE_RESTORED],
			[Data::TYPE_SHARED],
		];
	}

	/**
	 * @dataProvider dataGetNotificationTypes
	 * @param string $typeKey
	 */
	public function testGetNotificationTypes($typeKey) {
		$this->assertArrayHasKey($typeKey, $this->data->getNotificationTypes($this->activityLanguage));
		// Check cached version aswell
		$this->assertArrayHasKey($typeKey, $this->data->getNotificationTypes($this->activityLanguage));
	}

	public function getFilterFromParamData() {
		return array(
			array('test', 'all'),
			array('all', 'all'),
			array('by', 'by'),
			array('files', 'files'),
			array('self', 'self'),
			array('shares', 'shares'),
			array('test', 'all'),
			array(null, 'all'),
		);
	}

	/**
	 * @dataProvider getFilterFromParamData
	 *
	 * @param string $globalValue
	 * @param string $expected
	 */
	public function testGetFilterFromParam($globalValue, $expected) {
		if ($globalValue !== null) {
			$_GET['filter'] = $globalValue;
		}

		$this->assertEquals(
			$expected,
			$this->data->getFilterFromParam()
		);
	}
}
