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

namespace OCA\Activity;

use OCP\Activity\IManager;
use OCP\IConfig;

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
	const EMAIL_SEND_ASAP = 3;

	/**
	 * @param IManager $manager
	 * @param IConfig $config
	 */
	public function __construct(IManager $manager, IConfig $config) {
		$this->manager = $manager;
		$this->config = $config;
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
		if ($method === 'email' && $this->config->getAppValue('activity', 'enable_email', 'yes') === 'no') {
			return false;
		}

		$defaultSetting = $this->getDefaultFromSetting($method, $type);
		if (is_bool($defaultSetting)) {
			return (bool) $this->config->getUserValue(
				$user,
				'activity',
				'notify_' . $method . '_' . $type,
				$defaultSetting
			);
		}

		return (int) $this->config->getUserValue(
			$user,
			'activity',
			'notify_' . $method . '_' . $type,
			$defaultSetting
		);
	}

	/**
	 * @param string $method
	 * @param string $type
	 * @return bool|int
	 */
	public function getConfigSetting($method, $type) {
		$defaultSetting = $this->getDefaultFromSetting($method, $type);
		if (is_bool($defaultSetting)) {
			return (bool) $this->config->getAppValue(
				'activity',
				'notify_' . $method . '_' . $type,
				$defaultSetting
			);
		}

		return (int) $this->config->getAppValue(
			'activity',
			'notify_' . $method . '_' . $type,
			$defaultSetting
		);
	}

	/**
	 * Get a good default setting for a preference
	 *
	 * @param string $method Should be one of 'stream', 'email' or 'setting'
	 * @param string $type One of the activity types, 'batchtime', 'self' or 'selfemail'
	 * @return bool|int
	 */
	protected function getDefaultFromSetting($method, $type) {
		if ($method === 'setting') {
			if ($type === 'batchtime') {
				return 3600;
			}

			if ($type === 'self') {
				return true;
			}

			if ($type === 'selfemail') {
				return false;
			}

			return false;
		}

		try {
			$setting = $this->manager->getSettingById($type);
			return ($method === 'stream') ? $setting->isDefaultEnabledStream() : $setting->isDefaultEnabledMail();
		} catch (\InvalidArgumentException $e) {
			return false;
		}
	}

	/**
	 * Get a list with enabled notification types for a user
	 *
	 * @param string	$user	Name of the user
	 * @param string	$method	Should be one of 'stream' or 'email'
	 * @return array
	 */
	public function getNotificationTypes($user, $method) {
		$notificationTypes = array();

		$settings = $this->manager->getSettings();
		foreach ($settings as $setting) {
			if ($this->getUserSetting($user, $method, $setting->getIdentifier())) {
				$notificationTypes[] = $setting->getIdentifier();
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
			return [];
		}

		if ($method === 'email' && $this->config->getAppValue('activity', 'enable_email', 'yes') === 'no') {
			return [];
		}

		$filteredUsers = array();
		$potentialUsers = $this->config->getUserValueForUsers('activity', 'notify_' . $method . '_' . $type, $users);
		foreach ($potentialUsers as $user => $value) {
			if ($value) {
				$filteredUsers[$user] = true;
			}
			unset($users[array_search($user, $users, true)]);
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
		if ($this->getDefaultFromSetting($method, $type)) {
			foreach ($users as $user) {
				if ($method === 'stream') {
					$filteredUsers[$user] = true;
				} else {
					$filteredUsers[$user] = $this->getDefaultFromSetting('setting', 'batchtime');
				}
			}
		}

		return $filteredUsers;
	}
}
