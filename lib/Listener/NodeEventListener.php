<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Listener;

use OCA\Activity\FilesHooks;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Files\Events\Node\BeforeNodeDeletedEvent;
use OCP\Files\Events\Node\BeforeNodeRenamedEvent;
use OCP\Files\Events\Node\BeforeNodeWrittenEvent;
use OCP\Files\Events\Node\NodeCreatedEvent;
use OCP\Files\Events\Node\NodeRenamedEvent;
use OCP\Files\Events\Node\NodeWrittenEvent;
use OCP\Files\NotFoundException;

/**
 * @template-implements IEventListener<NodeCreatedEvent|BeforeNodeWrittenEvent|NodeWrittenEvent|BeforeNodeDeletedEvent|BeforeNodeRenamedEvent|NodeRenamedEvent>
 */
class NodeEventListener implements IEventListener {
	/**
	 * Paths of the nodes currently being written that already existed,
	 * as a write is also dispatched when a node is created.
	 *
	 * @var array<string, true>
	 */
	private array $pendingUpdates = [];

	public function __construct(
		private readonly FilesHooks $fileHooks,
	) {
	}

	#[\Override]
	public function handle(Event $event): void {
		if ($event instanceof NodeCreatedEvent) {
			$this->fileHooks->fileCreate($event->getNode());
		} elseif ($event instanceof BeforeNodeWrittenEvent) {
			$this->handleBeforeNodeWritten($event);
		} elseif ($event instanceof NodeWrittenEvent) {
			$this->handleNodeWritten($event);
		} elseif ($event instanceof BeforeNodeDeletedEvent) {
			$this->fileHooks->fileDelete($event->getNode());
		} elseif ($event instanceof BeforeNodeRenamedEvent) {
			$this->fileHooks->fileMove($event->getSource(), $event->getTarget());
		} elseif ($event instanceof NodeRenamedEvent) {
			$this->fileHooks->fileMovePost($event->getSource(), $event->getTarget());
		}
	}

	private function handleBeforeNodeWritten(BeforeNodeWrittenEvent $event): void {
		$node = $event->getNode();
		try {
			// Nodes that do not exist yet throw, those writes are reported as created
			$node->getId();
			$this->pendingUpdates[$node->getPath()] = true;
		} catch (NotFoundException) {
			unset($this->pendingUpdates[$node->getPath()]);
		}
	}

	private function handleNodeWritten(NodeWrittenEvent $event): void {
		$node = $event->getNode();
		if (!isset($this->pendingUpdates[$node->getPath()])) {
			return;
		}

		unset($this->pendingUpdates[$node->getPath()]);
		$this->fileHooks->fileUpdate($node);
	}
}
