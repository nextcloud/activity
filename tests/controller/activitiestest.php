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
	protected $display;

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
		$this->display = $this->getMockBuilder('OCA\Activity\Display')
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
		$this->view = $this->getMockBuilder('OC\Files\View')
			->disableOriginalConstructor()
			->getMock();

		$this->request = $this->getMock('OCP\IRequest');

		$this->controller = new Activities(
			'activity',
			$this->request,
			$this->data,
			$this->display,
			$this->helper,
			$this->navigation,
			$this->userSettings,
			$this->dateTimeFormatter,
			$this->preview,
			$this->urlGenerator,
			$this->view,
			'test'
		);
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

						'preview'			=> [
							'link'				=> 'linkToStub',
							'source'			=> 'linkToRouteStub',
							'isMimeTypeIcon'	=> false,
						]
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

						'preview'			=> [
							'link'				=> 'linkToStub',
							'source'			=> '/core/img/filetypes/audio.png',
							'isMimeTypeIcon'	=> true,
						]
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

						'preview'			=> [
							'link'				=> 'linkToStub',
							'source'			=> '/core/img/filetypes/folder.png',
							'isMimeTypeIcon'	=> true,
						]
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

		/** @var \OCP\AppFramework\Http\JSONResponse $response */
		$response = $this->controller->fetch(1);
		$this->assertInstanceOf('\OCP\AppFramework\Http\JSONResponse', $response, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$this->assertEquals($expected, $response->getData());
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
