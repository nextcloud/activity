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

	public function testAddParameter() {
		$collection = $this->getCollection();

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter1 */
		$parameter1 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter1->expects($this->atLeastOnce())
			->method('getParameter')
			->willReturn('One');

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter2 */
		$parameter2 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter2->expects($this->atLeastOnce())
			->method('getParameter')
			->willReturn('Two');

		$this->assertCount(0, $this->invokePrivate($collection, 'parameters'));
		$collection->addParameter($parameter1);
		$this->assertCount(1, $this->invokePrivate($collection, 'parameters'));
		$collection->addParameter($parameter1);
		$this->assertCount(1, $this->invokePrivate($collection, 'parameters'));
		$collection->addParameter($parameter2);
		$this->assertCount(2, $this->invokePrivate($collection, 'parameters'));
	}

	public function dataFormat() {
		return [
			[true, true, ['One', 'Two'], ['OneFalse', 'TwoFalse']],
			[true, false, ['One', 'Two'], ['OneFalse', 'TwoFalse']],
			[false, true, ['One', 'Two'], ['OneFalse', 'TwoFalse']],
			[false, false, ['OneFalse', 'TwoFalse'], ['OneFalse', 'TwoFalse']],
		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param bool $allowHtml
	 * @param bool $verbose
	 * @param array $parameterList
	 * @param array $plainParameterList
	 */
	public function testFormat($allowHtml, $verbose, array $parameterList, array $plainParameterList) {
		$collection = $this->getCollection(['joinParameterList']);

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter1 */
		$parameter1 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter1->expects($this->exactly(2))
			->method('format')
			->willReturnMap([
				[false, false, 'OneFalse'],
				[$allowHtml, $verbose, 'One'],
			]);

		/** @var \OCA\Activity\Parameter\IParameter|\PHPUnit_Framework_MockObject_MockObject $parameter2 */
		$parameter2 = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		$parameter2->expects($this->exactly(2))
			->method('format')
			->willReturnMap([
				[false, false, 'TwoFalse'],
				[$allowHtml, $verbose, 'Two'],
			]);

		$this->invokePrivate($collection, 'parameters', [
			[$parameter1, $parameter2]
		]);

		$collection->expects($this->once())
			->method('joinParameterList')
			->with($parameterList, $plainParameterList, $allowHtml)
			->willReturn('return(joinParameterList)');

		$this->assertSame('return(joinParameterList)', $collection->format($allowHtml, $verbose));
	}

	public function dataJoinParameterList() {
		return [
			[0, true, ''],
			[0, false, ''],

			[1, true, '<strong>item1</strong>'],
			[1, false, 'item1'],

			[2, true, '<strong>item1</strong> and <strong>item2</strong>'],
			[2, false, 'item1 and item2'],

			[3, true, '<strong>item1</strong>, <strong>item2</strong> and <strong>item3</strong>'],
			[3, false, 'item1, item2 and item3'],
			[
				6, true,
				'<strong>item1</strong>, <strong>item2</strong>, <strong>item3</strong> and <strong class="has-tooltip" title="item4, item5, item6">3 more</strong>',
			],
			[
				6, false,
				'item1, item2, item3 and 3 more',
			],
		];
	}

	/**
	 * @dataProvider dataJoinParameterList
	 *
	 * @param int $numParameters
	 * @param bool $allowHtml
	 * @param string $expected
	 */
	public function testJoinParameterList($numParameters, $allowHtml, $expected) {
		$parameterList = $plainParameterList = [];
		for ($i = 1; $i <= $numParameters; $i++) {
			if ($allowHtml) {
				$parameterList[] = '<strong>item' . $i . '</strong>';
			} else {
				$parameterList[] = 'item' . $i;
			}
			$plainParameterList[] = 'item' . $i;
		}

		$collection = $this->getCollection();

		$this->l->expects($this->any())
			->method('t')
			->willReturnCallback(function ($string, $parameters) {
				return vsprintf($string, $parameters);
			});
		$this->l->expects($this->any())
			->method('n')
			->willReturnCallback(function ($singular, $plural, $count, $parameters) {
				if ($count === 1) {
					$string = str_replace('%n', $count, $singular);
				} else {
					$string = str_replace('%n', $count, $plural);
				}
				return vsprintf($string, $parameters);
			});

		$this->assertSame($expected, $this->invokePrivate($collection, 'joinParameterList', [
			$parameterList, $plainParameterList, $allowHtml
		]));
	}
}
