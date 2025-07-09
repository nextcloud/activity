<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Migration;

use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version2006Date20170808155040 extends SimpleMigrationStep {
	/**
	 * @param IOutput $output
	 * @param \Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 * @since 13.0.0
	 */
	#[\Override]
	public function changeSchema(IOutput $output, \Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		/**
		 * FIXME To prevent slowness on update we don't change the index.
		 * FIXME Anyone complaining can manually update it.
		 *
		 * $schema = $schemaClosure();
		 *
		 * $table = $schema->getTable('activity');
		 * $table->dropIndex('activity_filter_app');
		 * $table->addIndex(['affecteduser', 'type', 'app', 'timestamp'], 'activity_filter');
		 *
		 * return $schema;
		 */
		return null;
	}
}
