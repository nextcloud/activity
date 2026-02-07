<?php

declare(strict_types=1);

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
	protected array $cacheId = [];

	public function __construct(
		protected IRootFolder $rootFolder,
	) {
	}

	public function getInfoById(string $user, int $fileId, string $path): array {
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
	 * @return array{path: string, exists: bool, is_dir: bool, view: string, node?: Node}
	 */
	protected function findInfoById(string $user, int $fileId, string $filePath): array {
		$cache = [
			'path' => $filePath,
			'exists' => false,
			'is_dir' => false,
			'view' => '',
		];

		$notFound = false;
		try {
			$userFolder = $this->rootFolder->getUserFolder($user);
			$entry = $userFolder->getFirstNodeById($fileId);
			if ($entry === null) {
				throw new NotFoundException('No entries returned');
			}

			$cache['path'] = $userFolder->getRelativePath($entry->getPath());
			$cache['is_dir'] = $entry instanceof Folder;
			$cache['exists'] = true;
			$cache['node'] = $entry;
		} catch (NotFoundException) {
			// The file was not found in the normal view,
			// maybe it is in the trashbin?
			try {
				$userTrashBin = $this->rootFolder->get('/' . $user . '/files_trashbin');
				if (!$userTrashBin instanceof Folder) {
					throw new NotFoundException('No trash bin found for user: ' . $user);
				}
				$entry = $userTrashBin->getFirstNodeById($fileId);
				if ($entry === null) {
					throw new NotFoundException('No entries returned');
				}

				$cache = [
					'path' => $userTrashBin->getRelativePath($entry->getPath()),
					'exists' => true,
					'is_dir' => $entry instanceof Folder,
					'view' => 'trashbin',
					'node' => $entry,
				];
			} catch (NotFoundException) {
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
