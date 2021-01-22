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

namespace OCA\Activity\Controller;

use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
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

	/** @var \OCA\Activity\Data */
	protected $data;

	/** @var \OCA\Activity\GroupHelper */
	protected $helper;

	/** @var \OCA\Activity\UserSettings */
	protected $settings;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var IManager */
	protected $activityManager;

	/** @var IConfig */
	protected $config;

	/** @var IFactory */
	protected $l10nFactory;

	/** @var IL10N */
	protected $l;

	/**
	 * constructor of the controller
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param Data $data
	 * @param GroupHelper $helper
	 * @param UserSettings $settings
	 * @param IURLGenerator $urlGenerator
	 * @param IManager $activityManager
	 * @param IFactory $l10nFactory
	 * @param IConfig $config
	 */
	public function __construct($appName,
								IRequest $request,
								Data $data,
								GroupHelper $helper,
								UserSettings $settings,
								IURLGenerator $urlGenerator,
								IManager $activityManager,
								IFactory $l10nFactory,
								IConfig $config) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->helper = $helper;
		$this->settings = $settings;
		$this->urlGenerator = $urlGenerator;
		$this->activityManager = $activityManager;
		$this->l10nFactory = $l10nFactory;
		$this->config = $config;
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

		$response = new TemplateResponse('activity', 'rss', [
			'rssLang' => $this->l->getLanguageCode(),
			'rssLink' => $this->urlGenerator->linkToRouteAbsolute('activity.Feed.show'),
			'rssPubDate' => date('r'),
			'description' => $description,
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
