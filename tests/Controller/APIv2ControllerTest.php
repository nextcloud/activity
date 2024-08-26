<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity\Tests\Controller;

use OCA\Activity\Controller\APIv2Controller;
use OCA\Activity\Data;
use OCA\Activity\Exception\InvalidFilterException;
use OCA\Activity\GroupHelper;
use OCA\Activity\Tests\TestCase;
use OCA\Activity\UserSettings;
use OCA\Activity\ViewInfoCache;
use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\Files\IMimeTypeDetector;
use OCP\IL10N;
use OCP\IPreview;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserSession;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class APIv2Test
 *
 * @group DB
 * @package OCA\Activity\Tests\Controller
 */
class APIv2ControllerTest extends TestCase {
	/** @var IRequest|MockObject */
	protected $request;

	/** @var IManager|MockObject */
	protected $activityManager;

	/** @var Data|MockObject */
	protected $data;

	/** @var GroupHelper|MockObject */
	protected $helper;

	/** @var UserSettings|MockObject */
	protected $userSettings;

	/** @var IPreview|MockObject */
	protected $preview;

	/** @var IURLGenerator|MockObject */
	protected $urlGenerator;

	/** @var IUserSession|MockObject */
	protected $userSession;

	/** @var IMimeTypeDetector|MockObject */
	protected $mimeTypeDetector;

	/** @var ViewInfoCache|MockObject */
	protected $infoCache;

	/** @var IL10N */
	protected $l10n;

	/** @var APIv2Controller */
	protected $controller;

	protected function setUp(): void {
		parent::setUp();

		$this->activityManager = $this->createMock(IManager::class);
		$this->data = $this->createMock(Data::class);
		$this->helper = $this->createMock(GroupHelper::class);
		$this->userSettings = $this->createMock(UserSettings::class);
		$this->preview = $this->createMock(IPreview::class);
		$this->urlGenerator = $this->createMock(IURLGenerator::class);
		$this->userSession = $this->createMock(IUserSession::class);
		$this->mimeTypeDetector = $this->createMock(IMimeTypeDetector::class);
		$this->infoCache = $this->createMock(ViewInfoCache::class);
		$this->request = $this->createMock(IRequest::class);

		$this->controller = $this->getController();
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	protected function getController(array $methods = []): APIv2Controller {
		if (empty($methods)) {
			return new APIv2Controller(
				'activity',
				$this->request,
				$this->activityManager,
				$this->data,
				$this->helper,
				$this->userSettings,
				$this->urlGenerator,
				$this->userSession,
				$this->preview,
				$this->mimeTypeDetector,
				$this->infoCache
			);
		}

		return $this->getMockBuilder(APIv2Controller::class)
			->setConstructorArgs([
				'activity',
				$this->request,
				$this->activityManager,
				$this->data,
				$this->helper,
				$this->userSettings,
				$this->urlGenerator,
				$this->userSession,
				$this->preview,
				$this->mimeTypeDetector,
				$this->infoCache,
			])
			->onlyMethods($methods)
			->getMock();
	}

	public function dataValidateParametersFilter(): array {
		return [
			['valid1', 'valid1'],
			['all', 'all'],
		];
	}

	/**
	 * @dataProvider dataValidateParametersFilter
	 * @param string $param
	 * @param string $filter
	 */
	public function testValidateParametersFilter(string $param, string $filter): void {
		$this->data->expects($this->once())
			->method('validateFilter')
			->with($param)
			->willReturn($filter);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($this->createMock(IUser::class));

		self::invokePrivate($this->controller, 'validateParameters', [$param, 0, 0, false, '', 0, 'desc']);
		$this->assertSame($filter, self::invokePrivate($this->controller, 'filter'));
	}

	public function dataValidateParametersFilterInvalid(): array {
		return [
			['invalid1'],
			['invalid2'],
		];
	}

	/**
	 * @dataProvider dataValidateParametersFilterInvalid
	 * @param string $filter
	 */
	public function testValidateParametersFilterInvalid(string $filter): void {
		$this->data->expects($this->once())
			->method('validateFilter')
			->with($filter)
			->willReturn('all');
		$this->userSession->expects($this->never())
			->method('getUser');

		$this->expectException(InvalidFilterException::class);
		self::invokePrivate($this->controller, 'validateParameters', [$filter, 0, 0, false, '', 0, 'desc']);
		$this->assertSame($filter, self::invokePrivate($this->controller, 'filter'));
	}

	public function dataValidateParametersObject(): array {
		return [
			['type', 42, 'type', 42],
			['type', '42', 'type', 42],
			[null, '42', '', 0],
			['type', null, '', 0],
		];
	}

	/**
	 * @dataProvider dataValidateParametersObject
	 * @param mixed $type
	 * @param mixed $id
	 * @param string $expectedType
	 * @param int $expectedId
	 */
	public function testValidateParametersObject(?string $type, $id, string $expectedType, int $expectedId): void {
		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($this->createMock(IUser::class));

		self::invokePrivate($this->controller, 'validateParameters', ['all', 0, 0, false, $type, $id, 'desc']);
		$this->assertSame($expectedType, self::invokePrivate($this->controller, 'objectType'));
		$this->assertSame($expectedId, self::invokePrivate($this->controller, 'objectId'));
	}

	public function dataValidateParameters(): array {
		return [
			['since', 0, 'since', 0],
			['since', 20, 'since', 20],
			['since', '25', 'since', 25],
			['limit', 0, 'limit', 0],
			['limit', 20, 'limit', 20],
			['limit', '25', 'limit', 25],
			['sort', '', 'sort', 'desc'],
			['sort', 'asc', 'sort', 'asc'],
			['sort', 'desc', 'sort', 'desc'],
			['previews', false, 'loadPreviews', false],
			['previews', true, 'loadPreviews', true],
		];
	}

	/**
	 * @dataProvider dataValidateParameters
	 * @param string $param
	 * @param string $value
	 * @param mixed $memberName
	 * @param mixed $expectedValue
	 */
	public function testValidateParameters(string $param, $value, string $memberName, $expectedValue): void {
		$params = ['all', 0, 0, false, '', 0, 'desc'];
		switch ($param) {
			case 'since':
				$params[1] = $value;
				break;
			case 'limit':
				$params[2] = $value;
				break;
			case 'previews':
				$params[3] = $value;
				break;
			case 'sort':
				$params[6] = $value;
				break;
		}

		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($this->createMock(IUser::class));

		self::invokePrivate($this->controller, 'validateParameters', $params);
		$this->assertSame($expectedValue, self::invokePrivate($this->controller, $memberName));
	}

	public function dataValidateParametersUser(): array {
		return [
			['uid1'],
			['uid2'],
		];
	}

	/**
	 * @dataProvider dataValidateParametersUser
	 * @param string $uid
	 */
	public function testValidateParametersUser(string $uid): void {
		$user = $this->createMock(IUser::class);
		$user->expects($this->once())
			->method('getUID')
			->willReturn($uid);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($user);

		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);

		self::invokePrivate($this->controller, 'validateParameters', ['all', 0, 0, false, '', 0, 'desc']);
		$this->assertSame($uid, self::invokePrivate($this->controller, 'user'));
	}

	public function testValidateParametersUserInvalid(): void {
		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn(null);

		$this->expectException(\OutOfBoundsException::class);
		self::invokePrivate($this->controller, 'validateParameters', ['all', 0, 0, false, '', 0, 'desc']);
		$this->assertNull(self::invokePrivate($this->controller, 'user'));
	}

	public function dataParameters(): array {
		return [
			['all', 0, 0, false, '', 0, 'desc'],
			['filter', 1, 25, true, 'files', 42, 'asc'],
		];
	}

	/**
	 * @dataProvider dataParameters
	 *
	 * @param string $filter
	 * @param int $since
	 * @param int $limit
	 * @param bool $previews
	 * @param string $filterObjectType
	 * @param int $filterObjectId
	 * @param string $sort
	 */
	public function testGetDefault(string $filter, int $since, int $limit, bool $previews, string $filterObjectType, int $filterObjectId, string $sort): void {
		$controller = $this->getController([
			'get'
		]);

		$controller->expects($this->once())
			->method('get')
			->with('all', $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);

		$controller->getDefault($since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);
	}

	/**
	 * @dataProvider dataParameters
	 *
	 * @param string $filter
	 * @param int $since
	 * @param int $limit
	 * @param bool $previews
	 * @param string $filterObjectType
	 * @param int $filterObjectId
	 * @param string $sort
	 */
	public function testGetFilter(string $filter, int $since, int $limit, bool $previews, string $filterObjectType, int $filterObjectId, string $sort): void {
		$controller = $this->getController([
			'get'
		]);

		$controller->expects($this->once())
			->method('get')
			->with($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);

		$controller->getFilter($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);
	}

	public function dataGetInvalid(): array {
		return [
			[new InvalidFilterException(), null, Http::STATUS_NOT_FOUND],
			[new \OutOfBoundsException(), null, Http::STATUS_FORBIDDEN],
			[null, new \OutOfBoundsException(), Http::STATUS_FORBIDDEN],
			[null, new \BadMethodCallException(), Http::STATUS_NO_CONTENT],
			[null, false, Http::STATUS_NOT_MODIFIED],
		];
	}

	/**
	 * @dataProvider dataGetInvalid
	 * @param \Exception $readParamsThrows
	 * @param bool|\Exception $dataGetThrows
	 * @param int $expected
	 */
	public function testGetInvalid(?\Exception $readParamsThrows, $dataGetThrows, int $expected): void {
		$controller = $this->getController(['validateParameters', 'generateHeaders']);
		$controller->expects($this->any())
			->method('generateHeaders')
			->willReturnArgument(0);

		if ($readParamsThrows instanceof \Exception) {
			$controller->expects($this->once())
				->method('validateParameters')
				->willThrowException($readParamsThrows);
		} else {
			$controller->expects($this->once())
				->method('validateParameters');
		}
		if ($dataGetThrows instanceof \Exception) {
			$this->data->expects($this->once())
				->method('get')
				->willThrowException($dataGetThrows);
		} elseif ($dataGetThrows === false) {
			$this->data->expects($this->once())
				->method('get')
				->willReturn([
					'data' => [],
					'headers' => ['X-Activity-First-Known' => 23],
					'has_more' => false,
				]);
		} else {
			$controller->expects($this->never())
				->method('generateHeaders');
		}

		/** @var DataResponse $result */
		$result = self::invokePrivate($controller, 'get', ['all', 0, 50, false, '', 0, 'desc']);

		$this->assertInstanceOf(DataResponse::class, $result);
		$this->assertSame($expected, $result->getStatus());
	}

	public function testListFilters(): void {
		$filters = [
			$this->createFilterMock(10, 'id1', 'Filter 3'),
			$this->createFilterMock(10, 'abc', 'Filter 2'),
			$this->createFilterMock(5, 'id3', 'Filter 1'),
		];

		$this->activityManager->expects($this->once())
			->method('getFilters')
			->willReturn($filters);

		$controller = $this->getController();
		$response = $controller->listFilters();

		$this->assertInstanceOf(DataResponse::class, $response);
		$this->assertSame(Http::STATUS_OK, $response->getStatus());
		$this->assertSame([
			['id' => 'id3', 'name' => 'Filter 1', 'icon' => 'id35', 'priority' => 5],
			['id' => 'abc', 'name' => 'Filter 2', 'icon' => 'abc10', 'priority' => 10],
			['id' => 'id1', 'name' => 'Filter 3', 'icon' => 'id110', 'priority' => 10],
		], $response->getData());
	}

	protected function createFilterMock(int $priority, string $id, string $name): IFilter {
		$filter = $this->createMock(IFilter::class);
		$filter->expects($this->any())
			->method('getPriority')
			->willReturn($priority);
		$filter->expects($this->any())
			->method('getIdentifier')
			->willReturn($id);
		$filter->expects($this->any())
			->method('getName')
			->willReturn($name);
		$filter->expects($this->any())
			->method('getIcon')
			->willReturn($id . $priority);

		return $filter;
	}

	public function dataGet(): array {
		return [
			[123456789, 'files', 42, [], false, 0, ['object_type' => 'files', 'object_id' => 42, 'datetime' => date(\DateTime::ATOM, 123456789)]],
			[12345678, 'calendar', 23, [], true, 0, ['object_type' => 'calendar', 'object_id' => 23, 'datetime' => date(\DateTime::ATOM, 12345678), 'previews' => []]],
			[
				12345678, 'files', 23, ['objects' => [], 'affecteduser' => 'user1', 'object_name' => 'file.txt'],
				true, 1,
				['object_type' => 'files', 'object_id' => 23, 'objects' => [], 'object_name' => 'file.txt', 'datetime' => date(\DateTime::ATOM, 12345678), 'previews' => [['preview']]]
			],
			[
				12345678, 'files', 23, ['objects' => [12 => '12.png', 23 => '23.txt', 0 => '0.txt', 123 => ''], 'affecteduser' => 'user1'],
				true, 2,
				['object_type' => 'files', 'object_id' => 23, 'objects' => [12 => '12.png', 23 => '23.txt', 0 => '0.txt', 123 => ''], 'datetime' => date(\DateTime::ATOM, 12345678), 'previews' => [['preview'], ['preview']]]
			],
		];
	}

	/**
	 * @dataProvider dataGet
	 * @param int $time
	 * @param string $objectType
	 * @param int $objectId
	 * @param array $additionalArgs
	 * @param bool $loadPreviews
	 * @param int $numGetPreviewCalls
	 * @param array $expected
	 */
	public function testGet(int $time, string $objectType, int $objectId, array $additionalArgs, bool $loadPreviews, int $numGetPreviewCalls, array $expected): void {
		$controller = $this->getController(['validateParameters', 'generateHeaders', 'getPreview']);
		$controller->expects($this->any())
			->method('generateHeaders')
			->willReturnArgument(0);

		$controller->expects($this->once())
			->method('validateParameters');

		$controller->expects($this->exactly($numGetPreviewCalls))
			->method('getPreview')
			->willReturn(['preview']);

		self::invokePrivate($controller, 'loadPreviews', [$loadPreviews]);

		$this->data->expects($this->once())
			->method('get')
			->willReturn([
				'data' => [
					array_merge(['timestamp' => $time, 'object_type' => $objectType, 'object_id' => $objectId], $additionalArgs),
				],
				'headers' => ['X-Activity-First-Known' => 23, 'ETag' => md5('foobar')],
				'has_more' => false,
			]);

		/** @var DataResponse $result */
		$result = self::invokePrivate($controller, 'get', ['all', 0, 50, false, $objectType, $objectId, 'desc']);

		$this->assertInstanceOf(DataResponse::class, $result);
		$this->assertSame(Http::STATUS_OK, $result->getStatus());
		$this->assertSame([
			$expected,
		], $result->getData());
	}

	public function dataGetNotModified(): array {
		return [
			[[], null],
			[[[]], md5('foobar')],
		];
	}

	/**
	 * @dataProvider dataGetNotModified
	 * @param array $data
	 * @param string|null $etag
	 */
	public function testGetNotModified(array $data, ?string $etag): void {
		$controller = $this->getController(['validateParameters', 'generateHeaders']);
		$controller->expects($this->any())
			->method('generateHeaders')
			->willReturnArgument(0);

		$controller->expects($this->once())
			->method('validateParameters');

		$this->request->expects($etag !== null ? $this->once() : $this->never())
			->method('getHeader')
			->willReturn((string)$etag);

		$this->data->expects($this->once())
			->method('get')
			->willReturn([
				'data' => $data,
				'headers' => ['X-Activity-First-Known' => 23, 'ETag' => md5('foobar')],
				'has_more' => false,
			]);

		/** @var DataResponse $result */
		$result = self::invokePrivate($controller, 'get', ['all', 0, 50, false, '', 0, 'desc']);

		$this->assertInstanceOf(DataResponse::class, $result);
		$this->assertSame(Http::STATUS_NOT_MODIFIED, $result->getStatus());
		$this->assertSame([], $result->getData());
	}

	public function dataGenerateHeaders(): array {
		return [
			['asc', 25, '', 0, null, ['X-Activity-Last-Given' => 23], false, [], ['X-Activity-Last-Given' => 23, 'ETag' => 'd751713988987e9331980363e24189ce']],
			['asc', 25, '', 0, null, ['X-Activity-Last-Given' => 23], false, [['activity_id' => 23]], ['X-Activity-Last-Given' => 23, 'ETag' => 'b37e127599eef3cfeceac50e34cd5037']],
			['asc', 25, '', 0, null, ['X-Activity-Last-Given' => 23], false, [['activity_id' => 23], ['activity_id' => 42]], ['X-Activity-Last-Given' => 23, 'ETag' => 'f39a1f6e9647bf7ac763f3728830d64b']],
			['asc', 25, '', 0, null, ['X-Activity-Last-Given' => 23], true, [], ['X-Activity-Last-Given' => 23, 'Link' => '<://?since=23&limit=25&sort=asc>; rel="next"', 'ETag' => 'd751713988987e9331980363e24189ce']],
			['asc', 25, 'ob', 10, null, ['X-Activity-Last-Given' => 23], true, [], ['X-Activity-Last-Given' => 23, 'Link' => '<://?since=23&limit=25&sort=asc&object_type=ob&object_id=10>; rel="next"', 'ETag' => 'd751713988987e9331980363e24189ce']],
			['asc', 25, '', 0, 'json', ['X-Activity-Last-Given' => 23], true, [], ['X-Activity-Last-Given' => 23, 'Link' => '<://?since=23&limit=25&sort=asc&format=json>; rel="next"', 'ETag' => 'd751713988987e9331980363e24189ce']],
		];
	}

	/**
	 * @dataProvider dataGenerateHeaders
	 *
	 * @param string $sort
	 * @param int $limit
	 * @param string $objectType
	 * @param int $objectId
	 * @param string $format
	 * @param array $headersIn
	 * @param bool $hasMoreActivities
	 * @param array $data
	 * @param array $expected
	 */
	public function testGenerateHeaders(string $sort, int $limit, string $objectType, int $objectId, ?string $format, array $headersIn, bool $hasMoreActivities, array $data, array $expected): void {
		self::invokePrivate($this->controller, 'sort', [$sort]);
		self::invokePrivate($this->controller, 'limit', [$limit]);
		self::invokePrivate($this->controller, 'objectType', [$objectType]);
		self::invokePrivate($this->controller, 'objectId', [$objectId]);
		$this->request->expects($this->any())
			->method('getParam')
			->with('format')
			->willReturn($format);

		$headers = self::invokePrivate($this->controller, 'generateHeaders', [$headersIn, $hasMoreActivities, $data]);
		$this->assertEquals($expected, $headers);
	}

	public function dataGetPreviewInvalidPaths(): array {
		return [
			['author', 42, '/path', null, null],
			['author', 42, '/path', '', null],
			['author', 42, '/path', '/currentPath', false],
		];
	}

	/**
	 * @dataProvider dataGetPreviewInvalidPaths
	 *
	 * @param string $author
	 * @param int $fileId
	 * @param string $path
	 * @param string $returnedPath
	 * @param null|bool $exists
	 */
	public function testGetPreviewInvalidPaths(string $author, int $fileId, string $path, ?string $returnedPath, ?bool $exists): void {
		$this->infoCache->expects($this->once())
			->method('getInfoById')
			->with($author, $fileId, $path)
			->willReturn([
				'path' => $returnedPath,
				'exists' => $exists,
				'is_dir' => false,
				'view' => '',
			]);

		$controller = $this->getController([
			'getPreviewFromPath'
		]);
		$controller->expects($this->any())
			->method('getPreviewFromPath')
			->with($fileId, $path)
			->willReturn(['getPreviewFromPath']);

		$this->assertSame(['getPreviewFromPath'], self::invokePrivate($controller, 'getPreview', [$author, $fileId, $path]));
	}

	public function dataGetPreview(): array {
		return [
			['author', 42, '/path', '/currentPath', true, true, false, '/preview/dir', true, 'dir'],
			['author', 42, '/file.txt', '/currentFile.txt', false, true, false, '/preview/mpeg', true, 'audio/mp3'],
			['author', 42, '/file.txt', '/currentFile.txt', false, true, true, 'core.Preview.getPreviewByFileId#42', false, 'text/plain'],
			['author', 42, '/file.txt', '/currentFile.txt', false, false, true, 'source::getPreviewFromPath', true, 'text/plain'],
		];
	}

	/**
	 * @dataProvider dataGetPreview
	 *
	 * @param string $author
	 * @param int $fileId
	 * @param string $path
	 * @param string $returnedPath
	 * @param bool $isDir
	 * @param bool $validFileInfo
	 * @param bool $isMimeSup
	 * @param string $source
	 * @param bool $isMimeTypeIcon
	 * @param string $mimeType
	 */
	public function testGetPreview(string $author, int $fileId, string $path, string $returnedPath, bool $isDir, bool $validFileInfo, bool $isMimeSup, string $source, bool $isMimeTypeIcon, string $mimeType): void {
		$controller = $this->getController([
			'getPreviewFromPath',
			'getPreviewPathFromMimeType',
		]);

		$node = $this->createMock(\OCP\Files\File::class);
		$this->urlGenerator->expects($this->any())
			->method('linkToRouteAbsolute')
			->willReturnCallback(function ($url, $params) {
				return $url . '#' . ($params['fileid'] ?? $params['fileId']);
			});

		if ($isDir) {
			$controller->expects($this->once())
				->method('getPreviewPathFromMimeType')
				->with('dir')
				->willReturn('/preview/dir');
		} elseif ($validFileInfo) {
			$this->preview->expects($this->once())
				->method('isAvailable')
				->with($node)
				->willReturn($isMimeSup);

			$node->expects($this->atLeastOnce())
				->method('getPath')
				->willReturn($path);
			if (!$isMimeSup) {
				$node->expects($this->atLeastOnce())
					->method('getMimetype')
					->willReturn('audio/mp3');

				$controller->expects($this->once())
					->method('getPreviewPathFromMimeType')
					->with('audio/mp3')
					->willReturn('/preview/mpeg');
			} else {
				$node->expects($this->atLeastOnce())
					->method('getMimetype')
					->willReturn('text/plain');
			}
		} else {
			$node = null;

			$controller->expects($this->once())
				->method('getPreviewFromPath')
				->with($fileId, $path, $this->anything())
				->willReturn([
					'link' => 'files.viewcontroller.showFile#' . $fileId,
					'source' => 'source::getPreviewFromPath',
					'mimeType' => $mimeType,
					'isMimeTypeIcon' => $isMimeTypeIcon,
					'fileId' => $fileId,
					'view' => 'files',
					'filename' => basename($path),
				]);
		}

		$this->infoCache->expects($this->once())
			->method('getInfoById')
			->with($author, $fileId, $path)
			->willReturn([
				'path' => $returnedPath,
				'exists' => true,
				'is_dir' => $isDir,
				'view' => '',
				'node' => $node,
			]);

		$this->assertSame(array_merge([
			'link' => 'files.viewcontroller.showFile#' . $fileId,
			'source' => $source,
			'mimeType' => $mimeType,
			'isMimeTypeIcon' => $isMimeTypeIcon,
			'fileId' => $fileId,
			'view' => 'files',
			'filename' => basename($path),
		], $validFileInfo && !$isDir ? ['filePath' => $path ] : []),
			self::invokePrivate($controller, 'getPreview', [$author, $fileId, $path]));
	}

	public function dataGetPreviewFromPath(): array {
		return [
			[23, 'dir', 'dir', true, ''],
			[42, 'test.txt', 'text/plain', false, 'trashbin'],
			[128, 'test.mp3', 'audio/mpeg', false, ''],
		];
	}

	/**
	 * @dataProvider dataGetPreviewFromPath
	 * @param int $fileId
	 * @param string $filePath
	 * @param string $mimeType
	 * @param bool $isDir
	 * @param string $view
	 */
	public function testGetPreviewFromPath(int $fileId, string $filePath, string $mimeType, bool $isDir, string $view): void {
		$controller = $this->getController([
			'getPreviewPathFromMimeType',
		]);

		$controller->expects($this->once())
			->method('getPreviewPathFromMimeType')
			->with($mimeType)
			->willReturn('mime-type-icon');
		$this->urlGenerator->expects($this->any())
			->method('linkToRouteAbsolute')
			->willReturnCallback(function ($url, $params) {
				return $url . '#' . ($params['fileid'] ?? $params['fileId']);
			});
		$this->mimeTypeDetector->expects($isDir ? $this->never() : $this->once())
			->method('detectPath')
			->willReturn($mimeType);

		$this->assertSame(
			[
				'link' => 'files.viewcontroller.showFile#' . $fileId,
				'source' => 'mime-type-icon',
				'mimeType' => $mimeType,
				'isMimeTypeIcon' => true,
				'fileId' => $fileId,
				'view' => $view ?: 'files',
				'filename' => basename($filePath),
			],
			self::invokePrivate($controller, 'getPreviewFromPath', [$fileId, $filePath, ['path' => $filePath, 'is_dir' => $isDir, 'view' => $view]])
		);
	}

	public function dataGetPreviewPathFromMimeType(): array {
		return [
			['dir', 'folder.png', 'absolute-folder.svg'],
			['text/plain', 'text.svg', 'absolute-text.svg'],
			['text/plain', 'text.jpg', 'absolute-text.jpg'],
		];
	}

	/**
	 * @dataProvider dataGetPreviewPathFromMimeType
	 * @param string $mimeType
	 * @param string $icon
	 * @param string $expected
	 */
	public function testGetPreviewPathFromMimeType(string $mimeType, string $icon, string $expected): void {
		$this->mimeTypeDetector->expects($this->once())
			->method('mimeTypeIcon')
			->with($mimeType)
			->willReturn($icon);

		$this->urlGenerator->expects($this->once())
			->method('getAbsoluteURL')
			->willReturnCallback(function ($url) {
				return 'absolute-' . $url;
			});

		$this->assertSame(
			$expected,
			self::invokePrivate($this->controller, 'getPreviewPathFromMimeType', [$mimeType])
		);
	}
}
