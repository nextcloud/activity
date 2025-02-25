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

use OCA\Activity\Controller\ActivitiesController;
use OCA\Activity\Data;
use OCA\Activity\Tests\TestCase;
use OCP\Activity\IManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Template;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ActivitiesTest
 *
 * @group DB
 * @package OCA\Activity\Tests\Controller
 */
class ActivitiesControllerTest extends TestCase {
	/** @var IRequest|MockObject */
	protected $request;
	/** @var IConfig|MockObject */
	protected $config;
	/** @var Data|MockObject */
	protected $data;
	/** @var IEventDispatcher|MockObject */
	protected $eventDispatcher;
	/** @var IL10N|MockObject */
	protected $l10n;
	/** @var IInitialState|MockObject */
	protected $initialState;
	/** @var IURLGenerator|MockObject */
	protected $urlGenerator;
	/** @var IManager|MockObject */
	protected $activityManager;

	/** @var ActivitiesController */
	protected $controller;

	protected function setUp(): void {
		parent::setUp();

		$this->config = $this->createMock(IConfig::class);
		$this->data = $this->createMock(Data::class);
		$this->eventDispatcher = $this->createMock(IEventDispatcher::class);
		$this->request = $this->createMock(IRequest::class);
		$this->l10n = $this->createMock(IL10N::class);
		$this->initialState = $this->createMock(IInitialState::class);
		$this->urlGenerator = $this->createMock(IURLGenerator::class);
		$this->activityManager = $this->createMock(IManager::class);

		$this->controller = $this->getController();
	}

	protected function getController(array $methods = []): ActivitiesController {
		if (empty($methods)) {
			return new ActivitiesController(
				'activity',
				$this->request,
				'some-user',
				$this->config,
				$this->data,
				$this->l10n,
				$this->eventDispatcher,
				$this->initialState,
				$this->urlGenerator,
				$this->activityManager,
			);
		}

		return $this->getMockBuilder(ActivitiesController::class)
			->setConstructorArgs([
				'activity',
				$this->request,
				'some-user',
				$this->config,
				$this->data,
				$this->l10n,
				$this->eventDispatcher,
				$this->initialState,
				$this->urlGenerator,
				$this->activityManager,
			])
			->onlyMethods($methods)
			->getMock();
	}

	public function testShowList(): void {
		$template = new Template('activity', 'stream.app.navigation', '');
		$template->assign('activeNavigation', 'all');
		$template->assign('navigations', []);
		$template->assign('rssLink', '');
		$template->assign('personalSettingsLink', '');

		$this->eventDispatcher->expects($this->once())
			->method('dispatch')
			->with('OCA\Activity::loadAdditionalScripts', $this->anything());

		$this->data->expects($this->any())
			->method('validateFilter')
			->with('all')
			->willReturn('all');

		$templateResponse = $this->controller->showList();
		$this->assertInstanceOf(TemplateResponse::class, $templateResponse, 'Asserting type of return is \OCP\AppFramework\Http\TemplateResponse');

		$renderedResponse = $templateResponse->render();
		$this->assertNotEmpty($renderedResponse);
	}
}
