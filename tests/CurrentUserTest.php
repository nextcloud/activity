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
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserSession;
use OCP\L10N\IFactory;
use OCP\Share\Exceptions\ShareNotFound;
use OCP\Share\IManager;
use OCP\Share\IShare;
use PHPUnit\Framework\MockObject\MockObject;

/* We have to use this to add a property to the mocked request and avoid warnings about dynamic properties on PHP>=8.2 */
abstract class RequestMock implements IRequest {
	public array $server = [];
}

/**
 * Class CurrentUserTest
 *
 * @package OCA\Activity\Tests
 */
class CurrentUserTest extends TestCase {
	protected IRequest&MockObject $request;
	protected IUserSession&MockObject $userSession;
	protected IManager&MockObject $shareManager;
	protected IFactory&MockObject $l10nFactory;

	protected function setUp(): void {
		parent::setUp();

		$this->request = $this->createMock(RequestMock::class);
		$this->userSession = $this->createMock(IUserSession::class);
		$this->shareManager = $this->createMock(IManager::class);
		$this->l10nFactory = $this->createMock(IFactory::class);
	}

	/**
	 * @param array $methods
	 * @return CurrentUser&MockObject
	 */
	protected function getInstance(array $methods = []): CurrentUser {
		if (empty($methods)) {
			return new CurrentUser(
				$this->userSession,
				$this->request,
				$this->shareManager,
				$this->l10nFactory,
			);
		}

		return $this->getMockBuilder(CurrentUser::class)
			->setConstructorArgs([
				$this->userSession,
				$this->request,
				$this->shareManager,
				$this->l10nFactory,
			])
			->onlyMethods($methods)
			->getMock();
	}

	public function dataGetUserIdentifier(): array {
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
	public function testGetUserIdentifier(?string $cachedIdentifier, $uidResult, $tokenResult, string $expected): void {
		$instance = $this->getInstance([
			'getUID',
			'getCloudIDFromToken',
		]);

		self::invokePrivate($instance, 'identifier', [$cachedIdentifier]);

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
	protected function getUserMock(string $uid): IUser {
		$user = $this->createMock(IUser::class);
		$user->expects($this->once())
			->method('getUID')
			->willReturn($uid);
		return $user;
	}

	public function dataGetUID(): array {
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
	public function testGetUID(?IUser $user, ?string $expected): void {
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
	protected function getShareMock(int $type, ?string $shareWith): IShare {
		$share = $this->createMock(IShare::class);
		$share->expects($this->once())
			->method('getShareType')
			->willReturn($type);
		$share->expects($shareWith !== null  ? $this->once() : $this->never())
			->method('getSharedWith')
			->willReturn((string)$shareWith);
		return $share;
	}

	public function dataGetCloudIDFromToken(): array {
		return [
			[[], null, null],
			[['PHP_AUTH_USER' => 'token23'], $this->getShareMock(IShare::TYPE_LINK, null), null],
			[['PHP_AUTH_USER' => 'token32'], new ShareNotFound(), null],
			[['PHP_AUTH_USER' => 'token42'], $this->getShareMock(IShare::TYPE_REMOTE, 'test@localhost'), 'test@localhost'],
		];
	}

	/**
	 * @dataProvider dataGetCloudIDFromToken
	 *
	 * @param array $server
	 * @param IShare|\Exception|null $share
	 * @param string|null $expected
	 */
	public function testGetCloudIDFromToken(array $server, $share, ?string $expected): void {
		$instance = $this->getInstance();

		$this->request->server = $server;

		if ($share === null) {
			$this->shareManager->expects($this->never())
				->method('getShareByToken');
		} elseif ($share instanceof \Exception) {
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

		$this->assertSame($expected, self::invokePrivate($instance, 'getCloudIDFromToken'));
	}
}
