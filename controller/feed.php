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
			$this->helper->setL10n($this->l);
			$this->helper->setUser($user);

			$description = (string) $this->l->t('Personal activity feed for %s', $user);
			$response = $this->data->get($this->helper, $this->settings, $user, 0, self::DEFAULT_PAGE_SIZE, 'desc', 'all');
			$data = $response['data'];

			$activities = [];
			foreach ($data as $activity) {
				$activity['subject_prepared'] = $this->parseMessage($activity['subject_prepared']);
				$activity['message_prepared'] = $this->parseMessage($activity['message_prepared']);
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

	/**
	 * Parse the parameters in the subject and message
	 *
	 * @param string $message
	 * @return string
	 */
	protected function parseMessage($message) {
		$message = $this->parseCollections($message);
		$message = $this->parseParameters($message);
		return $message;
	}

	/**
	 * Parse collections
	 *
	 * @param string $message
	 * @return string
	 */
	protected function parseCollections($message) {
		return preg_replace_callback('/<collection>(.*?)<\/collection>/', function($match) {
			$parameterList = explode('><', $match[1]);
			$parameterListLength = sizeof($parameterList);

			$parameters = [];
			for ($i = 0; $i < $parameterListLength; $i++) {
				$parameter = $parameterList[$i];
				if ($i > 0) {
					$parameter = '<' . $parameter;
				}
				if ($i + 1 < $parameterListLength) {
					$parameter = $parameter . '>';
				}

				$parameters[] = $this->parseParameters($parameter);
			}
			if ($parameterListLength === 1) {
				return array_pop($parameters);
			} else {
				$lastParameter = array_pop($parameters);
				return $this->l->t('%s and %s', [
					implode($this->l->t(', '), $parameters),
					$lastParameter,
				]);
			}
		}, $message);
	}

	/**
	 * Parse the parameters in the subject and message
	 *
	 * @param string $message
	 * @return string
	 */
	protected function parseParameters($message) {
		$message = $this->parseUntypedParameters($message);
		$message = $this->parseUserParameters($message);
		$message = $this->parseFederatedCloudIDParameters($message);
		$message = $this->parseFileParameters($message);
		return $message;
	}

	/**
	 * Display the parameter value
	 *
	 * @param string $message
	 * @return string
	 */
	protected function parseUntypedParameters($message) {
		return preg_replace_callback('/<parameter>(.*?)<\/parameter>/', function($match) {
			return $match[1];
		}, $message);
	}

	/**
	 * Display the users display name
	 *
	 * @param string $message
	 * @return string
	 */
	protected function parseUserParameters($message) {
		return preg_replace_callback('/<user\ display\-name=\"(.*?)\">(.*?)<\/user>/', function($match) {
			return $match[1];
		}, $message);
	}

	/**
	 * Display the full cloud id
	 *
	 * @param string $message
	 * @return string
	 */
	protected function parseFederatedCloudIDParameters($message) {
		return preg_replace_callback('/<federated-cloud-id\ display\-name=\"(.*?)\"\ user=\"(.*?)\"\ server=\"(.*?)\">(.*?)<\/federated-cloud-id>/', function($match) {
			return $match[1];
		}, $message);
	}

	/**
	 * Display the path for files
	 *
	 * @param string $message
	 * @return string
	 */
	protected function parseFileParameters($message) {
		return preg_replace_callback('/<file\ link=\"(.*?)\"\ id=\"(.*?)\">(.*?)<\/file>/', function($match) {
			return $match[3];
		}, $message);
	}
}
