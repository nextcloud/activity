<?php
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

use OCA\Activity\Controller\APIv2;
use OCA\Activity\Exception\InvalidFilterException;
use OCA\Activity\Tests\TestCase;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IUser;
use OCP\Files\FileInfo;
use OCP\IRequest;
use OCP\IAvatarManager;
use OCA\Activity\ViewInfoCache;
use OC\Files\View;
use OCP\Files\IMimeTypeDetector;
use OCP\IUserSession;
use OCP\IURLGenerator;
use OCP\IPreview;
use OCA\Activity\UserSettings;
use OCA\Activity\GroupHelper;
use OCA\Activity\Data;

/**
 * Class APIv2Test
 *
 * @group DB
 * @package OCA\Activity\Tests\Controller
 */
class APIv2Test extends TestCase {
	/** @var \OCP\IRequest|\PHPUnit_Framework_MockObject_MockObject */
	protected $request;

	/** @var \OCA\Activity\Data|\PHPUnit_Framework_MockObject_MockObject */
	protected $data;

	/** @var \OCA\Activity\GroupHelper|\PHPUnit_Framework_MockObject_MockObject */
	protected $helper;

	/** @var \OCA\Activity\UserSettings|\PHPUnit_Framework_MockObject_MockObject */
	protected $userSettings;

	/** @var \OCP\IPreview|\PHPUnit_Framework_MockObject_MockObject */
	protected $preview;

	/** @var \OCP\IURLGenerator|\PHPUnit_Framework_MockObject_MockObject */
	protected $urlGenerator;

	/** @var \OCP\IUserSession|\PHPUnit_Framework_MockObject_MockObject */
	protected $userSession;

	/** @var \OCP\Files\IMimeTypeDetector|\PHPUnit_Framework_MockObject_MockObject */
	protected $mimeTypeDetector;

	/** @var \OC\Files\View|\PHPUnit_Framework_MockObject_MockObject */
	protected $view;

	/** @var \OCA\Activity\ViewInfoCache|\PHPUnit_Framework_MockObject_MockObject */
	protected $infoCache;

	/** @var \OCP\IAvatarManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $avatarManager;

	/** @var \OCP\IL10N */
	protected $l10n;

	/** @var APIv2 */
	protected $controller;

	protected function setUp() {
		parent::setUp();

		$this->data = $this->createMock(Data::class);
		$this->helper = $this->createMock(GroupHelper::class);
		$this->userSettings = $this->createMock(UserSettings::class);
		$this->preview = $this->createMock(IPreview::class);
		$this->urlGenerator = $this->createMock(IURLGenerator::class);
		$this->userSession = $this->createMock(IUserSession::class);
		$this->mimeTypeDetector = $this->createMock(IMimeTypeDetector::class);
		$this->view = $this->createMock(View::class);
		$this->infoCache = $this->createMock(ViewInfoCache::class);
		$this->avatarManager = $this->createMock(IAvatarManager::class);
		$this->request = $this->createMock(IRequest::class);

		$this->controller = $this->getController();

		$this->overwriteService('AvatarManager', $this->avatarManager);
	}

	public function tearDown() {

		$this->restoreService('AvatarManager');
		parent::tearDown();
	}

	protected function getController(array $methods = []) {
		if (empty($methods)) {
			return new APIv2(
				'activity',
				$this->request,
				$this->data,
				$this->helper,
				$this->userSettings,
				$this->urlGenerator,
				$this->userSession,
				$this->preview,
				$this->mimeTypeDetector,
				$this->view,
				$this->infoCache
			);
		} else {
			return $this->getMockBuilder(APIv2::class)
				->setConstructorArgs([
					'activity',
					$this->request,
					$this->data,
					$this->helper,
					$this->userSettings,
					$this->urlGenerator,
					$this->userSession,
					$this->preview,
					$this->mimeTypeDetector,
					$this->view,
					$this->infoCache,
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function dataValidateParametersFilter() {
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
	public function testValidateParametersFilter($param, $filter) {
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

	public function dataValidateParametersFilterInvalid() {
		return [
			['invalid1'],
			['invalid2'],
		];
	}

	/**
	 * @dataProvider dataValidateParametersFilterInvalid
	 *
	 * @param string $filter
	 * @expectedException \OCA\Activity\Exception\InvalidFilterException
	 */
	public function testValidateParametersFilterInvalid($filter) {
		$this->data->expects($this->once())
			->method('validateFilter')
			->with($filter)
			->willReturn('all');
		$this->userSession->expects($this->never())
			->method('getUser');

		self::invokePrivate($this->controller, 'validateParameters', [$filter, 0, 0, false, '', 0, 'desc']);
		$this->assertSame($filter, self::invokePrivate($this->controller, 'filter'));
	}

	public function dataValidateParametersObject() {
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
	public function testValidateParametersObject($type, $id, $expectedType, $expectedId) {
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

	public function dataValidateParameters() {
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
	public function testValidateParameters($param, $value, $memberName, $expectedValue) {
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

	public function dataValidateParametersUser() {
		return [
			['uid1'],
			['uid2'],
		];
	}

	/**
	 * @dataProvider dataValidateParametersUser
	 * @param string $uid
	 */
	public function testValidateParametersUser($uid) {
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

	/**
	 * @expectedException \OutOfBoundsException
	 */
	public function testValidateParametersUserInvalid() {
		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn(null);

		self::invokePrivate($this->controller, 'validateParameters', ['all', 0, 0, false, '', 0, 'desc']);
		$this->assertNull(self::invokePrivate($this->controller, 'user'));
	}

	public function dataParameters() {
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
	public function testGetDefault($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort) {
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
	public function testGetFilter($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort) {
		$controller = $this->getController([
			'get'
		]);

		$controller->expects($this->once())
			->method('get')
			->with($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);

		$controller->getFilter($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);
	}

	public function dataGetInvalid() {
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
	public function testGetInvalid($readParamsThrows, $dataGetThrows, $expected) {
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
		} else if ($dataGetThrows === false) {
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

	public function dataGet() {
		return [
			[123456789, 'files', 42, [], false, 0, ['object_type' => 'files', 'object_id' => 42, 'datetime' => date(\DateTime::ATOM, 123456789)]],
			[12345678, 'calendar', 23, [], true, 0, ['object_type' => 'calendar', 'object_id' => 23, 'datetime' => date(\DateTime::ATOM, 12345678), 'previews' => []]],
			[
				12345678, 'files', 23, ['objects' => [], 'affecteduser' => 'user1', 'object_name' => 'file.txt'],
				true, 1,
				['object_type' => 'files', 'object_id' => 23, 'objects' => [], 'affecteduser' => 'user1', 'object_name' => 'file.txt', 'datetime' => date(\DateTime::ATOM, 12345678), 'previews' => [['preview']]]
			],
			[
				12345678, 'files', 23, ['objects' => [12 => '12.png', 23 => '23.txt', 0 => '0.txt', 123 => ''], 'affecteduser' => 'user1'],
				true, 2,
				['object_type' => 'files', 'object_id' => 23, 'objects' => [12 => '12.png', 23 => '23.txt', 0 => '0.txt', 123 => ''], 'affecteduser' => 'user1', 'datetime' => date(\DateTime::ATOM, 12345678), 'previews' => [['preview'], ['preview']]]
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
	public function testGet($time, $objectType, $objectId, array $additionalArgs, $loadPreviews, $numGetPreviewCalls, array $expected) {
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
				'headers' => ['X-Activity-First-Known' => 23],
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

	public function dataGenerateHeaders() {
		return [
			['asc', 25, '', 0, null, ['X-Activity-Last-Given' => 23], false, ['X-Activity-Last-Given' => 23]],
			['asc', 25, '', 0, null, ['X-Activity-Last-Given' => 23], true, ['X-Activity-Last-Given' => 23, 'Link' => '<://?since=23&limit=25&sort=asc>; rel="next"']],
			['asc', 25, 'ob', 10, null, ['X-Activity-Last-Given' => 23], true, ['X-Activity-Last-Given' => 23, 'Link' => '<://?since=23&limit=25&sort=asc&object_type=ob&object_id=10>; rel="next"']],
			['asc', 25, '', 0, 'json', ['X-Activity-Last-Given' => 23], true, ['X-Activity-Last-Given' => 23, 'Link' => '<://?since=23&limit=25&sort=asc&format=json>; rel="next"']],
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
	 * @param array $expected
	 */
	public function testGenerateHeaders($sort, $limit, $objectType, $objectId, $format, array $headersIn, $hasMoreActivities, array $expected) {
		self::invokePrivate($this->controller, 'sort', [$sort]);
		self::invokePrivate($this->controller, 'limit', [$limit]);
		self::invokePrivate($this->controller, 'objectType', [$objectType]);
		self::invokePrivate($this->controller, 'objectId', [$objectId]);
		$this->request->expects($this->any())
			->method('getParam')
			->with('format')
			->willReturn($format);

		$headers = self::invokePrivate($this->controller, 'generateHeaders', [$headersIn, $hasMoreActivities]);
		$this->assertEquals($expected, $headers);
	}

	public function dataGetPreviewInvalidPaths() {
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
	public function testGetPreviewInvalidPaths($author, $fileId, $path, $returnedPath, $exists) {
		$this->infoCache->expects($this->once())
			->method('getInfoById')
			->with($author, $fileId, $path)
			->willReturn([
				'path'		=> $returnedPath,
				'exists'	=> $exists,
				'is_dir'	=> false,
				'view'		=> '',
			]);

		$controller = $this->getController([
			'getPreviewFromPath'
		]);
		$controller->expects($this->any())
			->method('getPreviewFromPath')
			->with($path)
			->willReturn(['getPreviewFromPath']);

		$this->assertSame(['getPreviewFromPath'], self::invokePrivate($controller, 'getPreview', [$author, $fileId, $path]));
	}

	public function dataGetPreview() {
		return [
			['author', 42, '/path', '/currentPath', true, true, false, '/preview/dir', true],
			['author', 42, '/file.txt', '/currentFile.txt', false, true, false, '/preview/mpeg', true],
			['author', 42, '/file.txt', '/currentFile.txt', false, true, true, '/preview/currentFile.txt', false],
			['author', 42, '/file.txt', '/currentFile.txt', false, false, true, 'source::getPreviewFromPath', true],
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
	 */
	public function testGetPreview($author, $fileId, $path, $returnedPath, $isDir, $validFileInfo, $isMimeSup, $source, $isMimeTypeIcon) {

		$controller = $this->getController([
			'getPreviewLink',
			'getPreviewFromPath',
			'getPreviewPathFromMimeType',
		]);

		$this->infoCache->expects($this->once())
			->method('getInfoById')
			->with($author, $fileId, $path)
			->willReturn([
				'path'		=> $returnedPath,
				'exists'	=> true,
				'is_dir'	=> $isDir,
				'view'		=> '',
			]);

		$controller->expects($this->once())
			->method('getPreviewLink')
			->with($returnedPath, $isDir)
			->willReturnCallback(function($path) {
				return '/preview' . $path;
			});

		if ($isDir) {
			$controller->expects($this->once())
				->method('getPreviewPathFromMimeType')
				->with('dir')
				->willReturn('/preview/dir');
		} else if ($validFileInfo) {
			$fileInfo = $this->createMock(FileInfo::class);

			$this->view->expects($this->once())
				->method('chroot')
				->with('/' . $author . '/files');
			$this->view->expects($this->once())
				->method('getFileInfo')
				->with($returnedPath)
				->willReturn($fileInfo);

			$this->preview->expects($this->once())
				->method('isAvailable')
				->with($fileInfo)
				->willReturn($isMimeSup);

			if (!$isMimeSup) {
				$fileInfo->expects($this->once())
					->method('getMimetype')
					->willReturn('audio/mp3');

				$controller->expects($this->once())
					->method('getPreviewPathFromMimeType')
					->with('audio/mp3')
					->willReturn('/preview/mpeg');
			} else {
				$this->urlGenerator->expects($this->once())
					->method('linkToRouteAbsolute')
					->with('core.Preview.getPreview', $this->anything())
					->willReturnCallback(function() use ($returnedPath) {
						return '/preview' . $returnedPath;
					});
			}
		} else {
			$this->view->expects($this->once())
				->method('chroot')
				->with('/' . $author . '/files');
			$this->view->expects($this->once())
				->method('getFileInfo')
				->with($returnedPath)
				->willReturn(false);

			$controller->expects($this->once())
				->method('getPreviewFromPath')
				->with($path, $this->anything())
				->willReturn(['source' => 'source::getPreviewFromPath']);
		}

		$this->assertSame([
			'link' => '/preview' . $returnedPath,
			'source' => $source,
			'isMimeTypeIcon' => $isMimeTypeIcon,
		], self::invokePrivate($controller, 'getPreview', [$author, $fileId, $path]));
	}

	public function dataGetPreviewFromPath() {
		return [
			['dir', 'dir', true, ''],
			['test.txt', 'text/plain', false, 'trashbin'],
			['test.mp3', 'audio/mpeg', false, ''],
		];
	}

	/**
	 * @dataProvider dataGetPreviewFromPath
	 * @param string $filePath
	 * @param string $mimeType
	 * @param bool $isDir
	 * @param string $view
	 */
	public function testGetPreviewFromPath($filePath, $mimeType, $isDir, $view) {
		$controller = $this->getController([
			'getPreviewPathFromMimeType',
			'getPreviewLink',
		]);

		$controller->expects($this->once())
			->method('getPreviewPathFromMimeType')
			->with($mimeType)
			->willReturn('mime-type-icon');
		$controller->expects($this->once())
			->method('getPreviewLink')
			->with($filePath, $isDir, $view)
			->willReturn('target-link');
		$this->mimeTypeDetector->expects($this->once())
			->method('detectPath')
			->willReturn($mimeType);

		$this->assertSame(
			[
				'link' => 'target-link',
				'source' => 'mime-type-icon',
				'isMimeTypeIcon' => true,
			],
			self::invokePrivate($controller, 'getPreviewFromPath', [$filePath, ['path' => $filePath, 'is_dir' => $isDir, 'view' => $view]])
		);
	}

	public function dataGetPreviewPathFromMimeType() {
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
	public function testGetPreviewPathFromMimeType($mimeType, $icon, $expected) {
		$this->mimeTypeDetector->expects($this->once())
			->method('mimeTypeIcon')
			->with($mimeType)
			->willReturn($icon);

		$this->urlGenerator->expects($this->once())
			->method('getAbsoluteURL')
			->willReturnCallback(function($url) {
				return 'absolute-' . $url;
			});

		$this->assertSame(
			$expected,
			self::invokePrivate($this->controller, 'getPreviewPathFromMimeType', [$mimeType])
		);
	}

	public function dataGetPreviewLink() {
		return [
			['/folder', true, '', ['dir' => '/folder']],
			['/folder/sub1', true, 'trashbin', ['dir' => '/folder/sub1', 'view' => 'trashbin']],
			['/folder/sub1/sub2', true, '', ['dir' => '/folder/sub1/sub2']],
			['/file.txt', false, '', ['dir' => '/', 'scrollto' => 'file.txt']],
			['/folder/file.txt', false, 'trashbin', ['dir' => '/folder', 'scrollto' => 'file.txt', 'view' => 'trashbin']],
			['/folder/sub1/file.txt', false, '', ['dir' => '/folder/sub1', 'scrollto' => 'file.txt']],
		];
	}

	/**
	 * @dataProvider dataGetPreviewLink
	 *
	 * @param string $path
	 * @param bool $isDir
	 * @param string $view
	 * @param array $expected
	 */
	public function testGetPreviewLink($path, $isDir, $view, $expected) {
		$this->urlGenerator->expects($this->once())
			->method('linkToRouteAbsolute')
			->with('files.view.index', $expected);

		self::invokePrivate($this->controller, 'getPreviewLink', [$path, $isDir, $view]);
	}
}
