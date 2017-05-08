<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
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

namespace OCA\Activity;

use OCA\Activity\AppInfo\Application;
use OCP\IDBConnection;
use OCP\Util;

/**
 * Handles the stream and mail queue of a user when he is being deleted
 */
class Hooks {
	/**
	 * Delete remaining activities and emails when a user is deleted
	 *
	 * @param array $params The hook params
	 */
	static public function deleteUser($params) {
		$connection = \OC::$server->getDatabaseConnection();
		self::deleteUserStream($params['uid']);
		self::deleteUserMailQueue($connection, $params['uid']);
	}

	/**
	 * Delete all items of the stream
	 *
	 * @param string $user
	 */
	static protected function deleteUserStream($user) {
		// Delete activity entries
		$app = new Application();
		/** @var Data $activityData */
		$activityData = $app->getContainer()->query(Data::class);
		$activityData->deleteActivities(array('affecteduser' => $user));
	}

	/**
	 * Delete all mail queue entries
	 *
	 * @param IDBConnection $connection
	 * @param string $user
	 */
	static protected function deleteUserMailQueue(IDBConnection $connection, $user) {
		// Delete entries from mail queue
		$queryBuilder = $connection->getQueryBuilder();

		$queryBuilder->delete('activity_mq')
			->where($queryBuilder->expr()->eq('amq_affecteduser', $queryBuilder->createParameter('user')))
			->setParameter('user', $user);
		$queryBuilder->execute();
	}

	static public function setDefaultsForUser($params) {
		$config = \OC::$server->getConfig();
		if ($config->getUserValue($params['uid'], 'activity','notify_setting_batchtime', null) !== null) {
			// Already has settings
			return;
		}

		foreach ($config->getAppKeys('activity') as $key) {
			if (strpos($key, 'notify_') !== 0) {
				continue;
			}

			$config->setUserValue(
				$params['uid'],
				'activity',
				$key,
				$config->getAppValue('activity', $key)
			);
		}
	}

	/**
	 * Load additional scripts when the files app is visible
	 */
	public static function onLoadFilesAppScripts() {
		Util::addStyle('activity', 'style');
		Util::addScript('activity', 'activity-sidebar');
	}
}
