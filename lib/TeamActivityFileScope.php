<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Files\Folder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;

class TeamActivityFileScope {
	/** @var array<int, string|null> */
	private array $paths = [];
	/** @var array<string, bool> */
	private array $readable = [];

	public function __construct(
		public readonly Node $root,
		public readonly string $viewerPath,
	) {
	}

	public function getVisiblePath(int $fileId): ?string {
		if (array_key_exists($fileId, $this->paths)) {
			return $this->paths[$fileId];
		}
		$this->paths[$fileId] = null;

		try {
			$nodes = $fileId === $this->root->getId()
				? [$this->root]
				: ($this->root instanceof Folder ? $this->root->getById($fileId) : []);
			$rootPath = rtrim($this->root->getPath(), '/');
			$mountPoint = $this->root->getMountPoint()->getMountPoint();
			foreach ($nodes as $node) {
				$path = $node->getPath();
				if ($path !== $rootPath && !str_starts_with($path, $rootPath . '/')) {
					continue;
				}
				$current = $node;
				while (true) {
					$currentPath = $current->getPath();
					if (!array_key_exists($currentPath, $this->readable)) {
						$this->readable[$currentPath] = $current->getMountPoint()->getMountPoint() === $mountPoint
							&& $current->isReadable();
					}
					if (!$this->readable[$currentPath]) {
						continue 2;
					}
					if ($currentPath === $rootPath) {
						$this->paths[$fileId] = rtrim($this->viewerPath, '/') . substr($path, strlen($rootPath));
						return $this->paths[$fileId];
					}
					$current = $current->getParent();
					if ($current->getPath() !== $rootPath && !str_starts_with($current->getPath(), $rootPath . '/')) {
						continue 2;
					}
				}
			}
		} catch (NotFoundException|NotPermittedException) {
			// A file that disappeared or lost access is not part of this stream.
		}

		return null;
	}
}
