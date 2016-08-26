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

use OCA\Activity\AppInfo\Application;
use OCA\Activity\Tests\TestCase;

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

	protected function setUp() {
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
			array('OCA\Activity\Data', 'OCA\Activity\Data'),
			array('OCP\IL10N', 'OCP\IL10N'),
			array('Consumer', 'OCA\Activity\Consumer'),
			array('Consumer', 'OCP\Activity\IConsumer'),
			array('DataHelper', 'OCA\Activity\DataHelper'),
			array('GroupHelper', 'OCA\Activity\GroupHelper'),
			array('Hooks', 'OCA\Activity\FilesHooks'),
			array('MailQueueHandler', 'OCA\Activity\MailQueueHandler'),
			array('Navigation', 'OCA\Activity\Navigation'),
			array('UserSettings', 'OCA\Activity\UserSettings'),
			array('SettingsController', 'OCP\AppFramework\Controller'),
			array('ActivitiesController', 'OCP\AppFramework\Controller'),
			array('FeedController', 'OCP\AppFramework\Controller'),

			array('OCA\Activity\CurrentUser', 'OCA\Activity\CurrentUser'),
			array('OCA\Activity\ViewInfoCache', 'OCA\Activity\ViewInfoCache'),
		);
	}

	/**
	 * @dataProvider queryData
	 * @param string $service
	 * @param string $expected
	 */
	public function testContainerQuery($service, $expected) {
		$this->assertTrue($this->container->query($service) instanceof $expected);
	}
}
