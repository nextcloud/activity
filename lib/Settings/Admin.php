<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Settings;

use OCA\Activity\DatabaseStats;
use OCA\Activity\UserSettings;
use OCP\Activity\ActivitySettings;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\IConfig;
use OCP\IL10N;
use OCP\Settings\ISettings;

class Admin implements ISettings {
	public function __construct(
		private IConfig $config,
		private IL10N $l10n,
		private UserSettings $userSettings,
		private IManager $manager,
		private IInitialState $initialState,
		private DatabaseStats $databaseStats,
	) {
	}

	#[\Override]
	public function getForm(): TemplateResponse {
		$settings = $this->manager->getSettings();
		usort($settings, static function (ActivitySettings $a, ActivitySettings $b): int {
			if ($a->getPriority() === $b->getPriority()) {
				return (int)($a->getIdentifier() > $b->getIdentifier());
			}

			return (int)($a->getPriority() > $b->getPriority());
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

			$activityGroups[$groupIdentifier]['activities'][$identifier] = [
				'desc' => $setting->getName(),
				IExtension::METHOD_MAIL => $this->userSettings->getAdminSetting('email', $identifier),
				IExtension::METHOD_NOTIFICATION => $this->userSettings->getAdminSetting('notification', $identifier),
				'methods' => $methods,
			];
		}

		if (isset($activityGroups['other'])) {
			$otherActivities = $activityGroups['other'];
			unset($activityGroups['other']);
			$activityGroups['other'] = $otherActivities;
		}

		$settingBatchTime = UserSettings::EMAIL_SEND_HOURLY;
		$currentSetting = (int)$this->userSettings->getAdminSetting('setting', 'batchtime');
		if ($currentSetting === 3600 * 24 * 7) {
			$settingBatchTime = UserSettings::EMAIL_SEND_WEEKLY;
		} elseif ($currentSetting === 3600 * 24) {
			$settingBatchTime = UserSettings::EMAIL_SEND_DAILY;
		} elseif ($currentSetting === 0) {
			$settingBatchTime = UserSettings::EMAIL_SEND_ASAP;
		}

		$this->initialState->provideInitialState('setting', 'admin');
		$this->initialState->provideInitialState('activity_groups', $activityGroups);
		$this->initialState->provideInitialState('is_email_set', true);
		$this->initialState->provideInitialState('email_enabled', $this->config->getAppValue('activity', 'enable_email', 'yes') === 'yes');

		$this->initialState->provideInitialState('setting_batchtime', $settingBatchTime);

		$this->initialState->provideInitialState('methods', [
			IExtension::METHOD_MAIL => $this->l10n->t('Mail'),
			IExtension::METHOD_NOTIFICATION => $this->l10n->t('Push'),
		]);

		$tableSizes = $this->databaseStats->getTableSizesInBytes();
		$this->initialState->provideInitialState('database_stats', [
			'dedicated_connection' => $this->databaseStats->isDedicatedConnection(),
			'tables' => $tableSizes,
			'retention_suggestion' => $this->databaseStats->getRetentionSuggestion($tableSizes),
		]);

		return new TemplateResponse('activity', 'settings/admin', [], TemplateResponse::RENDER_AS_BLANK);
	}

	#[\Override]
	public function getSection(): string {
		return 'activity';
	}

	#[\Override]
	public function getPriority(): int {
		return 55;
	}
}
