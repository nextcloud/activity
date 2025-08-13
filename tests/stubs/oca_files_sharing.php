<?php

namespace OCA\Files_Sharing {
	interface SharedMount extends \OCP\Files\Mount\IMountPoint {
		public function getShare(): \OCP\Share\IShare;
	}
}
