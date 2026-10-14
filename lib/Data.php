<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity;

use OCA\Activity\Filter\AllFilter;
use OCP\Activity\Exceptions\FilterNotFoundException;
use OCP\Activity\IEvent;
use OCP\Activity\IExtension;
use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\Files\IRootFolder;
use OCP\IConfig;
use OCP\IDBConnection;
use Psr\Log\LoggerInterface;

/**
 * @brief Class for managing the data in the activities
 */
class Data {
	/**
	 * Runaway guard for {@see self::getDailyCounts()}.
	 *
	 * A histogram window is already bounded to one user and a date range, so this
	 * is not expected to bind; it exists so an extreme account cannot turn one
	 * chart into an unbounded fetch. Reaching it is reported rather than hidden.
	 */
	public const MAX_HISTOGRAM_ROWS = 100000;
	private const TEAM_RESOURCE_OBJECT_CHUNK_SIZE = 500;

	/** @var  */
	protected ?IQueryBuilder $insertActivity = null;
	protected ?IQueryBuilder $insertMail = null;

	public function __construct(
		protected IManager $activityManager,
		protected IDBConnection $connection,
		protected LoggerInterface $logger,
		protected IConfig $config,
		protected IRootFolder $rootFolder,
	) {
	}

	/**
	 * Check if the event should be processed (not excluded and has valid target)
	 */
	private function shouldSend(IEvent $event): bool {
		return $event->getAffectedUser() !== '' && !$this->isExcludedAuthor($event);
	}

	/**
	 * Check if the event's author is excluded from activity logging
	 */
	private function isExcludedAuthor(IEvent $event): bool {
		$excludedUsers = $this->config->getSystemValue('activity_log_exclude_users', []);
		if (empty($excludedUsers)) {
			return false;
		}
		$author = $event->getAuthor();
		if ($author === '' || !isset($excludedUsers[$author])) {
			return false;
		}
		$rule = $excludedUsers[$author];
		if (is_array($rule)) {
			return in_array($event->getType(), $rule, true);
		} else {
			$this->logger->warning(
				'activity_log_exclude_users rule for user "{user}" is not an array, skipping!',
				['app' => 'activity', 'user' => $author]
			);
		}
		return false;
	}

	/**
	 * Send an event into the activity stream
	 */
	public function send(IEvent $event): int {
		if (!$this->shouldSend($event)) {
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
	 * Bulk sends an event into the activity stream
	 * for a batch of users that are affected by the same event
	 * (ex. Call Started, Call ended)
	 *
	 * @return array<int, string>
	 * @throws Exception
	 */
	public function bulkSend(IEvent $event, array $affectedUsers): array {
		if ($this->isExcludedAuthor($event)) {
			return [];
		}

		$this->connection->beginTransaction();

		$activityIds = [];
		try {
			if ($this->insertActivity === null) {
				$this->insertActivity = $this->connection->getQueryBuilder();
			}
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

			$this->insertActivity->setParameters([
				'app' => $event->getApp(),
				'type' => $event->getType(),
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

			foreach ($affectedUsers as $affectedUser) {
				$this->insertActivity->setParameter('affecteduser', $affectedUser);
				$this->insertActivity->executeStatement();
				$activityIds[$this->insertActivity->getLastInsertId()] = (string)$affectedUser;
			}

			$this->connection->commit();
		} catch (Exception $e) {
			// Make sure to always roll back, otherwise the outer code runs in a failed transaction
			$this->logger->error('Could not create bulk activities', ['exception' => $e]);
			$this->connection->rollBack();
			return [];
		}

		return $activityIds;
	}

	/**
	 * Send an event as email
	 *
	 * @param int $latestSendTime Activity $timestamp + batch setting of $affectedUser
	 */
	public function storeMail(IEvent $event, int $latestSendTime): bool {
		if (!$this->shouldSend($event)) {
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
			'amq_affecteduser' => $event->getAffectedUser(),
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
	 * @param SearchCriteria|null $search Additional search term and date range restrictions
	 *
	 */
	public function get(GroupHelper $groupHelper, UserSettings $userSettings, string $user, int $since, int $limit, string $sort, string $filter, string $objectType = '', int $objectId = 0, bool $returnEvents = false, ?SearchCriteria $search = null): array {
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
			->from('activity', 'a');

		$this->applyStreamConditions($query, $userSettings, $user, $filter, $activeFilter, $objectType, $objectId, $search);

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
	 * Read activities for a Team and its current resources.
	 *
	 * @return array{data: array, has_more: bool, headers: array}
	 */
	public function getTeam(GroupHelper $groupHelper, UserSettings $userSettings, string $user, int $since, int $limit, string $sort, TeamActivityScope $scope, ?SearchCriteria $search = null): array {
		if ($user === '') {
			throw new \OutOfBoundsException('Invalid user', 1);
		}

		$limit = max(1, min(200, $limit));
		$activeFilter = null;
		try {
			$activeFilter = $this->activityManager->getFilterById('all');
		} catch (FilterNotFoundException) {
			// Unknown filter => ignore app filtering, as get() does.
		}

		$scopes = [['circles', [$scope->circleObjectId]]];
		foreach ($scope->resourceScopes as $resourceScope) {
			foreach (array_chunk($resourceScope->getObjectIds(), self::TEAM_RESOURCE_OBJECT_CHUNK_SIZE) as $objectIds) {
				if ($objectIds !== []) {
					$scopes[] = [$resourceScope->getObjectType(), $objectIds];
				}
			}
		}

		$cursorTimestamp = null;
		if ($since !== 0) {
			$cursorQuery = $this->connection->getQueryBuilder();
			$cursorQuery->select('timestamp')->from('activity')
				->where($cursorQuery->expr()->eq('activity_id', $cursorQuery->createNamedParameter($since, IQueryBuilder::PARAM_INT)));
			$cursorTimestamp = $cursorQuery->executeQuery()->fetchOne();
			if ($cursorTimestamp === false) {
				throw new \OutOfBoundsException('Unknown Team Activity cursor');
			}
			$cursorTimestamp = (int)$cursorTimestamp;
		}

		$sqlSort = ($sort === 'asc') ? 'ASC' : 'DESC';
		$queries = [];
		$timestamps = [];
		foreach ($scopes as [$objectType, $objectIds]) {
			$createQuery = function () use ($userSettings, $user, $activeFilter, $search, $objectType, $objectIds, $cursorTimestamp, $sqlSort): IQueryBuilder {
				$query = $this->connection->getQueryBuilder();
				$query->select('*')->from('activity', 'a');
				$this->applyStreamConditions($query, $userSettings, $user, 'all', $activeFilter, '', 0, null, false);
				$this->applyTeamSearchCriteria($query, $search ?? SearchCriteria::empty(), $objectType === 'circles');
				$query->andWhere($query->expr()->eq('object_type', $query->createNamedParameter($objectType)));
				$query->andWhere($query->expr()->in('object_id', $query->createNamedParameter($objectIds, IQueryBuilder::PARAM_INT_ARRAY)));
				if ($cursorTimestamp !== null) {
					$parameter = $query->createNamedParameter($cursorTimestamp, IQueryBuilder::PARAM_INT);
					$query->andWhere($sqlSort === 'DESC' ? $query->expr()->lte('timestamp', $parameter) : $query->expr()->gte('timestamp', $parameter));
				}
				return $query;
			};
			$queries[] = $createQuery;
			$timestampQuery = $createQuery();
			$timestampQuery->select('timestamp')->groupBy('timestamp')->orderBy('timestamp', $sqlSort)->setMaxResults($limit + 2);
			$result = $timestampQuery->executeQuery();
			foreach ($result->fetchAllAssociative() as $row) {
				$timestamps[(int)$row['timestamp']] = (int)$row['timestamp'];
			}
			$result->closeCursor();
		}
		$sqlSort === 'DESC' ? rsort($timestamps) : sort($timestamps);
		$rows = [];
		foreach ($timestamps as $timestamp) {
			$bucket = [];
			foreach ($queries as $createQuery) {
				$bucketQuery = $createQuery();
				$bucketQuery->andWhere($bucketQuery->expr()->eq('timestamp', $bucketQuery->createNamedParameter($timestamp, IQueryBuilder::PARAM_INT)));
				$result = $bucketQuery->executeQuery();
				while ($row = $result->fetch()) {
					$bucket[] = $this->normaliseTeamFileActivity($row, $scope->filePaths);
				}
				$result->closeCursor();
			}
			$bucket = $this->deduplicateTeamActivities($bucket);
			usort($bucket, static fn (array $left, array $right): int => $sqlSort === 'DESC'
				? (int)$right['activity_id'] <=> (int)$left['activity_id']
				: (int)$left['activity_id'] <=> (int)$right['activity_id']);
			foreach ($bucket as $row) {
				if ($timestamp === $cursorTimestamp && ($sqlSort === 'DESC' ? (int)$row['activity_id'] >= $since : (int)$row['activity_id'] <= $since)) {
					continue;
				}
				$rows[] = $row;
				if (count($rows) > $limit) {
					break 2;
				}
			}
		}

		$hasMore = count($rows) > $limit;
		$rows = array_slice($rows, 0, $limit);
		$headers = [];
		foreach ($rows as $row) {
			if ($row['object_type'] === 'files') {
				$row['affecteduser'] = $user;
			}
			$headers['X-Activity-Last-Given'] = (int)$row['activity_id'];
			$groupHelper->addActivity($row);
		}

		return ['data' => $groupHelper->getActivities(), 'has_more' => $hasMore, 'headers' => $headers];
	}

	/**
	 * Files publishes one recipient-specific row per user. A Team stream has no
	 * single recipient, so turn the self-only wording into its actor form before
	 * collapsing otherwise identical rows.
	 */
	private function normaliseTeamFileActivity(array $row, array $filePaths = []): array {
		if ($row['app'] !== 'files' || $row['object_type'] !== 'files' || !preg_match('/^(created|changed|deleted|restored|renamed|moved)_(self|by)(_enc)?$/', (string)$row['subject'], $matches)) {
			return $row;
		}

		$parameters = json_decode((string)$row['subjectparams'], true);
		if (!is_array($parameters)) {
			return $row;
		}

		$action = $matches[1];
		if ($matches[2] === 'self') {
			$parameters = in_array($action, ['renamed', 'moved'], true)
				? [$parameters[0] ?? [], $row['user'], $parameters[1] ?? []]
				: [$parameters[0] ?? [], $row['user']];
		}
		$sourcePath = is_array($parameters[0] ?? null) ? reset($parameters[0]) : ($parameters[0] ?? '');
		foreach ($parameters as &$parameter) {
			if (!is_array($parameter)) {
				continue;
			}
			foreach ($parameter as $fileId => &$path) {
				if (is_string($path) && is_numeric($fileId)) {
					$path = isset($filePaths[$fileId]) && is_string($sourcePath)
						? $this->normaliseTeamFilePath($path, $sourcePath, $filePaths[$fileId])
						: basename($path);
				}
			}
			unset($path);
		}
		unset($parameter);
		$row['file'] = $filePaths[$row['object_id']] ?? basename($row['file']);
		$row['link'] = '';
		$row['subject'] = $action . '_by' . ($matches[3] ?? '');
		$row['subjectparams'] = json_encode($parameters, JSON_THROW_ON_ERROR);

		return $row;
	}

	private function normaliseTeamFilePath(string $path, string $sourcePath, string $viewerPath): string {
		$source = explode('/', trim(dirname($sourcePath), '/'));
		$target = explode('/', trim(dirname($viewerPath), '/'));
		$relative = explode('/', trim($path, '/'));
		while ($source !== [] && $relative !== [] && $source[0] === $relative[0]) {
			array_shift($source);
			array_shift($relative);
		}
		$target = array_slice($target, 0, max(0, count($target) - count($source)));
		return '/' . implode('/', array_merge($target, $relative));
	}

	/**
	 * @param list<array<string, mixed>> $rows
	 * @return list<array<string, mixed>>
	 */
	private function deduplicateTeamActivities(array $rows): array {
		$deduplicated = [];
		foreach ($rows as $row) {
			$key = implode("\0", [
				$row['app'], $row['subject'], $row['subjectparams'], $row['message'], $row['messageparams'],
				$row['file'], $row['link'], $row['user'], $row['timestamp'], $row['priority'], $row['type'],
				$row['object_type'], $row['object_id'],
			]);
			if (!isset($deduplicated[$key]) || (int)$row['activity_id'] < (int)$deduplicated[$key]['activity_id']) {
				$deduplicated[$key] = $row;
			}
		}

		return array_values($deduplicated);
	}

	/**
	 * Restrict a query to the activities a user may see under a given filter.
	 *
	 * Shared by the stream itself and by {@see self::getDailyCounts()} so the two
	 * can never disagree about what the stream contains: a histogram whose bars
	 * count rows the feed below it does not list is worse than no histogram.
	 *
	 * @param IFilter|null $activeFilter The resolved filter, or null when unknown
	 */
	private function applyStreamConditions(
		IQueryBuilder $query,
		UserSettings $userSettings,
		string $user,
		string $filter,
		?IFilter $activeFilter,
		string $objectType,
		int $objectId,
		?SearchCriteria $search,
		bool $restrictToAffectedUser = true,
	): void {
		if ($restrictToAffectedUser) {
			$query->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)));
		}

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
		} elseif ($filter === 'focused') {
			// Get home storage id
			$storageId = $this->rootFolder->getUserFolder($user)->getMountPoint()->getNumericStorageId();
			// Only activity related to files owned by the user
			$query->leftJoin('a', 'filecache', 'f',
				$query->expr()->andX(
					$query->expr()->eq('a.object_type', $query->createNamedParameter('files')),
					$query->expr()->eq('a.object_id', 'f.fileid'),
				));
			// Filter out our own activity
			$query->andWhere($query->expr()->neq('user', $query->createNamedParameter($user)));
			$query->andWhere(
				$query->expr()->orX(
					// Affected object is one of our files
					$query->expr()->eq('f.storage', $query->createNamedParameter($storageId, IQueryBuilder::PARAM_INT)),
					// There is no affected object, so the activity affects our user directly
					$query->expr()->eq('object_type', $query->createNamedParameter('')),
					// Show all sharing related events
					$query->expr()->eq('app', $query->createNamedParameter('files_sharing')),
				)
			);
		}

		if ($activeFilter instanceof IFilter) {
			$apps = $activeFilter->allowedApps();
			if (!empty($apps)) {
				$query->andWhere($query->expr()->in('app', $query->createNamedParameter($apps, IQueryBuilder::PARAM_STR_ARRAY)));
			}
		}

		if (
			$filter === 'files_favorites'
			|| (in_array($filter, ['all', 'by', 'self']) && $userSettings->getUserSetting($user, 'stream', 'files_favorites'))
		) {
			try {
				$favoriteFilter = $this->activityManager->getFilterById('files_favorites');
				/** @var \OCA\Files\Activity\Filter\Favorites $favoriteFilter */
				$favoriteFilter->filterFavorites($query);
			} catch (FilterNotFoundException) {
			}
		}

		$this->applySearchCriteria($query, $search ?? SearchCriteria::empty());
	}

	/**
	 * Count the activities per calendar day within a window.
	 *
	 * Days are resolved in the given timezone rather than in UTC, because a
	 * histogram is read against the reader's own calendar: an activity at 01:00
	 * local time belongs to that day for them regardless of where the day
	 * boundary falls in UTC. Passing a real DateTimeZone rather than a fixed
	 * offset is what keeps this correct across a DST transition inside the
	 * window.
	 *
	 * Bucketing happens in PHP on purpose. Grouping by day in SQL needs either
	 * integer division or a modulo, and neither is expressible through
	 * IQueryBuilder — a raw expression would have to be written differently for
	 * MySQL, PostgreSQL, Oracle and SQLite, which this app supports all four of.
	 * The query itself stays cheap: it selects one column, restricted to a single
	 * `affecteduser` and a timestamp range, which is exactly the
	 * `activity_user_time` index.
	 *
	 * `counts` is keyed by `Y-m-d` and omits days with no activity, so its size
	 * tracks real activity rather than the length of the window. `partialBefore`
	 * is the date from which counts are known to be incomplete, or null.
	 *
	 * @param int $from Start of the window as a Unix timestamp, inclusive
	 * @param int $to End of the window as a Unix timestamp, inclusive
	 *
	 * @return array{counts: array<string, int>, partialBefore: ?string}
	 */
	public function getDailyCounts(
		UserSettings $userSettings,
		string $user,
		string $filter,
		int $from,
		int $to,
		\DateTimeZone $timezone,
		string $objectType = '',
		int $objectId = 0,
		?SearchCriteria $search = null,
	): array {
		if ($user === '') {
			throw new \OutOfBoundsException('Invalid user', 1);
		}

		$activeFilter = null;
		try {
			$activeFilter = $this->activityManager->getFilterById($filter);
		} catch (FilterNotFoundException) {
			// Unknown filter => count everything, as the stream would show it
		}

		$query = $this->connection->getQueryBuilder();
		$query->select('timestamp')
			->from('activity');

		$this->applyStreamConditions($query, $userSettings, $user, $filter, $activeFilter, $objectType, $objectId, $search);

		$query->andWhere($query->expr()->gte('timestamp', $query->createNamedParameter($from, IQueryBuilder::PARAM_INT)));
		$query->andWhere($query->expr()->lte('timestamp', $query->createNamedParameter($to, IQueryBuilder::PARAM_INT)));

		// Newest first, so that hitting the guard below costs the oldest days
		// rather than the recent ones a reader is actually looking at
		$query->orderBy('timestamp', 'DESC');
		$query->setMaxResults(self::MAX_HISTOGRAM_ROWS + 1);

		$result = $query->executeQuery();
		$counts = [];
		$rows = 0;
		$oldestCounted = null;
		while ($row = $result->fetch()) {
			$rows++;
			if ($rows > self::MAX_HISTOGRAM_ROWS) {
				break;
			}
			$timestamp = (int)$row['timestamp'];
			$day = (new \DateTimeImmutable('@' . $timestamp))->setTimezone($timezone)->format('Y-m-d');
			$counts[$day] = ($counts[$day] ?? 0) + 1;
			$oldestCounted = $day;
		}
		$result->closeCursor();

		$partialBefore = null;
		if ($rows > self::MAX_HISTOGRAM_ROWS && $oldestCounted !== null) {
			// The day the guard cut off is only partly counted, so drop it and
			// tell the client where the data stops being trustworthy instead of
			// drawing a bar that understates the day
			unset($counts[$oldestCounted]);
			$partialBefore = $oldestCounted;
			$this->logger->debug('Activity histogram truncated at ' . self::MAX_HISTOGRAM_ROWS . ' rows', ['app' => 'activity']);
		}

		return ['counts' => $counts, 'partialBefore' => $partialBefore];
	}

	/**
	 * Narrow a stream query down to a date range, a file path search term
	 * and/or the account that authored the activity.
	 */
	private function applySearchCriteria(IQueryBuilder $query, SearchCriteria $criteria): void {
		if ($criteria->from !== null) {
			$query->andWhere($query->expr()->gte('timestamp', $query->createNamedParameter($criteria->from, IQueryBuilder::PARAM_INT)));
		}

		if ($criteria->to !== null) {
			$query->andWhere($query->expr()->lte('timestamp', $query->createNamedParameter($criteria->to, IQueryBuilder::PARAM_INT)));
		}

		if ($criteria->term !== null) {
			// escapeLikeParameter() keeps % and _ in the user's input literal
			$pattern = '%' . $this->connection->escapeLikeParameter($criteria->term) . '%';
			$query->andWhere($query->expr()->iLike('file', $query->createNamedParameter($pattern)));
		}

		if ($criteria->actor !== null) {
			$query->andWhere($query->expr()->eq('user', $query->createNamedParameter($criteria->actor)));
		}
	}

	/**
	 * Apply Team stream restrictions. Unlike the personal stream, Team Activity
	 * includes roster events without a file path, so a text search also covers
	 * their subject identifier and serialized rich-subject parameters.
	 */
	private function applyTeamSearchCriteria(IQueryBuilder $query, SearchCriteria $criteria, bool $includeHistoricalInitiator): void {
		if ($criteria->from !== null) {
			$query->andWhere($query->expr()->gte('timestamp', $query->createNamedParameter($criteria->from, IQueryBuilder::PARAM_INT)));
		}

		if ($criteria->to !== null) {
			$query->andWhere($query->expr()->lte('timestamp', $query->createNamedParameter($criteria->to, IQueryBuilder::PARAM_INT)));
		}

		if ($criteria->term !== null) {
			$pattern = '%' . $this->connection->escapeLikeParameter($criteria->term) . '%';
			$query->andWhere($query->expr()->orX(
				$query->expr()->iLike('file', $query->createNamedParameter($pattern)),
				$query->expr()->iLike('subject', $query->createNamedParameter($pattern)),
				$query->expr()->iLike('subjectparams', $query->createNamedParameter($pattern)),
			));
		}

		if ($criteria->actor !== null) {
			$authorCondition = $query->expr()->eq('user', $query->createNamedParameter($criteria->actor));
			if ($includeHistoricalInitiator) {
				$initiatorPattern = '%"initiator":{"userId":"' . $this->connection->escapeLikeParameter($criteria->actor) . '"%';
				$authorCondition = $query->expr()->orX(
					$authorCondition,
					$query->expr()->iLike('subjectparams', $query->createNamedParameter($initiatorPattern)),
				);
			}
			$query->andWhere($authorCondition);
		}
	}

	/**
	 *
	 * @return array Headers that should be set on the response
	 *
	 * @throws \OutOfBoundsException If $since is not owned by $user
	 */
	protected function setOffsetFromSince(IQueryBuilder $query, string $user, int $since, string $sort): array {
		if (!$since) {
			return $this->getFirstKnownActivityHeader($user, $sort);
		}

		$queryBuilder = $this->connection->getQueryBuilder();
		$queryBuilder->select(['affecteduser', 'timestamp'])
			->from('activity')
			->where($queryBuilder->expr()->eq('activity_id', $queryBuilder->createNamedParameter($since)));
		$result = $queryBuilder->executeQuery();
		$activity = $result->fetch();
		$result->closeCursor();

		if (!$activity) {
			return $this->getFirstKnownActivityHeader($user, $sort);
		}

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

	private function getFirstKnownActivityHeader(string $user, string $sort): array {
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
			return ['X-Activity-First-Known' => (int)$activity['activity_id']];
		}
		return [];
	}

	/**
	 * Count the number of download activities for a given file
	 *
	 * @param string $user The affected user (file owner)
	 * @param int $objectId The file ID
	 * @param int|null $since Optional Unix timestamp; only count activities at or after this time
	 */
	public function countDownloads(string $user, int $objectId, ?int $since = null): int {
		$query = $this->connection->getQueryBuilder();
		$query->select($query->func()->count('activity_id', 'count'))
			->from('activity')
			->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)))
			->andWhere($query->expr()->eq('app', $query->createNamedParameter('files_sharing')))
			->andWhere($query->expr()->eq('type', $query->createNamedParameter('public_links')))
			->andWhere($query->expr()->eq('object_type', $query->createNamedParameter('files')))
			->andWhere($query->expr()->eq('object_id', $query->createNamedParameter($objectId, IQueryBuilder::PARAM_INT)));

		if ($since !== null) {
			$query->andWhere($query->expr()->gte('timestamp', $query->createNamedParameter($since, IQueryBuilder::PARAM_INT)));
		}

		$result = $query->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();

		return (int)($row['count'] ?? 0);
	}

	/**
	 * Verify that the filter is valid
	 */
	public function validateFilter(string $filterValue): string {
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
	public function expire($expireDays = 365): void {
		$ttl = (60 * 60 * 24 * max(1, $expireDays));
		$timelimit = time() - $ttl;
		$conditions = [
			['timestamp', $timelimit, '<'],
		];

		$excludedUsers = $this->config->getSystemValue('activity_expire_exclude_users', []);
		if (is_array($excludedUsers)) {
			foreach ($excludedUsers as $user) {
				$conditions[] = ['affecteduser', $user, '!='];
			}
		}

		$this->deleteActivities($conditions);
	}

	/**
	 * Delete activities that match certain conditions
	 *
	 * @param array $conditions List of conditions that all have to be met (combined with AND).
	 *                          Each condition is a [column, value, operator] tuple, where the
	 *                          operator is optional and defaults to '=':
	 *                          ['field', 'value']        => `field` = 'value'
	 *                          ['field', 'value', '!=']  => `field` != 'value'
	 * @psalm-param list<array{0: string, 1: mixed, 2?: string}> $conditions
	 */
	public function deleteActivities(array $conditions): void {
		if ($this->connection->getDatabaseProvider() === IDBConnection::PLATFORM_MYSQL) {
			$this->logger->debug('Choosing chunked activity delete for MySQL/MariaDB', ['app' => 'activity']);
			$this->deleteActivitiesForMySQL($conditions);
			return;
		}
		$this->logger->debug('Choosing regular activity delete', ['app' => 'activity']);
		$deleteQuery = $this->connection->getQueryBuilder();
		$deleteQuery->delete('activity');

		$this->applyConditions($deleteQuery, $conditions);
		// Dont use chunked delete - let the DB handle the large row count natively
		$deleteQuery->executeStatement();
	}

	/**
	 * Apply a list of conditions to a query, combined with AND.
	 *
	 * Using andWhere() for every condition is required: where() would replace any
	 * previously set restriction, silently dropping all but the last condition.
	 *
	 * @param array $conditions List of [column, value, operator] tuples; operator defaults to '='
	 * @psalm-param list<array{0: string, 1: mixed, 2?: string}> $conditions
	 */
	private function applyConditions(IQueryBuilder $query, array $conditions): void {
		foreach ($conditions as $condition) {
			$column = $condition[0];
			$value = $condition[1];
			$operation = $condition[2] ?? '=';
			$query->andWhere($query->expr()->comparison($column, $operation, $query->createNamedParameter($value)));
		}
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

		return $query->executeQuery()->fetch() ?: [];
	}

	/**
	 * Add galera safe delete chunking if using mysql
	 * Stops us hitting wsrep_max_ws_rows when large row counts are deleted
	 */
	private function deleteActivitiesForMySQL(array $conditions): void {
		$query = $this->connection->getQueryBuilder();
		$query->select('activity_id')
			->from('activity');

		$this->applyConditions($query, $conditions);

		$query->setMaxResults(50000);
		$result = $query->executeQuery();
		$count = $result->rowCount();
		if ($count === 0) {
			return;
		}
		$ids = array_map(static fn (array $id): int => (int)$id[0], $result->fetchAll(\PDO::FETCH_NUM));
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
