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


use OC\Files\View;
use OCA\Activity\Data;
use OCA\Activity\Exception\InvalidFilterException;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCA\Activity\ViewInfoCache;
use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\Files\FileInfo;
use OCP\Files\IMimeTypeDetector;
use OCP\IPreview;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserSession;

class APIv2 extends OCSController {

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

	/** @var bool */
	protected $loadPreviews;

	/** @var IManager */
	protected $activityManager;

	/** @var Data */
	protected $data;

	/** @var GroupHelper */
	protected $helper;

	/** @var UserSettings */
	protected $settings;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var IUserSession */
	protected $userSession;

	/** @var IPreview */
	protected $preview;

	/** @var IMimeTypeDetector */
	protected $mimeTypeDetector;

	/** @var View */
	protected $view;

	/** @var ViewInfoCache */
	protected $infoCache;

	/**
	 * OCSEndPoint constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param IManager $activityManager
	 * @param Data $data
	 * @param GroupHelper $helper
	 * @param UserSettings $settings
	 * @param IURLGenerator $urlGenerator
	 * @param IUserSession $userSession
	 * @param IPreview $preview
	 * @param IMimeTypeDetector $mimeTypeDetector
	 * @param View $view
	 * @param ViewInfoCache $infoCache
	 */
	public function __construct($appName,
								IRequest $request,
								IManager $activityManager,
								Data $data,
								GroupHelper $helper,
								UserSettings $settings,
								IURLGenerator $urlGenerator,
								IUserSession $userSession,
								IPreview $preview,
								IMimeTypeDetector $mimeTypeDetector,
								View $view,
								ViewInfoCache $infoCache) {
		parent::__construct($appName, $request);
		$this->activityManager = $activityManager;
		$this->data = $data;
		$this->helper = $helper;
		$this->settings = $settings;
		$this->urlGenerator = $urlGenerator;
		$this->userSession = $userSession;
		$this->preview = $preview;
		$this->mimeTypeDetector = $mimeTypeDetector;
		$this->view = $view;
		$this->infoCache = $infoCache;
	}

	/**
	 * @param string $filter
	 * @param int $since
	 * @param int $limit
	 * @param bool $previews
	 * @param string $objectType
	 * @param int $objectId
	 * @param string $sort
	 * @throws InvalidFilterException when the filter is invalid
	 * @throws \OutOfBoundsException when no user is given
	 */
	protected function validateParameters($filter, $since, $limit, $previews, $objectType, $objectId, $sort) {
		$this->filter = is_string($filter) ? $filter : 'all';
		if ($this->filter !== $this->data->validateFilter($this->filter)) {
			throw new InvalidFilterException();
		}
		$this->since = (int) $since;
		$this->limit = (int) $limit;
		$this->loadPreviews = (bool) $previews;
		$this->objectType = (string) $objectType;
		$this->objectId = (int) $objectId;
		$this->sort = in_array($sort, ['asc', 'desc'], true) ? $sort : 'desc';

		if (($this->objectType !== '' && $this->objectId === 0) || ($this->objectType === '' && $this->objectId !== 0)) {
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
	 * @NoAdminRequired
	 *
	 * @param int $since
	 * @param int $limit
	 * @param bool $previews
	 * @param string $object_type
	 * @param int $object_id
	 * @param string $sort
	 * @return DataResponse
	 */
	public function getDefault($since = 0, $limit = 50, $previews = false, $object_type = '', $object_id = 0, $sort = 'desc') {
		return $this->get('all', $since, $limit, $previews, $object_type, $object_id, $sort);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param string $filter
	 * @param int $since
	 * @param int $limit
	 * @param bool $previews
	 * @param string $object_type
	 * @param int $object_id
	 * @param string $sort
	 * @return DataResponse
	 */
	public function getFilter($filter, $since = 0, $limit = 50, $previews = false, $object_type = '', $object_id = 0, $sort = 'desc') {
		return $this->get($filter, $since, $limit, $previews, $object_type, $object_id, $sort);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @return DataResponse
	 */
	public function listFilters() {
		$filters = $this->activityManager->getFilters();

		$filters = array_map(function(IFilter $filter) {
			return [
				'id' => $filter->getIdentifier(),
				'name' => $filter->getName(),
				'icon' => $filter->getIcon(),
				'priority' => $filter->getPriority(),
			];
		}, $filters);

		// php 5.6 has problems with usort and objects
		usort($filters, function(array $a, array $b) {
			if ($a['priority'] === $b['priority']) {
				return $a['id'] > $b['id'];
			}

			return $a['priority'] > $b['priority'];
		});

		return new DataResponse($filters);
	}

	/**
	 * @param string $filter
	 * @param int $since
	 * @param int $limit
	 * @param bool $previews
	 * @param string $filterObjectType
	 * @param int $filterObjectId
	 * @param string $sort
	 * @return DataResponse
	 */
	protected function get($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort) {
		try {
			$this->validateParameters($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);
		} catch (InvalidFilterException $e) {
			return new DataResponse(null, Http::STATUS_NOT_FOUND);
		} catch (\OutOfBoundsException $e) {
			return new DataResponse(null, Http::STATUS_FORBIDDEN);
		}

		$this->activityManager->setRequirePNG($this->request->isUserAgent([IRequest::USER_AGENT_CLIENT_IOS]));
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
			// Invalid since argument
			return new DataResponse(null, Http::STATUS_FORBIDDEN);
		} catch (\BadMethodCallException $e) {
			// No activity settings enabled
			return new DataResponse(null, Http::STATUS_NO_CONTENT);
		}
		$this->activityManager->setRequirePNG(false);

		$headers = $this->generateHeaders($response['headers'], $response['has_more'], $response['data']);
		if (empty($response['data']) || $this->request->getHeader('If-None-Match') === $headers['ETag']) {
			return new DataResponse([], Http::STATUS_NOT_MODIFIED, $headers);
		}

		$preparedActivities = [];
		foreach ($response['data'] as $activity) {
			$activity['datetime'] = date(\DateTime::ATOM, $activity['timestamp']);
			unset($activity['timestamp']);

			if ($this->loadPreviews) {
				$activity['previews'] = [];
				if ($activity['object_type'] === 'files') {
					if (!empty($activity['objects']) && is_array($activity['objects'])) {
						foreach ($activity['objects'] as $objectId => $objectName) {
							if (((int) $objectId) === 0 || $objectName === '') {
								// No file, no preview
								continue;
							}

							$activity['previews'][] = $this->getPreview($activity['affecteduser'], (int) $objectId, $objectName);
						}
					} else if ($activity['object_id']) {
						$activity['previews'][] = $this->getPreview($activity['affecteduser'], (int) $activity['object_id'], $activity['object_name']);
					}
				}
			}

			unset($activity['affecteduser']);
			$preparedActivities[] = $activity;
		}

		return new DataResponse($preparedActivities, Http::STATUS_OK, $headers);
	}

	/**
	 * @param array $headers
	 * @param bool $hasMoreActivities
	 * @param array $data
	 * @return array
	 */
	protected function generateHeaders(array $headers, $hasMoreActivities, array $data) {
		if ($hasMoreActivities && isset($headers['X-Activity-Last-Given'])) {
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

		$ids = [];
		foreach ($data as $activity) {
			$ids[] = $activity['activity_id'];
		}
		$headers['ETag'] = md5(json_encode($ids));

		return $headers;
	}

	/**
	 * @param string $owner
	 * @param int $fileId
	 * @param string $filePath
	 * @return array
	 */
	protected function getPreview($owner, $fileId, $filePath) {
		$info = $this->infoCache->getInfoById($owner, $fileId, $filePath);

		if (!$info['exists'] || $info['view'] !== '') {
			return $this->getPreviewFromPath($filePath, $info);
		}

		$preview = [
			'link'			=> $this->getPreviewLink($info['path'], $info['is_dir'], $info['view']),
			'source'		=> '',
			'isMimeTypeIcon' => true,
		];

		// show a preview image if the file still exists
		if ($info['is_dir']) {
			$preview['source'] = $this->getPreviewPathFromMimeType('dir');
		} else {
			$this->view->chroot('/' . $owner . '/files');
			$fileInfo = $this->view->getFileInfo($info['path']);
			if (!($fileInfo instanceof FileInfo)) {
				$pathPreview = $this->getPreviewFromPath($filePath, $info);
				$preview['source'] = $pathPreview['source'];
			} else if ($this->preview->isAvailable($fileInfo)) {
				$preview['isMimeTypeIcon'] = false;
				$preview['source'] = $this->urlGenerator->linkToRouteAbsolute('core.Preview.getPreview', [
					'file' => $info['path'],
					'c' => $this->view->getETag($info['path']),
					'x' => 150,
					'y' => 150,
				]);
			} else {
				$preview['source'] = $this->getPreviewPathFromMimeType($fileInfo->getMimetype());
			}
		}

		return $preview;
	}

	/**
	 * @param string $filePath
	 * @param array $info
	 * @return array
	 */
	protected function getPreviewFromPath($filePath, $info) {
		$mimeType = $info['is_dir'] ? 'dir' : $this->mimeTypeDetector->detectPath($filePath);
		$preview = [
			'link'			=> $this->getPreviewLink($info['path'], $info['is_dir'], $info['view']),
			'source'		=> $this->getPreviewPathFromMimeType($mimeType),
			'isMimeTypeIcon' => true,
		];

		return $preview;
	}

	/**
	 * @param string $mimeType
	 * @return string
	 */
	protected function getPreviewPathFromMimeType($mimeType) {
		$mimeTypeIcon = $this->mimeTypeDetector->mimeTypeIcon($mimeType);
		if (substr($mimeTypeIcon, -4) === '.png') {
			$mimeTypeIcon = substr($mimeTypeIcon, 0, -4) . '.svg';
		}

		return $this->urlGenerator->getAbsoluteURL($mimeTypeIcon);
	}

	/**
	 * @param string $path
	 * @param bool $isDir
	 * @param string $view
	 * @return string
	 */
	protected function getPreviewLink($path, $isDir, $view) {
		$params = [
			'dir' => $path,
		];
		if (!$isDir) {
			$params['dir'] = (substr_count($path, '/') === 1) ? '/' : dirname($path);
			$params['scrollto'] = basename($path);
		}
		if ($view !== '') {
			$params['view'] = $view;
		}
		return $this->urlGenerator->linkToRouteAbsolute('files.view.index', $params);
	}
}
