<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Thomas Citharel <nextcloud@tcit.fr>
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

use OC\Files\View;
use OCA\Activity\AppInfo\Application;
use OCA\Activity\BackgroundJob\EmailNotification;
use OCA\Activity\BackgroundJob\ExpireActivities;
use OCA\Activity\Capabilities;
use OCA\Activity\Consumer;
use OCA\Activity\Controller\ActivitiesController;
use OCA\Activity\Controller\APIv1Controller;
use OCA\Activity\Controller\APIv2Controller;
use OCA\Activity\Controller\FeedController;
use OCA\Activity\Controller\SettingsController;
use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\FilesHooks;
use OCA\Activity\GroupHelper;
use OCA\Activity\GroupHelperDisabled;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\Tests\TestCase;
use OCA\Activity\UserSettings;
use OCA\Activity\ViewInfoCache;
use OCP\Activity\IConsumer;
use OCP\AppFramework\Controller;
use OCP\AppFramework\IAppContainer;
use OCP\AppFramework\OCSController;
use OCP\BackgroundJob\TimedJob;
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
			[MailQueueHandler::class],
			[UserSettings::class],
			[ViewInfoCache::class],

			// BackgroundJob
			[EmailNotification::class],
			[EmailNotification::class, TimedJob::class],
			[ExpireActivities::class,],
			[ExpireActivities::class, TimedJob::class],

			// Controller
			[ActivitiesController::class],
			[ActivitiesController::class, Controller::class],
			[APIv1Controller::class],
			[APIv1Controller::class, Controller::class],
			[APIv1Controller::class, OCSController::class],
			[APIv2Controller::class],
			[APIv2Controller::class, Controller::class],
			[APIv2Controller::class, OCSController::class],
			[FeedController::class],
			[FeedController::class, Controller::class],
			[SettingsController::class, SettingsController::class],
			[SettingsController::class, Controller::class],
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
		$this->assertInstanceOf($expected, $this->container->get($service));
	}
}
