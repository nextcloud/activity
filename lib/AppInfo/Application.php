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
use OCA\Activity\FilesHooks;
use OCP\AppFramework\App;
use OCP\IContainer;
use OCP\Util;

class Application extends App {
	public function __construct () {
		parent::__construct('activity');
		$container = $this->getContainer();

		// Allow automatic DI for the View, until we migrated to Nodes API
		$container->registerService('OC\Files\View', function() {
			return new View('');
		}, false);

		// Aliases for the controllers so we can use the automatic DI
		$container->registerAlias('ActivitiesController', 'OCA\Activity\Controller\Activities');
		$container->registerAlias('EndPointController', 'OCA\Activity\Controller\EndPoint');
		$container->registerAlias('FeedController', 'OCA\Activity\Controller\Feed');
		$container->registerAlias('SettingsController', 'OCA\Activity\Controller\Settings');
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
