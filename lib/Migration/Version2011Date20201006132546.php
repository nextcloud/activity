<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2020 Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
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
