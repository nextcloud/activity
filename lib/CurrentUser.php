<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Activity\IManager as IActivityManager;
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserSession;
use OCP\L10N\IFactory;
use OCP\Share\Exceptions\ShareNotFound;
use OCP\Share\IManager;
use OCP\Share\IShare;

class CurrentUser {

	private bool $publicShareLoaded = false;
	private ?IShare $publicShare = null;

	public function __construct(
		protected readonly IUserSession $userSession,
		protected readonly IRequest $request,
		protected readonly IFactory $l10nFactory,
		protected readonly IActivityManager $activityManager,
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
	 * Get the current user id
	 *
	 * Apps can override who an action is attributed to with
	 * IManager::setCurrentUserId(). That is the only way to name an actor when the
	 * action happens outside of that user's session, e.g. from a background job.
	 * Without an override the manager reads the session, and on a request without
	 * a session the owner of the activity feed token.
	 */
	public function getUID(): ?string {
		try {
			$userId = $this->activityManager->getCurrentUserId();
		} catch (\UnexpectedValueException) {
			// No override, no session and no valid feed token
			return null;
		}

		return $userId === '' ? null : $userId;
	}
}
