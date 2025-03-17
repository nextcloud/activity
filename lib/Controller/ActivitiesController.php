<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Activity\Controller;

use OCA\Activity\Data;
use OCA\Activity\Event\LoadAdditionalScriptsEvent;
use OCA\Viewer\Event\LoadViewer;
use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IURLGenerator;

class ActivitiesController extends Controller {

	public function __construct(
		string $appName,
		IRequest $request,
		private ?string $userId,
		private IConfig $config,
		private Data $data,
		private IL10N $l10n,
		private IEventDispatcher $eventDispatcher,
		private IInitialState $initialState,
		private IURLGenerator $urlGenerator,
		private IManager $activityManager,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $filter
	 * @return TemplateResponse
	 */
	public function index(): TemplateResponse {
		return $this->showList('all');
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $filter
	 * @return TemplateResponse
	 */
	public function showList(string $filter = 'all'): TemplateResponse {
		$filter = $this->data->validateFilter($filter);

		$event = new LoadAdditionalScriptsEvent($filter);
		$this->eventDispatcher->dispatchTyped($event);
		$this->eventDispatcher->dispatch(LoadAdditionalScriptsEvent::EVENT_ENTITY, $event);

		// Load the viewer
		if (class_exists(LoadViewer::class)) {
			$this->eventDispatcher->dispatchTyped(new LoadViewer());
		}

		$this->initialState->provideInitialState('settings', [
			'enableAvatars' => $this->config->getSystemValue('enable_avatars', true),
			'personalSettingsLink' => $this->getPersonalSettingsLink(),
			'rssLink' => $this->getRSSLink(),
		]);
		$this->initialState->provideInitialState('filter', $filter);
		$this->initialState->provideInitialState('navigationList', $this->getLinkList());

		\OCP\Util::addScript($this->appName, 'activity-app');
		\OCP\Util::addStyle($this->appName, 'style');

		return new TemplateResponse($this->appName, 'app-main');
	}

	/**
	 * Get link for personal settings
	 */
	protected function getPersonalSettingsLink(): string {
		return $this->urlGenerator->linkToRouteAbsolute('settings.PersonalSettings.index', ['section' => 'notifications']);
	}

	/**
	 * Link to RSS feed if there is a RSS token, empty string otherwise
	 */
	protected function getRSSLink(): string {
		$rssToken = $this->config->getUserValue($this->userId, 'activity', 'rsstoken');
		if ($rssToken) {
			return $this->urlGenerator->linkToRouteAbsolute('activity.Feed.show', ['token' => $rssToken]);
		} else {
			return '';
		}
	}

	/**
	 * Get all items for the users we want to send an email to
	 *
	 * @return array Notification data (user => array of rows from the table)
	 */
	protected function getLinkList(): array {
		$filters = $this->activityManager->getFilters();
		usort($filters, static function (IFilter $a, IFilter $b) {
			if ($a->getPriority() === $b->getPriority()) {
				return (int)($a->getIdentifier() > $b->getIdentifier());
			}

			return (int)($a->getPriority() > $b->getPriority());
		});

		$entries = [];
		foreach ($filters as $filter) {
			$entries[] = [
				'id' => $filter->getIdentifier(),
				'icon' => $filter->getIcon(),
				'name' => $filter->getName(),
				'url' => $this->urlGenerator->linkToRoute('activity.Activities.showList', ['filter' => $filter->getIdentifier()]),
			];
		}

		return $entries;
	}
}
