<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests\Migration;

use Doctrine\DBAL\Schema\Table;
use OCA\Activity\Migration\Version8000Date20260603120000;
use OCA\Activity\Tests\TestCase;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @see Version8000Date20260603120000
 */
class Version8000Date20260603120000Test extends TestCase {
	protected IOutput&MockObject $output;
	protected Version8000Date20260603120000 $migration;

	protected function setUp(): void {
		parent::setUp();

		$this->output = $this->createMock(IOutput::class);
		$this->migration = new Version8000Date20260603120000();
	}

	/**
	 * On a queue that still has the legacy single-column `amp_user` index the
	 * migration adds the covering composite index and drops the now-redundant one.
	 */
	public function testAddsCompositeIndexAndDropsRedundantOne(): void {
		$table = $this->createMock(Table::class);
		$table->method('hasIndex')
			->willReturnMap([
				['amp_user_send', false],
				['amp_user', true],
			]);
		$table->expects($this->once())
			->method('addIndex')
			->with(['amq_affecteduser', 'amq_latest_send'], 'amp_user_send');
		$table->expects($this->once())
			->method('dropIndex')
			->with('amp_user');

		$schema = $this->getSchemaMock($table);

		$result = $this->migration->changeSchema($this->output, fn (): ISchemaWrapper => $schema, []);
		$this->assertSame($schema, $result, 'The schema must be returned when it was changed');
	}

	/**
	 * Running the migration again (composite index present, legacy index gone)
	 * must be a no-op so re-runs / fresh installs are not touched.
	 */
	public function testIsIdempotentWhenAlreadyMigrated(): void {
		$table = $this->createMock(Table::class);
		$table->method('hasIndex')
			->willReturnMap([
				['amp_user_send', true],
				['amp_user', false],
			]);
		$table->expects($this->never())
			->method('addIndex');
		$table->expects($this->never())
			->method('dropIndex');

		$schema = $this->getSchemaMock($table);

		$result = $this->migration->changeSchema($this->output, fn (): ISchemaWrapper => $schema, []);
		$this->assertSame($schema, $result, 'Re-running must not add or drop the indexes again');
	}

	/**
	 * The mail queue table does not exist on every install (e.g. before its
	 * creation migration ran), so a missing table must be skipped gracefully.
	 */
	public function testSkipsWhenTableIsMissing(): void {
		$schema = $this->createMock(ISchemaWrapper::class);
		$schema->method('hasTable')
			->with('activity_mq')
			->willReturn(false);
		$schema->expects($this->never())
			->method('getTable');

		$result = $this->migration->changeSchema($this->output, fn (): ISchemaWrapper => $schema, []);
		$this->assertNull($result);
	}

	protected function getSchemaMock(Table&MockObject $table): ISchemaWrapper&MockObject {
		$schema = $this->createMock(ISchemaWrapper::class);
		$schema->method('hasTable')
			->with('activity_mq')
			->willReturn(true);
		$schema->method('getTable')
			->with('activity_mq')
			->willReturn($table);

		return $schema;
	}
}
