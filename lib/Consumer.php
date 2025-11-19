<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Activity;

use OCP\Activity\ActivitySettings;
use OCP\Activity\IBulkConsumer;
use OCP\Activity\IConsumer;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Activity\ISetting;
use OCP\Config\IUserConfig;
use OCP\Config\ValueType;
use OCP\DB\Exception;

class Consumer implements IConsumer, IBulkConsumer {

	public function __construct(
		protected Data $data,
		protected IManager $manager,
		protected UserSettings $userSettings,
		protected NotificationGenerator $notificationGenerator,
		protected IUserConfig $userConfig,
	) {
	}

	/**
	 * Send an event to the notifications of a user
	 *
	 * @param IEvent $event
	 *
	 * @return void
	 */
	#[\Override]
	public function receive(IEvent $event): void {
		$selfAction = $event->getAffectedUser() === $event->getAuthor();
		$notificationSetting = $this->userSettings->getUserSetting($event->getAffectedUser(), 'notification', $event->getType());
		$emailSetting = $this->userSettings->getUserSetting($event->getAffectedUser(), 'email', $event->getType());
		$emailSetting = ($emailSetting) ? $this->userSettings->getUserSetting($event->getAffectedUser(), 'setting', 'batchtime') : false;

		$activityId = $this->data->send($event);

		if (!$selfAction && $notificationSetting && $activityId) {
			$this->notificationGenerator->sendNotificationForEvent($event, $activityId);
		}

		// Add activity to mail queue and user is not the author
		if ($emailSetting !== false && !$selfAction) {
			$latestSend = $event->getTimestamp() + $emailSetting;
			$this->data->storeMail($event, $latestSend);
		}
	}

	/**
	 * Send an event to the notifications of a bulk of users
	 *
	 * @param IEvent $event
	 * @param array $affectedUserIds
	 * @param ISetting $setting
	 * @return void
	 * @throws Exception
	 */
	#[\Override]
	public function bulkReceive(IEvent $event, array $affectedUserIds, ISetting $setting): void {
		if (empty($affectedUserIds)) {
			return;
		}

		$activityIds = $this->data->bulkSend($event, $affectedUserIds);

		if (empty($activityIds)) {
			return;
		}

		$canChangeMail = $setting->canChangeMail();
		$canChangePush = $setting instanceof ActivitySettings && $setting->canChangeNotification() === true;

		$userPushSettings = $userEmailSettings = $batchTimeSettings = null;
		if ($canChangePush === true) {
			$userPushSettings = $this->userConfig->getValuesByUsers('activity', 'notify_notification_ ' . $event->getType(), ValueType::BOOL, $affectedUserIds);
		}

		if ($canChangeMail === true || $setting->isDefaultEnabledMail() === true) {
			$userEmailSettings = $this->userConfig->getValuesByUsers('activity', 'notify_email_ ' . $event->getType(), ValueType::BOOL, $affectedUserIds);
			$batchTimeSettings = $this->userConfig->getValuesByUsers('activity', 'setting_batchtime', ValueType::INT, $affectedUserIds);
		}

		$shouldFlush = $this->notificationGenerator->deferNotifications();
		foreach ($activityIds as $activityId => $affectedUser) {
			if ($event->getAuthor() === $affectedUser) {
				continue;
			}
			$event->setAffectedUser($affectedUser);
			$notificationSetting = $userPushSettings[$affectedUser] ?? false;
			$emailSetting = $userEmailSettings[$affectedUser] ?? $batchTimeSettings[$affectedUser] ?? false;

			if ($notificationSetting !== false) {
				$this->notificationGenerator->sendNotificationForEvent($event, $activityId, $notificationSetting);
			}

			if (isset($emailSetting) && $emailSetting !== false) {
				$latestSend = $event->getTimestamp() + $emailSetting;
				$this->data->storeMail($event, $latestSend);
			}
		}
		if ($shouldFlush === true) {
			$this->notificationGenerator->flushNotifications();
		}
	}
}
