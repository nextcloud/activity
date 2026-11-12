<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\IDBConnection;
use OCP\Teams\ITeamActivityResourceProvider;
use OCP\Teams\ITeamManager;
use OCP\Teams\TeamActivityScope as ResourceActivityScope;
use Psr\Log\LoggerInterface;

class TeamActivityResolver {
	public function __construct(
		private readonly ITeamManager $teamManager,
		private readonly IDBConnection $connection,
		private readonly IRootFolder $rootFolder,
		private readonly LoggerInterface $logger,
	) {
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
			} catch (\Throwable $e) {
				$this->logger->warning('Team resource provider failed to resolve its activity scopes', [
					'exception' => $e,
					'app' => 'activity',
				]);
			}
		}
		$fileScopes = $this->getResourceObjects(array_values(array_unique($fileResourceIds)), $userId);
		return new TeamActivityScope($circleObjectId, $fileScopes, $resourceScopes);
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
	 * @return list<TeamActivityFileScope>
	 */
	private function getResourceObjects(array $resourceIds, string $userId): array {
		if ($resourceIds === []) {
			return [];
		}
		try {
			$userFolder = $this->rootFolder->getUserFolder($userId);
		} catch (NotFoundException|NotPermittedException) {
			return [];
		}

		$scopes = [];
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
				try {
					if ($root->getId() === null || !$root->isReadable()) {
						continue;
					}
					$path = $userFolder->getRelativePath($root->getPath());
					if ($path !== null) {
						$scopes[] = new TeamActivityFileScope($root, $path);
					}
				} catch (NotFoundException|NotPermittedException) {
					continue;
				}
			}
		}

		return $scopes;
	}
}
