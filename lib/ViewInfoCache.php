<?php

/**
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity;

use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;

class ViewInfoCache {
	/** @var array */
	protected $cachePath;
	/** @var array */
	protected $cacheId;


	public function __construct(
		protected IRootFolder $rootFolder,
	) {
	}

	/**
	 * @param string $user
	 * @param int $fileId
	 * @param string $path
	 * @return array
	 */
	public function getInfoById($user, $fileId, $path) {
		if (isset($this->cacheId[$user][$fileId])) {
			$cache = $this->cacheId[$user][$fileId];
			if ($cache['path'] === null) {
				$cache['path'] = $path;
			}
			return $cache;
		}

		return $this->findInfoById($user, $fileId, $path);
	}

	/**
	 * @param string $user
	 * @param int $fileId
	 * @param string $filePath
	 * @return array
	 */
	protected function findInfoById($user, $fileId, $filePath) {
		$cache = [
			'path' => $filePath,
			'exists' => false,
			'is_dir' => false,
			'view' => '',
		];

		$notFound = false;
		try {
			$userFolder = $this->rootFolder->getUserFolder($user);
			$entries = $userFolder->getById($fileId);
			if (empty($entries)) {
				throw new NotFoundException('No entries returned');
			}
			/** @var Node $entry */
			$entry = array_shift($entries);

			$cache['path'] = $userFolder->getRelativePath($entry->getPath());
			$cache['is_dir'] = $entry instanceof Folder;
			$cache['exists'] = true;
			$cache['node'] = $entry;
		} catch (NotFoundException $e) {
			// The file was not found in the normal view,
			// maybe it is in the trashbin?
			try {
				/** @var Folder $userTrashBin */
				$userTrashBin = $this->rootFolder->get('/' . $user . '/files_trashbin');
				$entries = $userTrashBin->getById($fileId);
				if (empty($entries)) {
					throw new NotFoundException('No entries returned');
				}

				/** @var Node $entry */
				$entry = array_shift($entries);

				$cache = [
					'path' => $userTrashBin->getRelativePath($entry->getPath()),
					'exists' => true,
					'is_dir' => $entry instanceof Folder,
					'view' => 'trashbin',
					'node' => $entry,
				];
			} catch (NotFoundException $e) {
				$notFound = true;
			}
		}

		$this->cacheId[$user][$fileId] = $cache;
		if ($notFound) {
			$this->cacheId[$user][$fileId]['path'] = null;
		}

		return $cache;
	}
}
