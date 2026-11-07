<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests;

use OCA\Activity\TeamActivityFileScope;
use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\Mount\IMountPoint;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TeamActivityFileScopeTest extends TestCase {
	public static function visibility(): array {
		return [
			'readable path' => [true, true, '/viewer/files/Team/Child/a.txt', '/viewer/files/Team/', '/Team/Child/a.txt'],
			'denied file' => [false, true, '/viewer/files/Team/Child/a.txt', '/viewer/files/Team/', null],
			'denied ancestor' => [true, false, '/viewer/files/Team/Child/a.txt', '/viewer/files/Team/', null],
			'nested mount' => [true, true, '/viewer/files/Team/Child/a.txt', '/viewer/files/Team/Child/', null],
			'other resource' => [true, true, '/viewer/files/Team2/a.txt', '/viewer/files/Team/', null],
		];
	}

	#[DataProvider('visibility')]
	public function testVisibilityChecksTheWholePathAndMount(bool $readable, bool $parentReadable, string $path, string $mountPoint, ?string $expected): void {
		$mount = $this->createMock(IMountPoint::class);
		$mount->method('getMountPoint')->willReturn('/viewer/files/Team/');
		$root = $this->createMock(Folder::class);
		$root->method('getId')->willReturn(100);
		$root->method('getPath')->willReturn('/viewer/files/Team');
		$root->method('getMountPoint')->willReturn($mount);
		$root->method('isReadable')->willReturn(true);
		$root->expects($this->never())->method('getDirectoryListing');
		$parent = $this->createMock(Folder::class);
		$parent->method('getPath')->willReturn('/viewer/files/Team/Child');
		$parent->method('getMountPoint')->willReturn($mount);
		$parent->method('isReadable')->willReturn($parentReadable);
		$parent->method('getParent')->willReturn($root);
		$parent->expects($this->never())->method('getDirectoryListing');
		$fileMount = $this->createMock(IMountPoint::class);
		$fileMount->method('getMountPoint')->willReturn($mountPoint);
		$file = $this->createMock(File::class);
		$file->method('getPath')->willReturn($path);
		$file->method('getMountPoint')->willReturn($fileMount);
		$file->method('isReadable')->willReturn($readable);
		$file->method('getParent')->willReturn($parent);
		$root->expects($this->once())->method('getById')->with(7)->willReturn([$file]);
		$scope = new TeamActivityFileScope($root, '/Team');

		self::assertSame($expected, $scope->getVisiblePath(7));
		self::assertSame($expected, $scope->getVisiblePath(7));
	}

	public function testAnIndividualFileDoesNotIncludeOtherFiles(): void {
		$mount = $this->createMock(IMountPoint::class);
		$mount->method('getMountPoint')->willReturn('/viewer/files/');
		$file = $this->createMock(File::class);
		$file->method('getId')->willReturn(7);
		$file->method('getPath')->willReturn('/viewer/files/a.txt');
		$file->method('getMountPoint')->willReturn($mount);
		$file->method('isReadable')->willReturn(true);
		$scope = new TeamActivityFileScope($file, '/a.txt');

		self::assertSame('/a.txt', $scope->getVisiblePath(7));
		self::assertNull($scope->getVisiblePath(8));
	}

	public function testMissingOrInaccessibleCandidatesAreExcluded(): void {
		foreach ([new NotFoundException(), new NotPermittedException()] as $exception) {
			$root = $this->createMock(Folder::class);
			$root->method('getId')->willReturn(100);
			$root->method('getById')->willThrowException($exception);
			$scope = new TeamActivityFileScope($root, '/Team');
			self::assertNull($scope->getVisiblePath(7));
		}
	}

	public function testOtherMountsAreTriedAfterAnUnreadableMount(): void {
		$scopes = [];
		foreach ([false, true] as $readable) {
			$mount = $this->createMock(IMountPoint::class);
			$mount->method('getMountPoint')->willReturn($readable ? '/Second/' : '/First/');
			$root = $this->createMock(File::class);
			$root->method('getId')->willReturn(7);
			$root->method('getPath')->willReturn($readable ? '/Second/a.txt' : '/First/a.txt');
			$root->method('getMountPoint')->willReturn($mount);
			$root->method('isReadable')->willReturn($readable);
			$scopes[] = new TeamActivityFileScope($root, $readable ? '/Second/a.txt' : '/First/a.txt');
		}
		self::assertNull($scopes[0]->getVisiblePath(7));
		self::assertSame('/Second/a.txt', $scopes[1]->getVisiblePath(7));
	}
}
