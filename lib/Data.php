<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Activity;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use OCA\Activity\Filter\AllFilter;
use OCP\Activity\Exceptions\FilterNotFoundException;
use OCP\Activity\IEvent;
use OCP\Activity\IExtension;
use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use Psr\Log\LoggerInterface;

/**
 * @brief Class for managing the data in the activities
 */
class Data {

	/** @var  */
	protected ?IQueryBuilder $insertActivity = null;
	protected ?IQueryBuilder $insertMail = null;

	public function __construct(
		protected IManager $activityManager,
		protected IDBConnection $connection,
		protected LoggerInterface $logger,
	) {
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
		} catch (FilterNotFoundException) {
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
			} catch (FilterNotFoundException) {
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

		$result = $query->executeQuery();
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
			$result = $queryBuilder->executeQuery();
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
		$result = $fetchQuery->executeQuery();
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
				} catch (FilterNotFoundException) {
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
	 *                          'field' => 'value'  => `field` = 'value'
	 *                          'field' => array('value', 'operator') => `field` operator 'value'
	 */
	public function deleteActivities($conditions): void {
		$platform = $this->connection->getDatabasePlatform();
		if ($platform instanceof MySQLPlatform) {
			$this->logger->debug('Choosing chunked activity delete for MySQL/MariaDB', ['app' => 'activity']);
			$this->deleteActivitiesForMySQL($conditions);
			return;
		}
		$this->logger->debug('Choosing regular activity delete', ['app' => 'activity']);
		$deleteQuery = $this->connection->getQueryBuilder();
		$deleteQuery->delete('activity');

		foreach ($conditions as $column => $comparison) {
			if (is_array($comparison)) {
				$operation = $comparison[1] ?? '=';
				$value = $comparison[0];
			} else {
				$operation = '=';
				$value = $comparison;
			}

			$deleteQuery->andWhere($deleteQuery->expr()->comparison($column, $operation, $deleteQuery->createNamedParameter($value)));
		}
		// Dont use chunked delete - let the DB handle the large row count natively
		$deleteQuery->executeStatement();
	}

	public function getById(int $activityId): ?IEvent {
		$query = $this->connection->getQueryBuilder();
		$query->select('*')
			->from('activity')
			->where($query->expr()->eq('activity_id', $query->createNamedParameter($activityId)));

		$result = $query->executeQuery();
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

		$res = $query->executeQuery()->fetch(\PDO::FETCH_COLUMN);
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

		return $query->executeQuery()->fetch();
	}

	/**
	 * Add galera safe delete chunking if using mysql
	 * Stops us hitting wsrep_max_ws_rows when large row counts are deleted
	 *
	 * @param array $conditions
	 * @return void
	 */
	private function deleteActivitiesForMySQL(array $conditions): void {
		$query = $this->connection->getQueryBuilder();
		$query->select('activity_id')
			->from('activity');

		foreach ($conditions as $column => $comparison) {
			if (is_array($comparison)) {
				$operation = $comparison[1] ?? '=';
				$value = $comparison[0];
			} else {
				$operation = '=';
				$value = $comparison;
			}
			$query->where($query->expr()->comparison($column, $operation, $query->createNamedParameter($value)));
		}

		$query->setMaxResults(50000);
		$result = $query->executeQuery();
		$count = $result->rowCount();
		if ($count === 0) {
			return;
		}
		$ids = array_map(static function (array $id) {
			return (int)$id[0];
		}, $result->fetchAll(\PDO::FETCH_NUM));
		$result->closeCursor();

		$queryResult = 0;
		$deleteQuery = $this->connection->getQueryBuilder();
		$deleteQuery->delete('activity');
		$deleteQuery->where($deleteQuery->expr()->in('activity_id', $deleteQuery->createParameter('ids'), IQueryBuilder::PARAM_INT_ARRAY));
		foreach (array_chunk($ids, 1000) as $chunk) {
			$deleteQuery->setParameter('ids', $chunk, IQueryBuilder::PARAM_INT_ARRAY);
			$queryResult += $deleteQuery->executeStatement();
		}
		if ($queryResult === 50000) {
			$this->deleteActivitiesForMySQL($conditions);
		}
	}
}
