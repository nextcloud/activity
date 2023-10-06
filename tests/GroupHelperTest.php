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

use OCA\Activity\GroupHelper;
use OCA\Activity\GroupHelperDisabled;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\IL10N;
use OCP\RichObjectStrings\IValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class GroupHelperTest extends TestCase {
	/** @var IManager|MockObject */
	protected $activityManager;
	/** @var IValidator|MockObject */
	protected $validator;
	/** @var LoggerInterface|MockObject */
	protected $logger;
	/** @var IL10N|MockObject */
	protected $l;

	protected function setUp(): void {
		parent::setUp();

		$this->l = $this->createMock(IL10N::class);
		$this->activityManager = $this->createMock(IManager::class);
		$this->validator = $this->createMock(IValidator::class);
		$this->logger = $this->createMock(LoggerInterface::class);
	}

	/**
	 * @param array $methods
	 * @param bool $grouping
	 * @return GroupHelper|MockObject
	 */
	protected function getHelper(array $methods = [], $grouping = false): GroupHelper {
		if (empty($methods)) {
			if ($grouping) {
				return new GroupHelper(
					$this->l,
					$this->activityManager,
					$this->validator,
					$this->logger
				);
			}
			return new GroupHelperDisabled(
				$this->l,
				$this->activityManager,
				$this->validator,
				$this->logger
			);
		}

		return $this->getMockBuilder($grouping ? GroupHelper::class : GroupHelperDisabled::class)
			->setConstructorArgs([
				$this->l,
				$this->activityManager,
				$this->validator,
				$this->logger
			])
			->onlyMethods($methods)
			->getMock();
	}

	public function testSetL10n(): void {
		$helper = $this->getHelper();

		$l = \OC::$server->getL10NFactory()->get('activity', 'de');

		$helper->setL10n($l);
		$this->assertTrue(true);
	}

	public function dataGetEventFromArray(): array {
		return [
			[
				[
					'app' => 'app1',
					'type' => 'type1',
					'affecteduser' => 'affecteduser1',
					'user' => 'user1',
					'timestamp' => 42,
					'subject' => 'subject1',
					'subjectparams' => json_encode(['par1']),
					'message' => 'message1',
					'messageparams' => json_encode(['par2']),
					'object_type' => 'object_type1',
					'object_id' => 123,
					'file' => 'file1',
					'link' => 'link1',
				],
			],
			[
				[
					'app' => 'app2',
					'type' => 'type2',
					'affecteduser' => 'affecteduser2',
					'user' => 'user2',
					'timestamp' => 23,
					'subject' => 'subject2',
					'subjectparams' => json_encode(['par2']),
					'message' => 'message2',
					'messageparams' => json_encode(['par3']),
					'object_type' => 'object_type2',
					'object_id' => 456,
					'file' => 'file2',
					'link' => 'link2',
				],
			],
		];
	}

	/**
	 * @dataProvider dataGetEventFromArray
	 * @param array $activity
	 */
	public function testGetEventFromArray(array $activity): void {
		$event = $this->createMock(IEvent::class);
		$event->expects($this->once())
			->method('setApp')
			->with($activity['app'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setType')
			->with($activity['type'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setAffectedUser')
			->with($activity['affecteduser'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setAuthor')
			->with($activity['user'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setTimestamp')
			->with($activity['timestamp'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setSubject')
			->with($activity['subject'], json_decode($activity['subjectparams'], true))
			->willReturnSelf();
		$event->expects($this->once())
			->method('setMessage')
			->with($activity['message'], json_decode($activity['messageparams'], true))
			->willReturnSelf();
		$event->expects($this->once())
			->method('setObject')
			->with($activity['object_type'], $activity['object_id'], $activity['file'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setLink')
			->with($activity['link'])
			->willReturnSelf();

		$this->activityManager->expects($this->once())
			->method('generateEvent')
			->willReturn($event);

		$helper = $this->getHelper();
		$instance = self::invokePrivate($helper, 'arrayToEvent', [$activity]);
		$this->assertSame($event, $instance);
	}


	protected function createEvent(array $data): IEvent {
		/** @var IEvent|MockObject $event */
		$event = $this->createMock(IEvent::class);
		$event->expects($this->atLeastOnce())
			->method('getObjectId')
			->willReturn($data['id']);
		$event->expects($this->atLeastOnce())
			->method('getObjectName')
			->willReturn($data['name']);

		if (isset($data['child'])) {
			$event->expects($this->once())
				->method('getChildEvent')
				->willReturn($this->createEvent($data['child']));
		}

		return $event;
	}

	public function dataGetObjectsFromChildren(): array {
		return [
			[['id' => 0, 'name' => ''], []],
			[['id' => 12, 'name' => ''], [12 => '']],
			[['id' => 0, 'name' => 'zero'], [0 => 'zero']],
			[['id' => 12, 'name' => 'twelve'], [12 => 'twelve']],
			[['id' => 12, 'name' => 'twelve', 'child' => ['id' => 11, 'name' => 'eleven', 'child' => ['id' => 10, 'name' => 'ten']]], [10 => 'ten', 11 => 'eleven', 12 => 'twelve']],
		];
	}

	/**
	 * @dataProvider dataGetObjectsFromChildren
	 * @param array $data
	 * @param array $expected
	 */
	public function testGetObjectsFromChildren(array $data, array $expected): void {
		$event = $this->createEvent($data);
		$helper = $this->getHelper();
		$this->assertSame($expected, self::invokePrivate($helper, 'getObjectsFromChildren', [$event]));
	}
}
