<?php

namespace OCA\Files_Sharing {
	interface SharedMount extends \OCP\Files\Mount\IMountPoint {
		public function getShare(): \OCP\Share\IShare;
	}
}

namespace OCA\Files_Sharing\External {
	abstract class Storage implements \OCP\Files\Storage\ISharedStorage {
		public function getToken(): string {
		}
	}
}
