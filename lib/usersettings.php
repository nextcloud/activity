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
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity;

use \OCP\Config;

/**
 * Class UserSettings
 *
 * @package OCA\Activity
 */
class UserSettings
{
	const EMAIL_SEND_HOURLY = 0;
	const EMAIL_SEND_DAILY = 1;
	const EMAIL_SEND_WEEKLY = 2;

	public static function getUserDefaultSetting($method, $type) {
		if ($method == 'setting' && $type == 'batchtime') {
			return 3600;
		}

		$settings = self::getUserDefaultSettings($method);
		return in_array($type, $settings);
	}

	public static function getUserDefaultSettings($method) {
		$settings = array();
		switch ($method) {
			case 'stream':
				$settings[] = Data::TYPE_SHARE_CREATED;
				$settings[] = Data::TYPE_SHARE_CHANGED;
				$settings[] = Data::TYPE_SHARE_DELETED;
//				$settings[] = Data::TYPE_SHARE_RESHARED;
//
//				$settings[] = Data::TYPE_SHARE_DOWNLOADED;

			case 'email':
				$settings[] = Data::TYPE_SHARED;
//				$settings[] = Data::TYPE_SHARE_EXPIRED;
//				$settings[] = Data::TYPE_SHARE_UNSHARED;
//
//				$settings[] = Data::TYPE_SHARE_UPLOADED;
//
//				$settings[] = Data::TYPE_STORAGE_QUOTA_90;
//				$settings[] = Data::TYPE_STORAGE_FAILURE;
		}

		return $settings;
	}

	/**
	 * @param string $user
	 * @param string $method
	 * @param string $type
	 * @return string|int
	 */
	public static function getUserSetting($user, $method, $type) {
		return Config::getUserValue(
			$user,
			'activity',
			'notify_' . $method . '_' . $type,
			self::getUserDefaultSetting($method, $type)
		);
	}

	/**
	 * @param string	$user	Name of the user
	 * @param string	$method	Should be one of 'stream', 'email'
	 * @param string	$filter	Further filter the activities
	 * @return string	Part of the SQL query limiting the activities
	 */
	public static function getUserNotificationTypesQuery($user, $method, $filter) {
		$l = \OC_L10N::get('activity');
		$types = Data::getNotificationTypes($l);

		$userActivities = array();
		foreach ($types as $type => $desc) {
			if (self::getUserSetting($user, $method, $type)) {
				$userActivities[] = $type;
			}
		}

		$userActivities = Data::filterNotificationTypes($userActivities, $filter);

		// We don't want to display any activities
		if (empty($userActivities)) {
			return '1 = 0';
		}

		return "`type` IN ('" . implode("','", $userActivities) . "')";
	}
}
