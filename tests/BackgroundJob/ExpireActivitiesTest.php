<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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

namespace OCA\Activity\Tests\BackgroundJob;

use OCA\Activity\BackgroundJob\ExpireActivities;
use OCA\Activity\Data;
use OCA\Activity\Tests\TestCase;
use OCP\IConfig;

/**
 * Class ExpireActivitiesTest
 *
 * @group DB
 * @package OCA\Activity\Tests\BackgroundJob
 */
class ExpireActivitiesTest extends TestCase {
	public function dataExecute() {
		return [
			[],
			[
				$this->getMockBuilder('OCA\Activity\Data')->disableOriginalConstructor()->getMock(),
				$this->getMockBuilder('OCP\IConfig')->disableOriginalConstructor()->getMock(),
			],
		];
	}

	/**
	 * @dataProvider dataExecute
	 *
	 * @param Data $data
	 * @param IConfig $config
	 */
	public function testExecute(Data $data = null, IConfig $config = null) {
		$backgroundJob = new ExpireActivities($data, $config);

		$jobList = $this->getMock('\OCP\BackgroundJob\IJobList');

		/** @var \OC\BackgroundJob\JobList $jobList */
		$backgroundJob->execute($jobList);
		$this->assertTrue(true);

		// NOTE: the result of execute() is further tested in
		// DataDeleteActivitiesTest::testExpireActivities()
	}
}
