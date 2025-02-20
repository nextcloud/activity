<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Activity\Controller;

use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCA\Theming\ThemingDefaults;
use OCP\Activity\IManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\L10N\IFactory;

class FeedController extends Controller {

	public const DEFAULT_PAGE_SIZE = 30;
	protected IL10N $l;

	public function __construct(
		string $appName,
		IRequest $request,
		protected Data $data,
		protected GroupHelper $helper,
		protected UserSettings $settings,
		protected IURLGenerator $urlGenerator,
		protected IManager $activityManager,
		protected IFactory $l10nFactory,
		protected IConfig $config,
		protected ThemingDefaults $themingDefaults,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 */
	public function show() {
		try {
			$user = $this->activityManager->getCurrentUserId();

			$userLang = $this->config->getUserValue($user, 'core', 'lang');

			// Overwrite user and language in the helper
			$this->l = $this->l10nFactory->get('activity', $userLang);
			$this->helper->setL10n($this->l);

			$description = $this->l->t('Personal activity feed for %s', $user);
			$response = $this->data->get($this->helper, $this->settings, $user, 0, self::DEFAULT_PAGE_SIZE, 'desc', 'all');
			$activities = $response['data'];
		} catch (\UnexpectedValueException $e) {
			$this->l = $this->l10nFactory->get('activity');
			$description = $this->l->t('Your feed URL is invalid');

			$activities = [
				[
					'activity_id' => -1,
					'timestamp' => time(),
					'subject' => true,
					'subject_prepared' => $description,
				]
			];
		}

		$title = $this->themingDefaults->getTitle();
		$response = new TemplateResponse('activity', 'rss', [
			'rssLang' => $this->l->getLanguageCode(),
			'rssLink' => $this->urlGenerator->linkToRouteAbsolute('activity.Feed.show'),
			'rssPubDate' => date('r'),
			'description' => $description,
			'title' => $title !== '' ? $this->l->t('Activity feed for %1$s', [$title]) : $this->l->t('Activity feed'),
			'activities' => $activities,
		], '');

		if (stristr($this->request->getHeader('accept'), 'application/rss+xml')) {
			$response->addHeader('Content-Type', 'application/rss+xml');
		} else {
			$response->addHeader('Content-Type', 'text/xml; charset=UTF-8');
		}

		return $response;
	}
}
