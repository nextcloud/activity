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

namespace OCA\Activity\Controller;

use OCA\Activity\CurrentUser;
use OCA\Activity\UserSettings;
use OCP\Activity\IManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Security\ISecureRandom;

class SettingsController extends Controller {

	protected string $user;

	public function __construct(
		string $appName,
		IRequest $request,
		protected IConfig $config,
		protected ISecureRandom $random,
		protected IURLGenerator $urlGenerator,
		protected IManager $manager,
		protected UserSettings $userSettings,
		protected IL10N $l10n,
		CurrentUser $currentUser) {
		parent::__construct($appName, $request);
		$this->user = (string) $currentUser->getUID();
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $notify_setting_batchtime
	 * @param bool $notify_setting_self
	 * @param bool $notify_setting_selfemail
	 * @param bool $activity_digest
	 * @return DataResponse
	 */
	public function personal(
		$notify_setting_batchtime = UserSettings::EMAIL_SEND_HOURLY,
		$notify_setting_self = false,
		$notify_setting_selfemail = false,
		$activity_digest = false
	) {
		$settings = $this->manager->getSettings();
		foreach ($settings as $setting) {
			$this->config->setUserValue(
				$this->user, 'activity',
				'notify_notification_' . $setting->getIdentifier(),
				(string)(int) $this->request->getParam($setting->getIdentifier() . '_notification', false)
			);

			if ($setting->canChangeMail()) {
				$this->config->setUserValue(
					$this->user, 'activity',
					'notify_email_' . $setting->getIdentifier(),
					(string)(int) $this->request->getParam($setting->getIdentifier() . '_email', false)
				);
			}
		}

		$email_batch_time = 3600;
		if ($notify_setting_batchtime === UserSettings::EMAIL_SEND_DAILY) {
			$email_batch_time = 3600 * 24;
		} elseif ($notify_setting_batchtime === UserSettings::EMAIL_SEND_WEEKLY) {
			$email_batch_time = 3600 * 24 * 7;
		} elseif ($notify_setting_batchtime === UserSettings::EMAIL_SEND_ASAP) {
			$email_batch_time = 0;
		}

		$this->config->setUserValue(
			$this->user, 'activity',
			'notify_setting_batchtime',
			(string)$email_batch_time
		);
		$this->config->setUserValue(
			$this->user, 'activity',
			'notify_setting_self',
			(string)(int) $notify_setting_self
		);
		$this->config->setUserValue(
			$this->user, 'activity',
			'notify_setting_selfemail',
			(string)(int) $notify_setting_selfemail
		);
		$this->config->setUserValue(
			$this->user, 'activity',
			'notify_setting_activity_digest',
			(string)(int) $activity_digest
		);

		return new DataResponse([
			'data' => [
				'message' => $this->l10n->t('Your settings have been updated.'),
			],
		]);
	}

	/**
	 * @param int $notify_setting_batchtime
	 * @param bool $notify_setting_self
	 * @param bool $notify_setting_selfemail
	 * @return DataResponse
	 */
	public function admin(
		$notify_setting_batchtime = UserSettings::EMAIL_SEND_HOURLY,
		$notify_setting_self = false,
		$notify_setting_selfemail = false) {
		$settings = $this->manager->getSettings();
		foreach ($settings as $setting) {
			$this->config->setAppValue(
				'activity',
				'notify_notification_' . $setting->getIdentifier(),
				(string)(int)$this->request->getParam($setting->getIdentifier() . '_notification', false)
			);

			if ($setting->canChangeMail()) {
				$this->config->setAppValue(
					'activity',
					'notify_email_' . $setting->getIdentifier(),
					(string)(int) $this->request->getParam($setting->getIdentifier() . '_email', false)
				);
			}
		}

		$email_batch_time = 3600;
		if ($notify_setting_batchtime === UserSettings::EMAIL_SEND_DAILY) {
			$email_batch_time = 3600 * 24;
		} elseif ($notify_setting_batchtime === UserSettings::EMAIL_SEND_WEEKLY) {
			$email_batch_time = 3600 * 24 * 7;
		} elseif ($notify_setting_batchtime === UserSettings::EMAIL_SEND_ASAP) {
			$email_batch_time = 0;
		}

		$this->config->setAppValue(
			'activity',
			'notify_setting_batchtime',
			(string)$email_batch_time
		);
		$this->config->setAppValue(
			'activity',
			'notify_setting_self',
			(string)(int) $notify_setting_self
		);
		$this->config->setAppValue(
			'activity',
			'notify_setting_selfemail',
			(string)(int) $notify_setting_selfemail
		);

		return new DataResponse([
			'data' => [
				'message' => $this->l10n->t('Settings have been updated.'),
			],
		]);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param bool $enable true if the feed is enabled
	 * @return DataResponse
	 */
	public function feed(bool $enable) {
		$token = $tokenUrl = '';

		if ($enable === true) {
			$conflicts = true;

			// Check for collisions
			while (!empty($conflicts)) {
				$token = $this->random->generate(30, ISecureRandom::CHAR_UPPER . ISecureRandom::CHAR_LOWER . ISecureRandom::CHAR_DIGITS);
				$conflicts = $this->config->getUsersForUserValue('activity', 'rsstoken', $token);
			}

			$tokenUrl = $this->urlGenerator->linkToRouteAbsolute('activity.Feed.show', ['token' => $token]);
		}

		$this->config->setUserValue($this->user, 'activity', 'rsstoken', $token);

		return new DataResponse([
			'data' => [
				'message' => $this->l10n->t('Your settings have been updated.'),
				'rsslink' => trim($tokenUrl),
			],
		]);
	}
}
