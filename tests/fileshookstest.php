<?php

/**
 * ownCloud - Activities App
 *
 * @author Frank Karlitschek, Joas Schilling
 * @copyright 2013 Frank Karlitschek frank@owncloud.org
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

use OC\Files\Filesystem;
use OC\Files\View;
use OCA\Activity\Extension\Files;
use OCA\Activity\Extension\Files_Sharing;
use OCA\Activity\Tests\TestCase;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\IDBConnection;
use OCP\Share;
use OCP\Util;

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

		$this->filesHooks = $this->getFilesHooks();
	}

	protected function getFilesHooks(array $mockedMethods = []) {
		if (!empty($mockedMethods)) {
			return $this->getMockBuilder('OCA\Activity\FilesHooks')
				->disableOriginalConstructor()
				->setMethods($mockedMethods)
				->getMock();
		} else {
			return new \OCA\Activity\FilesHooks(
				$this->activityManager,
				$this->data,
				$this->settings,
				\OC::$server->getDatabaseConnection(),
				'user'
			);
		}
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
		$filesHooks = new \OCA\Activity\FilesHooks(
			$this->activityManager,
			$this->data,
			$this->settings,
			\OC::$server->getDatabaseConnection(),
			$user
		);

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
}
