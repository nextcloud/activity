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
use OC\Files\View;
use OCA\Activity\DataHelper;
use OCA\Activity\ParameterHelper;
use OCA\Activity\Tests\Mock\Extension;

class DataHelperTest extends TestCase {
	protected $originalWEBROOT;

	protected function setUp() {
		parent::setUp();

		$this->originalWEBROOT =\OC::$WEBROOT;
		\OC::$WEBROOT = '';
	}

	protected function tearDown() {
		\OC::$WEBROOT = $this->originalWEBROOT;
		parent::tearDown();
	}

	public function translationData() {
		return array(
			array(
				'subject1', array('/SubFolder/A.txt'), false, false,
				'Subject1 #SubFolder/A.txt',
			),
			array(
				'subject1', array('/SubFolder/A.txt'), true, false,
				'Subject1 #A.txt',
			),
			array(
				'subject1', array('/SubFolder/A.txt'), false, true,
				'Subject1 #<a class="filename" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=A.txt">SubFolder/A.txt</a>',
			),
			array(
				'subject1', array('/SubFolder/A.txt'), true, true,
				'Subject1 #<a class="filename tooltip" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=A.txt" title="in SubFolder">A.txt</a>',
			),

			array('subject2', array('/SubFolder/A.txt', 'UserB'), false, false, 'Subject2 @UserB #SubFolder/A.txt'),
			array('subject2', array('/SubFolder/A.txt', 'UserB'), true, false, 'Subject2 @UserB #A.txt'),
			array(
				'subject2', array('/SubFolder/A.txt', 'UserB'), false, true,
				'Subject2 @<div class="avatar" data-user="UserB"></div><strong>UserB</strong> #'
				. '<a class="filename" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=A.txt">SubFolder/A.txt</a>',
			),
			array(
				'subject2', array('/SubFolder/A.txt', 'UserB'), true, true,
				'Subject2 @<div class="avatar" data-user="UserB"></div><strong>UserB</strong> #'
				. '<a class="filename tooltip" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=A.txt" title="in SubFolder">A.txt</a>',
			),
			array(
				'subject2', array('/A.txt', 'UserB'), true, true,
				'Subject2 @<div class="avatar" data-user="UserB"></div><strong>UserB</strong> #'
				. '<a class="filename" href="/index.php/apps/files?dir=%2F&scrollto=A.txt">A.txt</a>',
			),

			array(
				'subject1',
				array(array('/SubFolder/A.txt')),
				false,
				false,
				'Subject1 #SubFolder/A.txt',
			),
			array(
				'subject1',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt')),
				false,
				false,
				'Subject1 #SubFolder/A.txt and SubFolder/B.txt',
			),
			array(
				'subject1',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt', '/SubFolder/C.txt', '/SubFolder/D.txt', '/SubFolder/E.txt')),
				false,
				false,
				'Subject1 #SubFolder/A.txt, SubFolder/B.txt, SubFolder/C.txt, SubFolder/D.txt and SubFolder/E.txt',
			),
			array(
				'subject1',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt', '/SubFolder/C.txt', '/SubFolder/D.txt', '/SubFolder/E.txt', '/SubFolder/F.txt')),
				false,
				false,
				'Subject1 #SubFolder/A.txt, SubFolder/B.txt, SubFolder/C.txt and 3 more',
			),
			array(
				'subject1',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt', '/SubFolder/C.txt', '/SubFolder/D.txt', '/SubFolder/E.txt', '/SubFolder/F.txt')),
				true,
				false,
				'Subject1 #A.txt, B.txt, C.txt and 3 more',
			),
			array(
				'subject1',
				array(array('/SubFolder/A.txt', '/SubFolder/B.txt', '/SubFolder/C.txt', '/SubFolder/D.txt', '/SubFolder/E.txt', '/SubFolder/F.txt')),
				false,
				true,
				'Subject1 #<a class="filename" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=A.txt">SubFolder/A.txt</a>,'
				. ' <a class="filename" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=B.txt">SubFolder/B.txt</a>,'
				. ' <a class="filename" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=C.txt">SubFolder/C.txt</a>'
				. ' and <strong class="tooltip" title="SubFolder/D.txt, SubFolder/E.txt, SubFolder/F.txt">3 more</strong>',
			),
		);
	}

	/**
	 * @dataProvider translationData
	 */
	public function testTranslation($text, $params, $stripPath, $highlightParams, $expected) {
		$activityLanguage = \OCP\Util::getL10N('activity', 'en');
		$activityManager = new ActivityManager();
		$activityManager->registerExtension(function() use ($activityLanguage) {
			return new Extension($activityLanguage, $this->getMock('\OCP\IURLGenerator'));
		});
		$config = $this->getMockBuilder('OCP\IConfig')->disableOriginalConstructor()->getMock();
		$config->expects($this->any())
			->method('getSystemValue')
			->with('enable_avatars', true)
			->willReturn(true);

		$dataHelper = new DataHelper(
			$activityManager,
			new ParameterHelper(
				$activityManager,
				new View(''),
				$config,
				$activityLanguage,
				'test'
			),
			$activityLanguage
		);

		$this->assertEquals(
			$expected,
			(string) $dataHelper->translation('app1', $text, $params, $stripPath, $highlightParams)
		);
	}

	public function translationNotActivityAppData() {
		return array(
			array(
				'You created %1$s', array('/SubFolder/A.txt'), false, false,
				'You created /SubFolder/A.txt',
			),
			array(
				'You created %1$s', array('/SubFolder/A.txt'), true, false,
				'You created /SubFolder/A.txt',
			),
			array(
				'You created %1$s', array('/SubFolder/A.txt'), false, true,
				'You created <strong>/SubFolder/A.txt</strong>',
			),
			array(
				'You created %1$s', array('/SubFolder/A.txt'), true, true,
				'You created <strong>/SubFolder/A.txt</strong>',
			),
		);
	}


	/**
	 * @dataProvider translationNotActivityAppData
	 */
	public function testTranslationNotActivityApp($text, $params, $stripPath, $highlightParams, $expected) {
		$activityLanguage = \OCP\Util::getL10N('activity', 'en');
		$activityManager = new ActivityManager();

		$dataHelper = new DataHelper(
			$activityManager,
			new ParameterHelper(
				$activityManager,
				new View(''),
				$this->getMockBuilder('OCP\IConfig')->disableOriginalConstructor()->getMock(),
				$activityLanguage,
				'test'
			),
			$activityLanguage
		);

		$this->assertEquals(
			$expected,
			(string) $dataHelper->translation('activity', $text, $params, $stripPath, $highlightParams)
		);
	}

	public function testSetUser() {
		/** @var DataHelper $helper */
		/** @var \PHPUnit_Framework_MockObject_MockObject $parameterHelperMock */
		list($helper, $parameterHelperMock) = $this->setUpHelpers();

		$parameterHelperMock->expects($this->once())
			->method('setUser')
			->with('foobar');

		$helper->setUser('foobar');
	}

	public function testSetL10n() {
		/** @var DataHelper $helper */
		/** @var \PHPUnit_Framework_MockObject_MockObject $parameterHelperMock */
		list($helper, $parameterHelperMock) = $this->setUpHelpers();
		$l = \OCP\Util::getL10N('activity', 'de');

		$parameterHelperMock->expects($this->once())
			->method('setL10n')
			->with($l);

		$helper->setL10n($l);
	}

	/**
	 * Sets up the DataHelper with a mocked ParameterHelper
	 * @return array
	 */
	protected function setUpHelpers() {
		$parameterHelperMock = $this->getMockBuilder('OCA\Activity\ParameterHelper')
			->disableOriginalConstructor()
			->getMock();
		$l = \OCP\Util::getL10N('activity', 'en');

		$helper = new DataHelper(
			$this->getMockBuilder('OCP\Activity\IManager')->disableOriginalConstructor()->getMock(),
			$parameterHelperMock,
			$l
		);

		return [$helper, $parameterHelperMock];
	}
}
