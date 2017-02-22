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

namespace OCA\Activity\Tests\AppInfo;

use OCA\Activity\Tests\TestCase;

/**
 * Class AppTest
 *
 * @group DB
 * @package OCA\Activity\Tests\AppInfo
 */
class AppTest extends TestCase {
	public function testNavigationEntry() {
		$navigationManager = \OC::$server->getNavigationManager();
		$navigationManager->clear();
		$countBefore = count($navigationManager->getAll());

		require '../appinfo/app.php';

		// Test whether the navigation entry got added
		$this->assertCount($countBefore + 1, $navigationManager->getAll());
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
