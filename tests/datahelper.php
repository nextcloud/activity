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

namespace OCA\Activity\Tests;

class DataHelper extends \PHPUnit_Framework_TestCase {
	public function prepareFilesParamsData() {
		return array(
			array(array(), false, false, false, array()),

			// No file position: no path strip
			array(array('/foo/bar.file'), false, false, false, array('/foo/bar.file')),
			array(array('/foo/bar.file'), false, true, false, array('/foo/bar.file')),
			array(array('/foo/bar.file'), false, false, true, array('<strong>/foo/bar.file</strong>')),
			array(array('/foo/bar.file'), false, true, true, array('<strong>/foo/bar.file</strong>')),

			// Valid file position
			array(array('/foo/bar.file'), 0, true, false, array('bar.file')),
			array(array('/foo/bar.file'), 0, true, true, array('<strong>bar.file</strong>')),
			array(array('/foo/bar.file'), 1, true, false, array('/foo/bar.file')),
			array(array('/foo/bar.file'), 1, true, true, array('<strong>/foo/bar.file</strong>')),

			// Valid file position
			array(array('UserA', '/foo/bar.file'), 0, true, false, array('UserA', '/foo/bar.file')),
			array(array('UserA', '/foo/bar.file'), 0, true, true, array('<strong>UserA</strong>', '<strong>/foo/bar.file</strong>')),
			array(array('UserA', '/foo/bar.file'), 1, true, false, array('UserA', 'bar.file')),
			array(array('UserA', '/foo/bar.file'), 1, true, true, array('<strong>UserA</strong>', '<strong>bar.file</strong>')),
		);
	}

	/**
	 * @dataProvider prepareFilesParamsData
	 */
	public function testPrepareFilesParams($params, $filePosition, $stripPath, $highlightParams, $expected) {
		$this->assertEquals(
			$expected,
			\OCA\Activity\DataHelper::prepareFilesParams('files', 'action', $params, $filePosition, $stripPath, $highlightParams)
		);
	}
}
