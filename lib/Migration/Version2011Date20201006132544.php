<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2020 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Activity\Migration;

use Closure;
use Doctrine\DBAL\Types\Type;
use OCP\DB\ISchemaWrapper;
use OCP\IDBConnection;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version2011Date20201006132544 extends SimpleMigrationStep {
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

		$column = $table->getColumn('amq_appid');
		$column->setType(Type::getType('string'));
		$column->setNotnull(true);
		$column->setLength(32);

		$column = $table->getColumn('amq_subjectparams');
		// Can't switch from Long to clob on Oracle, so we need an intermediate column
		if ($column->getType() !== Type::getType('text')) {
			$table->addColumn('amq_subjectparams2', 'text', [
				'notnull' => false,
			]);
		}

		return $schema;
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

		if (!$schema->getTable('activity_mq')->hasColumn('amq_subjectparams2')) {
			return;
		}

		$query = $this->connection->getQueryBuilder();
		$query->update('activity_mq')
			->set('amq_subjectparams2', 'amq_subjectparams');
		$query->execute();
	}
}
