<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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

namespace OCA\Activity\AppInfo;

use OC\Files\View;
use OCA\Activity\Consumer;
use OCA\Activity\Controller\Activities;
use OCA\Activity\Controller\EndPoint;
use OCA\Activity\Controller\Feed;
use OCA\Activity\Controller\OCSEndPoint;
use OCA\Activity\Controller\Settings;
use OCA\Activity\Data;
use OCA\Activity\DataHelper;
use OCA\Activity\GroupHelper;
use OCA\Activity\FilesHooks;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\Navigation;
use OCA\Activity\Parameter\Factory;
use OCA\Activity\UserSettings;
use OCA\Activity\ViewInfoCache;
use OCP\AppFramework\App;
use OCP\IContainer;

class Application extends App {
	public function __construct (array $urlParams = array()) {
		parent::__construct('activity', $urlParams);
		$container = $this->getContainer();

		/**
		 * Activity Services
		 */
		$container->registerService('ActivityData', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');
			return new Data(
				$server->getActivityManager(),
				$server->getDatabaseConnection(),
				$server->getUserSession()
			);
		});

		$container->registerService('ActivityL10N', function(IContainer $c) {
			return $c->query('ServerContainer')->getL10N('activity');
		});


		$container->registerService('Consumer', function(IContainer $c) {
			return new Consumer(
				$c->query('ActivityData'),
				$c->query('UserSettings')
			);
		});

		$container->registerService('DataHelper', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');
			return new DataHelper(
				$server->getActivityManager(),
				new Factory(
					$server->getActivityManager(),
					$server->getUserManager(),
					$server->getURLGenerator(),
					$server->getContactsManager(),
					$c->query('OCA\Activity\ViewInfoCache'),
					$c->query('ActivityL10N'),
					$c->query('CurrentUID')
				),
				$server->getL10NFactory(),
				$c->query('ActivityL10N')
			);
		});

		$container->registerService('GroupHelper', function(IContainer $c) {
			return new GroupHelper(
				$c->query('ServerContainer')->getActivityManager(),
				$c->query('DataHelper'),
				true
			);
		});

		$container->registerService('GroupHelperSingleEntries', function(IContainer $c) {
			return new GroupHelper(
				$c->query('ServerContainer')->getActivityManager(),
				$c->query('DataHelper'),
				false
			);
		});

		$container->registerService('Hooks', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new FilesHooks(
				$server->getActivityManager(),
				$c->query('ActivityData'),
				$c->query('UserSettings'),
				$server->getGroupManager(),
				new View(''),
				$server->getDatabaseConnection(),
				$server->getURLGenerator(),
				$c->query('CurrentUID')
			);
		});

		$container->registerService('MailQueueHandler', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new MailQueueHandler(
				$server->getDateTimeFormatter(),
				$server->getDatabaseConnection(),
				$c->query('DataHelper'),
				$server->getMailer(),
				$server->getURLGenerator(),
				$server->getUserManager(),
				$server->getActivityManager()
			);
		});

		$container->registerService('Navigation', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');
			$rssToken = ($c->query('CurrentUID') !== '') ? $server->getConfig()->getUserValue($c->query('CurrentUID'), 'activity', 'rsstoken') : '';

			return new Navigation(
				$c->query('ActivityL10N'),
				$server->getActivityManager(),
				$server->getURLGenerator(),
				$c->query('UserSettings'),
				$c->query('CurrentUID'),
				$rssToken
			);
		});

		$container->registerService('UserSettings', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');
			return new UserSettings(
				$server->getActivityManager(),
				$server->getConfig(),
				$c->query('ActivityData')
			);
		});

		$container->registerService('OCA\Activity\ViewInfoCache', function() {
			return new ViewInfoCache(
				new View('')
			);
		});

		/**
		 * Core Services
		 */
		$container->registerService('CurrentUID', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			$user = $server->getUserSession()->getUser();
			return ($user) ? $user->getUID() : '';
		});

		/**
		 * Controller
		 */
		$container->registerService('SettingsController', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new Settings(
				$c->query('AppName'),
				$server->getRequest(),
				$server->getConfig(),
				$server->getSecureRandom()->getMediumStrengthGenerator(),
				$server->getURLGenerator(),
				$c->query('ActivityData'),
				$c->query('UserSettings'),
				$c->query('ActivityL10N'),
				$c->query('CurrentUID')
			);
		});

		$container->registerService('OCA\Activity\Controller\OCSEndPoint', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new OCSEndPoint(
				$c->query('ActivityData'),
				$c->query('GroupHelper'),
				$c->query('UserSettings'),
				$server->getRequest(),
				$server->getURLGenerator(),
				$server->getUserSession(),
				$server->getPreviewManager(),
				$server->getMimeTypeDetector(),
				new View(''),
				$c->query('OCA\Activity\ViewInfoCache')
			);
		});

		$container->registerService('EndPointController', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new EndPoint(
				$c->query('AppName'),
				$server->getRequest(),
				$c->query('OCA\Activity\Controller\OCSEndPoint')
			);
		});

		$container->registerService('ActivitiesController', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new Activities(
				$c->query('AppName'),
				$server->getRequest(),
				$server->getConfig(),
				$c->query('ActivityData'),
				$c->query('Navigation')
			);
		});

		$container->registerService('FeedController', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new Feed(
				$c->query('AppName'),
				$server->getRequest(),
				$c->query('ActivityData'),
				$c->query('GroupHelperSingleEntries'),
				$c->query('UserSettings'),
				$server->getURLGenerator(),
				$server->getActivityManager(),
				$server->getL10NFactory(),
				$server->getConfig(),
				$c->query('CurrentUID')
			);
		});
	}
}
