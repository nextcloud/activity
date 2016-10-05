<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @copyright Copyright (c) 2016, Olivier Paroz
 *
 * @author Frank Karlitschek <frank@karlitschek.de>
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 * @author Olivier Paroz <developer@oparoz.com>
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
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCP\Activity\IManager;
use OCP\Files\Mount\IMountPoint;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Share;
use OCP\IRequest;

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

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var CurrentUser */
	protected $currentUser;

	/** @var IRequest */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param IManager $manager
	 * @param Data $activityData
	 * @param UserSettings $userSettings
	 * @param IGroupManager $groupManager
	 * @param View $view
	 * @param IDBConnection $connection
	 * @param IURLGenerator $urlGenerator
	 * @param CurrentUser $currentUser
	 * @param IRequest $request
	 */
	public function __construct(IManager $manager, Data $activityData, UserSettings $userSettings, IGroupManager $groupManager, View $view, IDBConnection $connection, IURLGenerator $urlGenerator, CurrentUser $currentUser, IRequest $request) {
		$this->manager = $manager;
		$this->activityData = $activityData;
		$this->userSettings = $userSettings;
		$this->groupManager = $groupManager;
		$this->view = $view;
		$this->connection = $connection;
		$this->urlGenerator = $urlGenerator;
		$this->currentUser = $currentUser;
		$this->request = $request;
	}

	/**
	 * Store the create hook events
	 * @param string $path Path of the file that has been created
	 */
	public function fileCreate($path) {
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

		$affectedUsers = $this->getUserPathsFromPath($filePath, $uidOwner);
		$filteredStreamUsers = $this->userSettings->filterUsersBySetting(array_keys($affectedUsers), 'stream', $activityType);
		$filteredEmailUsers = $this->userSettings->filterUsersBySetting(array_keys($affectedUsers), 'email', $activityType);

		foreach ($affectedUsers as $user => $path) {
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
				!empty($filteredEmailUsers[$user]) ? $filteredEmailUsers[$user] : 0,
				$activityType
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
		return Share::getUsersSharingFile($path, $uidOwner, true, true);
	}

	/**
	 * Return the source
	 *
	 * @param string $path
	 * @return array
	 */
	protected function getSourcePathAndOwner($path) {
		$view = Filesystem::getView();
		$uidOwner = $view->getOwner($path);
		$fileId = 0;

		if ($uidOwner !== $this->currentUser->getUID()) {
			/** @var \OCP\Files\Storage\IStorage $storage */
			list($storage,) = $view->resolvePath($path);
			if (!$storage->instanceOfStorage('OCA\Files_Sharing\External\Storage')) {
				Filesystem::initMountPoints($uidOwner);
			} else {
				// Probably a remote user, let's try to at least generate activities
				// for the current user
				$uidOwner = $this->currentUser->getUID();
			}
		}

		$info = Filesystem::getFileInfo($path);
		if ($info !== false) {
			$ownerView = new View('/' . $uidOwner . '/files');
			$fileId = (int) $info['fileid'];
			$path = $ownerView->getPath($fileId);
		}

		return array($path, $uidOwner, $fileId);
	}

	/**
	 * Manage sharing events
	 * @param array $params The hook params
	 */
	public function share($params) {
		if ($params['itemType'] === 'file' || $params['itemType'] === 'folder') {
			if ((int) $params['shareType'] === Share::SHARE_TYPE_USER) {
				$this->shareFileOrFolderWithUser($params['shareWith'], (int) $params['fileSource'], $params['itemType'], $params['fileTarget'], true);
			} else if ((int) $params['shareType'] === Share::SHARE_TYPE_GROUP) {
				$this->shareFileOrFolderWithGroup($params['shareWith'], (int) $params['fileSource'], $params['itemType'], $params['fileTarget'], (int) $params['id'], true);
			} else if ((int) $params['shareType'] === Share::SHARE_TYPE_LINK) {
				$this->shareFileOrFolderByLink((int) $params['fileSource'], $params['itemType'], $params['uidOwner'], true);
			}
		}
	}

	/**
	 * Manage sharing events
	 * @param array $params The hook params
	 */
	public function unShare($params) {
		if ($params['itemType'] === 'file' || $params['itemType'] === 'folder') {
			if ((int) $params['shareType'] === Share::SHARE_TYPE_USER) {
				$this->shareFileOrFolderWithUser($params['shareWith'], (int) $params['fileSource'], $params['itemType'], $params['fileTarget'], false);
			} else if ((int) $params['shareType'] === Share::SHARE_TYPE_GROUP) {
				$this->shareFileOrFolderWithGroup($params['shareWith'], (int) $params['fileSource'], $params['itemType'], $params['fileTarget'], (int) $params['id'], false);
			} else if ((int) $params['shareType'] === Share::SHARE_TYPE_LINK) {
				$this->shareFileOrFolderByLink((int) $params['fileSource'], $params['itemType'], $params['uidOwner'], false);
			}
		}
	}

	/**
	 * Manage share download events
	 *
	 * @param array $params The hook params
	 */
	public function downloadedShare($params) {
		list($ownerPath, $uidOwner, $fileId) = $this->getSourcePathAndOwner($params['target']);

		$client = 'web';
		if ($this->request->isUserAgent([IRequest::USER_AGENT_CLIENT_DESKTOP])) {
			$client = 'desktop';
		} else if ($this->request->isUserAgent([IRequest::USER_AGENT_CLIENT_ANDROID, IRequest::USER_AGENT_CLIENT_IOS])) {
			$client = 'mobile';
		}
		$subjectParams = [[$fileId => $ownerPath], $this->currentUser->getUserIdentifier(), $client];

		$this->addNotificationsForUser(
			$uidOwner, 'shared_file_downloaded', $subjectParams,
			$fileId, $ownerPath, true,
			$this->userSettings->getUserSetting(
				$uidOwner, 'stream', Files_Sharing::TYPE_PUBLIC_LINKS
			),
			$this->userSettings->getUserSetting(
				$uidOwner, 'email', Files_Sharing::TYPE_PUBLIC_LINKS
			) ? $this->userSettings->getUserSetting($uidOwner, 'setting', 'batchtime') : 0,
			Files_Sharing::TYPE_PUBLIC_LINKS
		);
	}

	/**
	 * Sharing a file or folder with a user
	 *
	 * @param string $shareWith
	 * @param int $fileSource File ID that is being shared
	 * @param string $itemType File type that is being shared (file or folder)
	 * @param string $fileTarget File path
	 * @param bool $isSharing True if sharing, false if unsharing
	 */
	protected function shareFileOrFolderWithUser($shareWith, $fileSource, $itemType, $fileTarget, $isSharing) {
		if ($isSharing) {
			$actionSharer = 'shared_user_self';
			$actionOwner = 'reshared_user_by';
			$actionUser = 'shared_with_by';
		} else {
			$actionSharer = 'unshared_user_self';
			$actionOwner = 'unshared_user_by';
			$actionUser = 'unshared_by';
		}

		// User performing the share
		$this->shareNotificationForSharer($actionSharer, $shareWith, $fileSource, $itemType);
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), $actionOwner, $shareWith, $fileSource, $itemType);
		}

		// New shared user
		$this->addNotificationsForUser(
			$shareWith, $actionUser, [[$fileSource => $fileTarget], $this->currentUser->getUserIdentifier()],
			(int) $fileSource, $fileTarget, ($itemType === 'file'),
			$this->userSettings->getUserSetting($shareWith, 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($shareWith, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($shareWith, 'setting', 'batchtime') : 0
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
	 * @param bool $isSharing True if sharing, false if unsharing
	 */
	protected function shareFileOrFolderWithGroup($shareWith, $fileSource, $itemType, $fileTarget, $shareId, $isSharing) {
		if ($isSharing) {
			$actionSharer = 'shared_group_self';
			$actionOwner = 'reshared_group_by';
			$actionUser = 'shared_with_by';
		} else {
			$actionSharer = 'unshared_group_self';
			$actionOwner = 'unshared_group_by';
			$actionUser = 'unshared_by';
		}

		// Members of the new group
		$group = $this->groupManager->get($shareWith);
		if (!($group instanceof IGroup)) {
			return;
		}

		// User performing the share
		$this->shareNotificationForSharer($actionSharer, $shareWith, $fileSource, $itemType);
		if ($this->currentUser->getUID() !== null) {
			$this->shareNotificationForOriginalOwners($this->currentUser->getUID(), $actionOwner, $shareWith, $fileSource, $itemType);
		}

		$offset = 0;
		$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
		while (!empty($users)) {
			$this->addNotificationsForGroupUsers($users, $actionUser, $fileSource, $itemType, $fileTarget, $shareId);
			$offset += self::USER_BATCH_SIZE;
			$users = $group->searchUsers('', self::USER_BATCH_SIZE, $offset);
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
				!empty($filteredEmailUsersInGroup[$user]) ? $filteredEmailUsersInGroup[$user] : 0
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
	 * Sharing a file or folder via link/public
	 *
	 * @param int $fileSource File ID that is being shared
	 * @param string $itemType File type that is being shared (file or folder)
	 * @param string $linkOwner
	 * @param bool $isSharing True if sharing, false if unsharing
	 */
	protected function shareFileOrFolderByLink($fileSource, $itemType, $linkOwner, $isSharing) {
		$owner = $this->currentUser->getUID();
		if ($isSharing) {
			$actionSharer = 'shared_link_self';
			$actionOwner = 'reshared_link_by';
		} else if ($owner !== $linkOwner) {
			// Link expired
			$actionSharer = 'link_expired';
			$actionOwner = 'link_by_expired';
			$owner = $linkOwner;
			\OC::$server->getUserFolder($linkOwner);
		} else {
			$actionSharer = 'unshared_link_self';
			$actionOwner = 'unshared_link_by';
		}

		$this->view->chroot('/' . $owner . '/files');

		try {
			$path = $this->view->getPath($fileSource);
		} catch (NotFoundException $e) {
			return;
		}

		$this->shareNotificationForOriginalOwners($owner, $actionOwner, '', $fileSource, $itemType);

		$this->addNotificationsForUser(
			$owner, $actionSharer, [[$fileSource => $path]],
			(int) $fileSource, $path, ($itemType === 'file'),
			$this->userSettings->getUserSetting($owner, 'stream', Files_Sharing::TYPE_SHARED),
			$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : 0
		);
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
			$this->userSettings->getUserSetting($sharer, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($sharer, 'setting', 'batchtime') : 0
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
			$this->userSettings->getUserSetting($owner, 'email', Files_Sharing::TYPE_SHARED) ? $this->userSettings->getUserSetting($owner, 'setting', 'batchtime') : 0
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
		if (!$storage->instanceOfStorage('OC\Files\Storage\Shared')) {
			return;
		}

		/** @var \OC\Files\Storage\Shared $storage */
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

		$selfAction = $user === $this->currentUser->getUID();
		$app = ($type === Files_Sharing::TYPE_SHARED || $type === Files_Sharing::TYPE_PUBLIC_LINKS) ? 'files_sharing' : 'files';
		$link = $this->urlGenerator->linkToRouteAbsolute('files.view.index', array(
			'dir' => ($isFile) ? dirname($path) : $path,
		));

		$objectType = ($fileId) ? 'files' : '';

		$event = $this->manager->generateEvent();
		$event->setApp($app)
			->setType($type)
			->setAffectedUser($user)
			->setAuthor($this->currentUser->getUID())
			->setTimestamp(time())
			->setSubject($subject, $subjectParams)
			->setObject($objectType, $fileId, $path)
			->setLink($link);

		// Add activity to stream
		if ($streamSetting && (!$selfAction || $this->userSettings->getUserSetting($this->currentUser->getUID(), 'setting', 'self'))) {
			$this->activityData->send($event);
		}

		// Add activity to mail queue
		if ($emailSetting && (!$selfAction || $this->userSettings->getUserSetting($this->currentUser->getUID(), 'setting', 'selfemail'))) {
			$latestSend = time() + $emailSetting;
			$this->activityData->storeMail($event, $latestSend);
		}
	}
}
