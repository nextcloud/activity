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

namespace OCA\Activity\AppInfo;

use OC\Files\View;
use OCA\Activity\Consumer;
use OCA\Activity\Controller\Activities;
use OCA\Activity\Controller\Feed;
use OCA\Activity\Controller\Settings;
use OCA\Activity\Data;
use OCA\Activity\DataHelper;
use OCA\Activity\GroupHelper;
use OCA\Activity\FilesHooks;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\Navigation;
use OCA\Activity\ParameterHelper;
use OCA\Activity\UserSettings;
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
				new ParameterHelper(
					$server->getActivityManager(),
					$server->getUserManager(),
					$server->getURLGenerator(),
					$server->getContactsManager(),
					new View(''),
					$server->getConfig(),
					$c->query('ActivityL10N'),
					$c->query('CurrentUID')
				),
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
				$server->getUserManager()
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

		/**
		 * Core Services
		 */
		$container->registerService('URLGenerator', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');
			return $server->getURLGenerator();
		});

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

		$container->registerService('ActivitiesController', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new Activities(
				$c->query('AppName'),
				$server->getRequest(),
				$c->query('ActivityData'),
				$c->query('GroupHelper'),
				$c->query('Navigation'),
				$c->query('UserSettings'),
				$server->getDateTimeFormatter(),
				$server->getPreviewManager(),
				$server->getURLGenerator(),
				$server->getMimeTypeDetector(),
				new View(''),
				$c->query('CurrentUID')
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
				$c->query('URLGenerator'),
				$server->getActivityManager(),
				$server->getL10NFactory(),
				$server->getConfig(),
				$c->query('CurrentUID')
			);
		});
	}
}
