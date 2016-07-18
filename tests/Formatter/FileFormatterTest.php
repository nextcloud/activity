<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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

use OCA\Activity\Formatter\FileFormatter;
use OCA\Activity\Formatter\IFormatter;
use OCA\Activity\Tests\TestCase;

class FileFormatterTest extends TestCase {
	/** @var \OCP\IURLGenerator|\PHPUnit_Framework_MockObject_MockObject */
	protected $urlGenerator;

	/** @var \OCA\Activity\ViewInfoCache|\PHPUnit_Framework_MockObject_MockObject */
	protected $infoCache;

	/** @var \OCP\IL10N|\PHPUnit_Framework_MockObject_MockObject */
	protected $l;

	protected function setUp() {
		parent::setUp();

		$this->urlGenerator = $this->getMockBuilder('OCP\IURLGenerator')
			->disableOriginalConstructor()
			->getMock();

		$this->infoCache = $this->getMockBuilder('OCA\Activity\ViewInfoCache')
			->disableOriginalConstructor()
			->getMock();

		$this->l = $this->getMockBuilder('OCP\IL10N')
			->disableOriginalConstructor()
			->getMock();
		$this->l->expects($this->any())
			->method('t')
			->willReturnCallback(function ($string, $parameters) {
				return vsprintf($string, $parameters);
			});
	}

	/**
	 * @param array $methods
	 * @param string $user
	 * @return IFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	public function getFormatter(array $methods = [], $user = 'user') {
		if (empty($methods)) {
			return new FileFormatter(
				$this->infoCache,
				$this->urlGenerator,
				$this->l,
				$user
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Formatter\FileFormatter')
				->setConstructorArgs([
					$this->infoCache,
					$this->urlGenerator,
					$this->l,
					$user,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataFormat() {
		$trash0 = [
			'path'		=> '/test2',
			'is_dir'	=> false,
			'exists'	=> true,
			'view'		=> 'trashbin',
		];
		$trash1 = [
			'path'		=> '/test2',
			'is_dir'	=> true,
			'exists'	=> true,
			'view'		=> 'trashbin',
		];

		return [
			['user1', '/test1', false, [], '<file link="apps/files/?dir=%2F&scrollto=test1" id="">test1</file>'],
			['user1', '/test1', true, [], '<file link="apps/files/?dir=%2Ftest1" id="">test1</file>'],
			['user1', '/', true, [], '<file link="apps/files/?dir=%2F" id="">/</file>'],
			['user1', '/test1/test2', false, [], '<file link="apps/files/?dir=%2Ftest1&scrollto=test2" id="">test1/test2</file>'],
			['user1', '/test1/test2', true, [], '<file link="apps/files/?dir=%2Ftest1%2Ftest2" id="">test1/test2</file>'],

			['user1', '/test1/test2', false, $trash0, '<file link="apps/files/?dir=%2F&scrollto=test2&view=trashbin" id="42">test1/test2</file>'],
			['user1', '/test1/test2', true, $trash1, '<file link="apps/files/?dir=%2Ftest2&view=trashbin" id="42">test1/test2</file>'],

			['user2', '/test1', false, [], '<file link="apps/files/?dir=%2F&scrollto=test1" id="">test1</file>'],
		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param string $user
	 * @param string $parameter
	 * @param bool $isDir
	 * @param array $info
	 * @param string $expected
	 */
	public function testFormat($user, $parameter, $isDir, array $info, $expected) {
		/** @var \OCP\Activity\IEvent|\PHPUnit_Framework_MockObject_MockObject $event */
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();
		if (!empty($info)) {
			$event->expects($this->once())
				->method('getObjectType')
				->willReturn('files');
			$event->expects($this->once())
				->method('getObjectName')
				->willReturn($parameter);
			$event->expects($this->once())
				->method('getObjectId')
				->willReturn(42);
		}

		$this->urlGenerator->expects($this->once())
			->method('linkToRouteAbsolute')
			->with('files.view.index', $this->anything())
			->willReturnCallback(function($route, $parameters) {
				$paramList = [];
				foreach ($parameters as $key => $value) {
					$paramList[] = $key . '=' . urlencode($value);
				}
				return 'apps/files/' . '?' . implode('&', $paramList);
			});

		$formatter = $this->getFormatter([
			'fixLegacyFilename',
		], $user);
		$formatter->expects($this->once())
			->method('fixLegacyFilename')
			->willReturnArgument(0);
		if (!empty($info)) {
			$this->infoCache->expects($this->once())
				->method('getInfoById')
				->with($user, 42, $parameter)
				->willReturn($info);
		} else {
			$this->infoCache->expects($this->once())
				->method('getInfoByPath')
				->with($user, $parameter)
				->willReturn([
					'path'		=> $parameter,
					'is_dir'	=> $isDir,
					'exists'	=> true,
					'view'		=> '',
				]);
		}

		$this->assertSame($expected, $formatter->format($event, $parameter));
	}

	public function dataFixLegacyFilename() {
		return [
			['test1', '/test1'],
			['/test1', '/test1'],
			['test1/test2', '/test1/test2'],
			['/test1/test2', '/test1/test2'],
			[[42 => 'fourtytwo'], [42 => 'fourtytwo']],
			[[23 => '/twentythree'], [23 => '/twentythree']],
		];
	}

	/**
	 * @dataProvider dataFixLegacyFilename
	 *
	 * @param string $filename
	 * @param string $expected
	 */
	public function testFixLegacyFilename($filename, $expected) {
		$formatter = $this->getFormatter();
		$this->assertSame($expected, $this->invokePrivate($formatter, 'fixLegacyFilename', [$filename]));
	}

	public function dataSplitPathFromFilename() {
		return [
			['test1', ['', 'test1']],
			['/test1', ['', 'test1']],
			['test1/test2', ['test1', 'test2']],
			['/test1/test2', ['test1', 'test2']],
			['test1/test2/test3', ['test1/test2', 'test3']],
			['/test1/test2/test3', ['test1/test2', 'test3']],
		];
	}

	/**
	 * @dataProvider dataSplitPathFromFilename
	 *
	 * @param string $filename
	 * @param string $expected
	 */
	public function testSplitPathFromFilename($filename, $expected) {
		$formatter = $this->getFormatter();
		$this->assertSame($expected, $this->invokePrivate($formatter, 'splitPathFromFilename', [$filename]));
	}
}
