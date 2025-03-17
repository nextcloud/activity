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

use OCA\Activity\Controller\FeedController;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\Tests\TestCase;
use OCA\Activity\UserSettings;
use OCA\Theming\ThemingDefaults;
use OCP\Activity\IManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserSession;
use OCP\Util;
use PHPUnit\Framework\MockObject\MockObject;

class FeedControllerTest extends TestCase {
	/** @var IConfig|MockObject */
	protected $config;

	/** @var IRequest|MockObject */
	protected $request;

	/** @var Data|MockObject */
	protected $data;

	/** @var GroupHelper|MockObject */
	protected $helper;

	/** @var UserSettings|MockObject */
	protected $userSettings;

	/** @var IManager|MockObject */
	protected $manager;

	/** @var IUserSession|MockObject */
	protected $session;

	/** @var \OCP\IL10N */
	protected $l10n;

	/** @var ThemingDefaults|MockObject */
	protected $themingDefault;

	/** @var FeedController */
	protected $controller;

	protected function setUp(): void {
		parent::setUp();

		$this->data = $this->createMock(Data::class);
		$this->helper = $this->createMock(GroupHelper::class);
		$this->userSettings = $this->createMock(UserSettings::class);

		$this->config = $this->createMock(IConfig::class);
		$this->request = $this->createMock(IRequest::class);
		$this->session = $this->createMock(IUserSession::class);
		$this->manager = $this->createMock(IManager::class);
		$this->themingDefault = $this->createMock(ThemingDefaults::class);

		/** @var IURLGenerator|MockObject $urlGenerator */
		$urlGenerator = $this->createMock(IURLGenerator::class);

		$this->controller = new FeedController(
			'activity',
			$this->request,
			$this->data,
			$this->helper,
			$this->userSettings,
			$urlGenerator,
			$this->manager,
			\OC::$server->getL10NFactory(),
			$this->config,
			$this->themingDefault,
		);
	}


	public function showData(): array {
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
	public function testShow(?string $acceptHeader, string $expectedHeader): void {
		$this->mockUserSession('test');
		$this->data->expects($this->any())
			->method('get')
			->willReturn(['data' => []]);
		if ($acceptHeader !== null) {
			$this->request->expects($this->any())
				->method('getHeader')
				->willReturn($acceptHeader);
		}

		$templateResponse = $this->controller->show();
		$this->assertInstanceOf(TemplateResponse::class, $templateResponse, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$headers = $templateResponse->getHeaders();
		$this->assertArrayHasKey('Content-Type', $headers);
		$this->assertEquals($expectedHeader, $headers['Content-Type']);

		$renderedResponse = $templateResponse->render();
		$this->assertNotEmpty($renderedResponse);

		$l = Util::getL10N('activity');
		$description = $l->t('Your feed URL is invalid');
		$this->assertStringNotContainsString($description, $renderedResponse);
	}

	/**
	 * @dataProvider showData
	 *
	 * @param string $acceptHeader
	 * @param string $expectedHeader
	 */
	public function testShowNoToken(?string $acceptHeader, string $expectedHeader): void {
		$this->manager->expects($this->any())
			->method('getCurrentUserId')
			->willThrowException(new \UnexpectedValueException());
		if ($acceptHeader !== null) {
			$this->request->expects($this->any())
				->method('getHeader')
				->willReturn($acceptHeader);
		}

		$templateResponse = $this->controller->show();
		$this->assertInstanceOf(TemplateResponse::class, $templateResponse, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$headers = $templateResponse->getHeaders();
		$this->assertArrayHasKey('Content-Type', $headers);
		$this->assertEquals($expectedHeader, $headers['Content-Type']);

		$renderedResponse = $templateResponse->render();
		$this->assertNotEmpty($renderedResponse);

		$l = Util::getL10N('activity');
		$description = (string)$l->t('Your feed URL is invalid');
		$this->assertStringContainsString($description, $renderedResponse);
	}

	protected function mockUserSession(string $user): void {
		$mockUser = $this->createMock(IUser::class);
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
