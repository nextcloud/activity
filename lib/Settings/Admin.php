<?php
/**
 * @copyright Copyright (c) 2017 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Settings;

use OCA\Activity\UserSettings;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\Activity\ISetting;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\Settings\ISettings;

class Admin implements ISettings {

	/** @var IConfig */
	protected $config;

	/** @var IL10N */
	protected $l10n;

	/** @var IManager */
	protected $manager;

	/** @var UserSettings */
	protected $userSettings;

	/**
	 * @param IConfig $config
	 * @param IL10N $l10n
	 * @param IManager $manager
	 * @param UserSettings $userSettings
	 */
	public function __construct(IConfig $config, IL10N $l10n, UserSettings $userSettings, IManager $manager) {
		$this->config = $config;
		$this->l10n = $l10n;
		$this->manager = $manager;
		$this->userSettings = $userSettings;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm() {
		$settings = $this->manager->getSettings();
		usort($settings, function(ISetting $a, ISetting $b) {
			if ($a->getPriority() === $b->getPriority()) {
				return $a->getIdentifier() > $b->getIdentifier();
			}

			return $a->getPriority() > $b->getPriority();
		});

		$activities = [];
		foreach ($settings as $setting) {
			if (!$setting->canChangeStream() && !$setting->canChangeMail()) {
				// No setting can be changed => don't display
				continue;
			}

			$methods = [];
			if ($setting->canChangeStream()) {
				$methods[] = IExtension::METHOD_STREAM;
			}
			if ($setting->canChangeMail()) {
				$methods[] = IExtension::METHOD_MAIL;
			}

			$identifier = $setting->getIdentifier();

			$activities[$identifier] = array(
				'desc'		=> $setting->getName(),
				IExtension::METHOD_MAIL		=> $this->userSettings->getConfigSetting('email', $identifier),
				IExtension::METHOD_STREAM	=> $this->userSettings->getConfigSetting('stream', $identifier),
				'methods'	=> $methods,
			);
		}

		$settingBatchTime = UserSettings::EMAIL_SEND_HOURLY;
		$currentSetting = (int) $this->userSettings->getConfigSetting('setting', 'batchtime');
		if ($currentSetting === 3600 * 24 * 7) {
			$settingBatchTime = UserSettings::EMAIL_SEND_WEEKLY;
		} else if ($currentSetting === 3600 * 24) {
			$settingBatchTime = UserSettings::EMAIL_SEND_DAILY;
		} else if ($currentSetting === 0) {
			$settingBatchTime = UserSettings::EMAIL_SEND_ASAP;
		}

		return new TemplateResponse('activity', 'settings/admin', [
			'setting'			=> 'admin',
			'activities'		=> $activities,
			'is_email_set'		=> true,
			'email_enabled'		=> $this->config->getAppValue('activity', 'enable_email', 'yes') === 'yes',

			'setting_batchtime'	=> $settingBatchTime,

			'notify_self'		=> $this->userSettings->getConfigSetting('setting', 'self'),
			'notify_selfemail'	=> $this->userSettings->getConfigSetting('setting', 'selfemail'),

			'methods'			=> [
				IExtension::METHOD_MAIL => $this->l10n->t('Mail'),
				IExtension::METHOD_STREAM => $this->l10n->t('Stream'),
			],
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
