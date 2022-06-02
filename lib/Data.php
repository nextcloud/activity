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

use Doctrine\DBAL\Platforms\MySQLPlatform;
use OCA\Activity\Filter\AllFilter;
use OCP\Activity\IEvent;
use OCP\Activity\IExtension;
use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @brief Class for managing the data in the activities
 */
class Data {
	/** @var IManager */
	protected $activityManager;

	/** @var IDBConnection */
	protected $connection;

	/** @var ?IQueryBuilder */
	protected $insertActivity;

	/** @var ?IQueryBuilder */
	protected $insertMail;

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
	 * @return int
	 */
	public function send(IEvent $event): int {
		if ($event->getAffectedUser() === '') {
			return 0;
		}

		if ($this->insertActivity === null) {
			$this->insertActivity = $this->connection->getQueryBuilder();
			$this->insertActivity->insert('activity')
				->values([
					'app' => $this->insertActivity->createParameter('app'),
					'subject' => $this->insertActivity->createParameter('subject'),
					'subjectparams' => $this->insertActivity->createParameter('subjectparams'),
					'message' => $this->insertActivity->createParameter('message'),
					'messageparams' => $this->insertActivity->createParameter('messageparams'),
					'file' => $this->insertActivity->createParameter('object_name'),
					'link' => $this->insertActivity->createParameter('link'),
					'user' => $this->insertActivity->createParameter('user'),
					'affecteduser' => $this->insertActivity->createParameter('affecteduser'),
					'timestamp' => $this->insertActivity->createParameter('timestamp'),
					'priority' => $this->insertActivity->createParameter('priority'),
					'type' => $this->insertActivity->createParameter('type'),
					'object_type' => $this->insertActivity->createParameter('object_type'),
					'object_id' => $this->insertActivity->createParameter('object_id'),
				]);
		}

		// store in DB
		$this->insertActivity->setParameters([
			'app' => $event->getApp(),
			'type' => $event->getType(),
			'affecteduser' => $event->getAffectedUser(),
			'user' => $event->getAuthor(),
			'timestamp' => $event->getTimestamp(),
			'subject' => $event->getSubject(),
			'subjectparams' => json_encode($event->getSubjectParameters()),
			'message' => $event->getMessage(),
			'messageparams' => json_encode($event->getMessageParameters()),
			'priority' => IExtension::PRIORITY_MEDIUM,
			'object_type' => $event->getObjectType(),
			'object_id' => $event->getObjectId(),
			'object_name' => $event->getObjectName(),
			'link' => $event->getLink(),
		]);
		$this->insertActivity->executeStatement();

		return $this->insertActivity->getLastInsertId();
	}

	/**
	 * Send an event as email
	 *
	 * @param IEvent $event
	 * @param int $latestSendTime Activity $timestamp + batch setting of $affectedUser
	 * @return bool
	 */
	public function storeMail(IEvent $event, int $latestSendTime): bool {
		$affectedUser = $event->getAffectedUser();
		if ($affectedUser === '') {
			return false;
		}

		if ($this->insertMail === null) {
			$this->insertMail = $this->connection->getQueryBuilder();
			$this->insertMail->insert('activity_mq')
				->values([
					'amq_appid' => $this->insertMail->createParameter('amq_appid'),
					'amq_subject' => $this->insertMail->createParameter('amq_subject'),
					'amq_subjectparams' => $this->insertMail->createParameter('amq_subjectparams'),
					'amq_affecteduser' => $this->insertMail->createParameter('amq_affecteduser'),
					'amq_timestamp' => $this->insertMail->createParameter('amq_timestamp'),
					'amq_type' => $this->insertMail->createParameter('amq_type'),
					'amq_latest_send' => $this->insertMail->createParameter('amq_latest_send'),
					'object_type' => $this->insertMail->createParameter('object_type'),
					'object_id' => $this->insertMail->createParameter('object_id'),
				]);
		}

		$this->insertMail->setParameters([
			'amq_appid' => $event->getApp(),
			'amq_subject' => $event->getSubject(),
			'amq_subjectparams' => json_encode($event->getSubjectParameters()),
			'amq_affecteduser' => $affectedUser,
			'amq_timestamp' => $event->getTimestamp(),
			'amq_type' => $event->getType(),
			'amq_latest_send' => $latestSendTime,
			'object_type' => $event->getObjectType(),
			'object_id' => $event->getObjectId(),
		]);

		$this->insertMail->executeStatement();

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
	 * @param bool $returnEvents return only the events
	 * @return array
	 *
	 */
	public function get(GroupHelper $groupHelper, UserSettings $userSettings, $user, $since, $limit, $sort, $filter, $objectType = '', $objectId = 0, bool $returnEvents = false) {
		// get current user
		if ($user === '') {
			throw new \OutOfBoundsException('Invalid user', 1);
		}

		$limit = min(200, $limit);

		$activeFilter = null;
		try {
			$activeFilter = $this->activityManager->getFilterById($filter);
		} catch (\InvalidArgumentException $e) {
			// Unknown filter => ignore and show all activities
		}

		$query = $this->connection->getQueryBuilder();
		$query->select('*')
			->from('activity');

		$query->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)));

		if ($activeFilter instanceof IFilter && !($activeFilter instanceof AllFilter)) {
			$notificationTypes = $userSettings->getNotificationTypes();
			$notificationTypes = $activeFilter->filterTypes($notificationTypes);
			$notificationTypes = array_unique($notificationTypes);

			$query->andWhere($query->expr()->in('type', $query->createNamedParameter($notificationTypes, IQueryBuilder::PARAM_STR_ARRAY)));
		}

		if ($filter === 'self') {
			$query->andWhere($query->expr()->eq('user', $query->createNamedParameter($user)));
		} elseif ($filter === 'by') {
			$query->andWhere($query->expr()->neq('user', $query->createNamedParameter($user)));
		} elseif ($filter === 'filter') {
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
			$headers['X-Activity-Last-Given'] = (int)$row['activity_id'];
			$groupHelper->addActivity($row);
			$limit--;
		}
		$result->closeCursor();

		if ($returnEvents) {
			return $groupHelper->getEvents();
		} else {
			return ['data' => $groupHelper->getActivities(), 'has_more' => $hasMore, 'headers' => $headers];
		}
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
				->where($queryBuilder->expr()->eq('activity_id', $queryBuilder->createNamedParameter((int)$since)));
			$result = $queryBuilder->execute();
			$activity = $result->fetch();
			$result->closeCursor();

			if ($activity) {
				if ($activity['affecteduser'] !== $user) {
					throw new \OutOfBoundsException('Invalid since', 2);
				}
				$timestamp = (int)$activity['timestamp'];

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
				'X-Activity-First-Known' => (int)$activity['activity_id'],
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
		$this->deleteActivities([
			'timestamp' => [$timelimit, '<'],
		]);
	}

	/**
	 * Delete activities that match certain conditions
	 *
	 * @param array $conditions Array with conditions that have to be met
	 *                      'field' => 'value'  => `field` = 'value'
	 *    'field' => array('value', 'operator') => `field` operator 'value'
	 */
	public function deleteActivities($conditions) {
		$delete = $this->connection->getQueryBuilder();
		$delete->delete('activity');

		foreach ($conditions as $column => $comparison) {
			if (is_array($comparison)) {
				$operation = $comparison[1] ?? '=';
				$value = $comparison[0];
			} else {
				$operation = '=';
				$value = $comparison;
			}

			$delete->andWhere($delete->expr()->comparison($column, $operation, $delete->createNamedParameter($value)));
		}

		// Add galera safe delete chunking if using mysql
		// Stops us hitting wsrep_max_ws_rows when large row counts are deleted
		if ($this->connection->getDatabasePlatform() instanceof MySQLPlatform) {
			// Then use chunked delete
			$max = 100000;
			$delete->setMaxResults($max);
			do {
				$deleted = $delete->executeStatement();
			} while ($deleted === $max);
		} else {
			// Dont use chunked delete - let the DB handle the large row count natively
			$delete->executeStatement();
		}
	}

	public function getById(int $activityId): ?IEvent {
		$query = $this->connection->getQueryBuilder();
		$query->select('*')
			->from('activity')
			->where($query->expr()->eq('activity_id', $query->createNamedParameter($activityId)));

		$result = $query->execute();
		if ($row = $result->fetch()) {
			$event = $this->activityManager->generateEvent();
			$event->setApp((string)$row['app'])
				->setType((string)$row['type'])
				->setAffectedUser((string)$row['affecteduser'])
				->setAuthor((string)$row['user'])
				->setTimestamp((int)$row['timestamp'])
				->setSubject((string)$row['subject'], (array)json_decode($row['subjectparams'], true))
				->setMessage((string)$row['message'], (array)json_decode($row['messageparams'], true))
				->setObject((string)$row['object_type'], (int)$row['object_id'], (string)$row['file'])
				->setLink((string)$row['link']);

			return $event;
		}

		return null;
	}

	/**
	 * Get the id of the first activity in the stream since a specified time
	 *
	 * @param string $user
	 * @param int $timestamp
	 * @return int
	 */
	public function getFirstActivitySince(string $user, int $timestamp): int {
		$query = $this->connection->getQueryBuilder();
		$query->select('activity_id')
			->from('activity')
			->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)))
			->andWhere($query->expr()->gt('timestamp', $query->createNamedParameter($timestamp, IQueryBuilder::PARAM_INT)))
			->orderBy('timestamp', 'ASC')
			->setMaxResults(1);

		$res = $query->execute()->fetch(\PDO::FETCH_COLUMN);
		return (int)$res;
	}

	/**
	 * Get the number of activity items and the latest activity id since the specified activity
	 *
	 * @param string $user
	 * @param int $since
	 * @param bool $byOthers
	 * @return array
	 */
	public function getActivitySince(string $user, int $since, bool $byOthers) {
		$query = $this->connection->getQueryBuilder();
		$nameParam = $query->createNamedParameter($user);
		$query->select($query->func()->count('activity_id', 'count'))
			->selectAlias($query->func()->max('activity_id'), 'max')
			->from('activity')
			->where($query->expr()->eq('affecteduser', $nameParam))
			->andWhere($query->expr()->gt('activity_id', $query->createNamedParameter($since, IQueryBuilder::PARAM_INT)));

		if ($byOthers) {
			$query->andWhere($query->expr()->neq('user', $nameParam));
		}

		return $query->execute()->fetch();
	}
}
