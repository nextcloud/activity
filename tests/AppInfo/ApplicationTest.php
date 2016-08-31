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
			array('OCP\IL10N', 'OCP\IL10N'),
			array('OC\Files\View', 'OC\Files\View'),

			array('OCA\Activity\Data', 'OCA\Activity\Data'),
			array('OCA\Activity\Consumer', 'OCA\Activity\Consumer'),
			array('OCA\Activity\Consumer', 'OCP\Activity\IConsumer'),
			array('OCA\Activity\Controller\OCSEndPoint', 'OCA\Activity\Controller\OCSEndPoint'),
			array('OCA\Activity\CurrentUser', 'OCA\Activity\CurrentUser'),
			array('OCA\Activity\DataHelper', 'OCA\Activity\DataHelper'),
			array('OCA\Activity\FilesHooks', 'OCA\Activity\FilesHooks'),
			array('OCA\Activity\GroupHelper', 'OCA\Activity\GroupHelper'),
			array('OCA\Activity\GroupHelperDisabled', 'OCA\Activity\GroupHelper'),
			array('OCA\Activity\GroupHelperDisabled', 'OCA\Activity\GroupHelperDisabled'),
			array('OCA\Activity\Parameter\Factory', 'OCA\Activity\Parameter\Factory'),
			array('OCA\Activity\MailQueueHandler', 'OCA\Activity\MailQueueHandler'),
			array('OCA\Activity\Navigation', 'OCA\Activity\Navigation'),
			array('OCA\Activity\UserSettings', 'OCA\Activity\UserSettings'),
			array('OCA\Activity\ViewInfoCache', 'OCA\Activity\ViewInfoCache'),

			array('ActivitiesController', 'OCP\AppFramework\Controller'),
			array('ActivitiesController', 'OCA\Activity\Controller\Activities'),
			array('APIv1Controller', 'OCP\AppFramework\Controller'),
			array('APIv1Controller', 'OCP\AppFramework\OCSController'),
			array('APIv1Controller', 'OCA\Activity\Controller\APIv1'),
			array('EndPointController', 'OCP\AppFramework\Controller'),
			array('EndPointController', 'OCA\Activity\Controller\EndPoint'),
			array('FeedController', 'OCP\AppFramework\Controller'),
			array('FeedController', 'OCA\Activity\Controller\Feed'),
			array('SettingsController', 'OCP\AppFramework\Controller'),
			array('SettingsController', 'OCA\Activity\Controller\Settings'),
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
