<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests;

use OCA\Activity\TeamActivityResolver;
use OCP\Files\Folder;
use OCP\Files\File;
use OCP\Files\IRootFolder;
use OCP\Files\IUserFolder;
use OCP\Files\Mount\IMountPoint;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\DB\IResult;
use OCP\DB\QueryBuilder\IExpressionBuilder;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\Teams\ITeamActivityResourceProvider;
use OCP\Teams\ITeamManager;
use OCP\Teams\ITeamResourceProvider;
use OCP\Teams\TeamActivityScope as ResourceActivityScope;
use OCP\Teams\TeamResource;
use PHPUnit\Framework\TestCase;

class TeamActivityResolverTest extends TestCase {
	public function testNonMemberCannotResolveActivityObjects(): void {
		$teamManager = $this->createMock(ITeamManager::class);
		$connection = $this->createMock(IDBConnection::class);
		$teamManager->expects($this->exactly(2))
			->method('getMembersOfTeam')
			->with('team-1', 'alice')
			->willReturn(['bob' => 'Bob']);
		$connection->expects($this->never())->method('getQueryBuilder');

		$resolver = new TeamActivityResolver($teamManager, $connection, $this->createMock(IRootFolder::class));

		self::assertNull($resolver->getActivityObjectId('team-1', 'alice'));
		self::assertNull($resolver->getScope('team-1', 'alice'));
	}

	public function testFolderScopeExcludesUnreadableSubtrees(): void {
		$rootFolder = $this->createMock(IRootFolder::class);
		$userFolder = $this->createMock(IUserFolder::class);
		$folder = $this->createMock(Folder::class);
		$denied = $this->createMock(Folder::class);
		$mount = $this->createMock(IMountPoint::class);
		$mount->method('getMountPoint')->willReturn('/alice/files/Team/');
		$rootFolder->method('getUserFolder')->with('alice')->willReturn($userFolder);
		$userFolder->method('getById')->with(200)->willReturn([$folder]);
		$userFolder->method('getRelativePath')->willReturn('/Team');
		$folder->method('getId')->willReturn(200);
		$folder->method('isReadable')->willReturn(true);
		$folder->method('getMountPoint')->willReturn($mount);
		$folder->method('getDirectoryListing')->willReturn([$denied]);
		$denied->method('getId')->willReturn(201);
		$denied->method('isReadable')->willReturn(false);
		$denied->expects($this->never())->method('getDirectoryListing');
		$resolver = new TeamActivityResolver($this->createMock(ITeamManager::class), $this->createMock(IDBConnection::class), $rootFolder);

		$method = new \ReflectionMethod($resolver, 'getResourceObjects');
		self::assertSame([200 => '/Team'], $method->invoke($resolver, [200], 'alice'));
	}

	public function testEachMountIsCheckedAndNestedMountsAreExcluded(): void {
		$rootFolder = $this->createMock(IRootFolder::class);
		$userFolder = $this->createMock(IUserFolder::class);
		$rootFolder->method('getUserFolder')->willReturn($userFolder);
		$userFolder->method('getRelativePath')->willReturnArgument(0);
		$roots = [];
		foreach (['/First', '/Second'] as $path) {
			$mount = $this->createMock(IMountPoint::class);
			$mount->method('getMountPoint')->willReturn($path);
			$root = $this->createMock(Folder::class);
			$root->method('getId')->willReturn(200);
			$root->method('getPath')->willReturn($path);
			$root->method('isReadable')->willReturn(true);
			$root->method('getMountPoint')->willReturn($mount);
			$child = $this->createMock(Folder::class);
			$child->method('getId')->willReturn(201);
			$child->method('getPath')->willReturn($path . '/Child');
			$child->method('isReadable')->willReturn($path === '/Second');
			$child->method('getMountPoint')->willReturn($mount);
			$foreign = $this->createMock(Folder::class);
			$foreign->method('getId')->willReturn(300);
			$foreign->method('isReadable')->willReturn(true);
			$foreignMount = $this->createMock(IMountPoint::class);
			$foreignMount->method('getMountPoint')->willReturn($path . '/Shared');
			$foreign->method('getMountPoint')->willReturn($foreignMount);
			$foreign->expects($this->never())->method('getDirectoryListing');
			$root->method('getDirectoryListing')->willReturn([$child, $foreign]);
			$roots[] = $root;
		}
		$userFolder->method('getById')->willReturn($roots);
		$resolver = new TeamActivityResolver($this->createMock(ITeamManager::class), $this->createMock(IDBConnection::class), $rootFolder);
		$method = new \ReflectionMethod($resolver, 'getResourceObjects');
		self::assertSame([200 => '/First', 201 => '/Second/Child'], $method->invoke($resolver, [200], 'alice'));
	}

	public function testMissingUserFolderHasNoFileScope(): void {
		$rootFolder = $this->createMock(IRootFolder::class);
		$rootFolder->method('getUserFolder')->willThrowException(new NotFoundException());
		$resolver = new TeamActivityResolver($this->createMock(ITeamManager::class), $this->createMock(IDBConnection::class), $rootFolder);
		$method = new \ReflectionMethod($resolver, 'getResourceObjects');
		self::assertSame([], $method->invoke($resolver, [200], 'alice'));
	}

	public function testResourceScopeIncludesFileRootsAndFolderSubtrees(): void {
		$rootFolder = $this->createMock(IRootFolder::class);
		$userFolder = $this->createMock(IUserFolder::class);
		$folder = $this->createMock(Folder::class);
		$file = $this->createMock(File::class);
		$child = $this->createMock(File::class);
		$mount = $this->createMock(IMountPoint::class);
		$mount->method('getMountPoint')->willReturn('/alice/files/');
		$rootFolder->method('getUserFolder')->with('alice')->willReturn($userFolder);
		$userFolder->method('getById')->willReturnMap([[100, [$file]], [200, [$folder]]]);
		$userFolder->method('getRelativePath')->willReturnMap([
			['/alice/files/readme.md', '/readme.md'],
			['/alice/files/Shared', '/Shared'],
			['/alice/files/Shared/note.md', '/Shared/note.md'],
		]);
		foreach ([[$file, 100, '/alice/files/readme.md'], [$folder, 200, '/alice/files/Shared'], [$child, 201, '/alice/files/Shared/note.md']] as [$node, $id, $path]) {
			$node->method('getId')->willReturn($id);
			$node->method('getPath')->willReturn($path);
			$node->method('isReadable')->willReturn(true);
			$node->method('getMountPoint')->willReturn($mount);
		}
		$folder->method('getDirectoryListing')->willReturn([$child]);
		$resolver = new TeamActivityResolver($this->createMock(ITeamManager::class), $this->createMock(IDBConnection::class), $rootFolder);

		$method = new \ReflectionMethod($resolver, 'getResourceObjects');
		self::assertSame([100 => '/readme.md', 200 => '/Shared', 201 => '/Shared/note.md'], $method->invoke($resolver, [100, 200], 'alice'));
	}

	public function testScopeUsesOnlyVisibleFileResources(): void {
		$teamManager = $this->createMock(ITeamManager::class);
		$connection = $this->createMock(IDBConnection::class);
		$rootFolder = $this->createMock(IRootFolder::class);
		$userFolder = $this->createMock(IUserFolder::class);
		$file = $this->createMock(File::class);
		$groupFolderFile = $this->createMock(File::class);
		$mount = $this->createMock(IMountPoint::class);
		$mount->method('getMountPoint')->willReturn('/alice/files/');
		$file->method('getId')->willReturn(100);
		$file->method('getPath')->willReturn('/alice/files/readme.md');
		$file->method('isReadable')->willReturn(true);
		$file->method('getMountPoint')->willReturn($mount);
		$groupFolderFile->method('getId')->willReturn(200);
		$groupFolderFile->method('getPath')->willReturn('/alice/files/Team/readme.md');
		$groupFolderFile->method('isReadable')->willReturn(true);
		$groupFolderFile->method('getMountPoint')->willReturn($mount);
		$rootFolder->expects($this->once())->method('getUserFolder')->with('alice')->willReturn($userFolder);
		$userFolder->expects($this->exactly(2))->method('getById')->willReturnMap([[100, [$file]], [200, [$groupFolderFile]]]);
		$userFolder->method('getRelativePath')->willReturnMap([
			['/alice/files/readme.md', '/readme.md'],
			['/alice/files/Team/readme.md', '/Team/readme.md'],
		]);
		$filesProvider = $this->createMock(ITeamResourceProvider::class);
		$filesProvider->method('getId')->willReturn('files');
		$deckProvider = $this->createMock(ITeamActivityResourceProvider::class);
		$deckProvider->method('getId')->willReturn('deck');
		$deckResource = new TeamResource($deckProvider, '200', 'Board', '/deck/200');
		$deckProvider->expects($this->once())
			->method('getActivityScopes')
			->with([$deckResource], 'alice')
			->willReturn([new ResourceActivityScope('deck_board', [200]), new ResourceActivityScope('deck_card', [300, 301])]);
		$groupFoldersProvider = $this->createMock(ITeamActivityResourceProvider::class);
		$groupFoldersProvider->method('getId')->willReturn('groupfolders');
		$groupFolderResource = new TeamResource($groupFoldersProvider, '42', 'Team folder', '/groupfolders/42');
		$groupFoldersProvider->expects($this->once())
			->method('getActivityScopes')
			->with([$groupFolderResource], 'alice')
			->willReturn([new ResourceActivityScope('files', [200])]);
		$unsupportedProvider = $this->createMock(ITeamResourceProvider::class);
		$unsupportedProvider->method('getId')->willReturn('calendar');
		$teamManager->expects($this->once())->method('getMembersOfTeam')->with('team-1', 'alice')->willReturn(['alice' => 'Alice']);
		$teamManager->expects($this->once())->method('getSharedWith')->with('team-1', 'alice')->willReturn([
			new TeamResource($filesProvider, '100', 'Readme', '/f/100'),
			$groupFolderResource,
			$deckResource,
			new TeamResource($unsupportedProvider, '400', 'Calendar', '/calendar/400'),
			new TeamResource($filesProvider, 'not-a-file-id', 'Invalid', '/f/invalid'),
		]);
		$query = $this->createMock(IQueryBuilder::class);
		$expression = $this->createMock(IExpressionBuilder::class);
		$result = $this->createMock(IResult::class);
		$connection->expects($this->once())->method('getQueryBuilder')->willReturn($query);
		$query->method('select')->willReturnSelf();
		$query->method('from')->willReturnSelf();
		$query->method('where')->willReturnSelf();
		$query->method('createNamedParameter')->willReturn(':team');
		$query->method('expr')->willReturn($expression);
		$expression->method('eq')->willReturn('unique_id = :team');
		$query->method('executeQuery')->willReturn($result);
		$result->method('fetchOne')->willReturn(42);

		$scope = (new TeamActivityResolver($teamManager, $connection, $rootFolder))->getScope('team-1', 'alice');

		self::assertNotNull($scope);
		self::assertSame(42, $scope->circleObjectId);
		self::assertSame([100, 200], $scope->fileObjectIds);
		self::assertSame([100 => '/readme.md', 200 => '/Team/readme.md'], $scope->filePaths);
		self::assertSame('files', $scope->resourceScopes[0]->getObjectType());
		self::assertSame([100, 200], $scope->resourceScopes[0]->getObjectIds());
		self::assertSame('deck_board', $scope->resourceScopes[1]->getObjectType());
		self::assertSame([200], $scope->resourceScopes[1]->getObjectIds());
		self::assertSame('deck_card', $scope->resourceScopes[2]->getObjectType());
		self::assertSame([300, 301], $scope->resourceScopes[2]->getObjectIds());
	}
}
