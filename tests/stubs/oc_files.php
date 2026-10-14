<?php

namespace OC\Files\Storage\Wrapper {
	abstract class Wrapper implements \OCP\Files\Storage\IStorage {
		public function getWrapperStorage(): \OCP\Files\Storage\IStorage {
		}
	}
}
