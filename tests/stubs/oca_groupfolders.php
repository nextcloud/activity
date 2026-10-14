<?php

namespace OCA\GroupFolders\ACL\UserMapping {
	interface IUserMapping {
		public function getType(): string;

		public function getId(): string;
	}
}

namespace OCA\GroupFolders\ACL {
	class Rule {
		public function getUserMapping(): UserMapping\IUserMapping {
		}

		public function getMask(): int {
		}

		public function getPermissions(): int {
		}
	}

	class RuleManager {
		/**
		 * @param string[] $filePaths
		 * @return array<string, Rule[]>
		 */
		public function getAllRulesForPaths(int $storageId, array $filePaths): array {
		}
	}
}

namespace OCA\GroupFolders\Folder {
	class FolderManager {
		public function getFolderAclEnabled(int $id): bool {
		}
	}
}
