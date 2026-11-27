<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Activity\IManager as IActivityManager;

class CurrentUser {

	public function __construct(
		protected readonly IActivityManager $activityManager,
	) {
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
