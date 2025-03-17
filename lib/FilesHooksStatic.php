<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity;

/**
 * The class to handle the filesystem hooks
 */
class FilesHooksStatic {
	/**
	 * @return FilesHooks
	 */
	protected static function getHooks() {
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
}
