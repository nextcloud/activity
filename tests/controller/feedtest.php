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

use OCA\Activity\Controller\Feed;
use OCA\Activity\Tests\TestCase;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Util;

class FeedTest extends TestCase {
	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $config;

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
	protected $manager;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $session;

	/** @var \OCP\IL10N */
	protected $l10n;

	/** @var Feed */
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

		$this->config = $this->getMock('OCP\IConfig');
		$this->request = $this->getMock('OCP\IRequest');
		$this->session = $this->getMockBuilder('OCP\IUserSession')
			->disableOriginalConstructor()
			->getMock();
		$this->manager = $this->getMockBuilder('OCP\Activity\IManager')
			->disableOriginalConstructor()
			->getMock();

		$this->controller = new Feed(
			'activity',
			$this->request,
			$this->data,
			$this->helper,
			$this->userSettings,
			$this->getMockBuilder('OCP\IURLGenerator')->disableOriginalConstructor()->getMock(),
			$this->manager,
			\OC::$server->getL10NFactory(),
			$this->config,
			'test'
		);
	}


	public function showData() {
		return [
			['application/rss+xml', 'application/rss+xml'],
			[null, 'text/xml; charset=UTF-8'],
		];
	}

	/**
	 * @dataProvider showData
	 *
	 * @param string $acceptHeader
	 * @param string $expectedHeader
	 */
	public function testShow($acceptHeader, $expectedHeader) {
		$this->mockUserSession('test');
		$this->data->expects($this->any())
			->method('read')
			->willReturn([]);
		if ($acceptHeader !== null) {
			$this->request->expects($this->any())
				->method('getHeader')
				->willReturn($acceptHeader);
		}

		$templateResponse = $this->controller->show();
		$this->assertTrue($templateResponse instanceof TemplateResponse, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$headers = $templateResponse->getHeaders();
		$this->assertArrayHasKey('Content-Type', $headers);
		$this->assertEquals($expectedHeader, $headers['Content-Type']);

		$renderedResponse = $templateResponse->render();
		$this->assertNotEmpty($renderedResponse);

		$l = Util::getL10N('activity');
		$description = (string) $l->t('Your feed URL is invalid');
		$this->assertNotContains($description, $renderedResponse);
	}

	/**
	 * @dataProvider showData
	 *
	 * @param string $acceptHeader
	 * @param string $expectedHeader
	 */
	public function testShowNoToken($acceptHeader, $expectedHeader) {
		$this->manager->expects($this->any())
			->method('getCurrentUserId')
			->willThrowException(new \UnexpectedValueException());
		if ($acceptHeader !== null) {
			$this->request->expects($this->any())
				->method('getHeader')
				->willReturn($acceptHeader);
		}

		$templateResponse = $this->controller->show();
		$this->assertTrue($templateResponse instanceof TemplateResponse, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$headers = $templateResponse->getHeaders();
		$this->assertArrayHasKey('Content-Type', $headers);
		$this->assertEquals($expectedHeader, $headers['Content-Type']);

		$renderedResponse = $templateResponse->render();
		$this->assertNotEmpty($renderedResponse);

		$l = Util::getL10N('activity');
		$description = (string) $l->t('Your feed URL is invalid');
		$this->assertContains($description, $renderedResponse);
	}

	protected function mockUserSession($user) {
		$mockUser = $this->getMockBuilder('\OCP\IUser')
			->disableOriginalConstructor()
			->getMock();
		$mockUser->expects($this->any())
			->method('getUID')
			->willReturn($user);

		$this->session->expects($this->any())
			->method('isLoggedIn')
			->willReturn(true);
		$this->session->expects($this->any())
			->method('getUser')
			->willReturn($mockUser);
		$this->manager->expects($this->any())
			->method('getCurrentUserId')
			->willReturn($user);
	}
}
