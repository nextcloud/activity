<?php
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
use OCA\Activity\Parameter\Collection;

class GroupHelperTest extends TestCase {
	/** @var \OCP\Activity\IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $activityManager;
	/** @var \OCA\Activity\DataHelper|\PHPUnit_Framework_MockObject_MockObject */
	protected $dataHelper;

	protected function setUp() {
		parent::setUp();

		$this->activityManager = $this->getMockBuilder('OCP\Activity\IManager')
			->disableOriginalConstructor()
			->getMock();

		$this->dataHelper = $this->getMockBuilder('OCA\Activity\DataHelper')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param array $methods
	 * @param bool $grouping
	 * @return GroupHelper|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getHelper(array $methods = [], $grouping = false) {
		if (empty($methods)) {
			if ($grouping) {
				return new GroupHelper(
					$this->activityManager,
					$this->dataHelper
				);
			} else {
				return new GroupHelperDisabled(
					$this->activityManager,
					$this->dataHelper
				);
			}
		} else {
			return $this->getMockBuilder($grouping ? 'OCA\Activity\GroupHelper' : 'OCA\Activity\GroupHelperDisabled')
				->setConstructorArgs([
					$this->activityManager,
					$this->dataHelper
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function testSetUser() {
		$helper = $this->getHelper();

		$this->dataHelper->expects($this->once())
			->method('setUser')
			->with('foobar');

		$helper->setUser('foobar');
	}

	public function testSetL10n() {
		$helper = $this->getHelper();

		$l = \OC::$server->getL10NFactory()->get('activity', 'de');
		$this->dataHelper->expects($this->once())
			->method('setL10n')
			->with($l);

		$helper->setL10n($l);
	}

	protected function getCollection($addParameterCalls) {
		$collection = $this->getMockBuilder('OCA\Activity\Parameter\Collection')
			->disableOriginalConstructor()
			->getMock();
		$collection->expects($this->exactly($addParameterCalls))
			->method('addParameter');
		return $collection;
	}

	protected function getParameter() {
		$parameter = $this->getMockBuilder('OCA\Activity\Parameter\IParameter')
			->disableOriginalConstructor()
			->getMock();
		return $parameter;
	}

	public function dataAddActivity() {
		$param1 = $this->getParameter();
		$param2 = $this->getParameter();
		$param3 = $this->getParameter();
		$coll1 = $this->getCollection(1);
		$coll2 = $this->getCollection(2);

		return [
			[
				// No grouping because grouping key is false
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'file' => '',
					'timestamp' => 1444895509,
				],
				[],
				[],
				false,
				false,
				[
					[
						'activity_id' => 2,
						'subjectparams' => ['param'],
						'messageparams' => ['messageparams'],
						'object_id' => 42,
						'object_name' => '',
						'timestamp' => 1444895509,
						'subjectparams_array' => [],
						'messageparams_array' => [],
					],
				],
				[],
			],
			[
				// Start grouping
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'file' => '',
					'timestamp' => 1444895509,
				],
				[],
				[],
				'not|false',
				false,
				[],
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'object_name' => '',
					'timestamp' => 1444895509,
					'subjectparams_array' => [],
					'messageparams_array' => [],
				],
			],
			[
				// No grouping because grouping key is false
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'file' => '42.txt',
					'timestamp' => 1444895509,
				],
				[],
				[
					'activity_id' => 3,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 21,
					'object_name' => '21.txt',
					'timestamp' => 1444895510,
					'subjectparams_array' => [],
					'messageparams_array' => [],
				],
				false,
				false,
				[
					[
						'activity_id' => 3,
						'subjectparams' => ['param'],
						'messageparams' => ['messageparams'],
						'object_id' => 21,
						'object_name' => '21.txt',
						'timestamp' => 1444895510,
						'subjectparams_array' => [],
						'messageparams_array' => [],
					],
					[
						'activity_id' => 2,
						'subjectparams' => ['param'],
						'messageparams' => ['messageparams'],
						'object_id' => 42,
						'object_name' => '42.txt',
						'timestamp' => 1444895509,
						'subjectparams_array' => [],
						'messageparams_array' => [],
					],
				],
				[],
			],
			[
				// No grouping because of different key
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'file' => '42.txt',
					'timestamp' => 1444895509,
				],
				[],
				[
					'activity_id' => 3,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 21,
					'object_name' => '21.txt',
					'timestamp' => 1444895510,
					'subjectparams_array' => [],
					'messageparams_array' => [],
				],
				'not|false',
				false,
				[
					[
						'activity_id' => 3,
						'subjectparams' => ['param'],
						'messageparams' => ['messageparams'],
						'object_id' => 21,
						'object_name' => '21.txt',
						'timestamp' => 1444895510,
						'subjectparams_array' => [],
						'messageparams_array' => [],
					],
				],
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'object_name' => '42.txt',
					'timestamp' => 1444895509,
					'subjectparams_array' => [],
					'messageparams_array' => [],
				],
			],
			[
				// No grouping because of time
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'file' => '42.txt',
					'timestamp' => 1440895509,
				],
				[],
				[
					'activity_id' => 3,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 21,
					'object_name' => '21.txt',
					'timestamp' => 1444895509,
					'subjectparams_array' => [],
					'messageparams_array' => [],
				],
				'not|false',
				'not|false',
				[
					[
						'activity_id' => 3,
						'subjectparams' => ['param'],
						'messageparams' => ['messageparams'],
						'object_id' => 21,
						'object_name' => '21.txt',
						'timestamp' => 1444895509,
						'subjectparams_array' => [],
						'messageparams_array' => [],
					],
				],
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'object_name' => '42.txt',
					'timestamp' => 1440895509,
					'subjectparams_array' => [],
					'messageparams_array' => [],
				],
			],
			[
				// No grouping because of group size
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'file' => '42.txt',
					'timestamp' => 1444895509,
				],
				[],
				[
					'activity_id' => 3,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 21,
					'object_name' => '21.txt',
					'timestamp' => 1444895510,
					'subjectparams_array' => [],
					'messageparams_array' => [],
					'activity_ids' => [1, 2, 3, 4, 5, 6],
				],
				'not|false',
				'not|false',
				[
					[
						'activity_id' => 3,
						'subjectparams' => ['param'],
						'messageparams' => ['messageparams'],
						'object_id' => 21,
						'object_name' => '21.txt',
						'timestamp' => 1444895510,
						'subjectparams_array' => [],
						'messageparams_array' => [],
						'activity_ids' => [1, 2, 3, 4, 5, 6],
					],
				],
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'object_name' => '42.txt',
					'timestamp' => 1444895509,
					'subjectparams_array' => [],
					'messageparams_array' => [],
				],
			],
			[
				// Group into existing collection
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'timestamp' => 1444895509,
					'object_id' => 7,
					'file' => 'file2',
				],
				[$param1],
				[
					'activity_id' => 5,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'timestamp' => 1444895510,
					'subjectparams_array' => [
						$coll1
					],
					'messageparams_array' => [],
					'activity_ids' => [5, 4, 3],
					'object_id' => 42,
					'files' => [
						42 => 'file5',
						23 => 'file4',
						12 => 'file3',
					],
				],
				'same',
				'same',
				[],
				[
					'activity_id' => 5,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'timestamp' => 1444895510,
					'subjectparams_array' => [$coll1],
					'messageparams_array' => [],
					'activity_ids' => [5, 4, 3, 2],
					'object_id' => 42,
					'files' => [
						42 => 'file5',
						23 => 'file4',
						12 => 'file3',
						7 => 'file2',
					],
				],
			],
			[
				// Creating a new collection
				[
					'activity_id' => 2,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'timestamp' => 1444895509,
					'object_id' => 7,
					'file' => 'file2',
				],
				[$param2],
				[
					'activity_id' => 5,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'object_id' => 42,
					'object_name' => 'file5',
					'timestamp' => 1444895510,
					'subjectparams_array' => [
						$param3
					],
					'messageparams_array' => [],
				],
				'same',
				'same',
				[],
				[
					'activity_id' => 5,
					'subjectparams' => ['param'],
					'messageparams' => ['messageparams'],
					'timestamp' => 1444895510,
					'subjectparams_array' => [$coll1],
					'messageparams_array' => [],
					'object_id' => 42,
					'object_name' => 'file5',
					'activity_ids' => [5, 2],
					'files' => [
						42 => 'file5',
						7 => 'file2',
					],
				],
				$coll2,
			],
		];
	}

	/**
	 * @dataProvider dataAddActivity
	 *
	 * @param array $activity
	 * @param array $subjectParams
	 * @param array $openGroup
	 * @param bool|string $getGroupKey
	 * @param bool|string $openGroupKey
	 * @param array $expectedActivities
	 * @param array $openGroupAfter
	 * @param Collection $createCollection
	 */
	public function testAddActivity(array $activity, array $subjectParams, array $openGroup, $getGroupKey, $openGroupKey, $expectedActivities, array $openGroupAfter, Collection $createCollection = null) {
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();

		$getGroupParameter = empty($subjectParams) ? null : 0;

		$this->dataHelper->expects($this->at(0))
			->method('getParameters')
			->with($event, 'subject', $this->anything())
			->willReturn($subjectParams);
		$this->dataHelper->expects($this->at(1))
			->method('getParameters')
			->with($event, 'message', $this->anything())
			->willReturn([]);
		if ($createCollection === null) {
			$this->dataHelper->expects($this->never())
				->method('createCollection');
		} else {
			$this->dataHelper->expects($this->once())
				->method('createCollection')
				->willReturn($createCollection);
		}

		$helper = $this->getHelper([
			'getEventFromArray',
			'getGroupKey',
			'getGroupParameter',
		]);
		$helper->expects($this->once())
			->method('getEventFromArray')
			->willReturnCallback(function($row) use ($event) {
				$this->assertSame([], $row['subjectparams']);
				$this->assertSame([], $row['messageparams']);
				return $event;
			});
		$helper->expects($getGroupParameter === null ? $this->never() : $this->once())
			->method('getGroupParameter')
			->willReturn($getGroupParameter);
		$helper->expects($this->once())
			->method('getGroupKey')
			->willReturn($getGroupKey);
		$this->invokePrivate($helper, 'openGroup', [$openGroup]);
		$this->invokePrivate($helper, 'groupKey', [$openGroupKey]);
		if (isset($openGroup['timestamp'])) {
			$this->invokePrivate($helper, 'groupTime', [$openGroup['timestamp']]);
		}

		$helper->addActivity($activity);

		$this->assertEquals($expectedActivities, $this->invokePrivate($helper, 'activities'));
		$this->assertEquals($openGroupAfter, $this->invokePrivate($helper, 'openGroup'));
	}

	public function dataCloseOpenGroup() {
		return [
			[[]],
			[['opengroup']]
		];
	}

	/**
	 * @dataProvider dataCloseOpenGroup
	 * @param $openGroup
	 */
	public function testCloseOpenGroup($openGroup) {
		$helper = $this->getHelper();
		$this->invokePrivate($helper, 'openGroup', $openGroup);

		$this->invokePrivate($helper, 'closeOpenGroup');

		$this->assertSame($openGroup, $this->invokePrivate($helper, 'activities'));
		$this->assertSame([], $this->invokePrivate($helper, 'openGroup'));
		$this->assertSame(0, $this->invokePrivate($helper, 'groupTime'));
		$this->assertSame('', $this->invokePrivate($helper, 'groupKey'));
	}

	public function dataGetGroupKey() {
		return [
			[
				[
					'app' => 'app1',
					'user' => 'user1',
					'subject' => 'subject1',
					'object_type' => 'object_type1',
					'subjectparams' => ['foo', 'bar'],
				], false, false
			],
			[
				[
					'app' => 'app1',
					'user' => '',
					'subject' => 'subject1',
					'object_type' => 'object_type1',
					'subjectparams' => ['foo', 'bar'],
				], 0, false
			],
			[
				[
					'app' => 'app1',
					'user' => 'user1',
					'subject' => 'subject1',
					'object_type' => 'object_type1',
					'subjectparams' => json_encode(['foo', 'bar']),
				], 1, 'app1|user1|subject1|object_type1|' . md5(json_encode(['foo']))
			],
		];
	}

	/**
	 * @dataProvider dataGetGroupKey
	 *
	 * @param array $activity
	 * @param bool|int $return
	 * @param bool|int $expected
	 */
	public function testGetGroupKey(array $activity, $return, $expected) {
		$helper = $this->getHelper(['getGroupParameter']);
		$helper->expects($this->once())
			->method('getGroupParameter')
			->willReturn($return);
		$this->assertSame($expected, $this->invokePrivate($helper, 'getGroupKey', [$activity]));
	}

	public function dataGetGroupParameter() {
		return [
			[[1], false, false, false],
			[[2], false, true, false],
			[[3], true, false, false],
			[[4], true, true, true],
		];
	}

	/**
	 * @dataProvider dataGetGroupParameter
	 *
	 * @param array $activity
	 * @param bool $allowGrouping
	 * @param bool|int $return
	 * @param bool|int $expected
	 */
	public function testGetGroupParameter(array $activity, $allowGrouping, $return, $expected) {
		$this->activityManager->expects($allowGrouping ? $this->once() : $this->never())
			->method('getGroupParameter')
			->with($activity)
			->willReturn($return);

		$helper = $this->getHelper([], $allowGrouping);
		$this->assertSame($expected, $this->invokePrivate($helper, 'getGroupParameter', [$activity]));
	}

	public function dataGetActivities() {
		return [
			[[], []],
			[
				[
					['object_type' => 'otype', 'object_id' => 42, 'type' => 'atype', 'subjectparams' => [], 'messageparams' => []],
				],
				[
					['object_type' => 'otype', 'object_id' => 42, 'type' => 'atype', 'subjectparams' => [], 'messageparams' => [], 'typeicon' => 'atype-icon'],
				],
			],
			[
				[
					['object_type' => 'otype', 'object_id' => 42, 'type' => 'atype', 'subjectparams' => [], 'messageparams' => []],
					['object_type' => 'object_type', 'object_id' => 23, 'type' => 'activity_type', 'subjectparams' => [], 'messageparams' => []],
				],
				[
					['object_type' => 'otype', 'object_id' => 42, 'type' => 'atype', 'subjectparams' => [], 'messageparams' => [], 'typeicon' => 'atype-icon'],
					['object_type' => 'object_type', 'object_id' => 23, 'type' => 'activity_type', 'subjectparams' => [], 'messageparams' => [], 'typeicon' => 'activity_type-icon'],
				],
			],
		];
	}

	/**
	 * @dataProvider dataGetActivities
	 *
	 * @param array $activities
	 * @param array $expected
	 */
	public function testGetActivities(array $activities, array $expected) {
		$numActivities = sizeof($activities);

		$helper = $this->getHelper(['closeOpenGroup']);
		$helper->expects($this->once())
			->method('closeOpenGroup');

		$this->invokePrivate($helper, 'activities', [$activities]);

		$this->dataHelper->expects($this->exactly($numActivities * 2))
			->method('formatStrings')
			->willReturnArgument(0);
		$this->activityManager->expects($this->exactly($numActivities))
			->method('getTypeIcon')
			->willReturnCallback(function ($type) {
				return $type . '-icon';
			});
		$this->activityManager->expects($this->exactly($numActivities + 1))
			->method('setFormattingObject');

		$this->assertSame($expected, $helper->getActivities());
	}

	public function dataGetEventFromArray() {
		return [
			[
				[
					'app' => 'app1',
					'type' => 'type1',
					'affecteduser' => 'affecteduser1',
					'user' => 'user1',
					'timestamp' => 42,
					'subject' => 'subject1',
					'subjectparams' => ['par1'],
					'message' => 'message1',
					'messageparams' => ['par2'],
					'object_type' => 'object_type1',
					'object_id' => 123,
					'object_name' => 'file1',
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
					'subjectparams' => ['par2'],
					'message' => 'message2',
					'messageparams' => ['par3'],
					'object_type' => 'object_type2',
					'object_id' => 456,
					'object_name' => 'file2',
					'link' => 'link2',
				],
			],
		];
	}

	/**
	 * @dataProvider dataGetEventFromArray
	 * @param array $activity
	 */
	public function testGetEventFromArray(array $activity) {
		$event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()
			->getMock();
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
			->with($activity['subject'], $activity['subjectparams'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setMessage')
			->with($activity['message'], $activity['messageparams'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setObject')
			->with($activity['object_type'], $activity['object_id'], $activity['object_name'])
			->willReturnSelf();
		$event->expects($this->once())
			->method('setLink')
			->with($activity['link'])
			->willReturnSelf();

		$this->activityManager->expects($this->once())
			->method('generateEvent')
			->willReturn($event);

		$helper = $this->getHelper();
		$instance = $helper->getEventFromArray($activity);
		$this->assertSame($event, $instance);
	}
}
