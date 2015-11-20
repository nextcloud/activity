<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
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
use OCA\Activity\Exception\InvalidFilterException;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Http;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserSession;

class OCSEndPoint {

	/** @var string */
	protected $filter;

	/** @var int */
	protected $since;

	/** @var int */
	protected $limit;

	/** @var string */
	protected $sort;

	/** @var string */
	protected $objectType;

	/** @var int */
	protected $objectId;

	/** @var string */
	protected $user;


	/** @var Data */
	protected $data;

	/** @var GroupHelper */
	protected $helper;

	/** @var UserSettings */
	protected $settings;

	/** @var IRequest */
	protected $request;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var IUserSession */
	protected $userSession;

	/**
	 * OCSEndPoint constructor.
	 *
	 * @param Data $data
	 * @param GroupHelper $helper
	 * @param UserSettings $settings
	 * @param IRequest $request
	 * @param IURLGenerator $urlGenerator
	 * @param IUserSession $userSession
	 */
	public function __construct(Data $data, GroupHelper $helper, UserSettings $settings, IRequest $request, IURLGenerator $urlGenerator, IUserSession $userSession) {
		$this->data = $data;
		$this->helper = $helper;
		$this->settings = $settings;
		$this->request = $request;
		$this->urlGenerator = $urlGenerator;
		$this->userSession = $userSession;
	}

	/**
	 * @param array $parameters
	 * @throws InvalidFilterException when the filter is invalid
	 * @throws \OutOfBoundsException when no user is given
	 */
	protected function readParameters(array $parameters) {
		$this->filter = isset($parameters['filter']) && is_string($parameters['filter']) ? (string) $parameters['filter'] : 'all';
		if ($this->filter !== $this->data->validateFilter($this->filter)) {
			throw new InvalidFilterException();
		}
		$this->since = (int) $this->request->getParam('since', 0);
		$this->limit = (int) $this->request->getParam('limit', 50);
		$this->objectType = (string) $this->request->getParam('object_type', '');
		$this->objectId = (int) $this->request->getParam('object_id', 0);
		$this->sort = (string) $this->request->getParam('sort', '');
		$this->sort = in_array($this->sort, ['asc', 'desc']) ? $this->sort : 'desc';

		if ($this->objectType !== '' && $this->objectId === 0 || $this->objectType === '' && $this->objectId !== 0) {
			// Only allowed together
			$this->objectType = '';
			$this->objectId = 0;
		}

		$user = $this->userSession->getUser();
		if ($user instanceof IUser) {
			$this->user = $user->getUID();
		} else {
			// No user logged in
			throw new \OutOfBoundsException();
		}
	}

	/**
	 * @param array $parameters
	 * @return \OC_OCS_Result
	 */
	public function getDefault(array $parameters) {
		return $this->get(array_merge($parameters, [
			'filter' => 'all',
		]));
	}

	/**
	 * @param array $parameters
	 * @return \OC_OCS_Result
	 */
	public function getFilter(array $parameters) {
		return $this->get($parameters);
	}

	/**
	 * @param array $parameters
	 * @return \OC_OCS_Result
	 */
	protected function get(array $parameters) {
		try {
			$this->readParameters($parameters);
		} catch (InvalidFilterException $e) {
			return new \OC_OCS_Result(null, Http::STATUS_NOT_FOUND);
		} catch (\OutOfBoundsException $e) {
			return new \OC_OCS_Result(null, Http::STATUS_FORBIDDEN);
		}

		try {
			$response = $this->data->get(
				$this->helper,
				$this->settings,
				$this->user,

				$this->since,
				$this->limit,
				$this->sort,

				$this->filter,
				$this->objectType,
				$this->objectId
			);
		} catch (\OutOfBoundsException $e) {
			return new \OC_OCS_Result(null, Http::STATUS_FORBIDDEN);
		}

		$headers = $this->generateHeaders($response['headers'], $response['has_more']);
		if (empty($response['data'])) {
			return new \OC_OCS_Result($response['data'], Http::STATUS_NOT_MODIFIED, null, $headers);
		}

		return new \OC_OCS_Result($response['data'], 100, null, $headers);
	}

	/**
	 * @param array $headers
	 * @param bool $hasMoreActivities
	 * @return array
	 */
	protected function generateHeaders(array $headers, $hasMoreActivities) {
		if ($hasMoreActivities) {
			// Set the "Link" header for the next page
			$nextPageParameters = [
				'since' => $headers['X-Activity-Last-Given'],
				'limit' => $this->limit,
				'sort' => $this->sort,
			];
			if ($this->objectType && $this->objectId) {
				$nextPageParameters['object_type'] = $this->objectType;
				$nextPageParameters['object_id'] = $this->objectId;
			}
			if ($this->request->getParam('format') !== null) {
				$nextPageParameters['format'] = $this->request->getParam('format');
			}

			$nextPage = $this->request->getServerProtocol(); # http
			$nextPage .= '://' . $this->request->getServerHost(); # localhost
			$nextPage .= $this->request->getScriptName(); # /ocs/v2.php
			$nextPage .= $this->request->getPathInfo(); # /apps/activity/api/v2/activity
			$nextPage .= '?' . http_build_query($nextPageParameters);
			$headers['Link'] = '<' . $nextPage . '>; rel="next"';
		}

		return $headers;
	}
}
