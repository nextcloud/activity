<?php

declare (strict_types=1);
/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\GroupFolders\Folder;

use OC\Files\Cache\Cache;
use OCA\Circles\CirclesManager;
use OCA\Circles\Model\Circle;
use OCA\Circles\Model\Member;
use OCA\GroupFolders\ACL\UserMapping\IUserMappingManager;
use OCA\GroupFolders\Mount\FolderStorageManager;
use OCA\GroupFolders\ResponseDefinitions;
use OCP\DB\Exception;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Files\IMimeTypeLoader;
use OCP\IAppConfig;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IGroupManager;
use OCP\IUser;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type GroupFoldersGroup from ResponseDefinitions
 * @phpstan-import-type GroupFoldersCircle from ResponseDefinitions
 * @phpstan-import-type GroupFoldersUser from ResponseDefinitions
 * @phpstan-import-type GroupFoldersAclManage from ResponseDefinitions
 * @phpstan-import-type GroupFoldersApplicable from ResponseDefinitions
 * @phpstan-type InternalFolderMapping = array{
 *   folder_id: int,
 *   mapping_type: 'user'|'group'|'circle',
 *   mapping_id: string,
 * }
 */
class FolderManager {
	public const SPACE_DEFAULT = -4;

	public function __construct(
		private readonly IDBConnection $connection,
		private readonly IGroupManager $groupManager,
		private readonly IMimeTypeLoader $mimeTypeLoader,
		private readonly LoggerInterface $logger,
		private readonly IEventDispatcher $eventDispatcher,
		private readonly IConfig $config,
		private readonly IUserMappingManager $userMappingManager,
		private readonly FolderStorageManager $folderStorageManager,
		private readonly IAppConfig $appConfig,
	) {
	}

	/**
	 * @return array<int, FolderDefinitionWithMappings>
	 * @throws Exception
	 */
	public function getAllFolders(): array {
	}

	/**
	 * @return array<int, FolderWithMappingsAndCache>
	 * @throws Exception
	 */
	public function getAllFoldersWithSize(int $offset = 0, ?int $limit = null, string $orderBy = 'mount_point', \SortDirection $order = \SortDirection::Ascending, ?string $mountPoint = null): array {
	}

	/**
	 * @return array<int, FolderWithMappingsAndCache>
	 * @throws Exception
	 */
	public function getAllFoldersForUserWithSize(IUser $user): array {
	}

	public function getFolder(int $id): ?FolderWithMappingsAndCache {
	}

	/**
	 * Return just the ACL for the folder.
	 *
	 * @throws Exception
	 */
	public function getFolderAclEnabled(int $id): bool {
	}

	public function getFolderByPath(string $path): int {
	}

	/**
	 * Check if the user is able to configure the advanced folder permissions. This
	 * is the case if the user is an admin, has admin permissions for the group folder
	 * app or is member of a group that can manage permissions for the specific folder.
	 *
	 * @throws Exception
	 */
	public function canManageACL(int $folderId, IUser $user, bool $excludeAdmins = false): bool {
	}

	public function mountPointExists(string $mountPoint): bool {
	}

	/**
	 * @return list<GroupFoldersGroup>
	 * @throws Exception
	 */
	public function searchGroups(int $id, string $search = ''): array {
	}

	/**
	 * @return list<GroupFoldersCircle>
	 * @throws Exception
	 */
	public function searchCircles(int $id, string $search = ''): array {
	}

	/**
	 * @return list<GroupFoldersUser>
	 * @throws Exception
	 */
	public function searchUsers(int $id, string $search = '', int $limit = 10, int $offset = 0): array {
	}

	/**
	 * @param string[] $groupIds
	 * @param list<string> $paths
	 * @return list<FolderDefinitionWithPermissions>
	 * @throws Exception
	 */
	public function getFoldersForGroups(array $groupIds, ?int $folderId = null, ?array $paths = null): array {
	}

	/**
	 * @throws \InvalidArgumentException
	 * @throws Exception
	 */
	public function hasFolderForGroup(string $groupId): bool {
	}

	/**
	 * @throws \InvalidArgumentException
	 * @throws Exception
	 */
	public function hasFolderForCircle(string $circleId): bool {
	}

	/**
	 * Return all group folders directly assigned to or owned by a circle.
	 *
	 * @return list<FolderDefinition>
	 * @throws Exception
	 */
	public function getFoldersForCircle(string $circleId): array {
	}

	/**
	 * @param list<string> $paths
	 * @return list<FolderDefinitionWithPermissions>
	 * @throws Exception
	 */
	public function getFoldersFromCircleMemberships(IUser $user, ?int $folderId = null, ?array $paths = null): array {
	}

	public function trimMountpoint(string $mountpoint): string {
	}

	/**
	 * @param array{separate-storage?: bool} $options
	 * @throws Exception
	 */
	public function createFolder(string $mountPoint, array $options = [], bool $aclDefaultNoPermission = false): int {
	}

	/**
	 * @throws Exception
	 */
	public function addApplicableGroup(int $folderId, string $groupId): void {
	}

	/**
	 * @throws Exception
	 */
	public function removeApplicableGroup(int $folderId, string $groupId): void {
	}

	/**
	 * @throws Exception
	 */
	public function setGroupPermissions(int $folderId, string $groupId, int $permissions): void {
	}

	/**
	 * @throws Exception
	 */
	public function setManageACL(int $folderId, string $type, string $id, bool $manageAcl): void {
	}

	/**
	 * @throws Exception
	 */
	public function removeFolder(int $folderId): void {
	}

	/**
	 * @throws Exception
	 */
	public function setFolderQuota(int $folderId, int $quota): void {
	}

	/**
	 * Update the JSON-encoded options of a folder.
	 *
	 * @param int $folderId
	 * @param array{separate-storage?: bool} $options
	 * @throws Exception
	 */
	public function setFolderOptions(int $folderId, array $options): void {
	}

	/**
	 * @throws Exception
	 */
	public function renameFolder(int $folderId, string $newMountPoint): void {
	}

	/**
	 * @throws Exception
	 */
	public function deleteGroup(string $groupId): void {
	}

	/**
	 * @throws Exception
	 */
	public function deleteUser(string $userId): void {
	}

	/**
	 * @throws Exception
	 */
	public function deleteCircle(string $circleId): void {
	}

	/**
	 * Look up the team folder that belongs to the given team (circle single id)
	 * via the `team_circle_id` column.
	 *
	 * This is the single source of truth for the "folder belongs to team"
	 * relationship. The legacy `group_folders_groups.circle_id` column tracks
	 * applicable-group membership (a separate concern) and must not be used to
	 * determine team ownership.
	 *
	 * @return int|null The folder id, or null if no folder belongs to this team.
	 */
	public function getFolderIdByTeamCircleId(string $circleId): ?int {
	}

	/**
	 * Mark a team folder as belonging to a team by setting the `team_circle_id`
	 * column.
	 */
	public function setTeamCircleId(int $folderId, string $circleId): void {
	}

	/**
	 * Whether the folder is assigned exclusively to the given circle. A folder
	 * must have precisely one applicable mapping, and that mapping must be the
	 * target team; group mappings and additional teams make it ineligible.
	 *
	 * @throws Exception
	 */
	public function isExclusivelyAssignedToCircle(int $folderId, string $circleId): bool {
	}

	/**
	 * Clear the team ownership of a team folder by resetting the
	 * `team_circle_id` column to null.
	 */
	public function clearTeamCircleId(int $folderId): void {
	}

	/**
	 * @throws Exception
	 */
	public function setFolderACL(int $folderId, bool $acl): void {
	}

	/**
	 * @param list<string> $paths
	 * @return list<FolderDefinitionWithPermissions>
	 * @throws Exception
	 */
	public function getFoldersForUser(IUser $user, ?int $folderId = null, ?array $paths = null): array {
	}

	/**
	 * @throws Exception
	 */
	public function getFolderPermissionsForUser(IUser $user, int $folderId): int {
	}

	/**
	 * returns if the groupId is in fact the singleId of an existing Circle
	 */
	public function isACircle(string $groupId): bool {
	}

	/**
	 * returns the Circle from its single Id, or NULL if not available
	 */
	public function getCircle(string $groupId): ?Circle {
	}

	public function getCirclesManager(): ?CirclesManager {
	}

	public function updateOverwriteHomeFolders(): void {
	}

	public function hasFolderACLDefaultNoPermission(int $folderId): bool {
	}

	/**
	 * Seed the cache from an already-loaded folder so bulk callers (the mount
	 * provider) skip one `getBasePermission()` lookup per folder.
	 */
	public function primeAclDefaultNoPermission(FolderDefinition $folder): void {
	}

	public function countAllFolders(): int {
	}
}
