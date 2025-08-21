<?php

declare(strict_types=1);
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
use OCA\Activity\Controller\APIv1Controller;
use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\Tests\Mock\Provider;
use OCA\Activity\Tests\Mock\Setting1;
use OCA\Activity\Tests\Mock\Setting2;
use OCA\Activity\Tests\TestCase;
use OCA\Activity\UserSettings;
use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IUserSession;
use OCP\RichObjectStrings\IValidator;
use Psr\Log\LoggerInterface;

/**
 * Class APIv1Test
 *
 * @group DB
 * @package OCA\Activity\Tests\Controller
 */
class APIv1ControllerTest extends TestCase {
	protected $originalWEBROOT;

	protected function setUp(): void {
		parent::setUp();
		$this->cleanUp();

		$this->originalWEBROOT = \OC::$WEBROOT;
		\OC::$WEBROOT = '';
		\OC::$server->getUserManager()->createUser('activity-api-user1', 'activity-api-user1');
		\OC::$server->getUserManager()->createUser('activity-api-user2', 'activity-api-user2');

		$activities = [
			[
				'affectedUser' => 'activity-api-user1',
				'subject' => 'subject1',
				'subjectparams' => ['/A/B.txt'],
				'type' => 'type1',
			],
			[
				'affectedUser' => 'activity-api-user1',
				'subject' => 'subject2',
				'subjectparams' => ['/A/B.txt', 'User'],
				'type' => 'type2',
			],
		];

		$queryActivity = \OC::$server->getDatabaseConnection()->prepare('INSERT INTO `*PREFIX*activity`(`app`, `subject`, `subjectparams`, `message`, `messageparams`, `file`, `link`, `user`, `affecteduser`, `timestamp`, `priority`, `type`)' . ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
		$loop = 0;
		foreach ($activities as $activity) {
			$queryActivity->execute([
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
			]);
			$loop++;
		}
	}

	protected function tearDown(): void {
		$this->cleanUp();
		\OC::$WEBROOT = $this->originalWEBROOT;

		parent::tearDown();
	}

	protected function cleanUp(): void {
		$data = new Data(
			$this->createMock(IManager::class),
			\OC::$server->getDatabaseConnection(),
			$this->createMock(LoggerInterface::class),
			$this->createMock(IConfig::class),
		);

		$this->deleteUser($data, 'activity-api-user1');
		$this->deleteUser($data, 'activity-api-user2');

		$data->deleteActivities([
			'app' => 'app1',
		]);
	}

	protected function deleteUser(Data $data, string $uid): void {
		$data->deleteActivities([
			'affecteduser' => $uid,
		]);
		$user = \OC::$server->getUserManager()->get($uid);
		if ($user) {
			$user->delete();
		}
	}

	public function getData(): array {
		return [
			['activity-api-user2', 0, 30, []],
			['activity-api-user1', 0, 30, [
				[
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject2 @User #/A/B.txt',
				],
				[
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject1 #/A/B.txt',
				],
			]],
			['activity-api-user1', 0, 1, [
				[
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject2 @User #/A/B.txt',
				],
			]],
			['activity-api-user1', 1, 1, [
				[
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject1 #/A/B.txt',
				],
			]],
			['activity-api-user1', 5, 1, [
				[
					'link' => 'link',
					'file' => 'file',
					'date' => null,
					'id' => null,
					'message' => '',
					'subject' => 'Subject2 @User #/A/B.txt',
				],
			]],
		];
	}

	/**
	 * @dataProvider getData
	 *
	 * @param string $user
	 * @param int $start
	 * @param int $count
	 * @param array $expected
	 */
	public function testGet(string $user, int $start, int $count, array $expected): void {
		$config = $this->createMock(IConfig::class);
		$config->expects($this->any())
			->method('getUserValue')
			->willReturnArgument(3);

		$l = $this->createMock(IL10N::class);
		$l->expects($this->any())
			->method('t')
			->will($this->returnCallback(function ($text, $parameters = []) {
				return vsprintf($text, $parameters);
			}));

		$activityManager = new Manager(
			$this->createMock(IRequest::class),
			$this->createMock(IUserSession::class),
			$config,
			\OC::$server->query(IValidator::class),
			\OC::$server->query(\OCP\RichObjectStrings\IRichTextFormatter::class),
			$this->createMock(IL10N::class),
			$this->createMock(ITimeFactory::class),
		);
		$activityManager->registerProvider(Provider::class);
		$activityManager->registerSetting(Setting1::class);
		$activityManager->registerSetting(Setting2::class);

		$currentUser = $this->getMockBuilder(CurrentUser::class)
			->disableOriginalConstructor()
			->getMock();
		$currentUser->expects($this->any())
			->method('getUID')
			->willReturn($user);

		$data = new Data($activityManager,
			\OC::$server->getDatabaseConnection(),
			$this->createMock(LoggerInterface::class),
			$this->createMock(IConfig::class),
		);

		$controller = new APIv1Controller(
			'activity',
			$this->createMock(IRequest::class),
			$data,
			new GroupHelper($l, $activityManager, $this->createMock(IValidator::class), $this->createMock(LoggerInterface::class)),
			new UserSettings($activityManager, $config),
			$currentUser,
			\OC::$server->getDatabaseConnection(),
		);
		$response = $controller->get($start, $count);

		$data = $response->getData();
		$this->assertCount(count($expected), $data, 'Number of expected activities does not match');

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
