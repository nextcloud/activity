<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @copyright Copyright (c) 2017, Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Vincent Petry <pvince81@owncloud.com>
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

namespace OCA\Activity;

use OCP\Share\IShare;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * The class to handle the filesystem hooks
 */
class FilesHooksStatic {

	/**
	 * @return FilesHooks
	 */
	static protected function getHooks() {
		return \OC::$server->query(FilesHooks::class);
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
	 * Store the rename hook events
	 * @param array $params The hook params
	 */
	public static function fileMove($params) {
		self::getHooks()->fileMove($params['oldpath'], $params['newpath']);
	}

	/**
	 * Store the rename hook events
	 * @param array $params The hook params
	 */
	public static function fileMovePost($params) {
		self::getHooks()->fileMovePost($params['oldpath'], $params['newpath']);
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
	 * Unsharing event
	 * @param GenericEvent $event
	 */
	public static function unShare(GenericEvent $event) {
		$share = $event->getSubject();
		if ($share instanceof IShare) {
			self::getHooks()->unShare($share);
		}
	}

	/**
	 * "Unsharing a share from self only" event
	 * @param GenericEvent $event
	 */
	public static function unShareSelf(GenericEvent $event) {
		$share = $event->getSubject();
		if ($share instanceof IShare) {
			self::getHooks()->unShareSelf($share);
		}
	}
}
