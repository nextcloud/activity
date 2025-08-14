<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
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

use OC\Files\Config\CachedMountFileInfo;
use OC\Files\View;
use OC\TagManager;
use OC\Tags;
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCA\Activity\Tests\TestCase;
use OCA\Files_Sharing\SharedMount;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Files\Config\IUserMountCache;
use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Share\IManager as ShareIManager;
use OCP\Share\IShare;
use OCP\Share\IShareHelper;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

/**
 * Class FilesHooksTest
 * Testing the public methods with internals being mocked out
 *
 * @group DB
 * @package OCA\Activity
 */
class FilesHooksTest extends TestCase {
	protected FilesHooks $filesHooks;
	protected IManager&MockObject $activityManager;
	protected Data&MockObject $data;
	protected UserSettings&MockObject $settings;
	protected IGroupManager&MockObject $groupManager;
	protected View&MockObject $view;
	protected IRootFolder&MockObject $rootFolder;
	protected IShareHelper&MockObject $shareHelper;
	protected IURLGenerator&MockObject $urlGenerator;
	protected IUserMountCache&MockObject $userMountCache;
	protected IConfig&MockObject $config;
	protected NotificationGenerator&MockObject $notificationGenerator;
	protected TagManager&MockObject $tagManager;
	protected $tags;
	/**
	 * @todo With PHP 8.2 we can put this directly on the declaration instead of a comment but 8.1 does not allow the parenthesis
	 * @var (OCA\Circles\CirclesManager&MockObject)|null
	 */
	protected $teamManager;

	protected function setUp(): void {
		parent::setUp();

		$this->activityManager = $this->createMock(IManager::class);
		$this->data = $this->createMock(Data::class);
		$this->settings = $this->createMock(UserSettings::class);
		$this->groupManager = $this->createMock(IGroupManager::class);
		$this->view = $this->createMock(View::class);
		$this->rootFolder = $this->createMock(IRootFolder::class);
		$this->shareHelper = $this->createMock(IShareHelper::class);
		$this->urlGenerator = $this->createMock(IURLGenerator::class);
		$this->userMountCache = $this->createMock(IUserMountCache::class);
		$this->config = $this->createMock(IConfig::class);
		$this->notificationGenerator = $this->createMock(NotificationGenerator::class);
		$this->tagManager = $this->createMock(TagManager::class);
		$this->tags = $this->createMock(Tags::class);
		$this->tagManager->method('getUsersFavoritingObject')
			->willReturn([]);
		$this->teamManager = null;

		$this->tagManager->method('load')
			->willReturn($this->tags);

		$this->filesHooks = $this->getFilesHooks();
	}

	/**
	 * @param array $mockedMethods
	 * @param string $user
	 * @return FilesHooks&MockObject
	 */
	protected function getFilesHooks(array $mockedMethods = [], string $user = 'user'): FilesHooks {
		$currentUser = $this->createMock(CurrentUser::class);
		$currentUser->expects($this->any())
			->method('getUID')
			->willReturn($user);
		$currentUser->expects($this->any())
			->method('getUserIdentifier')
			->willReturn($user);
		/** @var LoggerInterface $logger */
		$logger = $this->createMock(LoggerInterface::class);

		if (!empty($mockedMethods)) {
			return $this->getMockBuilder(FilesHooks::class)
				->setConstructorArgs([
					$this->activityManager,
					$this->data,
					$this->settings,
					$this->groupManager,
					$this->view,
					$this->rootFolder,
					$this->shareHelper,
					\OCP\Server::get(IDBConnection::class),
					$this->urlGenerator,
					$logger,
					$currentUser,
					$this->userMountCache,
					$this->config,
					$this->notificationGenerator,
					$this->tagManager,
					$this->teamManager,
				])
				->onlyMethods($mockedMethods)
				->getMock();
		}

		return new FilesHooks(
			$this->activityManager,
			$this->data,
			$this->settings,
			$this->groupManager,
			$this->view,
			$this->rootFolder,
			$this->shareHelper,
			\OCP\Server::get(IDBConnection::class),
			$this->urlGenerator,
			$logger,
			$currentUser,
			$this->userMountCache,
			$this->config,
			$this->notificationGenerator,
			$this->tagManager,
			$this->teamManager,
		);
	}

	protected function getUserMock(string $uid): IUser {
		$user = $this->createMock(IUser::class);
		$user->expects($this->any())
			->method('getUID')
			->willReturn($uid);
		return $user;
	}

	public static function dataFileCreate(): array {
		return [
			['user', 'created_self', 'created_by', Files::TYPE_SHARE_CREATED],
			['', '', 'created_public', Files_Sharing::TYPE_PUBLIC_UPLOAD],
		];
	}

	/**
	 * @dataProvider dataFileCreate
	 *
	 * @param mixed $currentUser
	 * @param string $selfSubject
	 * @param string $othersSubject
	 */
	public function testFileCreate(string $currentUser, string $selfSubject, string $othersSubject, string $type): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		], $currentUser);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', $type, $selfSubject, $othersSubject);

		$filesHooks->fileCreate('path');
	}

	/**
	 * @dataProvider dataFileCreate
	 *
	 * @param string $currentUser
	 */
	public function testFileCreateRoot(string $currentUser): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		], $currentUser);

		$filesHooks->expects($this->never())
			->method('addNotificationsForFileAction');

		$filesHooks->fileCreate('/');
	}

	public function testFileUpdate(): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_FILE_CHANGED, 'changed_self', 'changed_by');

		$filesHooks->fileUpdate('path');
	}

	public function testFileDelete(): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_SHARE_DELETED, 'deleted_self', 'deleted_by');

		$filesHooks->fileDelete('path');
	}

	public function testFileRestore(): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by');

		$filesHooks->fileRestore('path');
	}

	public function testAddNotificationsForFileActionPartFile(): void {
		$filesHooks = $this->getFilesHooks([
			'getSourcePathAndOwner',
		]);

		$filesHooks->expects($this->never())
			->method('getSourcePathAndOwner');

		$this->invokePrivate($filesHooks, 'addNotificationsForFileAction', ['/test.txt.part', '', '', '']);
	}

	public static function dataAddNotificationsForFileAction(): array {
		return [
			[
				[
					'email' => ['user' => 42],
					'notification' => ['user' => true],
				],
				'mountCacheUsed' => false,
				'isFavorite' => true,
				'addNotifications' => [
					'user' => [
						'subject' => 'restored_self',
						'subject_params' => [[1337 => '/user/files/path']],
						'path' => '/user/files/path',
						'notification' => true,
						'email' => 42,
					],
					'user1' => [
						'subject' => 'restored_by',
						'subject_params' => [[1337 => '/user1/files/path'], 'user'],
						'path' => '/user1/files/path',
						'notification' => false,
						'email' => 0,
					],
				],
			],
			[
				[
					'email' => [],
					'notification' => ['user1' => false],
				],
				'mountCacheUsed' => false,
				'isFavorite' => true,
				'addNotifications' => [
					'user' => [
						'subject' => 'restored_self',
						'subject_params' => [[1337 => '/user/files/path']],
						'path' => '/user/files/path',
						'notification' => false,
						'email' => 0,
					],
					'user1' => [
						'subject' => 'restored_by',
						'subject_params' => [[1337 => '/user1/files/path'], 'user'],
						'path' => '/user1/files/path',
						'notification' => false,
						'email' => 0,
					],
				],
			],
			[
				[
					'email' => ['user' => 42],
					'notification' => ['user' => false],
				],
				'mountCacheUsed' => true,
				'isFavorite' => true,
				'addNotifications' => [
					'user' => [
						'subject' => 'restored_self',
						'subject_params' => [[1337 => '/path']],
						'path' => '/path',
						'notification' => false,
						'email' => 42,
					],
					'user1' => [
						'subject' => 'restored_by',
						'subject_params' => [[1337 => '/path'], 'user'],
						'path' => '/path',
						'notification' => false,
						'email' => 0,
					],
				],
			],
			[
				[
					'email' => [],
					'notification' => ['user1' => true],
				],
				'mountCacheUsed' => true,
				'isFavorite' => true,
				'addNotifications' => [
					'user' => [
						'subject' => 'restored_self',
						'subject_params' => [[1337 => '/path']],
						'path' => '/path',
						'notification' => false,
						'email' => 0,
					],
					'user1' => [
						'subject' => 'restored_by',
						'subject_params' => [[1337 => '/path'], 'user'],
						'path' => '/path',
						'notification' => true,
						'email' => 0,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider dataAddNotificationsForFileAction
	 *
	 * @param array $filterUsers
	 * @param bool $mountCacheUsed
	 * @param bool $isFavorite
	 * @param array $addNotifications
	 */
	public function testAddNotificationsForFileAction(array $filterUsers, bool $mountCacheUsed, bool $isFavorite, array $addNotifications): void {
		$filesHooks = $this->getFilesHooks([
			'getSourcePathAndOwner',
			'getUserPathsFromPath',
			'addNotificationsForUser',
		]);

		if ($isFavorite) {
			$this->tagManager->method('getUsersFavoritingObject')
				->willReturn(['user', 'user1', 'user2']);
		}

		$filesHooks->expects($this->once())
			->method('getSourcePathAndOwner')
			->with('path')
			->willReturn(['/owner/path', 'owner', 1337]);
		$filesHooks->expects($this->once())
			->method('getUserPathsFromPath')
			->with('/owner/path', 'owner')
			->willReturn([
				'ownerPath' => '/owner/path',
				'users' => [
					'user' => '/user/files/path',
					'user1' => '/user1/files/path',
					'user2' => '/user2/files/path',
				],
				'remotes' => [],
			]);

		$this->config->expects($this->once())
			->method('getSystemValueBool')
			->with('activity_use_cached_mountpoints', false)
			->willReturn($mountCacheUsed);

		if ($mountCacheUsed) {
			$this->userMountCache->expects($this->once())
				->method('getMountsForFileId')
				->willReturn([
					new CachedMountFileInfo(
						$this->getUserMock('user'),
						1,
						1,
						'/user/files',
						null,
						'',
						'path',
						'files/path'
					),
					new CachedMountFileInfo(
						$this->getUserMock('user1'),
						1,
						1,
						'/user1/files',
						null,
						'',
						'path',
						'files/path'
					),
					new CachedMountFileInfo(
						$this->getUserMock('user2'),
						1,
						1,
						'/user2/files',
						null,
						'',
						'path',
						'files/path'
					)
				]);
		} else {
			$this->userMountCache->expects($this->never())
				->method('getMountsForFileId');
		}

		$this->settings->expects($this->exactly(2))
			->method('filterUsersBySetting')
			->willReturnCallback(function ($users, $method, $type) use ($filterUsers) {
				return $filterUsers[$method];
			});

		$addCalls = [];
		foreach ($addNotifications as $user => $arguments) {
			$addCalls[] = [
				$user,
				$arguments['subject'],
				$arguments['subject_params'],
				1337,
				$arguments['path'],
				true,
				$arguments['email'],
				$arguments['notification'],
				Files::TYPE_SHARE_RESTORED,
			];
		}
		$receivedActivities = [];
		$filesHooks->expects($this->any())
			->method('addNotificationsForUser')
			->willReturnCallback(function (...$params) use (&$receivedActivities) {
				$receivedActivities[] = $params;
			});

		self::invokePrivate($filesHooks, 'addNotificationsForFileAction', ['path', Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by']);

		$this->assertEquals($addCalls, array_slice($receivedActivities, 0, count($addCalls)));
	}

	private function getNodeMock(int $fileId = 1337, string $path = 'path', bool $isFile = true): Node&MockObject {
		if ($isFile) {
			$node = $this->createMock(File::class);
		} else {
			$node = $this->createMock(Folder::class);
		}
		$node->method('getId')
			->willReturn($fileId);
		$node->method('getPath')
			->willReturn($path);
		return $node;
	}

	public function testHookShareWithUser(): void {
		$filesHooks = $this->getFilesHooks([
			'shareWithUser',
		]);

		$node = $this->getNodeMock();

		$filesHooks->expects($this->once())
			->method('shareWithUser')
			->with('u1', $node, 'path');

		/** @var ShareIManager */
		$manager = \OC::$server->get(ShareIManager::class);
		$share = $manager->newShare();
		$share->setSharedWith('u1');
		$share->setShareType(IShare::TYPE_USER);
		$share->setTarget('path');
		$share->setNode($node);

		$filesHooks->share($share);
	}

	public function testHookShareWithGroup(): void {
		$filesHooks = $this->getFilesHooks([
			'shareWithGroup',
		]);

		$node = $this->getNodeMock();

		$filesHooks->expects($this->once())
			->method('shareWithGroup')
			->with('g1', $node, 'path', 42);

		/** @var ShareIManager */
		$manager = \OC::$server->get(ShareIManager::class);
		$share = $manager->newShare();
		$share->setId('42');
		$share->setSharedWith('g1');
		$share->setShareType(IShare::TYPE_GROUP);
		$share->setTarget('path');
		$share->setNode($node);

		$filesHooks->share($share);
	}

	public function testShareViaPublicLink(): void {
		$filesHooks = $this->getFilesHooks([
			'shareByLink',
		]);

		$node = $this->getNodeMock(1337, 'thepath');

		$filesHooks->expects($this->once())
			->method('shareByLink')
			->with($node, 'admin');

		/** @var ShareIManager */
		$manager = \OC::$server->get(ShareIManager::class);
		$share = $manager->newShare();
		$share->setShareType(IShare::TYPE_LINK);
		$share->setNodeType('file');
		$share->setNode($node);
		$share->setSharedBy('admin');

		$filesHooks->share($share);
	}

	public static function dataShareWithUser(): array {
		return [
			['file', '/path.txt'],
			['folder', '/path.txt'],
		];
	}

	/**
	 * @dataProvider dataShareWithUser
	 *
	 * @param string $itemType
	 * @param string $fileTarget
	 * @param bool $isFile
	 */
	public function testShareWithUser(string $itemType, string $fileTarget): void {
		$isFile = $itemType === 'file';
		$filesHooks = $this->getFilesHooks([
			'shareNotificationForSharer',
			'addNotificationsForUser',
			'shareNotificationForOriginalOwners',
		]);

		$this->settings->expects($this->exactly(3))
			->method('getUserSetting')
			->willReturnMap(
				[
					['recipient', 'notification', Files_Sharing::TYPE_SHARED, true],
					['recipient', 'email', Files_Sharing::TYPE_SHARED, true],
					['recipient', 'setting', 'batchtime', 42],
				]
			);

		$node = $this->getNodeMock(1337, 'path.txt', $isFile);
		$filesHooks->expects($this->once())
			->method('shareNotificationForSharer')
			->with('shared_user_self', 'recipient', $node);
		$filesHooks->expects($this->once())
			->method('addNotificationsForUser')
			->with(
				'recipient',
				'shared_with_by',
				[[1337 => $fileTarget], 'user'],
				1337,
				$fileTarget,
				$isFile,
				42,
				true
			);

		self::invokePrivate($filesHooks, 'shareWithUser', [
			'recipient', $node, $fileTarget, true
		]);
	}

	public function testShareWithGroupNonExisting(): void {
		$filesHooks = $this->getFilesHooks([
			'shareNotificationForSharer'
		]);

		$this->groupManager->expects($this->once())
			->method('get')
			->with('no-group')
			->willReturn(null);

		$filesHooks->expects($this->never())
			->method('shareNotificationForSharer');

		self::invokePrivate($filesHooks, 'shareWithGroup', [
			'no-group', $this->getNodeMock(), '', 0, true
		]);
	}

	public static function dataShareWithGroup(): array {
		return [
			[
				[
					[],
				], 0, 0, [], [], []
			],
			[
				[
					['user1'],
					[],
				], 2, 1, [], [], []
			],
			[
				[
					['user1'],
					[],
				],
				2, 1,
				['user1'],
				[
					[['user1'], 'notification', Files_Sharing::TYPE_SHARED, ['user1' => true]],
					[['user1'], 'email', Files_Sharing::TYPE_SHARED, []],
				],
				[
					'user1' => [
						'subject' => 'shared_with_by',
						'subject_params' => [[42 => '/file'], 'user'],
						'path' => '/file',
						'notification' => true,
						'email' => 0,
					],
				],
			],
			[
				[
					['user1'],
					[],
				],
				2, 1,
				['user1'],
				[
					[['user1'], 'notification', Files_Sharing::TYPE_SHARED, ['user1' => false]],
					[['user1'], 'email', Files_Sharing::TYPE_SHARED, ['user1' => false]],
				],
				[],
			],
			[
				[
					['user'],
					[],
				],
				0, 0,
				['user1'],
				[],
				[],
			],
		];
	}

	/**
	 * @dataProvider dataShareWithGroup
	 * @param array $usersInGroup
	 * @param int $settingCalls
	 * @param int $fixCalls
	 * @param array $settingUsers
	 * @param array $settingsReturn
	 * @param array $addNotifications
	 */
	public function testShareWithGroup(array $usersInGroup, int $settingCalls, int $fixCalls, array $settingUsers, array $settingsReturn, array $addNotifications): void {
		foreach ($usersInGroup as &$users) {
			$users = array_map($this->getUserMock(...), $users);
		}

		$node = $this->getNodeMock(42);

		$filesHooks = $this->getFilesHooks([
			'shareNotificationForSharer',
			'addNotificationsForUser',
			'fixPathsForShareExceptions',
			'shareNotificationForOriginalOwners',
		]);

		$group = $this->createMock(IGroup::class);
		$group->expects($this->any())
			->method('searchUsers')
			->with('')
			->willReturnOnConsecutiveCalls(...$usersInGroup);

		$this->groupManager->expects($this->once())
			->method('get')
			->with('group1')
			->willReturn($group);

		$this->settings->expects($this->exactly($settingCalls))
			->method('filterUsersBySetting')
			#->with($settingUsers, $this->anything(), Files_Sharing::TYPE_SHARED)
			->willReturnMap($settingsReturn);

		$filesHooks->expects($this->once())
			->method('shareNotificationForSharer')
			->with('shared_group_self', 'group1', $node);
		$filesHooks->expects($this->exactly($fixCalls))
			->method('fixPathsForShareExceptions')
			->with($this->anything(), 1337)
			->willReturnArgument(0);
		$filesHooks->expects($this->once())
			->method('shareNotificationForOriginalOwners')
			->with('user', 'reshared_group_by', 'group1', $node)
			->willReturnArgument(0);

		$addCalls = [];
		foreach ($addNotifications as $user => $arguments) {
			$addCalls[] = [
				$user,
				$arguments['subject'],
				$arguments['subject_params'],
				42,
				$arguments['path'],
				true,
				$arguments['email'],
				$arguments['notification'],
				Files_Sharing::TYPE_SHARED,
			];
		}
		$receivedActivities = [];
		$filesHooks->expects($this->any())
			->method('addNotificationsForUser')
			->willReturnCallback(function (...$params) use (&$receivedActivities) {
				$receivedActivities[] = $params;
			});

		self::invokePrivate($filesHooks, 'shareWithGroup', [
			'group1', $node, '/file', 1337
		]);
		$this->assertEquals($addCalls, $receivedActivities);
	}

	public function testReshareNotificationForSharer(): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForUser',
		]);

		$this->settings->expects($this->exactly(3))
			->method('getUserSetting')
			->willReturnMap([
				['owner', 'notification', Files_Sharing::TYPE_SHARED, true],
				['owner', 'email', Files_Sharing::TYPE_SHARED, true],
				['owner', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForUser')
			->with(
				'owner',
				'reshared_link_by',
				[[42 => '/path'], 'user', ''],
				42,
				'/path',
				true,
				true,
				21
			);

		self::invokePrivate($filesHooks, 'reshareNotificationForSharer', ['owner', 'reshared_link_by', '', 42, '/path', true]);
	}

	public function testShare(): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForUser',
			'shareNotificationForOriginalOwners',
		]);

		$node = $this->getNodeMock(42, '/user/files/path');

		$this->settings->expects($this->exactly(3))
			->method('getUserSetting')
			->willReturnMap([
				['user', 'notification', Files_Sharing::TYPE_SHARED, true],
				['user', 'email', Files_Sharing::TYPE_SHARED, true],
				['user', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForUser')
			->with(
				'user',
				'shared_link_self',
				[[42 => '/path']],
				42,
				'/path',
				true,
				true,
				21
			);
		$filesHooks->expects($this->once())
			->method('shareNotificationForOriginalOwners')
			->with('user', 'reshared_link_by', '', $node);

		self::invokePrivate($filesHooks, 'shareByLink', [$node, 'user']);
	}

	public static function dataShareNotificationForOriginalOwners(): array {
		return [
			[false, false, 'owner', '', 0],
			[true, false, 'owner', '', 1],
			[true, true, 'owner', '', 1],
			[true, true, 'owner', 'owner', 1],
			[true, true, 'owner', 'sharee', 2],
			[true, true, 'current', 'sharee', 1],
			[true, true, 'owner', 'current', 1],
			[true, true, 'current', 'current', 0],
		];
	}

	/**
	 * @dataProvider dataShareNotificationForOriginalOwners
	 *
	 * @param bool $validMountPoint
	 * @param bool $validSharedStorage
	 * @param string $pathOwner
	 * @param string $shareeUser
	 * @param int $numReshareNotification
	 */
	public function testShareNotificationForOriginalOwners(bool $validMountPoint, bool $validSharedStorage, string $pathOwner, string $shareeUser, int $numReshareNotification): void {
		$filesHooks = $this->getFilesHooks([
			'reshareNotificationForSharer',
		]);

		$node = $this->getNodeMock(42, '/path');
		$mount = $this->createMock(SharedMount::class);
		$node->method('getMountPoint')
			->willReturn($mount);
		$sourceShare = $this->createMock(IShare::class);
		$sourceShare->method('getShareOwner')
			->willReturn($pathOwner);
		$sourceShare->method('getSharedBy')
			->willReturn($shareeUser);
		$sourceShare->method('getTarget')
			->willReturn('/source-path');
		$mount->method('getShare')
			->willReturn($sourceShare);

		$filesHooks->expects($this->exactly($numReshareNotification))
			->method('reshareNotificationForSharer')
			->with($this->anything(), 'subject', 'with', 42, '/source-path', 'file');

		if ($validMountPoint) {
			$sourceNode = $this->getNodeMock(42, "/$pathOwner/files/source-path");
			$sourceShare->method('getNode')
				->willReturn($sourceNode);
		} else {
			$sourceShare->method('getNode')
				->willThrowException(new \OCP\Files\NotFoundException());
		}

		self::invokePrivate($filesHooks, 'shareNotificationForOriginalOwners', ['current', 'subject', 'with', $node]);
	}

	public function testShareNotificationForSharer(): void {
		$filesHooks = $this->getFilesHooks(['addNotificationsForUser']);

		$node = $this->getNodeMock(42, '/user/files/path');

		$this->settings->expects($this->exactly(3))
			->method('getUserSetting')
			->willReturnMap([
				['user', 'notification', Files_Sharing::TYPE_SHARED, true],
				['user', 'email', Files_Sharing::TYPE_SHARED, true],
				['user', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForUser')
			->with(
				'user',
				'subject',
				[[42 => '/path'], 'target'],
				42,
				'/path',
				true,
				true,
				21
			);

		self::invokePrivate($filesHooks, 'shareNotificationForSharer', ['subject', 'target', $node]);
	}

	public static function dataAddNotificationsForUser(): array {
		return [
			['user', 'subject', ['parameter'], 42, 'path/subpath', 'path', true, true, false, Files_Sharing::TYPE_SHARED, 'files_sharing', false],
			['user', 'subject', ['parameter'], 42, 'path/subpath', 'path', true, true, false, Files_Sharing::TYPE_SHARED, 'files_sharing', false],
			['notAuthor', 'subject', ['parameter'], 42, 'path/subpath', 'path', true, true, false, Files::TYPE_SHARE_CREATED, 'files', false],
			['notAuthor', 'subject', ['parameter'], 0, 'path/subpath', 'path', true, true, false, Files::TYPE_SHARE_CREATED, 'files', false],

			['user', 'subject', ['parameter'], 42, 'path/subpath', 'path', true, false, true, Files_Sharing::TYPE_SHARED, 'files_sharing', false],
			['notAuthor', 'subject', ['parameter'], 42, 'path/subpath', 'path', true, false, true, Files::TYPE_SHARE_CREATED, 'files', true],
			['notAuthor', 'subject', ['parameter'], 0, 'path/subpath', 'path', true, false, true, Files::TYPE_SHARE_CREATED, 'files', true],
			['notAuthor', 'subject', ['parameter'], 0, 'path/subpath', 'path/subpath', false, false, true, Files::TYPE_SHARE_CREATED, 'files', true],
		];
	}

	/**
	 * @dataProvider dataAddNotificationsForUser
	 *
	 * @param string $user
	 * @param string $subject
	 * @param array $parameter
	 * @param int $fileId
	 * @param string $path
	 * @param string $urlPath
	 * @param bool $isFile
	 * @param bool $notification
	 * @param bool $email
	 * @param string $type
	 * @param string $app
	 * @param bool $sentEmail
	 */
	public function testAddNotificationsForUser(string $user, string $subject, array $parameter, int $fileId, string $path, string $urlPath, bool $isFile, bool $notification, bool $email, string $type, string $app, bool $sentEmail): void {
		$this->urlGenerator->expects($this->once())
			->method('linkToRouteAbsolute')
			->with('files.view.index', ['dir' => $urlPath])
			->willReturn('routeToFilesIndex');

		$event = $this->createMock(IEvent::class);
		$event->expects($this->once())
			->method('setApp')
			->with($app)
			->willReturnSelf();
		$event->expects($this->once())
			->method('setType')
			->with($type)
			->willReturnSelf();
		$event->expects($this->once())
			->method('setAffectedUser')
			->with($user)
			->willReturnSelf();
		$event->expects($this->once())
			->method('setAuthor')
			->with('user')
			->willReturnSelf();
		$event->expects($this->once())
			->method('setTimestamp')
			->willReturnSelf();
		$event->expects($this->once())
			->method('setSubject')
			->with($subject, $parameter)
			->willReturnSelf();
		$event->expects($this->once())
			->method('setLink')
			->with('routeToFilesIndex')
			->willReturnSelf();

		if ($fileId) {
			$event->expects($this->once())
				->method('setObject')
				->with('files', $fileId, $path)
				->willReturnSelf();
		} else {
			$event->expects($this->once())
				->method('setObject')
				->with('', $fileId, $path)
				->willReturnSelf();
		}

		$this->activityManager->expects($this->once())
			->method('generateEvent')
			->willReturn($event);

		$this->data->expects($this->once())
			->method('send')
			->with($event);
		$this->data->expects($sentEmail ? $this->once() : $this->never())
			->method('storeMail')
			->with($event, $this->anything());

		self::invokePrivate($this->filesHooks, 'addNotificationsForUser', [$user, $subject, $parameter, $fileId, $path, $isFile, $email, $notification, $type]);
	}

	public function testLeaveShare(): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForUser',
			'shareNotificationForOriginalOwners',
		], 'with');

		$node = $this->getNodeMock(42, '/user/files/path');
		$share = $this->createMock(IShare::class);
		$share->method('getNode')
			->willReturn($node);
		$share->method('getTarget')
			->willReturn('/target');
		$share->method('getNodeType')
			->willReturn('file');
		$share->method('getSharedWith')
			->willReturn('with');

		$this->settings->expects($this->exactly(3))
			->method('getUserSetting')
			->willReturnMap([
				['with', 'notification', Files_Sharing::TYPE_SHARED, true],
				['with', 'email', Files_Sharing::TYPE_SHARED, true],
				['with', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForUser')
			->with(
				'with',
				'self_unshared',
				[[42 => '/target'], 'with'],
				42,
				'/target',
				true,
				true,
				21
			);
		$filesHooks->expects($this->once())
			->method('shareNotificationForOriginalOwners')
			->with('with', 'self_unshared_by', 'with', $node);

		self::invokePrivate($filesHooks, 'unShareSelf', [$share]);
	}
}
