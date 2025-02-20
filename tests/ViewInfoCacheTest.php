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

use OCA\Activity\ViewInfoCache;
use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;

class ViewInfoCacheTest extends TestCase {
	/** @var IRootFolder|MockObject */
	protected $rootFolder;

	/** @var ViewInfoCache|MockObject */
	protected $infoCache;

	protected function setUp(): void {
		parent::setUp();

		$this->rootFolder = $this->createMock(IRootFolder::class);
	}

	/**
	 * @param array $methods
	 * @return ViewInfoCache|MockObject
	 */
	public function getCache(array $methods = []): ViewInfoCache {
		if (empty($methods)) {
			return new ViewInfoCache(
				$this->rootFolder
			);
		}

		return $this->getMockBuilder(ViewInfoCache::class)
			->setConstructorArgs([
				$this->rootFolder,
			])
			->onlyMethods($methods)
			->getMock();
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
				'user2', 23, '/test1', null, '/test3', '/test3', false,
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
				'user3', 23, '/test1', null, '/test3', '/test3', true,
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
		$userFolder = $this->createMock(Folder::class);

		$this->rootFolder->expects($this->any())
			->method('getUserFolder')
			->with($user)
			->willReturn($userFolder);
		if ($path === null) {
			$userFolder->expects($this->once())
				->method('getById')
				->with($fileId)
				->willThrowException(new NotFoundException());

			$userTrashBin = $this->createMock(Folder::class);
			$this->rootFolder->expects($this->once())
				->method('get')
				->with('/' . $user . '/files_trashbin')
				->willReturn($userTrashBin);
			if ($pathTrash === null) {
				$userTrashBin->expects($this->once())
					->method('getById')
					->with($fileId)
					->willThrowException(new NotFoundException());
			} else {
				$node = $this->createMock($isDir ? Folder::class : File::class);
				$node->expects($this->any())
					->method('getPath')
					->willReturn('/' . $user . '/files_trashbin' . $pathTrash);
				$userTrashBin->expects($this->any())
					->method('getRelativePath')
					->with('/' . $user . '/files_trashbin' . $pathTrash)
					->willReturn($pathTrash);

				$userTrashBin->expects($this->once())
					->method('getById')
					->with($fileId)
					->willReturn([2 => $node]);
				$expected['node'] = $node;
				$expectedCache[$user][$fileId]['node'] = $node;
			}
		} else {
			$node = $this->createMock($isDir ? Folder::class : File::class);
			$node->expects($this->any())
				->method('getPath')
				->willReturn('/' . $user . '/files' . $path);
			$userFolder->expects($this->any())
				->method('getRelativePath')
				->with('/' . $user . '/files' . $path)
				->willReturn($path);

			$userFolder->expects($this->once())
				->method('getById')
				->with($fileId)
				->willReturn([3 => $node]);
			$expected['node'] = $node;
			$expectedCache[$user][$fileId]['node'] = $node;
		}

		$infoCache = $this->getCache();
		$this->assertSame($expected, self::invokePrivate($infoCache, 'findInfoById', [$user, $fileId, $filename]));
		$this->assertSame($expectedCache, self::invokePrivate($infoCache, 'cacheId'));
	}
}
