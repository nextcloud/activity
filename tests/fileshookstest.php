<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace OCA\Activity;

use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCA\Activity\Tests\TestCase;
use OCP\Share;

/**
 * Testing the public methods with internals being mocked out
 */
class FilesHooksTest extends TestCase {
	/** @var \OCA\Activity\FilesHooks */
	protected $filesHooks;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\OCP\Activity\IManager */
	protected $activityManager;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\OCA\Activity\Data */
	protected $data;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\OCA\Activity\UserSettings */
	protected $settings;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\OCP\IGroupManager */
	protected $groupManager;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\OC\Files\View */
	protected $view;

	protected function setUp() {
		parent::setUp();

		$this->activityManager = $this->getMockBuilder('OCP\Activity\IManager')
			->disableOriginalConstructor()
			->getMock();
		$this->data = $this->getMockBuilder('OCA\Activity\Data')
			->disableOriginalConstructor()
			->getMock();
		$this->settings = $this->getMockBuilder('OCA\Activity\UserSettings')
			->disableOriginalConstructor()
			->getMock();
		$this->data = $this->getMockBuilder('OCA\Activity\Data')
			->disableOriginalConstructor()
			->getMock();
		$this->groupManager = $this->getMockBuilder('OCP\IGroupManager')
			->disableOriginalConstructor()
			->getMock();
		$this->view = $this->getMockBuilder('OC\Files\View')
			->disableOriginalConstructor()
			->getMock();

		$this->filesHooks = $this->getFilesHooks();
	}

	/**
	 * @param array $mockedMethods
	 * @param string $user
	 * @return FilesHooks|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getFilesHooks(array $mockedMethods = [], $user = 'user') {
		if (!empty($mockedMethods)) {
			return $this->getMockBuilder('OCA\Activity\FilesHooks')
				->setConstructorArgs([
					$this->activityManager,
					$this->data,
					$this->settings,
					$this->groupManager,
					$this->view,
					\OC::$server->getDatabaseConnection(),
					$user,
				])
				->setMethods($mockedMethods)
				->getMock();
		} else {
			return new FilesHooks(
				$this->activityManager,
				$this->data,
				$this->settings,
				$this->groupManager,
				$this->view,
				\OC::$server->getDatabaseConnection(),
				$user
			);
		}
	}

	protected function getUserMock($uid) {
		$user = $this->getMockBuilder('OCP\IUser')
			->disableOriginalConstructor()
			->getMock();
		$user->expects($this->any())
			->method('getUID')
			->willReturn($uid);
		return $user;
	}

	public function dataGetCurrentUser() {
		return [
			['user'],
			[false],
		];
	}

	/**
	 * @dataProvider dataGetCurrentUser
	 *
	 * @param mixed $user
	 */
	public function testGetCurrentUser($user) {
		$filesHooks = $this->getFilesHooks([], $user);
		$this->assertSame($user, $this->invokePrivate($filesHooks, 'getCurrentUser'));
	}

	public function dataFileCreate() {
		return [
			['user', 'created_self', 'created_by'],
			[false, '', 'created_public'],
		];
	}

	/**
	 * @dataProvider dataFileCreate
	 *
	 * @param mixed $currentUser
	 * @param string $selfSubject
	 * @param string $othersSubject
	 */
	public function testFileCreate($currentUser, $selfSubject, $othersSubject) {
		$filesHooks = $this->getFilesHooks([
			'getCurrentUser',
			'addNotificationsForFileAction',
		]);
		$filesHooks->expects($this->once())
			->method('getCurrentUser')
			->willReturn($currentUser);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_SHARE_CREATED, $selfSubject, $othersSubject);

		$filesHooks->fileCreate('path');
	}

	public function testFileUpdate() {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_SHARE_CHANGED, 'changed_self', 'changed_by');

		$filesHooks->fileUpdate('path');
	}

	public function testFileDelete() {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_SHARE_DELETED, 'deleted_self', 'deleted_by');

		$filesHooks->fileDelete('path');
	}

	public function testFileRestore() {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForFileAction',
		]);

		$filesHooks->expects($this->once())
			->method('addNotificationsForFileAction')
			->with('path', Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by');

		$filesHooks->fileRestore('path');
	}

	public function testAddNotificationsForFileActionPartFile() {
		$filesHooks = $this->getFilesHooks([
			'getSourcePathAndOwner',
		]);

		$filesHooks->expects($this->never())
			->method('getSourcePathAndOwner');

		$this->invokePrivate($filesHooks, 'addNotificationsForFileAction', ['/test.txt.part', '', '', '']);
	}

	public function dataAddNotificationsForFileAction() {
		return [
			[
				[
					[['user', 'user1', 'user2'], 'stream', Files::TYPE_SHARE_RESTORED, ['user' => true]],
					[['user', 'user1', 'user2'], 'email', Files::TYPE_SHARE_RESTORED, ['user' => 42]],
				],
				[
					'user' => [
						'subject' => 'restored_self',
						'subject_params' => ['/user/path'],
						'path' => '/user/path',
						'stream' => true,
						'email' => 42,
					],
				],
			],
			[
				[
					[['user', 'user1', 'user2'], 'stream', Files::TYPE_SHARE_RESTORED, ['user1' => true]],
					[['user', 'user1', 'user2'], 'email', Files::TYPE_SHARE_RESTORED, []],
				],
				[
					'user1' => [
						'subject' => 'restored_by',
						'subject_params' => ['/user1/path', 'user'],
						'path' => '/user1/path',
						'stream' => true,
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
	 * @param array $addNotifications
	 */
	public function testAddNotificationsForFileAction($filterUsers, $addNotifications) {
		$filesHooks = $this->getFilesHooks([
			'getSourcePathAndOwner',
			'getUserPathsFromPath',
			'addNotificationsForUser',
		]);

		$filesHooks->expects($this->once())
			->method('getSourcePathAndOwner')
			->with('path')
			->willReturn(['/owner/path', 'owner', 1337]);
		$filesHooks->expects($this->once())
			->method('getUserPathsFromPath')
			->with('/owner/path', 'owner')
			->willReturn([
				'user' => '/user/path',
				'user1' => '/user1/path',
				'user2' => '/user2/path',
			]);

		$this->settings->expects($this->exactly(2))
			->method('filterUsersBySetting')
			->willReturnMap($filterUsers);

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
					$arguments['stream'],
					$arguments['email'],
					Files::TYPE_SHARE_RESTORED
				);
			$i++;
		}

		$this->invokePrivate($filesHooks, 'addNotificationsForFileAction', ['path', Files::TYPE_SHARE_RESTORED, 'restored_self', 'restored_by']);
	}

	public function testShareWithUser() {
		$filesHooks = $this->getFilesHooks([
			'shareFileOrFolderWithUser',
		]);

		$filesHooks->expects($this->once())
			->method('shareFileOrFolderWithUser')
			->with('u1', 1337, 'file', 'path');

		$filesHooks->share([
			'fileSource' => 1337,
			'shareType' => Share::SHARE_TYPE_USER,
			'shareWith' => 'u1',
			'itemType' => 'file',
			'fileTarget' => 'path',
		]);
	}

	public function testShareWithGroup() {
		$filesHooks = $this->getFilesHooks([
			'shareFileOrFolderWithGroup',
		]);

		$filesHooks->expects($this->once())
			->method('shareFileOrFolderWithGroup')
			->with('g1', 1337, 'file', 'path', 42);

		$filesHooks->share([
			'fileSource' => 1337,
			'shareType' => Share::SHARE_TYPE_GROUP,
			'shareWith' => 'g1',
			'itemType' => 'file',
			'fileTarget' => 'path',
			'id' => '42',
		]);
	}

	public function testShareViaPublicLink() {
		$filesHooks = $this->getFilesHooks([
			'shareFileOrFolder',
		]);

		$filesHooks->expects($this->once())
			->method('shareFileOrFolder')
			->with(1337, 'file');

		$filesHooks->share([
			'fileSource' => 1337,
			'shareWith' => '',
			'itemType' => 'file',
		]);
	}

	public function dataShareFileOrFolderWithUser() {
		return [
			['file', '/path.txt', true],
			['folder', '/path.txt', false],
		];
	}

	/**
	 * @dataProvider dataShareFileOrFolderWithUser
	 *
	 * @param string $itemType
	 * @param string $fileTarget
	 * @param bool $isFile
	 */
	public function testShareFileOrFolderWithUser($itemType, $fileTarget, $isFile) {
		$filesHooks = $this->getFilesHooks([
			'shareNotificationForSharer',
			'addNotificationsForUser',
			'shareNotificationForOriginalOwners',
		]);

		$this->settings->expects($this->exactly(3))
			->method('getUserSetting')
			->willReturnMap(
				[
					['recipient', 'stream', Files_Sharing::TYPE_SHARED, true],
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
				[$fileTarget, 'user'],
				1337,
				$fileTarget,
				$isFile,
				true,
				42
			);

		$this->invokePrivate($filesHooks, 'shareFileOrFolderWithUser', [
			'recipient', 1337, $itemType, $fileTarget
		]);
	}

	public function testShareFileOrFolderWithGroupNonExisting() {
		$filesHooks = $this->getFilesHooks([
			'shareNotificationForSharer'
		]);

		$this->groupManager->expects($this->once())
			->method('get')
			->with('no-group')
			->willReturn(null);

		$filesHooks->expects($this->never())
			->method('shareNotificationForSharer');

		$this->invokePrivate($filesHooks, 'shareFileOrFolderWithGroup', [
			'no-group', 0, '', '', 0
		]);
	}

	public function dataShareFileOrFolderWithGroup() {
		return [
			[[], [], [], []],
			[[$this->getUserMock('user')], [], [], []],
			[
				[$this->getUserMock('user1')],
				['user1'],
				[
					[['user1'], 'stream', Files_Sharing::TYPE_SHARED, ['user1' => true]],
					[['user1'], 'email', Files_Sharing::TYPE_SHARED, []],
				],
				[
					'user1' => [
						'subject' => 'shared_with_by',
						'subject_params' => ['/file', 'user'],
						'path' => '/file',
						'stream' => true,
						'email' => 0,
					],
				],
			],
			[
				[$this->getUserMock('user1')],
				['user1'],
				[
					[['user1'], 'stream', Files_Sharing::TYPE_SHARED, ['user1' => false]],
					[['user1'], 'email', Files_Sharing::TYPE_SHARED, ['user1' => false]],
				],
				[],
			],
		];
	}

	/**
	 * @dataProvider dataShareFileOrFolderWithGroup
	 * @param array $usersInGroup
	 * @param array $settingUsers
	 * @param array $settingsReturn
	 * @param array $addNotifications
	 */
	public function testShareFileOrFolderWithGroup($usersInGroup, $settingUsers, $settingsReturn, $addNotifications) {
		$filesHooks = $this->getFilesHooks([
			'shareNotificationForSharer',
			'addNotificationsForUser',
			'fixPathsForShareExceptions',
			'shareNotificationForOriginalOwners',
		]);

		$group = $this->getMockBuilder('OCP\IGroup')
			->disableOriginalConstructor()
			->getMock();
		$group->expects($this->once())
			->method('searchUsers')
			->with('')
			->willReturn($usersInGroup);

		$this->groupManager->expects($this->once())
			->method('get')
			->with('group1')
			->willReturn($group);

		$this->settings->expects(empty($settingUsers) ? $this->never() : $this->exactly(2))
			->method('filterUsersBySetting')
			#->with($settingUsers, $this->anything(), Files_Sharing::TYPE_SHARED)
			->willReturnMap($settingsReturn);

		$filesHooks->expects($this->once())
			->method('shareNotificationForSharer')
			->with('shared_group_self', 'group1', 42, 'file');
		$filesHooks->expects(empty($settingUsers) ? $this->never() : $this->once())
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
					$arguments['stream'],
					$arguments['email'],
					Files_Sharing::TYPE_SHARED
				);
			$i++;
		}

		$this->invokePrivate($filesHooks, 'shareFileOrFolderWithGroup', [
			'group1', 42, 'file', '/file', 1337
		]);
	}

	public function dataAddNotificationsForUserWithoutSettings() {
		return [
			['user', 'subject', ['parameter'], 42, 'path', true, false, false, Files::TYPE_SHARE_CREATED]
		];
	}

	/**
	 * @dataProvider dataAddNotificationsForUserWithoutSettings
	 *
	 * @param string $user
	 * @param string $subject
	 * @param array $parameter
	 * @param int $fileId
	 * @param string $path
	 * @param bool $isFile
	 * @param bool $stream
	 * @param bool $email
	 * @param int $type
	 */
	public function testAddNotificationsForUserWithoutSettings($user, $subject, $parameter, $fileId, $path, $isFile, $stream, $email, $type) {
		$this->activityManager->expects($this->never())
			->method('generateEvent');

		$this->invokePrivate($this->filesHooks, 'addNotificationsForUser', [$user, $subject, $parameter, $fileId, $path, $isFile, $stream, $email, $type]);
	}

	public function dataReshareNotificationForSharer() {
		return [
			[null],
			['/path'],
		];
	}

	/**
	 * @dataProvider dataReshareNotificationForSharer
	 * @param string $path
	 */
	public function testReshareNotificationForSharer($path) {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForUser',
		]);

		$this->view->expects($this->once())
			->method('chroot')
			->with('/owner/files');
		$this->view->expects($this->once())
			->method('getPath')
			->willReturn($path);

		$this->settings->expects(($path !== null) ? $this->exactly(3) : $this->never())
			->method('getUserSetting')
			->willReturnMap([
				['owner', 'stream', Files_Sharing::TYPE_SHARED, true],
				['owner', 'email', Files_Sharing::TYPE_SHARED, true],
				['owner', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
			->method('addNotificationsForUser')
			->with(
				'owner',
				'reshared_link_by',
				['/path', 'user', ''],
				42,
				'/path',
				true,
				true,
				21
			);

		$this->invokePrivate($filesHooks, 'reshareNotificationForSharer', ['owner', 'reshared_link_by', '', 42, 'file']);
	}

	public function dataShareNotificationForSharer() {
		return [
			[null],
			['/path'],
		];
	}

	/**
	 * @dataProvider dataShareNotificationForSharer
	 * @param string $path
	 */
	public function testShareFileOrFolder($path) {
		$filesHooks = $this->getFilesHooks([
			'addNotificationsForUser',
			'shareNotificationForOriginalOwners',
		]);

		$this->view->expects($this->once())
			->method('getPath')
			->willReturn($path);

		$this->settings->expects(($path !== null) ? $this->exactly(3) : $this->never())
			->method('getUserSetting')
			->willReturnMap([
				['user', 'stream', Files_Sharing::TYPE_SHARED, true],
				['user', 'email', Files_Sharing::TYPE_SHARED, true],
				['user', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
			->method('addNotificationsForUser')
			->with(
				'user',
				'shared_link_self',
				['/path'],
				42,
				'/path',
				true,
				true,
				21
			);
		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
			->method('shareNotificationForOriginalOwners')
			->with('user', 'reshared_link_by', '', 42, 'file');

		$this->invokePrivate($filesHooks, 'shareFileOrFolder', [42, 'file']);
	}

	public function testShareNotificationForOriginalOwnersNoPath() {
		$filesHooks = $this->getFilesHooks([
			'reshareNotificationForSharer',
		]);

		$this->view->expects($this->once())
			->method('getPath')
			->with(42)
			->willReturn(null);

		$filesHooks->expects($this->never())
			->method('reshareNotificationForSharer');

		$this->invokePrivate($filesHooks, 'shareNotificationForOriginalOwners', ['', '', '', 42, '', '']);
	}

	public function dataShareNotificationForOriginalOwners() {
		return [
			[false, false, 'owner', '', 1],
			[true, false, 'owner', '', 1],
			[true, true, 'owner', null, 1],
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
	 * @param string $shareeUser
	 * @param bool $reshareNotificationForSharerTwice
	 */
	public function testShareNotificationForOriginalOwners($validMountPoint, $validSharedStorage, $pathOwner, $shareeUser, $numReshareNotification) {
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
			$storage = $this->getMockBuilder('OC\Files\Storage\Shared')
				->disableOriginalConstructor()
				->setMethods([
					'instanceOfStorage',
					'getSharedFrom',
				])
				->getMock();
			$storage->expects($this->once())
				->method('instanceOfStorage')
				->with('OC\Files\Storage\Shared')
				->willReturn($validSharedStorage);
			$storage->expects($validSharedStorage ? $this->once() : $this->never())
				->method('getSharedFrom')
				->willReturn($shareeUser);

			$mount = $this->getMockBuilder('OCP\Files\Mount\IMountPoint')
				->disableOriginalConstructor()
				->getMock();
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

		$this->invokePrivate($filesHooks, 'shareNotificationForOriginalOwners', ['current', 'subject', 'with', 42, 'type']);
	}

	/**
	 * @dataProvider dataShareNotificationForSharer
	 * @param string $path
	 */
	public function testShareNotificationForSharer($path) {
		$filesHooks = $this->getFilesHooks(['addNotificationsForUser']);

		$this->view->expects($this->once())
			->method('getPath')
			->willReturn($path);

		$this->settings->expects(($path !== null) ? $this->exactly(3) : $this->never())
			->method('getUserSetting')
			->willReturnMap([
				['user', 'stream', Files_Sharing::TYPE_SHARED, true],
				['user', 'email', Files_Sharing::TYPE_SHARED, true],
				['user', 'setting', 'batchtime', 21],
			]);

		$filesHooks->expects(($path !== null) ? $this->once() : $this->never())
			->method('addNotificationsForUser')
			->with(
				'user',
				'subject',
				['/path', 'target'],
				42,
				'/path',
				true,
				true,
				21
			);

		$this->invokePrivate($filesHooks, 'shareNotificationForSharer', ['subject', 'target', 42, 'file']);
	}

	public function dataAddNotificationsForUser() {
		return [
			['user', 'subject', ['parameter'], 42, 'path', true, true, false, Files_Sharing::TYPE_SHARED, false, false, 'files_sharing', false, false],
			['user', 'subject', ['parameter'], 42, 'path', true, true, false, Files_Sharing::TYPE_SHARED, true, false, 'files_sharing', true, false],
			['notAuthor', 'subject', ['parameter'], 42, 'path', true, true, false, Files::TYPE_SHARE_CREATED, false, false, 'files', true, false],
			['notAuthor', 'subject', ['parameter'], 0, 'path', true, true, false, Files::TYPE_SHARE_CREATED, false, false, 'files', true, false],

			['user', 'subject', ['parameter'], 42, 'path', true, false, true, Files_Sharing::TYPE_SHARED, false, false, 'files_sharing', false, false],
			['user', 'subject', ['parameter'], 42, 'path', true, false, true, Files_Sharing::TYPE_SHARED, false, true, 'files_sharing', false, true],
			['notAuthor', 'subject', ['parameter'], 42, 'path', true, false, true, Files::TYPE_SHARE_CREATED, false, false, 'files', false, true],
			['notAuthor', 'subject', ['parameter'], 0, 'path', true, false, true, Files::TYPE_SHARE_CREATED, false, false, 'files', false, true],
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
	 * @param bool $isFile
	 * @param bool $stream
	 * @param bool $email
	 * @param int $type
	 * @param bool $selfSetting
	 * @param bool $selfEmailSetting
	 * @param string $app
	 * @param bool $sentStream
	 * @param bool $sentEmail
	 */
	public function testAddNotificationsForUser($user, $subject, $parameter, $fileId, $path, $isFile, $stream, $email, $type, $selfSetting, $selfEmailSetting, $app, $sentStream, $sentEmail) {
		$this->settings->expects($this->any())
			->method('getUserSetting')
			->willReturnMap([
				[$user, 'setting', 'self', $selfSetting],
				[$user, 'setting', 'selfemail', $selfEmailSetting],
			]);

		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();
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

		$this->data->expects($sentStream ? $this->once() : $this->never())
			->method('send')
			->with($event);
		$this->data->expects($sentEmail ? $this->once() : $this->never())
			->method('storeMail')
			->with($event, $this->anything());

		$this->invokePrivate($this->filesHooks, 'addNotificationsForUser', [$user, $subject, $parameter, $fileId, $path, $isFile, $stream, $email, $type]);
	}
}
