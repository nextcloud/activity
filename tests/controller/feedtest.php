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

		$this->controller = new Feed(
			'activity',
			$this->request,
			$this->data,
			$this->helper,
			$this->userSettings,
			$this->getMockBuilder('OCP\IURLGenerator')->disableOriginalConstructor()->getMock(),
			$this->session,
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

	public function getUserFromTokenThrowInvalidTokenData() {
		return [
			[null, []],
			['', []],
			['12345678901234567890123456789', []],
			['1234567890123456789012345678901', []],
			['123456789012345678901234567890', []],
			['123456789012345678901234567890', ['user1', 'user2']],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 * @dataProvider getUserFromTokenThrowInvalidTokenData
	 *
	 * @param string $token
	 * @param array $users
	 */
	public function testGetUserFromTokenThrowInvalidToken($token, $users) {
		if ($token !== null) {
			$this->request->expects($this->any())
				->method('getParam')
				->with('token', '')
				->willReturn($token);
		}
		$this->config->expects($this->any())
			->method('getUsersForUserValue')
			->with('activity', 'rsstoken', $token)
			->willReturn($users);

		\Test_Helper::invokePrivate($this->controller, 'getUserFromToken');
	}


	public function getUserData() {
		return [
			[null, '123456789012345678901234567890', 'user1'],
			['user2', null, 'user2'],
			['user2', '123456789012345678901234567890', 'user2'],
		];
	}

	/**
	 * @dataProvider getUserData
	 *
	 * @param string $userLoggedIn
	 * @param string $token
	 * @param string $expected
	 */
	public function testGetUser($userLoggedIn, $token, $expected) {
		if ($userLoggedIn !== null) {
			$this->mockUserSession($userLoggedIn);
		}

		if ($token !== null) {
			$this->request->expects($this->any())
				->method('getParam')
				->with('token', '')
				->willReturn($token);
		}

		$this->config->expects($this->any())
			->method('getUsersForUserValue')
			->with('activity', 'rsstoken', '123456789012345678901234567890')
			->willReturn(['user1']);

		$this->assertEquals($expected, \Test_Helper::invokePrivate($this->controller, 'getUser'));
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
	}
}
