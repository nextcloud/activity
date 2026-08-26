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
		protected readonly IUserSession $userSession,
		protected readonly IRequest $request,
		protected readonly IManager $shareManager,
		protected readonly IFactory $l10nFactory,
	) {
	}

	public function getUser(): ?IUser {
		return $this->userSession->getUser();
	}

	/**
	 * Get an identifier for the user, session or token
	 */
	public function getUserIdentifier(): string {
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
	 */
	public function getUID(): ?string {
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
	 */
	public function getCloudId(): ?string {
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
	 * Check if the current request is via a public share link
	 */
	public function isPublicShareToken(): bool {
		return $this->getPublicShare() !== null;
	}

	/**
	 * Get the cloud ID from the sharing token
	 */
	protected function getCloudIDFromToken(): ?string {
		$share = $this->getPublicShare();

		if ($share === null || $share->getShareType() !== IShare::TYPE_REMOTE) {
			return null;
		}

		return $share->getSharedWith();
	}

	protected function getPublicShare(): ?IShare {
		if (basename($this->request->getScriptName()) !== 'public.php') {
			return null;
		}

		$token = $this->getShareToken();
		if ($token === null) {
			return null;
		}

		try {
			return $this->shareManager->getShareByToken($token);
		} catch (ShareNotFound $e) {
			return null;
		}
	}

	protected function getShareToken(): ?string {
		// The legacy public endpoint receive the share token in the HTTP basic auth header.
		/** @psalm-suppress NoInterfaceProperties */
		$authUser = (string)($this->request->server['PHP_AUTH_USER'] ?? '');
		if ($authUser !== '') {
			return $authUser;
		}

		// The current public endpoint receives the share token in the path.
		// Copied from apps/dav/lib/Connector/Sabre/PublicAuth::getToken()
		$path = $this->request->getPathInfo() ?: '';
		// ['', 'dav', 'files', 'token']
		$splittedPath = explode('/', $path);

		if (count($splittedPath) < 4 || $splittedPath[3] === '') {
			return null;
		}

		return $splittedPath[3];
	}
}
