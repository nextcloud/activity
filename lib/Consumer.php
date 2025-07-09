<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Activity;

use OCP\Activity\IConsumer;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;

class Consumer implements IConsumer {

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
	public function receive(IEvent $event) {
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
}
