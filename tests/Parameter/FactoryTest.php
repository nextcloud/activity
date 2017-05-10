<?php
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

namespace OCA\Activity\Tests\Parameter;

use OCA\Activity\Parameter\Factory;
use OCA\Activity\Tests\TestCase;

class FactoryTest extends TestCase {
	/** @var \OCP\Activity\IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $activityManager;

	/** @var \OCP\IUserManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $userManager;

	/** @var \OCP\IURLGenerator|\PHPUnit_Framework_MockObject_MockObject */
	protected $urlGenerator;

	/** @var \OCP\Contacts\IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $contactsManager;

	/** @var \OCA\Activity\ViewInfoCache|\PHPUnit_Framework_MockObject_MockObject */
	protected $infoCache;

	/** @var \OCP\IConfig|\PHPUnit_Framework_MockObject_MockObject */
	protected $config;

	/** @var \OCP\IL10N|\PHPUnit_Framework_MockObject_MockObject */
	protected $l;

	protected function setUp() {
		parent::setUp();

		$this->activityManager = $this->getMockBuilder('OCP\Activity\IManager')
			->disableOriginalConstructor()
			->getMock();

		$this->userManager = $this->getMockBuilder('OCP\IUserManager')
			->disableOriginalConstructor()
			->getMock();

		$this->urlGenerator = $this->getMockBuilder('OCP\IURLGenerator')
			->disableOriginalConstructor()
			->getMock();

		$this->contactsManager = $this->getMockBuilder('OCP\Contacts\IManager')
			->disableOriginalConstructor()
			->getMock();

		$this->infoCache = $this->getMockBuilder('OCA\Activity\ViewInfoCache')
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
	 * @param string $user
	 * @return Factory|\PHPUnit_Framework_MockObject_MockObject
	 */
	public function getFactory(array $methods = [], $user = 'user') {
		$currentUser = $this->getMockBuilder('OCA\Activity\CurrentUser')
			->disableOriginalConstructor()
			->getMock();
		$currentUser->expects($this->once())
			->method('getUID')
			->willReturn($user);

		if (empty($methods)) {
			return new Factory(
				$this->activityManager,
				$this->userManager,
				$this->urlGenerator,
				$this->contactsManager,
				$this->infoCache,
				$this->l,
				$currentUser
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Parameter\Factory')
				->setConstructorArgs([
					$this->activityManager,
					$this->userManager,
					$this->urlGenerator,
					$this->contactsManager,
					$this->infoCache,
					$this->l,
					$currentUser,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataSetUser() {
		return [
			['user1', 'user2'],
			['user2', 'user3'],
		];
	}

	/**
	 * @dataProvider dataSetUser
	 *
	 * @param string $initUser
	 * @param string $setUser
	 */
	public function testSetUser($initUser, $setUser) {
		$factory = $this->getFactory([], $initUser);
		$this->assertSame($initUser, $this->invokePrivate($factory, 'user'));

		$factory->setUser($setUser);
		$this->assertSame($setUser, $this->invokePrivate($factory, 'user'));
		$this->assertNotSame($initUser, $this->invokePrivate($factory, 'user'));
	}

	public function testSetL10N() {
		$factory = $this->getFactory();
		$this->assertSame($this->l, $this->invokePrivate($factory, 'l'));

		/** @var \OCP\IL10N|\PHPUnit_Framework_MockObject_MockObject $l2 */
		$l2 = $this->getMockBuilder('OCP\IL10N')
			->disableOriginalConstructor()
			->getMock();

		$factory->setL10n($l2);
		$this->assertSame($l2, $this->invokePrivate($factory, 'l'));
		$this->assertNotSame($this->l, $this->invokePrivate($factory, 'l'));
	}

	public function dataGetParameter() {
		return [
			['parameter1', 'formatter1'],
			['parameter2', 'formatter2'],
		];
	}

	/**
	 * @dataProvider dataGetParameter
	 *
	 * @param string $parameter
	 * @param string $formatter
	 */
	public function testGetParameter($parameter, $formatter) {
		$factory = $this->getFactory(['getFormatter']);

		/** @var \OCP\Activity\IEvent|\PHPUnit_Framework_MockObject_MockObject $event */
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();

		$formatterInstance = $this->getMockBuilder('OCA\Activity\Formatter\IFormatter')
			->disableOriginalConstructor()
			->getMock();

		$factory->expects($this->once())
			->method('getFormatter')
			->with($formatter)
			->willReturn($formatterInstance);

		$instance = $factory->get($parameter, $event, $formatter);
		$this->assertInstanceOf('OCA\Activity\Parameter\IParameter', $instance);
		$this->assertInstanceOf('OCA\Activity\Parameter\Parameter', $instance);

		$this->assertSame($parameter, $instance->getParameter());
		$this->assertSame($formatterInstance, $this->invokePrivate($instance, 'formatter'));
	}

	public function testCreateCollection() {
		$factory = $this->getFactory();

		$collection1 = $factory->createCollection();
		$this->assertInstanceOf('OCA\Activity\Parameter\IParameter', $collection1);
		$this->assertInstanceOf('OCA\Activity\Parameter\Collection', $collection1);

		$collection2 = $factory->createCollection();
		$this->assertNotSame($collection2->getParameter(), $collection1->getParameter(), 'Collection identifier should not be reused');
	}

	public function dataGetFormatter() {
		return [
			['base', 'OCA\Activity\Formatter\BaseFormatter'],
			['file', 'OCA\Activity\Formatter\FileFormatter'],
			['email', 'OCA\Activity\Formatter\EmailFormatter'],
			['username', 'OCA\Activity\Formatter\UserFormatter'],
			['federated_cloud_id', 'OCA\Activity\Formatter\CloudIDFormatter'],
		];
	}

	/**
	 * @dataProvider dataGetFormatter
	 *
	 * @param string $formatter
	 * @param string $formatterClass
	 */
	public function testGetFormatter($formatter, $formatterClass) {
		$factory = $this->getFactory();
		$formatterInstance = $this->invokePrivate($factory, 'getFormatter', [$formatter]);

		$this->assertInstanceOf('OCA\Activity\Formatter\IFormatter', $formatterInstance);
		$this->assertInstanceOf($formatterClass, $formatterInstance);
	}
}
