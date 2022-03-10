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
use OC\TagManager;
use OC\Tags;
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCA\Activity\Tests\TestCase;
use OCP\Files\Config\IUserMountCache;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IConfig;
use OCP\ILogger;
use OCP\IUser;
use OCP\Share\IShare;
use OCP\Share\IShareHelper;
use OCP\Activity\IManager;
use OCP\IGroupManager;
use OC\Files\View;
use OCP\IURLGenerator;
use OCP\IGroup;
use OCA\Files_Sharing\SharedStorage;
use OCP\Files\Mount\IMountPoint;
use OCP\Activity\IEvent;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class FilesHooksTest
 * Testing the public methods with internals being mocked out
 *
 * @group DB
 * @package OCA\Activity
 */
class FilesHooksTest extends TestCase {
	/** @var FilesHooks */
	protected $filesHooks;
	/** @var IManager|MockObject */
	protected $activityManager;
	/** @var Data|MockObject */
	protected $data;
	/** @var UserSettings|MockObject */
	protected $settings;
	/** @var IGroupManager|MockObject */
	protected $groupManager;
	/** @var View|MockObject */
	protected $view;
	/** @var IRootFolder|MockObject */
	protected $rootFolder;
	/** @var IShareHelper|MockObject */
	protected $shareHelper;
	/** @var IURLGenerator|MockObject */
	protected $urlGenerator;
	/** @var IUserMountCache|MockObject */
	protected $userMountCache;
	/** @var IConfig|MockObject */
	protected $config;
	/** @var NotificationGenerator|MockObject */
	protected $notificationGenerator;
	/** @var TagManager|MockObject */
	protected $tagManager;
	protected $tags;

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

		$this->tagManager->method('load')
			->willReturn($this->tags);

		$this->filesHooks = $this->getFilesHooks();
	}

	/**
	 * @param array $mockedMethods
	 * @param string $user
	 * @return FilesHooks|MockObject
	 */
	protected function getFilesHooks(array $mockedMethods = [], string $user = 'user'): FilesHooks {
		$currentUser = $this->createMock(CurrentUser::class);
		$currentUser->expects($this->any())
			->method('getUID')
			->willReturn($user);
		$currentUser->expects($this->any())
			->method('getUserIdentifier')
			->willReturn($user);
		/** @var ILogger $logger */
		$logger = $this->createMock(ILogger::class);

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
					\OC::$server->getDatabaseConnection(),
					$this->urlGenerator,
					$logger,
					$currentUser,
					$this->userMountCache,
					$this->config,
					$this->notificationGenerator,
					$this->tagManager
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
			\OC::$server->getDatabaseConnection(),
			$this->urlGenerator,
			$logger,
			$currentUser,
			$this->userMountCache,
			$this->config,
			$this->notificationGenerator,
			$this->tagManager
		);
	}

	protected function getUserMock(string $uid): IUser {
		$user = $this->createMock(IUser::class);
		$user->expects($this->any())
			->method('getUID')
			->willReturn($uid);
		return $user;
	}

	public function dataFileCreate(): array {
		return [
			['user', 'created_self', 'created_by'],
			['', '', 'created_public'],
		];
	}

	/**
	 * @dataProvider dataFileCreate
	 *
	 * @param mixed $currentUser
	 * @param string $selfSubject
	 * @param string $othersSubject
	 */
	public function testFileCreate(string $currentUser, string $selfSubject, string $othersSubject): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		], $currentUser);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_SHARE_CREATED, $selfSubject, $othersSubject);

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

	public function dataAddNotificationsForFileAction(): array {
		return [
			[
				[
					'email' => ['user' => 42],
					'notification' => ['user' => true],
				],
				'mountcache_used' => false,
				'isFavorite' => true,
				[
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
				'mountcache_used' => false,
				'isFavorite' => true,
				[
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
				'mountcache_used' => true,
				'isFavorite' => true,
				[
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
				'mountcache_used' => true,
				'isFavorite' => true,
				[
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

		$i = 2;
		foreach ($addNotifications as $user => $arguments) {
			$filesHooks->expects($this->at($i))
				->method('addNotificationsForUser')
				->with(
					$user,
					$arguments['subject'],
					$arguments['subject_params'],
					1337,
					$arguments['path'],
					true,
					$arguments['email'],
					$arguments['notification'],
					Files::TYPE_SHARE_RESTORED
				);
			$i++;
		}

		self::invokePrivate($filesHooks, 'addNotificationsForFileAction', ['path', Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by']);
	}

	public function testHookShareWithUser(): void {
		$filesHooks = $this->getFilesHooks([
			'shareWithUser',
		]);

		$filesHooks->expects($this->once())
			->method('shareWithUser')
			->with('u1', 1337, 'file', 'path');

		$filesHooks->share([
			'fileSource' => 1337,
			'shareType' => IShare::TYPE_USER,
			'shareWith' => 'u1',
			'itemType' => 'file',
			'fileTarget' => 'path',
		]);
	}

	public function testHookShareWithGroup(): void {
		$filesHooks = $this->getFilesHooks([
			'shareWithGroup',
		]);

		$filesHooks->expects($this->once())
			->method('shareWithGroup')
			->with('g1', 1337, 'file', 'path', 42);

		$filesHooks->share([
			'fileSource' => 1337,
			'shareType' => IShare::TYPE_GROUP,
			'shareWith' => 'g1',
			'itemType' => 'file',
			'fileTarget' => 'path',
			'id' => '42',
		]);
	}

	public function testShareViaPublicLink(): void {
		$filesHooks = $this->getFilesHooks([
			'shareByLink',
		]);

		$filesHooks->expects($this->once())
			->method('shareByLink')
			->with(1337, 'file', 'admin');

		$filesHooks->share([
			'fileSource' => 1337,
			'shareType' => IShare::TYPE_LINK,
			'itemType' => 'file',
			'uidOwner' => 'admin',
		]);
	}

	public function dataShareWithUser(): array {
		return [
			['file', '/path.txt', true],
			['folder', '/path.txt', false],
		];
	}

	/**
	 * @dataProvider dataShareWithUser
	 *
	 * @param string $itemType
	 * @param string $fileTarget
	 * @param bool $isFile
	 */
	public function testShareWithUser(string $itemType, string $fileTarget, bool $isFile): void {
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

		$filesHooks->expects($this->once())
			->method('shareNotificationForSharer')
			->with('shared_user_self', 'recipient', 1337, $itemType);
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
			'recipient', 1337, $itemType, $fileTarget, true
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
			'no-group', 0, '', '', 0, true
		]);
	}

	public function dataShareWithGroup(): array {
		return [
			[
				[
					[],
				], 0, 0, [], [], []
			],
			[
				[
					[$this->getUserMock('user1')],
					[],
				], 2, 1, [], [], []
			],
			[
				[
					[$this->getUserMock('user1')],
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
					[$this->getUserMock('user1')],
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
					[$this->getUserMock('user')],
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
		$filesHooks = $this->getFilesHooks([
			'shareNotificationForSharer',
			'addNotificationsForUser',
			'fixPathsForShareExceptions',
			'shareNotificationForOriginalOwners',
		]);

		$group = $this->createMock(IGroup::class);
		for ($i = 0, $iMax = count($usersInGroup); $i < $iMax; $i++) {
			$group->expects($this->at($i))
				->method('searchUsers')
				->with('')
				->willReturn($usersInGroup[$i]);
		}

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
			->with('shared_group_self', 'group1', 42, 'file');
		$filesHooks->expects($this->exactly($fixCalls))
			->method('fixPathsForShareExceptions')
			->with($this->anything(), 1337)
			->willReturnArgument(0);
		$filesHooks->expects($this->once())
			->method('shareNotificationForOriginalOwners')
			->with('user', 'reshared_group_by', 'group1', 42, 'file')
			->willReturnArgument(0);

		$i = 3;
		foreach ($addNotifications as $user => $arguments) {
			$filesHooks->expects($this->at($i))
				->method('addNotificationsForUser')
				->with(
					$user,
					$arguments['subject'],
					$arguments['subject_params'],
					42,
					$arguments['path'],
					true,
					$arguments['email'],
					$arguments['notification'],
					Files_Sharing::TYPE_SHARED
				);
			$i++;
		}

		self::invokePrivate($filesHooks, 'shareWithGroup', [
			'group1', 42, 'file', '/file', 1337, true
		]);
	}

	public function dataReshareNotificationForSharer(): array {
		return [
			[null],
			['/path'],
		];
	}

	/**
	 * @dataProvider dataReshareNotificationForSharer
	 * @param string $path
	 */
	public function testReshareNotificationForSharer(?string $path): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForUser',
		]);

		$this->view->expects($this->once())
			->method('chroot')
			->with('/owner/files');
		if ($path === null) {
			$this->view->expects($this->once())
				->method('getPath')
				->willThrowException(new NotFoundException());
		} else {
			$this->view->expects($this->once())
				->method('getPath')
				->willReturn($path);
		}

		$this->settings->expects(($path !== null) ? $this->exactly(3) : $this->never())
			->method('getUserSetting')
			->willReturnMap([
				['owner', 'notification', Files_Sharing::TYPE_SHARED, true],
				['owner', 'email', Files_Sharing::TYPE_SHARED, true],
				['owner', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
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

		self::invokePrivate($filesHooks, 'reshareNotificationForSharer', ['owner', 'reshared_link_by', '', 42, 'file']);
	}

	public function dataShareNotificationForSharer(): array {
		return [
			[null],
			['/path'],
		];
	}

	/**
	 * @dataProvider dataShareNotificationForSharer
	 * @param string $path
	 */
	public function testShare(?string $path): void {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForUser',
			'shareNotificationForOriginalOwners',
		]);

		if ($path === null) {
			$this->view->expects($this->once())
				->method('getPath')
				->willThrowException(new NotFoundException());
		} else {
			$this->view->expects($this->once())
				->method('getPath')
				->willReturn($path);
		}

		$this->settings->expects(($path !== null) ? $this->exactly(3) : $this->never())
			->method('getUserSetting')
			->willReturnMap([
				['user', 'notification', Files_Sharing::TYPE_SHARED, true],
				['user', 'email', Files_Sharing::TYPE_SHARED, true],
				['user', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
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
		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
			->method('shareNotificationForOriginalOwners')
			->with('user', 'reshared_link_by', '', 42, 'file');

		self::invokePrivate($filesHooks, 'shareByLink', [42, 'file', 'user']);
	}

	public function testShareNotificationForOriginalOwnersNoPath(): void {
		$filesHooks = $this->getFilesHooks([
			'reshareNotificationForSharer',
		]);

		$this->view->expects($this->once())
			->method('getPath')
			->with(42)
			->willThrowException(new NotFoundException());

		$filesHooks->expects($this->never())
			->method('reshareNotificationForSharer');

		self::invokePrivate($filesHooks, 'shareNotificationForOriginalOwners', ['', '', '', 42, '', '']);
	}

	public function dataShareNotificationForOriginalOwners(): array {
		return [
			[false, false, 'owner', '', 1],
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
	public function testShareNotificationForOriginalOwners(bool $validMountPoint, bool $validSharedStorage, string $pathOwner, ?string $shareeUser, int $numReshareNotification): void {
		$filesHooks = $this->getFilesHooks([
			'reshareNotificationForSharer',
		]);

		$this->view->expects($this->atLeastOnce())
			->method('getPath')
			->willReturn('/path');

		$this->view->expects($this->once())
			->method('getOwner')
			->with('/path')
			->willReturn($pathOwner);

		$filesHooks->expects($this->exactly($numReshareNotification))
			->method('reshareNotificationForSharer')
			->with($this->anything(), 'subject', 'with', 42, 'type');

		if ($validMountPoint) {
			$storage = $this->getMockBuilder(SharedStorage::class)
				->disableOriginalConstructor()
				->onlyMethods([
					'instanceOfStorage',
					'getSharedFrom',
				])
				->getMock();
			$storage->expects($this->once())
				->method('instanceOfStorage')
				->with(SharedStorage::class)
				->willReturn($validSharedStorage);
			$storage->expects($validSharedStorage ? $this->once() : $this->never())
				->method('getSharedFrom')
				->willReturn($shareeUser);

			$mount = $this->createMock(IMountPoint::class);
			$mount->expects($this->once())
				->method('getStorage')
				->willReturn($storage);

			$this->view->expects($this->once())
				->method('getMount')
				->with('/path')
				->willReturn($mount);
		} else {
			$this->view->expects($this->once())
				->method('getMount')
				->with('/path')
				->willReturn(null);
		}

		self::invokePrivate($filesHooks, 'shareNotificationForOriginalOwners', ['current', 'subject', 'with', 42, 'type']);
	}

	/**
	 * @dataProvider dataShareNotificationForSharer
	 * @param string $path
	 */
	public function testShareNotificationForSharer(?string $path): void {
		$filesHooks = $this->getFilesHooks(['addNotificationsForUser']);

		if ($path === null) {
			$this->view->expects($this->once())
				->method('getPath')
				->willThrowException(new NotFoundException());
		} else {
			$this->view->expects($this->once())
				->method('getPath')
				->willReturn($path);
		}

		$this->settings->expects(($path !== null) ? $this->exactly(3) : $this->never())
			->method('getUserSetting')
			->willReturnMap([
				['user', 'notification', Files_Sharing::TYPE_SHARED, true],
				['user', 'email', Files_Sharing::TYPE_SHARED, true],
				['user', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
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

		self::invokePrivate($filesHooks, 'shareNotificationForSharer', ['subject', 'target', 42, 'file']);
	}

	public function dataAddNotificationsForUser(): array {
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
}
