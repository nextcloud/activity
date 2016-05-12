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
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\AppFramework\IAppContainer;
use OCP\L10N\IFactory;

class Consumer implements IConsumer {

	/** @var Data */
	protected $data;

	/** @var UserSettings */
	protected $userSettings;

	/** @var IFactory */
	protected $l10nFactory;

	/**
	 * Constructor
	 *
	 * @param Data $data
	 * @param UserSettings $userSettings
	 * @param IFactory $l10nFactory
	 */
	public function __construct(Data $data, UserSettings $userSettings, IFactory $l10nFactory) {
		$this->data = $data;
		$this->userSettings = $userSettings;
		$this->l10nFactory = $l10nFactory;
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

		$types = $this->data->getNotificationTypes($this->l10nFactory->get('activity'));
		$typeData = $types[$event->getType()];

		// User is not the author or wants to see their own actions
		$createStream = !$selfAction || $this->userSettings->getUserSetting($event->getAffectedUser(), 'setting', 'self');
		// User can not control the setting
		$createStream = $createStream || is_array($typeData) && isset($typeData['methods']) && !in_array(IExtension::METHOD_STREAM, $typeData['methods']);

		// Add activity to stream
		if ($streamSetting && $createStream) {
			$this->data->send($event);
		}

		// User is not the author or wants to see their own actions
		$createEmail = !$selfAction || $this->userSettings->getUserSetting($event->getAffectedUser(), 'setting', 'selfemail');
		// User can not control the setting
		$createEmail = $createEmail || is_array($typeData) && isset($typeData['methods']) && !in_array(IExtension::METHOD_MAIL, $typeData['methods']);

		// Add activity to mail queue
		if ($emailSetting && $createEmail) {
			$latestSend = $event->getTimestamp() + $emailSetting;
			$this->data->storeMail($event, $latestSend);
		}
	}
}
