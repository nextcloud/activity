<?php
/**
 * @copyright Copyright (c) 2023, Louis Chmn <louis@chmn.me>
 *
 * @author Louis Chmn <louis@chmn.me>
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

namespace OCA\Activity\Listener;

use OCA\Activity\FilesHooks;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Share\Events\BeforeShareDeletedEvent;
use OCP\Share\Events\ShareDeletedFromSelfEvent;

/**
 * The class to handle the share events
 * @template-implements IEventListener<Event>
 */
class ShareEventListener implements IEventListener {

	public function __construct(
		private FilesHooks $fileHooks,
	) {
	}

	public function handle(Event $event): void {
		if ($event instanceof BeforeShareDeletedEvent) {
			$this->unShare($event);
		}

		if ($event instanceof ShareDeletedFromSelfEvent) {
			$this->unShareSelf($event);
		}
	}

	/**
	 * Unsharing event
	 */
	public function unShare(BeforeShareDeletedEvent $event): void {
		$share = $event->getShare();
		$this->fileHooks->unShare($share);
	}

	/**
	 * "Unsharing a share from self only" event
	 */
	public function unShareSelf(ShareDeletedFromSelfEvent $event): void {
		$share = $event->getShare();
		$this->fileHooks->unShareSelf($share);
	}
}
