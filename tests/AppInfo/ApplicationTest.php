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

use OC\BackgroundJob\TimedJob;
use OC\Files\View;
use OCA\Activity\AppInfo\Application;
use OCA\Activity\BackgroundJob\EmailNotification;
use OCA\Activity\BackgroundJob\ExpireActivities;
use OCA\Activity\Capabilities;
use OCA\Activity\Consumer;
use OCA\Activity\Controller\Activities;
use OCA\Activity\Controller\APIv1;
use OCA\Activity\Controller\APIv2;
use OCA\Activity\Controller\Feed;
use OCA\Activity\Controller\Settings;
use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\FilesHooks;
use OCA\Activity\GroupHelper;
use OCA\Activity\GroupHelperDisabled;
use OCA\Activity\Hooks;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\Navigation;
use OCA\Activity\Tests\TestCase;
use OCA\Activity\UserSettings;
use OCA\Activity\ViewInfoCache;
use OCP\Activity\IConsumer;
use OCP\AppFramework\Controller;
use OCP\AppFramework\OCSController;
use OCP\Capabilities\ICapability;
use OCP\IL10N;

/**
 * Class ApplicationTest
 *
 * @group DB
 * @package OCA\Activity\Tests\AppInfo
 */
class ApplicationTest extends TestCase {
	/** @var \OCA\Activity\AppInfo\Application */
	protected $app;

	/** @var \OCP\AppFramework\IAppContainer */
	protected $container;

	protected function setUp(): void {
		parent::setUp();
		$this->app = new Application();
		$this->container = $this->app->getContainer();
	}

	public function testContainerAppName() {
		$this->app = new Application();
		$this->assertEquals('activity', $this->container->getAppName());
	}

	public function queryData() {
		return array(
			array(IL10N::class),
			array(View::class),

			// lib/
			array(Capabilities::class),
			array(Capabilities::class, ICapability::class),
			array(Consumer::class),
			array(Consumer::class, IConsumer::class),
			array(CurrentUser::class),
			array(Data::class),
			array(FilesHooks::class),
			array(GroupHelper::class),
			array(GroupHelperDisabled::class),
			array(GroupHelperDisabled::class, GroupHelper::class),
			array(Hooks::class),
			array(MailQueueHandler::class),
			array(Navigation::class),
			array(UserSettings::class),
			array(ViewInfoCache::class),

			// BackgroundJob
			array(EmailNotification::class),
			array(EmailNotification::class, TimedJob::class),
			array(ExpireActivities::class,),
			array(ExpireActivities::class, TimedJob::class),

			// Controller
			array('ActivitiesController', Activities::class),
			array('ActivitiesController', Controller::class),
			array('APIv1Controller', APIv1::class),
			array('APIv1Controller', Controller::class),
			array('APIv1Controller', OCSController::class),
			array('APIv2Controller', APIv2::class),
			array('APIv2Controller', Controller::class),
			array('APIv2Controller', OCSController::class),
			array('FeedController', Feed::class),
			array('FeedController', Controller::class),
			array('SettingsController', Settings::class),
			array('SettingsController', Controller::class),
		);
	}

	/**
	 * @dataProvider queryData
	 * @param string $service
	 * @param string $expected
	 */
	public function testContainerQuery($service, $expected = null) {
		if ($expected === null) {
			$expected = $service;
		}
		$this->assertInstanceOf($expected, $this->container->query($service));
	}
}
