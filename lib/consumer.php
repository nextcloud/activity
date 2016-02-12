<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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

namespace OCA\Activity;

use OCP\Activity\IConsumer;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\AppFramework\IAppContainer;

class Consumer implements IConsumer {
	/**
	 * Registers the consumer to the Activity Manager
	 *
	 * @param IManager $am
	 * @param IAppContainer $container
	 */
	public static function register(IManager $am, IAppContainer $container) {
		$am->registerConsumer(function() use ($am, $container) {
			return $container->query('Consumer');
		});
	}

	/** @var Data */
	protected $data;

	/** @var UserSettings */
	protected $userSettings;

	/**
	 * Constructor
	 *
	 * @param Data $data
	 * @param UserSettings $userSettings
	 */
	public function __construct(Data $data, UserSettings $userSettings) {
		$this->data = $data;
		$this->userSettings = $userSettings;
	}

	/**
	 * Send an event to the notifications of a user
	 *
	 * @param IEvent $event
	 * @return null
	 */
	public function receive(IEvent $event) {
		$selfAction = $event->getAffectedUser() === $event->getAuthor();
		$streamSetting = $this->userSettings->getUserSetting($event->getAffectedUser(), 'stream', $event->getType());
		$emailSetting = $this->userSettings->getUserSetting($event->getAffectedUser(), 'email', $event->getType());
		$emailSetting = ($emailSetting) ? $this->userSettings->getUserSetting($event->getAffectedUser(), 'setting', 'batchtime') : false;

		// Add activity to stream
		if ($streamSetting && (!$selfAction || $this->userSettings->getUserSetting($event->getAffectedUser(), 'setting', 'self'))) {
			$this->data->send($event);
		}

		// Add activity to mail queue
		if ($emailSetting && (!$selfAction || $this->userSettings->getUserSetting($event->getAffectedUser(), 'setting', 'selfemail'))) {
			$latestSend = $event->getTimestamp() + $emailSetting;
			$this->data->storeMail($event, $latestSend);
		}
	}
}
