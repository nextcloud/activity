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
		$uid = $this->getUID();
		if ($uid !== null) {
			return $uid;
		}

		$cloudId = $this->getCloudIDFromToken();
		if ($cloudId !== null) {
			return $cloudId;
		}

		$nickname = htmlspecialchars($this->request->getHeader('X-NC-Nickname'));
		if ($nickname !== '') {
			return $nickname . ' (' . $this->l10nFactory->get('comments')->t('remote user') . ')';
		}

		// Nothing worked, fallback to empty string
		return '';
	}

	/**
	 * Get the current user id from the session
	 */
	public function getUID(): ?string {
		$user = $this->userSession->getUser();
		if ($user instanceof IUser) {
			return $user->getUID();
		}
		return null;
	}

	/**
	 * Get the current user cloud id from the session
	 */
	public function getCloudId(): ?string {
		$user = $this->userSession->getUser();
		if ($user instanceof IUser) {
			return $user->getCloudId();
		} else {
			return $this->getCloudIDFromToken();
		}
	}

	/**
	 * Check if the current request is via a public share link
	 */
	public function isPublicShareToken(): bool {
		/** @psalm-suppress NoInterfaceProperties */
		if (!empty($this->request->server['PHP_AUTH_USER'])) {
			$token = $this->request->server['PHP_AUTH_USER'];
			try {
				$share = $this->shareManager->getShareByToken($token);
				return $share->getShareType() === IShare::TYPE_LINK
					|| $share->getShareType() === IShare::TYPE_EMAIL;
			} catch (ShareNotFound $e) {
				// No share found for this token
			}
		}

		return false;
	}

	/**
	 * Get the cloud ID from the sharing token
	 */
	protected function getCloudIDFromToken() {
		/** @psalm-suppress NoInterfaceProperties */
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
