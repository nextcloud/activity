<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Listener;

use OCA\Activity\AppInfo\Application;
use OCA\Files\Event\LoadSidebar;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

/**
 * @template-implements IEventListener<Event>
 */
class LoadSidebarScripts implements IEventListener {
	#[\Override]
	public function handle(Event $event): void {
		if (!($event instanceof LoadSidebar)) {
			return;
		}

		// TODO: make sure to only include the sidebar script when
		// we properly split it between files list and sidebar
		Util::addStyle(Application::APP_ID, 'style');
		Util::addScript(Application::APP_ID, 'activity-sidebar');
		Util::addInitScript(Application::APP_ID, 'activity-api');
	}
}
