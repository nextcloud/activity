<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\GroupFolders\ACL;

use OCA\GroupFolders\ACL\UserMapping\IUserMapping;
use OCA\GroupFolders\ACL\UserMapping\IUserMappingManager;
use OCP\DB\QueryBuilder\ICompositeExpression;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IDBConnection;
use OCP\IUser;
use OCP\Log\Audit\CriticalActionPerformedEvent;

class RuleManager {
	public function __construct(
		private readonly IDBConnection $connection,
		private readonly IUserMappingManager $userMappingManager,
		private readonly IEventDispatcher $eventDispatcher,
	) {
	}

	/**
	 * @param int[] $fileIds
	 * @return array<int, Rule[]>
	 */
	public function getRulesForFilesById(IUser $user, array $fileIds): array
 {
 }

	/**
	 * @param string[] $filePaths
	 * @return array<string, Rule[]>
	 */
	public function getRulesForFilesByPath(IUser $user, int $storageId, array $filePaths): array
 {
 }

	/**
	 * @param int[] $fileIds
	 * @return array<int, array<string, Rule[]>>
	 */
	public function getRulesForFilesByIds(IUser $user, array $fileIds): array
 {
 }

	/**
	 * @return array<string, Rule[]>
	 */
	public function getRulesForFilesByParent(IUser $user, int $storageId, int $parentId): array
 {
 }

	/**
	 * @param string[] $filePaths
	 * @return array<string, Rule[]>
	 */
	public function getAllRulesForPaths(int $storageId, array $filePaths): array
 {
 }

	/**
	 * @return array<string, Rule[]>
	 */
	public function getAllRulesForPrefix(int $storageId, string $prefix): array
 {
 }

	/**
	 * @return array<string, Rule[]>
	 */
	public function getRulesForPrefix(IUser $user, int $storageId, string $prefix): array
 {
 }

	public function saveRule(Rule $rule): void
 {
 }

	public function deleteRule(Rule $rule): void
 {
 }
}
