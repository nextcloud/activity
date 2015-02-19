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
use OCA\Activity\Tests\TestCase;

class ExpireActivitiesTest extends TestCase {
	public function testExecute() {
		$backgroundJob = new ExpireActivities();

		$jobList = $this->getMock('\OCP\BackgroundJob\IJobList');

		/** @var \OC\BackgroundJob\JobList $jobList */
		$backgroundJob->execute($jobList);
		$this->assertTrue(true);

		// NOTE: the result of execute() is further tested in
		// DataDeleteActivitiesTest::testExpireActivities()
	}
}
