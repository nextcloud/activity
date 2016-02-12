<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Vincent Petry <pvince81@owncloud.com>
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

namespace OCA\Activity;

use OCP\Util;

/**
 * The class to handle the filesystem hooks
 */
class FilesHooksStatic {
	/**
	 * Registers the filesystem hooks for basic filesystem operations.
	 * All other events has to be triggered by the apps.
	 */
	public static function register() {
		Util::connectHook('OC_Filesystem', 'post_create', 'OCA\Activity\FilesHooksStatic', 'fileCreate');
		Util::connectHook('OC_Filesystem', 'post_update', 'OCA\Activity\FilesHooksStatic', 'fileUpdate');
		Util::connectHook('OC_Filesystem', 'delete', 'OCA\Activity\FilesHooksStatic', 'fileDelete');
		Util::connectHook('\OCA\Files_Trashbin\Trashbin', 'post_restore', 'OCA\Activity\FilesHooksStatic', 'fileRestore');
		Util::connectHook('OCP\Share', 'post_shared', 'OCA\Activity\FilesHooksStatic', 'share');
		Util::connectHook('OCP\Share', 'pre_unshare', 'OCA\Activity\FilesHooksStatic', 'unShare');

		$eventDispatcher = \OC::$server->getEventDispatcher();
		$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', ['OCA\Activity\FilesHooksStatic', 'onLoadFilesAppScripts']);
	}

	/**
	 * @return FilesHooks
	 */
	static protected function getHooks() {
		$app = new AppInfo\Application();
		return $app->getContainer()->query('Hooks');
	}

	/**
	 * Store the create hook events
	 * @param array $params The hook params
	 */
	public static function fileCreate($params) {
		self::getHooks()->fileCreate($params['path']);
	}

	/**
	 * Store the update hook events
	 * @param array $params The hook params
	 */
	public static function fileUpdate($params) {
		self::getHooks()->fileUpdate($params['path']);
	}

	/**
	 * Store the delete hook events
	 * @param array $params The hook params
	 */
	public static function fileDelete($params) {
		self::getHooks()->fileDelete($params['path']);
	}

	/**
	 * Store the restore hook events
	 * @param array $params The hook params
	 */
	public static function fileRestore($params) {
		self::getHooks()->fileRestore($params['filePath']);
	}

	/**
	 * Manage sharing events
	 * @param array $params The hook params
	 */
	public static function share($params) {
		self::getHooks()->share($params);
	}

	/**
	 * Manage sharing events
	 * @param array $params The hook params
	 */
	public static function unShare($params) {
		self::getHooks()->unShare($params);
	}

	/**
	 * Load additional scripts when the files app is visible
	 */
	public static function onLoadFilesAppScripts() {
		Util::addStyle('activity', 'style');
		Util::addScript('activity', 'formatter');
		Util::addScript('activity', 'activitymodel');
		Util::addScript('activity', 'activitycollection');
		Util::addScript('activity', 'activitytabview');
		Util::addScript('activity', 'filesplugin');
	}
}
