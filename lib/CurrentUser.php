<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\IRequest;
use OCP\IUser;
use OCP\IUserSession;
use OCP\L10N\IFactory;
use OCP\Share\Exceptions\ShareNotFound;
use OCP\Share\IManager;
use OCP\Share\IShare;

class CurrentUser {

	/** @var string|null */
	protected $identifier = null;
	/** @var string|false|null */
	protected $cloudId = false;
	/** @var string|false|null */
	protected $sessionUser = false;

	public function __construct(
		protected IUserSession $userSession,
		protected IRequest $request,
		protected IManager $shareManager,
		protected IFactory $l10nFactory,
	) {
	}

	public function getUser(): ?IUser {
		return $this->userSession->getUser();
	}

	/**
	 * Get an identifier for the user, session or token
	 * @return string
	 */
	public function getUserIdentifier() {
		if ($this->identifier !== null) {
			return $this->identifier;
		}

		$uid = $this->getUID();
		if ($uid !== null) {
			$this->identifier = $uid;
			return $this->identifier;
		}

		$cloudId = $this->getCloudIDFromToken();
		if ($cloudId !== null) {
			$this->identifier = $cloudId;
			return $this->identifier;
		}

		$nickname = htmlspecialchars($this->request->getHeader('X-NC-Nickname'));
		if ($nickname !== '') {
			$this->identifier = $nickname . ' (' . $this->l10nFactory->get('comments')->t('remote user') . ')';
			return $this->identifier;
		}

		// Nothing worked, fallback to empty string
		$this->identifier = '';
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
				$this->sessionUser = (string)$user->getUID();
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
				$this->cloudId = (string)$user->getCloudId();
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
