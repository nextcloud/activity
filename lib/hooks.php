<?php
/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity;

use OCA\Activity\AppInfo\Application;
use OCP\IDBConnection;

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
		$activityData = $app->getContainer()->query('ActivityData');
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
}
