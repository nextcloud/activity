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

use OCA\Activity\UserSettings;

class UserSettingsTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$preferences = array(
			array('test1', 'activity', 'notify_stream_type1', '1'),
			array('test1', 'activity', 'notify_stream_type2', '2'),
			array('test2', 'activity', 'notify_stream_type1', '0'),
			array('test2', 'activity', 'notify_stream_type2', '0'),
			array('test3', 'activity', 'notify_stream_type1', ''),
			array('test4', 'activity', 'notify_stream_type1', '3'),

			array('test1', 'activity', 'notify_email_type1', '1'),
			array('test1', 'activity', 'notify_email_type2', '2'),
			array('test2', 'activity', 'notify_email_type1', '0'),
			array('test2', 'activity', 'notify_email_type2', '0'),
			array('test3', 'activity', 'notify_email_type1', ''),
			array('test4', 'activity', 'notify_email_type1', '3'),
			array('test5', 'activity', 'notify_email_type1', '1'),

			array('test1', 'activity', 'notify_setting_batchtime', '1'),
			array('test2', 'activity', 'notify_setting_batchtime', '2'),
			array('test3', 'activity', 'notify_setting_batchtime', '3'),
			array('test4', 'activity', 'notify_setting_batchtime', '4'),
		);

		$query = \OCP\DB::prepare('INSERT INTO `*PREFIX*preferences`(`userid`, `appid`, `configkey`, `configvalue`)' . ' VALUES(?, ?, ?, ?)');
		foreach ($preferences as $preference) {
			$query->execute($preference);
		}
	}

	public function filterUsersBySettingData() {
		return array(
			array(array(), 'stream', array()),
			array(array('test', 'test1', 'test2', 'test3', 'test4'), 'stream', array('test1' => true, 'test4' => true)),
			array(array('test', 'test1', 'test2', 'test3', 'test4', 'test5'), 'email', array('test1' => '1', 'test4' => '4', 'test5' => true)),
		);
	}

	/**
	 * @dataProvider filterUsersBySettingData
	 */
	public function testFilterUsersBySetting($users, $method, $expected) {
		$this->assertEquals($expected, UserSettings::filterUsersBySetting($users, $method, 'type1'));
	}

	public function tearDown() {
		$query = \OCP\DB::prepare('DELETE FROM `*PREFIX*preferences` WHERE `appid` = ?');
		$query->execute(array('activity'));
	}
}
