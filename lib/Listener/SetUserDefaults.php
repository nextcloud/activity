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

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\IUser;
use OCP\User\Events\PostLoginEvent;

/**
 * @template-implements IEventListener<Event>
 */
class SetUserDefaults implements IEventListener {
	/** @var IConfig */
	private $config;

	public function __construct(IConfig $config) {
		$this->config = $config;
	}

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

		foreach ($this->config->getAppKeys('activity') as $key) {
			if (strpos($key, 'notify_') !== 0) {
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
				$this->config->getAppValue('activity', $key)
			);
		}

		// Mark settings as configured
		$this->config->setUserValue($user->getUID(), 'activity', 'configured', 'yes');
	}
}
