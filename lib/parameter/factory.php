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

namespace OCA\Activity\Parameter;


use OCA\Activity\Formatter\IFormatter;
use OCA\Activity\Formatter\BaseFormatter;
use OCA\Activity\Formatter\CloudIDFormatter;
use OCA\Activity\Formatter\FileFormatter;
use OCA\Activity\Formatter\UserFormatter;
use OCA\Activity\ViewInfoCache;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Contacts\IManager as IContactsManager;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\IUserManager;

class Factory {
	/** @var IManager */
	protected $activityManager;

	/** @var IUserManager */
	protected $userManager;

	/** @var IContactsManager */
	protected $contactsManager;

	/** @var IL10N */
	protected $l;

	/** @var ViewInfoCache */
	protected $infoCache;

	/** @var string */
	protected $user;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/**
	 * @param IManager $activityManager
	 * @param IUserManager $userManager
	 * @param IURLGenerator $urlGenerator
	 * @param IContactsManager $contactsManager
	 * @param ViewInfoCache $infoCache,
	 * @param IL10N $l
	 * @param string $user
	 */
	public function __construct(IManager $activityManager,
								IUserManager $userManager,
								IURLGenerator $urlGenerator,
								IContactsManager $contactsManager,
								ViewInfoCache $infoCache,
								IL10N $l,
								$user) {
		$this->activityManager = $activityManager;
		$this->userManager = $userManager;
		$this->urlGenerator = $urlGenerator;
		$this->contactsManager = $contactsManager;
		$this->infoCache = $infoCache;
		$this->l = $l;
		$this->user = $user;
	}

	/**
	 * @param string $user
	 */
	public function setUser($user) {
		$this->user = (string) $user;
	}

	/**
	 * @param IL10N $l
	 */
	public function setL10n(IL10N $l) {
		$this->l = $l;
	}

	/**
	 * @param string $parameter
	 * @param IEvent $event
	 * @param string $formatter
	 * @return IParameter
	 */
	public function get($parameter, IEvent $event, $formatter) {
		return new Parameter(
			$parameter,
			$event,
			$this->getFormatter($formatter),
			$formatter
		);
	}

	/**
	 * @return Collection
	 */
	public function createCollection() {
		return new Collection($this->l, sha1(microtime() . mt_rand()));
	}

	/**
	 * @param string $formatter
	 * @return IFormatter
	 */
	protected function getFormatter($formatter) {
		if ($formatter === 'file') {
			return new FileFormatter($this->infoCache, $this->urlGenerator, $this->l, $this->user);
		} else if ($formatter === 'username') {
			return new UserFormatter($this->userManager, $this->l);
		} else if ($formatter === 'federated_cloud_id') {
			return new CloudIDFormatter($this->contactsManager);
		} else {
			return new BaseFormatter();
		}
	}
}
