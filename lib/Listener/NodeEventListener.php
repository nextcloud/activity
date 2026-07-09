<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Listener;

use OCA\Activity\BackgroundJob\RemoteActivity;
use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCA\Activity\NotificationGenerator;
use OCA\Activity\UserSettings;
use OCA\Files_Trashbin\Events\NodeRestoredEvent;
use OCP\Activity\IManager;
use OCP\BackgroundJob\IJobList;
use OCP\Constants;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Files\Config\IUserMountCache;
use OCP\Files\Events\Node\BeforeNodeRenamedEvent;
use OCP\Files\Events\Node\NodeCreatedEvent;
use OCP\Files\Events\Node\NodeDeletedEvent;
use OCP\Files\Events\Node\NodeRenamedEvent;
use OCP\Files\Events\Node\NodeWrittenEvent;
use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\ISetupManager;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IGroupManager;
use OCP\ITagManager;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Share\IShareHelper;
use Psr\Log\LoggerInterface;

/**
 * The class to handle the share events
 * @template-implements IEventListener<NodeCreatedEvent|NodeDeletedEvent|NodeWrittenEvent|BeforeNodeRenamedEvent|NodeRenamedEvent>
 */
class NodeEventListener implements IEventListener {
	private string|false $moveCase = false;
	private ?string $oldParentPath = null;
	private ?string $oldParentOwner = null;
	private ?int $oldParentId = null;
	protected array $oldAccessList;

	public function __construct(
		private readonly CurrentUser $currentUser,
		private readonly IShareHelper $shareHelper,
		private readonly IJobList $jobList,
		private readonly IConfig $config,
		private readonly IUserMountCache $userMountCache,
		private readonly IGroupManager $groupManager,
		private readonly IRootFolder $rootFolder,
		private readonly UserSettings $userSettings,
		private readonly LoggerInterface $logger,
		private readonly IURLGenerator $urlGenerator,
		private readonly ITagManager $tagManager,
		private readonly IManager $manager,
		private readonly Data $activityData,
		private readonly NotificationGenerator $notificationGenerator,
		private readonly ISetupManager $setupManager,
		private readonly IDBConnection $connection,
	) {

	}

	public function handle(Event $event): void {
		if ($event instanceof NodeCreatedEvent) {
			$this->handleNodeCreated($event);
		} elseif ($event instanceof NodeDeletedEvent) {
			$this->handleNodeDelete($event);
		} elseif ($event instanceof NodeWrittenEvent) {
			$this->handleNodeWritten($event);
		} elseif ($event instanceof NodeRenamedEvent) {
			$this->handleNodeMoved($event);
		} elseif ($event instanceof BeforeNodeRenamedEvent) {
			$this->handleBeforeNodeMoved($event);
		} elseif ($event instanceof NodeRestoredEvent) {
			$this->handleNodeRestored($event);
		}
	}

	private function handleNodeCreated(NodeCreatedEvent $event): void {
		if ($this->currentUser->getUserIdentifier() === '' && $this->currentUser->isPublicShareToken()) {
			$this->addNotificationsForFileAction($event->getNode(), Files_Sharing::TYPE_PUBLIC_UPLOAD, '', 'created_public');
		} else {
			$this->addNotificationsForFileAction($event->getNode(), Files::TYPE_SHARE_CREATED, 'created_self', 'created_by');
		}
	}

	public function handleNodeDelete(NodeDeletedEvent $event): void {
		$this->addNotificationsForFileAction($event->getNode(), Files::TYPE_SHARE_DELETED, 'deleted_self', 'deleted_by');
	}

	public function handleNodeWritten(NodeWrittenEvent $event): void {
		$this->addNotificationsForFileAction($event->getNode(), Files::TYPE_FILE_CHANGED, 'changed_self', 'changed_by');
	}

	public function handleNodeRestored(NodeRestoredEvent $event): void {
		$this->addNotificationsForFileAction($event->getTarget(), Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by');
	}

	/**
	 * Collect some information for move/renames
	 */
	public function handleBeforeNodeMoved(BeforeNodeRenamedEvent $event): void {
		$oldPath = $event->getSource()->getPath();
		$newPath = $event->getTarget()->getPath();

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

		[$this->oldParentPath, $this->oldParentOwner, $this->oldParentId] = $this->getSourcePathAndOwner($event->getSource()->getParent());
		if ($this->oldParentId === 0) {
			// Could not find the file for the owner ...
			$this->moveCase = false;
			return;
		}

		$oldAccessList = $this->getUserPathsFromPath($this->oldParentPath, $this->oldParentOwner);

		// file can be shared using GroupFolders, including ACL check
		if ($this->config->getSystemValueBool('activity_use_cached_mountpoints', false)) {
			$oldFileId = $event->getSource()->getId();
			$oldAccessList['users'] = array_merge($oldAccessList['users'], $this->getAffectedUsersFromCachedMounts($oldFileId));
		}

		$this->oldAccessList = $oldAccessList;
	}

	/**
	 * Returns a "username => path" map for all affected users
	 *
	 * @param string $path
	 * @param string $uidOwner
	 * @return array{ownerPath?: string, remotes: array<string, array{node_path: string, token: string}>, users: array<string, string>}
	 */
	protected function getUserPathsFromPath($path, $uidOwner) {
		$emptyResult = ['users' => [], 'remotes' => []];

		try {
			$node = $this->rootFolder->getUserFolder($uidOwner)->get($path);
		} catch (NotFoundException $e) {
			return $emptyResult;
		}

		if (!$node instanceof Node) {
			return $emptyResult;
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
	 * @return array
	 */
	protected function getSourcePathAndOwner(Folder $node): array {
		$owner = $node->getOwner();
		$currentUser = $this->currentUser->getUser();

		if ($owner === null || $owner->getUID() !== $currentUser->getUID()) {
			$storage = $node->getStorage();

			if ($owner !== null && !$storage->instanceOfStorage('OCA\Files_Sharing\External\Storage')) {
				$this->setupManager->setupForUser($owner);
			} else {
				// Probably a remote user, let's try to at least generate activities
				// for the current user
				if ($currentUser === null) {
					[, $owner,] = explode('/', $node->getInternalPath(), 3);
				} else {
					$owner = $currentUser;
				}
			}
		}

		return [$node->getInternalPath(), $owner, $node->getId()];
	}

	public function handleNodeMoved(NodeRenamedEvent $event): void {
		// Do not add activities for .part-files
		if ($this->moveCase === false) {
			return;
		}

		switch ($this->moveCase) {
			case 'rename':
				$this->fileRenaming($event);
				break;
			case 'moveUp':
			case 'moveDown':
			case 'moveCross':
				$this->fileMoving($event);
				break;
		}

		$this->moveCase = false;
	}

	/**
	 * Renaming a file inside the same folder (a/b to a/c)
	 */
	protected function fileRenaming(NodeRenamedEvent $event): void {
		$oldPath = $event->getSource()->getPath();
		$newPath = $event->getTarget()->getPath();

		$dirName = dirname($newPath);
		$fileName = basename($newPath);
		$oldFileName = basename($oldPath);

		$fileId = $event->getTarget()->getId();
		[$parentPath, $parentOwner, $parentId] = $this->getSourcePathAndOwner($event->getSource()->getParent());
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
	protected function fileMoving(NodeRenamedEvent $event) {
		$oldPath = $event->getSource()->getPath();
		$newPath = $event->getTarget()->getPath();

		$dirName = dirname($newPath);
		$fileName = basename($newPath);
		$oldFileName = basename($oldPath);

		$fileId = $event->getTarget()->getId();
		[$parentPath, $parentOwner, $parentId] = $this->getSourcePathAndOwner($event->getTarget()->getParent());
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

	private function addNotificationsForFileAction(Node $node, string $activityType, string $subject, string $subjectBy) {
		$filePath = $node->getPath();

		if (substr($filePath, -5) === '.part') {
			// Do not add activities for .part-files
			return;
		}

		if (str_starts_with($node->getPath(), $this->rootFolder->getAppDataDirectoryName())) {
			// Do not add activities for internal files
			return;
		}

		$accessList = $this->getUserPathsFromNode($node);

		if ($accessList['remotes'] !== []) {
			$this->generateRemoteActivity($accessList['remotes'], $activityType, time(), $this->currentUser->getCloudId(), $accessList['ownerPath']);
		}

		$affectedUsers = $accessList['users'];

		// file can be shared using GroupFolders, including ACL check
		if ($this->config->getSystemValueBool('activity_use_cached_mountpoints', false)) {
			$affectedUsers = array_merge($affectedUsers, $this->getAffectedUsersFromCachedMounts($node->getId()));
		}

		[$filteredEmailUsers, $filteredNotificationUsers]
			= $this->getFileChangeActivitySettings($node->getId(), array_keys($affectedUsers), $activityType);

		foreach ($affectedUsers as $user => $path) {
			$user = (string)$user;

			if ($user === $this->currentUser->getUID()) {
				$userSubject = $subject;
				$userParams = [[$node->getId() => $path]];
			} else {
				$userSubject = $subjectBy;
				$userParams = [[$node->getId() => $path], $this->currentUser->getUserIdentifier()];
			}

			$this->addNotificationsForUser(
				$user, $userSubject, $userParams,
				$node->getId(), $path, true,
				$filteredEmailUsers[$user] ?? false,
				$filteredNotificationUsers[$user] ?? false,
				$activityType,
			);
		}
	}

	/**
	 * @param Node $node
	 * @return array{
	 *     users: array<string, string>,
	 *     remotes: array<string, array{token: string, node_path: string}>,
	 *     ownerPath: string,
	 * }
	 */
	protected function getUserPathsFromNode(Node $node): array {
		$accessList = $this->shareHelper->getPathsForAccessList($node);

		$path = $node->getPath();
		$accessList['ownerPath'] = $this->getVisiblePath($path);
		return $accessList;
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

			$this->jobList->add(RemoteActivity::class, $arguments);
		}
	}

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

		$unrelatedUsers = $this->getUnrelatedUsers($cachedMounts);

		return array_filter($affectedUsers, fn ($userId): bool => !in_array($userId, $unrelatedUsers), ARRAY_FILTER_USE_KEY);
	}

	/**
	 * returns an array of users that have confirmed no access to fileId
	 *
	 * @param array $cachedMounts
	 *
	 * @return string[] list of unrelated userIds
	 */
	private function getUnrelatedUsers(array $cachedMounts): array {
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
				$userIds = array_map(static fn (IUser $user): string => $user->getUID(), $group->getUsers());

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
	 * Adds the activity and email for a user when the settings require it
	 *
	 * @param bool $isFile If the item is a file, we link to the parent directory
	 */
	protected function addNotificationsForUser(string $user, string $subject, array $subjectParams, ?int $fileId, string $path, bool $isFile, int|false $emailSetting, bool $notificationSetting, string $type = Files_Sharing::TYPE_SHARED) {
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
			$this->notificationGenerator->sendNotificationForEvent($event, $activityId);
		}

		// Add activity to mail queue
		if ($emailSetting !== false && !$selfAction) {
			$latestSend = time() + $emailSetting;
			$this->activityData->storeMail($event, $latestSend);
		}
	}

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
}
