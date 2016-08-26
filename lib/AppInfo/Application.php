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

namespace OCA\Activity\AppInfo;

use OC\Files\View;
use OCA\Activity\Controller\Activities;
use OCA\Activity\Controller\Feed;
use OCA\Activity\Controller\OCSEndPoint;
use OCA\Activity\Controller\Settings;
use OCA\Activity\GroupHelper;
use OCA\Activity\FilesHooks;
use OCA\Activity\Navigation;
use OCA\Activity\Parameter\Factory;
use OCA\Activity\ViewInfoCache;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\IContainer;
use OCP\Util;

class Application extends App {
	public function __construct (array $urlParams = array()) {
		parent::__construct('activity', $urlParams);
		$container = $this->getContainer();

		/**
		 * Activity Services
		 */
		$container->registerService('OCA\Activity\Parameter\Factory', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');
			return new Factory(
				$server->getActivityManager(),
				$server->getUserManager(),
				$server->getURLGenerator(),
				$server->getContactsManager(),
				$c->query('OCA\Activity\ViewInfoCache'),
				$c->query('OCP\IL10N'),
				$c->query('CurrentUID')
			);
		});

		$container->registerService('OCA\Activity\FilesHooks', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new FilesHooks(
				$server->getActivityManager(),
				$c->query('OCA\Activity\Data'),
				$c->query('OCA\Activity\UserSettings'),
				$server->getGroupManager(),
				new View(''),
				$server->getDatabaseConnection(),
				$server->getURLGenerator(),
				$c->query('CurrentUID')
			);
		});

		$container->registerService('OCA\Activity\Navigation', function(IContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');
			$rssToken = ($c->query('CurrentUID') !== '') ? $server->getConfig()->getUserValue($c->query('CurrentUID'), 'activity', 'rsstoken') : '';

			return new Navigation(
				$c->query('OCP\IL10N'),
				$server->getActivityManager(),
				$server->getURLGenerator(),
				$c->query('CurrentUID'),
				$rssToken
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
			/** @var \OCA\Activity\CurrentUser $currentUser */
			$currentUser = $c->query('OCA\Activity\CurrentUser');
			return $currentUser->getUserIdentifier();
		});

		/**
		 * Controller
		 */
		$container->registerService('SettingsController', function(IAppContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new Settings(
				$c->getAppName(),
				$server->getRequest(),
				$server->getConfig(),
				$server->getSecureRandom()->getMediumStrengthGenerator(),
				$server->getURLGenerator(),
				$c->query('OCA\Activity\Data'),
				$c->query('OCA\Activity\UserSettings'),
				$c->query('OCP\IL10N'),
				$c->query('CurrentUID')
			);
		});

		$container->registerService('OCA\Activity\Controller\OCSEndPoint', function(IAppContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new OCSEndPoint(
				$c->query('OCA\Activity\Data'),
				$c->query('OCA\Activity\GroupHelper'),
				$c->query('OCA\Activity\UserSettings'),
				$server->getRequest(),
				$server->getURLGenerator(),
				$server->getUserSession(),
				$server->getPreviewManager(),
				$server->getMimeTypeDetector(),
				new View(''),
				$c->query('OCA\Activity\ViewInfoCache')
			);
		});

		$container->registerAlias('EndPointController', 'OCA\Activity\Controller\EndPoint');
		$container->registerAlias('ActivitiesController', 'OCA\Activity\Controller\Activities');

		$container->registerService('FeedController', function(IAppContainer $c) {
			/** @var \OC\Server $server */
			$server = $c->query('ServerContainer');

			return new Feed(
				$c->getAppName(),
				$server->getRequest(),
				$c->query('OCA\Activity\Data'),
				$c->query('OCA\Activity\GroupHelperDisabled'),
				$c->query('OCA\Activity\UserSettings'),
				$server->getURLGenerator(),
				$server->getActivityManager(),
				$server->getL10NFactory(),
				$server->getConfig(),
				$c->query('CurrentUID')
			);
		});
	}

	/**
	 * Register the navigation entry
	 */
	public function registerNavigationEntry() {
		$c = $this->getContainer();
		/** @var \OCP\IServerContainer $server */
		$server = $c->getServer();

		$navigationEntry = function () use ($c, $server) {
			return [
				'id' => $c->getAppName(),
				'order' => 1,
				'name' => $c->query('OCP\IL10N')->t('Activity'),
				'href' => $server->getURLGenerator()->linkToRoute('activity.Activities.showList'),
				'icon' => $server->getURLGenerator()->imagePath($c->getAppName(), 'activity.svg'),
			];
		};
		$server->getNavigationManager()->add($navigationEntry);
	}

	/**
	 * Registers the consumer to the Activity Manager
	 */
	public function registerActivityConsumer() {
		$c = $this->getContainer();
		/** @var \OCP\IServerContainer $server */
		$server = $c->getServer();

		$server->getActivityManager()->registerConsumer(function() use ($c) {
			return $c->query('OCA\Activity\Consumer');
		});
	}

	/**
	 * Register the hooks and events
	 */
	public function registerHooksAndEvents() {
		$eventDispatcher = $this->getContainer()->getServer()->getEventDispatcher();
		$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', ['OCA\Activity\FilesHooksStatic', 'onLoadFilesAppScripts']);

		Util::connectHook('OC_User', 'post_deleteUser', 'OCA\Activity\Hooks', 'deleteUser');

		$this->registerFilesActivity();
	}

	/**
	 * Register the hooks for filesystem operations
	 */
	public function registerFilesActivity() {
		// All other events from other apps have to be send via the Consumer
		Util::connectHook('OC_Filesystem', 'post_create', 'OCA\Activity\FilesHooksStatic', 'fileCreate');
		Util::connectHook('OC_Filesystem', 'post_update', 'OCA\Activity\FilesHooksStatic', 'fileUpdate');
		Util::connectHook('OC_Filesystem', 'delete', 'OCA\Activity\FilesHooksStatic', 'fileDelete');
		Util::connectHook('\OCA\Files_Trashbin\Trashbin', 'post_restore', 'OCA\Activity\FilesHooksStatic', 'fileRestore');
		Util::connectHook('OCP\Share', 'post_shared', 'OCA\Activity\FilesHooksStatic', 'share');
		Util::connectHook('OCP\Share', 'pre_unshare', 'OCA\Activity\FilesHooksStatic', 'unShare');
	}

	/**
	 * Register personal settings for notifications and emails
	 */
	public function registerPersonalPage() {
		\OCP\App::registerPersonal($this->getContainer()->getAppName(), 'personal');
	}
}
