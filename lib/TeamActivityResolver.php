<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\IDBConnection;
use OCP\Teams\ITeamActivityResourceProvider;
use OCP\Teams\ITeamManager;
use OCP\Teams\TeamActivityScope as ResourceActivityScope;
use OCP\Teams\TeamResource;

class TeamActivityResolver {
	public function __construct(
		private readonly ITeamManager $teamManager,
		private readonly IDBConnection $connection,
		private readonly IRootFolder $rootFolder,
	) {
	}

	public function getActivityObjectId(string $teamId, string $userId): ?int {
		$members = $this->teamManager->getMembersOfTeam($teamId, $userId);
		if ($members === [] || !array_key_exists($userId, $members)) {
			return null;
		}

		return $this->getCircleObjectId($teamId);
	}

	/** Resolve the Activity objects reachable through a Team's resources. */
	public function getScope(string $teamId, string $userId): ?TeamActivityScope {
		if (!$this->isMember($teamId, $userId)) {
			return null;
		}

		$circleObjectId = $this->getCircleObjectId($teamId);
		if ($circleObjectId === null) {
			return null;
		}

		$fileResourceIds = [];
		$providerResources = [];
		foreach ($this->teamManager->getSharedWith($teamId, $userId) as $resource) {
			$provider = $resource->getProvider();
			if ($provider->getId() === 'files' && ctype_digit($resource->getId())) {
				$fileResourceIds[] = (int)$resource->getId();
				continue;
			}
			if ($provider instanceof ITeamActivityResourceProvider) {
				$providerResources[spl_object_id($provider)]['provider'] = $provider;
				$providerResources[spl_object_id($provider)]['resources'][] = $resource;
			}
		}

		$resourceScopes = [];
		foreach ($providerResources as $entry) {
			try {
				foreach ($entry['provider']->getActivityScopes($entry['resources'], $userId) as $resourceScope) {
					if (!$resourceScope instanceof ResourceActivityScope) {
						continue;
					}
					if ($resourceScope->getObjectType() === 'files') {
						array_push($fileResourceIds, ...$resourceScope->getObjectIds());
					} else {
						$resourceScopes[] = $resourceScope;
					}
				}
			} catch (\Throwable) {
				continue;
			}
		}
		$filePaths = $this->getResourceObjects(array_values(array_unique($fileResourceIds)), $userId);
		if ($filePaths !== []) {
			array_unshift($resourceScopes, new ResourceActivityScope('files', array_keys($filePaths)));
		}

		return new TeamActivityScope($circleObjectId, array_keys($filePaths), $filePaths, $resourceScopes);
	}

	private function isMember(string $teamId, string $userId): bool {
		$members = $this->teamManager->getMembersOfTeam($teamId, $userId);
		return array_key_exists($userId, $members);
	}

	private function getCircleObjectId(string $teamId): ?int {
		$query = $this->connection->getQueryBuilder();
		$query->select('id')
			->from('circles_circle')
			->where($query->expr()->eq('unique_id', $query->createNamedParameter($teamId)));

		$objectId = $query->executeQuery()->fetchOne();
		return $objectId === false ? null : (int)$objectId;
	}

	/**
	 * @param list<int> $resourceIds
	 * @return array<int, string>
	 */
	private function getResourceObjects(array $resourceIds, string $userId): array {
		try {
			$userFolder = $this->rootFolder->getUserFolder($userId);
		} catch (NotFoundException|NotPermittedException) {
			return [];
		}

		$paths = [];
		foreach ($resourceIds as $resourceId) {
			try {
				$roots = $userFolder->getById($resourceId);
			} catch (NotFoundException|NotPermittedException) {
				continue;
			}
			foreach ($roots as $root) {
				if (!$root instanceof Node) {
					continue;
				}
				$visited = [];
				$pending = [$root];
				while ($pending !== []) {
					$node = array_pop($pending);
					try {
						$id = $node->getId();
						if ($id === null || isset($visited[$id]) || !$node->isReadable()
							|| $node->getMountPoint()->getMountPoint() !== $root->getMountPoint()->getMountPoint()) {
							continue;
						}
						$visited[$id] = true;
						$path = $userFolder->getRelativePath($node->getPath());
						if ($path === null) {
							continue;
						}
						$paths[$id] ??= $path;
						if ($node instanceof Folder) {
							array_push($pending, ...$node->getDirectoryListing());
						}
					} catch (NotFoundException|NotPermittedException) {
						continue;
					}
				}
			}
		}

		return $paths;
	}
}
