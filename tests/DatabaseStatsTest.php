<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use OCA\Activity\DatabaseStats;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IConfig;
use OCP\IDBConnection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class DatabaseStatsTest extends TestCase {
	private IDBConnection&MockObject $connection;
	private IConfig&MockObject $config;
	private LoggerInterface&MockObject $logger;
	private DatabaseStats $stats;

	protected function setUp(): void {
		parent::setUp();

		$this->connection = $this->createMock(IDBConnection::class);
		$this->config = $this->createMock(IConfig::class);
		$this->logger = $this->createMock(LoggerInterface::class);

		$this->stats = new DatabaseStats(
			$this->connection,
			$this->config,
			$this->logger,
		);
	}

	// ── getTableSizesInBytes ────────────────────────────────────────────────

	public function testGetTableSizesMySQLReturnsRowSums(): void {
		$platform = $this->createMock(MySQLPlatform::class);
		$this->connection->method('getDatabasePlatform')->willReturn($platform);

		$this->config->method('getSystemValue')
			->willReturnMap([
				['activity_dbtableprefix', 'oc_', 'oc_'],
				['dbtableprefix', 'oc_', 'oc_'],
			]);

		$result = $this->createMock(\OCP\DB\IResult::class);
		$result->method('fetch')->willReturnOnConsecutiveCalls(
			['table_name' => 'oc_activity',    'data_length' => '1000', 'index_length' => '200'],
			['table_name' => 'oc_activity_mq', 'data_length' => '500',  'index_length' => '100'],
			false,
		);
		$result->method('closeCursor')->willReturn(true);

		$expr = $this->createMock(\OCP\DB\QueryBuilder\IExpressionBuilder::class);
		$expr->method('eq')->willReturn('1=1');
		$expr->method('in')->willReturn('1=1');

		$qb = $this->createMock(IQueryBuilder::class);
		$qb->method('select')->willReturnSelf();
		$qb->method('from')->willReturnSelf();
		$qb->method('where')->willReturnSelf();
		$qb->method('andWhere')->willReturnSelf();
		$qb->method('expr')->willReturn($expr);
		$qb->method('createFunction')->willReturn('DATABASE()');
		$qb->method('createNamedParameter')->willReturn(':param');
		$qb->method('executeQuery')->willReturn($result);

		$this->connection->method('getQueryBuilder')->willReturn($qb);

		$sizes = $this->stats->getTableSizesInBytes();

		$this->assertSame(1200, $sizes['activity']);
		$this->assertSame(600, $sizes['activity_mq']);
	}

	public function testGetTableSizesPostgresReturnsRowValues(): void {
		$platform = $this->createMock(PostgreSQLPlatform::class);

		$this->connection->method('getDatabasePlatform')->willReturn($platform);

		$this->config->method('getSystemValue')
			->willReturnMap([
				['activity_dbtableprefix', 'oc_', 'oc_'],
				['dbtableprefix', 'oc_', 'oc_'],
			]);

		$resultActivity = $this->createMock(\OCP\DB\IResult::class);
		$resultActivity->method('fetch')->willReturn(['size_bytes' => '4096']);
		$resultActivity->method('closeCursor')->willReturn(true);

		$resultMq = $this->createMock(\OCP\DB\IResult::class);
		$resultMq->method('fetch')->willReturn(['size_bytes' => '2048']);
		$resultMq->method('closeCursor')->willReturn(true);

		$this->connection->method('executeQuery')
			->willReturnOnConsecutiveCalls($resultActivity, $resultMq);

		$sizes = $this->stats->getTableSizesInBytes();

		$this->assertSame(4096, $sizes['activity']);
		$this->assertSame(2048, $sizes['activity_mq']);
	}

	public function testGetTableSizesPostgresNullWhenTableMissing(): void {
		$platform = $this->createMock(PostgreSQLPlatform::class);

		$this->connection->method('getDatabasePlatform')->willReturn($platform);

		$this->config->method('getSystemValue')
			->willReturnMap([
				['activity_dbtableprefix', 'oc_', 'oc_'],
				['dbtableprefix', 'oc_', 'oc_'],
			]);

		// to_regclass returns NULL for missing tables → size_bytes is null
		$resultNull = $this->createMock(\OCP\DB\IResult::class);
		$resultNull->method('fetch')->willReturn(['size_bytes' => null]);
		$resultNull->method('closeCursor')->willReturn(true);

		$this->connection->method('executeQuery')
			->willReturn($resultNull);

		$sizes = $this->stats->getTableSizesInBytes();

		$this->assertNull($sizes['activity']);
		$this->assertNull($sizes['activity_mq']);
	}

	public function testGetTableSizesOracleReturnsRowValues(): void {
		$platform = $this->createMock(OraclePlatform::class);
		$this->connection->method('getDatabasePlatform')->willReturn($platform);

		$this->config->method('getSystemValue')
			->willReturnMap([
				['activity_dbtableprefix', 'oc_', 'oc_'],
				['dbtableprefix', 'oc_', 'oc_'],
			]);

		$resultActivity = $this->createMock(\OCP\DB\IResult::class);
		$resultActivity->method('fetch')->willReturn(['size_bytes' => '8192']);
		$resultActivity->method('closeCursor')->willReturn(true);

		$resultMq = $this->createMock(\OCP\DB\IResult::class);
		$resultMq->method('fetch')->willReturn(['size_bytes' => '4096']);
		$resultMq->method('closeCursor')->willReturn(true);

		$this->connection->method('executeQuery')
			->willReturnOnConsecutiveCalls($resultActivity, $resultMq);

		$sizes = $this->stats->getTableSizesInBytes();

		$this->assertSame(8192, $sizes['activity']);
		$this->assertSame(4096, $sizes['activity_mq']);
	}

	public function testGetTableSizesOracleUsesUppercaseTableName(): void {
		$platform = $this->createMock(OraclePlatform::class);
		$this->connection->method('getDatabasePlatform')->willReturn($platform);

		$this->config->method('getSystemValue')
			->willReturnMap([
				['activity_dbtableprefix', 'oc_', 'oc_'],
				['dbtableprefix', 'oc_', 'oc_'],
			]);

		$result = $this->createMock(\OCP\DB\IResult::class);
		$result->method('fetch')->willReturn(['size_bytes' => null]);
		$result->method('closeCursor')->willReturn(true);

		$this->connection->expects($this->exactly(2))
			->method('executeQuery')
			->with($this->anything(), $this->callback(static fn (array $params) => $params[0] === 'OC_ACTIVITY' || $params[0] === 'OC_ACTIVITY_MQ'))
			->willReturn($result);

		$this->stats->getTableSizesInBytes();
	}

	public function testGetTableSizesFallsBackToNullForSQLite(): void {
		// Any platform class without MySQL or PostgreSQL in its name → null sizes
		$platform = $this->createMock(AbstractPlatform::class);

		$this->connection->method('getDatabasePlatform')->willReturn($platform);

		$sizes = $this->stats->getTableSizesInBytes();

		$this->assertNull($sizes['activity']);
		$this->assertNull($sizes['activity_mq']);
	}

	public function testGetTableSizesCatchesExceptionAndReturnsNull(): void {
		$platform = $this->createMock(MySQLPlatform::class);
		$this->connection->method('getDatabasePlatform')->willReturn($platform);

		$this->connection->method('getQueryBuilder')
			->willThrowException(new \RuntimeException('DB error'));

		$this->logger->expects($this->once())->method('warning');

		$sizes = $this->stats->getTableSizesInBytes();

		$this->assertNull($sizes['activity']);
		$this->assertNull($sizes['activity_mq']);
	}

	// ── getRetentionSuggestion ──────────────────────────────────────────────

	public static function dataRetentionSuggestion(): array {
		return [
			'all null sizes returns null' => [
				['activity' => null, 'activity_mq' => null],
				365,
				null,
			],
			'small tables returns null' => [
				['activity' => 500 * 1024 * 1024, 'activity_mq' => 100 * 1024 * 1024],
				365,
				null,
			],
			'admin already below default returns null' => [
				['activity' => 15 * 1024 * 1024 * 1024, 'activity_mq' => 0],
				180,
				null,
			],
			'over 1 GB suggests 180 days' => [
				['activity' => 1 * 1024 * 1024 * 1024 + 1, 'activity_mq' => 0],
				365,
				180,
			],
			'over 5 GB suggests 90 days' => [
				['activity' => 5 * 1024 * 1024 * 1024 + 1, 'activity_mq' => 0],
				365,
				90,
			],
			'over 10 GB suggests 30 days' => [
				['activity' => 10 * 1024 * 1024 * 1024 + 1, 'activity_mq' => 0],
				365,
				30,
			],
			'threshold already below suggested returns null' => [
				['activity' => 1 * 1024 * 1024 * 1024 + 1, 'activity_mq' => 0],
				90,
				null,
			],
		];
	}

	#[DataProvider('dataRetentionSuggestion')]
	public function testGetRetentionSuggestion(array $sizes, int $currentDays, ?int $expectedSuggested): void {
		$this->config->method('getSystemValue')
			->with('activity_expire_days', DatabaseStats::DEFAULT_RETENTION_DAYS)
			->willReturn((string)$currentDays);

		$result = $this->stats->getRetentionSuggestion($sizes);

		if ($expectedSuggested === null) {
			$this->assertNull($result);
		} else {
			$this->assertNotNull($result);
			$this->assertSame($expectedSuggested, $result['suggested_days']);
			$this->assertSame($currentDays, $result['current_days']);
			$this->assertSame('activity_expire_days', $result['config_key']);
			$this->assertGreaterThan(0, $result['total_bytes']);
		}
	}

	// ── isDedicatedConnection ───────────────────────────────────────────────

	public static function dataIsDedicatedConnection(): array {
		return [
			'no keys set' => [[], false],
			'dbuser set' => [['activity_dbuser' => 'actuser'], true],
			'dbname set' => [['activity_dbname' => 'actdb'], true],
			'dbhost set' => [['activity_dbhost' => 'db.host'], true],
			'dbdriveroptions set' => [['activity_dbdriveroptions' => ['key' => 'val']], true],
		];
	}

	#[DataProvider('dataIsDedicatedConnection')]
	public function testIsDedicatedConnection(array $configValues, bool $expected): void {
		$this->config->method('getSystemValue')
			->willReturnCallback(static function (string $key, mixed $default) use ($configValues): mixed {
				return $configValues[$key] ?? $default;
			});

		$this->assertSame($expected, $this->stats->isDedicatedConnection());
	}
}
