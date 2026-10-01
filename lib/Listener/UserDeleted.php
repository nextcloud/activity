<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Listener;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IUser;
use OCP\User\Events\UserDeletedEvent;

/**
 * @template-implements IEventListener<Event>
 */
class UserDeleted implements IEventListener {
	public function __construct(
		private readonly \OCA\Activity\Data $data,
		private readonly \OCA\Activity\MailQueueHandler $mailQueueHandler,
	) {
	}

	#[\Override]
	public function handle(Event $event): void {
		if (!($event instanceof UserDeletedEvent)) {
			return;
		}
		$user = $event->getUser();

		$this->deleteUserStream($user);
		$this->deleteUserMailQueue($user);
	}

	private function deleteUserStream(IUser $user): void {
		$this->data->deleteActivities([['affecteduser', $user->getUID()]]);
	}

	private function deleteUserMailQueue(IUser $user): void {
		$this->mailQueueHandler->purgeItemsForUser($user->getUID());
	}
}
