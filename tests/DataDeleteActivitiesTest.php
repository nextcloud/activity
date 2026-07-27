<?php

declare(strict_types=1);
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

namespace OCA\Activity\Tests;

use OCA\Activity\BackgroundJob\ExpireActivities;
use OCA\Activity\Data;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\IJobList;
use OCP\DB\IPreparedStatement;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\Server;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Psr\Log\LoggerInterface;

/**
 * Class DataDeleteActivitiesTest
 *
 * @group DB
 * @package OCA\Activity\Tests
 */
#[Group('DB')]
class DataDeleteActivitiesTest extends TestCase {
	protected Data $data;

	protected function setUp(): void {
		parent::setUp();

		$this->insertActivity('delete', 0, 'subject');
		$this->insertActivity('delete', time() - 2 * 365 * 24 * 3600, 'subject2');
		$this->insertActivity('otherUser', time(), 'subject');
		$this->insertActivity('otherUser', time(), 'subject2');

		$this->data = new Data(
			$this->createMock(IManager::class),
			Server::get(IDBConnection::class),
			$this->createMock(LoggerInterface::class),
			$this->createMock(IConfig::class),
		);
	}

	private function insertActivity(string $affectedUser, int $time, string $subject = 'subject'): void {
		$query = Server::get(IDBConnection::class)->getQueryBuilder();
		$query->insert('activity')
			->values([
				'app' => $query->createNamedParameter('app'),
				'subject' => $query->createNamedParameter($subject),
				'subjectparams' => $query->createNamedParameter(json_encode([])),
				'message' => $query->createNamedParameter(''),
				'messageparams' => $query->createNamedParameter(json_encode([])),
				'file' => $query->createNamedParameter('file'),
				'link' => $query->createNamedParameter('link'),
				'user' => $query->createNamedParameter('user'),
				'affecteduser' => $query->createNamedParameter($affectedUser),
				'timestamp' => $query->createNamedParameter($time, IQueryBuilder::PARAM_INT),
				'priority' => $query->createNamedParameter(IExtension::PRIORITY_MEDIUM, IQueryBuilder::PARAM_INT),
				'type' => $query->createNamedParameter('test'),
			]);
		$query->executeStatement();
	}

	protected function tearDown(): void {
		$this->data->deleteActivities([
			['type', 'test'],
		]);

		parent::tearDown();
	}

	public static function deleteActivitiesData(): array {
		return [
			[[['affecteduser', 'delete']], ['otherUser']],
			[[['affecteduser', 'delete', '=']], ['otherUser']],
			[[['timestamp', time() - 10, '<']], ['otherUser']],
			[[['timestamp', time() - 10, '>']], ['delete']],
		];
	}

	#[DataProvider('deleteActivitiesData')]
	public function testDeleteActivities(array $condition, array $expected): void {
		$this->assertUserActivities(['delete', 'otherUser']);
		$this->data->deleteActivities($condition);
		$this->assertUserActivities($expected);
	}

	public function testExpireActivities(): void {
		$config = $this->createMock(IConfig::class);
		$time = $this->createMock(ITimeFactory::class);
		$time->method('getTime')
			->willReturn(time());
		$backgroundjob = new ExpireActivities(
			$time,
			$this->data,
			$config
		);
		$backgroundjob->setId('1');
		$this->assertUserActivities(['delete', 'otherUser']);
		$jobList = $this->createMock(IJobList::class);
		$backgroundjob->start($jobList);
		$this->assertUserActivities(['otherUser']);
	}

	/**
	 * Regression test for https://github.com/nextcloud/activity/issues/2647
	 *
	 * With 'activity_expire_exclude_users' set, expire() builds more than one
	 * condition. This must:
	 *  - not crash (the excluded users used to be added in a shape the delete
	 *    query could not parse), and
	 *  - keep ANDing every condition together. On MySQL/MariaDB the chunked
	 *    delete used where() in a loop, which dropped all but the last condition
	 *    and would have deleted recent, non-expired activity.
	 */
	public function testExpireActivitiesWithExcludedUsers(): void {
		// Old activity for a user that is NOT excluded -> must still be deleted.
		$this->insertActivity('expired', 0, 'subject');

		$config = $this->createMock(IConfig::class);
		$config->method('getSystemValue')
			->willReturnCallback(static function ($key, $default) {
				if ($key === 'activity_expire_exclude_users') {
					return ['delete', 'otherUser'];
				}
				return $default;
			});
		$data = new Data(
			$this->createMock(IManager::class),
			Server::get(IDBConnection::class),
			$this->createMock(LoggerInterface::class),
			$config,
		);

		$time = $this->createMock(ITimeFactory::class);
		$time->method('getTime')
			->willReturn(time());
		$backgroundjob = new ExpireActivities($time, $data, $config);
		$backgroundjob->setId('1');

		$this->assertUserActivities(['delete', 'expired', 'otherUser']);
		$backgroundjob->start($this->createMock(IJobList::class));

		// 'delete' (old) survives because it is excluded; 'otherUser' (recent)
		// survives; 'expired' (old, not excluded) is the only one deleted.
		$this->assertUserActivities(['delete', 'otherUser']);
	}

	protected function assertUserActivities(array $expected): void {
		$query = Server::get(IDBConnection::class)
			->prepare("SELECT `affecteduser` FROM `*PREFIX*activity` WHERE `type` = 'test'");
		$this->assertTableKeys($expected, $query, 'affecteduser');
	}

	protected function assertTableKeys(array $expected, IPreparedStatement $query, string $keyName): void {
		$query->execute();

		$users = [];
		while ($row = $query->fetch()) {
			$users[] = $row[$keyName];
		}
		$query->closeCursor();
		$users = array_unique($users);
		sort($users);
		sort($expected);

		$this->assertEquals($expected, $users);
	}
}
