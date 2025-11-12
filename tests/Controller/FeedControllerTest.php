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
use OCP\L10N\IFactory;
use OCP\Server;
use OCP\Util;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use UnexpectedValueException;

class FeedControllerTest extends TestCase {
	protected IConfig&MockObject $config;
	protected MockObject&IRequest $request;
	protected Data&MockObject $data;
	protected GroupHelper&MockObject $helper;
	protected UserSettings&MockObject $userSettings;
	protected IManager&MockObject $manager;
	protected IUserSession&MockObject $session;
	protected ThemingDefaults&MockObject $themingDefault;
	protected FeedController $controller;

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
		/** @var IURLGenerator&MockObject $urlGenerator */
		$urlGenerator = $this->createMock(IURLGenerator::class);

		$this->controller = new FeedController(
			'activity',
			$this->request,
			$this->data,
			$this->helper,
			$this->userSettings,
			$urlGenerator,
			$this->manager,
			Server::get(IFactory::class),
			$this->config,
			$this->themingDefault,
		);
	}


	public static function showData(): array {
		return [
			['application/rss+xml', 'application/rss+xml'],
			[null, 'text/xml; charset=UTF-8'],
		];
	}

	#[DataProvider('showData')]
	public function testShow(?string $acceptHeader, string $expectedHeader): void {
		$this->mockUserSession('test');
		$this->data
			->method('get')
			->willReturn(['data' => []]);
		if ($acceptHeader !== null) {
			$this->request
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

	#[DataProvider('showData')]
	public function testShowNoToken(?string $acceptHeader, string $expectedHeader): void {
		$this->manager
			->method('getCurrentUserId')
			->willThrowException(new UnexpectedValueException());
		if ($acceptHeader !== null) {
			$this->request
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
		$this->assertStringContainsString($description, $renderedResponse);
	}

	protected function mockUserSession(string $user): void {
		$mockUser = $this->createMock(IUser::class);
		$mockUser
			->method('getUID')
			->willReturn($user);

		$this->session
			->method('isLoggedIn')
			->willReturn(true);
		$this->session
			->method('getUser')
			->willReturn($mockUser);
		$this->manager
			->method('getCurrentUserId')
			->willReturn($user);
	}
}
