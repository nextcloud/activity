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

use OCA\Activity\Formatter\CloudIDFormatter;
use OCA\Activity\Formatter\IFormatter;
use OCA\Activity\Tests\TestCase;

class CloudIDFormatterTest extends TestCase {
	/** @var \OCP\Contacts\IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $contactsManager;

	protected function setUp() {
		parent::setUp();

		$this->contactsManager = $this->getMockBuilder('OCP\Contacts\IManager')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param array $methods
	 * @return IFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	public function getFormatter(array $methods = []) {
		if (empty($methods)) {
			return new CloudIDFormatter(
				$this->contactsManager
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Formatter\CloudIDFormatter')
				->setConstructorArgs([
					$this->contactsManager,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataFormat() {
		return [
			['test1', '<federated-cloud-id display-name="test1" user="test1" server="">test1</federated-cloud-id>'],
			['test1@localhost1', '<federated-cloud-id display-name="test1@…" user="test1" server="localhost1">test1@localhost1</federated-cloud-id>'],
			['test2@localhost2', '<federated-cloud-id display-name="test2@…" user="test2" server="localhost2">test2@localhost2</federated-cloud-id>'],
			['t<e>st@l<o>calhost', '<federated-cloud-id display-name="t&lt;e&gt;st@…" user="t&lt;e&gt;st" server="l&lt;o&gt;calhost">t&lt;e&gt;st@l&lt;o&gt;calhost</federated-cloud-id>'],

		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param string $parameter
	 * @param string $expected
	 */
	public function testFormat($parameter, $expected) {
		/** @var \OCP\Activity\IEvent|\PHPUnit_Framework_MockObject_MockObject $event */
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();

		$formatter = $this->getFormatter(['getDisplayNameFromContact']);
		$formatter->expects($this->once())
			->method('getDisplayNameFromContact')
			->willThrowException(new \OutOfBoundsException());

		$this->assertSame($expected, $formatter->format($event, $parameter));
	}

	public function dataGetDisplayNameFromContact() {
		return [
			[
				'test@localhost',
				[
					[
						'FN'	=> 'Test On Localhost',
						'CLOUD'	=> [
							'test@localhost'
						],
					],
				],
				'Test On Localhost',
			],
			[
				'test@localhost',
				[
					[
						'FN'	=> 'Test On Localhost',
						'CLOUD'	=> [
							'test1@localhost',
							'test@localhost',
						],
					],
				],
				'Test On Localhost',
			],
		];
	}

	/**
	 * @dataProvider dataGetDisplayNameFromContact
	 *
	 * @param string $cloudId
	 * @param array $addressReturn
	 * @param string $expected
	 */
	public function testGetDisplayNameFromContact($cloudId, array $addressReturn, $expected) {
		$this->contactsManager->expects($this->once())
			->method('search')
			->with($cloudId, ['CLOUD'])
			->willReturn($addressReturn);

		$formatter = $this->getFormatter();
		$this->assertSame($expected, $this->invokePrivate($formatter, 'getDisplayNameFromContact', [$cloudId]));
		$this->assertSame($expected, $this->invokePrivate($formatter, 'getDisplayNameFromContact', [$cloudId]));
	}


	public function dataGetDisplayNameFromContactThrows() {
		return [
			[
				[],
			],
			[
				[
					[
						'FN'	=> 'test@localhost',
					],
				],
			],
			[
				[
					[
						'FN'	=> 'test@localhost',
						'CLOUD'	=> [],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider dataGetDisplayNameFromContactThrows
	 *
	 * @param array $addressReturn
	 */
	public function testGetDisplayNameFromContactThrows(array $addressReturn) {
		$this->contactsManager->expects($this->once())
			->method('search')
			->with('test@localhost', ['CLOUD'])
			->willReturn($addressReturn);

		$formatter = $this->getFormatter();

		try {
			$this->invokePrivate($formatter, 'getDisplayNameFromContact', ['test@localhost']);
			$this->assertTrue(false, 'Asserting that OutOfBoundsException is thrown');
		} catch (\OutOfBoundsException $e) {
			$this->assertTrue(true, 'Asserting that OutOfBoundsException is thrown');
		}

		try {
			$this->invokePrivate($formatter, 'getDisplayNameFromContact', ['test@localhost']);
			$this->assertTrue(false, 'Asserting that OutOfBoundsException is thrown');
		} catch (\OutOfBoundsException $e) {
			$this->assertTrue(true, 'Asserting that OutOfBoundsException is thrown');
		}
	}
}
