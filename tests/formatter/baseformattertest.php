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
use OCA\Activity\Formatter\BaseFormatter;
use OCA\Activity\Tests\TestCase;

class BaseFormatterTest extends TestCase {

	/**
	 * @param array $methods
	 * @return IFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	public function getFormatter(array $methods = []) {
		if (empty($methods)) {
			return new BaseFormatter();
		} else {
			return $this->getMockBuilder('OCA\Activity\Formatter\BaseFormatter')
				->setConstructorArgs([
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataFormat() {
		return [
			['para<m>eter1', '<parameter>para&lt;m&gt;eter1</parameter>'],
			['para<m>eter2', '<parameter>para&lt;m&gt;eter2</parameter>'],
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

		$formatter = $this->getFormatter();
		$this->assertSame($expected, $formatter->format($event, $parameter));
	}
}
