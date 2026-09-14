<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity\Tests;

use OCA\Activity\HTMLSubjectFormatter;
use OCP\Activity\IEvent;
use PHPUnit\Framework\MockObject\MockObject;

class HTMLSubjectFormatterTest extends TestCase {
	private function getEvent(string $richSubject, array $richParams, string $parsedSubject): IEvent&MockObject {
		$event = $this->createMock(IEvent::class);
		$event->method('getRichSubject')->willReturn($richSubject);
		$event->method('getRichSubjectParameters')->willReturn($richParams);
		$event->method('getParsedSubject')->willReturn($parsedSubject);
		return $event;
	}

	public function testNoRichSubjectFallsToParsed(): void {
		$event = $this->getEvent('', [], 'Plain <subject>');
		$this->assertSame('Plain &lt;subject&gt;', HTMLSubjectFormatter::format($event));
	}

	public function testFileParameterUsesPath(): void {
		$event = $this->getEvent(
			'File {file} was changed',
			['file' => ['type' => 'file', 'path' => '/my/file.txt', 'name' => 'file.txt']],
			'',
		);
		$this->assertSame('File <strong>/my/file.txt</strong> was changed', HTMLSubjectFormatter::format($event));
	}

	public function testNonFileParameterUsesName(): void {
		$event = $this->getEvent(
			'{user} shared',
			['user' => ['type' => 'user', 'name' => 'Alice & Bob']],
			'',
		);
		$this->assertSame('<strong>Alice &amp; Bob</strong> shared', HTMLSubjectFormatter::format($event));
	}

	public function testParameterWithLinkRendersAnchor(): void {
		$event = $this->getEvent(
			'See {file}',
			['file' => ['type' => 'file', 'path' => 'report.pdf', 'name' => 'report.pdf', 'link' => 'https://example.com/report.pdf']],
			'',
		);
		$this->assertSame('See <a href="https://example.com/report.pdf">report.pdf</a>', HTMLSubjectFormatter::format($event));
	}
}
