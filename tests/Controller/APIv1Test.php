<?php
/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Tests\Controller;

use OC\Activity\Manager;
use OCA\Activity\Controller\APIv1;
use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\DataHelper;
use OCA\Activity\GroupHelper;
use OCA\Activity\Parameter\Factory;
use OCA\Activity\PlainTextParser;
use OCA\Activity\Tests\Mock\Extension;
use OCA\Activity\Tests\TestCase;
use OCA\Activity\UserSettings;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\L10N\IFactory;

/**
 * Class APIv1Test
 *
 * @group DB
 * @package OCA\Activity\Tests\Controller
 */
class APIv1Test extends TestCase {
	protected $originalWEBROOT;

	protected function setUp() {
		parent::setUp();

		$this->originalWEBROOT = \OC::$WEBROOT;
		\OC::$WEBROOT = '';
		\OC::$server->getUserManager()->createUser('activity-api-user1', 'activity-api-user1');
		\OC::$server->getUserManager()->createUser('activity-api-user2', 'activity-api-user2');

		$activities = array(
			array(
				'affectedUser' => 'activity-api-user1',
				'subject' => 'subject1',
				'subjectparams' => array('/A/B.txt'),
				'type' => 'type1',
			),
			array(
				'affectedUser' => 'activity-api-user1',
				'subject' => 'subject2',
				'subjectparams' => array('/A/B.txt', 'User'),
				'type' => 'type2',
			),
		);

		$queryActivity = \OC::$server->getDatabaseConnection()->prepare('INSERT INTO `*PREFIX*activity`(`app`, `subject`, `subjectparams`, `message`, `messageparams`, `file`, `link`, `user`, `affecteduser`, `timestamp`, `priority`, `type`)' . ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
		$loop = 0;
		foreach ($activities as $activity) {
			$queryActivity->execute(array(
				'app1',
				$activity['subject'],
				json_encode($activity['subjectparams']),
				'',
				json_encode([]),
				'file',
				'link',
				'user',
				$activity['affectedUser'],
				time() + $loop,
				IExtension::PRIORITY_MEDIUM,
				$activity['type'],
			));
			$loop++;
		}
	}

	protected function tearDown() {
		$data = new Data(
			$this->getMockBuilder(IManager::class)->getMock(),
			\OC::$server->getDatabaseConnection(),
			$this->getMockBuilder(IUserSession::class)->getMock()
		);

		$this->deleteUser($data, 'activity-api-user1');
		$this->deleteUser($data, 'activity-api-user2');

		$data->deleteActivities(array(
			'app' => 'app1',
		));
		\OC::$WEBROOT = $this->originalWEBROOT;

		parent::tearDown();
	}

	protected function deleteUser(Data $data, $uid) {
		$data->deleteActivities(array(
			'affecteduser' => $uid,
		));
		$user = \OC::$server->getUserManager()->get($uid);
		if ($user) {
			$user->delete();
		}
	}

	public function getData() {
		return array(
			array('activity-api-user2', 0, 30, array()),
			array('activity-api-user1', 0, 30, array(
				array(
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject2 @User #A/B.txt',
				),
				array(
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject1 #A/B.txt',
				),
			)),
			array('activity-api-user1', 0, 1, array(
				array(
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject2 @User #A/B.txt',
				),
			)),
			array('activity-api-user1', 1, 1, array(
				array(
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject1 #A/B.txt',
				),
			)),
			array('activity-api-user1', 5, 1, array(
				array(
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject2 @User #A/B.txt',
				),
			)),
		);
	}

	/**
	 * @dataProvider getData
	 *
	 * @param string $user
	 * @param int $start
	 * @param int $count
	 * @param array $expected
	 */
	public function testGet($user, $start, $count, $expected) {
		$config = $this->getMockBuilder(IConfig::class)->getMock();
		$config->expects($this->any())
			->method('getUserValue')
			->willReturnArgument(3);

		$l = $this->getMockBuilder(IL10N::class)->getMock();
		$l->expects($this->any())
			->method('t')
			->will($this->returnCallback(function($text, $parameters = array()) {
				return vsprintf($text, $parameters);
			}));

		$activityManager = new Manager(
			$this->getMockBuilder(IRequest::class)->getMock(),
			$this->getMockBuilder(IUserSession::class)->getMock(),
			$config
		);
		$activityManager->registerExtension(function() use ($l) {
			return new Extension($l, $this->getMockBuilder(IURLGenerator::class)->getMock());
		});

		$currentUser = $this->getMockBuilder(CurrentUser::class)
			->disableOriginalConstructor()
			->getMock();
		$currentUser->expects($this->any())
			->method('getUID')
			->willReturn($user);

		$data = new Data($activityManager, \OC::$server->getDatabaseConnection());

		/** @var APIv1 $controller */
		$controller = new APIv1(
			'activity',
			$this->getMockBuilder(IRequest::class)->getMock(),
			$data,
			new GroupHelper($activityManager, new DataHelper(
				$activityManager,
				\OC::$server->query(Factory::class),
				$this->getMockBuilder(IFactory::class)->getMock(),
				$l
			)),
			new UserSettings($activityManager, $config, $data),
			new PlainTextParser($l),
			$currentUser
		);
		$response = $controller->get($start, $count);

		$data = $response->getData();
		$this->assertEquals(sizeof($expected), sizeof($data), 'Number of expected activities does not match');

		while (!empty($expected)) {
			$assertExpected = array_shift($expected);
			$assertData = array_shift($data);
			foreach ($assertExpected as $key => $value) {
				$this->assertArrayHasKey($key, $assertData);
				if ($value !== null) {
					$this->assertEquals($value, $assertData[$key]);
				}
			}
		}
	}
}
