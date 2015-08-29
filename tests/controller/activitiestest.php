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

use OCA\Activity\Controller\Activities;
use OCA\Activity\Tests\TestCase;
use OCP\Template;

class ActivitiesTest extends TestCase {
	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $request;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $data;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $helper;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $navigation;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $userSettings;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $dateTimeFormatter;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $preview;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $urlGenerator;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $mimeTypeDetector;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $view;

	/** @var \OCP\IL10N */
	protected $l10n;

	/** @var Activities */
	protected $controller;

	protected function setUp() {
		parent::setUp();

		$this->data = $this->getMockBuilder('OCA\Activity\Data')
			->disableOriginalConstructor()
			->getMock();
		$this->helper = $this->getMockBuilder('OCA\Activity\GroupHelper')
			->disableOriginalConstructor()
			->getMock();
		$this->navigation = $this->getMockBuilder('OCA\Activity\Navigation')
			->disableOriginalConstructor()
			->getMock();
		$this->userSettings = $this->getMockBuilder('OCA\Activity\UserSettings')
			->disableOriginalConstructor()
			->getMock();
		$this->dateTimeFormatter = $this->getMockBuilder('OCP\IDateTimeFormatter')
			->disableOriginalConstructor()
			->getMock();
		$this->preview = $this->getMockBuilder('OCP\IPreview')
			->disableOriginalConstructor()
			->getMock();
		$this->urlGenerator = $this->getMockBuilder('OCP\IURLGenerator')
			->disableOriginalConstructor()
			->getMock();
		$this->mimeTypeDetector = $this->getMockBuilder('OCP\Files\IMimeTypeDetector')
			->disableOriginalConstructor()
			->getMock();
		$this->view = $this->getMockBuilder('OC\Files\View')
			->disableOriginalConstructor()
			->getMock();

		$this->request = $this->getMock('OCP\IRequest');

		$this->controller = $this->getController();
	}

	protected function getController(array $methods = []) {
		if (empty($methods)) {
			return new Activities(
				'activity',
				$this->request,
				$this->data,
				$this->helper,
				$this->navigation,
				$this->userSettings,
				$this->dateTimeFormatter,
				$this->preview,
				$this->urlGenerator,
				$this->mimeTypeDetector,
				$this->view,
				'test'
			);
		} else {
			return $this->getMockBuilder('OCA\Activity\Controller\Activities')
				->setConstructorArgs([
					'activity',
					$this->request,
					$this->data,
					$this->helper,
					$this->navigation,
					$this->userSettings,
					$this->dateTimeFormatter,
					$this->preview,
					$this->urlGenerator,
					$this->mimeTypeDetector,
					$this->view,
					'test',
				])
				->setMethods($methods)
				->getMock();
		}
	}

	public function testShowList() {
		$template = new Template('activity', 'stream.app.navigation', '');
		$template->assign('activeNavigation', 'all');
		$template->assign('navigations', []);
		$template->assign('rssLink', '');
		$this->navigation->expects($this->any())
			->method('getTemplate')
			->willReturn($template);

		$templateResponse = $this->controller->showList();
		$this->assertInstanceOf('\OCP\AppFramework\Http\TemplateResponse', $templateResponse, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$renderedResponse = $templateResponse->render();
		$this->assertNotEmpty($renderedResponse);
	}

	public function dataFetch() {
		$timestamp = time();
		return [
			[
				[],
				[],
			],
			[
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> 'http://localhost',
						'file'			=> '/file.txt',
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.markup.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'object_type' => 'files',
						'object_id' => 21,
					],
				],
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> 'http://localhost',
						'file'			=> '/file.txt',
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.markup.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'relativeTimestamp' => 'today',
						'readableTimestamp' => (string) $timestamp,
						'relativeDateTimestamp' => 'seconds ago',
						'readableDateTimestamp' => (string) $timestamp,

						'object_type' => 'files',
						'object_id' => 21,
						'previews'			=> [
							['preview'],
						],
					],
				],
			],
			[
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> 'http://localhost',
						'file'			=> '/file.mp3',
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.markup.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'object_type' => 'files',
						'object_id' => 21,
					],
				],
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> 'http://localhost',
						'file'			=> '/file.mp3',
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.markup.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'relativeTimestamp' => 'today',
						'readableTimestamp' => (string) $timestamp,
						'relativeDateTimestamp' => 'seconds ago',
						'readableDateTimestamp' => (string) $timestamp,

						'object_type' => 'files',
						'object_id' => 21,
						'previews'			=> [
							['preview'],
						],
					],
				],
			],
			[
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> 'http://localhost',
						'file'			=> '/directory',
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.<a href="http://localhost">markup</a>.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'object_type' => 'files',
						'object_id' => 21,
					],
				],
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> '',
						'file'			=> '/directory',
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.<a href="http://localhost">markup</a>.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'relativeTimestamp' => 'today',
						'readableTimestamp' => (string) $timestamp,
						'relativeDateTimestamp' => 'seconds ago',
						'readableDateTimestamp' => (string) $timestamp,

						'object_type' => 'files',
						'object_id' => 21,
						'previews'			=> [
							['preview']
						],
					],
				],
			],
			[
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> 'http://localhost',
						'file'			=> '/directory',
						'files'			=> [
							21 => '/directory',
							20 => '',
							19 => '/directory',
							18 => '/directory',
							17 => '/directory',
							16 => '/directory',
							15 => '/directory',
							14 => '/directory',
							13 => '/directory',
						],
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.<a href="http://localhost">markup</a>.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'object_type' => 'files',
						'object_id' => 21,
					],
				],
				[
					[
						'timestamp'		=> $timestamp,
						'user'			=> 'test',
						'affecteduser'	=> 'foobar',
						'app'			=> 'files',
						'link'			=> '',
						'file'			=> '/directory',
						'files'			=> [
							21 => '/directory',
							20 => '',
							19 => '/directory',
							18 => '/directory',
							17 => '/directory',
							16 => '/directory',
							15 => '/directory',
							14 => '/directory',
							13 => '/directory',
						],
						'typeicon'		=> '',
						'subject'		=> 'subject',
						'subjectformatted'		=> [
							'trimmed'	=> 'subject.trimmed',
							'full'		=> 'subject.full',
							'markup'	=>[
								'trimmed'	=> 'subject.<a href="http://localhost">markup</a>.trimmed',
								'full'		=> 'subject.markup.full',
							],
						],
						'message'		=> 'message',
						'messageformatted'		=> [
							'trimmed'	=> 'message.trimmed',
							'full'		=> 'message.full',
							'markup'	=>[
								'trimmed'	=> 'message.markup.trimmed',
								'full'		=> 'message.markup.full',
							],
						],
						'relativeTimestamp' => 'today',
						'readableTimestamp' => (string) $timestamp,
						'relativeDateTimestamp' => 'seconds ago',
						'readableDateTimestamp' => (string) $timestamp,

						'object_type' => 'files',
						'object_id' => 21,
						'previews'			=> [
							['preview'],
							['preview'],
							['preview'],
							['preview'],
							['preview'],
							['preview'],
							['preview'],
						],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider dataFetch
	 *
	 * @param array $readData
	 * @param array $expected
	 */
	public function testFetch($readData, $expected) {
		$this->data->expects($this->any())
			->method('read')
			->willReturn($readData);

		$this->dateTimeFormatter->expects($this->any())
			->method('formatDate')
			->will($this->returnArgument(0));
		$this->dateTimeFormatter->expects($this->any())
			->method('formatDateTime')
			->will($this->returnArgument(0));

		$this->urlGenerator->expects($this->any())
			->method('linkTo')
			->willReturn('linkToStub');
		$this->urlGenerator->expects($this->any())
			->method('linkToRoute')
			->willReturn('linkToRouteStub');

		$this->view->expects($this->any())
			->method('getPath')
			->willReturnMap([
				[21, '/file.txt'],
				[42, null],
			]);
		$this->view->expects($this->any())
			->method('is_dir')
			->willReturnMap([
				['/directory', true],
				['/file.txt', false],
				['/file.mp3', false],
			]);
		$this->view->expects($this->any())
			->method('file_exists')
			->willReturnMap([
				['/directory', true],
				['/file.txt', true],
				['/file.mp3', false],
			]);

		$this->preview->expects($this->any())
			->method('isMimeSupported')
			->willReturnMap([
				['text/plain', true],
				['audio/mpeg', false],
			]);

		$controller = $this->getController([
			'getPreview'
		]);
		$controller->expects($this->any())
			->method('getPreview')
			->willReturn(['preview']);

		/** @var \OCP\AppFramework\Http\JSONResponse $response */
		$response = $controller->fetch(1);
		$this->assertInstanceOf('\OCP\AppFramework\Http\JSONResponse', $response, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$this->assertEquals($expected, $response->getData());
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
		$this->view->expects($this->once())
			->method('chroot')
			->with('/' . $author . '/files');
		$this->view->expects($this->once())
			->method('getPath')
			->with($fileId)
			->willReturn($returnedPath);
		if ($exists === null) {
			$this->view->expects($this->never())
				->method('file_exists');
		} else {
			$this->view->expects($this->once())
				->method('file_exists')
				->willReturn($exists);
		}

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

		$this->view->expects($this->once())
			->method('chroot')
			->with('/' . $author . '/files');
		$this->view->expects($this->once())
			->method('getPath')
			->with($fileId)
			->willReturn($returnedPath);
		$this->view->expects($this->once())
			->method('file_exists')
			->with($returnedPath)
			->willReturn(true);
		$this->view->expects($this->once())
			->method('is_dir')
			->with($returnedPath)
			->willReturn($isDir);

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
	 * @param string $expected
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
