<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Frank Karlitschek <frank@karlitschek.de>
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
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

use OCP\Activity\IEvent;
use OCP\Activity\IExtension;
use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\IL10N;

/**
 * @brief Class for managing the data in the activities
 */
class Data {
	/** @var IManager */
	protected $activityManager;

	/** @var IDBConnection */
	protected $connection;

	/**
	 * @param IManager $activityManager
	 * @param IDBConnection $connection
	 */
	public function __construct(IManager $activityManager, IDBConnection $connection) {
		$this->activityManager = $activityManager;
		$this->connection = $connection;
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
	public function storeMail(IEvent $event, int $latestSendTime): bool {
		$affectedUser = $event->getAffectedUser();
		if ($affectedUser === '' || $affectedUser === null) {
			return false;
		}

		$query = $this->connection->getQueryBuilder();
		$query->insert('activity_mq')
			->values([
				'amq_appid' => $query->createNamedParameter($event->getApp()),
				'amq_subject' => $query->createNamedParameter($event->getSubject()),
				'amq_subjectparams' => $query->createNamedParameter(json_encode($event->getSubjectParameters())),
				'amq_affecteduser' => $query->createNamedParameter($affectedUser),
				'amq_timestamp' => $query->createNamedParameter((int) $event->getTimestamp()),
				'amq_type' => $query->createNamedParameter($event->getType()),
				'amq_latest_send' => $query->createNamedParameter($latestSendTime),
				'object_type' => $query->createNamedParameter($event->getObjectType()),
				'object_id' => $query->createNamedParameter($event->getObjectId()),
			]);
		$query->execute();

		return true;
	}

	/**
	 * Read a list of events from the activity stream
	 *
	 * @param GroupHelper $groupHelper Allows activities to be grouped
	 * @param UserSettings $userSettings Gets the settings of the user
	 * @param string $user User for whom we display the stream
	 *
	 * @param int $since The integer ID of the last activity that has been seen.
	 * @param int $limit How many activities should be returned
	 * @param string $sort Should activities be given ascending or descending
	 *
	 * @param string $filter Filter the activities
	 * @param string $objectType Allows to filter the activities to a given object. May only appear together with $objectId
	 * @param int $objectId Allows to filter the activities to a given object. May only appear together with $objectType
	 *
	 * @return array
	 *
	 * @throws \OutOfBoundsException if the user (Code: 1) or the since (Code: 2) is invalid
	 * @throws \BadMethodCallException if the user has selected to display no types for this filter (Code: 3)
	 */
	public function get(GroupHelper $groupHelper, UserSettings $userSettings, $user, $since, $limit, $sort, $filter, $objectType = '', $objectId = 0) {
		// get current user
		if ($user === '') {
			throw new \OutOfBoundsException('Invalid user', 1);
		}

		$activeFilter = null;
		try {
			$activeFilter = $this->activityManager->getFilterById($filter);
		} catch (\InvalidArgumentException $e) {
			// Unknown filter => ignore and show all activities
		}

		$enabledNotifications = $userSettings->getNotificationTypes($user, 'stream');
		if ($activeFilter instanceof IFilter) {
			$enabledNotifications = $activeFilter->filterTypes($enabledNotifications);
		}
		$enabledNotifications = array_unique($enabledNotifications);

		// We don't want to display any activities
		if (empty($enabledNotifications)) {
			throw new \BadMethodCallException('No settings enabled', 3);
		}

		$query = $this->connection->getQueryBuilder();
		$query->select('*')
			->from('activity');

		$query->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)))
			->andWhere($query->expr()->in('type', $query->createNamedParameter($enabledNotifications, IQueryBuilder::PARAM_STR_ARRAY)));
		if ($filter === 'self') {
			$query->andWhere($query->expr()->eq('user', $query->createNamedParameter($user)));

		} else if ($filter === 'by') {
			$query->andWhere($query->expr()->neq('user', $query->createNamedParameter($user)));

		} else if ($filter === 'all' && !$userSettings->getUserSetting($user, 'setting', 'self')) {
			$query->andWhere($query->expr()->orX(
				$query->expr()->neq('user', $query->createNamedParameter($user)),
				$query->expr()->notIn('type', $query->createNamedParameter([
					'file_created',
					'file_changed',
					'file_deleted',
					'file_restored',
				], IQueryBuilder::PARAM_STR_ARRAY))
			));

		} else if ($filter === 'filter') {
			if (!$userSettings->getUserSetting($user, 'setting', 'self')) {
				$query->andWhere($query->expr()->orX(
					$query->expr()->neq('user', $query->createNamedParameter($user)),
					$query->expr()->notIn('type', $query->createNamedParameter([
						'file_created',
						'file_changed',
						'file_deleted',
						'file_restored',
					], IQueryBuilder::PARAM_STR_ARRAY))
				));
			}

			$query->andWhere($query->expr()->eq('object_type', $query->createNamedParameter($objectType)));
			$query->andWhere($query->expr()->eq('object_id', $query->createNamedParameter($objectId)));
		}

		if ($activeFilter instanceof IFilter) {
			$apps = $activeFilter->allowedApps();
			if (!empty($apps)) {
				$query->andWhere($query->expr()->in('app', $query->createNamedParameter($apps, IQueryBuilder::PARAM_STR_ARRAY)));
			}
		}

		if (
			$filter === 'files_favorites' ||
			(in_array($filter, ['all', 'by', 'self']) && $userSettings->getUserSetting($user, 'stream', 'files_favorites'))
		) {
			try {
				$favoriteFilter = $this->activityManager->getFilterById('files_favorites');
				/** @var \OCA\Files\Activity\Filter\Favorites $favoriteFilter */
				$favoriteFilter->filterFavorites($query);
			} catch (\InvalidArgumentException $e) {
			}
		}

		/**
		 * Order and specify the offset
		 */
		$sqlSort = ($sort === 'asc') ? 'ASC' : 'DESC';
		$headers = $this->setOffsetFromSince($query, $user, $since, $sqlSort);
		$query->orderBy('timestamp', $sqlSort)
			->addOrderBy('activity_id', $sqlSort);

		$query->setMaxResults($limit + 1);

		$result = $query->execute();
		$hasMore = false;
		while ($row = $result->fetch()) {
			if ($limit === 0) {
				$hasMore = true;
				break;
			}
			$headers['X-Activity-Last-Given'] = (int) $row['activity_id'];
			$groupHelper->addActivity($row);
			$limit--;
		}
		$result->closeCursor();

		return ['data' => $groupHelper->getActivities(), 'has_more' => $hasMore, 'headers' => $headers];
	}

	/**
	 * @param IQueryBuilder $query
	 * @param string $user
	 * @param int $since
	 * @param string $sort
	 *
	 * @return array Headers that should be set on the response
	 *
	 * @throws \OutOfBoundsException If $since is not owned by $user
	 */
	protected function setOffsetFromSince(IQueryBuilder $query, $user, $since, $sort) {
		if ($since) {
			$queryBuilder = $this->connection->getQueryBuilder();
			$queryBuilder->select(['affecteduser', 'timestamp'])
				->from('activity')
				->where($queryBuilder->expr()->eq('activity_id', $queryBuilder->createNamedParameter((int) $since)));
			$result = $queryBuilder->execute();
			$activity = $result->fetch();
			$result->closeCursor();

			if ($activity) {
				if ($activity['affecteduser'] !== $user) {
					throw new \OutOfBoundsException('Invalid since', 2);
				}
				$timestamp = (int) $activity['timestamp'];

				if ($sort === 'DESC') {
					$query->andWhere($query->expr()->lte('timestamp', $query->createNamedParameter($timestamp)));
					$query->andWhere($query->expr()->lt('activity_id', $query->createNamedParameter($since)));
				} else {
					$query->andWhere($query->expr()->gte('timestamp', $query->createNamedParameter($timestamp)));
					$query->andWhere($query->expr()->gt('activity_id', $query->createNamedParameter($since)));
				}
				return [];
			}
		}

		/**
		 * Couldn't find the since, so find the oldest one and set the header
		 */
		$fetchQuery = $this->connection->getQueryBuilder();
		$fetchQuery->select('activity_id')
			->from('activity')
			->where($fetchQuery->expr()->eq('affecteduser', $fetchQuery->createNamedParameter($user)))
			->orderBy('timestamp', $sort)
			->setMaxResults(1);
		$result = $fetchQuery->execute();
		$activity = $result->fetch();
		$result->closeCursor();

		if ($activity !== false) {
			return [
				'X-Activity-First-Known' => (int) $activity['activity_id'],
			];
		}

		return [];
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
			case 'filter':
				return $filterValue;
			default:
				try {
					$this->activityManager->getFilterById($filterValue);
					return $filterValue;
				} catch (\InvalidArgumentException $e) {
					return 'all';
				}
		}
	}

	/**
	 * Delete old events
	 *
	 * @param int $expireDays Minimum 1 day
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
