<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Files_Sharing;

use OC\Files\Mount\MountPoint;
use OCA\Files_Sharing\Exceptions\BrokenPath;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Files\Mount\IMovableMount;
use OCP\Files\Storage\IStorageFactory;
use OCP\IUser;
use OCP\Share\IShare;
use Override;

/**
 * Shared mount points can be moved by the user
 */
class SharedMount extends MountPoint implements IMovableMount, ISharedMountPoint {
	/**
	 * @var ?SharedStorage $storage
	 */
	protected $storage = null;

	public function __construct(
		$storage,
		$arguments,
		IStorageFactory $loader,
		private IEventDispatcher $eventDispatcher,
		private IUser $user,
	) {
	}

	/**
	 * update fileTarget in the database if the mount point changed
	 *
	 * @param string $newPath
	 * @param IShare $share
	 * @return bool
	 */
	protected function updateFileTarget($newPath, &$share) {
	}

	/**
	 * Format a path to be relative to the /user/files/ directory
	 *
	 * The result is normalized, so it never carries a trailing slash. Callers may
	 * pass mount points, which always end in a slash.
	 *
	 * @param string $path the absolute path
	 * @return string e.g. turns '/admin/files/test.txt' into '/test.txt'
	 * @throws BrokenPath
	 */
	protected function stripUserFilesPath(string $path): string {
	}

	#[Override]
	public function moveMount(string $target): bool {
	}

	#[Override]
	public function removeMount(): bool {
	}

	/**
	 * @return IShare
	 */
	public function getShare() {
	}

	/**
	 * @return IShare[]
	 */
	public function getGroupedShares(): array {
	}

	/**
	 * Get the file id of the root of the storage
	 *
	 * @return int
	 */
	#[\Override]
	public function getStorageRootId() {
	}

	/**
	 * @return int
	 */
	#[\Override]
	public function getNumericStorageId() {
	}

	#[Override]
	public function getMountType(): string {
	}

	public function getUser(): IUser {
	}
}
