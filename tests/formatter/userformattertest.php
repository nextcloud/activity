<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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

namespace OCA\Activity\Tests\Formatter;

use OCA\Activity\Formatter\IFormatter;
use OCA\Activity\Formatter\UserFormatter;
use OCA\Activity\Tests\TestCase;

class UserFormatterTest extends TestCase {

	/** @var \OCP\IUserManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $userManager;

	/** @var \OCP\IL10N|\PHPUnit_Framework_MockObject_MockObject */
	protected $l;

	protected function setUp() {
		parent::setUp();

		$this->userManager = $this->getMockBuilder('OCP\IUserManager')
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
				$this->l
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Formatter\UserFormatter')
				->setConstructorArgs([
					$this->userManager,
					$this->l,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataFormatRemoteUser() {
		return [
			['<user display-name="&quot;remote user&quot;"></user>'],
		];
	}

	/**
	 * @dataProvider dataFormatRemoteUser
	 *
	 * @param string $expected
	 */
	public function testFormatRemoteUser($expected) {
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
		$this->assertSame($expected, $formatter->format($event, ''));
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
			['nouser', null, '<user display-name="nouser">nouser</user>'],
			['user1', $this->getUser('Display1'), '<user display-name="Display1">user1</user>'],
			['user5', $this->getUser('Display5'), '<user display-name="Display5">user5</user>'],
		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param string $parameter
	 * @param null|\OCP\IUser $user
	 * @param string $expected
	 */
	public function testFormat($parameter, $user, $expected) {
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

		$formatter = $this->getFormatter();
		$this->assertSame($expected, $formatter->format($event, $parameter));
	}
}
