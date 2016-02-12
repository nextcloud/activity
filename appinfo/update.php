<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */
$installedVersion = \OC::$server->getConfig()->getAppValue('activity', 'installed_version');
$connection = \OC::$server->getDatabaseConnection();

if (version_compare($installedVersion, '1.2.2', '<')) {
	$mistakes = [
		['*PREFIX*activity', 'subjectparams'],
		['*PREFIX*activity', 'messageparams'],
		['*PREFIX*activity_mq', 'amq_subjectparams'],
	];

	foreach ($mistakes as $entry) {
		list($table, $column) = $entry;

		$numEntries = $connection->executeUpdate(
			'DELETE FROM `' . $table . '` WHERE `' . $column . "` NOT LIKE '%]' AND `" . $column . "` NOT LIKE '%}'"
		);

		\OC::$server->getLogger()->debug('Deleting ' . $numEntries . ' activities with a broken ' . $column . ' value.', ['app' => 'acitivity']);
	}
}

if (version_compare($installedVersion, '1.2.2', '<')) {
	$connection->executeUpdate('UPDATE `*PREFIX*activity` SET `app` = ? WHERE `type` = ?', array('files_sharing', 'shared'));
	$connection->executeUpdate('UPDATE `*PREFIX*activity_mq` SET `amq_appid` = ? WHERE `amq_type` = ?', array('files_sharing', 'shared'));
}

// Cron job for sending emails and pruning the activity list
\OC::$server->getJobList()->add('OCA\Activity\BackgroundJob\EmailNotification');
\OC::$server->getJobList()->add('OCA\Activity\BackgroundJob\ExpireActivities');
