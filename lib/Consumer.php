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
use Throwable;

class Consumer implements IConsumer, IBulkConsumer {

	public function __construct(
		protected Data $data,
		protected IManager $manager,
		protected UserSettings $userSettings,
		protected NotificationGenerator $notificationGenerator,
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
	 * Send an event to the notifications of a user
	 *
	 * @param IEvent $event
	 * @throws Throwable
	 *
	 * @return void
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
		$canChangePush = false;
		if ($setting instanceof ActivitySettings && $setting->canChangeNotification() === true) {
			$canChangePush = true;
		}

		if ($canChangePush === false && $canChangeMail === false) {
			return;
		}

		foreach ($activityIds as $activityId => $affectedUser) {
			if ($event->getAuthor() === $affectedUser) {
				continue;
			}
			$event->setAffectedUser($affectedUser);
			if ($canChangePush === true) {
				$notificationSetting = $this->userSettings->getUserSetting($affectedUser, 'notification', $event->getType());
			}

			if ($canChangeMail === true) {
				$emailSetting = $this->userSettings->getUserSetting($event->getAffectedUser(), 'email', $event->getType());
				$emailSetting = ($emailSetting) ? $this->userSettings->getUserSetting($event->getAffectedUser(), 'setting', 'batchtime') : false;
			}

			if (isset($notificationSetting) && $notificationSetting === true) {
				$this->notificationGenerator->sendNotificationForEvent($event, $activityId, $notificationSetting);
			}

			if (isset($emailSetting) && $emailSetting !== false) {
				$latestSend = $event->getTimestamp() + $emailSetting;
				$this->data->storeMail($event, $latestSend);
			}
		}
	}
}
