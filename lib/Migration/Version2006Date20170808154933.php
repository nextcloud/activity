<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Migration;

use Doctrine\DBAL\Types\Types;
use OCP\DB\ISchemaWrapper;
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
			$table->addColumn('timestamp', 'integer', [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('priority', 'integer', [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('type', 'string', [
				'notnull' => false,
				'length' => 255,
			]);
			$table->addColumn('user', 'string', [
				'notnull' => false,
				'length' => 64,
			]);
			$table->addColumn('affecteduser', 'string', [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('app', 'string', [
				'notnull' => true,
				'length' => 32,
			]);
			$table->addColumn('subject', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('subjectparams', 'text', [
				'notnull' => true,
			]);
			$table->addColumn('message', 'string', [
				'notnull' => false,
				'length' => 255,
			]);
			$table->addColumn('messageparams', 'text', [
				'notnull' => false,
			]);
			$table->addColumn('file', 'string', [
				'notnull' => false,
				'length' => 4000,
			]);
			$table->addColumn('link', 'string', [
				'notnull' => false,
				'length' => 4000,
			]);
			$table->addColumn('object_type', 'string', [
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
		}

		if (!$schema->hasTable('activity_mq')) {
			$table = $schema->createTable('activity_mq');
			$table->addColumn('mail_id', Types::BIGINT, [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 20,
			]);
			$table->addColumn('amq_timestamp', 'integer', [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('amq_latest_send', 'integer', [
				'notnull' => true,
				'length' => 4,
				'default' => 0,
			]);
			$table->addColumn('amq_type', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('amq_affecteduser', 'string', [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('amq_appid', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('amq_subject', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('amq_subjectparams', 'text', [
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
