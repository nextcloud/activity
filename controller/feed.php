<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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
use OCA\Activity\PlainTextParser;
use OCA\Activity\UserSettings;
use OCP\Activity\IManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\L10N\IFactory;
use OCP\Util;

class Feed extends Controller {
	const DEFAULT_PAGE_SIZE = 30;

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

	/** @var string */
	protected $user;

	/** @var string */
	protected $tokenUser;

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
	 * @param string $user
	 */
	public function __construct($appName,
								IRequest $request,
								Data $data,
								GroupHelper $helper,
								UserSettings $settings,
								IURLGenerator $urlGenerator,
								IManager $activityManager,
								IFactory $l10nFactory,
								IConfig $config,
								$user) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->helper = $helper;
		$this->settings = $settings;
		$this->urlGenerator = $urlGenerator;
		$this->activityManager = $activityManager;
		$this->l10nFactory = $l10nFactory;
		$this->config = $config;
		$this->user = $user;
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
			$parser = new PlainTextParser($this->l);
			$this->helper->setL10n($this->l);
			$this->helper->setUser($user);

			$description = (string) $this->l->t('Personal activity feed for %s', $user);
			$response = $this->data->get($this->helper, $this->settings, $user, 0, self::DEFAULT_PAGE_SIZE, 'desc', 'all');
			$data = $response['data'];

			$activities = [];
			foreach ($data as $activity) {
				$activity['subject_prepared'] = $parser->parseMessage($activity['subject_prepared']);
				$activity['message_prepared'] = $parser->parseMessage($activity['message_prepared']);
				$activities[] = $activity;
			}

		} catch (\UnexpectedValueException $e) {
			$this->l = $this->l10nFactory->get('activity');
			$description = (string) $this->l->t('Your feed URL is invalid');

			$activities = [
				[
					'activity_id'	=> -1,
					'timestamp'		=> time(),
					'subject'		=> true,
					'subject_prepared'	=> $description,
				]
			];
		}

		$response = new TemplateResponse('activity', 'rss', [
			'rssLang'		=> $this->l->getLanguageCode(),
			'rssLink'		=> $this->urlGenerator->linkToRouteAbsolute('activity.Feed.show'),
			'rssPubDate'	=> date('r'),
			'description'	=> $description,
			'activities'	=> $activities,
		], '');

		if ($this->request->getHeader('accept') !== null && stristr($this->request->getHeader('accept'), 'application/rss+xml')) {
			$response->addHeader('Content-Type', 'application/rss+xml');
		} else {
			$response->addHeader('Content-Type', 'text/xml; charset=UTF-8');
		}

		return $response;
	}
}
