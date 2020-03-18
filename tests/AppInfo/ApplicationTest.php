<?php
declare(strict_types=1);
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
use OCP\AppFramework\IAppContainer;
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
	/** @var Application */
	protected $app;

	/** @var IAppContainer */
	protected $container;

	protected function setUp(): void {
		parent::setUp();
		$this->app = new Application();
		$this->container = $this->app->getContainer();
	}

	public function testContainerAppName(): void {
		$this->app = new Application();
		$this->assertEquals('activity', $this->container->getAppName());
	}

	public function queryData(): array {
		return [
			[IL10N::class],
			[View::class],

			// lib/
			[Capabilities::class],
			[Capabilities::class, ICapability::class],
			[Consumer::class],
			[Consumer::class, IConsumer::class],
			[CurrentUser::class],
			[Data::class],
			[FilesHooks::class],
			[GroupHelper::class],
			[GroupHelperDisabled::class],
			[GroupHelperDisabled::class, GroupHelper::class],
			[Hooks::class],
			[MailQueueHandler::class],
			[Navigation::class],
			[UserSettings::class],
			[ViewInfoCache::class],

			// BackgroundJob
			[EmailNotification::class],
			[EmailNotification::class, TimedJob::class],
			[ExpireActivities::class,],
			[ExpireActivities::class, TimedJob::class],

			// Controller
			['ActivitiesController', Activities::class],
			['ActivitiesController', Controller::class],
			['APIv1Controller', APIv1::class],
			['APIv1Controller', Controller::class],
			['APIv1Controller', OCSController::class],
			['APIv2Controller', APIv2::class],
			['APIv2Controller', Controller::class],
			['APIv2Controller', OCSController::class],
			['FeedController', Feed::class],
			['FeedController', Controller::class],
			['SettingsController', Settings::class],
			['SettingsController', Controller::class],
		];
	}

	/**
	 * @dataProvider queryData
	 * @param string $service
	 * @param string $expected
	 */
	public function testContainerQuery(string $service, ?string $expected = null): void {
		if ($expected === null) {
			$expected = $service;
		}
		$this->assertInstanceOf($expected, $this->container->query($service));
	}
}
