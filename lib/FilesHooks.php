<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Frank Karlitschek <frank@karlitschek.de>
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity;

use OC\Files\Filesystem;
use OC\Files\View;
use OCA\Activity\BackgroundJob\RemoteActivity;
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCP\Activity\IManager;
use OCP\Files\IRootFolder;
use OCP\Files\Mount\IMountPoint;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\ILogger;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Share;
use OCP\Share\IShare;
use OCP\Share\IShareHelper;

/**
 * The class to handle the filesystem hooks
 */
class FilesHooks {
	const USER_BATCH_SIZE = 50;

	/** @var \OCP\Activity\IManager */
	protected $manager;

	/** @var \OCA\Activity\Data */
	protected $activityData;

	/** @var \OCA\Activity\UserSettings */
	protected $userSettings;

	/** @var \OCP\IGroupManager */
	protected $groupManager;

	/** @var \OCP\IDBConnection */
	protected $connection;

	/** @var \OC\Files\View */
	protected $view;

	/** @var IRootFolder */
	protected $rootFolder;

	/** @var IShareHelper */
	protected $shareHelper;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var ILogger */
	protected $logger;

	/** @var CurrentUser */
	protected $currentUser;

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

	/**
	 * Constructor
	 *
	 * @param IManager $manager
	 * @param Data $activityData
	 * @param UserSettings $userSettings
	 * @param IGroupManager $groupManager
	 * @param View $view
	 * @param IRootFolder $rootFolder
	 * @param IShareHelper $shareHelper
	 * @param IDBConnection $connection
	 * @param IURLGenerator $urlGenerator
	 * @param ILogger $logger
	 * @param CurrentUser $currentUser
	 */
	public function __construct(IManager $manager,
								Data $activityData,
								UserSettings $userSettings,
								IGroupManager $groupManager,
								View $view,
								IRootFolder $rootFolder,
								IShareHelper $shareHelper,
								IDBConnection $connection,
								IURLGenerator $urlGenerator,
								ILogger $logger,
								CurrentUser $currentUser) {
		$this->manager = $manager;
		$this->activityData = $activityData;
		$this->userSettings = $userSettings;
		$this->groupManager = $groupManager;
		$this->view = $view;
		$this->rootFolder = $rootFolder;
		$this->shareHelper = $shareHelper;
		$this->connection = $connection;
		$this->urlGenerator = $urlGenerator;
		$this->logger = $logger;
		$this->currentUser = $currentUser;
	}

	/**
	 * Store the create hook events
	 * @param string $path Path of the file that has been created
	 */
	public function fileCreate($path) {
		if ($path === '/' || $path === '' || $path === null) {
			return;
		}

		if ($this->currentUser->getUserIdentifier() !== '') {
			$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_CREATED, 'created_self', 'created_by');
		} else {
			$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_CREATED, '', 'created_public');
		}
	}

	/**
	 * Store the update hook events
	 * @param string $path Path of the file that has been modified
	 */
	public function fileUpdate($path) {
		$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_CHANGED, 'changed_self', 'changed_by');
	}

	/**
	 * Store the delete hook events
	 * @param string $path Path of the file that has been deleted
	 */
	public function fileDelete($path) {
		$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_DELETED, 'deleted_self', 'deleted_by');
	}

	/**
	 * Store the restore hook events
	 * @param string $path Path of the file that has been restored
	 */
	public function fileRestore($path) {
		$this->addNotificationsForFileAction($path, Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by');
	}

	/**
	 * Creates the entries for file actions on $file_path
	 *
	 * @param string $filePath         The file that is being changed
	 * @param int    $activityType     The activity type
	 * @param string $subject          The subject for the actor
	 * @param string $subjectBy        The subject for other users (with "by $actor")
	 */
	protected function addNotificationsForFileAction($filePath, $activityType, $subject, $subjectBy) {
		// Do not add activities for .part-files
		if (substr($filePath, -5) === '.part') {
			return;
		}

		list($filePath, $uidOwner, $fileId) = $this->getSourcePathAndOwner($filePath);
		if ($fileId === 0) {
			// Could not find the file for the owner ...
			return;
		}

		$accessList = $this->getUserPathsFromPath($filePath, $uidOwner);

		$this->generateRemoteActivity($accessList['remotes'], $activityType, time(), $this->currentUser->getCloudId(), $accessList['ownerPath']);

		$affectedUsers = $accessList['users'];
		$filteredStreamUsers = $this->userSettings->filterUsersBySetting(array_keys($affectedUsers), 'stream', $activityType);
		$filteredEmailUsers = $this->userSettings->filterUsersBySetting(array_keys($affectedUsers), 'email', $activityType);

		foreach ($affectedUsers as $user => $path) {
			$user = (string) $user;
			if (empty($filteredStreamUsers[$user]) && empty($filteredEmailUsers[$user])) {
				continue;
			}

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
				!empty($filteredStreamUsers[$user]),
				$filteredEmailUsers[$user] ?? false,
				$activityType
			);
		}
	}

	protected function generateRemoteActivity(array $remoteUsers, $type, $time, $actor, $ownerPath = false) {
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

			\OC::$server->getJobList()->add(RemoteActivity::class, $arguments);
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
		} else if (strpos($newDir, $oldDir) === 0) {
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

		list($this->oldParentPath, $this->oldParentOwner, $this->oldParentId) = $this->getSourcePathAndOwner($oldDir);
		if ($this->oldParentId === 0) {
			// Could not find the file for the owner ...
			$this->moveCase = false;
			return;
		}
		$this->oldAccessList = $this->getUserPathsFromPath($this->oldParentPath, $this->oldParentOwner);
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

		list(, , $fileId) = $this->getSourcePathAndOwner($newPath);
		list($parentPath, $parentOwner, $parentId) = $this->getSourcePathAndOwner($dirName);
		if ($fileId === 0 || $parentId === 0) {
			// Could not find the file for the owner ...
			return;
		}
		$accessList = $this->getUserPathsFromPath($parentPath, $parentOwner);

		$renameRemotes = [];
		foreach ($accessList['remotes'] as $remote => $info) {
			$renameRemotes[$remote] = [
				'token'       => $info['token'],
				'node_path'   => substr($newPath, strlen($info['node_path'])),
				'second_path' => substr($oldPath, strlen($info['node_path'])),
			];
		}
		$this->generateRemoteActivity($renameRemotes, Files::TYPE_SHARE_CHANGED, time(), $this->currentUser->getCloudId());

		$affectedUsers = $accessList['users'];
		$filteredStreamUsers = $this->userSettings->filterUsersBySetting(array_keys($affectedUsers), 'stream', Files::TYPE_SHARE_CHANGED);
		$filteredEmailUsers = $this->userSettings->filterUsersBySetting(array_keys($affectedUsers), 'email', Files::TYPE_SHARE_CHANGED);

		foreach ($affectedUsers as $user => $path) {
			if (empty($filteredStreamUsers[$user]) && empty($filteredEmailUsers[$user])) {
				continue;
			}

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
				!empty($filteredStreamUsers[$user]),
				$filteredEmailUsers[$user] ?? false,
				Files::TYPE_SHARE_CHANGED
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

		list(, , $fileId) = $this->getSourcePathAndOwner($newPath);
		list($parentPath, $parentOwner, $parentId) = $this->getSourcePathAndOwner($dirName);
		if ($fileId === 0 || $parentId === 0) {
			// Could not find the file for the owner ...
			return;
		}
		$accessList = $this->getUserPathsFromPath($parentPath, $parentOwner);
		$affectedUsers = $accessList['users'];
		$oldUsers = $this->oldAccessList['users'];

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
		$this->generateRemoteActivity($moveRemotes, Files::TYPE_SHARE_CHANGED, time(), $this->currentUser->getCloudId());
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

		$filteredStreamUsers = $this->userSettings->filterUsersBySetting($users, 'stream', Files::TYPE_SHARE_DELETED);
		$filteredEmailUsers = $this->userSettings->filterUsersBySetting($users, 'email', Files::TYPE_SHARE_DELETED);

		foreach ($users as $user) {
			if (empty($filteredStreamUsers[$user]) && empty($filteredEmailUsers[$user])) {
				continue;
			}

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
				!empty($filteredStreamUsers[$user]),
				$filteredEmailUsers[$user] ?? false,
				Files::TYPE_SHARE_DELETED
			);
		}
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

		$filteredStreamUsers = $this->userSettings->filterUsersBySetting($users, 'stream', Files::TYPE_SHARE_CREATED);
		$filteredEmailUsers = $this->userSettings->filterUsersBySetting($users, 'email', Files::TYPE_SHARE_CREATED);

		foreach ($users as $user) {
			if (empty($filteredStreamUsers[$user]) && empty($filteredEmailUsers[$user])) {
				continue;
			}

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
				!empty($filteredStreamUsers[$user]),
				$filteredEmailUsers[$user] ?? false,
				Files::TYPE_SHARE_CREATED
			);
		}
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

		$filteredStreamUsers = $this->userSettings->filterUsersBySetting($users, 'stream', Files::TYPE_SHARE_CHANGED);
		$filteredEmailUsers = $this->userSettings->filterUsersBySetting($users, 'email', Files::TYPE_SHARE_CHANGED);

		foreach ($users as $user) {
			if (empty($filteredStreamUsers[$user]) && empty($filteredEmailUsers[$user])) {
				continue;
			}

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
				!empty($filteredStreamUsers[$user]),
				$filteredEmailUsers[$user] ?? false,
				Files::TYPE_SHARE_CHANGED
			);
		}
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
		$sections = explode('/', $path, 4);

		$accessList['ownerPath'] = '/';
		if (isset($sections[3])) {
			// Not the case when a file in root is renamed
			$accessList['ownerPath'] .= $sections[3];
		}

		return $accessList;
	}

	/**
	 * Return the source
	 *
	 * @param string $path
	 * @return array
	 */
	protected function getSourcePathAndOwner($path) {
		$view = Filesystem::getView();
		$owner = $view->getOwner($path);
		$owner = !is_string($owner) || $owner === '' ? null : $owner;
		$fileId = 0;
		$currentUser = $this->currentUser->getUID();

		if ($owner === null || $owner !== $currentUser) {
			/** @var \OCP\Files\Storage\IStorage $storage */
			list($storage,) = $view->resolvePath($path);

			if ($owner !== null && !$storage->instanceOfStorage('OCA\Files_Sharing\External\Storage')) {
				Filesystem::initMountPoints($owner);
			} else {
				// Probably a remote user, let's try to at least generate activities
				// for the current user
				if ($currentUser === null) {
					list(,$owner,) = explode('/', $view->getAbsolutePath($path), 3);
				} else {
					$owner = $currentUser;
				}
			}
		}

		$info = Filesystem::getFileInfo($path);
		if ($info !== false) {
			$ownerView = new View('/' . $owner . '/files');
			$fileId = (int) $info['fileid'];
			$path = $ownerView->getPath($fileId);
		}

		return array($path, $owner, $fileId);
	}

	/**
	 * Manage sharing events
	 * @param array $params The hook params
	 */
	public function share($params) {
		if ($params['itemType'] === 'file' || $params['itemType'] === 'folder') {
			if ((int) $params['shareType'] === Share::SHARE_TYPE_USER) {
				$this->shareWithUser($params['shareWith'], (int) $params['fileSource'], $params['itemType'], $params['fileTarget']);
			} else if ((int) $params['shareType'] === Share::SHARE_TYPE_GROUP) {
				$this->shareWithGroup($params['shareWith'], (int) $params['fileSource'], $params['itemType'], $params['fileTarget'], (int) $params['id']);
			} else if ((int) $params['shareType'] === Share::SHARE_TYPE_LINK) {
				$this->shareByLink((int) $params['fileSource'], $params['itemType'], $params['uidOwner']);
			} else if ((int) $params['shareType'] === Share::SHARE_TYPE_CIRCLE) {
				$this->shareWithCircle($params['shareWith'], (int) $params['fileSource'], $params['itemType'], $params['fileTarget'], (int) $params['id']);
			}
		}
	}

	/**
	 * Sharing a file or folder with a user
	 *
	 * @param string $shareWith
	 * @param int $fileSource File ID that is being shared
	 * @param string $itemType File type that is being shared (file or folder)
	 * @param string $fileTarget File path
	 */
	protected function shareWithUser($shareWith, $fileSource, $itemType, $fileTarget) {
		// User performing the share
		$this->shareNotificationForSharer('shared_user_self', $shareWith, $fileSource, $itemType);
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'reshared_user_by', $shareWith, $fileSource, $itemType);
		}

		// New shared user
		$this->addNotificationsForUser(
			$shareWith, 'shared_with_by', [[$fileSource => $fileTarget], $this->currentUser->getUserIdentifier()],
			(int) $fileSource, $fileTarget, $itemType === 'file',
			$this->userSettings->getUserSetting($shareWith, 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($shareWith, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($shareWith, 'setting', 'batchtime') : false
		);
	}

	/**
	 * Sharing a file or folder with a group
	 *
	 * @param string $shareWith
	 * @param int $fileSource File ID that is being shared
	 * @param string $itemType File type that is being shared (file or folder)
	 * @param string $fileTarget File path
	 * @param int $shareId The Share ID of this share
	 */
	protected function shareWithGroup($shareWith, $fileSource, $itemType, $fileTarget, $shareId) {
		// Members of the new group
		$group = $this->groupManager->get($shareWith);
		if (!($group instanceof IGroup)) {
			return;
		}

		// User performing the share
		$this->shareNotificationForSharer('shared_group_self', $shareWith, $fileSource, $itemType);
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'reshared_group_by', $shareWith, $fileSource, $itemType);
		}

		$offset = 0;
		$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		while (!empty($users)) {
			$this->addNotificationsForGroupUsers($users, 'shared_with_by', $fileSource, $itemType, $fileTarget, $shareId);
			$offset += self::USER_BATCH_SIZE;
			$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		}
	}

	/**
	 * Sharing a file or folder via link/public
	 *
	 * @param int $fileSource File ID that is being shared
	 * @param string $itemType File type that is being shared (file or folder)
	 * @param string $linkOwner
	 */
	protected function shareByLink($fileSource, $itemType, $linkOwner) {
		$this->view->chroot('/' . $linkOwner . '/files');

		try {
			$path = $this->view->getPath($fileSource);
		} catch (NotFoundException $e) {
			return;
		}

		$this->shareNotificationForOriginalOwners($linkOwner, 'reshared_link_by', '', $fileSource, $itemType);

		$this->addNotificationsForUser(
			$linkOwner, 'shared_link_self', [[$fileSource => $path]],
			(int) $fileSource, $path, $itemType === 'file',
			$this->userSettings->getUserSetting($linkOwner, 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($linkOwner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($linkOwner, 'setting', 'batchtime') : false
		);
	}

	/**
	 * Manage unsharing events
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	public function unShare(IShare $share) {
		if (in_array($share->getNodeType(), ['file', 'folder'], true)) {
			if ($share->getShareType() === Share::SHARE_TYPE_USER) {
				$this->unshareFromUser($share);
			} else if ($share->getShareType() === Share::SHARE_TYPE_GROUP) {
				$this->unshareFromGroup($share);
			} else if ($share->getShareType() === Share::SHARE_TYPE_LINK) {
				$this->unshareLink($share);
			} else if ($share->getShareType() === Share::SHARE_TYPE_CIRCLE) {
				$this->unshareFromCircle($share);
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
		// User performing the share
		$this->shareNotificationForSharer('unshared_user_self', $share->getSharedWith(), $share->getNodeId(), $share->getNodeType());

		// Owner
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'unshared_user_by', $share->getSharedWith(), $share->getNodeId(), $share->getNodeType());
		}

		// Recipient
		$this->addNotificationsForUser(
			$share->getSharedWith(), 'unshared_by', [[$share->getNodeId() => $share->getTarget()], $this->currentUser->getUserIdentifier()],
			$share->getNodeId(), $share->getTarget(), $share->getNodeType() === 'file',
			$this->userSettings->getUserSetting($share->getSharedWith(), 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($share->getSharedWith(), 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($share->getSharedWith(), 'setting', 'batchtime') : false
		);
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

		// User performing the share
		$this->shareNotificationForSharer('unshared_group_self', $share->getSharedWith(), $share->getNodeId(), $share->getNodeType());
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'unshared_group_by', $share->getSharedWith(), $share->getNodeId(), $share->getNodeType());
		}

		$offset = 0;
		$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		while (!empty($users)) {
			$this->addNotificationsForGroupUsers($users, 'unshared_by', $share->getNodeId(), $share->getNodeType(), $share->getTarget(), $share->getId());
			$offset += self::USER_BATCH_SIZE;
			$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
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
		if ($this->currentUser->getUID() === null) {
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
			$this->userSettings->getUserSetting($owner, 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : false
		);

		if ($share->getSharedBy() !== $share->getShareOwner()) {
			$owner = $share->getShareOwner();
			$this->addNotificationsForUser(
				$owner, $actionOwner, [[$share->getNodeId() => $share->getTarget()], $share->getSharedBy()],
				$share->getNodeId(), $share->getTarget(), $share->getNodeType() === 'file',
				$this->userSettings->getUserSetting($owner, 'stream', Files_Sharing::TYPE_SHARED),
				$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : false
			);
		}
	}

	/**
	 * @param IUser[] $usersInGroup
	 * @param string $actionUser
	 * @param int $fileSource File ID that is being shared
	 * @param string $itemType File type that is being shared (file or folder)
	 * @param string $fileTarget File path
	 * @param int $shareId The Share ID of this share
	 */
	protected function addNotificationsForGroupUsers(array $usersInGroup, $actionUser, $fileSource, $itemType, $fileTarget, $shareId) {
		$affectedUsers = [];

		foreach ($usersInGroup as $user) {
			$affectedUsers[$user->getUID()] = $fileTarget;
		}

		// Remove the triggering user, we already managed his notifications
		unset($affectedUsers[$this->currentUser->getUID()]);

		if (empty($affectedUsers)) {
			return;
		}

		$userIds = array_keys($affectedUsers);
		$filteredStreamUsersInGroup = $this->userSettings->filterUsersBySetting($userIds, 'stream', Files_Sharing::TYPE_SHARED);
		$filteredEmailUsersInGroup = $this->userSettings->filterUsersBySetting($userIds, 'email', Files_Sharing::TYPE_SHARED);

		$affectedUsers = $this->fixPathsForShareExceptions($affectedUsers, $shareId);
		foreach ($affectedUsers as $user => $path) {
			if (empty($filteredStreamUsersInGroup[$user]) && empty($filteredEmailUsersInGroup[$user])) {
				continue;
			}

			$this->addNotificationsForUser(
				$user, $actionUser, [[$fileSource => $path], $this->currentUser->getUserIdentifier()],
				$fileSource, $path, ($itemType === 'file'),
				!empty($filteredStreamUsersInGroup[$user]),
				$filteredEmailUsersInGroup[$user] ?? false
			);
		}
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
			->setParameter('parent', (int) $shareId);
		$query = $queryBuilder->execute();

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
	 * @param int $fileSource
	 * @param string $itemType
	 */
	protected function shareNotificationForSharer($subject, $shareWith, $fileSource, $itemType) {
		$sharer = $this->currentUser->getUID();
		if ($sharer === null) {
			return;
		}

		$this->view->chroot('/' . $sharer . '/files');

		try {
			$path = $this->view->getPath($fileSource);
		} catch (NotFoundException $e) {
			return;
		}

		$this->addNotificationsForUser(
			$sharer, $subject, [[$fileSource => $path], $shareWith],
			$fileSource, $path, ($itemType === 'file'),
			$this->userSettings->getUserSetting($sharer, 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($sharer, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($sharer, 'setting', 'batchtime') : false
		);
	}

	/**
	 * Add notifications for the user that shares a file/folder
	 *
	 * @param string $owner
	 * @param string $subject
	 * @param string $shareWith
	 * @param int $fileSource
	 * @param string $itemType
	 */
	protected function reshareNotificationForSharer($owner, $subject, $shareWith, $fileSource, $itemType) {
		$this->view->chroot('/' . $owner . '/files');

		try {
			$path = $this->view->getPath($fileSource);
		} catch (NotFoundException $e) {
			return;
		}

		$this->addNotificationsForUser(
			$owner, $subject, [[$fileSource => $path], $this->currentUser->getUserIdentifier(), $shareWith],
			$fileSource, $path, ($itemType === 'file'),
			$this->userSettings->getUserSetting($owner, 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : false
		);
	}

	/**
	 * Add notifications for the owners whose files have been reshared
	 *
	 * @param string $currentOwner
	 * @param string $subject
	 * @param string $shareWith
	 * @param int $fileSource
	 * @param string $itemType
	 */
	protected function shareNotificationForOriginalOwners($currentOwner, $subject, $shareWith, $fileSource, $itemType) {
		// Get the full path of the current user
		$this->view->chroot('/' . $currentOwner . '/files');

		try {
			$path = $this->view->getPath($fileSource);
		} catch (NotFoundException $e) {
			return;
		}

		/**
		 * Get the original owner and his path
		 */
		$owner = $this->view->getOwner($path);
		if ($owner !== $currentOwner) {
			$this->reshareNotificationForSharer($owner, $subject, $shareWith, $fileSource, $itemType);
		}

		/**
		 * Get the sharee who shared the item with the currentUser
		 */
		$this->view->chroot('/' . $currentOwner . '/files');
		$mount = $this->view->getMount($path);
		if (!($mount instanceof IMountPoint)) {
			return;
		}

		$storage = $mount->getStorage();
		if (!$storage->instanceOfStorage('OCA\Files_Sharing\SharedStorage')) {
			return;
		}

		/** @var \OCA\Files_Sharing\SharedStorage $storage */
		$shareOwner = $storage->getSharedFrom();
		if ($shareOwner === '' || $shareOwner === null || $shareOwner === $owner || $shareOwner === $currentOwner) {
			return;
		}

		$this->reshareNotificationForSharer($shareOwner, $subject, $shareWith, $fileSource, $itemType);
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
	 * @param bool $streamSetting
	 * @param int $emailSetting
	 * @param string $type
	 */
	protected function addNotificationsForUser($user, $subject, $subjectParams, $fileId, $path, $isFile, $streamSetting, $emailSetting, $type = Files_Sharing::TYPE_SHARED) {
		if (!$streamSetting && !$emailSetting) {
			return;
		}

		$user = (string)$user;
		$selfAction = $user === $this->currentUser->getUID();
		$app = $type === Files_Sharing::TYPE_SHARED ? 'files_sharing' : 'files';
		$link = $this->urlGenerator->linkToRouteAbsolute('files.view.index', array(
			'dir' => ($isFile) ? dirname($path) : $path,
		));

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
			$this->logger->logException($e);
		}

		// Add activity to stream
		if ($streamSetting && (!$selfAction || $this->userSettings->getUserSetting($this->currentUser->getUID(), 'setting', 'self'))) {
			$this->activityData->send($event);
		}

		// Add activity to mail queue
		if ($emailSetting !== false && (!$selfAction || $this->userSettings->getUserSetting($this->currentUser->getUID(), 'setting', 'selfemail'))) {
			$latestSend = time() + $emailSetting;
			$this->activityData->storeMail($event, $latestSend);
		}
	}
	

	/**
	 * Sharing a file or folder with a circle
	 *
	 * @param string $shareWith
	 * @param int $fileSource File ID that is being shared
	 * @param string $itemType File type that is being shared (file or folder)
	 * @param string $fileTarget File path
	 * @param int $shareId The Share ID of this share
	 */
	protected function shareWithCircle($shareWith, $fileSource, $itemType, $fileTarget, $shareId) {
		// User performing the share
		$this->shareNotificationForSharer('shared_circle_self', $shareWith, $fileSource, $itemType);
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'reshared_circle_by', $shareWith, $fileSource, $itemType);
		}
	}

	/**
	 * Unsharing a file or folder from a circle
	 *
	 * @param IShare $share
	 * @throws \OCP\Files\NotFoundException
	 */
	protected function unshareFromCircle(IShare $share) {
	
		// User performing the share
		$this->shareNotificationForSharer('unshared_circle_self', $share->getSharedWith(), $share->getNodeId(), $share->getNodeType());
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), 'unshared_circle_by', $share->getSharedWith(), $share->getNodeId(), $share->getNodeType());
		}
	}
}
