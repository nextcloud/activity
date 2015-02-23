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
use OCA\Activity\Navigation;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IUserSession;

class Feed extends Controller {
	const DEFAULT_PAGE_SIZE = 30;

	/** @var \OCA\Activity\Data */
	protected $data;

	/** @var \OCA\Activity\GroupHelper */
	protected $helper;

	/** @var \OCA\Activity\Navigation */
	protected $navigation;

	/** @var \OCA\Activity\UserSettings */
	protected $settings;

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
	 * @param Navigation $navigation
	 * @param UserSettings $settings
	 * @param IUserSession $session
	 * @param IConfig $config
	 * @param string $user
	 */
	public function __construct($appName,
								IRequest $request,
								Data $data,
								GroupHelper $helper,
								Navigation $navigation,
								UserSettings $settings,
								IUserSession $session,
								IConfig $config,
								$user) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->helper = $helper;
		$this->navigation = $navigation;
		$this->settings = $settings;
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
		$response = new TemplateResponse('activity', 'rss', [
			'rssLang'		=> \OC::$server->getConfig()->getUserValue($this->getUser(), 'core', 'lang'),
			'rssLink'		=> \OC::$server->getURLGenerator()->getAbsoluteURL(
				\OC::$server->getURLGenerator()->linkToRoute('activity.rss')
			),
			'rssPubDate'	=> date('r'),
			'user'			=> $this->getUser(),
			// TODO we need to inject the user here :/
			'activity'		=> $this->data->read($this->helper, $this->settings, 0, self::DEFAULT_PAGE_SIZE, 'all')
		], '');

		if ($this->request->getHeader('accept') !== null && stristr($this->request->getHeader('accept'), 'application/rss+xml')) {
			$response->addHeader('Content-Type', 'application/rss+xml');
		} else {
			$response->addHeader('Content-Type', 'text/xml; charset=UTF-8');
		}

		return $response;
	}

	protected function getUser() {
		if (!$this->session->isLoggedIn()) {
			return $this->getUserFromToken();
		} else {
			return $this->session->getUser()->getUID();
		}
	}

	protected function getUserFromToken() {
		if ($this->tokenUser === null) {
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
			$this->tokenUser = array_shift($users);
		}

		return $this->tokenUser;
	}
}
