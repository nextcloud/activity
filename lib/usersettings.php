<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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

namespace OCA\Activity;

use OCP\Activity\IManager;
use OCP\IConfig;
use OCP\Util;

/**
 * Class UserSettings
 *
 * @package OCA\Activity
 */
class UserSettings {
	/** @var IManager */
	protected $manager;

	/** @var IConfig */
	protected $config;

	/** @var Data */
	protected $data;

	const EMAIL_SEND_HOURLY = 0;
	const EMAIL_SEND_DAILY = 1;
	const EMAIL_SEND_WEEKLY = 2;

	/**
	 * @param IManager $manager
	 * @param IConfig $config
	 * @param Data $data
	 */
	public function __construct(IManager $manager, IConfig $config, Data $data) {
		$this->manager = $manager;
		$this->config = $config;
		$this->data = $data;
	}

	/**
	 * Get a setting for a user
	 *
	 * Falls back to some good default values if the user does not have a preference
	 *
	 * @param string $user
	 * @param string $method Should be one of 'stream', 'email' or 'setting'
	 * @param string $type One of the activity types, 'batchtime' or 'self'
	 * @return bool|int
	 */
	public function getUserSetting($user, $method, $type) {
		$defaultSetting = $this->getDefaultSetting($method, $type);
		if (is_bool($defaultSetting)) {
			return (bool) $this->config->getUserValue(
				$user,
				'activity',
				'notify_' . $method . '_' . $type,
				$defaultSetting
			);
		} else {
			return (int) $this->config->getUserValue(
				$user,
				'activity',
				'notify_' . $method . '_' . $type,
				$defaultSetting
			);
		}
	}

	/**
	 * Get a good default setting for a preference
	 *
	 * @param string $method Should be one of 'stream', 'email' or 'setting'
	 * @param string $type One of the activity types, 'batchtime', 'self' or 'selfemail'
	 * @return bool|int
	 */
	public function getDefaultSetting($method, $type) {
		if ($method === 'setting') {
			if ($type === 'batchtime') {
				return 3600;
			} else if ($type === 'self') {
				return true;
			} else if ($type === 'selfemail') {
				return false;
			}
		}

		$settings = $this->manager->getDefaultTypes($method);
		return in_array($type, $settings);
	}

	/**
	 * Get a list with enabled notification types for a user
	 *
	 * @param string	$user	Name of the user
	 * @param string	$method	Should be one of 'stream' or 'email'
	 * @return array
	 */
	public function getNotificationTypes($user, $method) {
		$l = Util::getL10N('activity');
		$types = $this->data->getNotificationTypes($l);

		$notificationTypes = array();
		foreach ($types as $type => $desc) {
			if ($this->getUserSetting($user, $method, $type)) {
				$notificationTypes[] = $type;
			}
		}

		return $notificationTypes;
	}

	/**
	 * Filters the given user array by their notification setting
	 *
	 * @param array $users
	 * @param string $method
	 * @param string $type
	 * @return array Returns a "username => b:true" Map for method = stream
	 *               Returns a "username => i:batchtime" Map for method = email
	 */
	public function filterUsersBySetting($users, $method, $type) {
		if (empty($users) || !is_array($users)) {
			return array();
		}

		$filteredUsers = array();
		$potentialUsers = $this->config->getUserValueForUsers('activity', 'notify_' . $method . '_' . $type, $users);
		foreach ($potentialUsers as $user => $value) {
			if ($value) {
				$filteredUsers[$user] = true;
			}
			unset($users[array_search($user, $users)]);
		}

		// Get the batch time setting from the database
		if ($method === 'email') {
			$potentialUsers = $this->config->getUserValueForUsers('activity', 'notify_setting_batchtime', array_keys($filteredUsers));
			foreach ($potentialUsers as $user => $value) {
				$filteredUsers[$user] = $value;
			}
		}

		if (empty($users)) {
			return $filteredUsers;
		}

		// If the setting is enabled by default,
		// we add all users that didn't set the preference yet.
		if ($this->getDefaultSetting($method, $type)) {
			foreach ($users as $user) {
				if ($method === 'stream') {
					$filteredUsers[$user] = true;
				} else {
					$filteredUsers[$user] = $this->getDefaultSetting('setting', 'batchtime');
				}
			}
		}

		return $filteredUsers;
	}
}
