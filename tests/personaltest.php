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

namespace OCA\Activity\Tests;

class PersonalTest extends TestCase {
	public function testInclude() {
		$settingsPage = include '../personal.php';
		$this->assertNotEmpty(include '../personal.php', 'Asserting that the personal.php does produce output.');
	}
}
