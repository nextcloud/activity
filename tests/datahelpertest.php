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
use OCA\Activity\ParameterHelper;
use OCA\Activity\Tests\Mock\Extension;

class DataHelperTest extends TestCase {
	protected $originalWEBROOT;

	protected function setUp() {
		parent::setUp();

		$this->originalWEBROOT = \OC::$WEBROOT;
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
				'Subject1 #<a class="filename has-tooltip" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=A.txt" title="in SubFolder">A.txt</a>',
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
				. '<a class="filename has-tooltip" href="/index.php/apps/files?dir=%2FSubFolder&scrollto=A.txt" title="in SubFolder">A.txt</a>',
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
				. ' and <strong class="has-tooltip" title="SubFolder/D.txt, SubFolder/E.txt, SubFolder/F.txt">3 more</strong>',
			),
		);
	}

	/**
	 * @dataProvider translationData
	 */
	public function testTranslation($text, $params, $stripPath, $highlightParams, $expected) {
		$activityLanguage = \OCP\Util::getL10N('activity', 'en');
		$activityManager = new ActivityManager(
			$this->getMock('OCP\IRequest'),
			$this->getMock('OCP\IUserSession'),
			$this->getMock('OCP\IConfig')
		);

		$urlGenerator = $this->getMockBuilder('\OCP\IURLGenerator')
			->disableOriginalConstructor()
			->getMock();
		$urlGenerator->expects($this->any())
			->method('linkTo')
			->willReturnCallback(function($app, $file, $params) {
				$paramStrings = [];
				foreach ($params as $name => $value) {
					$paramStrings[] = $name . '=' . urlencode($value);
				}

				return '/index.php/apps/' . $app . '?' . implode('&', $paramStrings);
			});

		$activityManager->registerExtension(function() use ($activityLanguage, $urlGenerator) {
			return new Extension($activityLanguage, $urlGenerator);
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
				$this->getMockBuilder('OCP\IUserManager')->disableOriginalConstructor()->getMock(),
				$urlGenerator,
				$this->getMockBuilder('OCP\Contacts\IManager')->disableOriginalConstructor()->getMock(),
				$this->getMockBuilder('OC\Files\View')->disableOriginalConstructor()->getMock(),
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
		$activityManager = new ActivityManager(
			$this->getMock('OCP\IRequest'),
			$this->getMock('OCP\IUserSession'),
			$this->getMock('OCP\IConfig')
		);

		$dataHelper = new DataHelper(
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

	public function getParametersData() {
		return [
			[false, []],
			['a', ['a']],
			['"foo"bar"', ['"foo"bar"']],
			[serialize('a'), ['a']],
			[serialize(['a']), ['a']],
			[json_encode(['a']), ['a']],
		];
	}

	/**
	 * @dataProvider getParametersData
	 * @param string $stringInput
	 * @param array $expected
	 */
	public function testGetParameters($stringInput, $expected) {
		/** @var DataHelper $helper */
		list($helper,) = $this->setUpHelpers();
		$this->assertEquals($expected, $helper->getParameters($stringInput));
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
