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

use OCA\Activity\Parameter\Collection;
use OCA\Activity\Tests\TestCase;

class CollectionTest extends TestCase {
	/** @var \OCP\IL10N|\PHPUnit_Framework_MockObject_MockObject */
	protected $l;

	protected function setUp() {
		parent::setUp();

		$this->l = $this->getMockBuilder('OCP\IL10N')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param array $methods
	 * @param string $random
	 * @return Collection|\PHPUnit_Framework_MockObject_MockObject
	 */
	public function getCollection(array $methods = [], $random = 'random') {
		if (empty($methods)) {
			return new Collection(
				$this->l,
				$random
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Parameter\Collection')
				->setConstructorArgs([
					$this->l,
					$random,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataGetParameter() {
		return [
			['random1'],
			['random2'],
		];
	}

	/**
	 * @dataProvider dataGetParameter
	 * @param string $random
	 */
	public function testGetParameter($random) {
		$collection = $this->getCollection([], $random);
		$this->assertSame($random, $collection->getParameter());
	}

	public function dataGetParameterInfo() {
		return [
			[
				[],
				[],
			],
			[
				[
					[
						'value' => 'value1',
						'type' => 'type1',
					],
					[
						'value' => 'value2',
						'type' => 'type2',
					],
				],
				[
					[
						'value' => 'value1',
						'type' => 'type1',
					],
					[
						'value' => 'value2',
						'type' => 'type2',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider dataGetParameterInfo
	 * @param array $parameters
	 * @param array $expected
	 */
	public function testGetParameterInfo(array $parameters, array $expected) {
		$instance = $this->getCollection();
		$setParams = [];
		foreach ($parameters as $parameter) {
			$param = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
				->disableOriginalConstructor()
				->getMock();
			$param->expects($this->once())
				->method('getParameterInfo')
				->willReturn($parameter);
			$setParams[] = $param;
		}
		$this->invokePrivate($instance, 'parameters', [$setParams]);

		$this->assertSame([
			'value' => $expected,
			'type' => 'collection',
		], $instance->getParameterInfo());
	}

	public function testAddParameter() {
		$collection = $this->getCollection();

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter1 */
		$parameter1 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter1->expects($this->atLeastOnce())
			->method('getParameterInfo')
			->willReturn('One');

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter2 */
		$parameter2 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter2->expects($this->atLeastOnce())
			->method('getParameterInfo')
			->willReturn('Two');

		$this->assertCount(0, $this->invokePrivate($collection, 'parameters'));
		$collection->addParameter($parameter1);
		$this->assertCount(1, $this->invokePrivate($collection, 'parameters'));
		$collection->addParameter($parameter1);
		$this->assertCount(1, $this->invokePrivate($collection, 'parameters'));
		$collection->addParameter($parameter2);
		$this->assertCount(2, $this->invokePrivate($collection, 'parameters'));
	}

	public function testFormat() {
		$collection = $this->getCollection();

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter1 */
		$parameter1 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter1->expects($this->once())
			->method('format')
			->willReturn('OneNull');

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter2 */
		$parameter2 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter2->expects($this->once())
			->method('format')
			->willReturn('TwoNull');

		$this->invokePrivate($collection, 'parameters', [
			[$parameter1, $parameter2]
		]);

		$this->assertSame('<collection>OneNullTwoNull</collection>', $collection->format());
	}
}
