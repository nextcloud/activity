<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

/**
 * The internal Activity objects visible in a Team's stream.
 */
class TeamActivityScope {
	/**
	 * @param list<TeamActivityFileScope> $fileScopes Accessible file roots, without enumerating their descendants
	 * @param list<\OCP\Teams\TeamActivityScope> $resourceScopes Activity objects reachable through Team resources
	 */
	public function __construct(
		public readonly int $circleObjectId,
		public readonly array $fileScopes = [],
		public readonly array $resourceScopes = [],
	) {
		foreach ($resourceScopes as $resourceScope) {
			if (!$resourceScope instanceof \OCP\Teams\TeamActivityScope) {
				throw new \InvalidArgumentException('Team resource scopes must be Teams Activity scopes');
			}
		}
	}
}
