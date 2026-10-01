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
use OCP\Config\IUserConfig;
use OCP\IAppConfig;

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

	public const BATCH_TIME_HOURLY = 3600;
	public const BATCH_TIME_DAILY = 3600 * 24;
	public const BATCH_TIME_WEEKLY = 3600 * 24 * 7;

	public function __construct(
		protected IManager $manager,
		private readonly IAppConfig $appConfig,
		private readonly IUserConfig $userConfig,
	) {
	}

	/**
	 * Get the user setting
	 * Falling back to the admin default if not set for the user
	 *
	 * Falls back to some good default values if the user does not have a preference
	 *
	 * @param string $method Should be one of 'stream', 'email' or 'setting'
	 * @param string $type One of the activity types, 'batchtime' or 'self'
	 */
	public function getUserSetting(string $user, string $method, string $type): bool|int {
		if ($method === 'email' && $this->appConfig->getValueString('activity', 'enable_email', 'yes') === 'no') {
			return false;
		}

		$defaultSetting = $this->getAdminSetting($method, $type);
		if (!$this->canModifySetting($method, $type)) {
			return $defaultSetting;
		}

		if (is_bool($defaultSetting)) {
			return (bool)$this->userConfig->getValueString(
				$user,
				'activity',
				'notify_' . $method . '_' . $type,
				(string)(int)$defaultSetting
			);
		}

		return (int)$this->userConfig->getValueString(
			$user,
			'activity',
			'notify_' . $method . '_' . $type,
			(string)$defaultSetting
		);
	}

	/**
	 * Get the admin configured default for the setting
	 * Falling back to the implementation default if not set by the admin
	 */
	public function getAdminSetting(string $method, string $type): bool|int {
		$defaultSetting = $this->getDefaultSetting($method, $type);
		if (is_bool($defaultSetting)) {
			return (bool)$this->appConfig->getValueString(
				'activity',
				'notify_' . $method . '_' . $type,
				(string)$defaultSetting
			);
		}

		return (int)$this->appConfig->getValueString(
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
	 */
	protected function getDefaultSetting(string $method, string $type): bool|int {
		if ($method === 'setting') {
			if ($type === 'batchtime') {
				return self::BATCH_TIME_HOURLY;
			}

			if ($type === 'self') {
				return true;
			}

			return false;
		}

		try {
			$setting = $this->manager->getSettingById($type);
			return match ($method) {
				'email' => $setting->isDefaultEnabledMail(),
				'notification' => $setting->isDefaultEnabledNotification(),
				default => false,
			};
		} catch (SettingNotFoundException) {
			return false;
		}
	}

	/**
	 * Get a good default setting for a preference
	 *
	 * @param string $method Should be one of 'stream', 'email' or 'setting'
	 * @param string $type One of the activity types, 'batchtime', 'self' or 'selfemail'
	 */
	protected function canModifySetting(string $method, string $type): bool {
		if ($method === 'setting') {
			return true;
		}

		try {
			$setting = $this->manager->getSettingById($type);
			return match ($method) {
				'email' => $setting->canChangeMail(),
				'notification' => $setting->canChangeNotification(),
				default => false,
			};
		} catch (SettingNotFoundException) {
			return false;
		}
	}

	/**
	 * Get a list with all notification types
	 */
	public function getNotificationTypes(): array {
		$settings = $this->manager->getSettings();

		$return = array_map(fn (ActivitySettings $setting) => $setting->getIdentifier(), $settings);

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
	 * @return array Returns a "username => b:true" Map for method = notification
	 *               Returns a "username => i:batchtime" Map for method = email
	 */
	public function filterUsersBySetting(array $users, string $method, string $type): array {
		if (empty($users)) {
			return [];
		}

		if ($method === 'email' && $this->appConfig->getValueString('activity', 'enable_email', 'yes') === 'no') {
			return [];
		}

		// file_created, file_deleted and file_restored are grouped under file_changed
		if ($type === Files::TYPE_SHARE_CREATED || $type === Files::TYPE_SHARE_DELETED || $type === Files::TYPE_SHARE_RESTORED) {
			$type = Files::TYPE_FILE_CHANGED;
		}

		$filteredUsers = [];
		$potentialUsers = $this->userConfig->getValuesByUsers('activity', 'notify_' . $method . '_' . $type, userIds: $users);
		foreach ($potentialUsers as $user => $value) {
			if ($value) {
				$filteredUsers[$user] = true;
			}
			unset($users[array_search($user, $users, true)]);
		}

		// Get the batch time setting from the database
		if ($method === 'email') {
			$potentialUsers = $this->userConfig->getValuesByUsers('activity', 'notify_setting_batchtime', userIds: array_keys($filteredUsers));
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
