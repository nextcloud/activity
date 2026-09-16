<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Migration;

use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version2006Date20170808154933 extends SimpleMigrationStep {
	/**
	 * @param IOutput $output
	 * @param \Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 * @since 13.0.0
	 */
	#[\Override]
	public function changeSchema(IOutput $output, \Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable('activity')) {
			$table = $schema->createTable('activity');
			$table->addColumn('activity_id', Types::BIGINT, [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 20,
			]);
			$table->addColumn('timestamp', Types::INTEGER, [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('priority', Types::INTEGER, [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('type', Types::STRING, [
				'notnull' => false,
				'length' => 255,
			]);
			$table->addColumn('user', Types::STRING, [
				'notnull' => false,
				'length' => 64,
			]);
			$table->addColumn('affecteduser', Types::STRING, [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('app', Types::STRING, [
				'notnull' => true,
				'length' => 32,
			]);
			$table->addColumn('subject', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('subjectparams', Types::TEXT, [
				'notnull' => true,
			]);
			$table->addColumn('message', Types::STRING, [
				'notnull' => false,
				'length' => 255,
			]);
			$table->addColumn('messageparams', Types::TEXT, [
				'notnull' => false,
			]);
			$table->addColumn('file', Types::STRING, [
				'notnull' => false,
				'length' => 4000,
			]);
			$table->addColumn('link', Types::STRING, [
				'notnull' => false,
				'length' => 4000,
			]);
			$table->addColumn('object_type', Types::STRING, [
				'notnull' => false,
				'length' => 255,
			]);
			$table->addColumn('object_id', Types::BIGINT, [
				'notnull' => true,
				'length' => 20,
				'default' => 0,
			]);
			$table->setPrimaryKey(['activity_id']);
			$table->addIndex(['affecteduser', 'timestamp'], 'activity_user_time');
			$table->addIndex(['affecteduser', 'user', 'timestamp'], 'activity_filter_by');
			// FIXME Fixed install, see Version2006Date20170808155040: $table->addIndex(['affecteduser', 'app', 'timestamp'], 'activity_filter_app');
			$table->addIndex(['affecteduser', 'type', 'app', 'timestamp'], 'activity_filter');
			$table->addIndex(['object_type', 'object_id'], 'activity_object');
			$table->addIndex(['affecteduser', 'object_type', 'object_id', 'timestamp'], 'activity_object_user');
		}

		if (!$schema->hasTable('activity_mq')) {
			$table = $schema->createTable('activity_mq');
			$table->addColumn('mail_id', Types::BIGINT, [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 20,
			]);
			$table->addColumn('amq_timestamp', Types::INTEGER, [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('amq_latest_send', Types::INTEGER, [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('amq_type', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('amq_affecteduser', Types::STRING, [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('amq_appid', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('amq_subject', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('amq_subjectparams', Types::TEXT, [
				'notnull' => true,
			]);
			$table->setPrimaryKey(['mail_id']);
			$table->addIndex(['amq_affecteduser'], 'amp_user');
			$table->addIndex(['amq_latest_send'], 'amp_latest_send_time');
			$table->addIndex(['amq_timestamp'], 'amp_timestamp_time');
		}
		return $schema;
	}
}
