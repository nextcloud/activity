<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2020 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Activity\Exceptions\UnknownActivityException;
use OCP\Activity\IEvent;
use OCP\Activity\IManager as ActivityManager;
use OCP\IL10N;
use OCP\Notification\AlreadyProcessedException;
use OCP\Notification\IManager as NotificationManager;
use OCP\Notification\INotification;
use OCP\Notification\INotifier;
use OCP\Notification\UnknownNotificationException;
use Psr\Log\LoggerInterface;

class NotificationGenerator implements INotifier {

	public function __construct(
		protected Data $data,
		protected ActivityManager $activityManager,
		protected NotificationManager $notificationManager,
		protected UserSettings $userSettings,
		protected IL10N $l10n,
		protected LoggerInterface $logger,
	) {
	}

	public function deferNotifications(): bool {
		return $this->notificationManager->defer();
	}

	public function flushNotifications() {
		$this->notificationManager->flush();
	}

	public function sendNotificationForEvent(IEvent $event, int $activityId, ?bool $notificationSetting = null) {
		$selfAction = $event->getAffectedUser() === $event->getAuthor();
		$notifySetting = $notificationSetting ?? $this->userSettings->getUserSetting($event->getAffectedUser(), 'notification', $event->getType());

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
			} catch (UnknownActivityException) {
			} catch (\InvalidArgumentException $e) {
				// todo 33.0.0 Log as warning
				// todo 39.0.0 Log as error
				$this->logger->debug(get_class($provider) . '::parse() threw \InvalidArgumentException which is deprecated. Throw \OCP\Activity\Exceptions\UnknownActivityException when the event is not known to your provider and otherwise handle all \InvalidArgumentException yourself.');
			}
		}
		$this->activityManager->setFormattingObject('', 0);

		return $event;
	}

	#[\Override]
	public function getID(): string {
		return 'activity';
	}

	#[\Override]
	public function getName(): string {
		return 'Activity';
	}

	#[\Override]
	public function prepare(INotification $notification, string $languageCode): INotification {
		if ($notification->getObjectType() !== 'activity_notification') {
			throw new UnknownNotificationException();
		}

		$event = $this->data->getById((int)$notification->getObjectId());
		if (!$event) {
			throw new AlreadyProcessedException();
		}
		if ($event->getAffectedUser() !== $notification->getUser()) {
			throw new AlreadyProcessedException();
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
