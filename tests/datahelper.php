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
			array(array('/foo/bar.file'), 0, true, true, array('<strong class="tooltip" title="foo/bar.file">bar.file</strong>')),
			array(array('/foo/bar.file'), 1, true, false, array('/foo/bar.file')),
			array(array('/foo/bar.file'), 1, true, true, array('<strong>/foo/bar.file</strong>')),

			// Valid file position
			array(array('UserA', '/foo/bar.file'), 0, true, false, array('UserA', '/foo/bar.file')),
			array(array('UserA', '/foo/bar.file'), 1, true, false, array('UserA', 'bar.file')),
			array(array('UserA', '/foo/bar.file'), 1, true, true, array('<strong>UserA</strong>', '<strong class="tooltip" title="foo/bar.file">bar.file</strong>')),
			array(array('UserA', '/foo/bar.file'), 2, true, true, array('<strong>UserA</strong>', '<strong>/foo/bar.file</strong>')),
		);
	}

	/**
	 * @dataProvider prepareFilesParamsData
	 */
	public function testPrepareFilesParams($params, $filePosition, $stripPath, $highlightParams, $expected) {
		$l = \OC_L10N::get('activity');
		$this->assertEquals(
			$expected,
			\OCA\Activity\DataHelper::prepareFilesParams($l, 'files', 'action', $params, $filePosition, $stripPath, $highlightParams)
		);
	}

	public function translationData() {
		return array(
			array('created_self', array('/SubFolder/A.txt'), false, false, 'You created SubFolder/A.txt'),
			array('created_self', array('/SubFolder/A.txt'), true, false, 'You created A.txt'),
			array('created_self', array('/SubFolder/A.txt'), false, true, 'You created <strong>SubFolder/A.txt</strong>'),
			array('created_self', array('/SubFolder/A.txt'), true, true, 'You created <strong class="tooltip" title="SubFolder/A.txt">A.txt</strong>'),

			array('created_by', array('/SubFolder/A.txt', 'UserB'), false, false, 'UserB created SubFolder/A.txt'),
			array('created_by', array('/SubFolder/A.txt', 'UserB'), true, false, 'UserB created A.txt'),
			array('created_by', array('/SubFolder/A.txt', 'UserB'), false, true, '<strong>UserB</strong> created <strong>SubFolder/A.txt</strong>'),
			array('created_by', array('/SubFolder/A.txt', 'UserB'), true, true, '<strong>UserB</strong> created <strong class="tooltip" title="SubFolder/A.txt">A.txt</strong>'),

			array(
				'created_self',
				array(array('/SubFolder/A.txt')),
				false,
				false,
				'You created SubFolder/A.txt',
			),
			array(
				'created_self',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt')),
				false,
				false,
				'You created SubFolder/A.txt and SubFolder/B.txt',
			),
			array(
				'created_self',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt', '/SubFolder/C.txt', '/SubFolder/D.txt', '/SubFolder/E.txt')),
				false,
				false,
				'You created SubFolder/A.txt, SubFolder/B.txt, SubFolder/C.txt, SubFolder/D.txt and SubFolder/E.txt',
			),
			array(
				'created_self',
				array(array('SubFolder/A.txt', 'SubFolder/B.txt', 'SubFolder/C.txt', 'SubFolder/D.txt', 'SubFolder/E.txt', '/SubFolder/F.txt')),
				false,
				false,
				'You created SubFolder/A.txt, SubFolder/B.txt, SubFolder/C.txt and 3 more',
			),
			array(
				'created_self',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt', '/SubFolder/C.txt', '/SubFolder/D.txt', '/SubFolder/E.txt', '/SubFolder/F.txt')),
				true,
				false,
				'You created A.txt, B.txt, C.txt and 3 more',
			),
			array(
				'created_self',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt', '/SubFolder/C.txt', '/SubFolder/D.txt', '/SubFolder/E.txt', '/SubFolder/F.txt')),
				false,
				true,
				'You created <strong>SubFolder/A.txt</strong>, <strong>SubFolder/B.txt</strong>, <strong>SubFolder/C.txt</strong> and <strong class="tooltip" title="SubFolder/D.txt, SubFolder/E.txt, SubFolder/F.txt">3 more</strong>',
			),
		);
	}

	/**
	 * @dataProvider translationData
	 */
	public function testTranslation($text, $params, $stripPath, $highlightParams, $expected) {
		$this->assertEquals(
			$expected,
			(string) \OCA\Activity\DataHelper::translation('files', $text, $params, $stripPath, $highlightParams)
		);
	}
}
