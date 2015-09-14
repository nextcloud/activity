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

class AppTest extends TestCase {
	public function testNavigationEntry() {
		$navigationManager = \OC::$server->getNavigationManager();
		$navigationManager->clear();
		$this->assertEmpty($navigationManager->getAll());

		require '../appinfo/app.php';

		// Test whether the navigation entry got added
		$this->assertCount(1, $navigationManager->getAll());
	}

// FIXME: Uncomment once the OC_App stuff is not static anymore
//	public function testPersonalPanel() {
//		require '../appinfo/app.php';
//
//		// Test whether the personal panel got registered
//		$forms = \OC_App::getForms('personal');
//		$this->assertGreaterThanOrEqual(1, sizeof($forms), 'Expected to find the activity personal panel');
//
//		$foundActivityPanel = false;
//		foreach ($forms as $form) {
//			if (strpos($form, 'id="activity_notifications"') !== false) {
//				$foundActivityPanel = true;
//				break;
//			}
//		}
//		$this->assertTrue($foundActivityPanel, 'Expected to find the activity personal panel');
//	}
}
