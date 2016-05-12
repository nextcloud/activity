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

use OCP\DB\QueryBuilder\IQueryBuilder;

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

// Delete notification settings that can not be changed, so we correctly fall
// back to the default value.
$deleteNotificationTypes = [];
/**
 * For now we need to do it manually because the other apps might not be loaded
 * on the update.
$notificationTypes = \OC::$server->getActivityManager()->getNotificationTypes('en');
foreach ($notificationTypes as $type => $data) {
	if (is_array($data) && isset($data['methods'])) {
		if (!in_array(\OCP\Activity\IExtension::METHOD_MAIL, $data['methods'])) {
			$deleteNotificationTypes[] = 'notify_email_' . $type;
		}
		if (!in_array(\OCP\Activity\IExtension::METHOD_STREAM, $data['methods'])) {
			$deleteNotificationTypes[] = 'notify_stream_' . $type;
		}
	}
}
 */
$deleteNotificationTypes[] = 'notify_stream_comments';
$deleteNotificationTypes[] = 'notify_email_files_favorites';

if (!empty($deleteNotificationTypes)) {
	$query = $connection->getQueryBuilder();
	$query->delete('preferences')
		->where($query->expr()->eq('appid', $query->createNamedParameter('activity')))
		->andWhere($query->expr()->in('configkey', $query->createNamedParameter($deleteNotificationTypes, IQueryBuilder::PARAM_STR_ARRAY)));
	$query->execute();
}
