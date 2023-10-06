<?php
/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
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

namespace OCA\Activity;

use OCP\IRequest;
use OCP\IUser;
use OCP\IUserSession;
use OCP\Share\Exceptions\ShareNotFound;
use OCP\Share\IManager;
use OCP\Share\IShare;

class CurrentUser {

	/** @var string */
	protected $identifier;
	/** @var string|null */
	protected $cloudId;
	/** @var string|false|null */
	protected $sessionUser;

	/**
	 * @param IUserSession $userSession
	 * @param IRequest $request
	 * @param IManager $shareManager
	 */
	public function __construct(
		protected IUserSession $userSession,
		protected IRequest $request,
		protected IManager $shareManager) {
		$this->cloudId = false;
		$this->sessionUser = false;
	}

	public function getUser(): ?IUser {
		return $this->userSession->getUser();
	}

	/**
	 * Get an identifier for the user, session or token
	 * @return string
	 */
	public function getUserIdentifier() {
		if ($this->identifier === null) {
			$this->identifier = $this->getUID();

			if ($this->identifier === null) {
				$this->identifier = $this->getCloudIDFromToken();

				if ($this->identifier === null) {
					// Nothing worked, fallback to empty string
					$this->identifier = '';
				}
			}
		}

		return $this->identifier;
	}

	/**
	 * Get the current user id from the session
	 * @return string|null
	 */
	public function getUID() {
		if ($this->sessionUser === false) {
			$user = $this->userSession->getUser();
			if ($user instanceof IUser) {
				$this->sessionUser = (string) $user->getUID();
			} else {
				$this->sessionUser = null;
			}
		}

		return $this->sessionUser;
	}

	/**
	 * Get the current user cloud id from the session
	 * @return string|null
	 */
	public function getCloudId() {
		if ($this->cloudId === false) {
			$user = $this->userSession->getUser();
			if ($user instanceof IUser) {
				$this->cloudId = (string) $user->getCloudId();
			} else {
				$this->cloudId = $this->getCloudIDFromToken();
			}
		}

		return $this->cloudId;
	}

	/**
	 * Get the cloud ID from the sharing token
	 * @return string|null
	 */
	protected function getCloudIDFromToken() {
		if (!empty($this->request->server['PHP_AUTH_USER'])) {
			$token = $this->request->server['PHP_AUTH_USER'];
			/**
			 * Until https://github.com/nextcloud/server/pull/26681 is merged
			 * @psalm-suppress InvalidCatch
			 */
			try {
				$share = $this->shareManager->getShareByToken($token);
				if ($share->getShareType() === IShare::TYPE_REMOTE) {
					return $share->getSharedWith();
				}
			} catch (ShareNotFound $e) {
				// No share, use the fallback
			}
		}

		return null;
	}
}
