<?php

/**
 * ownCloud
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Tests\BackgroundJob;

use OCA\Activity\BackgroundJob\ExpireActivities;
use OCA\Activity\Data;
use OCA\Activity\Tests\TestCase;
use OCP\IConfig;

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
