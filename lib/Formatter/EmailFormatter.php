<?php
/**
 * @copyright Copyright (c) 2016 Bjoern Schiessle <bjoern@schiessle.org>
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

namespace OCA\Activity\Formatter;

use OCP\Activity\IEvent;
use OCP\Contacts\IManager;
use OCP\Util;

class EmailFormatter implements IFormatter {

	/** @var IManager */
	protected $contactsManager;

	/**
	 * EmailFormatter constructor.
	 *
	 * @param IManager $contactsManager
	 */
	public function __construct(IManager $contactsManager) {
		$this->contactsManager = $contactsManager;
	}

	/**
	 * create a mailto link
	 *
	 * @param IEvent $event
	 * @param string $parameter The parameter to be formatted
	 * @return string The formatted parameter
	 */
	public function format(IEvent $event, $parameter) {
		$displayName = $this->getDisplayName($parameter);
		return '<a href="mailto:' . Util::sanitizeHTML($parameter) . '">' . Util::sanitizeHTML($displayName) . '</a>';
	}

	/**
	 * look up address books if we can find a user with the same email address
	 *
	 * @param string $email
	 * @return string
	 */
	protected function getDisplayName($email) {
		$result = $this->contactsManager->search($email, ['EMAIL']);
		foreach ($result as $r) {
			foreach($r['EMAIL'] as $value) {
				if ($value === $email) {
					return $r['FN'];
				}
			}
		}
		return $email;
	}

}
