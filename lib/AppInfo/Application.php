<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author John Molakvoæ <skjnldsv@protonmail.com>
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
use OCA\Activity\Capabilities;
use OCA\Activity\Consumer;
use OCA\Activity\FilesHooksStatic;
use OCA\Activity\Hooks;
use OCA\Activity\Listener\LoadSidebarScripts;
use OCA\Activity\NotificationGenerator;
use OCA\Files\Event\LoadSidebar;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Util;

class Application extends App implements IBootstrap {

	const APP_ID = 'activity';

	public function __construct () {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		// Allow automatic DI for the View, until we migrated to Nodes API
		$context->registerService(View::class, function() {
			return new View('');
		}, false);

		$context->registerCapability(Capabilities::class);

		$context->registerEventListener(LoadSidebar::class, LoadSidebarScripts::class);
	}

	public function boot(IBootContext $context): void {
		$this->registerActivityConsumer();
		$this->registerHooksAndEvents();
		$this->registerNotifier();
	}

	/**
	 * Registers the consumer to the Activity Manager
	 */
	private function registerActivityConsumer() {
		$c = $this->getContainer();
		/** @var \OCP\IServerContainer $server */
		$server = $c->getServer();

		$server->getActivityManager()->registerConsumer(function() use ($c) {
			return $c->query(Consumer::class);
		});
	}

	public function registerNotifier() {
		$server = $this->getContainer()->getServer();
		$server->getNotificationManager()->registerNotifierService(NotificationGenerator::class);
	}

	/**
	 * Register the hooks and events
	 */
	private function registerHooksAndEvents() {
		Util::connectHook('OC_User', 'post_deleteUser', Hooks::class, 'deleteUser');
		Util::connectHook('OC_User', 'post_login', Hooks::class, 'setDefaultsForUser');

		$this->registerFilesActivity();
	}

	/**
	 * Register the hooks for filesystem operations
	 */
	private function registerFilesActivity() {
		// All other events from other apps have to be send via the Consumer
		Util::connectHook('OC_Filesystem', 'post_create', FilesHooksStatic::class, 'fileCreate');
		Util::connectHook('OC_Filesystem', 'post_update', FilesHooksStatic::class, 'fileUpdate');
		Util::connectHook('OC_Filesystem', 'delete', FilesHooksStatic::class, 'fileDelete');
		Util::connectHook('OC_Filesystem', 'rename', FilesHooksStatic::class, 'fileMove');
		Util::connectHook('OC_Filesystem', 'post_rename', FilesHooksStatic::class, 'fileMovePost');
		Util::connectHook('\OCA\Files_Trashbin\Trashbin', 'post_restore', FilesHooksStatic::class, 'fileRestore');
		Util::connectHook('OCP\Share', 'post_shared', FilesHooksStatic::class, 'share');

		$eventDispatcher = $this->getContainer()->getServer()->getEventDispatcher();
		$eventDispatcher->addListener('OCP\Share::preUnshare', [FilesHooksStatic::class, 'unShare']);
		$eventDispatcher->addListener('OCP\Share::postUnshareFromSelf', [FilesHooksStatic::class, 'unShareSelf']);
	}
}
