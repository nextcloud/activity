<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

/**
 * The internal Activity objects visible in a Team's stream.
 *
 * @psalm-type FileObjectIds = list<int>
 */
class TeamActivityScope {
	/**
	 * @param FileObjectIds $fileObjectIds Filecache IDs reachable through Team resources
	 * @param array<int, string> $filePaths Readable paths relative to the viewer's files
	 * @param list<\OCP\Teams\TeamActivityScope> $resourceScopes Activity objects reachable through Team resources
	 */
	public function __construct(
		public readonly int $circleObjectId,
		public readonly array $fileObjectIds = [],
		public readonly array $filePaths = [],
		array $resourceScopes = [],
	) {
		foreach ($resourceScopes as $resourceScope) {
			if (!$resourceScope instanceof \OCP\Teams\TeamActivityScope) {
				throw new \InvalidArgumentException('Team resource scopes must be Teams Activity scopes');
			}
		}
		$this->resourceScopes = $resourceScopes !== []
			? $resourceScopes
			: ($this->fileObjectIds === [] ? [] : [new \OCP\Teams\TeamActivityScope('files', $this->fileObjectIds)]);
	}

	/** @var list<\OCP\Teams\TeamActivityScope> */
	public readonly array $resourceScopes;
}
