<?php

/**
 * ownCloud - Activity App
 *
 * @author Frank Karlitschek
 * @author Joas Schilling
 * @copyright 2013 Frank Karlitschek frank@owncloud.org
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity;

use OCP\Activity\IEvent;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\IDBConnection;
use OCP\IL10N;
use OCP\IUser;
use OCP\IUserSession;

/**
 * @brief Class for managing the data in the activities
 */
class Data {
	/** @var IManager */
	protected $activityManager;

	/** @var IDBConnection */
	protected $connection;

	/** @var IUserSession */
	protected $userSession;

	/**
	 * @param IManager $activityManager
	 * @param IDBConnection $connection
	 * @param IUserSession $userSession
	 */
	public function __construct(IManager $activityManager, IDBConnection $connection, IUserSession $userSession) {
		$this->activityManager = $activityManager;
		$this->connection = $connection;
		$this->userSession = $userSession;
	}

	protected $notificationTypes = array();

	/**
	 * @param IL10N $l
	 * @return array Array "stringID of the type" => "translated string description for the setting"
	 * 				or Array "stringID of the type" => [
	 * 					'desc' => "translated string description for the setting"
	 * 					'methods' => [\OCP\Activity\IExtension::METHOD_*],
	 * 				]
	 */
	public function getNotificationTypes(IL10N $l) {
		if (isset($this->notificationTypes[$l->getLanguageCode()])) {
			return $this->notificationTypes[$l->getLanguageCode()];
		}

		// Allow apps to add new notification types
		$notificationTypes = $this->activityManager->getNotificationTypes($l->getLanguageCode());
		$this->notificationTypes[$l->getLanguageCode()] = $notificationTypes;
		return $notificationTypes;
	}

	/**
	 * Send an event into the activity stream
	 *
	 * @param IEvent $event
	 * @return bool
	 */
	public function send(IEvent $event) {
		if ($event->getAffectedUser() === '' || $event->getAffectedUser() === null) {
			return false;
		}

		// store in DB
		$queryBuilder = $this->connection->getQueryBuilder();
		$queryBuilder->insert('activity')
			->values([
				'app' => $queryBuilder->createParameter('app'),
				'subject' => $queryBuilder->createParameter('subject'),
				'subjectparams' => $queryBuilder->createParameter('subjectparams'),
				'message' => $queryBuilder->createParameter('message'),
				'messageparams' => $queryBuilder->createParameter('messageparams'),
				'file' => $queryBuilder->createParameter('object_name'),
				'link' => $queryBuilder->createParameter('link'),
				'user' => $queryBuilder->createParameter('user'),
				'affecteduser' => $queryBuilder->createParameter('affecteduser'),
				'timestamp' => $queryBuilder->createParameter('timestamp'),
				'priority' => $queryBuilder->createParameter('priority'),
				'type' => $queryBuilder->createParameter('type'),
				'object_type' => $queryBuilder->createParameter('object_type'),
				'object_id' => $queryBuilder->createParameter('object_id'),
			])
			->setParameters([
				'app' => $event->getApp(),
				'type' => $event->getType(),
				'affecteduser' => $event->getAffectedUser(),
				'user' => $event->getAuthor(),
				'timestamp' => (int) $event->getTimestamp(),
				'subject' => $event->getSubject(),
				'subjectparams' => json_encode($event->getSubjectParameters()),
				'message' => $event->getMessage(),
				'messageparams' => json_encode($event->getMessageParameters()),
				'priority' => IExtension::PRIORITY_MEDIUM,
				'object_type' => $event->getObjectType(),
				'object_id' => (int) $event->getObjectId(),
				'object_name' => $event->getObjectName(),
				'link' => $event->getLink(),
			])
			->execute();

		return true;
	}

	/**
	 * Send an event as email
	 *
	 * @param IEvent $event
	 * @param int    $latestSendTime Activity $timestamp + batch setting of $affectedUser
	 * @return bool
	 */
	public function storeMail(IEvent $event, $latestSendTime) {
		if ($event->getAffectedUser() === '' || $event->getAffectedUser() === null) {
			return false;
		}

		// store in DB
		$queryBuilder = $this->connection->getQueryBuilder();
		$queryBuilder->insert('activity_mq')
			->values([
				'amq_appid' => $queryBuilder->createParameter('app'),
				'amq_subject' => $queryBuilder->createParameter('subject'),
				'amq_subjectparams' => $queryBuilder->createParameter('subjectparams'),
				'amq_affecteduser' => $queryBuilder->createParameter('affecteduser'),
				'amq_timestamp' => $queryBuilder->createParameter('timestamp'),
				'amq_type' => $queryBuilder->createParameter('type'),
				'amq_latest_send' => $queryBuilder->createParameter('latest_send'),
			])
			->setParameters([
				'app' => $event->getApp(),
				'subject' => $event->getSubject(),
				'subjectparams' => json_encode($event->getSubjectParameters()),
				'affecteduser' => $event->getAffectedUser(),
				'timestamp' => (int) $event->getTimestamp(),
				'type' => $event->getType(),
				'latest_send' => $latestSendTime,
			])
			->execute();

		return true;
	}

	/**
	 * @brief Read a list of events from the activity stream
	 * @param GroupHelper $groupHelper Allows activities to be grouped
	 * @param UserSettings $userSettings Gets the settings of the user
	 * @param int $start The start entry
	 * @param int $count The number of statements to read
	 * @param string $filter Filter the activities
	 * @param string $user User for whom we display the stream
	 * @param string $objectType
	 * @param int $objectId
	 * @return array
	 */
	public function read(GroupHelper $groupHelper, UserSettings $userSettings, $start, $count, $filter = 'all', $user = '', $objectType = '', $objectId = 0) {
		// get current user
		if ($user === '') {
			$user = $this->userSession->getUser();
			if ($user instanceof IUser) {
				$user = $user->getUID();
			} else {
				// No user given and not logged in => no activities
				return [];
			}
		}
		$groupHelper->setUser($user);

		$enabledNotifications = $userSettings->getNotificationTypes($user, 'stream');
		$enabledNotifications = $this->activityManager->filterNotificationTypes($enabledNotifications, $filter);
		$parameters = array_unique($enabledNotifications);

		// We don't want to display any activities
		if (empty($parameters)) {
			return array();
		}

		$placeholders = implode(',', array_fill(0, sizeof($parameters), '?'));
		$limitActivities = " AND `type` IN (" . $placeholders . ")";
		array_unshift($parameters, $user);

		if ($filter === 'self') {
			$limitActivities .= ' AND `user` = ?';
			$parameters[] = $user;
		} else if ($filter === 'by' || $filter === 'all' && !$userSettings->getUserSetting($user, 'setting', 'self')) {
			$limitActivities .= ' AND `user` <> ?';
			$parameters[] = $user;
		} else if ($filter === 'filter') {
			if (!$userSettings->getUserSetting($user, 'setting', 'self')) {
				$limitActivities .= ' AND `user` <> ?';
				$parameters[] = $user;
			}
			$limitActivities .= ' AND `object_type` = ?';
			$parameters[] = $objectType;
			$limitActivities .= ' AND `object_id` = ?';
			$parameters[] = $objectId;
		}

		list($condition, $params) = $this->activityManager->getQueryForFilter($filter);
		if (!is_null($condition)) {
			$limitActivities .= ' ';
			$limitActivities .= $condition;
			if (is_array($params)) {
				$parameters = array_merge($parameters, $params);
			}
		}

		return $this->getActivities($count, $start, $limitActivities, $parameters, $groupHelper);
	}

	/**
	 * Process the result and return the activities
	 *
	 * @param int $count
	 * @param int $start
	 * @param string $limitActivities
	 * @param array $parameters
	 * @param \OCA\Activity\GroupHelper $groupHelper
	 * @return array
	 */
	protected function getActivities($count, $start, $limitActivities, $parameters, GroupHelper $groupHelper) {
		$query = $this->connection->prepare(
			'SELECT * '
			. ' FROM `*PREFIX*activity` '
			. ' WHERE `affecteduser` = ? ' . $limitActivities
			. ' ORDER BY `timestamp` DESC',
			$count, $start);
		$query->execute($parameters);

		while ($row = $query->fetch()) {
			$groupHelper->addActivity($row);
		}
		$query->closeCursor();

		return $groupHelper->getActivities();
	}

	/**
	 * Verify that the filter is valid
	 *
	 * @param string $filterValue
	 * @return string
	 */
	public function validateFilter($filterValue) {
		if (!isset($filterValue)) {
			return 'all';
		}

		switch ($filterValue) {
			case 'by':
			case 'self':
			case 'all':
			case 'filter':
				return $filterValue;
			default:
				if ($this->activityManager->isFilterValid($filterValue)) {
					return $filterValue;
				}
				return 'all';
		}
	}

	/**
	 * Delete old events
	 *
	 * @param int $expireDays Minimum 1 day
	 * @return null
	 */
	public function expire($expireDays = 365) {
		$ttl = (60 * 60 * 24 * max(1, $expireDays));

		$timelimit = time() - $ttl;
		$this->deleteActivities(array(
			'timestamp' => array($timelimit, '<'),
		));
	}

	/**
	 * Delete activities that match certain conditions
	 *
	 * @param array $conditions Array with conditions that have to be met
	 *                      'field' => 'value'  => `field` = 'value'
	 *    'field' => array('value', 'operator') => `field` operator 'value'
	 * @return null
	 */
	public function deleteActivities($conditions) {
		$sqlWhere = '';
		$sqlParameters = $sqlWhereList = array();
		foreach ($conditions as $column => $comparison) {
			$sqlWhereList[] = " `$column` " . ((is_array($comparison) && isset($comparison[1])) ? $comparison[1] : '=') . ' ? ';
			$sqlParameters[] = (is_array($comparison)) ? $comparison[0] : $comparison;
		}

		if (!empty($sqlWhereList)) {
			$sqlWhere = ' WHERE ' . implode(' AND ', $sqlWhereList);
		}

		$query = $this->connection->prepare(
			'DELETE FROM `*PREFIX*activity`' . $sqlWhere);
		$query->execute($sqlParameters);
	}
}
