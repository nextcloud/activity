<?php

/**
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity;

use OCA\Activity\Extension\Files;
use OCP\Activity\ActivitySettings;
use OCP\Activity\Exceptions\SettingNotFoundException;
use OCP\Activity\IManager;
use OCP\IConfig;

/**
 * Class UserSettings
 *
 * @package OCA\Activity
 */
class UserSettings {

	protected Data $data;
	public const EMAIL_SEND_HOURLY = 0;
	public const EMAIL_SEND_DAILY = 1;
	public const EMAIL_SEND_WEEKLY = 2;
	public const EMAIL_SEND_ASAP = 3;

	/**
	 * @param IManager $manager
	 * @param IConfig $config
	 */
	public function __construct(
		protected IManager $manager,
		protected IConfig $config,
	) {
	}

	/**
	 * Get the user setting
	 * Falling back to the admin default if not set for the user
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

		$defaultSetting = $this->getAdminSetting($method, $type);
		if (!$this->canModifySetting($method, $type)) {
			return $defaultSetting;
		}

		if (is_bool($defaultSetting)) {
			return (bool)$this->config->getUserValue(
				$user,
				'activity',
				'notify_' . $method . '_' . $type,
				$defaultSetting
			);
		}

		return (int)$this->config->getUserValue(
			$user,
			'activity',
			'notify_' . $method . '_' . $type,
			$defaultSetting
		);
	}

	/**
	 * Get the admin configured default for the setting
	 * Falling back to the implementation default if not set by the admin
	 *
	 * @param string $method
	 * @param string $type
	 * @return bool|int
	 */
	public function getAdminSetting($method, $type) {
		$defaultSetting = $this->getDefaultSetting($method, $type);
		if (is_bool($defaultSetting)) {
			return (bool)$this->config->getAppValue(
				'activity',
				'notify_' . $method . '_' . $type,
				(string)$defaultSetting
			);
		}

		return (int)$this->config->getAppValue(
			'activity',
			'notify_' . $method . '_' . $type,
			(string)$defaultSetting
		);
	}

	/**
	 * Get default setting for a preference from the implementation
	 *
	 * @param string $method Should be one of 'stream', 'email' or 'setting'
	 * @param string $type One of the activity types, 'batchtime', 'self' or 'selfemail'
	 * @return bool|int
	 */
	protected function getDefaultSetting($method, $type) {
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
			switch ($method) {
				case 'email':
					return $setting->isDefaultEnabledMail();
				case 'notification':
					return $setting->isDefaultEnabledNotification();
				default:
					return false;
			}
		} catch (SettingNotFoundException) {
			return false;
		}
	}

	/**
	 * Get a good default setting for a preference
	 *
	 * @param string $method Should be one of 'stream', 'email' or 'setting'
	 * @param string $type One of the activity types, 'batchtime', 'self' or 'selfemail'
	 * @return bool
	 */
	protected function canModifySetting($method, $type) {
		if ($method === 'setting') {
			return true;
		}

		try {
			$setting = $this->manager->getSettingById($type);
			switch ($method) {
				case 'email':
					return $setting->canChangeMail();
				case 'notification':
					return $setting->canChangeNotification();
				default:
					return false;
			}
		} catch (SettingNotFoundException) {
			return false;
		}
	}

	/**
	 * Get a list with all notification types
	 */
	public function getNotificationTypes() {
		$settings = $this->manager->getSettings();

		$return = array_map(function (ActivitySettings $setting) {
			return $setting->getIdentifier();
		}, $settings);

		// TYPE_FILE_CHANGED is used to group all file changes together
		// But we still differentiate between file_created, file_deleted and file_restored
		// so let's add them to the list.
		if (array_search(Files::TYPE_FILE_CHANGED, $return) !== false) {
			array_push($return, Files::TYPE_SHARE_CREATED, Files::TYPE_SHARE_DELETED, Files::TYPE_SHARE_RESTORED);
		}

		return $return;
	}

	/**
	 * Filters the given user array by their notification setting
	 *
	 * @param array $users
	 * @param string $method
	 * @param string $type
	 * @return array Returns a "username => b:true" Map for method = notification
	 *               Returns a "username => i:batchtime" Map for method = email
	 */
	public function filterUsersBySetting($users, $method, $type) {
		if (empty($users)) {
			return [];
		}

		if ($method === 'email' && $this->config->getAppValue('activity', 'enable_email', 'yes') === 'no') {
			return [];
		}

		// file_created, file_deleted and file_restored are grouped under file_changed
		if ($type === Files::TYPE_SHARE_CREATED || $type === Files::TYPE_SHARE_DELETED || $type === Files::TYPE_SHARE_RESTORED) {
			$type = Files::TYPE_FILE_CHANGED;
		}

		$filteredUsers = [];
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
		if ($this->getDefaultSetting($method, $type)) {
			foreach ($users as $user) {
				if ($method === 'notification') {
					$filteredUsers[$user] = true;
				} else {
					$filteredUsers[$user] = $this->getDefaultSetting('setting', 'batchtime');
				}
			}
		}

		return $filteredUsers;
	}
}
