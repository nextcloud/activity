<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests;

use Doctrine\DBAL\Connection as DbalConnection;
use Doctrine\DBAL\DriverManager;
use OC\DB\Connection;
use OC\DB\ConnectionAdapter;
use OC\DB\QueryBuilder\QueryBuilder;
use OC\DB\ResultAdapter;
use OC\SystemConfig;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\SearchCriteria;
use OCA\Activity\TeamActivityFileScope;
use OCA\Activity\TeamActivityScope;
use OCA\Activity\UserSettings;
use OCP\Activity\Exceptions\FilterNotFoundException;
use OCP\Activity\IManager;
use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\IHomeStorage;
use OCP\Files\IRootFolder;
use OCP\Files\Mount\IMountPoint;
use OCP\Files\Storage\IStorage;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\Teams\TeamActivityScope as ResourceActivityScope;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class TeamActivityDataTest extends TestCase {
	private DbalConnection $database;
	private Data $data;
	/** @var list<int> */
	private array $fileLookups = [];
	/** @var list<array{sql: string, parameters: array, types: array}> */
	private array $queries = [];

	protected function setUp(): void {
		parent::setUp();
		if (!in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
			$this->markTestSkipped('The isolated Team Activity tests require pdo_sqlite');
		}
		$this->database = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
		$this->database->executeStatement('CREATE TABLE activity (activity_id INTEGER PRIMARY KEY, app TEXT, subject TEXT, subjectparams TEXT, message TEXT, messageparams TEXT, file TEXT, link TEXT, user TEXT, affecteduser TEXT, timestamp INTEGER, priority INTEGER, type TEXT, object_type TEXT, object_id INTEGER)');
		$this->database->executeStatement('CREATE INDEX activity_object ON activity (object_type, object_id)');
		$this->database->executeStatement('CREATE TABLE filecache (fileid INTEGER PRIMARY KEY, storage INTEGER, path TEXT)');
		$this->database->executeStatement('CREATE INDEX filecache_storage_path ON filecache (storage, path)');
		$inner = $this->createMock(Connection::class);
		$inner->method('getDatabasePlatform')->willReturn($this->database->getDatabasePlatform());
		$connection = $this->createMock(ConnectionAdapter::class);
		$connection->method('getInner')->willReturn($inner);
		$connection->method('getDatabaseProvider')->willReturn(IDBConnection::PLATFORM_SQLITE);
		$connection->method('escapeLikeParameter')->willReturnCallback(static fn (string $value): string => addcslashes($value, '\\_%'));
		$connection->method('executeQuery')->willReturnCallback(function ($sql, $parameters, $types): ResultAdapter {
			$this->queries[] = ['sql' => $sql, 'parameters' => $parameters, 'types' => $types];
			return new ResultAdapter($this->database->executeQuery($sql, $parameters, $types));
		});
		$systemConfig = $this->createMock(SystemConfig::class);
		$connection->method('getQueryBuilder')->willReturnCallback(static function () use ($connection, $systemConfig): QueryBuilder {
			$query = new QueryBuilder($connection, $systemConfig, new NullLogger());
			$query->automaticTablePrefix(false);
			return $query;
		});
		$manager = $this->createMock(IManager::class);
		$manager->method('getFilterById')->willThrowException(new FilterNotFoundException('all'));
		$this->data = new Data($manager, $connection, new NullLogger(), $this->createMock(IConfig::class), $this->createMock(IRootFolder::class));
	}

	public static function pages(): array {
		return [
			[1, 'desc', [90, 85, 80]],
			[2, 'desc', [90, 85, 80]],
			[1, 'asc', [80, 85, 90]],
			[2, 'asc', [80, 85, 90]],
		];
	}

	#[DataProvider('pages')]
	public function testPaginationDeduplicatesBeforeApplyingCursorAndLimit(int $limit, string $sort, array $expected): void {
		$this->insertFileEvent(100, 100, 7, 'created_self', 'alice', '/Owner/a.txt');
		$this->insertFileEvent(90, 100, 7, 'created_by', 'bob', '/Recipient/a.txt');
		$this->insertFileEvent(85, 100, 8, 'created_by', 'bob', '/Recipient/b.txt');
		$this->database->insert('activity', [
			'activity_id' => 80, 'timestamp' => 99, 'app' => 'circles',
			'subject' => 'member_added', 'subjectparams' => '[]', 'message' => '', 'messageparams' => '[]',
			'file' => '', 'link' => '', 'user' => 'alice', 'affecteduser' => 'bob',
			'priority' => 30, 'type' => 'member_added', 'object_type' => 'circles', 'object_id' => 42,
		]);
		$scope = $this->fileScope([7 => '/a.txt', 8 => '/b.txt']);
		$seen = [];
		$since = 0;
		$page = 0;
		do {
			$result = $this->page($scope, $since, $limit, $sort);
			foreach ($result['data'] as $row) {
				$seen[] = (int)$row['activity_id'];
				if ($row['object_type'] === 'files') {
					$this->assertSame('viewer', $row['affecteduser']);
					$this->assertStringStartsWith('/Viewer/', $row['file']);
					$this->assertStringNotContainsString('Recipient', $row['subjectparams']);
					$this->assertStringNotContainsString('Owner', $row['subjectparams']);
				}
			}
			if (!$result['has_more']) {
				break;
			}
			$this->assertNotEmpty($result['data']);
			$since = $result['headers']['X-Activity-Last-Given'];
			$page++;
		} while ($page < 5);
		$this->assertSame($expected, $seen);
		$this->assertFalse($result['has_more']);
		$empty = $this->page($scope, $result['headers']['X-Activity-Last-Given'], $limit, $sort);
		$this->assertSame([], $empty['data']);
		$this->assertFalse($empty['has_more']);
	}

	public function testMovePathsAreRecipientIndependentButDistinctMovesRemain(): void {
		$this->insertFileEvent(10, 100, 7, 'moved_self', 'alice', '/Owner/New/a.txt', '/Owner/Old/a.txt');
		$this->insertFileEvent(20, 100, 7, 'moved_by', 'bob', '/Recipient/New/a.txt', '/Recipient/Old/a.txt');
		$this->insertFileEvent(30, 100, 7, 'moved_by', 'bob', '/Recipient/New/a.txt', '/Recipient/Other/a.txt');
		$scope = $this->fileScope([7 => '/New/a.txt']);
		$result = $this->page($scope, 0, 10, 'asc');
		$this->assertSame([10, 30], array_map('intval', array_column($result['data'], 'activity_id')));
		$this->assertSame([[7 => '/Viewer/New/a.txt'], 'alice', [7 => '/Viewer/Old/a.txt']], json_decode($result['data'][0]['subjectparams'], true));
		$this->assertSame([[7 => '/Viewer/New/a.txt'], 'alice', [7 => '/Viewer/Other/a.txt']], json_decode($result['data'][1]['subjectparams'], true));
	}

	public function testPaginationDeduplicatesOverlappingFileRoots(): void {
		$this->insertFileEvent(10, 100, 1, 'created_self', 'alice', '/Owner/a.txt');
		$this->insertFileEvent(20, 100, 1, 'created_by', 'bob', '/Recipient/a.txt');
		$this->insertFileEvent(30, 100, 501, 'created_by', 'bob', '/Recipient/b.txt');
		$scope = $this->fileScope([1 => '/a.txt', 501 => '/b.txt']);
		$scope = new TeamActivityScope(42, [$scope->fileScopes[0], $scope->fileScopes[0]]);
		$first = $this->page($scope, 0, 1, 'desc');
		$this->assertSame(30, $first['headers']['X-Activity-Last-Given']);
		$this->assertTrue($first['has_more']);
		$second = $this->page($scope, 30, 1, 'desc');
		$this->assertSame(10, $second['headers']['X-Activity-Last-Given']);
		$this->assertFalse($second['has_more']);
	}

	private function insertFileEvent(int $id, int $timestamp, int $fileId, string $subject, string $recipient, string $path, ?string $oldPath = null): void {
		$parameters = [[$fileId => $path]];
		if (str_ends_with($subject, '_by')) {
			$parameters[] = 'alice';
		}
		if ($oldPath !== null) {
			$parameters[] = [$fileId => $oldPath];
		}
		$this->database->insert('activity', [
			'activity_id' => $id, 'timestamp' => $timestamp, 'app' => 'files',
			'subject' => $subject, 'subjectparams' => json_encode($parameters), 'message' => '', 'messageparams' => '[]',
			'file' => $path, 'link' => '/recipient/' . $recipient, 'user' => 'alice', 'affecteduser' => $recipient,
			'priority' => 30, 'type' => 'file_created', 'object_type' => 'files', 'object_id' => $fileId,
		]);
	}

	private function page(TeamActivityScope $scope, int $since, int $limit, string $sort, ?SearchCriteria $search = null): array {
		$activities = [];
		$helper = $this->createMock(GroupHelper::class);
		$helper->method('addActivity')->willReturnCallback(static function (array $row) use (&$activities): void {
			$activities[] = $row;
		});
		$helper->method('getActivities')->willReturnCallback(static function () use (&$activities): array {
			return $activities;
		});
		return $this->data->getTeam($helper, $this->createMock(UserSettings::class), 'viewer', $since, $limit, $sort, $scope, $search);
	}

	public function testNonFileResourceScopesKeepTheirActivityPayload(): void {
		$this->database->insert('activity', [
			'activity_id' => 20, 'timestamp' => 100, 'app' => 'deck',
			'subject' => 'card_create', 'subjectparams' => '{"card":{"id":70,"title":"Team card"}}', 'message' => '', 'messageparams' => '[]',
			'file' => '', 'link' => '/apps/deck/#/board/7/card/70', 'user' => 'alice', 'affecteduser' => 'alice',
			'priority' => 30, 'type' => 'deck', 'object_type' => 'deck_card', 'object_id' => 70,
		]);
		$this->database->insert('activity', [
			'activity_id' => 21, 'timestamp' => 101, 'app' => 'deck',
			'subject' => 'card_create', 'subjectparams' => '{"card":{"id":71,"title":"Other card"}}', 'message' => '', 'messageparams' => '[]',
			'file' => '', 'link' => '/apps/deck/#/board/8/card/71', 'user' => 'alice', 'affecteduser' => 'alice',
			'priority' => 30, 'type' => 'deck', 'object_type' => 'deck_card', 'object_id' => 71,
		]);
		$scope = new TeamActivityScope(42, [], [new ResourceActivityScope('deck_card', [70])]);

		$result = $this->page($scope, 0, 10, 'desc');

		self::assertSame([20], array_map('intval', array_column($result['data'], 'activity_id')));
		self::assertSame('/apps/deck/#/board/7/card/70', $result['data'][0]['link']);
		self::assertSame('{"card":{"id":70,"title":"Team card"}}', $result['data'][0]['subjectparams']);
	}

	/**
	 * @param array<int, string> $paths Paths relative to the shared root
	 * @param list<int> $denied
	 */
	private function fileScope(array $paths, array $denied = [], string $cacheRoot = 'files/Team', int $storage = 1, bool $homeStorage = false, int $rootId = 10000): TeamActivityScope {
		$this->database->insert('filecache', ['fileid' => $rootId, 'storage' => $storage, 'path' => $cacheRoot]);
		$mount = $this->createMock(IMountPoint::class);
		$mount->method('getMountPoint')->willReturn('/viewer/files/Viewer/');
		$root = $this->createMock(Folder::class);
		$root->method('getId')->willReturn($rootId);
		$root->method('getPath')->willReturn('/viewer/files/Viewer');
		$root->method('getMountPoint')->willReturn($mount);
		$root->method('isReadable')->willReturn(true);
		$rootStorage = $this->createMock(IStorage::class);
		$rootStorage->method('instanceOfStorage')->willReturnCallback(static fn (string $class): bool => $homeStorage && $class === IHomeStorage::class);
		$root->method('getStorage')->willReturn($rootStorage);
		$root->expects($this->never())->method('getDirectoryListing');
		$folders = ['' => $root];
		$nodes = [];
		foreach ($paths as $id => $path) {
			$this->database->insert('filecache', ['fileid' => $id, 'storage' => $storage, 'path' => ltrim($cacheRoot . $path, '/')]);
			$parentPath = '';
			foreach (array_slice(explode('/', trim($path, '/')), 0, -1) as $segment) {
				$folderPath = $parentPath . '/' . $segment;
				if (!isset($folders[$folderPath])) {
					$folder = $this->createMock(Folder::class);
					$folder->method('getPath')->willReturn('/viewer/files/Viewer' . $folderPath);
					$folder->method('getMountPoint')->willReturn($mount);
					$folder->method('isReadable')->willReturn(true);
					$folder->method('getParent')->willReturn($folders[$parentPath]);
					$folders[$folderPath] = $folder;
				}
				$parentPath = $folderPath;
			}
			$node = $this->createMock(File::class);
			$node->method('getId')->willReturn($id);
			$node->method('getPath')->willReturn('/viewer/files/Viewer' . $path);
			$node->method('getMountPoint')->willReturn($mount);
			$node->method('isReadable')->willReturn(!in_array($id, $denied, true));
			$node->method('getParent')->willReturn($folders[$parentPath]);
			$nodes[$id] = [$node];
		}
		$root->method('getById')->willReturnCallback(function (int $id) use ($nodes): array {
			$this->fileLookups[] = $id;
			return $nodes[$id] ?? [];
		});
		return new TeamActivityScope(42, [new TeamActivityFileScope($root, '/Viewer')]);
	}

	#[DataProvider('pages')]
	public function testPaginationRefillsAfterMoreThanOneBatchOfDeniedTimestamps(int $limit, string $sort): void {
		$paths = [];
		$denied = [];
		for ($id = 1; $id <= 125; $id++) {
			$paths[$id] = '/' . $id . '.txt';
			if (!in_array($id, [1, 63, 125], true)) {
				$denied[] = $id;
			}
			$this->insertFileEvent($id, $id, $id, 'created_by', 'alice', '/Owner/' . $id . '.txt');
		}
		$scope = $this->fileScope($paths, $denied);
		$seen = [];
		$since = 0;
		do {
			$result = $this->page($scope, $since, $limit, $sort);
			foreach ($result['data'] as $row) {
				$seen[] = (int)$row['activity_id'];
			}
			if ($result['has_more']) {
				self::assertNotEmpty($result['data']);
				$since = $result['headers']['X-Activity-Last-Given'];
			}
		} while ($result['has_more'] && count($seen) < 5);
		self::assertSame($sort === 'asc' ? [1, 63, 125] : [125, 63, 1], $seen);
		self::assertFalse($result['has_more']);
	}

	public static function cacheRoots(): array {
		return [
			'shared folder' => ['files/Team'],
			'literal wildcards' => ['files/T_e%am'],
			'literal escape' => ['files/T\\eam'],
			'storage root' => [''],
		];
	}

	#[DataProvider('cacheRoots')]
	public function testFilecacheRestrictsStorageAndSubtree(string $cacheRoot): void {
		$scope = $this->fileScope([7 => '/a.txt'], [], $cacheRoot);
		$this->insertFileEvent(1, 100, 7, 'created_by', 'alice', '/Owner/a.txt');
		foreach ([
			[8, 1, $cacheRoot . '2/a.txt'],
			[9, 2, ltrim($cacheRoot . '/b.txt', '/')],
			[10, 1, 'files/Txexxam/a.txt'],
		] as [$id, $storage, $path]) {
			$this->database->insert('filecache', ['fileid' => $id, 'storage' => $storage, 'path' => $path]);
			$this->insertFileEvent($id, 100, $id, 'created_by', 'alice', '/Other/a.txt');
		}
		$this->insertFileEvent(11, 100, 11, 'deleted_by', 'alice', '/Deleted/a.txt');
		$result = $this->page($scope, 0, 10, 'desc');
		self::assertSame([1], array_map('intval', array_column($result['data'], 'activity_id')));
		sort($this->fileLookups);
		self::assertSame($cacheRoot === '' ? [7, 8, 10] : [7], $this->fileLookups);
	}

	public function testAllDeniedCandidatesProduceAnExhaustedEmptyPage(): void {
		$scope = $this->fileScope([7 => '/a.txt'], [7]);
		$this->insertFileEvent(1, 100, 7, 'created_by', 'alice', '/Owner/a.txt');
		$result = $this->page($scope, 0, 1, 'desc');
		self::assertSame([], $result['data']);
		self::assertFalse($result['has_more']);
		self::assertSame([], $result['headers']);
	}

	public function testLargeSubtreeOnlyResolvesActivityCandidates(): void {
		$scope = $this->fileScope([7 => '/a.txt']);
		$this->database->beginTransaction();
		for ($id = 20000; $id < 30000; $id++) {
			$this->database->insert('filecache', ['fileid' => $id, 'storage' => 1, 'path' => 'files/Team/unused/' . $id]);
		}
		$this->database->commit();
		$this->insertFileEvent(1, 100, 7, 'created_self', 'alice', '/Owner/a.txt');
		$this->insertFileEvent(2, 100, 7, 'created_by', 'bob', '/Recipient/a.txt');

		$result = $this->page($scope, 0, 1, 'desc');

		self::assertSame([1], array_map('intval', array_column($result['data'], 'activity_id')));
		self::assertFalse($result['has_more']);
		self::assertSame([7], $this->fileLookups);
		self::assertCount(5, $this->queries);
		foreach ($this->queries as $query) {
			self::assertLessThan(1000, strlen($query['sql']));
		}
	}

	public function testIndividualFileScopeDoesNotIncludeSiblingFiles(): void {
		$folderScope = $this->fileScope([7 => '/a.txt', 8 => '/b.txt']);
		$file = $folderScope->fileScopes[0]->root->getById(7)[0];
		$scope = new TeamActivityScope(42, [new TeamActivityFileScope($file, '/Viewer/a.txt')]);
		$this->insertFileEvent(1, 100, 7, 'created_by', 'alice', '/Owner/a.txt');
		$this->insertFileEvent(2, 100, 8, 'created_by', 'alice', '/Owner/b.txt');

		$result = $this->page($scope, 0, 10, 'desc');

		self::assertSame([1], array_map('intval', array_column($result['data'], 'activity_id')));
	}

	public function testFileSearchDateAndActorRestrictionsArePreserved(): void {
		$scope = $this->fileScope([7 => '/a.txt', 8 => '/b.txt']);
		$this->insertFileEvent(1, 100, 7, 'created_by', 'alice', '/Owner/a.txt');
		$this->insertFileEvent(2, 101, 8, 'created_by', 'alice', '/Owner/b.txt');
		$this->insertFileEvent(3, 102, 7, 'created_by', 'alice', '/Owner/a.txt');
		$result = $this->page($scope, 0, 10, 'desc', SearchCriteria::create('a.txt', 100, 101, 'alice'));
		self::assertSame([1], array_map('intval', array_column($result['data'], 'activity_id')));
	}

	public function testNonFileResourceChunksStillMergeBeforePagination(): void {
		foreach ([1 => 1, 2 => 501] as $activityId => $objectId) {
			$this->insertFileEvent($activityId, 100, $objectId, 'created_by', 'alice', '/Unused');
			$this->database->update('activity', ['app' => 'deck', 'object_type' => 'deck_card', 'subject' => 'card_create'], ['activity_id' => $activityId]);
		}
		$scope = new TeamActivityScope(42, [], [new ResourceActivityScope('deck_card', range(1, 501))]);
		$first = $this->page($scope, 0, 1, 'desc');
		self::assertSame(2, $first['headers']['X-Activity-Last-Given']);
		self::assertTrue($first['has_more']);
		$second = $this->page($scope, 2, 1, 'desc');
		self::assertSame(1, $second['headers']['X-Activity-Last-Given']);
		self::assertFalse($second['has_more']);
	}

	public function testTimestampBucketsAreNotTruncatedBeforeDeduplication(): void {
		$scope = $this->fileScope([7 => '/a.txt', 8 => '/b.txt']);
		for ($id = 1; $id <= 125; $id++) {
			$this->insertFileEvent($id, 100, 7, 'created_by', 'recipient-' . $id, '/Recipient/a.txt');
		}
		$this->insertFileEvent(126, 100, 8, 'created_by', 'alice', '/Owner/b.txt');
		$first = $this->page($scope, 0, 1, 'asc');
		self::assertSame(1, $first['headers']['X-Activity-Last-Given']);
		self::assertTrue($first['has_more']);
		$second = $this->page($scope, 1, 1, 'asc');
		self::assertSame(126, $second['headers']['X-Activity-Last-Given']);
		self::assertFalse($second['has_more']);
	}

	public function testHomeStorageRootExcludesTrashVersionsAndCaches(): void {
		$scope = $this->fileScope([], [], '', 1, true);
		foreach ([[20, 'files/a.txt'], [21, 'files_trashbin/files/b.txt'], [22, 'files_versions/a.txt.v1'], [23, 'cache/c']] as [$id, $path]) {
			$this->database->insert('filecache', ['fileid' => $id, 'storage' => 1, 'path' => $path]);
			$this->insertFileEvent($id, 100, $id, 'created_by', 'alice', '/Owner/' . $id);
		}

		$this->page($scope, 0, 10, 'desc');

		self::assertSame([20], $this->fileLookups);
	}

	public function testManyFileRootsAreSplitIntoBoundedQueries(): void {
		$scope = $this->fileScope([7 => '/a.txt']);
		$fileScopes = $scope->fileScopes;
		for ($i = 0; $i < 250; $i++) {
			$this->database->insert('filecache', ['fileid' => 20000 + $i, 'storage' => 1, 'path' => 'files/Filler' . $i]);
			$fileScopes[] = new TeamActivityFileScope($this->filler(Folder::class, 20000 + $i), '/Filler' . $i);
			$fileScopes[] = new TeamActivityFileScope($this->filler(File::class, 40000 + $i), '/Single' . $i);
		}
		$this->insertFileEvent(1, 100, 7, 'created_by', 'alice', '/Owner/a.txt');

		$result = $this->page(new TeamActivityScope(42, $fileScopes), 0, 10, 'desc');

		self::assertSame([1], array_map('intval', array_column($result['data'], 'activity_id')));
		foreach ($this->queries as $query) {
			$count = 0;
			array_walk_recursive($query['parameters'], static function () use (&$count): void {
				$count++;
			});
			self::assertLessThan(400, $count);
		}
	}

	public function testQueryCountDoesNotGrowWithTheNumberOfTimestamps(): void {
		$paths = [];
		for ($id = 1; $id <= 30; $id++) {
			$paths[$id] = '/' . $id . '.txt';
			$this->insertFileEvent($id, $id, $id, 'created_by', 'alice', '/Owner/' . $id . '.txt');
		}
		$scope = $this->fileScope($paths);

		$result = $this->page($scope, 0, 200, 'desc');

		self::assertCount(30, $result['data']);
		self::assertCount(5, $this->queries);
	}

	public function testRootsOnDifferentStoragesOnlyMatchTheirOwnSubtree(): void {
		$first = $this->fileScope([7 => '/a.txt'], [], 'files/Team', 1);
		$second = $this->fileScope([8 => '/b.txt'], [], 'files/Other', 2, false, 10001);
		$scope = new TeamActivityScope(42, [$first->fileScopes[0], $second->fileScopes[0]]);
		$this->insertFileEvent(1, 100, 7, 'created_by', 'alice', '/Owner/a.txt');
		$this->insertFileEvent(2, 101, 8, 'created_by', 'alice', '/Owner/b.txt');
		foreach ([[9, 2, 'files/Team/x.txt'], [10, 1, 'files/Other/y.txt']] as [$id, $storage, $path]) {
			$this->database->insert('filecache', ['fileid' => $id, 'storage' => $storage, 'path' => $path]);
			$this->insertFileEvent($id + 10, 102, $id, 'created_by', 'alice', '/Owner/' . $id);
		}

		$result = $this->page($scope, 0, 10, 'asc');

		self::assertSame([1, 2], array_map('intval', array_column($result['data'], 'activity_id')));
		self::assertNotContains(9, $this->fileLookups);
		self::assertNotContains(10, $this->fileLookups);
	}

	public function testExactlyOneBatchOfTimestampsIsNotTruncated(): void {
		$paths = [];
		for ($id = 1; $id <= 50; $id++) {
			$paths[$id] = '/' . $id . '.txt';
			$this->insertFileEvent($id, $id, $id, 'created_by', 'alice', '/Owner/' . $id . '.txt');
		}
		$scope = $this->fileScope($paths);

		$result = $this->page($scope, 0, 200, 'desc');

		self::assertCount(50, $result['data']);
		self::assertFalse($result['has_more']);
	}

	/**
	 * @param class-string<\OCP\Files\Node> $class
	 */
	private function filler(string $class, int $id): \OCP\Files\Node {
		$node = $this->createMock($class);
		$node->method('getId')->willReturn($id);
		$node->method('getPath')->willReturn('/viewer/files/Filler' . $id);
		return $node;
	}
}
