<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests\Listener;

use OCA\Activity\FilesHooks;
use OCA\Activity\Listener\NodeEventListener;
use OCP\EventDispatcher\Event;
use OCP\Files\Events\Node\BeforeNodeDeletedEvent;
use OCP\Files\Events\Node\BeforeNodeRenamedEvent;
use OCP\Files\Events\Node\BeforeNodeWrittenEvent;
use OCP\Files\Events\Node\NodeCreatedEvent;
use OCP\Files\Events\Node\NodeRenamedEvent;
use OCP\Files\Events\Node\NodeWrittenEvent;
use OCP\Files\File;
use OCP\Files\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NodeEventListenerTest extends TestCase {
	private FilesHooks&MockObject $filesHooks;
	private NodeEventListener $listener;

	protected function setUp(): void {
		parent::setUp();

		$this->filesHooks = $this->createMock(FilesHooks::class);
		$this->listener = new NodeEventListener($this->filesHooks);
	}

	private function getNode(string $path, bool $exists = true): File&MockObject {
		$node = $this->createMock(File::class);
		$node->method('getPath')
			->willReturn($path);
		if ($exists) {
			$node->method('getId')
				->willReturn(42);
		} else {
			$node->method('getId')
				->willThrowException(new NotFoundException());
		}
		return $node;
	}

	public function testHandleNodeCreatedEvent(): void {
		$node = $this->getNode('/user/files/file.txt');

		$this->filesHooks->expects($this->once())
			->method('fileCreate')
			->with($node);

		$this->listener->handle(new NodeCreatedEvent($node));
	}

	public function testHandleWriteOfExistingNode(): void {
		$node = $this->getNode('/user/files/file.txt');

		$this->filesHooks->expects($this->once())
			->method('fileUpdate')
			->with($node);

		$this->listener->handle(new BeforeNodeWrittenEvent($node));
		$this->listener->handle(new NodeWrittenEvent($node));
	}

	public function testHandleWriteOfNewNode(): void {
		$this->filesHooks->expects($this->never())
			->method('fileUpdate');

		$this->listener->handle(new BeforeNodeWrittenEvent($this->getNode('/user/files/file.txt', false)));
		$this->listener->handle(new NodeWrittenEvent($this->getNode('/user/files/file.txt')));
	}

	public function testHandleWriteWithoutBeforeEvent(): void {
		$this->filesHooks->expects($this->never())
			->method('fileUpdate');

		$this->listener->handle(new NodeWrittenEvent($this->getNode('/user/files/file.txt')));
	}

	public function testHandleWriteIsOnlyReportedOnce(): void {
		$node = $this->getNode('/user/files/file.txt');

		$this->filesHooks->expects($this->once())
			->method('fileUpdate');

		$this->listener->handle(new BeforeNodeWrittenEvent($node));
		$this->listener->handle(new NodeWrittenEvent($node));
		$this->listener->handle(new NodeWrittenEvent($node));
	}

	public function testHandleBeforeNodeDeletedEvent(): void {
		$node = $this->getNode('/user/files/file.txt');

		$this->filesHooks->expects($this->once())
			->method('fileDelete')
			->with($node);

		$this->listener->handle(new BeforeNodeDeletedEvent($node));
	}

	public function testHandleRenameEvents(): void {
		$source = $this->getNode('/user/files/old.txt');
		$target = $this->getNode('/user/files/new.txt');

		$this->filesHooks->expects($this->once())
			->method('fileMove')
			->with($source, $target);
		$this->filesHooks->expects($this->once())
			->method('fileMovePost')
			->with($source, $target);

		$this->listener->handle(new BeforeNodeRenamedEvent($source, $target));
		$this->listener->handle(new NodeRenamedEvent($source, $target));
	}

	public function testHandleIgnoresOtherEvents(): void {
		$this->filesHooks->expects($this->never())
			->method($this->anything());

		$this->listener->handle(new Event());
	}
}
