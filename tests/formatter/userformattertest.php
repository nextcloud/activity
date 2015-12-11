<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
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

namespace OCA\Activity\Tests\Formatter;

use OCA\Activity\Formatter\IFormatter;
use OCA\Activity\Formatter\UserFormatter;
use OCA\Activity\Tests\TestCase;

class UserFormatterTest extends TestCase {

	/** @var \OCP\IUserManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $userManager;

	/** @var \OCP\IConfig|\PHPUnit_Framework_MockObject_MockObject */
	protected $config;

	/** @var \OCP\IL10N|\PHPUnit_Framework_MockObject_MockObject */
	protected $l;

	protected function setUp() {
		parent::setUp();

		$this->userManager = $this->getMockBuilder('OCP\IUserManager')
			->disableOriginalConstructor()
			->getMock();

		$this->config = $this->getMockBuilder('OCP\IConfig')
			->disableOriginalConstructor()
			->getMock();

		$this->l = $this->getMockBuilder('OCP\IL10N')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param array $methods
	 * @return IFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	public function getFormatter(array $methods = []) {
		if (empty($methods)) {
			return new UserFormatter(
				$this->userManager,
				$this->config,
				$this->l
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Formatter\UserFormatter')
				->setConstructorArgs([
					$this->userManager,
					$this->config,
					$this->l,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataFormatRemoteUser() {
		return [
			[true, true, '<strong>"remote user"</strong>'],
			[false, true, '"remote user"'],
			[true, false, '<strong>"remote user"</strong>'],
			[false, false, '"remote user"'],
			[null, null, '<user display-name="&quot;remote user&quot;"></user>'],
		];
	}

	/**
	 * @dataProvider dataFormatRemoteUser
	 *
	 * @param bool $allowHtml
	 * @param bool $verbose
	 * @param string $expected
	 */
	public function testFormatRemoteUser($allowHtml, $verbose, $expected) {
		/** @var \OCP\Activity\IEvent|\PHPUnit_Framework_MockObject_MockObject $event */
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();

		$this->l->expects($this->once())
			->method('t')
			->willReturnCallback(function ($string, $parameters) {
				return vsprintf($string, $parameters);
			});
		$this->userManager->expects($this->never())
			->method('get');

		$formatter = $this->getFormatter();
		$this->assertSame($expected, $formatter->format($event, '', $allowHtml, $verbose));
	}

	protected function getUser($displayName) {
		$user = $this->getMockBuilder('OCP\IUser')
			->disableOriginalConstructor()
			->getMock();
		$user->expects($this->once())
			->method('getDisplayName')
			->willReturn($displayName);
		return $user;
	}

	public function dataFormat() {
		return [
			['nouser', null, false, true, true, '<strong>nouser</strong>'],
			['nouser', null, false, false, true, 'nouser'],
			['nouser', null, false, true, false, '<strong>nouser</strong>'],
			['nouser', null, false, false, false, 'nouser'],

			['user', $this->getUser('Display'), false, true, true, '<strong>Display</strong>'],
			['user', $this->getUser('Display'), false, false, true, 'Display'],
			['user', $this->getUser('Display'), false, true, false, '<strong>Display</strong>'],
			['user', $this->getUser('Display'), false, false, false, 'Display'],

			['nouser1', null, true, true, true, '<div class="avatar" data-user="nouser1"></div><strong>nouser1</strong>'],
			['nouser2', null, true, false, true, 'nouser2'],
			['nouser3', null, true, true, false, '<div class="avatar" data-user="nouser3"></div><strong>nouser3</strong>'],
			['nouser4', null, true, false, false, 'nouser4'],

			['user1', $this->getUser('Display'), true, true, true, '<div class="avatar" data-user="user1"></div><strong>Display</strong>'],
			['user2', $this->getUser('Display'), true, false, true, 'Display'],
			['user3', $this->getUser('Display'), true, true, false, '<div class="avatar" data-user="user3"></div><strong>Display</strong>'],
			['user4', $this->getUser('Display'), true, false, false, 'Display'],
			['user5', $this->getUser('Display5'), true, null, null, '<user display-name="Display5">user5</user>'],
			['user5', $this->getUser('Display5'), false, null, null, '<user display-name="Display5">user5</user>'],
		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param string $parameter
	 * @param null|\OCP\IUser $user
	 * @param bool $avatarsEnabled
	 * @param bool $allowHtml
	 * @param bool $verbose
	 * @param string $expected
	 */
	public function testFormat($parameter, $user, $avatarsEnabled, $allowHtml, $verbose, $expected) {
		/** @var \OCP\Activity\IEvent|\PHPUnit_Framework_MockObject_MockObject $event */
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();

		$this->l->expects($this->never())
			->method('t');
		$this->userManager->expects($this->once())
			->method('get')
			->with($parameter)
			->willReturn($user);
		$this->config->expects($this->any())
			->method('getSystemValue')
			->with('enable_avatars', true)
			->willReturn($avatarsEnabled);

		$formatter = $this->getFormatter();
		$this->assertSame($expected, $formatter->format($event, $parameter, $allowHtml, $verbose));
	}
}
