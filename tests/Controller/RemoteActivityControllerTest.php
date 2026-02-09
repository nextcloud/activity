<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests\Controller;

use OC\Files\Storage\Storage as InternalStorage;
use OC\Files\Storage\Wrapper\Wrapper;
use OCA\Activity\Controller\RemoteActivityController;
use OCA\Activity\Tests\TestCase;
use OCA\Files_Sharing\External\Storage as ExternalStorage;
use OCP\Activity\IEvent;
use OCP\Activity\IManager as IActivityManager;
use OCP\App\IAppManager;
use OCP\AppFramework\Http;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\DB\IResult;
use OCP\DB\QueryBuilder\IExpressionBuilder;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\Federation\ICloudId;
use OCP\Federation\ICloudIdManager;
use OCP\Files\Folder;
use OCP\Files\InvalidPathException;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserManager;
use PHPUnit\Framework\MockObject\MockObject;

class RemoteActivityControllerTest extends TestCase {
	private IRequest&MockObject $request;
	private IDBConnection&MockObject $db;
	private IUserManager&MockObject $userManager;
	private IAppManager&MockObject $appManager;
	private IRootFolder&MockObject $rootFolder;
	private IActivityManager&MockObject $activityManager;
	private ICloudIdManager&MockObject $cloudIdManager;
	private ITimeFactory&MockObject $timeFactory;

	private RemoteActivityController $controller;

	protected function setUp(): void {
		parent::setUp();

		$this->request = $this->createMock(IRequest::class);
		$this->db = $this->createMock(IDBConnection::class);
		$this->userManager = $this->createMock(IUserManager::class);
		$this->appManager = $this->createMock(IAppManager::class);
		$this->rootFolder = $this->createMock(IRootFolder::class);
		$this->activityManager = $this->createMock(IActivityManager::class);
		$this->cloudIdManager = $this->createMock(ICloudIdManager::class);
		$this->timeFactory = $this->createMock(ITimeFactory::class);
		$this->timeFactory->method('getTime')->willReturn(time());

		$this->controller = new RemoteActivityController(
			'activity',
			$this->request,
			$this->db,
			$this->userManager,
			$this->appManager,
			$this->rootFolder,
			$this->activityManager,
			$this->cloudIdManager,
			$this->timeFactory,
		);
	}

	private function validTimestamp(): string {
		return date(\DateTime::W3C, time());
	}

	private function setupDbQueryMock(array|false $shareResult): void {
		$expr = $this->createMock(IExpressionBuilder::class);
		$expr->method('eq')->willReturn('');

		$queryResult = $this->createMock(IResult::class);
		$queryResult->method('fetch')->willReturn($shareResult);
		$queryResult->method('closeCursor')->willReturn(true);

		$qb = $this->createMock(IQueryBuilder::class);
		$qb->method('select')->willReturnSelf();
		$qb->method('from')->willReturnSelf();
		$qb->method('where')->willReturnSelf();
		$qb->method('andWhere')->willReturnSelf();
		$qb->method('expr')->willReturn($expr);
		$qb->method('createNamedParameter')->willReturnArgument(0);
		$qb->method('executeQuery')->willReturn($queryResult);

		$this->db->method('getQueryBuilder')->willReturn($qb);
	}

	private function createUserMock(string $uid = 'alice', string $cloudId = 'alice@nextcloud1.local'): IUser&MockObject {
		$user = $this->createMock(IUser::class);
		$user->method('getUID')->willReturn($uid);
		$user->method('getCloudId')->willReturn($cloudId);
		return $user;
	}

	private function createCloudIdMock(string $remote = 'https://nextcloud2.local/', string $user = 'admin'): ICloudId&MockObject {
		$cloudId = $this->createMock(ICloudId::class);
		$cloudId->method('getRemote')->willReturn($remote);
		$cloudId->method('getUser')->willReturn($user);
		return $cloudId;
	}

	private function validShare(string $remote = 'https://nextcloud2.local/'): array {
		return [
			'mountpoint' => '/SharedFolder',
			'remote' => $remote,
		];
	}

	private function setupSuccessfulNode(string $path = '/SharedFolder/file.txt', int $fileId = 42): Node&MockObject {
		$storage = $this->createMock(ExternalStorage::class);
		$storage->method('instanceOfStorage')->willReturn(true);
		$storage->method('getToken')->willReturn('token');

		$node = $this->createMock(Node::class);
		$node->method('getId')->willReturn($fileId);
		$node->method('getStorage')->willReturn($storage);

		$userFolder = $this->createMock(Folder::class);
		$userFolder->method('get')->with($path)->willReturn($node);
		$this->rootFolder->method('getUserFolder')->willReturn($userFolder);

		return $node;
	}

	private function setupSuccessfulEvent(): IEvent&MockObject {
		$event = $this->createMock(IEvent::class);
		$event->method('setAffectedUser')->willReturnSelf();
		$event->method('setApp')->willReturnSelf();
		$event->method('setType')->willReturnSelf();
		$event->method('setAuthor')->willReturnSelf();
		$event->method('setObject')->willReturnSelf();
		$event->method('setSubject')->willReturnSelf();
		$event->method('setTimestamp')->willReturnSelf();

		$this->activityManager->method('generateEvent')->willReturn($event);
		return $event;
	}

	public function testFederationAppNotEnabled(): void {
		$this->appManager->method('isInstalled')
			->with('federatedfilesharing')
			->willReturn(false);
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_NOT_FOUND, $response->getStatus());
	}

	public static function dataInvalidTimestamps(): array {
		return [
			'not a date string' => ['not-a-date'],
			'empty string' => [''],
			'plain unix integer' => ['1234567890'],
			'too old – 11 minutes ago' => [date(\DateTime::W3C, time() - 660)],
			'too far in future – 11 min' => [date(\DateTime::W3C, time() + 660)],
		];
	}

	/**
	 * @dataProvider dataInvalidTimestamps
	 */
	public function testInvalidTimestamp(string $updated): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $updated,
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testTimestampJustInsideWindow(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$user = $this->createUserMock();
		$this->userManager->method('get')->willReturn($user);
		$this->cloudIdManager->method('resolveCloudId')
			->willThrowException(new \InvalidArgumentException('invalid'));

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', date(\DateTime::W3C, time() - 299),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public static function dataInvalidTo(): array {
		return [
			'missing type' => [['name' => 'alice']],
			'missing name' => [['type' => 'Person']],
			'wrong type' => [['type' => 'Organization', 'name' => 'alice']],
			'empty array' => [[]],
		];
	}

	/**
	 * @dataProvider dataInvalidTo
	 */
	public function testInvalidTo(array $to): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token', $to,
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testUserNotFound(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->with('alice')->willReturn(null);
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_NOT_FOUND, $response->getStatus());
	}

	public static function dataInvalidActor(): array {
		return [
			'missing type' => [['name' => 'admin@nextcloud2.local']],
			'missing name' => [['type' => 'Person']],
			'wrong type' => [['type' => 'Organization', 'name' => 'admin@nextcloud2.local']],
			'empty array' => [[]],
		];
	}

	/**
	 * @dataProvider dataInvalidActor
	 */
	public function testInvalidActor(array $actor): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			$actor,
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testActorIsSameAsUser(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')
			->willReturn($this->createUserMock('alice', 'alice@nextcloud1.local'));
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'alice@nextcloud1.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public static function dataPathTraversal(): array {
		return [
			'double-dot at start' => ['../secret.txt'],
			'double-dot in the middle' => ['foo/../bar'],
			'double-dot at end' => ['foo/..'],
			'bare double-dot' => ['..'],
			'multiple traversal segments' => ['../../etc/passwd'],
		];
	}

	/**
	 * @dataProvider dataPathTraversal
	 */
	public function testPathTraversal(string $objectName): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => $objectName],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testPathWithThreeDotsIsAllowed(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')
			->willThrowException(new \InvalidArgumentException());

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '.../file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testInvalidCloudId(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')
			->willThrowException(new \InvalidArgumentException('not a cloud id'));
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'not-valid'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testUnknownActivityType(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->db->expects($this->never())->method('getQueryBuilder');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'UnknownType', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testShareNotFound(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock(false);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_NOT_FOUND, $response->getStatus());
	}

	public function testShareWithTemporaryMountpoint(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock([
			'mountpoint' => '{{TemporaryMountPointName#/image.png}}',
			'remote' => 'https://nextcloud2.local/',
		]);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_NOT_FOUND, $response->getStatus());
	}

	public function testActorServerMismatch(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')
			->willReturn($this->createCloudIdMock('https://nextcloud2.local/', 'admin'));
		$this->setupDbQueryMock([
			'mountpoint' => '/SharedFolder',
			'remote' => 'https://nextcloud3.local/',
		]);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_FORBIDDEN, $response->getStatus());
	}

	public static function dataActorServerNormalization(): array {
		return [
			'exact match' => ['https://nextcloud2.local/', 'https://nextcloud2.local/', true],
			'actor missing trailing slash' => ['https://nextcloud2.local',  'https://nextcloud2.local/', true],
			'db remote missing trailing slash' => ['https://nextcloud2.local/', 'https://nextcloud2.local',  true],
			'actor uses http, db uses https' => ['http://nextcloud2.local/',  'https://nextcloud2.local/', true],
			'completely different server' => ['https://evil.com/',         'https://nextcloud2.local/', false],
			'subdomain mismatch' => ['https://sub.nextcloud2.local/', 'https://nextcloud2.local/', false],
		];
	}

	/**
	 * @dataProvider dataActorServerNormalization
	 */
	public function testActorServerNormalization(string $actorServer, string $shareRemote, bool $shouldMatch): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')
			->willReturn($this->createCloudIdMock($actorServer, 'admin'));
		$this->setupDbQueryMock([
			'mountpoint' => '/SharedFolder',
			'remote' => $shareRemote,
		]);

		if ($shouldMatch) {
			$userFolder = $this->createMock(Folder::class);
			$userFolder->method('get')->willThrowException(new NotFoundException());
			$this->rootFolder->method('getUserFolder')->willReturn($userFolder);
		}

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$expected = $shouldMatch ? Http::STATUS_NOT_FOUND : Http::STATUS_FORBIDDEN;
		$this->assertSame($expected, $response->getStatus());
	}

	public static function dataInvalidMoveParams(): array {
		return [
			'missing target' => [[], ['type' => 'Document', 'name' => '/old.png']],
			'target missing type' => [['name' => '/new.png'], ['type' => 'Document', 'name' => '/old.png']],
			'target wrong type' => [['type' => 'Wrong', 'name' => '/new.png'], ['type' => 'Document', 'name' => '/old.png']],
			'missing origin' => [['type' => 'Document', 'name' => '/new.png'], []],
			'origin missing type' => [['type' => 'Document', 'name' => '/new.png'], ['name' => '/old.png']],
			'origin wrong type' => [['type' => 'Document', 'name' => '/new.png'], ['type' => 'Wrong', 'name' => '/old.png']],
		];
	}

	/**
	 * @dataProvider dataInvalidMoveParams
	 */
	public function testInvalidMoveParams(array $target, array $origin): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Move', $this->validTimestamp(),
			['type' => 'Document', 'name' => ''],
			$target, $origin,
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public static function dataInvalidObjectParams(): array {
		return [
			'missing type' => [['name' => '/file.txt']],
			'missing name' => [['type' => 'Document']],
			'wrong type' => [['type' => 'Wrong', 'name' => '/file.txt']],
		];
	}

	/**
	 * @dataProvider dataInvalidObjectParams
	 */
	public function testInvalidObjectParams(array $object): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			$object,
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testNodeNotFoundException(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$userFolder = $this->createMock(Folder::class);
		$userFolder->method('get')->willThrowException(new NotFoundException());
		$this->rootFolder->method('getUserFolder')->willReturn($userFolder);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_NOT_FOUND, $response->getStatus());
	}

	public function testNodeInvalidPathException(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$userFolder = $this->createMock(Folder::class);
		$userFolder->method('get')->willThrowException(new InvalidPathException());
		$this->rootFolder->method('getUserFolder')->willReturn($userFolder);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_NOT_FOUND, $response->getStatus());
	}

	public function testPublishInvalidArgumentException(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());
		$this->setupSuccessfulNode();
		$this->setupSuccessfulEvent();

		$this->activityManager->expects($this->once())
			->method('publish')
			->willThrowException(new \InvalidArgumentException());

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public function testPublishBadMethodCallException(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());
		$this->setupSuccessfulNode();
		$this->setupSuccessfulEvent();

		$this->activityManager->expects($this->once())
			->method('publish')
			->willThrowException(new \BadMethodCallException());

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_BAD_REQUEST, $response->getStatus());
	}

	public static function dataSuccessfulNonMoveTypes(): array {
		return [
			'Create' => ['Create'],
			'Update' => ['Update'],
			'Delete' => ['Delete'],
		];
	}

	/**
	 * @dataProvider dataSuccessfulNonMoveTypes
	 */
	public function testSuccessfulNonMoveActivity(string $type): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$user = $this->createUserMock();
		$this->userManager->method('get')->willReturn($user);
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());
		$this->setupSuccessfulNode();
		$this->setupSuccessfulEvent();

		$this->activityManager->expects($this->once())->method('publish');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			$type, $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_OK, $response->getStatus());
	}

	public function testSuccessfulMoveRename(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$targetName = '/new_name.png';
		$originName = '/old_name.png';
		$path = '/SharedFolder' . $targetName;

		$this->setupSuccessfulNode($path, 55);
		$this->setupSuccessfulEvent();
		$this->activityManager->expects($this->once())->method('publish');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Move', $this->validTimestamp(),
			['type' => 'Document', 'name' => ''],
			['type' => 'Document', 'name' => $targetName],
			['type' => 'Document', 'name' => $originName],
		);

		$this->assertSame(Http::STATUS_OK, $response->getStatus());
	}

	public function testStorageNotExternalStorage(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$storage = $this->createMock(InternalStorage::class);
		$storage->method('instanceOfStorage')->willReturn(false);

		$node = $this->createMock(Node::class);
		$node->method('getStorage')->willReturn($storage);

		$userFolder = $this->createMock(Folder::class);
		$userFolder->method('get')->willReturn($node);
		$this->rootFolder->method('getUserFolder')->willReturn($userFolder);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_FORBIDDEN, $response->getStatus());
	}

	public function testStorageWrapperResolvesToNonExternalStorage(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		// The outer storage reports instanceOfStorage true (passes first check),
		// but unwrapping the Wrapper chain reveals a non-ExternalStorage backend.
		$innerStorage = $this->createMock(InternalStorage::class);
		$wrapperStorage = $this->getMockBuilder(Wrapper::class)
			->disableOriginalConstructor()
			->getMock();
		$wrapperStorage->method('instanceOfStorage')->willReturn(true);
		$wrapperStorage->method('getWrapperStorage')->willReturn($innerStorage);

		$node = $this->createMock(Node::class);
		$node->method('getStorage')->willReturn($wrapperStorage);

		$userFolder = $this->createMock(Folder::class);
		$userFolder->method('get')->willReturn($node);
		$this->rootFolder->method('getUserFolder')->willReturn($userFolder);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_FORBIDDEN, $response->getStatus());
	}

	public function testExternalStorageTokenMismatch(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$storage = $this->createMock(ExternalStorage::class);
		$storage->method('instanceOfStorage')->willReturn(true);
		$storage->method('getToken')->willReturn('wrong-token');

		$node = $this->createMock(Node::class);
		$node->method('getStorage')->willReturn($storage);

		$userFolder = $this->createMock(Folder::class);
		$userFolder->method('get')->willReturn($node);
		$this->rootFolder->method('getUserFolder')->willReturn($userFolder);

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Update', $this->validTimestamp(),
			['type' => 'Document', 'name' => '/file.txt'],
		);

		$this->assertSame(Http::STATUS_FORBIDDEN, $response->getStatus());
	}

	public function testSuccessfulMoveToAnotherFolder(): void {
		$this->appManager->method('isInstalled')->willReturn(true);
		$this->userManager->method('get')->willReturn($this->createUserMock());
		$this->cloudIdManager->method('resolveCloudId')->willReturn($this->createCloudIdMock());
		$this->setupDbQueryMock($this->validShare());

		$targetName = '/subfolder/image.png';
		$originName = '/image.png';
		$path = '/SharedFolder' . $targetName;

		$parent = $this->createMock(Folder::class);
		$parent->method('getId')->willReturn(77);

		$storage = $this->createMock(ExternalStorage::class);
		$storage->method('instanceOfStorage')->willReturn(true);
		$storage->method('getToken')->willReturn('token');

		$node = $this->createMock(Node::class);
		$node->method('getId')->willReturn(99);
		$node->method('getParent')->willReturn($parent);
		$node->method('getStorage')->willReturn($storage);

		$userFolder = $this->createMock(Folder::class);
		$userFolder->method('get')->with($path)->willReturn($node);
		$this->rootFolder->method('getUserFolder')->willReturn($userFolder);

		$this->setupSuccessfulEvent();
		$this->activityManager->expects($this->once())->method('publish');

		$response = $this->controller->receiveActivity(
			'token',
			['type' => 'Person', 'name' => 'alice'],
			['type' => 'Person', 'name' => 'admin@nextcloud2.local'],
			'Move', $this->validTimestamp(),
			['type' => 'Document', 'name' => ''],
			['type' => 'Document', 'name' => $targetName],
			['type' => 'Document', 'name' => $originName],
		);

		$this->assertSame(Http::STATUS_OK, $response->getStatus());
	}

}
