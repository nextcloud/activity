<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IConfig;
use OCP\IDBConnection;
use Psr\Log\LoggerInterface;

/**
 * Reports on-disk size of the activity tables for the admin UI, and turns
 * that into a retention suggestion when the tables get large.
 *
 * The sizes are queried against whichever connection the activity app is
 * actually using — i.e. the dedicated connection if `activity_db*` is
 * configured, otherwise the main Nextcloud DB.
 */
class DatabaseStats {
	/** Tables owned by the activity app. Keep in sync with lib/Migration. */
	public const TABLES = ['activity', 'activity_mq'];

	/** Default retention used when `activity_expire_days` is unset (mirrors {@see \OCA\Activity\BackgroundJob\ExpireActivities}). */
	public const DEFAULT_RETENTION_DAYS = 365;

	/**
	 * Threshold pairs `[bytes, suggestedDays]`, biggest first. The first row
	 * whose `bytes` the actual size exceeds is the recommendation. Values are
	 * intentionally conservative — the admin always opts in by editing
	 * config.php, we never auto-apply.
	 */
	private const RETENTION_THRESHOLDS = [
		[10 * 1024 * 1024 * 1024,  30], // > 10 GB → 30 days
		[ 5 * 1024 * 1024 * 1024,  90], // >  5 GB → 90 days
		[ 1 * 1024 * 1024 * 1024, 180], // >  1 GB → 180 days
	];

	public function __construct(
		private IDBConnection $connection,
		private IConfig $config,
		private LoggerInterface $logger,
	) {
	}

	/**
	 * Returns total size in bytes for each activity table, or null per table
	 * if the engine doesn't expose per-table size cheaply (e.g. SQLite).
	 *
	 * Result shape: ['activity' => 12345, 'activity_mq' => 678] or
	 *               ['activity' => null, 'activity_mq' => null] on SQLite.
	 *
	 * @return array<string, int|null>
	 */
	public function getTableSizesInBytes(): array {
		$platform = $this->connection->getDatabasePlatform();

		try {
			if ($platform instanceof MySQLPlatform) {
				return $this->getSizesMySQL();
			}
			if ($platform instanceof PostgreSQLPlatform) {
				return $this->getSizesPostgres();
			}
			if ($platform instanceof OraclePlatform) {
				return $this->getSizesOracle();
			}
		} catch (\Throwable $e) {
			$this->logger->warning('Failed to query activity table sizes', ['exception' => $e]);
		}

		return array_fill_keys(self::TABLES, null);
	}

	/**
	 * Returns the suggested retention in days when the activity tables have
	 * grown past one of the {@see self::RETENTION_THRESHOLDS}, or null when
	 * either size info isn't available, the tables are still small, or the
	 * admin has already tuned `activity_expire_days` below the default
	 * (assume they know what they want).
	 *
	 * Result shape:
	 *   [
	 *     'total_bytes'    => 12_345_678,         // current total
	 *     'current_days'   => 365,                // value in effect today
	 *     'suggested_days' => 90,                 // what we recommend
	 *     'config_key'     => 'activity_expire_days',
	 *   ]
	 */
	public function getRetentionSuggestion(array $sizes): ?array {
		$values = array_filter($sizes, static fn ($b) => $b !== null);
		if ($values === []) {
			return null;
		}
		$totalBytes = array_sum($values);

		$current = (int)$this->config->getSystemValue('activity_expire_days', self::DEFAULT_RETENTION_DAYS);
		if ($current < self::DEFAULT_RETENTION_DAYS) {
			// Admin has already turned retention down — don't second-guess them.
			return null;
		}

		foreach (self::RETENTION_THRESHOLDS as [$threshold, $days]) {
			if ($totalBytes > $threshold && $days < $current) {
				return [
					'total_bytes' => $totalBytes,
					'current_days' => $current,
					'suggested_days' => $days,
					'config_key' => 'activity_expire_days',
				];
			}
		}
		return null;
	}

	/**
	 * The dedicated activity DB is in use when any activity_db* key is set;
	 * mirrors the check in {@see \OCA\Activity\AppInfo\Application::register}.
	 */
	public function isDedicatedConnection(): bool {
		foreach (['dbuser', 'dbpassword', 'dbname', 'dbhost', 'dbport', 'dbdriveroptions'] as $key) {
			if ($this->config->getSystemValue('activity_' . $key, null) !== null) {
				return true;
			}
		}
		return false;
	}

	/** @return array<string, int|null> */
	private function getSizesMySQL(): array {
		$qb = $this->connection->getQueryBuilder();
		$qb->select('table_name', 'data_length', 'index_length')
			->from('information_schema.tables')
			->where($qb->expr()->eq('table_schema', $qb->createFunction('DATABASE()')))
			->andWhere($qb->expr()->in('table_name', $qb->createNamedParameter(
				$this->prefixedTables(),
				IQueryBuilder::PARAM_STR_ARRAY,
			)));

		$result = $qb->executeQuery();
		$sizes = array_fill_keys(self::TABLES, null);
		while ($row = $result->fetch()) {
			$bare = $this->stripPrefix((string)$row['table_name']);
			if ($bare !== null) {
				$sizes[$bare] = (int)$row['data_length'] + (int)$row['index_length'];
			}
		}
		$result->closeCursor();
		return $sizes;
	}

	/** @return array<string, int|null> */
	private function getSizesPostgres(): array {
		$sizes = array_fill_keys(self::TABLES, null);
		foreach (self::TABLES as $table) {
			$qualified = $this->prefix() . $table;
			// to_regclass returns NULL when the relation doesn't exist, avoiding an exception.
			// pg_total_relation_size includes table + indexes + toast.
			$sql = 'SELECT pg_total_relation_size(to_regclass(?)) AS size_bytes';
			$result = $this->connection->executeQuery($sql, [$qualified]);
			$row = $result->fetch();
			$result->closeCursor();
			if ($row !== false && $row['size_bytes'] !== null) {
				$sizes[$table] = (int)$row['size_bytes'];
			}
		}
		return $sizes;
	}

	/** @return array<string, int|null> */
	private function getSizesOracle(): array {
		$sizes = array_fill_keys(self::TABLES, null);
		foreach (self::TABLES as $table) {
			// Oracle stores unquoted identifiers in uppercase.
			$qualified = strtoupper($this->prefix() . $table);
			// Sum the table segment plus all index segments belonging to it.
			$sql = 'SELECT SUM(s.bytes) AS size_bytes
			        FROM user_segments s
			        WHERE s.segment_name = ?
			           OR (s.segment_type = \'INDEX\'
			               AND s.segment_name IN (
			                   SELECT index_name FROM user_indexes WHERE table_name = ?
			               ))';
			$result = $this->connection->executeQuery($sql, [$qualified, $qualified]);
			$row = $result->fetch();
			$result->closeCursor();
			if ($row !== false && $row['size_bytes'] !== null) {
				$sizes[$table] = (int)$row['size_bytes'];
			}
		}
		return $sizes;
	}

	/** @return string[] */
	private function prefixedTables(): array {
		$prefix = $this->prefix();
		return array_map(static fn (string $t): string => $prefix . $t, self::TABLES);
	}

	private function prefix(): string {
		return (string)$this->config->getSystemValue(
			'activity_dbtableprefix',
			$this->config->getSystemValue('dbtableprefix', 'oc_'),
		);
	}

	private function stripPrefix(string $table): ?string {
		$prefix = $this->prefix();
		if ($prefix !== '' && str_starts_with($table, $prefix)) {
			$table = substr($table, strlen($prefix));
		}
		return in_array($table, self::TABLES, true) ? $table : null;
	}
}
