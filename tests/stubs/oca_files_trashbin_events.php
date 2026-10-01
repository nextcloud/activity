<?php

namespace OCA\Files_Trashbin\Events {
	class NodeRestoredEvent extends \OCP\Files\Events\Node\AbstractNodesEvent {
		public function __construct(\OCP\Files\Node $source, \OCP\Files\Node $target) {
		}
	}
}
