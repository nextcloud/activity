<?php

namespace OC\Files {
	class Filesystem {
		public static function initMountPoints(string|\OCP\IUser|null $user = ''): void {
		}

		public static function getView(): ?View {
		}

		/**
		 * @param string $path
		 * @param bool|string $includeMountPoints
		 * @return FileInfo|false
		 */
		public static function getFileInfo($path, $includeMountPoints = true) {
		}
	}

	class View {
		public function __construct(string $root = '') {
		}

		/**
		 * @psalm-template S as string|null
		 * @psalm-param S $path
		 * @psalm-return (S is string ? string : null)
		 */
		public function getAbsolutePath(?string $path = '/'): ?string {
		}

		/**
		 * @return array{?\OCP\Files\Storage\IStorage, string}
		 */
		public function resolvePath(string $path): array {
		}

		/**
		 * @throws \OCP\Files\NotFoundException
		 */
		public function getOwner(string $path): string {
		}

		/**
		 * @param int $id
		 * @throws \OCP\Files\NotFoundException
		 */
		public function getPath($id, ?int $storageId = null): string {
		}
	}

	abstract class FileInfo implements \OCP\Files\FileInfo, \ArrayAccess {
	}
}

namespace OC\Files\Storage\Wrapper {
	abstract class Wrapper implements \OCP\Files\Storage\IStorage {
		public function getWrapperStorage(): \OCP\Files\Storage\IStorage {
		}
	}
}
