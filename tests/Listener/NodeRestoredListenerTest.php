<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests\Listener;

use OCA\Activity\FilesHooks;
use OCA\Activity\Listener\NodeRestoredListener;
use OCA\Files_Trashbin\Events\NodeRestoredEvent;
use OCP\EventDispatcher\Event;
use OCP\Files\File;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NodeRestoredListenerTest extends TestCase {
	private FilesHooks&MockObject $filesHooks;
	private NodeRestoredListener $listener;

	protected function setUp(): void {
		parent::setUp();

		$this->filesHooks = $this->createMock(FilesHooks::class);
		$this->listener = new NodeRestoredListener($this->filesHooks);
	}

	public function testHandleNodeRestoredEvent(): void {
		$source = $this->createMock(File::class);
		$target = $this->createMock(File::class);

		$this->filesHooks->expects($this->once())
			->method('fileRestore')
			->with($target);

		$this->listener->handle(new NodeRestoredEvent($source, $target));
	}

	public function testHandleIgnoresOtherEvents(): void {
		$this->filesHooks->expects($this->never())
			->method('fileRestore');

		$this->listener->handle(new Event());
	}
}
