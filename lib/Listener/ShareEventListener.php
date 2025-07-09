<?php

/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity\Listener;

use OCA\Activity\FilesHooks;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Share\Events\BeforeShareDeletedEvent;
use OCP\Share\Events\ShareCreatedEvent;
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

	#[\Override]
	public function handle(Event $event): void {
		if ($event instanceof BeforeShareDeletedEvent) {
			$this->unShare($event);
		}

		if ($event instanceof ShareDeletedFromSelfEvent) {
			$this->unShareSelf($event);
		}

		if ($event instanceof ShareCreatedEvent) {
			$this->createShare($event);
		}
	}

	/**
	 * Node shared event
	 */
	public function createShare(ShareCreatedEvent $event): void {
		$share = $event->getShare();
		$this->fileHooks->share($share);
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
