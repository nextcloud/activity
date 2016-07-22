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
