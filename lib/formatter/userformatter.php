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

use OCP\Activity\IEvent;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IUserManager;
use OCP\Util;

class UserFormatter implements IFormatter {
	/** @var IUserManager */
	protected $manager;
	/** @var IL10N */
	protected $l;

	/**
	 * @param IUserManager $userManager
	 * @param IL10N $l
	 */
	public function __construct(IUserManager $userManager, IL10N $l) {
		$this->manager = $userManager;
		$this->l = $l;
	}

	/**
	 * @param IEvent $event
	 * @param string $parameter The parameter to be formatted
	 * @return string The formatted parameter
	 */
	public function format(IEvent $event, $parameter) {
		// If the username is empty, the action has been performed by a remote
		// user, or via a public share. We don't know the username in that case
		if ($parameter === '') {
			return '<user display-name="' . Util::sanitizeHTML($this->l->t('"remote user"')) . '">' . Util::sanitizeHTML('') . '</user>';
		}

		$user = $this->manager->get($parameter);
		$displayName = ($user) ? $user->getDisplayName() : $parameter;
		$parameter = Util::sanitizeHTML($parameter);

		return '<user display-name="' . Util::sanitizeHTML($displayName) . '">' . Util::sanitizeHTML($parameter) . '</user>';
	}
}
