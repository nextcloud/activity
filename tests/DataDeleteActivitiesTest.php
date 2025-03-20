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
use OCP\IConfig;
use Psr\Log\LoggerInterface;

/**
 * Class DataDeleteActivitiesTest
 *
 * @group DB
 * @package OCA\Activity\Tests
 */
class DataDeleteActivitiesTest extends TestCase {
	/** @var Data */
	protected $data;

	protected function setUp(): void {
		parent::setUp();

		$activities = [
			['affectedUser' => 'delete', 'subject' => 'subject', 'time' => 0],
			['affectedUser' => 'delete', 'subject' => 'subject2', 'time' => time() - 2 * 365 * 24 * 3600],
			['affectedUser' => 'otherUser', 'subject' => 'subject', 'time' => time()],
			['affectedUser' => 'otherUser', 'subject' => 'subject2', 'time' => time()],
		];

		$queryActivity = \OC::$server->getDatabaseConnection()->prepare('INSERT INTO `*PREFIX*activity`(`app`, `subject`, `subjectparams`, `message`, `messageparams`, `file`, `link`, `user`, `affecteduser`, `timestamp`, `priority`, `type`)' . ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
		foreach ($activities as $activity) {
			$queryActivity->execute([
				'app',
				$activity['subject'],
				json_encode([]),
				'',
				json_encode([]),
				'file',
				'link',
				'user',
				$activity['affectedUser'],
				$activity['time'],
				IExtension::PRIORITY_MEDIUM,
				'test',
			]);
		}
		$this->data = new Data(
			$this->createMock(IManager::class),
			\OC::$server->getDatabaseConnection(),
			$this->createMock(LoggerInterface::class),
			$this->createMock(IConfig::class),
		);
	}

	protected function tearDown(): void {
		$this->data->deleteActivities([
			'type' => 'test',
		]);

		parent::tearDown();
	}

	public function deleteActivitiesData(): array {
		return [
			[['affecteduser' => 'delete'], ['otherUser']],
			[['affecteduser' => ['delete', '=']], ['otherUser']],
			[['timestamp' => [time() - 10, '<']], ['otherUser']],
			[['timestamp' => [time() - 10, '>']], ['delete']],
		];
	}

	/**
	 * @dataProvider deleteActivitiesData
	 */
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
		$backgroundjob->setId(1);
		$this->assertUserActivities(['delete', 'otherUser']);
		$jobList = $this->createMock(IJobList::class);
		$backgroundjob->execute($jobList);
		$this->assertUserActivities(['otherUser']);
	}

	protected function assertUserActivities(array $expected) {
		$query = \OC::$server->getDatabaseConnection()->prepare("SELECT `affecteduser` FROM `*PREFIX*activity` WHERE `type` = 'test'");
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
