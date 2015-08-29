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

use OC\ActivityManager;
use OCA\Activity\DataHelper;
use OCA\Activity\GroupHelper;
use OCA\Activity\ParameterHelper;
use OCA\Activity\Tests\Mock\Extension;

class GroupHelperTest extends TestCase {
	public function groupHelperData() {
		return array(
			array(
				true,
				array(),
				array(),
			),
			array(
				false,
				array(),
				array(),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
				),
				array(
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
				),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
				),
				array(
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
				),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
				array(
					array(
						'activity_id'	=> 2,
						'activity_ids'	=> array(2, 1),
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array(array(
							'testing/file2.txt',
							'testing/file1.txt',
						)),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file2.txt',
						'files'			=> [
							42 => 'testing/file2.txt',
							21 => 'testing/file1.txt',
						],
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
				),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
				),
				array(
					array(
						'activity_id'	=> 2,
						'activity_ids'	=> array(2, 1),
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array(array(
							'testing/file2.txt',
						)),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file2.txt',
						'files'			=> [
							42 => 'testing/file2.txt'
						],
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
				),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 3,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file3.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file3.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 1337,
					),
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'NOTtype1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time() - 10,
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
				array(
					array(
						'activity_id'	=> 3,
						'activity_ids'	=> array(3, 2),
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array(array(
							'testing/file3.txt',
							'testing/file2.txt',
						)),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file3.txt',
						'files'			=> [
							1337 => 'testing/file3.txt',
							42 => 'testing/file2.txt',
						],
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 1337,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'NOTtype1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> '',
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
			),

			// FIXME
			// Non-local users are currently not distinguishable, so grouping them might
			// remove the information how many different users performed the same action.
			// So we do not group them anymore, until we found another solution.
			array(
				true,
				array(
					array(
						'activity_id'	=> 3,
						'user'			=> '',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file3.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file3.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 1337,
					),
					array(
						'activity_id'	=> 2,
						'user'			=> '',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> '',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'NOTtype1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time() - 10,
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
				array(
					array(
						'activity_id'	=> 3,
						'user'			=> '',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file3.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file3.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 1337,
					),
					array(
						'activity_id'	=> 2,
						'user'			=> '',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file2.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file2.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> '',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'NOTtype1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> '',
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> json_encode(''),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time() - 1000000,
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file2.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file2.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(''),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
			),
			array(
				false,
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file2.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file2.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'NOTapp1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'NOTapp1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'NOTapp1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file2.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file2.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'NOTapp1',
						'type'			=> 'type1',
						'subject'		=> 'subject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
			),
			array(
				true,
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> json_encode(['testing/file2.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file2.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> json_encode(['testing/file1.txt']),
						'message'		=> '',
						'messageparams'	=> json_encode([]),
						'file'			=> 'testing/file1.txt',
						'timestamp'		=> time(),
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
				array(
					array(
						'activity_id'	=> 2,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> array('testing/file2.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file2.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 42,
					),
					array(
						'activity_id'	=> 1,
						'user'			=> 'user',
						'affecteduser'	=> 'affecteduser',
						'app'			=> 'app1',
						'type'			=> 'type1',
						'subject'		=> 'NOTsubject1',
						'subjectparams'	=> array('testing/file1.txt'),
						'message'		=> '',
						'messageparams'	=> array(),
						'file'			=> 'testing/file1.txt',
						'typeicon'		=> 'icon-type1',
						'object_type'	=> 'files',
						'object_id'		=> 21,
					),
				),
			),
		);
	}

	/**
	 * @dataProvider groupHelperData
	 */
	public function testGroupHelper($allowGrouping, $activities, $expected) {
		$activityLanguage = \OCP\Util::getL10N('activity', 'en');
		$activityManager = new ActivityManager(
			$this->getMock('OCP\IRequest'),
			$this->getMock('OCP\IUserSession'),
			$this->getMock('OCP\IConfig')
		);
		$activityManager->registerExtension(function() use ($activityLanguage) {
			return new Extension($activityLanguage, $this->getMock('\OCP\IURLGenerator'));
		});

		$helper = new GroupHelper(
			$activityManager,
			new DataHelper(
				$activityManager,
				new ParameterHelper(
					$activityManager,
					$this->getMockBuilder('OCP\IUserManager')->disableOriginalConstructor()->getMock(),
					$this->getMockBuilder('\OCP\IURLGenerator')->disableOriginalConstructor()->getMock(),
					$this->getMockBuilder('OCP\Contacts\IManager')->disableOriginalConstructor()->getMock(),
					$this->getMockBuilder('OC\Files\View')->disableOriginalConstructor()->getMock(),
					$this->getMockBuilder('OCP\IConfig')->disableOriginalConstructor()->getMock(),
					$activityLanguage,
					'test'
				),
				$activityLanguage
			),
			$allowGrouping
		);

		foreach ($activities as $activity) {
			$helper->addActivity($activity);
		}

		$result = $helper->getActivities();
		$clearedResult = array();
		foreach ($result as $activity) {
			unset($activity['subjectformatted']);
			unset($activity['messageformatted']);
			unset($activity['timestamp']);
			$clearedResult[] = $activity;
		}
		$this->assertEquals($expected, $clearedResult);
	}

	public function testSetUser() {
		/** @var GroupHelper $helper */
		/** @var \PHPUnit_Framework_MockObject_MockObject $dataHelperMock */
		list($helper, $dataHelperMock) = $this->setUpHelpers();

		$dataHelperMock->expects($this->once())
			->method('setUser')
			->with('foobar');

		$helper->setUser('foobar');
	}

	public function testSetL10n() {
		/** @var GroupHelper $helper */
		/** @var \PHPUnit_Framework_MockObject_MockObject $dataHelperMock */
		list($helper, $dataHelperMock) = $this->setUpHelpers();
		$l = \OCP\Util::getL10N('activity', 'de');

		$dataHelperMock->expects($this->once())
			->method('setL10n')
			->with($l);

		$helper->setL10n($l);
	}

	/**
	 * Sets up the GroupHelper with a mocked DataHelper
	 * @return array
	 */
	protected function setUpHelpers() {
		$dataHelperMock = $this->getMockBuilder('OCA\Activity\DataHelper')
			->disableOriginalConstructor()
			->getMock();

		$helper = new GroupHelper(
			$this->getMockBuilder('OCP\Activity\IManager')->disableOriginalConstructor()->getMock(),
			$dataHelperMock,
			true
		);

		return [$helper, $dataHelperMock];
	}
}
