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

namespace OCA\Activity\Tests\AppInfo;

use OCA\Activity\Tests\TestCase;

class InstallTest extends TestCase {
	public function testJobList() {
		/** @var \OCP\BackgroundJob\IJobList|\PHPUnit_Framework_MockObject_MockObject $jobList */
		$jobList = $this->getMockBuilder('\OCP\BackgroundJob\IJobList')
			->disableOriginalConstructor()
			->getMock();

		// Test whether the background jobs got registered
		$jobList->expects($this->at(0))
			->method('add')
			->with('OCA\Activity\BackgroundJob\EmailNotification');
		$jobList->expects($this->at(1))
			->method('add')
			->with('OCA\Activity\BackgroundJob\ExpireActivities');

		$this->overwriteService('JobList', $jobList);
		require '../appinfo/install.php';
		$this->restoreService('JobList');
	}
}
