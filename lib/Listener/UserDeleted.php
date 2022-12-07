<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2021, Thomas Citharel <nextcloud@tcit.fr>
 *
 * @author Thomas Citharel <nextcloud@tcit.fr>
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

namespace OCA\Activity\Listener;

use OCA\Activity\Data;
use OCA\Activity\MailQueueHandler;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IUser;
use OCP\User\Events\UserDeletedEvent;

/**
 * @template-implements IEventListener<Event>
 */
class UserDeleted implements IEventListener {
	/** @var Data */
	private $data;
	/**
	 * @var MailQueueHandler
	 */
	private $mailQueueHandler;

	public function __construct(Data $data, MailQueueHandler $mailQueueHandler) {
		$this->data = $data;
		$this->mailQueueHandler = $mailQueueHandler;
	}

	public function handle(Event $event): void {
		if (!($event instanceof UserDeletedEvent)) {
			return;
		}
		$user = $event->getUser();

		$this->deleteUserStream($user);
		$this->deleteUserMailQueue($user);
	}

	private function deleteUserStream(IUser $user): void {
		$this->data->deleteActivities(['affecteduser' => $user->getUID()]);
	}

	private function deleteUserMailQueue(IUser $user): void {
		$this->mailQueueHandler->purgeItemsForUser($user->getUID());
	}
}
