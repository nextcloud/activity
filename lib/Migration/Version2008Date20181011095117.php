<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Migration;

use Closure;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version2008Date20181011095117 extends SimpleMigrationStep {
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

		try {
			$table = $schema->getTable('activity_mq');
		} catch (SchemaException $e) {
			return null;
		}

		$table->addColumn('object_type', Types::STRING, [
			'notnull' => false,
			'length' => 255,
		]);
		$table->addColumn('object_id', Types::BIGINT, [
			'notnull' => true,
			'length' => 20,
			'default' => 0,
		]);

		return $schema;
	}
}
