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
use OCA\Activity\ViewInfoCache;
use OCP\Files\NotFoundException;

class ViewInfoCacheTest extends TestCase {
	/** @var \OC\Files\View|\PHPUnit_Framework_MockObject_MockObject */
	protected $view;

	/** @var \OCA\Activity\ViewInfoCache|\PHPUnit_Framework_MockObject_MockObject */
	protected $infoCache;

	protected function setUp() {
		parent::setUp();

		$this->view = $this->getMockBuilder('OC\Files\View')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param array $methods
	 * @return ViewInfoCache|\PHPUnit_Framework_MockObject_MockObject
	 */
	public function getCache(array $methods = []) {
		if (empty($methods)) {
			return new ViewInfoCache(
				$this->view
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\ViewInfoCache')
				->setConstructorArgs([
					$this->view,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataGetInfoByPath() {
		return [
			[
				'user', 'path', [], true, 'findInfoByPath',
			],
			[
				'user',
				'path',
				[
					'user' => [
						'different/path' => 'returnCache',
					]
				],
				true,
				'findInfoByPath',
			],
			[
				'user',
				'path',
				[
					'different-user' => [
						'path' => 'returnCache',
					]
				],
				true,
				'findInfoByPath',
			],
			[
				'user',
				'path',
				[
					'user' => [
						'path' => 'returnCache',
					]
				],
				false,
				'returnCache',
			],
		];
	}

	/**
	 * @dataProvider dataGetInfoByPath
	 *
	 * @param string $user
	 * @param string $path
	 * @param array $cache
	 * @param bool $callsFind
	 * @param string $expected
	 */
	public function testGetInfoByPath($user, $path, $cache, $callsFind, $expected) {
		$infoCache = $this->getCache([
			'findInfoByPath',
		]);

		if ($callsFind) {
			$infoCache->expects($this->once())
				->method('findInfoByPath')
				->willReturn('findInfoByPath');
		} else {
			$infoCache->expects($this->never())
				->method('findInfoByPath');
		}
		$this->invokePrivate($infoCache, 'cachePath', [$cache]);

		$this->assertSame($expected, $infoCache->getInfoByPath($user, $path));
	}

	public function dataGetInfoById() {
		return [
			[
				'user', 23, 'path', [], true, 'findInfoById',
			],
			[
				'user',
				23,
				'path',
				[
					'user' => [
						42 => 'returnCache',
					]
				],
				true,
				'findInfoById',
			],
			[
				'user',
				23,
				'path',
				[
					'different-user' => [
						23 => 'returnCache',
					]
				],
				true,
				'findInfoById',
			],
			[
				'user',
				23,
				'path',
				[
					'user' => [
						23 => ['path' => 'returnCache'],
					]
				],
				false,
				['path' => 'returnCache'],
			],
			[
				'user',
				23,
				'path',
				[
					'user' => [
						23 => ['path' => null],
					]
				],
				false,
				['path' => 'path'],
			],
		];
	}

	/**
	 * @dataProvider dataGetInfoById
	 *
	 * @param string $user
	 * @param int $id
	 * @param string $path
	 * @param array $cache
	 * @param bool $callsFind
	 * @param string $expected
	 */
	public function testGetInfoById($user, $id, $path, $cache, $callsFind, $expected) {
		$infoCache = $this->getCache([
			'findInfoById',
		]);

		if ($callsFind) {
			$infoCache->expects($this->once())
				->method('findInfoById')
				->willReturn('findInfoById');
		} else {
			$infoCache->expects($this->never())
				->method('findInfoById');
		}
		$this->invokePrivate($infoCache, 'cacheId', [$cache]);

		$this->assertSame($expected, $infoCache->getInfoById($user, $id, $path));
	}

	public function dataFindByPath() {
		return [
			['user1', 'path1', true, true, [
				'path'		=> 'path1',
				'exists'	=> true,
				'is_dir'	=> true,
				'view'		=> '',
			]],
			['user2', 'path2', true, false, [
				'path'		=> 'path2',
				'exists'	=> true,
				'is_dir'	=> false,
				'view'		=> '',
			]],
			['user3', 'path3', false, null, [
				'path'		=> 'path3',
				'exists'	=> false,
				'is_dir'	=> false,
				'view'		=> '',
			]],
		];
	}

	/**
	 * @dataProvider dataFindByPath
	 *
	 * @param string $user
	 * @param string $path
	 * @param bool $exists
	 * @param bool $is_dir
	 * @param array $expected
	 */
	public function testFindByPath($user, $path, $exists, $is_dir, $expected) {
		$this->view->expects($this->once())
			->method('chroot')
			->with('/' . $user . '/files');
		$this->view->expects($this->once())
			->method('file_exists')
			->with($path)
			->willReturn($exists);
		$this->view->expects($is_dir !== null ? $this->once() : $this->never())
			->method('is_dir')
			->with($path)
			->willReturn($is_dir);

		$infoCache = $this->getCache();

		$this->assertSame($expected, $this->invokePrivate($infoCache, 'findInfoByPath', [$user, $path]));
	}

	public function dataFindInfoById() {
		return [
			[
				'user1', 23, '/test1', null, null, '/test1', false,
				[
					'path'		=> '/test1',
					'exists'	=> false,
					'is_dir'	=> false,
					'view'		=> '',
				],
				[
					'user1' => [
						23 => [
							'path'		=> null,
							'exists'	=> false,
							'is_dir'	=> false,
							'view'		=> '',
						],
					],
				],
			],
			[
				'user2', 23, '/test1', null, '/files/test3', '/files/test3', false,
				[
					'path'		=> '/test3',
					'exists'	=> true,
					'is_dir'	=> false,
					'view'		=> 'trashbin',
				],
				[
					'user2' => [
						23 => [
							'path'		=> '/test3',
							'exists'	=> true,
							'is_dir'	=> false,
							'view'		=> 'trashbin',
						],
					],
				],
			],
			[
				'user3', 23, '/test1', null, '/files/test3', '/files/test3', true,
				[
					'path'		=> '/test3',
					'exists'	=> true,
					'is_dir'	=> true,
					'view'		=> 'trashbin',
				],
				[
					'user3' => [
						23 => [
							'path'		=> '/test3',
							'exists'	=> true,
							'is_dir'	=> true,
							'view'		=> 'trashbin',
						],
					],
				],
			],
			[
				'user4', 23, '/test1', '/test3', null, '/test3', false,
				[
					'path'		=> '/test3',
					'exists'	=> true,
					'is_dir'	=> false,
					'view'		=> '',
				],
				[
					'user4' => [
						23 => [
							'path'		=> '/test3',
							'exists'	=> true,
							'is_dir'	=> false,
							'view'		=> '',
						],
					],
				],
			],
			[
				'user5', 23, '/test1', '/test3', null, '/test3', true,
				[
					'path'		=> '/test3',
					'exists'	=> true,
					'is_dir'	=> true,
					'view'		=> '',
				],
				[
					'user5' => [
						23 => [
							'path'		=> '/test3',
							'exists'	=> true,
							'is_dir'	=> true,
							'view'		=> '',
						],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider dataFindInfoById
	 *
	 * @param string $user
	 * @param int $fileId
	 * @param string $filename
	 * @param string|null $path
	 * @param string|null $pathTrash
	 * @param string $isDirPath
	 * @param bool $isDir
	 * @param string $expected
	 * @param array $expectedCache
	 */
	public function testFindInfoById($user, $fileId, $filename, $path, $pathTrash, $isDirPath, $isDir, $expected, array $expectedCache) {
		$this->view->expects($this->at(0))
			->method('chroot')
			->with('/' . $user . '/files');
		if ($path === null) {
			$this->view->expects($this->at(1))
				->method('getPath')
				->with($fileId)
				->willThrowException(new NotFoundException());

			$this->view->expects($this->at(2))
				->method('chroot')
				->with('/' . $user . '/files_trashbin');
			if ($pathTrash === null) {
				$this->view->expects($this->at(3))
					->method('getPath')
					->with($fileId)
					->willThrowException(new NotFoundException());
			} else {
				$this->view->expects($this->at(3))
					->method('getPath')
					->with($fileId)
					->willReturn($pathTrash);
			}
		} else {
			$this->view->expects($this->at(1))
				->method('getPath')
				->with($fileId)
				->willReturn($path);
		}

		$this->view->expects(($path === null && $pathTrash === null) ? $this->never() : $this->once())
			->method('is_dir')
			->with($isDirPath)
			->willReturn($isDir);

		$infoCache = $this->getCache();
		$this->assertSame($expected, $this->invokePrivate($infoCache, 'findInfoById', [$user, $fileId, $filename]));
		$this->assertSame($expectedCache, $this->invokePrivate($infoCache, 'cacheId'));
	}
}
