<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
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
	/** @var IConfig */
	protected $config;
	/** @var IL10N */
	protected $l;

	/**
	 * @param IUserManager $userManager
	 * @param IConfig $config
	 * @param IL10N $l
	 */
	public function __construct(IUserManager $userManager, IConfig $config, IL10N $l) {
		$this->manager = $userManager;
		$this->config = $config;
		$this->l = $l;
	}

	/**
	 * @param IEvent $event
	 * @param string $parameter The parameter to be formatted
	 * @param bool $allowHtml   Should HTML be used to format the parameter?
	 * @param bool $verbose     Should paths, names, etc be shortened or full length
	 * @return string The formatted parameter
	 */
	public function format(IEvent $event, $parameter, $allowHtml, $verbose = false) {
		// If the username is empty, the action has been performed by a remote
		// user, or via a public share. We don't know the username in that case
		if ($parameter === '') {
			if ($allowHtml) {
				return '<strong>' . $this->l->t('"remote user"') . '</strong>';
			} else {
				return $this->l->t('"remote user"');
			}
		}

		$user = $this->manager->get($parameter);
		$displayName = ($user) ? $user->getDisplayName() : $parameter;
		$parameter = Util::sanitizeHTML($parameter);

		if ($allowHtml) {
			$avatarPlaceholder = '';
			if ($this->config->getSystemValue('enable_avatars', true)) {
				$avatarPlaceholder = '<div class="avatar" data-user="' . $parameter . '"></div>';
			}
			return $avatarPlaceholder . '<strong>' . Util::sanitizeHTML($displayName) . '</strong>';
		} else {
			return $displayName;
		}
	}
}
