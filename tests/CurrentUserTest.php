<?php
/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Tests;


use OCA\Activity\CurrentUser;
use OCP\IUser;
use OCP\Share;
use OCP\Share\Exceptions\ShareNotFound;
use OCP\Share\IShare;

/**
 * Class CurrentUserTest
 *
 * @package OCA\Activity\Tests
 */
class CurrentUserTest extends TestCase {

	/** @var \OCP\IRequest|\PHPUnit_Framework_MockObject_MockObject */
	protected $request;

	/** @var \OCP\IUserSession|\PHPUnit_Framework_MockObject_MockObject */
	protected $userSession;

	/** @var \OCP\Share\IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $shareManager;

	protected function setUp() {
		parent::setUp();

		$this->request = $this->getMockBuilder('OCP\IRequest')
			->disableOriginalConstructor()
			->getMock();

		$this->userSession = $this->getMockBuilder('OCP\IUserSession')
			->disableOriginalConstructor()
			->getMock();

		$this->shareManager = $this->getMockBuilder('OCP\Share\IManager')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param array $methods
	 * @return CurrentUser|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getInstance(array $methods = []) {
		if (empty($methods)) {
			return new CurrentUser(
				$this->userSession,
				$this->request,
				$this->shareManager
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\CurrentUser')
				->setConstructorArgs([
					$this->userSession,
					$this->request,
					$this->shareManager,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataGetUserIdentifier() {
		return [
			[null, null, null, ''],
			[null, 'uid', -1, 'uid'],
			[null, null, 'token', 'token'],
			['cached', -1, -1, 'cached'],
		];
	}

	/**
	 * @dataProvider dataGetUserIdentifier
	 *
	 * @param string|null $cachedIdentifier
	 * @param string|int|null $uidResult
	 * @param string|int|null $tokenResult
	 * @param string $expected
	 */
	public function testGetUserIdentifier($cachedIdentifier, $uidResult, $tokenResult, $expected) {
		$instance = $this->getInstance([
			'getUID',
			'getCloudIDFromToken',
		]);

		$this->invokePrivate($instance, 'identifier', [$cachedIdentifier]);

		$instance->expects($uidResult !== -1 ? $this->once() : $this->never())
			->method('getUID')
			->willReturn($uidResult);

		$instance->expects($tokenResult !== -1 ? $this->once() : $this->never())
			->method('getCloudIDFromToken')
			->willReturn($tokenResult);

		$this->assertSame($expected, $instance->getUserIdentifier());
	}

	/**
	 * @param string $uid
	 * @return IUser
	 */
	protected function getUserMock($uid) {
		$user = $this->getMockBuilder('OCP\IUser')
			->disableOriginalConstructor()
			->getMock();
		$user->expects($this->once())
			->method('getUID')
			->willReturn($uid);
		return $user;
	}

	public function dataGetUID() {
		return [
			[null, null],
			[$this->getUserMock('uid'), 'uid'],
			[$this->getUserMock('test'), 'test'],
		];
	}

	/**
	 * @dataProvider dataGetUID
	 *
	 * @param IUser|null $user
	 * @param string|null $expected
	 */
	public function testGetUID($user, $expected) {
		$instance = $this->getInstance();

		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($user);

		$this->assertSame($expected, $instance->getUID());
	}

	/**
	 * @param int $type
	 * @param string $shareWith
	 * @return IShare
	 */
	protected function getShareMock($type, $shareWith) {
		$share = $this->getMockBuilder('OCP\Share\IShare')
			->disableOriginalConstructor()
			->getMock();
		$share->expects($this->once())
			->method('getShareType')
			->willReturn($type);
		$share->expects($shareWith !== null  ? $this->once() : $this->never())
			->method('getSharedWith')
			->willReturn($shareWith);
		return $share;
	}

	public function dataGetCloudIDFromToken() {
		return [
			[[], null, null],
			[['PHP_AUTH_USER' => 'token23'], $this->getShareMock(Share::SHARE_TYPE_LINK, null), null],
			[['PHP_AUTH_USER' => 'token32'], new ShareNotFound(), null],
			[['PHP_AUTH_USER' => 'token42'], $this->getShareMock(Share::SHARE_TYPE_REMOTE, 'test@localhost'), 'test@localhost'],
		];
	}

	/**
	 * @dataProvider dataGetCloudIDFromToken
	 *
	 * @param array $server
	 * @param IShare|\Exception|null $share
	 * @param string|null $expected
	 */
	public function testGetCloudIDFromToken(array $server, $share, $expected) {
		$instance = $this->getInstance();

		$this->request->server = $server;

		if ($share === null) {
			$this->shareManager->expects($this->never())
				->method('getShareByToken');
		} else if ($share instanceof \Exception) {
			$this->shareManager->expects($this->once())
				->method('getShareByToken')
				->with($server['PHP_AUTH_USER'])
				->willThrowException($share);
		} else {
			$this->shareManager->expects($this->once())
				->method('getShareByToken')
				->with($server['PHP_AUTH_USER'])
				->willReturn($share);
		}

		$this->assertSame($expected, $this->invokePrivate($instance, 'getCloudIDFromToken'));
	}
}
