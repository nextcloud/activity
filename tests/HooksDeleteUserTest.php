<?php

declare(strict_types=1);
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

use OC;
use OCA\Activity\Data;
use OCA\Activity\Listener\UserDeleted;
use OCA\Activity\MailQueueHandler;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\IUser;
use OCP\User\Events\UserDeletedEvent;

/**
 * Class HooksDeleteUserTest
 *
 * @group DB
 * @package OCA\Activity\Tests
 */
class HooksDeleteUserTest extends TestCase {
	/** @var Data */
	private $data;

	/** @var MailQueueHandler */
	private $mailQueueHandler;

	/** @var IDBConnection */
	private $dbConnection;

	/**
	 * @throws Exception
	 */
	protected function setUp(): void {
		$this->dbConnection = OC::$server->get(IDBConnection::class);
		$this->data = new Data($this->createMock(IManager::class), $this->dbConnection);
		$this->mailQueueHandler = OC::$server->get(MailQueueHandler::class);

		parent::setUp();

		$activities = [
			['affectedUser' => 'delete', 'subject' => 'subject'],
			['affectedUser' => 'delete', 'subject' => 'subject2'],
			['affectedUser' => 'otherUser', 'subject' => 'subject'],
			['affectedUser' => 'otherUser', 'subject' => 'subject2'],
		];


		foreach ($activities as $activity) {
			$this->insertActivity($activity);
			$this->insertActivityMQ($activity);
		}
	}

	/**
	 * @throws Exception
	 */
	protected function tearDown(): void {
		$this->data->deleteActivities([
			'type' => 'test',
		]);
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->delete('activity_mq')
			->where($qb->expr()->eq('amq_type', $qb->createNamedParameter('test')));
		$qb->executeStatement();

		parent::tearDown();
	}

	/**
	 * @throws Exception
	 */
	public function testHooksDeleteUser(): void {
		$this->assertUserActivities(['delete', 'otherUser']);
		$this->assertUserMailQueue(['delete', 'otherUser']);
		$user = $this->createMock(IUser::class);
		$user->expects($this->exactly(2))->method('getUID')->willReturn('delete');
		$userDeleted = new UserDeleted($this->data, $this->mailQueueHandler);
		$userDeleted->handle(new UserDeletedEvent($user));
		$this->assertUserActivities(['otherUser']);
		$this->assertUserMailQueue(['otherUser']);
	}

	/**
	 * @throws Exception
	 */
	protected function assertUserActivities(array $expected): void {
		$qb = $this->dbConnection->getQueryBuilder();
		$query = $qb->select('affecteduser')
			->from('activity')
			->where($qb->expr()->eq('type', $qb->createNamedParameter('test')));
		$this->assertTableKeys($expected, $query, 'affecteduser');
	}

	/**
	 * @throws Exception
	 */
	protected function assertUserMailQueue(array $expected): void {
		$qb = $this->dbConnection->getQueryBuilder();
		$query = $qb->select('amq_affecteduser')
			->from('activity_mq')
			->where($qb->expr()->eq('amq_type', $qb->createNamedParameter('test')));
		$this->assertTableKeys($expected, $query, 'amq_affecteduser');
	}

	/**
	 * @throws Exception
	 */
	protected function assertTableKeys(array $expected, IQueryBuilder $query, string $keyName): void {
		$result = $query->executeQuery();

		$users = [];
		while ($row = $result->fetch()) {
			$users[] = $row[$keyName];
		}
		$result->closeCursor();
		$users = array_unique($users);
		sort($users);
		sort($expected);

		$this->assertEquals($expected, $users);
	}

	/**
	 * @throws Exception
	 */
	private function insertActivity(array $activity): void {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->insert('activity')
			->setValue('app', $qb->createNamedParameter('app'))
			->setValue('subject', $qb->createNamedParameter($activity['subject']))
			->setValue('subjectparams', $qb->createNamedParameter(json_encode([])))
			->setValue('message', $qb->createNamedParameter(''))
			->setValue('messageparams', $qb->createNamedParameter(json_encode([])))
			->setValue('file', $qb->createNamedParameter('file'))
			->setValue('user', $qb->createNamedParameter('user'))
			->setValue('affecteduser', $qb->createNamedParameter($activity['affectedUser']))
			->setValue('timestamp', $qb->createNamedParameter(time()))
			->setValue('priority', $qb->createNamedParameter(IExtension::PRIORITY_MEDIUM))
			->setValue('type', $qb->createNamedParameter('test'))
			->executeStatement();
	}

	/**
	 * @throws Exception
	 */
	private function insertActivityMQ(array $activityMQ): void {
		$qb = $this->dbConnection->getQueryBuilder();
		$qb->insert('activity_mq')
			->setValue('amq_appid', $qb->createNamedParameter('app'))
			->setValue('amq_subject', $qb->createNamedParameter($activityMQ['subject']))
			->setValue('amq_subjectparams', $qb->createNamedParameter(json_encode([])))
			->setValue('amq_affecteduser', $qb->createNamedParameter($activityMQ['affectedUser']))
			->setValue('amq_timestamp', $qb->createNamedParameter(time()))
			->setValue('amq_type', $qb->createNamedParameter('test'))
			->setValue('amq_latest_send', $qb->createNamedParameter(time() + 10))
			->executeStatement();
	}
}
