<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Lukas Reschke <lukas@statuscode.ch>
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

namespace OCA\Activity\Settings;

use OCA\Activity\CurrentUser;
use OCA\Activity\UserSettings;
use OCP\Activity\ActivitySettings;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\Settings\ISettings;

class Personal implements ISettings {
	/** @var \OCP\IConfig */
	protected $config;

	/** @var IManager */
	protected $manager;

	/** @var \OCA\Activity\UserSettings */
	protected $userSettings;

	/** @var \OCP\IL10N */
	protected $l10n;

	/** @var string */
	protected $user;

	/**
	 * constructor of the controller
	 *
	 * @param IConfig $config
	 * @param IManager $manager
	 * @param UserSettings $userSettings
	 * @param IL10N $l10n
	 * @param CurrentUser $currentUser
	 */
	public function __construct(IConfig $config,
								IManager $manager,
								UserSettings $userSettings,
								IL10N $l10n,
								CurrentUser $currentUser) {
		$this->config = $config;
		$this->manager = $manager;
		$this->userSettings = $userSettings;
		$this->l10n = $l10n;
		$this->user = (string) $currentUser->getUID();
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm() {
		$settings = $this->manager->getSettings();
		usort($settings, function(ActivitySettings $a, ActivitySettings $b) {
			if ($a->getPriority() === $b->getPriority()) {
				return $a->getIdentifier() > $b->getIdentifier();
			}

			return $a->getPriority() > $b->getPriority();
		});

		$activityGroups = [];
		foreach ($settings as $setting) {
			if (!$setting->canChangeMail() && !$setting->canChangeNotification()) {
				// No setting can be changed => don't display
				continue;
			}

			$methods = [];
			if ($setting->canChangeMail()) {
				$methods[] = IExtension::METHOD_MAIL;
			}

			if ($setting->canChangeNotification()) {
				$methods[] = IExtension::METHOD_NOTIFICATION;
			}

			$identifier = $setting->getIdentifier();
			$groupIdentifier = $setting->getGroupIdentifier();

			if (!isset($activityGroups[$groupIdentifier])) {
				$activityGroups[$groupIdentifier] = [
					'activities' => [],
					'name' => $setting->getGroupName()
				];
			}

			$activityGroups[$groupIdentifier]['activities'][$identifier] = array(
				'desc'		=> $setting->getName(),
				IExtension::METHOD_MAIL		=> $this->userSettings->getUserSetting($this->user, 'email', $identifier),
				IExtension::METHOD_NOTIFICATION	=> $this->userSettings->getUserSetting($this->user, 'notification', $identifier),
				'methods'	=> $methods,
			);
		}

		if (isset($activityGroups['other'])) {
			$otherActivities = $activityGroups['other'];
			unset($activityGroups['other']);
			$activityGroups['other'] = $otherActivities;
		}

		$settingBatchTime = UserSettings::EMAIL_SEND_HOURLY;
		$currentSetting = (int) $this->userSettings->getUserSetting($this->user, 'setting', 'batchtime');
		if ($currentSetting === 3600 * 24 * 7) {
			$settingBatchTime = UserSettings::EMAIL_SEND_WEEKLY;
		} else if ($currentSetting === 3600 * 24) {
			$settingBatchTime = UserSettings::EMAIL_SEND_DAILY;
		} else if ($currentSetting === 0) {
			$settingBatchTime = UserSettings::EMAIL_SEND_ASAP;
		}

		$emailEnabled = $this->config->getAppValue('activity', 'enable_email', 'yes') === 'yes';
		if ($emailEnabled) {
			$methods = [
				IExtension::METHOD_MAIL => $this->l10n->t('Mail'),
			];
		} else {
			$methods = [];
		}

		if ($this->config->getAppValue('activity', 'enable_notify', 'yes') === 'yes') {
			$methods[IExtension::METHOD_NOTIFICATION] = $this->l10n->t('Push');
		}

		return new TemplateResponse('activity', 'settings/personal', [
			'setting'			=> 'personal',
			'activityGroups'	=> $activityGroups,
			'is_email_set'		=> !empty($this->config->getUserValue($this->user, 'settings', 'email', '')),
			'email_enabled'		=> $emailEnabled,

			'setting_batchtime'	=> $settingBatchTime,

			'notify_self'		=> $this->userSettings->getUserSetting($this->user, 'setting', 'self'),
			'notify_selfemail'	=> $this->userSettings->getUserSetting($this->user, 'setting', 'selfemail'),

			'methods'			=> $methods,

			'activity_digest_enabled' => $this->userSettings->getUserSetting($this->user, 'setting', 'activity_digest')
		], 'blank');
	}

	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection() {
		return 'activity';
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 */
	public function getPriority() {
		return 55;
	}
}
