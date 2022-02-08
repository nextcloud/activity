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

namespace OCA\Activity\Tests;

use OC\Files\View;
use OCA\Activity\ViewInfoCache;
use OCP\Files\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;

class ViewInfoCacheTest extends TestCase {
	/** @var View|MockObject */
	protected $view;

	/** @var ViewInfoCache|MockObject */
	protected $infoCache;

	protected function setUp(): void {
		parent::setUp();

		$this->view = $this->createMock(View::class);
	}

	/**
	 * @param array $methods
	 * @return ViewInfoCache|MockObject
	 */
	public function getCache(array $methods = []): ViewInfoCache {
		if (empty($methods)) {
			return new ViewInfoCache(
				$this->view
			);
		}

		return $this->getMockBuilder(ViewInfoCache::class)
			->setConstructorArgs([
				$this->view,
			])
			->setMethods($methods)
			->getMock();
	}

	public function dataGetInfoByPath(): array {
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
	public function testGetInfoByPath(string $user, string $path, array $cache, bool $callsFind, string $expected): void {
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
		self::invokePrivate($infoCache, 'cachePath', [$cache]);

		$this->assertSame($expected, $infoCache->getInfoByPath($user, $path));
	}

	public function dataGetInfoById(): array {
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
	public function testGetInfoById(string $user, int $id, string $path, array $cache, bool $callsFind, $expected): void {
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
		self::invokePrivate($infoCache, 'cacheId', [$cache]);

		$this->assertSame($expected, $infoCache->getInfoById($user, $id, $path));
	}

	public function dataFindByPath(): array {
		return [
			['user1', 'path1', true, true, [
				'path' => 'path1',
				'exists' => true,
				'is_dir' => true,
				'view' => '',
			]],
			['user2', 'path2', true, false, [
				'path' => 'path2',
				'exists' => true,
				'is_dir' => false,
				'view' => '',
			]],
			['user3', 'path3', false, null, [
				'path' => 'path3',
				'exists' => false,
				'is_dir' => false,
				'view' => '',
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
	public function testFindByPath(string $user, string $path, bool $exists, ?bool $is_dir, array $expected): void {
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

		$this->assertSame($expected, self::invokePrivate($infoCache, 'findInfoByPath', [$user, $path]));
	}

	public function dataFindInfoById(): array {
		return [
			[
				'user1', 23, '/test1', null, null, '/test1', false,
				[
					'path' => '/test1',
					'exists' => false,
					'is_dir' => false,
					'view' => '',
				],
				[
					'user1' => [
						23 => [
							'path' => null,
							'exists' => false,
							'is_dir' => false,
							'view' => '',
						],
					],
				],
			],
			[
				'user2', 23, '/test1', null, '/files/test3', '/files/test3', false,
				[
					'path' => '/test3',
					'exists' => true,
					'is_dir' => false,
					'view' => 'trashbin',
				],
				[
					'user2' => [
						23 => [
							'path' => '/test3',
							'exists' => true,
							'is_dir' => false,
							'view' => 'trashbin',
						],
					],
				],
			],
			[
				'user3', 23, '/test1', null, '/files/test3', '/files/test3', true,
				[
					'path' => '/test3',
					'exists' => true,
					'is_dir' => true,
					'view' => 'trashbin',
				],
				[
					'user3' => [
						23 => [
							'path' => '/test3',
							'exists' => true,
							'is_dir' => true,
							'view' => 'trashbin',
						],
					],
				],
			],
			[
				'user4', 23, '/test1', '/test3', null, '/test3', false,
				[
					'path' => '/test3',
					'exists' => true,
					'is_dir' => false,
					'view' => '',
				],
				[
					'user4' => [
						23 => [
							'path' => '/test3',
							'exists' => true,
							'is_dir' => false,
							'view' => '',
						],
					],
				],
			],
			[
				'user5', 23, '/test1', '/test3', null, '/test3', true,
				[
					'path' => '/test3',
					'exists' => true,
					'is_dir' => true,
					'view' => '',
				],
				[
					'user5' => [
						23 => [
							'path' => '/test3',
							'exists' => true,
							'is_dir' => true,
							'view' => '',
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
	 * @param array $expected
	 * @param array $expectedCache
	 */
	public function testFindInfoById(string $user, int $fileId, string $filename, ?string $path, ?string $pathTrash, string $isDirPath, bool $isDir, array $expected, array $expectedCache): void {
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
		$this->assertSame($expected, self::invokePrivate($infoCache, 'findInfoById', [$user, $fileId, $filename]));
		$this->assertSame($expectedCache, self::invokePrivate($infoCache, 'cacheId'));
	}
}
