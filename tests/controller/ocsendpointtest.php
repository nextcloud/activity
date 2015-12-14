<?php

/**
 * ownCloud
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Tests\Controller;

use OCA\Activity\Controller\OCSEndPoint;
use OCA\Activity\Tests\TestCase;

/**
 * Class OCSEndPointTest
 *
 * @group DB
 * @package OCA\Activity\Tests\Controller
 */
class OCSEndPointTest extends TestCase {
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

	/** @var OCSEndPoint */
	protected $controller;

	protected function setUp() {
		parent::setUp();

		$this->data = $this->getMockBuilder('OCA\Activity\Data')
			->disableOriginalConstructor()
			->getMock();
		$this->helper = $this->getMockBuilder('OCA\Activity\GroupHelper')
			->disableOriginalConstructor()
			->getMock();
		$this->userSettings = $this->getMockBuilder('OCA\Activity\UserSettings')
			->disableOriginalConstructor()
			->getMock();
		$this->preview = $this->getMockBuilder('OCP\IPreview')
			->disableOriginalConstructor()
			->getMock();
		$this->urlGenerator = $this->getMockBuilder('OCP\IURLGenerator')
			->disableOriginalConstructor()
			->getMock();
		$this->userSession = $this->getMockBuilder('OCP\IUserSession')
			->disableOriginalConstructor()
			->getMock();
		$this->mimeTypeDetector = $this->getMockBuilder('OCP\Files\IMimeTypeDetector')
			->disableOriginalConstructor()
			->getMock();
		$this->view = $this->getMockBuilder('OC\Files\View')
			->disableOriginalConstructor()
			->getMock();
		$this->infoCache = $this->getMockBuilder('OCA\Activity\ViewInfoCache')
			->disableOriginalConstructor()
			->getMock();
		$this->avatarManager = $this->getMockBuilder('OCP\IAvatarManager')
			->disableOriginalConstructor()
			->getMock();

		$this->request = $this->getMock('OCP\IRequest');

		$this->controller = $this->getController();

		$this->overwriteService('AvatarManager', $this->avatarManager);
	}

	public function tearDown() {

		$this->restoreService('AvatarManager');
		parent::tearDown();
	}

	protected function getController(array $methods = []) {
		if (empty($methods)) {
			return new OCSEndPoint(
				$this->data,
				$this->helper,
				$this->userSettings,
				$this->request,
				$this->urlGenerator,
				$this->userSession,
				$this->preview,
				$this->mimeTypeDetector,
				$this->view,
				$this->infoCache
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Controller\OCSEndPoint')
				->setConstructorArgs([
					$this->data,
					$this->helper,
					$this->userSettings,
					$this->request,
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

	public function dataReadParameterFilter() {
		return [
			[['filter' => 'valid1'], 'valid1'],
			[['filter' => 'valid2'], 'valid2'],
			[[], 'all'],
		];
	}

	/**
	 * @dataProvider dataReadParameterFilter
	 * @param array $params
	 * @param string $filter
	 */
	public function testReadParameterFilter(array $params, $filter) {
		$this->data->expects($this->once())
			->method('validateFilter')
			->with($filter)
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($this->getMock('OCP\IUser'));

		$this->invokePrivate($this->controller, 'readParameters', [$params]);
		$this->assertSame($filter, $this->invokePrivate($this->controller, 'filter'));
	}

	public function dataReadParameterFilterInvalid() {
		return [
			[['filter' => 'invalid1'], 'invalid1'],
			[['filter' => 'invalid2'], 'invalid2'],
		];
	}

	/**
	 * @dataProvider dataReadParameterFilterInvalid
	 *
	 * @param array $params
	 * @param string $filter
	 * @expectedException \OCA\Activity\Exception\InvalidFilterException
	 */
	public function testReadParameterFilterInvalid(array $params, $filter) {
		$this->data->expects($this->once())
			->method('validateFilter')
			->with($filter)
			->willReturn('all');
		$this->userSession->expects($this->never())
			->method('getUser');

		$this->invokePrivate($this->controller, 'readParameters', [$params]);
		$this->assertSame($filter, $this->invokePrivate($this->controller, 'filter'));
	}

	public function dataReadParameterObject() {
		return [
			['type', 42, 'type', 42],
			['type', '42', 'type', 42],
			[null, '42', '', 0],
			['type', null, '', 0],
		];
	}

	/**
	 * @dataProvider dataReadParameterObject
	 * @param mixed $type
	 * @param mixed $id
	 * @param string $expectedType
	 * @param int $expectedId
	 */
	public function testReadParameterObject($type, $id, $expectedType, $expectedId) {
		$this->request->expects($this->any())
			->method('getParam')
			->willReturnMap([
				['object_type', '', $type],
				['object_id', 0, $id],
			]);

		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($this->getMock('OCP\IUser'));

		$this->invokePrivate($this->controller, 'readParameters', [[]]);
		$this->assertSame($expectedType, $this->invokePrivate($this->controller, 'objectType'));
		$this->assertSame($expectedId, $this->invokePrivate($this->controller, 'objectId'));
	}

	public function dataReadParameters() {
		return [
			['since', 0, 0, 'since', 0],
			['since', 0, 20, 'since', 20],
			['since', 0, '25', 'since', 25],
			['limit', 50, 0, 'limit', 0],
			['limit', 50, 20, 'limit', 20],
			['limit', 50, '25', 'limit', 25],
			['sort', '', '', 'sort', 'desc'],
			['sort', '', 'asc', 'sort', 'asc'],
			['sort', '', 'desc', 'sort', 'desc'],
			['previews', 'false', 'false', 'loadPreviews', false],
			['previews', 'false', 'true', 'loadPreviews', true],
		];
	}

	/**
	 * @dataProvider dataReadParameters
	 * @param string $param
	 * @param mixed $default
	 * @param string $value
	 * @param mixed $memberName
	 * @param mixed $expectedValue
	 */
	public function testReadParameters($param, $default, $value, $memberName, $expectedValue) {
		$this->request->expects($this->any())
			->method('getParam')
			->willReturnMap([
				[$param, $default, $value],
			]);

		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($this->getMock('OCP\IUser'));

		$this->invokePrivate($this->controller, 'readParameters', [[]]);
		$this->assertSame($expectedValue, $this->invokePrivate($this->controller, $memberName));
	}

	public function dataReadParameterUser() {
		return [
			['uid1'],
			['uid2'],
		];
	}

	/**
	 * @dataProvider dataReadParameterUser
	 * @param string $uid
	 */
	public function testReadParameterUser($uid) {
		$user = $this->getMock('OCP\IUser');
		$user->expects($this->once())
			->method('getUID')
			->willReturn($uid);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn($user);

		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);

		$this->invokePrivate($this->controller, 'readParameters', [[]]);
		$this->assertSame($uid, $this->invokePrivate($this->controller, 'user'));
	}

	/**
	 * @expectedException \OutOfBoundsException
	 */
	public function testReadParameterUserInvalid() {
		$this->data->expects($this->once())
			->method('validateFilter')
			->willReturnArgument(0);
		$this->userSession->expects($this->once())
			->method('getUser')
			->willReturn(null);

		$this->invokePrivate($this->controller, 'readParameters', [[]]);
		$this->assertSame(null, $this->invokePrivate($this->controller, 'user'));
	}

	public function dataParameters() {
		return [
			[['limit' => 25], ['limit' => 25]],
			[['filter' => 'anything'], []],
			[['filter' => 'two'], []],
		];
	}

	/**
	 * @dataProvider dataParameters
	 *
	 * @param array $parameters
	 * @param array $expected
	 */
	public function testGetDefault(array $parameters, array $expected) {
		$controller = $this->getController([
			'get'
		]);

		$expected['filter'] = 'all';

		$controller->expects($this->once())
			->method('get')
			->with($expected);

		$controller->getDefault($parameters);
	}

	/**
	 * @dataProvider dataParameters
	 *
	 * @param array $parameters
	 */
	public function testGetFilter(array $parameters) {
		$controller = $this->getController([
			'get'
		]);

		$controller->expects($this->once())
			->method('get')
			->with($parameters);

		$controller->getFilter($parameters);
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
		$this->invokePrivate($this->controller, 'sort', [$sort]);
		$this->invokePrivate($this->controller, 'limit', [$limit]);
		$this->invokePrivate($this->controller, 'objectType', [$objectType]);
		$this->invokePrivate($this->controller, 'objectId', [$objectId]);
		$this->request->expects($this->any())
			->method('getParam')
			->with('format')
			->willReturn($format);

		$headers = $this->invokePrivate($this->controller, 'generateHeaders', [$headersIn, $hasMoreActivities]);
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

		$this->assertSame(['getPreviewFromPath'], $this->invokePrivate($controller, 'getPreview', [$author, $fileId, $path]));
	}

	public function dataGetPreview() {
		return [
			['author', 42, '/path', '/currentPath', true, false, '/preview/dir', true],
			['author', 42, '/file.txt', '/currentFile.txt', false, false, '/preview/mpeg', true],
			['author', 42, '/file.txt', '/currentFile.txt', false, true, '/preview/currentFile.txt', false],
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
	 * @param bool $isMimeSup
	 * @param string $source
	 * @param bool $isMimeTypeIcon
	 */
	public function testGetPreview($author, $fileId, $path, $returnedPath, $isDir, $isMimeSup, $source, $isMimeTypeIcon) {

		$controller = $this->getController([
			'getPreviewLink',
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
		} else {
			$fileInfo = $this->getMockBuilder('OCP\Files\FileInfo')
				->disableOriginalConstructor()
				->getMock();

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
					->method('linkToRoute')
					->with('core_ajax_preview', $this->anything())
					->willReturnCallback(function() use ($returnedPath) {
						return '/preview' . $returnedPath;
					});
			}
		}

		$this->assertSame([
			'link' => '/preview' . $returnedPath,
			'source' => $source,
			'isMimeTypeIcon' => $isMimeTypeIcon,
		], $this->invokePrivate($controller, 'getPreview', [$author, $fileId, $path]));
	}

	public function dataGetPreviewFromPath() {
		return [
			['dir', 'dir', '/core/img/filetypes/folder.svg'],
			['test.txt', 'text/plain', '/core/img/filetypes/text.svg'],
			['test.mp3', 'audio/mpeg', '/core/img/filetypes/audio.svg'],
		];
	}

	/**
	 * @dataProvider dataGetPreviewFromPath
	 * @param string $filePath
	 * @param string $mimeType
	 */
	public function testGetPreviewFromPath($filePath, $mimeType) {
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
			->with($filePath, false)
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
			$this->invokePrivate($controller, 'getPreviewFromPath', [$filePath])
		);
	}

	public function dataGetPreviewPathFromMimeType() {
		return [
			['dir', '/core/img/filetypes/folder.png', '/core/img/filetypes/folder.svg'],
			['text/plain', '/core/img/filetypes/text.svg', '/core/img/filetypes/text.svg'],
			['text/plain', '/core/img/filetypes/text.jpg', '/core/img/filetypes/text.jpg'],
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

		$this->assertSame(
			$expected,
			$this->invokePrivate($this->controller, 'getPreviewPathFromMimeType', [$mimeType])
		);
	}

	public function dataGetPreviewLink() {
		return [
			['/folder', true, ['dir' => '/folder']],
			['/folder/sub1', true, ['dir' => '/folder/sub1']],
			['/folder/sub1/sub2', true, ['dir' => '/folder/sub1/sub2']],
			['/file.txt', false, ['dir' => '/', 'scrollto' => 'file.txt']],
			['/folder/file.txt', false, ['dir' => '/folder', 'scrollto' => 'file.txt']],
			['/folder/sub1/file.txt', false, ['dir' => '/folder/sub1', 'scrollto' => 'file.txt']],
		];
	}

	/**
	 * @dataProvider dataGetPreviewLink
	 *
	 * @param string $path
	 * @param bool $isDir
	 * @param array $expected
	 */
	public function testGetPreviewLink($path, $isDir, $expected) {
		$this->urlGenerator->expects($this->once())
			->method('linkTo')
			->with('files', 'index.php', $expected);

		$this->invokePrivate($this->controller, 'getPreviewLink', [$path, $isDir]);
	}
}
