<?php

/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Citharel <nextcloud@tcit.fr>
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

namespace OCA\Activity\Tests;

use OCA\Activity\AppInfo\Application;
use OCA\Activity\Data;
use OCA\Activity\UserSettings;
use OCP\Activity\Exceptions\FilterNotFoundException;
use OCP\Activity\IManager;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\Files\IRootFolder;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IL10N;
use OCP\Server;
use OCP\Util;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;

/**
 * Class DataTest
 * @package OCA\Activity\Tests
 */
#[Group('DB')]
class DataTest extends TestCase {
	protected Data $data;
	protected IL10N $activityLanguage;
	protected IDBConnection $dbConnection;
	protected IManager $realActivityManager;
	protected NullLogger $logger;
	protected IConfig&MockObject $config;
	protected IRootFolder&MockObject $rootFolder;

	protected function setUp(): void {
		parent::setUp();

		$this->activityLanguage = Util::getL10N(Application::APP_ID, 'en');
		$this->dbConnection = Server::get(IDBConnection::class);
		$this->realActivityManager = Server::get(IManager::class);
		$this->logger = Server::get(NullLogger::class);

		$activityManager = $this->createMock(IManager::class);
		$this->config = $this->createMock(IConfig::class);
		$this->rootFolder = $this->createMock(IRootFolder::class);

		$this->data = new Data(
			$activityManager,
			$this->dbConnection,
			$this->logger,
			$this->config,
			$this->rootFolder,
		);
	}

	public static function dataSend(): array {
		return [
			// Default case
			['author', 'affectedUser', 'author', 'affectedUser', true],
			// Public page / Incognito mode
			['', 'affectedUser', '', 'affectedUser', true],
			// No affected user => no activity
			['author', '', 'author', '', false],
			// No affected user and no author => no activity
			['', '', '', '', false],
		];
	}

	#[DataProvider('dataSend')]
	public function testSend(string $actionUser, string $affectedUser, string $expectedAuthor, string $expectedAffected, bool $expectedActivity): void {
		$this->deleteTestActivities();

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setSubject('subject');

		if ($affectedUser !== '') {
			$event->setAffectedUser($affectedUser);
		}

		if ($actionUser !== '') {
			$event->setAuthor($actionUser);
		}

		$this->assertSame($expectedActivity, $this->data->send($event) !== 0);

		$qb = $this->dbConnection->getQueryBuilder();
		$query = $qb->select('user', 'affecteduser')
			->from('activity')
			->where($qb->expr()->eq('app', $qb->createNamedParameter('test')))
			->orderBy('activity_id', 'DESC');
		$result = $query->executeQuery();
		$row = $result->fetch();

		if ($expectedActivity) {
			$this->assertEquals(['user' => $expectedAuthor, 'affecteduser' => $expectedAffected], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestActivities();
	}

	#[DataProvider('dataSend')]
	public function testStoreMail(string $actionUser, string $affectedUser, string $expectedAuthor, string $expectedAffected, bool $expectedActivity): void {
		$this->deleteTestMails();

		$time = time();

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setSubject('subject')
			->setTimestamp($time);

		if ($affectedUser !== '') {
			$event->setAffectedUser($affectedUser);
		}

		$this->assertSame($expectedActivity, $this->data->storeMail($event, $time + 10));

		$qb = $this->dbConnection->getQueryBuilder();
		$query = $qb->select('amq_latest_send', 'amq_affecteduser')
			->from('activity_mq')
			->where($qb->expr()->eq('amq_appid', $qb->createNamedParameter('test')))
			->orderBy('mail_id', 'DESC');
		$result = $query->executeQuery();
		$row = $result->fetch();

		if ($expectedActivity) {
			$this->assertEquals(['amq_latest_send' => $time + 10, 'amq_affecteduser' => $expectedAffected], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestMails();
	}

	public function testBulkSend(): void {
		$this->deleteTestActivities();

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setAuthor('author')
			->setTimestamp(time())
			->setSubject('subject', ['param1'])
			->setMessage('message', ['msgParam1'])
			->setObject('files', 42, 'file.txt')
			->setLink('https://example.com');

		$affectedUsers = ['user1', 'user2', 'user3'];
		$activityIds = $this->data->bulkSend($event, $affectedUsers);

		$this->assertCount(3, $activityIds);
		// Values should be the affected user strings
		$this->assertEqualsCanonicalizing($affectedUsers, array_values($activityIds));
		// Keys should be positive integer IDs
		foreach (array_keys($activityIds) as $id) {
			$this->assertGreaterThan(0, $id);
		}

		// Verify rows in DB
		$qb = $this->dbConnection->getQueryBuilder();
		$query = $qb->select('user', 'affecteduser', 'app', 'subject', 'object_type', 'object_id')
			->from('activity')
			->where($qb->expr()->eq('app', $qb->createNamedParameter('test')))
			->orderBy('activity_id', 'ASC');
		$result = $query->executeQuery();
		$rows = $result->fetchAll();

		$this->assertCount(3, $rows);
		foreach ($rows as $i => $row) {
			$this->assertSame('author', $row['user']);
			$this->assertSame($affectedUsers[$i], $row['affecteduser']);
			$this->assertSame('test', $row['app']);
			$this->assertSame('subject', $row['subject']);
			$this->assertSame('files', $row['object_type']);
			$this->assertEquals(42, $row['object_id']);
		}

		$this->deleteTestActivities();
	}

	public function testBulkSendEmptyUsers(): void {
		$this->deleteTestActivities();

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType('type')
			->setAuthor('author')
			->setTimestamp(time())
			->setSubject('subject');

		$activityIds = $this->data->bulkSend($event, []);
		$this->assertEmpty($activityIds);

		$this->deleteTestActivities();
	}

	public static function dataSetOffsetFromSince(): array {
		return [
			['ASC', '`timestamp` >= \'123465789\'', '`activity_id` > \'{id}\'', null, null, null],
			['DESC', '`timestamp` <= \'123465789\'', '`activity_id` < \'{id}\'', null, null, null],
			['DESC', null, null, 'invalid-user', null, null],
			['DESC', null, null, null, 1, 'X-Activity-First-Known'],
			['DESC', null, null, 'user', false, null],
		];
	}

	#[DataProvider('dataSetOffsetFromSince')]
	public function testSetOffsetFromSince(string $sort, ?string $timestampWhere, ?string $idWhere, ?string $offsetUser, $offsetId, ?string $expectedHeader): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('testing');
		if ($offsetUser === null) {
			$offsetUser = $user;
		} elseif ($offsetUser === 'invalid-user') {
			$this->expectException('OutOfBoundsException');
			$this->expectExceptionMessage('Invalid since');
			$this->expectExceptionCode(2);
		}

		$query = $this->dbConnection->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('test'),
				'affecteduser' => $query->createNamedParameter($user),
				'timestamp' => 123465789,
				'subject' => $query->createNamedParameter('subject'),
				'subjectparams' => $query->createNamedParameter('subjectparams'),
				'priority' => 1,
			])
			->executeStatement();
		$id = $query->getLastInsertId();

		$mock = $this->getMockBuilder(IQueryBuilder::class)
			->disableOriginalConstructor()
			->getMock();
		$mock
			->method('expr')
			->willReturn($query->expr());
		$mock
			->method('createNamedParameter')
			->willReturnCallback(function ($arg) use ($query) {
				return $query->expr()->literal($arg);
			});
		if ($timestampWhere !== null && $idWhere !== null) {
			$mock->expects($this->exactly(2))
				->method('andWhere');
		} else {
			$mock->expects($this->never())
				->method('andWhere');
		}

		if ($offsetId === null) {
			$offsetId = $id;
		} elseif ($offsetId === false) {
			$offsetId = 0;
		} else {
			$offsetId += $id;
		}

		$headers = self::invokePrivate($this->data, 'setOffsetFromSince', [$mock, $offsetUser, $offsetId, $sort]);

		if ($expectedHeader) {
			$this->assertArrayHasKey($expectedHeader, $headers);
			$this->assertEquals($id, $headers[$expectedHeader]);
		} else {
			$this->assertCount(0, $headers);
		}

		$this->deleteTestActivities();
	}

	public function testCountDownloads(): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('testing');
		$objectId = 12345;
		$now = time();

		$insertRow = function (string $affectedUser, int $objectId, int $timestamp) {
			$query = $this->dbConnection->getQueryBuilder();
			$query->insert('activity')
				->values([
					'app' => $query->createNamedParameter('files_sharing'),
					'type' => $query->createNamedParameter('public_links'),
					'affecteduser' => $query->createNamedParameter($affectedUser),
					'user' => $query->createNamedParameter(''),
					'timestamp' => $query->createNamedParameter($timestamp, IQueryBuilder::PARAM_INT),
					'subject' => $query->createNamedParameter('public_shared_file_downloaded'),
					'subjectparams' => $query->createNamedParameter('[]'),
					'message' => $query->createNamedParameter(''),
					'messageparams' => $query->createNamedParameter('[]'),
					'priority' => $query->createNamedParameter(30, IQueryBuilder::PARAM_INT),
					'object_type' => $query->createNamedParameter('files'),
					'object_id' => $query->createNamedParameter($objectId, IQueryBuilder::PARAM_INT),
				])
				->executeStatement();
		};

		// 3 downloads: 2 recent (within 30 days), 1 old
		$insertRow($user, $objectId, $now - 5 * 24 * 3600);
		$insertRow($user, $objectId, $now - 15 * 24 * 3600);
		$insertRow($user, $objectId, $now - 60 * 24 * 3600);
		// Download for a different file — should not be counted
		$insertRow($user, 99999, $now);
		// Download for a different user — should not be counted
		$insertRow('other_user', $objectId, $now);

		$this->assertSame(3, $this->data->countDownloads($user, $objectId));
		$this->assertSame(2, $this->data->countDownloads($user, $objectId, $now - 30 * 24 * 3600));
		$this->assertSame(0, $this->data->countDownloads($user, $objectId, $now + 1));

		$this->deleteTestActivities();
	}

	public function testDeleteAffectedUserActivities(): void {
		$this->deleteTestActivities();
		$user1 = self::getUniqueID('testing');
		$user2 = self::getUniqueID('testing');
		$this->insertActivityForAffectedUser($user1);
		$this->insertActivityForAffectedUser($user2);

		$this->assertEquals(1, $this->countActivitiesForAffectedUser($user1));
		$this->assertEquals(1, $this->countActivitiesForAffectedUser($user2));
		$this->data->deleteActivities([['affecteduser', $user1]]);
		$this->assertEquals(0, $this->countActivitiesForAffectedUser($user1));
		$this->assertEquals(1, $this->countActivitiesForAffectedUser($user2));
		$this->deleteTestActivities();
	}

	public static function dataExcludedAuthor(): array {
		return [
			// author+type match → blocked
			['alice', 'target', 'file_created', ['alice' => ['file_created']], false],
			// type mismatch → allowed
			['alice', 'target', 'file_created', ['alice' => ['file_deleted']], true],
			// different user → allowed
			['bob', 'target', 'file_created', ['alice' => ['file_created']], true],
			// empty config → allowed
			['alice', 'target', 'file_created', [], true],
			// non-array rule → allowed
			['alice', 'target', 'file_created', ['alice' => 'file_created'], true],
		];
	}

	#[DataProvider('dataExcludedAuthor')]
	public function testSendWithExcludedAuthor(string $author, string $affectedUser, string $type, array $excludedUsers, bool $expectedInsert): void {
		$this->deleteTestActivities();

		$this->config->method('getSystemValue')
			->with('activity_log_exclude_users', [])
			->willReturn($excludedUsers);

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType($type)
			->setAuthor($author)
			->setAffectedUser($affectedUser)
			->setSubject('subject');

		$result = $this->data->send($event);
		$this->assertSame($expectedInsert, $result !== 0);

		$qb = $this->dbConnection->getQueryBuilder();
		$row = $qb->select('user', 'affecteduser')
			->from('activity')
			->where($qb->expr()->eq('app', $qb->createNamedParameter('test')))
			->orderBy('activity_id', 'DESC')
			->executeQuery()
			->fetch();

		if ($expectedInsert) {
			$this->assertEquals(['user' => $author, 'affecteduser' => $affectedUser], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestActivities();
	}

	#[DataProvider('dataExcludedAuthor')]
	public function testStoreMailWithExcludedAuthor(string $author, string $affectedUser, string $type, array $excludedUsers, bool $expectedInsert): void {
		$this->deleteTestMails();

		$this->config->method('getSystemValue')
			->with('activity_log_exclude_users', [])
			->willReturn($excludedUsers);

		$time = time();
		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType($type)
			->setAuthor($author)
			->setAffectedUser($affectedUser)
			->setSubject('subject')
			->setTimestamp($time);

		$this->assertSame($expectedInsert, $this->data->storeMail($event, $time + 10));

		$qb = $this->dbConnection->getQueryBuilder();
		$row = $qb->select('amq_latest_send', 'amq_affecteduser')
			->from('activity_mq')
			->where($qb->expr()->eq('amq_appid', $qb->createNamedParameter('test')))
			->orderBy('mail_id', 'DESC')
			->executeQuery()
			->fetch();

		if ($expectedInsert) {
			$this->assertEquals(['amq_latest_send' => $time + 10, 'amq_affecteduser' => $affectedUser], $row);
		} else {
			$this->assertFalse($row);
		}

		$this->deleteTestMails();
	}

	#[DataProvider('dataExcludedAuthor')]
	public function testBulkSendWithExcludedAuthor(string $author, string $_affectedUser, string $type, array $excludedUsers, bool $expectedInsert): void {
		$this->deleteTestActivities();

		$this->config->method('getSystemValue')
			->with('activity_log_exclude_users', [])
			->willReturn($excludedUsers);

		$event = $this->realActivityManager->generateEvent();
		$event->setApp('test')
			->setType($type)
			->setAuthor($author)
			->setSubject('subject')
			->setTimestamp(time());

		$bulkUsers = ['user1', 'user2'];
		$result = $this->data->bulkSend($event, $bulkUsers);

		if ($expectedInsert) {
			$this->assertCount(2, $result);
			$this->assertEqualsCanonicalizing($bulkUsers, array_values($result));
		} else {
			$this->assertEmpty($result);
		}

		$qb = $this->dbConnection->getQueryBuilder();
		$count = (int)$qb->select($qb->func()->count('activity_id', 'count'))
			->from('activity')
			->where($qb->expr()->eq('app', $qb->createNamedParameter('test')))
			->executeQuery()
			->fetch()['count'];

		$this->assertSame($expectedInsert ? 2 : 0, $count);

		$this->deleteTestActivities();
	}

	/**
	 * Delete all testing activities
	 */
	protected function deleteTestActivities(): void {
		$query = $this->dbConnection->getQueryBuilder();
		$query->delete('activity')
			->where($query->expr()->eq('app', $query->createNamedParameter('test')));
		$query->executeStatement();
	}

	/**
	 * Delete all testing mails
	 */
	protected function deleteTestMails(): void {
		$query = $this->dbConnection->getQueryBuilder();
		$query->delete('activity_mq')
			->where($query->expr()->eq('amq_appid', $query->createNamedParameter('test')));
		$query->executeStatement();
	}

	private function insertActivityForAffectedUser(string $user): void {
		$query = $this->dbConnection->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('test'),
				'affecteduser' => $query->createNamedParameter($user),
				'timestamp' => 123465789,
				'subject' => $query->createNamedParameter('subject'),
				'subjectparams' => $query->createNamedParameter('subjectparams'),
				'priority' => 1,
			])
			->executeStatement();
	}

	/**
	 * A Data whose filter lookup always misses, so only the user and the date
	 * window restrict the query. Keeps these tests about the day bucketing
	 * rather than about the filter plumbing, which the stream tests cover.
	 */
	private function getUnfilteredData(): Data {
		$activityManager = $this->createMock(IManager::class);
		$activityManager->method('getFilterById')
			->willThrowException(new FilterNotFoundException('all'));

		return new Data(
			$activityManager,
			$this->dbConnection,
			$this->logger,
			$this->config,
			$this->rootFolder,
		);
	}

	private function getHistogramUserSettings(): UserSettings&MockObject {
		$userSettings = $this->createMock(UserSettings::class);
		$userSettings->method('getUserSetting')->willReturn(false);
		return $userSettings;
	}

	/**
	 * Insert one activity for the given user at the given moment.
	 */
	private function insertActivityAt(string $affectedUser, int $timestamp, string $author = 'author'): void {
		$query = $this->dbConnection->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('test'),
				'type' => $query->createNamedParameter('file_changed'),
				'affecteduser' => $query->createNamedParameter($affectedUser),
				'user' => $query->createNamedParameter($author),
				'timestamp' => $query->createNamedParameter($timestamp, IQueryBuilder::PARAM_INT),
				'subject' => $query->createNamedParameter('subject'),
				'subjectparams' => $query->createNamedParameter('[]'),
				'message' => $query->createNamedParameter(''),
				'messageparams' => $query->createNamedParameter('[]'),
				'priority' => $query->createNamedParameter(30, IQueryBuilder::PARAM_INT),
				'object_type' => $query->createNamedParameter('files'),
				'object_id' => $query->createNamedParameter(1, IQueryBuilder::PARAM_INT),
			])
			->executeStatement();
	}

	public function testGetDailyCountsGroupsByDayAndSkipsEmptyDays(): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('histogram');
		$timezone = new \DateTimeZone('UTC');

		$day = static fn (string $date, string $time = '12:00:00'): int
			=> (new \DateTimeImmutable($date . ' ' . $time, new \DateTimeZone('UTC')))->getTimestamp();

		$this->insertActivityAt($user, $day('2024-03-01', '00:00:00'));
		$this->insertActivityAt($user, $day('2024-03-01', '23:59:59'));
		$this->insertActivityAt($user, $day('2024-03-03'));
		// Another account's activity must not be counted
		$this->insertActivityAt(self::getUniqueID('other'), $day('2024-03-01'));

		$result = $this->getUnfilteredData()->getDailyCounts(
			$this->getHistogramUserSettings(),
			$user,
			'all',
			$day('2024-03-01', '00:00:00'),
			$day('2024-03-05', '23:59:59'),
			$timezone,
		);

		// 2024-03-02 is absent rather than zero: the client fills the gaps, so
		// the payload stays proportional to real activity
		$counts = $result['counts'];
		ksort($counts);
		$this->assertSame(['2024-03-01' => 2, '2024-03-03' => 1], $counts);
		$this->assertNull($result['partialBefore']);

		$this->deleteTestActivities();
	}

	public function testGetDailyCountsResolvesDaysInTheGivenTimezone(): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('histogram');

		// 23:30 UTC on 1 March is already 00:30 on 2 March in Berlin (UTC+1)
		$moment = (new \DateTimeImmutable('2024-03-01 23:30:00', new \DateTimeZone('UTC')))->getTimestamp();
		$this->insertActivityAt($user, $moment);

		$from = $moment - 86400;
		$to = $moment + 86400;
		$data = $this->getUnfilteredData();

		$utc = $data->getDailyCounts($this->getHistogramUserSettings(), $user, 'all', $from, $to, new \DateTimeZone('UTC'));
		$berlin = $data->getDailyCounts($this->getHistogramUserSettings(), $user, 'all', $from, $to, new \DateTimeZone('Europe/Berlin'));

		$this->assertSame(['2024-03-01' => 1], $utc['counts']);
		$this->assertSame(['2024-03-02' => 1], $berlin['counts']);

		$this->deleteTestActivities();
	}

	public function testGetDailyCountsExcludesActivityOutsideTheWindow(): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('histogram');
		$timezone = new \DateTimeZone('UTC');

		$at = static fn (string $date): int
			=> (new \DateTimeImmutable($date . ' 12:00:00', new \DateTimeZone('UTC')))->getTimestamp();

		$this->insertActivityAt($user, $at('2024-03-10'));
		$this->insertActivityAt($user, $at('2024-03-20'));

		$result = $this->getUnfilteredData()->getDailyCounts(
			$this->getHistogramUserSettings(),
			$user,
			'all',
			$at('2024-03-15'),
			$at('2024-03-25'),
			$timezone,
		);

		$this->assertSame(['2024-03-20' => 1], $result['counts']);

		$this->deleteTestActivities();
	}

	public function testGetActorsListsEveryAuthorInTheStream(): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('actors');
		$time = time();

		$this->insertActivityAt($user, $time, 'charlie');
		$this->insertActivityAt($user, $time - 10, 'alice');
		// A second activity by the same author is one entry, not two
		$this->insertActivityAt($user, $time - 20, 'alice');
		// Another account's stream must not leak into this one
		$this->insertActivityAt(self::getUniqueID('other'), $time, 'mallory');

		$actors = $this->getUnfilteredData()->getActors($this->getHistogramUserSettings(), $user, 'all');

		$this->assertSame(['alice', 'charlie'], $actors);

		$this->deleteTestActivities();
	}

	public function testGetActorsIsUnaffectedByTheDateOfTheActivity(): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('actors');

		// An author whose only activity is old still has to be offerable, or a
		// reader could never filter their way back to it
		$this->insertActivityAt($user, time() - (300 * 86400), 'alice');

		$actors = $this->getUnfilteredData()->getActors($this->getHistogramUserSettings(), $user, 'all');

		$this->assertSame(['alice'], $actors);

		$this->deleteTestActivities();
	}

	public function testGetActorsSkipsActivitiesWithoutAnAuthor(): void {
		$this->deleteTestActivities();
		$user = self::getUniqueID('actors');
		$time = time();

		$this->insertActivityAt($user, $time, '');
		$this->insertActivityAt($user, $time - 10, 'alice');

		$actors = $this->getUnfilteredData()->getActors($this->getHistogramUserSettings(), $user, 'all');

		$this->assertSame(['alice'], $actors);

		$this->deleteTestActivities();
	}

	public function testGetActorsRejectsAnEmptyUser(): void {
		$this->expectException(\OutOfBoundsException::class);
		$this->getUnfilteredData()->getActors($this->getHistogramUserSettings(), '', 'all');
	}

	public function testGetDailyCountsRejectsAnEmptyUser(): void {
		$this->expectException(\OutOfBoundsException::class);
		$this->getUnfilteredData()->getDailyCounts(
			$this->getHistogramUserSettings(),
			'',
			'all',
			0,
			1,
			new \DateTimeZone('UTC'),
		);
	}

	private function countActivitiesForAffectedUser(string $user): int {
		$query = $this->dbConnection->getQueryBuilder();
		$query->select($query->func()->count('activity_id', 'count'))
			->from('activity')
			->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)));
		$result = $query->executeQuery();
		$row = $result->fetch();
		return $row['count'];
	}
}
