<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2020 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Activity\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\IDBConnection;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version2011Date20201006132546 extends SimpleMigrationStep {
	/** @var IDBConnection */
	protected $connection;

	public function __construct(IDBConnection $connection) {
		$this->connection = $connection;
	}

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	#[\Override]
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		$table = $schema->getTable('activity_mq');
		if (!$table->hasColumn('amq_subjectparams')) {
			$table->addColumn('amq_subjectparams', 'text', [
				'notnull' => false,
			]);
			return $schema;
		}

		return null;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 13.0.0
	 */
	#[\Override]
	public function postSchemaChange(IOutput $output, \Closure $schemaClosure, array $options): void {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->getTable('activity_mq')->hasColumn('amq_subjectparams2')
			|| !$schema->getTable('activity_mq')->hasColumn('amq_subjectparams')) {
			return;
		}

		$query = $this->connection->getQueryBuilder();
		$query->update('activity_mq')
			->set('amq_subjectparams', 'amq_subjectparams2');
		$query->execute();
	}
}
