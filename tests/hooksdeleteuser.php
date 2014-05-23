<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
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

namespace OCA\Activity\Tests;

use OCA\Activity\Data;
use OCA\Activity\Hooks;

class HooksDeleteUser extends \PHPUnit_Framework_TestCase {
	public function testHooksDeleteUser() {
		$query = \OCP\DB::prepare('INSERT INTO `*PREFIX*activity`(`app`, `subject`, `subjectparams`, `message`, `messageparams`, `file`, `link`, `user`, `affecteduser`, `timestamp`, `priority`, `type`)' . ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
		$activities = array(
			array('affectedUser' => 'delete', 'subject' => 'subject'),
			array('affectedUser' => 'delete', 'subject' => 'subject2'),
			array('affectedUser' => 'otherUser', 'subject' => 'subject'),
			array('affectedUser' => 'otherUser', 'subject' => 'subject2'),
		);

		foreach ($activities as $activity) {
			$query->execute(array(
				'app',
				$activity['subject'],
				serialize(array()),
				'',
				serialize(array()),
				'file',
				'link',
				'user',
				$activity['affectedUser'],
				time(),
				Data::PRIORITY_MEDIUM,
				'test',
			));
		}

		$this->assertUserActivities(array('delete', 'otherUser'));
		Hooks::deleteUser(array('uid' => 'delete'));
		$this->assertUserActivities(array('otherUser'));

		$query = \OCP\DB::prepare('DELETE FROM `*PREFIX*activity`');
		$query->execute();
	}

	protected function assertUserActivities($expected) {
		$query = \OCP\DB::prepare('SELECT `affecteduser` FROM `*PREFIX*activity`');
		$result = $query->execute();

		$users = array();
		while ($row = $result->fetchRow()) {
			$users[] = $row['affecteduser'];
		}
		$users = array_unique($users);
		sort($users);
		sort($expected);

		$this->assertEquals($expected, $users);
	}
}
