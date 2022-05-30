<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2020 Robin Appelman <robin@icewind.nl>
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

namespace OCA\Activity;

use OCP\Activity\IEvent;
use OCP\Activity\IManager as ActivityManager;
use OCP\Notification\IManager as NotificationManager;
use OCP\IL10N;
use OCP\Notification\INotification;
use OCP\Notification\INotifier;

class NotificationGenerator implements INotifier {
	protected $data;
	protected $activityManager;
	protected $userSettings;
	protected $l10n;
	protected $notificationManager;

	public function __construct(Data $data, ActivityManager $activityManager, NotificationManager $notificationManager, UserSettings $userSettings, IL10N $l10n) {
		$this->data = $data;
		$this->activityManager = $activityManager;
		$this->notificationManager = $notificationManager;
		$this->userSettings = $userSettings;
		$this->l10n = $l10n;
	}

	public function deferNotifications(): bool {
		return $this->notificationManager->defer();
	}

	public function flushNotifications() {
		$this->notificationManager->flush();
	}

	public function sendNotificationForEvent(IEvent $event, int $activityId) {
		$selfAction = $event->getAffectedUser() === $event->getAuthor();
		$notifySetting = $this->userSettings->getUserSetting($event->getAffectedUser(), 'notification', $event->getType());

		if ($notifySetting && !$selfAction && $event->getGenerateNotification()) {
			$this->notificationManager->notify($this->getNotificationForEvent($event, $activityId));
		}
	}

	private function getNotificationForEvent(IEvent $event, int $activityId): INotification {
		$notification = $this->notificationManager->createNotification();
		$notification->setApp($event->getApp());
		$notification->setUser($event->getAffectedUser());
		$notification->setDateTime(\DateTime::createFromFormat('U', (string)$event->getTimestamp()));
		$notification->setObject('activity_notification', (string)$activityId);
		$notification->setSubject($event->getSubject(), $event->getSubjectParameters());

		if ($event->getRichSubject()) {
			$notification->setRichSubject($event->getRichSubject(), $event->getRichSubjectParameters());
		}

		if ($event->getRichMessage()) {
			$notification->setRichMessage($event->getRichMessage(), $event->getRichMessageParameters());
		}

		if ($event->getMessage()) {
			$notification->setMessage($event->getMessage(), $event->getMessageParameters());
		}

		if ($event->getLink()) {
			$notification->setLink($event->getLink());
		}

		return $notification;
	}

	private function populateEvent(IEvent $event, string $language) {
		$this->activityManager->setFormattingObject($event->getObjectType(), $event->getObjectId());
		foreach ($this->activityManager->getProviders() as $provider) {
			try {
				$event = $provider->parse($language, $event);
			} catch (\InvalidArgumentException $e) {
			}
		}
		$this->activityManager->setFormattingObject('', 0);

		return $event;
	}

	public function getID(): string {
		return 'activity';
	}

	public function getName(): string {
		return 'Activity';
	}

	public function prepare(INotification $notification, string $languageCode): INotification {
		if ($notification->getObjectType() !== 'activity_notification') {
			throw new \InvalidArgumentException();
		}

		$event = $this->data->getById((int)$notification->getObjectId());
		if (!$event || $event->getAffectedUser() !== $notification->getUser()) {
			throw new \InvalidArgumentException();
		}
		$this->activityManager->setCurrentUserId($notification->getUser());
		$event = $this->populateEvent($event, $languageCode);
		$this->activityManager->setCurrentUserId(null);

		return $this->getDisplayNotificationForEvent($event, $event->getObjectId());
	}

	private function getDisplayNotificationForEvent(IEvent $event, int $activityId): INotification {
		$notification = $this->getNotificationForEvent($event, $activityId);

		$notification->setRichSubject($event->getRichSubject(), $event->getRichSubjectParameters());
		$notification->setParsedSubject($event->getParsedSubject());

		if ($event->getIcon()) {
			$notification->setIcon($event->getIcon());
		}

		if ($event->getRichMessage()) {
			$notification->setRichMessage($event->getRichMessage(), $event->getRichMessageParameters());
		}

		if ($event->getParsedMessage()) {
			$notification->setParsedMessage($event->getParsedMessage());
		}

		return $notification;
	}
}
