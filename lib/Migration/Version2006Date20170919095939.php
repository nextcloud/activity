<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Migration;

use OCP\DB\ISchemaWrapper;
use OCP\Migration\BigIntMigration;
use OCP\Migration\IOutput;

class Version2006Date20170919095939 extends BigIntMigration {
	/**
	 * @return array Returns an array with the following structure
	 *               ['table1' => ['column1', 'column2'], ...]
	 * @since 13.0.0
	 */
	#[\Override]
	protected function getColumnsByTable() {
		return [
			'activity' => ['activity_id', 'object_id'],
			'activity_mq' => ['mail_id'],
		];
	}

	/**
	 * @param IOutput $output
	 * @param \Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 * @since 13.0.0
	 */
	#[\Override]
	public function changeSchema(IOutput $output, \Closure $schemaClosure, array $options) {
		/**
		 * FIXME To prevent slowness on update we don't change the schema.
		 * FIXME Instead it can be updated with ./occ db:convert-filecache-bigint
		 * parent::changeSchema($output, $schemaClosure, $options);
		 */

		return null;
	}
}
