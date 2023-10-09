<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
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

use OCP\Activity\IConsumer;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;

class Consumer implements IConsumer {

	public function __construct(
		protected Data $data,
		protected IManager $manager,
		protected UserSettings $userSettings,
		protected NotificationGenerator $notificationGenerator) {
	}

	/**
	 * Send an event to the notifications of a user
	 *
	 * @param IEvent $event
	 *
	 * @return void
	 */
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
