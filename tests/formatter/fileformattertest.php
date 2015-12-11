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
			['user1', '/test1', false, [], true, true, '<a class="filename" href="files/index.php?dir=%2F&scrollto=test1">test1</a>'],
			['user1', '/test1', true, [], true, true, '<a class="filename" href="files/index.php?dir=%2Ftest1">test1</a>'],
			['user1', '/test1', false, [], false, true, 'test1'],
			['user1', '/test1', true, [], false, true, 'test1'],
			['user1', '/test1', false, [], true, false, '<a class="filename" href="files/index.php?dir=%2F&scrollto=test1">test1</a>'],
			['user1', '/test1', true, [], true, false, '<a class="filename" href="files/index.php?dir=%2Ftest1">test1</a>'],
			['user1', '/test1', false, [], false, false, 'test1'],
			['user1', '/test1', true, [], false, false, 'test1'],
			['user1', '/test1/test2', false, [], true, true, '<a class="filename" href="files/index.php?dir=%2Ftest1&scrollto=test2">test1/test2</a>'],
			['user1', '/test1/test2', true, [], true, true, '<a class="filename" href="files/index.php?dir=%2Ftest1%2Ftest2">test1/test2</a>'],
			['user1', '/test1/test2', false, [], false, true, 'test1/test2'],
			['user1', '/test1/test2', true, [], false, true, 'test1/test2'],
			['user1', '/test1/test2', false, [], true, false, '<a class="filename has-tooltip" href="files/index.php?dir=%2Ftest1&scrollto=test2" title="in test1">test2</a>'],
			['user1', '/test1/test2', true, [], true, false, '<a class="filename has-tooltip" href="files/index.php?dir=%2Ftest1%2Ftest2" title="in test1">test2</a>'],
			['user1', '/test1/test2', false, [], false, false, 'test2'],
			['user1', '/test1/test2', true, [], false, false, 'test2'],

			['user1', '/test1/test2', false, $trash0, true, true, '<a class="filename" href="files/index.php?dir=%2F&scrollto=test2&view=trashbin">test1/test2</a>'],
			['user1', '/test1/test2', true, $trash1, true, true, '<a class="filename" href="files/index.php?dir=%2Ftest2&view=trashbin">test1/test2</a>'],
			['user1', '/test1/test2', false, $trash0, false, true, 'test1/test2'],
			['user1', '/test1/test2', true, $trash1, false, true, 'test1/test2'],
			['user1', '/test1/test2', false, $trash0, true, false, '<a class="filename has-tooltip" href="files/index.php?dir=%2F&scrollto=test2&view=trashbin" title="in test1">test2</a>'],
			['user1', '/test1/test2', true, $trash1, true, false, '<a class="filename has-tooltip" href="files/index.php?dir=%2Ftest2&view=trashbin" title="in test1">test2</a>'],
			['user1', '/test1/test2', false, $trash0, false, false, 'test2'],
			['user1', '/test1/test2', true, $trash1, false, false, 'test2'],

			['user2', '/test1', false, [], true, true, '<a class="filename" href="files/index.php?dir=%2F&scrollto=test1">test1</a>'],
		];
	}

	/**
	 * @dataProvider dataFormat
	 *
	 * @param string $user
	 * @param string $parameter
	 * @param bool $isDir
	 * @param array $info
	 * @param bool $allowHtml
	 * @param bool $verbose
	 * @param string $expected
	 */
	public function testFormat($user, $parameter, $isDir, array $info, $allowHtml, $verbose, $expected) {
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
			->method('linkTo')
			->with('files', 'index.php', $this->anything())
			->willReturnCallback(function($app, $file, $parameters) {
				$paramList = [];
				foreach ($parameters as $key => $value) {
					$paramList[] = $key . '=' . urlencode($value);
				}
				return $app . '/' . $file . '?' . implode('&', $paramList);
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

		$this->assertSame($expected, $formatter->format($event, $parameter, $allowHtml, $verbose));
	}

	public function dataFixLegacyFilename() {
		return [
			['test1', '/test1'],
			['/test1', '/test1'],
			['test1/test2', '/test1/test2'],
			['/test1/test2', '/test1/test2'],
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
