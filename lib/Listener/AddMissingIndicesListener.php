<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Listener;

use OCP\DB\Events\AddMissingIndicesEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;

/**
 * @template-implements IEventListener<Event|AddMissingIndicesEvent>
 */
class AddMissingIndicesListener implements IEventListener {
	#[\Override]

	public function handle(Event $event): void {
		if (!($event instanceof AddMissingIndicesEvent)) {
			return;
		}

		$event->replaceIndex(
			'activity',
			['activity_object'],
			'activity_object_user',
			['affecteduser', 'object_type', 'object_id', 'timestamp'],
			false,
		);
	}
}
