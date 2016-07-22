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
use OCA\Activity\Parameter\Parameter;
use OCA\Activity\Tests\TestCase;

class ParameterTest extends TestCase {
	/** @var \OCP\Activity\IEvent|\PHPUnit_Framework_MockObject_MockObject */
	protected $event;
	/** @var \OCA\Activity\Formatter\IFormatter|\PHPUnit_Framework_MockObject_MockObject */
	protected $formatter;

	protected function setUp() {
		parent::setUp();

		$this->event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();
		$this->formatter = $this->getMockBuilder('OCA\Activity\Formatter\IFormatter')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param string $parameter
	 * @param string $type
	 * @return Parameter
	 */
	public function getParameter($parameter = 'parameter', $type = 'type') {
		return new Parameter(
			$parameter,
			$this->event,
			$this->formatter,
			$type
		);
	}

	public function dataGetParameter() {
		return [
			['parameter1', '', 0, 'parameter1'],
			['parameter2', '', 0, 'parameter2'],
			['parameter1', 'files', 23, 'files#23'],
			['parameter1', 'item', 42, 'item#42'],
		];
	}

	/**
	 * @dataProvider dataGetParameter
	 * @param string $parameter
	 * @param string $objectType
	 * @param int $objectId
	 * @param string $expected
	 */
	public function testGetParameter($parameter, $objectType, $objectId, $expected) {
		$instance = $this->getParameter($parameter);

		$this->event->expects($this->atLeastOnce())
			->method('getObjectType')
			->willReturn($objectType);
		$this->event->expects($this->any())
			->method('getObjectId')
			->willReturn($objectId);

		$this->assertSame($expected, $instance->getParameter());
	}

	public function dataGetParameterInfo() {
		return [
			['parameter1', 'files'],
			['parameter2', 'item'],
		];
	}

	/**
	 * @dataProvider dataGetParameterInfo
	 * @param string $parameter
	 * @param string $type
	 */
	public function testGetParameterInfo($parameter, $type) {
		$instance = $this->getParameter($parameter, $type);

		$this->assertSame([
			'value' => $parameter,
			'type' => $type,
		], $instance->getParameterInfo());
	}

	public function dataFormat() {
		return [
			['parameter1'],
			['parameter2'],
		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param string $parameter
	 */
	public function testFormat($parameter) {
		$instance = $this->getParameter($parameter);

		$this->formatter->expects($this->once())
			->method('format')
			->with($this->event, $parameter)
			->willReturn('formatted()');

		$this->assertSame('formatted()', $instance->format());
	}
}
