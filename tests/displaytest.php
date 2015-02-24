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

namespace OCA\Activity\Tests;

use OC\Files\View;
use OCA\Activity\Display;

class DisplayTest extends TestCase {
	/** @var Display */
	protected $display;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $dateTimeFormatter;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $preview;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $urlGenerator;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $view;

	protected function setUp() {
		parent::setUp();

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

		$this->display = new Display(
			$this->dateTimeFormatter,
			$this->preview,
			$this->urlGenerator,
			$this->view
		);
	}

	public function showData() {
		return [
			[
				[
					'timestamp'		=> time(),
					'user'			=> 'test',
					'affecteduser'	=> 'foobar',
					'app'			=> 'files',
					'link'			=> 'localhost',
					'file'			=> 'A.txt',
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
				]
			],
		];
	}

	/**
	 * @param array $data
	 * @dataProvider showData
	 */
	public function testShow(array $data) {
		$output = $this->display->show($data);
		$this->assertNotEmpty($output, 'Asserting that the template output is not empty');
	}

	/**
	 * @param array $data
	 * @dataProvider showData
	 */
	public function testShowExisting(array $data) {
		$this->view->expects($this->any())
			->method('file_exists')
			->with('A.txt')
			->willReturn(true);
		$this->view->expects($this->any())
			->method('is_dir')
			->with('A.txt')
			->willReturn(false);

		$this->preview->expects($this->any())
			->method('isMimeSupported')
			->with('text/plain')
			->willReturn(true);

		$output = $this->display->show($data);
		$this->assertNotEmpty($output, 'Asserting that the template output is not empty');
	}
}
