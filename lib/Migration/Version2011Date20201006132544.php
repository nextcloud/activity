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
