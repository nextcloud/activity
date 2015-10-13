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
	 * @return Parameter
	 */
	public function getParameter($parameter = 'parameter') {
		return new Parameter(
			$parameter,
			$this->event,
			$this->formatter
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

	public function dataFormat() {
		return [
			['parameter1', true, true],
			['parameter2', true, false],
			['parameter3', false, true],
			['parameter4', false, false],
		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param string $parameter
	 * @param bool $allowHtml
	 * @param bool $verbose
	 */
	public function testFormat($parameter, $allowHtml, $verbose) {
		$instance = $this->getParameter($parameter);

		$this->formatter->expects($this->once())
			->method('format')
			->with($this->event, $parameter, $allowHtml, $verbose)
			->willReturn('formatted()');

		$this->assertSame('formatted()', $instance->format($allowHtml, $verbose));
	}
}
