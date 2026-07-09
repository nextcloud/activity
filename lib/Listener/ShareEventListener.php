<?php

/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity\Listener;

use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCA\Activity\NotificationGenerator;
use OCA\Activity\UserSettings;
use OCA\Circles\CirclesManager;
use OCA\Circles\Model\Member;
use OCA\Files_Sharing\SharedMount;
use OCP\Activity\IManager;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Files\File;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Share\Events\BeforeShareDeletedEvent;
use OCP\Share\Events\ShareCreatedEvent;
use OCP\Share\Events\ShareDeletedFromSelfEvent;
use OCP\Share\IShare;
use Psr\Log\LoggerInterface;

/**
 * The class to handle the share events
 * @template-implements IEventListener<BeforeShareDeletedEvent|ShareDeletedFromSelfEvent|ShareCreatedEvent>
 */
class ShareEventListener implements IEventListener {
	public const USER_BATCH_SIZE = 50;

	public function __construct(
		private readonly IManager $manager,
		private readonly UserSettings $userSettings,
		private readonly CurrentUser $currentUser,
		private readonly IGroupManager $groupManager,
		private readonly IDBConnection $connection,
		private readonly Data $activityData,
		private readonly IRootFolder $rootFolder,
		private readonly NotificationGenerator $notificationGenerator,
		private readonly ?CirclesManager $teamManager,
		private readonly LoggerInterface $logger,
		private readonly IURLGenerator $urlGenerator,
	) {
	}

	#[\Override]
	public function handle(Event $event): void {
		if ($event instanceof BeforeShareDeletedEvent) {
			$this->unShare($event);
		}

		if ($event instanceof ShareDeletedFromSelfEvent) {
			$this->unShareSelf($event);
		}

		if ($event instanceof ShareCreatedEvent) {
			$this->createShare($event);
		}
	}

	/**
	 * Node shared event
	 */
	public function createShare(ShareCreatedEvent $event): void {
		$share = $event->getShare();
		$this->share($share);
	}

	/**
	 * Unsharing event
	 */
	public function unShare(BeforeShareDeletedEvent $event): void {
		$share = $event->getShare();

		if (!in_array($share->getNodeType(), ['file', 'folder'], true) || $this->isDeletedNode($share->getShareOwner(), $share->getNodeId())) {
			return;
		}
		if ($share->getShareType() === IShare::TYPE_USER) {
			$this->unshareFromUser($share);
		} elseif ($share->getShareType() === IShare::TYPE_GROUP) {
			$this->unshareFromGroup($share);
		} elseif ($share->getShareType() === IShare::TYPE_LINK) {
			$this->unshareLink($share);
		}
	}

	/**
	 * "Unsharing a share from self only" event
	 */
	public function unShareSelf(ShareDeletedFromSelfEvent $event): void {
		$share = $event->getShare();

		if (!in_array($share->getNodeType(), ['file', 'folder'], true)) {
			return;
		}
		if ($share->getShareType() === IShare::TYPE_GROUP) {
			$this->unshareFromSelfGroup($share);
		} elseif ($share->getShareType() === IShare::TYPE_USER) {
			$this->unshareFromUser($share);
		}
	}

	/**
	 * Unharing a file or folder from a user
	 *
	 * @throws \OCP\Files\NotFoundException
	 */
	protected function unshareFromUser(IShare $share): void {
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
		if (!$group instanceof IGroup) {
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

			$fileId = $fileSource->getId();

			if ($sourceShare->getShareOwner() !== $sharedBy) {
				$owner = $sourceShare->getShareOwner();
				try {
					$ownerNode = $this->rootFolder->getUserFolder($owner)->getFirstNodeById($fileId);
				} catch (NotFoundException) {
					return;
				}
				if ($ownerNode === null) {
					return;
				}
				$this->reshareNotificationForSharer(
					$owner,
					$subject,
					$shareWith,
					$fileId,
					$this->getUserRelativePath($owner, $ownerNode->getPath()),
					$fileSource instanceof File,
				);
			}

			if ($sourceShare->getSharedBy() && $sourceShare->getSharedBy() !== $sharedBy && $sourceShare->getShareOwner() !== $sourceShare->getSharedBy()) {
				$sharer = $sourceShare->getSharedBy();
				try {
					$sharerNode = $this->rootFolder->getUserFolder($sharer)->getFirstNodeById($fileId);
				} catch (NotFoundException) {
					return;
				}
				if ($sharerNode === null) {
					return;
				}

				$this->reshareNotificationForSharer(
					$sharer,
					$subject,
					$shareWith,
					$fileId,
					$this->getUserRelativePath($sharer, $sharerNode->getPath()),
					$fileSource instanceof File,
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

	/**
	 * Manage sharing events
	 *
	 * @param IShare $share the share from the event
	 */
	public function share(IShare $share): void {
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
