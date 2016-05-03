<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
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

namespace OCA\Activity\Formatter;

use OC\HintException;
use OC\Share\Helper;
use OCP\Activity\IEvent;
use OCP\Contacts\IManager;
use OCP\Util;

class CloudIDFormatter implements IFormatter {
	/** @var IManager */
	protected $manager;
	/** @var array */
	protected $federatedContacts;

	/**
	 * @param IManager $contactsManager
	 */
	public function __construct(IManager $contactsManager) {
		$this->manager = $contactsManager;
		$this->federatedContacts = [];
	}

	/**
	 * @param IEvent $event
	 * @param string $parameter The parameter to be formatted
	 * @return string The formatted parameter
	 */
	public function format(IEvent $event, $parameter) {
		$displayName = $parameter;
		try {
			list($user, $server) = Helper::splitUserRemote($parameter);
		} catch (HintException $e) {
			$user = $parameter;
			$server = '';
		}

		if ($server !== '') {
			$displayName = $user . '@…';
		}

		try {
			$displayName = $this->getDisplayNameFromContact($parameter);
		} catch (\OutOfBoundsException $e) {}

		return '<federated-cloud-id display-name="' . Util::sanitizeHTML($displayName) . '" user="' . Util::sanitizeHTML($user) . '" server="' . Util::sanitizeHTML($server) . '">' . Util::sanitizeHTML($parameter) . '</federated-cloud-id>';
	}

	/**
	 * Try to find the user in the contacts
	 *
	 * @param string $federatedCloudId
	 * @return string
	 * @throws \OutOfBoundsException when there is no contact for the id
	 */
	protected function getDisplayNameFromContact($federatedCloudId) {
		$federatedCloudId = strtolower($federatedCloudId);
		if (isset($this->federatedContacts[$federatedCloudId])) {
			if ($this->federatedContacts[$federatedCloudId] !== '') {
				return $this->federatedContacts[$federatedCloudId];
			} else {
				throw new \OutOfBoundsException('No contact found for federated cloud id');
			}
		}

		$addressBookEntries = $this->manager->search($federatedCloudId, ['CLOUD']);
		foreach ($addressBookEntries as $entry) {
			if (isset($entry['CLOUD'])) {
				foreach ($entry['CLOUD'] as $cloudID) {
					if ($cloudID === $federatedCloudId) {
						$this->federatedContacts[$federatedCloudId] = $entry['FN'];
						return $entry['FN'];
					}
				}
			}
		}

		$this->federatedContacts[$federatedCloudId] = '';
		throw new \OutOfBoundsException('No contact found for federated cloud id');
	}
}
