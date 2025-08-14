<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity;

use OC\Files\Filesystem;
use OC\Files\View;
use OCA\Activity\BackgroundJob\RemoteActivity;
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCA\Circles\CirclesManager;
use OCA\Circles\Model\Member;
use OCA\Files_Sharing\SharedMount;
use OCP\Activity\IManager;
use OCP\BackgroundJob\IJobList;
use OCP\Constants;
use OCP\Files\Config\IUserMountCache;
use OCP\Files\File;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\ITagManager;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Share\IShare;
use OCP\Share\IShareHelper;
use Psr\Log\LoggerInterface;

/**
 * The class to handle the filesystem hooks
 */
class FilesHooks {
	public const USER_BATCH_SIZE = 50;

	/** @var string|bool */
	protected $moveCase = false;
	/** @var array */
	protected $oldAccessList;
	/** @var string */
	protected $oldParentPath;
	/** @var string */
	protected $oldParentOwner;
	/** @var string */
	protected $oldParentId;

	public function __construct(
		protected IManager $manager,
		protected Data $activityData,
		protected UserSettings $userSettings,
		protected IGroupManager $groupManager,
		protected View $view,
		protected IRootFolder $rootFolder,
		protected IShareHelper $shareHelper,
		protected IDBConnection $connection,
		protected IURLGenerator $urlGenerator,
		protected LoggerInterface $logger,
		protected CurrentUser $currentUser,
		protected IUserMountCache $userMountCache,
		protected IConfig $config,
		protected NotificationGenerator $notificationGenerator,
		protected ITagManager $tagManager,
		protected ?CirclesManager $teamManager,
	) {
	}

	/**
	 * Store the create hook events
	 *
	 * @param string $path Path of the file that has been created
	 */
	public function fileCreate($path) {
		if ($path === '/' || $path === '' || $path === null) {
			return;
		}

		if ($this->currentUser->getUserIdentifier() !== '') {
			$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_CREATED, 'created_self', 'created_by');
		} else {
			$this->addNotificationsForFileAction($path, Files_Sharing::TYPE_PUBLIC_UPLOAD, '', 'created_public');
		}
	}

	/**
	 * Store the update hook events
	 *
	 * @param string $path Path of the file that has been modified
	 */
	public function fileUpdate($path) {
		$this->addNotificationsForFileAction($path, Files::TYPE_FILE_CHANGED, 'changed_self', 'changed_by');
	}

	/**
	 * Store the delete hook events
	 *
	 * @param string $path Path of the file that has been deleted
	 */
	public function fileDelete($path) {
		$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_DELETED, 'deleted_self', 'deleted_by');
	}

	/**
	 * Store the restore hook events
	 *
	 * @param string $path Path of the file that has been restored
	 */
	public function fileRestore($path) {
		$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by');
	}

	private function getFileChangeActivitySettings(int $fileId, array $users, $type = Files::TYPE_FILE_CHANGED): array {
		$filteredEmailUsers = $this->userSettings->filterUsersBySetting($users, 'email', $type);
		$filteredNotificationUsers = $this->userSettings->filterUsersBySetting($users, 'notification', $type);

		/** @psalm-suppress UndefinedMethod */
		$favoriteUsers = $this->tagManager->getUsersFavoritingObject('files', $fileId);
		if (!empty($favoriteUsers)) {
			$favoriteUsers = array_intersect($users, $favoriteUsers);
			if (!empty($favoriteUsers)) {
				$filteredEmailUsers = array_merge($filteredEmailUsers, $this->userSettings->filterUsersBySetting($favoriteUsers, 'email', Files::TYPE_FAVORITE_CHANGED));
				$filteredNotificationUsers = array_merge($filteredNotificationUsers, $this->userSettings->filterUsersBySetting($favoriteUsers, 'notification', Files::TYPE_FAVORITE_CHANGED));
			}
		}

		return [$filteredEmailUsers, $filteredNotificationUsers];
	}

	/**
	 * Creates the entries for file actions on $file_path
	 *
	 * @param string $filePath The file that is being changed
	 * @param string $activityType The activity type
	 * @param string $subject The subject for the actor
	 * @param string $subjectBy The subject for other users (with "by $actor")
	 */
	protected function addNotificationsForFileAction($filePath, $activityType, $subject, $subjectBy) {
		// Do not add activities for .part-files
		if (substr($filePath, -5) === '.part') {
			return;
		}

		[$filePath, $uidOwner, $fileId] = $this->getSourcePathAndOwner($filePath);
		if ($fileId === 0) {
			// Could not find the file for the owner ...
			return;
		}

		$accessList = $this->getUserPathsFromPath($filePath, $uidOwner);

		if (!empty($accessList['remotes'])) {
			$this->generateRemoteActivity($accessList['remotes'], $activityType, time(), $this->currentUser->getCloudId(), $accessList['ownerPath']);
		}

		$affectedUsers = $accessList['users'];

		// file can be shared using GroupFolders, including ACL check
		if ($this->config->getSystemValueBool('activity_use_cached_mountpoints', false)) {
			$affectedUsers = array_merge($affectedUsers, $this->getAffectedUsersFromCachedMounts($fileId));
		}

		[$filteredEmailUsers, $filteredNotificationUsers] =
			$this->getFileChangeActivitySettings($fileId, array_keys($affectedUsers), $activityType);

		foreach ($affectedUsers as $user => $path) {
			$user = (string)$user;

			if ($user === $this->currentUser->getUID()) {
				$userSubject = $subject;
				$userParams = [[$fileId => $path]];
			} else {
				$userSubject = $subjectBy;
				$userParams = [[$fileId => $path], $this->currentUser->getUserIdentifier()];
			}

			$this->addNotificationsForUser(
				$user, $userSubject, $userParams,
				$fileId, $path, true,
				$filteredEmailUsers[$user] ?? false,
				$filteredNotificationUsers[$user] ?? false,
				$activityType,
			);
		}
	}

	protected function generateRemoteActivity(array $remoteUsers, string $type, int $time, $actor, $ownerPath = false) {
		foreach ($remoteUsers as $remoteUser => $info) {
			if ($actor === $remoteUser) {
				// Current user receives the notification on their own instance already
				continue;
			}

			$arguments = [
				$remoteUser,
				$info['token'],
				$ownerPath !== false ? substr($ownerPath, strlen($info['node_path'])) : $info['node_path'],
				$type,
				$time,
				$actor,
			];

			if (isset($info['second_path'])) {
				$arguments[] = $info['second_path'];
			}

			\OCP\Server::get(IJobList::class)->add(RemoteActivity::class, $arguments);
		}
	}

	/**
	 * Collect some information for move/renames
	 *
	 * @param string $oldPath Path of the file that has been moved
	 * @param string $newPath Path of the file that has been moved
	 */
	public function fileMove($oldPath, $newPath) {
		if (substr($oldPath, -5) === '.part' || substr($newPath, -5) === '.part') {
			// Do not add activities for .part-files
			$this->moveCase = false;
			return;
		}

		$oldDir = dirname($oldPath);
		$newDir = dirname($newPath);

		if ($oldDir === $newDir) {
			/**
			 * a/b moved to a/c
			 *
			 * Cases:
			 * - a/b shared: no visible change
			 * - a/ shared: rename
			 */
			$this->moveCase = 'rename';
			return;
		}

		if (strpos($oldDir, $newDir) === 0) {
			/**
			 * a/b/c moved to a/c
			 *
			 * Cases:
			 * - a/b/c shared: no visible change
			 * - a/b/ shared: delete
			 * - a/ shared: move/rename
			 */
			$this->moveCase = 'moveUp';
		} elseif (strpos($newDir, $oldDir) === 0) {
			/**
			 * a/b moved to a/c/b
			 *
			 * Cases:
			 * - a/b shared: no visible change
			 * - a/c/ shared: add
			 * - a/ shared: move/rename
			 */
			$this->moveCase = 'moveDown';
		} else {
			/**
			 * a/b/c moved to a/d/c
			 *
			 * Cases:
			 * - a/b/c shared: no visible change
			 * - a/b/ shared: delete
			 * - a/d/ shared: add
			 * - a/ shared: move/rename
			 */
			$this->moveCase = 'moveCross';
		}

		[$this->oldParentPath, $this->oldParentOwner, $this->oldParentId] = $this->getSourcePathAndOwner($oldDir);
		if ($this->oldParentId === 0) {
			// Could not find the file for the owner ...
			$this->moveCase = false;
			return;
		}

		$oldAccessList = $this->getUserPathsFromPath($this->oldParentPath, $this->oldParentOwner);

		// file can be shared using GroupFolders, including ACL check
		if ($this->config->getSystemValueBool('activity_use_cached_mountpoints', false)) {
			[, , $oldFileId] = $this->getSourcePathAndOwner($oldPath);
			$oldAccessList['users'] = array_merge($oldAccessList['users'], $this->getAffectedUsersFromCachedMounts($oldFileId));
		}

		$this->oldAccessList = $oldAccessList;
	}


	/**
	 * Store the move hook events
	 *
	 * @param string $oldPath Path of the file that has been moved
	 * @param string $newPath Path of the file that has been moved
	 */
	public function fileMovePost($oldPath, $newPath) {
		// Do not add activities for .part-files
		if ($this->moveCase === false) {
			return;
		}

		switch ($this->moveCase) {
			case 'rename':
				$this->fileRenaming($oldPath, $newPath);
				break;
			case 'moveUp':
			case 'moveDown':
			case 'moveCross':
				$this->fileMoving($oldPath, $newPath);
				break;
		}

		$this->moveCase = false;
	}


	/**
	 * Renaming a file inside the same folder (a/b to a/c)
	 *
	 * @param string $oldPath
	 * @param string $newPath
	 */
	protected function fileRenaming($oldPath, $newPath) {
		$dirName = dirname($newPath);
		$fileName = basename($newPath);
		$oldFileName = basename($oldPath);

		[, , $fileId] = $this->getSourcePathAndOwner($newPath);
		[$parentPath, $parentOwner, $parentId] = $this->getSourcePathAndOwner($dirName);
		if ($fileId === 0 || $parentId === 0) {
			// Could not find the file for the owner ...
			return;
		}
		$accessList = $this->getUserPathsFromPath($parentPath, $parentOwner);

		$renameRemotes = [];
		foreach ($accessList['remotes'] as $remote => $info) {
			$renameRemotes[$remote] = [
				'token' => $info['token'],
				'node_path' => substr($newPath, strlen($info['node_path'])),
				'second_path' => substr($oldPath, strlen($info['node_path'])),
			];
		}
		$this->generateRemoteActivity($renameRemotes, Files::TYPE_FILE_CHANGED, time(), $this->currentUser->getCloudId());

		$affectedUsers = $accessList['users'];

		// file can be shared using GroupFolders, including ACL check
		if ($this->config->getSystemValueBool('activity_use_cached_mountpoints', false)) {
			$affectedUsers = array_merge($affectedUsers, $this->getAffectedUsersFromCachedMounts($fileId));
		}

		[$filteredEmailUsers, $filteredNotificationUsers] = $this->getFileChangeActivitySettings($fileId, array_keys($affectedUsers));

		foreach ($affectedUsers as $user => $path) {
			if ($user === $this->currentUser->getUID()) {
				$userSubject = 'renamed_self';
				$userParams = [
					[$fileId => $path . '/' . $fileName],
					[$fileId => $path . '/' . $oldFileName],
				];
			} else {
				$userSubject = 'renamed_by';
				$userParams = [
					[$fileId => $path . '/' . $fileName],
					$this->currentUser->getUserIdentifier(),
					[$fileId => $path . '/' . $oldFileName],
				];
			}

			$this->addNotificationsForUser(
				$user, $userSubject, $userParams,
				$fileId, $path . '/' . $fileName, true,
				$filteredEmailUsers[$user] ?? false,
				$filteredNotificationUsers[$user] ?? false,
				Files::TYPE_FILE_CHANGED,
			);
		}
	}

	/**
	 * Moving a file from one folder to another
	 *
	 * @param string $oldPath
	 * @param string $newPath
	 */
	protected function fileMoving($oldPath, $newPath) {
		$dirName = dirname($newPath);
		$fileName = basename($newPath);
		$oldFileName = basename($oldPath);

		[, , $fileId] = $this->getSourcePathAndOwner($newPath);
		[$parentPath, $parentOwner, $parentId] = $this->getSourcePathAndOwner($dirName);
		if ($fileId === 0 || $parentId === 0) {
			// Could not find the file for the owner ...
			return;
		}
		$accessList = $this->getUserPathsFromPath($parentPath, $parentOwner);
		$affectedUsers = $accessList['users'];
		$oldUsers = $this->oldAccessList['users'];

		// file can be shared using GroupFolders, including ACL check
		if ($this->config->getSystemValueBool('activity_use_cached_mountpoints', false)) {
			$this->userMountCache->clear(); // clear cache for new data
			$affectedUsers = array_merge($affectedUsers, $this->getAffectedUsersFromCachedMounts($fileId));
		}

		$beforeUsers = array_keys($oldUsers);
		$afterUsers = array_keys($affectedUsers);

		$deleteUsers = array_diff($beforeUsers, $afterUsers);
		$this->generateDeleteActivities($deleteUsers, $oldUsers, $fileId, $oldFileName);

		$addUsers = array_diff($afterUsers, $beforeUsers);
		$this->generateAddActivities($addUsers, $affectedUsers, $fileId, $fileName);

		$moveUsers = array_intersect($beforeUsers, $afterUsers);
		$this->generateMoveActivities($moveUsers, $oldUsers, $affectedUsers, $fileId, $oldFileName, $parentId, $fileName);

		$beforeRemotes = $this->oldAccessList['remotes'];
		$afterRemotes = $accessList['remotes'];

		$addRemotes = $deleteRemotes = $moveRemotes = [];
		foreach ($afterRemotes as $remote => $info) {
			if (isset($beforeRemotes[$remote])) {
				// Move
				$info['node_path'] = substr($newPath, strlen($info['node_path']));
				$info['second_path'] = substr($oldPath, strlen($beforeRemotes[$remote]['node_path']));
				$moveRemotes[$remote] = $info;
			} else {
				$info['node_path'] = substr($newPath, strlen($info['node_path']));
				$addRemotes[$remote] = $info;
			}
		}

		foreach ($beforeRemotes as $remote => $info) {
			if (!isset($afterRemotes[$remote])) {
				$info['node_path'] = substr($oldPath, strlen($info['node_path']));
				$deleteRemotes[$remote] = $info;
			}
		}

		$this->generateRemoteActivity($deleteRemotes, Files::TYPE_SHARE_DELETED, time(), $this->currentUser->getCloudId());
		$this->generateRemoteActivity($addRemotes, Files::TYPE_SHARE_CREATED, time(), $this->currentUser->getCloudId());
		$this->generateRemoteActivity($moveRemotes, Files::TYPE_FILE_CHANGED, time(), $this->currentUser->getCloudId());
	}

	/**
	 * @param string[] $users
	 * @param string[] $pathMap
	 * @param int $fileId
	 * @param string $oldFileName
	 */
	protected function generateDeleteActivities($users, $pathMap, $fileId, $oldFileName) {
		if (empty($users)) {
			return;
		}

		[$filteredEmailUsers, $filteredNotificationUsers] = $this->getFileChangeActivitySettings($fileId, $users);

		$shouldFlush = $this->startActivityTransaction();
		foreach ($users as $user) {
			$path = $pathMap[$user];

			if ($user === $this->currentUser->getUID()) {
				$userSubject = 'deleted_self';
				$userParams = [[$fileId => $path . '/' . $oldFileName]];
			} else {
				$userSubject = 'deleted_by';
				$userParams = [[$fileId => $path . '/' . $oldFileName], $this->currentUser->getUserIdentifier()];
			}

			$this->addNotificationsForUser(
				$user, $userSubject, $userParams,
				$fileId, $path . '/' . $oldFileName, true,
				$filteredEmailUsers[$user] ?? false,
				$filteredNotificationUsers[$user] ?? false,
				Files::TYPE_SHARE_DELETED,
			);
		}
		$this->commitActivityTransaction($shouldFlush);
	}

	/**
	 * @param string[] $users
	 * @param string[] $pathMap
	 * @param int $fileId
	 * @param string $fileName
	 */
	protected function generateAddActivities($users, $pathMap, $fileId, $fileName) {
		if (empty($users)) {
			return;
		}

		[$filteredEmailUsers, $filteredNotificationUsers] = $this->getFileChangeActivitySettings($fileId, $users);

		$shouldFlush = $this->startActivityTransaction();
		foreach ($users as $user) {
			$path = $pathMap[$user];

			if ($user === $this->currentUser->getUID()) {
				$userSubject = 'created_self';
				$userParams = [[$fileId => $path . '/' . $fileName]];
			} else {
				$userSubject = 'created_by';
				$userParams = [[$fileId => $path . '/' . $fileName], $this->currentUser->getUserIdentifier()];
			}

			$this->addNotificationsForUser(
				$user, $userSubject, $userParams,
				$fileId, $path . '/' . $fileName, true,
				$filteredEmailUsers[$user] ?? false,
				$filteredNotificationUsers[$user] ?? false,
				Files::TYPE_FILE_CHANGED,
			);
		}
		$this->commitActivityTransaction($shouldFlush);
	}

	/**
	 * @param string[] $users
	 * @param string[] $beforePathMap
	 * @param string[] $afterPathMap
	 * @param int $fileId
	 * @param string $oldFileName
	 * @param int $newParentId
	 * @param string $fileName
	 */
	protected function generateMoveActivities($users, $beforePathMap, $afterPathMap, $fileId, $oldFileName, $newParentId, $fileName) {
		if (empty($users)) {
			return;
		}

		[$filteredEmailUsers, $filteredNotificationUsers] = $this->getFileChangeActivitySettings($fileId, $users);

		$shouldFlush = $this->startActivityTransaction();
		foreach ($users as $user) {
			if ($oldFileName === $fileName) {
				$userParams = [[$newParentId => $afterPathMap[$user] . '/']];
			} else {
				$userParams = [[$fileId => $afterPathMap[$user] . '/' . $fileName]];
			}

			if ($user === $this->currentUser->getUID()) {
				$userSubject = 'moved_self';
			} else {
				$userSubject = 'moved_by';
				$userParams[] = $this->currentUser->getUserIdentifier();
			}
			$userParams[] = [$fileId => $beforePathMap[$user] . '/' . $oldFileName];

			$this->addNotificationsForUser(
				$user, $userSubject, $userParams,
				$fileId, $afterPathMap[$user] . '/' . $fileName, true,
				$filteredEmailUsers[$user] ?? false,
				$filteredNotificationUsers[$user] ?? false,
				Files::TYPE_FILE_CHANGED,
			);
		}
		$this->commitActivityTransaction($shouldFlush);
	}

	/**
	 * Returns a "username => path" map for all affected users
	 *
	 * @param string $path
	 * @param string $uidOwner
	 * @return array
	 */
	protected function getUserPathsFromPath($path, $uidOwner) {
		try {
			$node = $this->rootFolder->getUserFolder($uidOwner)->get($path);
		} catch (NotFoundException $e) {
			return [];
		}

		if (!$node instanceof Node) {
			return [];
		}

		$accessList = $this->shareHelper->getPathsForAccessList($node);

		$path = $node->getPath();
		$accessList['ownerPath'] = $this->getVisiblePath($path);
		return $accessList;
	}

	protected function getVisiblePath(string $absolutePath): string {
		$sections = explode('/', $absolutePath, 4);

		$path = '/';
		if (isset($sections[3])) {
			// Not the case when a file in root is renamed
			$path .= $sections[3];
		}

		return $path;
	}

	/**
	 * Return the source
	 *
	 * @param string $path
	 * @return array
	 */
	protected function getSourcePathAndOwner($path) {
		$view = Filesystem::getView();
		try {
			$owner = $view->getOwner($path);
			$owner = !is_string($owner) || $owner === '' ? null : $owner;
		} catch (NotFoundException $e) {
			$owner = null;
		}
		$fileId = 0;
		$currentUser = $this->currentUser->getUID();

		if ($owner === null || $owner !== $currentUser) {
			/** @var \OCP\Files\Storage\IStorage $storage */
			[$storage,] = $view->resolvePath($path);

			if ($owner !== null && !$storage->instanceOfStorage('OCA\Files_Sharing\External\Storage')) {
				Filesystem::initMountPoints($owner);
			} else {
				// Probably a remote user, let's try to at least generate activities
				// for the current user
				if ($currentUser === null) {
					[, $owner,] = explode('/', $view->getAbsolutePath($path), 3);
				} else {
					$owner = $currentUser;
				}
			}
		}

		$info = Filesystem::getFileInfo($path);
		if ($info !== false) {
			$ownerView = new View('/' . $owner . '/files');
			$fileId = (int)$info['fileid'];
			$path = $ownerView->getPath($fileId);
		}

		return [$path, $owner, $fileId];
	}

	/**
	 * Manage sharing events
	 *
	 * @param IShare $share the share from the event
	 */
	public function share($share) {
		switch ($share->getShareType()) {
			case IShare::TYPE_USER:
				$this->shareWithUser(
					$share->getSharedWith(),
					$share->getNode(),
					$share->getTarget(),
				);
				break;
			case IShare::TYPE_GROUP:
				$this->shareWithGroup(
					$share->getSharedWith(),
					$share->getNode(),
					$share->getTarget(),
					(int)$share->getId(),
				);
				break;
			case IShare::TYPE_CIRCLE:
				$this->shareWithTeam(
					$share->getSharedWith(),
					$share->getNode(),
					$share->getTarget(),
					(int)$share->getId(),
					$share->getSharedBy(),
				);
				break;
			case IShare::TYPE_LINK:
				$this->shareByLink(
					$share->getNode(),
					$share->getSharedBy(),
				);
				break;
			default:
				// Currently not supported
				break;
		}
	}

	/**
	 * Sharing a file or folder with a user
	 *
	 * @param string $shareWith
	 * @param Node $fileSource File that is being shared
	 * @param string $fileTarget File path
	 */
	protected function shareWithUser(string $shareWith, Node $fileSource, string $fileTarget) {
		// User performing the share
		$this->shareNotificationForSharer('shared_user_self', $shareWith, $fileSource);
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'reshared_user_by', $shareWith, $fileSource);
		}

		// New shared user
		$this->addNotificationsForUser(
			$shareWith, 'shared_with_by', [[$fileSource->getId() => $fileTarget], $this->currentUser->getUserIdentifier()],
			$fileSource->getId(), $fileTarget, $fileSource instanceof File,
			$this->userSettings->getUserSetting($shareWith, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($shareWith, 'setting', 'batchtime') : false,
			(bool)$this->userSettings->getUserSetting($shareWith, 'notification', Files_Sharing::TYPE_SHARED),
		);
	}

	/**
	 * Sharing a file or folder with a group
	 *
	 * @param string $shareWith
	 * @param Node $fileSource File that is being shared
	 * @param string $fileTarget File path
	 * @param int $shareId The Share ID of this share
	 */
	protected function shareWithGroup(string $shareWith, Node $fileSource, string $fileTarget, int $shareId) {
		// Members of the new group
		$group = $this->groupManager->get($shareWith);
		if (!($group instanceof IGroup)) {
			return;
		}

		// User performing the share
		$this->shareNotificationForSharer('shared_group_self', $shareWith, $fileSource);
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'reshared_group_by', $shareWith, $fileSource);
		}

		$offset = 0;
		$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		while (!empty($users)) {
			$userIds = array_map(fn (IUser $user) => $user->getUID(), $users);
			$this->addNotificationsForUsers($userIds, 'shared_with_by', $fileSource, $fileTarget, $shareId);
			$offset += self::USER_BATCH_SIZE;
			$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		}
	}

	/**
	 * Sharing a file or folder via link/public
	 *
	 * @param Node $fileSource File that is being shared
	 * @param string $sharedBy
	 */
	protected function shareByLink(Node $fileSource, string $sharedBy) {
		$relativePath = $this->getUserRelativePath($sharedBy, $fileSource->getPath());
		$this->shareNotificationForOriginalOwners($sharedBy, 'reshared_link_by', '', $fileSource);

		$this->addNotificationsForUser(
			$sharedBy, 'shared_link_self', [[$fileSource->getId() => $relativePath]],
			$fileSource->getId(), $relativePath, $fileSource instanceof File,
			$this->userSettings->getUserSetting($sharedBy, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($sharedBy, 'setting', 'batchtime') : false,
			(bool)$this->userSettings->getUserSetting($sharedBy, 'notification', Files_Sharing::TYPE_SHARED),
		);
	}

	/**
	 * Sharing a file or folder with a team
	 *
	 * @param string $shareWith
	 * @param Node $fileSource File that is being shared
	 * @param string $fileTarget File path
	 * @param int $shareId The Share ID of this share
	 */
	protected function shareWithTeam(string $shareWith, Node $fileSource, string $fileTarget, int $shareId, string $sharer): void {
		if ($this->teamManager === null) {
			return;
		}

		try {
			$this->teamManager->startSuperSession();
			$team = $this->teamManager->getCircle($shareWith);
			$members = $team->getInheritedMembers();
			$members = array_filter($members, fn ($member) => $member->getUserType() === Member::TYPE_USER);
			$userIds = array_map(fn ($member) => $member->getUserId(), $members);
		} catch (\Throwable $e) {
			$this->logger->debug('Fetching team members for share activity failed', ['exception' => $e]);
			// error in teams app - setting users list to empty
			$userIds = [];
		}

		// Activity for user performing the share
		$this->shareNotificationForSharer('shared_team_self', $shareWith, $fileSource);
		// Activity for original owner of the file (re-sharing)
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 're-shared_team_by', $shareWith, $fileSource);
		}
		// Activity for all affected users
		$this->addNotificationsForUsers($userIds, 'shared_with_by', $fileSource, $fileTarget, $shareId);
	}

	/**
	 * Manage unsharing events
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	public function unShare(IShare $share) {
		if (in_array($share->getNodeType(), ['file', 'folder'], true) && !$this->isDeletedNode($share->getShareOwner(), $share->getNodeId())) {
			if ($share->getShareType() === IShare::TYPE_USER) {
				$this->unshareFromUser($share);
			} elseif ($share->getShareType() === IShare::TYPE_GROUP) {
				$this->unshareFromGroup($share);
			} elseif ($share->getShareType() === IShare::TYPE_LINK) {
				$this->unshareLink($share);
			}
		}
	}

	/**
	 * Manage unsharing a share from self only events
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	public function unShareSelf(IShare $share) {
		if (in_array($share->getNodeType(), ['file', 'folder'], true)) {
			if ($share->getShareType() === IShare::TYPE_GROUP) {
				$this->unshareFromSelfGroup($share);
			} else {
				$this->unshareFromUser($share);
			}
		}
	}

	/**
	 * Unharing a file or folder from a user
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	protected function unshareFromUser(IShare $share) {
		if ($share->getSharedWith() === $this->currentUser->getUID()) {
			$this->selfUnshareFromUser($share);
			return;
		}

		if ($share->isExpired()) {
			$actionSharer = 'expired_user';
			$actionOwner = 'expired_user';
			$actionUser = 'expired';
		} else {
			$actionSharer = 'unshared_user_self';
			$actionOwner = 'unshared_user_by';
			$actionUser = 'unshared_by';
		}

		// User performing the share
		$this->shareNotificationForSharer($actionSharer, $share->getSharedWith(), $share->getNode());

		// Owner
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), $actionOwner, $share->getSharedWith(), $share->getNode());
		}

		// Recipient
		$this->addNotificationsForUser(
			$share->getSharedWith(), $actionUser, [[$share->getNodeId() => $share->getTarget()], $this->currentUser->getUserIdentifier()],
			$share->getNodeId(), $share->getTarget(), $share->getNodeType() === 'file',
			$this->userSettings->getUserSetting($share->getSharedWith(), 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($share->getSharedWith(), 'setting', 'batchtime') : false,
			(bool)$this->userSettings->getUserSetting($share->getSharedWith(), 'notification', Files_Sharing::TYPE_SHARED),
		);
	}

	/**
	 * Unharing a file or folder from a user
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	protected function selfUnshareFromUser(IShare $share) {
		// User performing the share
		$this->shareNotificationForSharer('self_unshared', $share->getSharedWith(), $share->getNode(), $share->getTarget());

		// Owner
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'self_unshared_by', $share->getSharedWith(), $share->getNode());
		}
	}

	/**
	 * Unsharing a file or folder from a group
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	protected function unshareFromGroup(IShare $share) {
		// Members of the new group
		$group = $this->groupManager->get($share->getSharedWith());
		if (!($group instanceof IGroup)) {
			return;
		}

		if ($share->isExpired()) {
			$actionSharer = 'expired_group';
			$actionOwner = 'expired_group';
			$actionUser = 'expired';
		} else {
			$actionSharer = 'unshared_group_self';
			$actionOwner = 'unshared_group_by';
			$actionUser = 'unshared_by';
		}

		// User performing the share
		$this->shareNotificationForSharer($actionSharer, $share->getSharedWith(), $share->getNode());
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), $actionOwner, $share->getSharedWith(), $share->getNode());
		}

		$offset = 0;
		$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		$shouldFlush = $this->startActivityTransaction();
		while (!empty($users)) {
			$userIds = \array_map(fn (IUser $user) => $user->getUID(), $users);
			$this->addNotificationsForUsers($userIds, $actionUser, $share->getNode(), $share->getTarget(), (int)$share->getId());
			$offset += self::USER_BATCH_SIZE;
			$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		}
		$this->commitActivityTransaction($shouldFlush);
	}

	/**
	 * Unsharing a file or folder from self from a group share
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	protected function unshareFromSelfGroup(IShare $share) {
		// User performing the unshare
		$this->shareNotificationForSharer('self_unshared', $this->currentUser->getUID(), $share->getNode(), $share->getTarget());

		// Owner
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'self_unshared_by', $this->currentUser->getUID(), $share->getNode());
		}
	}

	/**
	 * Sharing a file or folder via link/public
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	protected function unshareLink(IShare $share) {
		$owner = $share->getSharedBy();

		if ($share->isExpired()) {
			// Link expired
			$actionSharer = 'link_expired';
			$actionOwner = 'link_by_expired';
		} else {
			$actionSharer = 'unshared_link_self';
			$actionOwner = 'unshared_link_by';
		}

		$this->addNotificationsForUser(
			$owner, $actionSharer, [[$share->getNodeId() => $share->getTarget()]],
			$share->getNodeId(), $share->getTarget(), $share->getNodeType() === 'file',
			$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : false,
			(bool)$this->userSettings->getUserSetting($owner, 'notification', Files_Sharing::TYPE_SHARED),
		);

		if ($share->getSharedBy() !== $share->getShareOwner()) {
			$owner = $share->getShareOwner();
			$this->addNotificationsForUser(
				$owner, $actionOwner, [[$share->getNodeId() => $share->getTarget()], $share->getSharedBy()],
				$share->getNodeId(), $share->getTarget(), $share->getNodeType() === 'file',
				$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : false,
				(bool)$this->userSettings->getUserSetting($owner, 'notification', Files_Sharing::TYPE_SHARED),
			);
		}
	}

	/**
	 * @param string[] $usersIds
	 * @param string $actionUser
	 * @param Node $fileSource File that is being shared
	 * @param string $fileTarget File path
	 * @param int $shareId The Share ID of this share
	 */
	protected function addNotificationsForUsers(array $usersIds, string $actionUser, Node $fileSource, string $fileTarget, int $shareId) {
		$affectedUsers = [];

		foreach ($usersIds as $user) {
			$affectedUsers[$user] = $fileTarget;
		}

		// Remove the triggering user, we already managed his notifications
		unset($affectedUsers[$this->currentUser->getUID()]);

		if (empty($affectedUsers)) {
			return;
		}

		$userIds = array_keys($affectedUsers);
		$filteredEmailUsersInGroup = $this->userSettings->filterUsersBySetting($userIds, 'email', Files_Sharing::TYPE_SHARED);
		$filteredNotificationUsers = $this->userSettings->filterUsersBySetting($userIds, 'notification', Files_Sharing::TYPE_SHARED);

		$affectedUsers = $this->fixPathsForShareExceptions($affectedUsers, $shareId);
		$shouldFlush = $this->startActivityTransaction();
		foreach ($affectedUsers as $user => $path) {
			$emailSetting = $filteredEmailUsersInGroup[$user] ?? false;
			$notificationSetting = $filteredNotificationUsers[$user] ?? false;
			if ($emailSetting || $notificationSetting) {
				$this->addNotificationsForUser(
					$user,
					$actionUser,
					[[$fileSource->getId() => $path], $this->currentUser->getUserIdentifier()],
					$fileSource->getId(),
					$path,
					$fileSource instanceof File,
					$emailSetting,
					$notificationSetting,
				);
			}
		}
		$this->commitActivityTransaction($shouldFlush);
	}

	/**
	 * Check when there was a naming conflict and the target is different
	 * for some of the users
	 *
	 * @param array $affectedUsers
	 * @param int $shareId
	 * @return mixed
	 */
	protected function fixPathsForShareExceptions(array $affectedUsers, $shareId) {
		$queryBuilder = $this->connection->getQueryBuilder();
		$queryBuilder->select(['share_with', 'file_target'])
			->from('share')
			->where($queryBuilder->expr()->eq('parent', $queryBuilder->createParameter('parent')))
			->setParameter('parent', (int)$shareId);
		$query = $queryBuilder->executeQuery();

		while ($row = $query->fetch()) {
			$affectedUsers[$row['share_with']] = $row['file_target'];
		}

		return $affectedUsers;
	}

	/**
	 * Add notifications for the user that shares a file/folder
	 *
	 * @param string $subject
	 * @param string $shareWith
	 * @param Node $fileSource
	 */
	protected function shareNotificationForSharer(string $subject, string $shareWith, Node $fileSource, ?string $path = null) {
		$sharer = $this->currentUser->getUID();
		if ($sharer === null) {
			return;
		}
		if (!$path) {
			$path = $this->getUserRelativePath($sharer, $fileSource->getPath());
		}

		$this->addNotificationsForUser(
			$sharer, $subject, [[$fileSource->getId() => $path], $shareWith],
			$fileSource->getId(), $path, $fileSource instanceof File,
			$this->userSettings->getUserSetting($sharer, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($sharer, 'setting', 'batchtime') : false,
			(bool)$this->userSettings->getUserSetting($sharer, 'notification', Files_Sharing::TYPE_SHARED),
		);
	}

	/**
	 * Add notifications for the user that shares a file/folder
	 */
	protected function reshareNotificationForSharer(string $owner, string $subject, string $shareWith, int $sourceId, string $sourcePath, bool $isFile) {
		$this->addNotificationsForUser(
			$owner, $subject, [[$sourceId => $sourcePath], $this->currentUser->getUserIdentifier(), $shareWith],
			$sourceId, $sourcePath, $isFile,
			$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : false,
			(bool)$this->userSettings->getUserSetting($owner, 'notification', Files_Sharing::TYPE_SHARED),
		);
	}

	/**
	 * Add notifications for the owners whose files have been reshared
	 *
	 * @param string $sharedBy
	 * @param string $subject
	 * @param string $shareWith
	 * @param Node $fileSource
	 */
	protected function shareNotificationForOriginalOwners(string $sharedBy, string $subject, string $shareWith, Node $fileSource) {
		$mount = $fileSource->getMountPoint();
		if ($mount instanceof SharedMount) {
			$sourceShare = $mount->getShare();
			try {
				$sourceNode = $sourceShare->getNode();
			} catch (NotFoundException) {
				return;
			}

			if ($sourceShare->getShareOwner() !== $sharedBy) {
				$this->reshareNotificationForSharer(
					$sourceShare->getShareOwner(),
					$subject,
					$shareWith,
					$sourceNode->getId(),
					$this->getUserRelativePath($sourceShare->getShareOwner(), $sourceNode->getPath()),
					$sourceNode instanceof File,
				);
			}


			if ($sourceShare->getSharedBy() && $sourceShare->getSharedBy() !== $sharedBy && $sourceShare->getShareOwner() !== $sourceShare->getSharedBy()) {
				$this->reshareNotificationForSharer(
					$sourceShare->getSharedBy(),
					$subject,
					$shareWith,
					$sourceNode->getId(),
					$sourceShare->getTarget(),
					$sourceNode instanceof File,
				);
			}
		}
	}

	/**
	 * Adds the activity and email for a user when the settings require it
	 *
	 * @param string $user
	 * @param string $subject
	 * @param array $subjectParams
	 * @param int $fileId
	 * @param string $path
	 * @param bool $isFile If the item is a file, we link to the parent directory
	 * @param int|false $emailSetting
	 * @param bool $notificationSetting
	 * @param string $type
	 */
	protected function addNotificationsForUser($user, $subject, $subjectParams, $fileId, $path, $isFile, $emailSetting, $notificationSetting, $type = Files_Sharing::TYPE_SHARED) {
		$user = (string)$user;
		$selfAction = $user === $this->currentUser->getUID();
		$app = $type === Files_Sharing::TYPE_SHARED ? 'files_sharing' : 'files';
		$link = $this->urlGenerator->linkToRouteAbsolute('files.view.index', [
			'dir' => ($isFile) ? dirname($path) : $path,
		]);

		$objectType = ($fileId) ? 'files' : '';

		$event = $this->manager->generateEvent();
		try {
			$event->setApp($app)
				->setType($type)
				->setAffectedUser($user)
				->setTimestamp(time())
				->setSubject($subject, $subjectParams)
				->setObject($objectType, $fileId, $path)
				->setLink($link);

			if ($this->currentUser->getUID() !== null) {
				// Allow this to be empty for guests
				$event->setAuthor($this->currentUser->getUID());
			}
		} catch (\InvalidArgumentException $e) {
			$this->logger->error(
				$e->getMessage(),
				[
					'app' => 'activity',
					'exception' => $e,
				],
			);
		}

		// Add activity to stream
		$activityId = $this->activityData->send($event);

		if ($activityId && !$selfAction && $notificationSetting) {
			$this->notificationGenerator->sendNotificationForEvent($event, $activityId, $notificationSetting);
		}

		// Add activity to mail queue
		if ($emailSetting !== false && !$selfAction) {
			$latestSend = time() + $emailSetting;
			$this->activityData->storeMail($event, $latestSend);
		}
	}

	protected function startActivityTransaction(): bool {
		$this->connection->beginTransaction();
		return $this->notificationGenerator->deferNotifications();
	}

	protected function commitActivityTransaction(bool $shouldFlush): void {
		if ($shouldFlush) {
			$this->notificationGenerator->flushNotifications();
		}
		$this->connection->commit();
	}


	/**
	 * @param int $fileId
	 *
	 * @return array
	 */
	private function getAffectedUsersFromCachedMounts(int $fileId): array {
		$affectedUsers = $cachedMounts = [];
		$mountsForFile = $this->userMountCache->getMountsForFileId($fileId);
		foreach ($mountsForFile as $mount) {
			$affectedUsers[$mount->getUser()->getUID()] = $this->getVisiblePath($mount->getPath());
			$cachedMounts[] = [
				'userId' => $mount->getUser()->getUID(),
				'provider' => str_replace('\\\\', '\\', $mount->getMountProvider()),
				'path' => $mount->getPath(),
				'visiblePath' => $this->getVisiblePath($mount->getPath()),
				'storageId' => $mount->getStorageId(),
				'internalPath' => $mount->getInternalPath(),
				'rootInternalPath' => $mount->getRootInternalPath(),
			];
		}

		$unrelatedUsers = $this->getUnrelatedUsers($fileId, $cachedMounts);

		return array_filter($affectedUsers, function ($userId) use ($unrelatedUsers): bool {
			return !in_array($userId, $unrelatedUsers);
		}, ARRAY_FILTER_USE_KEY);
	}


	/**
	 * returns an array of users that have confirmed no access to fileId
	 *
	 * @param int $fileId
	 * @param array $cachedMounts
	 *
	 * @return string[] list of unrelated userIds
	 */
	private function getUnrelatedUsers(int $fileId, array $cachedMounts): array {
		/** @var \OCA\GroupFolders\ACL\RuleManager $ruleManager */
		/** @var \OCA\GroupFolders\Folder\FolderManager $folderManager */
		try {
			$ruleManager = \OCP\Server::get(\OCA\GroupFolders\ACL\RuleManager::class);
			$folderManager = \OCP\Server::get(\OCA\GroupFolders\Folder\FolderManager::class);
		} catch (\Throwable $e) {
			return []; // if we have no access to RuleManager, we cannot filter unrelated users
		}

		$groupFolderAclStatus = [];

		/** @var \OCA\GroupFolders\ACL\Rule[] $rules */
		$rules = $knownRules = $knownGroupRules = $usersToCheck = $cachedPath = [];
		foreach ($cachedMounts as $cachedMount) {
			// we are only interested in filtering GroupFolders ACL
			if ($cachedMount['provider'] !== 'OCA\GroupFolders\Mount\MountProvider') {
				continue;
			}

			// caching rules based on storage id
			$storageId = $cachedMount['storageId'];
			if (!array_key_exists($storageId, $knownRules)) {
				$knownRules[$storageId] = [];
			}

			$cachedPath[$cachedMount['userId']] = $fullPath = $cachedMount['path'];

			// caching rules based on storage+path to file
			if (!array_key_exists($cachedMount['visiblePath'], $knownRules[$storageId])) {
				// we need mountPoint and folderId to generate the correct path
				try {
					// only check for groupfolders
					if (!str_starts_with($cachedMount['rootInternalPath'], '__groupfolders')) {
						continue;
					}

					$folderId = (int)basename($cachedMount['rootInternalPath']);
					if (!isset($groupFolderAclStatus[$folderId])) {
						$groupFolderAclStatus[$folderId] = $folderManager->getFolderAclEnabled($folderId);
					}
					if (!$groupFolderAclStatus[$folderId]) {
						continue; // acl are disable
					}

					$folderPath = '/' . $cachedMount['rootInternalPath'];
					$path = $cachedMount['internalPath'];
				} catch (\Exception $e) {
					// in case of issue during the process, we can imagine the user have no access to the file
					$usersToCheck[] = $cachedMount['userId'];
					continue; // we'll catch rules on next user with access to the file
				}

				// we generate a list of path from top level of group folder to the file itself to get all rules
				$paths = [$folderPath];
				while ($path !== '') {
					$paths[] = $folderPath . '/' . $path;
					$path = dirname($path);
					if ($path === '.' || $path === '/') {
						$path = '';
					}
				}

				// we might already know the rules for some path of the list
				$paths = array_filter($paths, function (string $path) use ($knownRules, $storageId): bool {
					if (array_key_exists($path, $knownRules[$storageId])) {
						return false;
					}

					return true;
				});

				// we get and store the rules for each path from the list
				$rulesPerPath = $ruleManager->getAllRulesForPaths($storageId, $paths);
				foreach (array_keys($rulesPerPath) as $path) {
					$rules = array_merge($rules, $rulesPerPath[$path]);
				}

				$knownRules[$storageId][$cachedMount['visiblePath']] = true;
			}
		}

		// using each rules that disable read permission to generate a list of users
		// that might not have access to fileId
		foreach ($rules as $rule) {
			if (($rule->getMask() & Constants::PERMISSION_READ) === 0
				|| ($rule->getPermissions() & Constants::PERMISSION_READ) !== 0) {
				continue; // not interested of rules with 'mask' not including read capability (1), or if 'permission' does
			}

			$mapping = $rule->getUserMapping();
			$id = $mapping->getId();

			// if mapping is about user
			if ($mapping->getType() === 'user' && !in_array($id, $usersToCheck)) {
				$usersToCheck[] = $id;
			}

			// if mapping is about group
			if ($mapping->getType() === 'group'
				&& !in_array($mapping->getId(), $knownGroupRules)) {
				$knownGroupRules[] = $mapping->getId();

				$group = $this->groupManager->get($id);
				if ($group === null) {
					continue;
				}
				$userIds = array_map(function (IUser $user): string {
					return $user->getUID();
				}, $group->getUsers());

				// merge current user list with members of the group
				$usersToCheck = array_values(array_unique(array_merge($usersToCheck, $userIds)));
			}
		}


		// now that we have a list of eventuals filtered users, we confirm they have no access to the file
		$filteredUsers = [];
		foreach ($usersToCheck as $userId) {
			try {
				$node = $this->rootFolder->get($cachedPath[$userId]);
				if ($node->isReadable()) {
					continue; // overkill ? as rootFolder->get() would throw an exception if file is not available
				}
			} catch (\Throwable $e) {
			}

			$filteredUsers[] = $userId;
		}

		return $filteredUsers;
	}

	private function isDeletedNode(string $owner, int $nodeId): bool {
		try {
			$userFolder = $this->rootFolder->getUserFolder($owner);
			$node = $userFolder->getFirstNodeById($nodeId);
			return $node === null;
		} catch (NotFoundException $e) {
			return true;
		}
	}

	private function getUserRelativePath(string $userId, string $path): string {
		if (str_starts_with($path, '/' . $userId . '/files')) {
			return substr($path, strlen('/' . $userId . '/files'));
		} else {
			throw new NotFoundException("$path is not a path for $userId");
		}
	}
}
