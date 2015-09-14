<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
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
