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

namespace OCA\Activity\Controller;

use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUserSession;
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

	/** @var IUserSession */
	protected $session;

	/** @var IConfig */
	protected $config;

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
	 * @param IUserSession $session
	 * @param IConfig $config
	 * @param string $user
	 */
	public function __construct($appName,
								IRequest $request,
								Data $data,
								GroupHelper $helper,
								UserSettings $settings,
								IURLGenerator $urlGenerator,
								IUserSession $session,
								IConfig $config,
								$user) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->helper = $helper;
		$this->settings = $settings;
		$this->urlGenerator = $urlGenerator;
		$this->session = $session;
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
			$user = $this->getUser();

			$userLang = $this->config->getUserValue($user, 'core', 'lang');

			// Overwrite user and language in the helper
			$l = Util::getL10N('activity', $userLang);
			$l->forceLanguage($userLang);
			$this->helper->setL10n($l);
			$this->helper->setUser($user);

			$description = (string) $l->t('Personal activity feed for %s', $user);
			$activities = $this->data->read($this->helper, $this->settings, 0, self::DEFAULT_PAGE_SIZE, 'all', $user);
		} catch (\UnexpectedValueException $e) {
			$l = Util::getL10N('activity');
			$description = (string) $l->t('Your feed URL is invalid');

			$activities = [
				[
					'activity_id'	=> -1,
					'timestamp'		=> time(),
					'subject'		=> true,
					'subjectformatted'	=> [
						'full' => $description,
					],
				]
			];
		}

		$response = new TemplateResponse('activity', 'rss', [
			'rssLang'		=> $l->getLanguageCode(),
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

	/**
	 * Get the user we need to use
	 *
	 * Either the user is logged in, or we try to get it from the token
	 *
	 * @return string
	 * @throws \UnexpectedValueException If the token is invalid, does not exist or is not unique
	 */
	protected function getUser() {
		if (!$this->session->isLoggedIn()) {
			return $this->getUserFromToken();
		} else {
			return $this->session->getUser()->getUID();
		}
	}

	/**
	 * Get the user for the token
	 *
	 * @return string
	 * @throws \UnexpectedValueException If the token is invalid, does not exist or is not unique
	 */
	protected function getUserFromToken() {
		$token = (string) $this->request->getParam('token', '');
		if (strlen($token) !== 30) {
			throw new \UnexpectedValueException('The token is invalid');
		}

		$users = $this->config->getUsersForUserValue('activity', 'rsstoken', $token);

		if (sizeof($users) !== 1) {
			// No unique user found
			throw new \UnexpectedValueException('The token is invalid');
		}

		// Token found login as that user
		return array_shift($users);
	}
}
