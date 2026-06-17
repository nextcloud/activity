<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Widen the `amq_affecteduser` index on `activity_mq` to also cover
 * `amq_latest_send`.
 *
 * The email digest cron (`MailQueueHandler::getAffectedUsers()`) runs on every
 * cron tick and does
 *   SELECT amq_affecteduser, MIN(amq_latest_send)
 *   FROM activity_mq WHERE amq_latest_send < ? GROUP BY amq_affecteduser
 * The previous `amp_user` index only contained `amq_affecteduser`, so the
 * aggregate forced a full index scan plus a temporary table to resolve the
 * GROUP BY. Including `amq_latest_send` lets MariaDB/MySQL resolve the MIN()
 * with a loose index scan ("Using index for group-by"), reading one entry per
 * user instead of the whole queue.
 *
 * `amp_user` is a strict prefix of the new index, so it becomes redundant and
 * is dropped to avoid carrying two overlapping indexes.
 */
class Version8000Date20260603120000 extends SimpleMigrationStep {
	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	#[\Override]
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable('activity_mq')) {
			return null;
		}

		$table = $schema->getTable('activity_mq');

		if (!$table->hasIndex('amp_user_send')) {
			$table->addIndex(['amq_affecteduser', 'amq_latest_send'], 'amp_user_send');
		}

		if ($table->hasIndex('amp_user')) {
			$table->dropIndex('amp_user');
		}

		return $schema;
	}
}
