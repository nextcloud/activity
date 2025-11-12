<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Listener;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IAppConfig;
use OCP\IConfig;
use OCP\IUser;
use OCP\User\Events\PostLoginEvent;

/**
 * @template-implements IEventListener<Event>
 */
class SetUserDefaults implements IEventListener {

	public function __construct(
		private IConfig $config,
		private IAppConfig $appConfig,
	) {
	}

	#[\Override]
	public function handle(Event $event): void {
		if (!($event instanceof PostLoginEvent)) {
			return;
		}
		$user = $event->getUser();

		$this->setDefaultsForUser($user);
	}

	private function setDefaultsForUser(IUser $user): void {
		if ($this->config->getUserValue($user->getUID(), 'activity', 'configured', 'no') === 'yes') {
			// Already has settings
			return;
		}

		foreach ($this->appConfig->getKeys('activity') as $key) {
			if (!str_starts_with($key, 'notify_')) {
				continue;
			}

			if ($this->config->getUserValue($user->getUID(), 'activity', $key, null) !== null) {
				// Already has this setting
				continue;
			}

			$this->config->setUserValue(
				$user->getUID(),
				'activity',
				$key,
				$this->appConfig->getValueString('activity', $key)
			);
		}

		// Mark settings as configured
		$this->config->setUserValue($user->getUID(), 'activity', 'configured', 'yes');
	}
}
