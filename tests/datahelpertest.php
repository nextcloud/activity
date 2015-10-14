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
use OCA\Activity\Parameter\Factory;
use OCA\Activity\Tests\Mock\Extension;

class DataHelperTest extends TestCase {
	protected $originalWEBROOT;
	/** @var \OCP\Activity\IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $activityManager;
	/** @var \OCA\Activity\Parameter\Factory|\PHPUnit_Framework_MockObject_MockObject */
	protected $parameterFactory;
	/** @var \OCP\L10N\IFactory|\PHPUnit_Framework_MockObject_MockObject */
	protected $l10Nfactory;
	/** @var \OCP\IL10N|\PHPUnit_Framework_MockObject_MockObject */
	protected $l;

	protected function setUp() {
		parent::setUp();

		$this->originalWEBROOT = \OC::$WEBROOT;
		\OC::$WEBROOT = '';

		$this->activityManager = $this->getMockBuilder('OCP\Activity\IManager')
			->disableOriginalConstructor()
			->getMock();

		$this->parameterFactory = $this->getMockBuilder('OCA\Activity\Parameter\Factory')
			->disableOriginalConstructor()
			->getMock();

		$this->l10Nfactory = $this->getMockBuilder('OCP\L10N\IFactory')
			->disableOriginalConstructor()
			->getMock();

		$this->l = $this->getMockBuilder('OCP\IL10N')
			->disableOriginalConstructor()
			->getMock();
	}

	protected function tearDown() {
		\OC::$WEBROOT = $this->originalWEBROOT;
		parent::tearDown();
	}

	/**
	 * @param array $methods
	 * @return DataHelper|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getHelper(array $methods = []) {
		if (empty($methods)) {
			return new DataHelper(
				$this->activityManager,
				$this->parameterFactory,
				$this->l10Nfactory,
				$this->l
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\DataHelper')
				->setConstructorArgs([
					$this->activityManager,
					$this->parameterFactory,
					$this->l10Nfactory,
					$this->l,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataTranslation() {
		return [
			['app1', 'text1', [], true, true, false, 'lang'],
			['app2', 'text2', [], true, false, false, 'lang'],
			['app2', 'text2', [], false, true, false, 'lang'],
			['app2', 'text2', [], false, false, false, 'lang'],
			['app2', 'text2', [], false, false, 'manager', 'manager'],
		];
	}

	/**
	 * @dataProvider dataTranslation
	 *
	 * @param string $app
	 * @param string $text
	 * @param array $params
	 * @param bool $stripPath
	 * @param bool $highlightParams
	 * @param bool|string $managerReturn
	 * @param string $expected
	 */
	public function testTranslation($app, $text, array $params, $stripPath, $highlightParams, $managerReturn, $expected) {

		$this->activityManager->expects($this->once())
			->method('translate')
			->with($app, $text, $this->anything(), $stripPath, $highlightParams)
			->willReturn($managerReturn);
		if ($managerReturn === false) {
			$l = $this->getMockBuilder('OCP\IL10N')
				->disableOriginalConstructor()
				->getMock();
			$l->expects($this->once())
				->method('t')
				->with($text, $this->anything())
				->willReturn('lang');
			$this->l10Nfactory->expects($this->once())
				->method('get')
				->with($app, $this->anything())
				->willReturn($l);
		}

		$helper = $this->getHelper();
		$this->assertSame(
			$expected,
			(string) $helper->translation($app, $text, $params, $stripPath, $highlightParams)
		);
	}

	public function dataTranslationNoText() {
		return [
			[true, true],
			[true, false],
			[false, true],
			[false, false],
		];
	}

	/**
	 * @dataProvider dataTranslationNoText
	 *
	 * @param bool $stripPath
	 * @param bool $highlightParams
	 */
	public function testTranslationNoText($stripPath, $highlightParams) {
		$this->activityManager->expects($this->never())
			->method('translate');

		$helper = $this->getHelper();
		$this->assertSame('', $helper->translation('', '', [], $stripPath, $highlightParams));
	}

	public function dataGetSpecialParameterList() {
		return [
			['app1', 'text1', false, []],
			['app2', 'text2', [], []],
			['app3', 'text3', [0 => 'username'], [0 => 'username']],
		];
	}

	/**
	 * @dataProvider dataGetSpecialParameterList
	 *
	 * @param string $app
	 * @param string $text
	 * @param array|bool $managerReturn
	 * @param array $expected
	 */
	public function testGetSpecialParameterList($app, $text, $managerReturn, array $expected) {
		$this->activityManager->expects($this->once())
			->method('getSpecialParameterList')
			->with($app, $text)
			->willReturn($managerReturn);

		$instance = $this->getHelper();
		$this->assertSame($expected, $this->invokePrivate($instance, 'getSpecialParameterList', [$app, $text]));
	}

	public function dataFormatString() {
		return [
			[
				[
					'app' => 'app1',
					'subject' => 'subject1',
					'subjectparams_array' => [],
					'message' => 'message1',
					'messageparams_array' => [],
				],
				'subject',
				[
					'app' => 'app1',
					'subject' => 'subject1',
					'message' => 'message1',
					'messageparams_array' => [],
					'subjectparams' => [],
					'subjectformatted' => [
						'trimmed' => 'translation1',
						'full' => 'translation2',
						'markup' => [
							'trimmed' => 'translation3',
							'full' => 'translation4',
						],
					],
				],
			],
			[
				[
					'app' => 'app1',
					'subject' => 'subject1',
					'subjectparams_array' => [],
					'message' => 'message1',
					'messageparams_array' => [],
				],
				'message',
				[
					'app' => 'app1',
					'subject' => 'subject1',
					'subjectparams_array' => [],
					'message' => 'message1',
					'messageparams' => [],
					'messageformatted' => [
						'trimmed' => 'translation1',
						'full' => 'translation2',
						'markup' => [
							'trimmed' => 'translation3',
							'full' => 'translation4',
						],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider dataFormatString
	 *
	 * @param array $activity
	 * @param string $message
	 * @param array $expected
	 */
	public function testFormatString(array $activity, $message, array $expected) {
		$instance = $this->getHelper(['translation']);

		global $call;
		$call = 0;
		$instance->expects($this->exactly(4))
			->method('translation')
			->withConsecutive(
				[$activity['app'], $activity[$message], $activity[$message . 'params_array'], true, false],
				[$activity['app'], $activity[$message], $activity[$message . 'params_array'], false, false],
				[$activity['app'], $activity[$message], $activity[$message . 'params_array'], true, true],
				[$activity['app'], $activity[$message], $activity[$message . 'params_array'], false, true]
			)
			->willReturnCallback(function() {
				global $call;
				$call++;
				return 'translation' . $call;
			});

		$this->assertSame($expected, $instance->formatStrings($activity, $message));
	}

	public function dataGetParameters() {
		return [
			['subject', 'params1', [], [], [], []],
			[
				'subject',
				'params1',
				['one', 'two'],
				[],
				[['one', 'base'], ['two', 'base']],
				['param1', 'param2'],
			],
			[
				'subject',
				'params1',
				['one', 'two'],
				['user'],
				[['one', 'user'], ['two', 'base']],
				['param1', 'param2'],
			],
			[
				'message',
				'params1',
				['one', 'two'],
				[1 => 'user'],
				[['one', 'base'], ['two', 'user']],
				['param1', 'param2'],
			],
		];
	}

	/**
	 * @dataProvider dataGetParameters
	 *
	 * @param string $parsing
	 * @param string $parameterString
	 * @param array $parameters
	 * @param array $parameterTypes
	 * @param array $factoryCalls
	 * @param array $expected
	 */
	public function testGetParameters($parsing, $parameterString, array $parameters, array $parameterTypes, array $factoryCalls, array $expected) {
		/** @var \OCP\Activity\IEvent|\PHPUnit_Framework_MockObject_MockObject $event */
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();
		$event->expects($this->once())
			->method('getApp')
			->willReturn('app');

		if ($parsing === 'subject') {
			$event->expects($this->once())
				->method('getSubject')
				->willReturn('text');
			$event->expects($this->never())
				->method('getMessage');
		} else {
			$event->expects($this->never())
				->method('getSubject');
			$event->expects($this->once())
				->method('getMessage')
				->willReturn('text');
		}

		global $call;
		$call = 0;
		foreach ($factoryCalls as $i => $factoryCall) {
			$this->parameterFactory->expects($this->at($i))
				->method('get')
				->with($factoryCall[0], $event, $factoryCall[1])
				->willReturnCallback(function() {
					global $call;
					$call++;
					return 'param' . $call;
				});
		}

		$instance = $this->getHelper([
			'parseParameters',
			'getSpecialParameterList',
		]);

		$instance->expects($this->once())
			->method('parseParameters')
			->with($parameterString)
			->willReturn($parameters);
		$instance->expects($this->once())
			->method('getSpecialParameterList')
			->with('app', 'text')
			->willReturn($parameterTypes);

		$this->assertSame($expected, $instance->getParameters($event, $parsing, $parameterString));
	}

	public function testSetUser() {
		$helper = $this->getHelper();
		$this->parameterFactory->expects($this->once())
			->method('setUser')
			->with('foobar');

		$helper->setUser('foobar');
	}

	public function testSetL10n() {
		$l = \OC::$server->getL10NFactory()->get('activity', 'de');

		$helper = $this->getHelper();
		$this->parameterFactory->expects($this->once())
			->method('setL10n')
			->with($l);

		$helper->setL10n($l);
	}

	public function dataParseParameters() {
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
	 * @dataProvider dataParseParameters
	 * @param string $stringInput
	 * @param array $expected
	 */
	public function testParseParameters($stringInput, $expected) {
		$helper = $this->getHelper();
		$this->assertEquals($expected, $this->invokePrivate($helper, 'parseParameters', [$stringInput]));
	}

	public function testCreateCollection() {
		$helper = $this->getHelper();

		$collection = $this->getMockBuilder('OCA\Activity\Parameter\Collection')
			->disableOriginalConstructor()
			->getMock();

		$this->parameterFactory->expects($this->once())
			->method('createCollection')
			->willReturn($collection);

		$return = $this->invokePrivate($helper, 'createCollection');
		$this->assertEquals($collection, $return);
		$this->assertInstanceOf('OCA\Activity\Parameter\Collection', $return);
		$this->assertInstanceOf('OCA\Activity\Parameter\IParameter', $return);
	}
}
