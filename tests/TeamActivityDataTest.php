<?php

declare(strict_types=1);

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
use OCA\Activity\TeamActivityScope;
use OCA\Activity\UserSettings;
use OCP\Activity\Exceptions\FilterNotFoundException;
use OCP\Activity\IManager;
use OCP\Files\IRootFolder;
use OCP\IConfig;
use OCP\IDBConnection;
	use OCP\Teams\TeamActivityScope as ResourceActivityScope;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class TeamActivityDataTest extends TestCase {
	private DbalConnection $database;
	private Data $data;

	protected function setUp(): void {
		parent::setUp();
		if (!in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
			$this->markTestSkipped('The isolated Team Activity tests require pdo_sqlite');
		}
		$this->database = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
		$this->database->executeStatement('CREATE TABLE activity (activity_id INTEGER PRIMARY KEY, app TEXT, subject TEXT, subjectparams TEXT, message TEXT, messageparams TEXT, file TEXT, link TEXT, user TEXT, affecteduser TEXT, timestamp INTEGER, priority INTEGER, type TEXT, object_type TEXT, object_id INTEGER)');
		$inner = $this->createMock(Connection::class);
		$inner->method('getDatabasePlatform')->willReturn($this->database->getDatabasePlatform());
		$connection = $this->createMock(ConnectionAdapter::class);
		$connection->method('getInner')->willReturn($inner);
		$connection->method('getDatabaseProvider')->willReturn(IDBConnection::PLATFORM_SQLITE);
		$connection->method('executeQuery')->willReturnCallback(fn ($sql, $parameters, $types) => new ResultAdapter($this->database->executeQuery($sql, $parameters, $types)));
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
		$scope = new TeamActivityScope(42, [7, 8], [7 => '/Viewer/a.txt', 8 => '/Viewer/b.txt']);
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
		$scope = new TeamActivityScope(42, [7], [7 => '/Viewer/New/a.txt']);
		$result = $this->page($scope, 0, 10, 'asc');
		$this->assertSame([10, 30], array_map('intval', array_column($result['data'], 'activity_id')));
		$this->assertSame([[7 => '/Viewer/New/a.txt'], 'alice', [7 => '/Viewer/Old/a.txt']], json_decode($result['data'][0]['subjectparams'], true));
		$this->assertSame([[7 => '/Viewer/New/a.txt'], 'alice', [7 => '/Viewer/Other/a.txt']], json_decode($result['data'][1]['subjectparams'], true));
	}

	public function testPaginationMergesMultipleFileIdChunks(): void {
		$this->insertFileEvent(10, 100, 1, 'created_self', 'alice', '/Owner/a.txt');
		$this->insertFileEvent(20, 100, 1, 'created_by', 'bob', '/Recipient/a.txt');
		$this->insertFileEvent(30, 100, 501, 'created_by', 'bob', '/Recipient/b.txt');
		$scope = new TeamActivityScope(42, range(1, 501), [1 => '/Viewer/a.txt', 501 => '/Viewer/b.txt']);
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

	private function page(TeamActivityScope $scope, int $since, int $limit, string $sort): array {
		$activities = [];
		$helper = $this->createMock(GroupHelper::class);
		$helper->method('addActivity')->willReturnCallback(static function (array $row) use (&$activities): void {
			$activities[] = $row;
		});
		$helper->method('getActivities')->willReturnCallback(static function () use (&$activities): array {
			return $activities;
		});
		return $this->data->getTeam($helper, $this->createMock(UserSettings::class), 'viewer', $since, $limit, $sort, $scope);
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
		$scope = new TeamActivityScope(42, [], [], [new ResourceActivityScope('deck_card', [70])]);

		$result = $this->page($scope, 0, 10, 'desc');

		self::assertSame([20], array_map('intval', array_column($result['data'], 'activity_id')));
		self::assertSame('/apps/deck/#/board/7/card/70', $result['data'][0]['link']);
		self::assertSame('{"card":{"id":70,"title":"Team card"}}', $result['data'][0]['subjectparams']);
	}

}
