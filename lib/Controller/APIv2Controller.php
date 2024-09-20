<?php

declare(strict_types=1);
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

class APIv2Controller extends OCSController {
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

	public function __construct($appName,
		IRequest $request,
		protected IManager $activityManager,
		protected Data $data,
		protected GroupHelper $helper,
		protected UserSettings $settings,
		protected IURLGenerator $urlGenerator,
		protected IUserSession $userSession,
		protected IPreview $preview,
		protected IMimeTypeDetector $mimeTypeDetector,
		protected ViewInfoCache $infoCache,
	) {
		parent::__construct($appName, $request);
		$this->activityManager = $activityManager;
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
		$this->filter = \is_string($filter) ? $filter : 'all';
		if ($this->filter !== $this->data->validateFilter($this->filter)) {
			throw new InvalidFilterException('Invalid filter');
		}
		$this->since = (int) $since;
		$this->limit = (int) $limit;
		$this->loadPreviews = (bool) $previews;
		$this->objectType = (string) $objectType;
		$this->objectId = (int) $objectId;
		$this->sort = \in_array($sort, ['asc', 'desc'], true) ? $sort : 'desc';

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
			throw new \OutOfBoundsException('Not logged in');
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
	public function getDefault($since = 0, $limit = 50, $previews = false, $object_type = '', $object_id = 0, $sort = 'desc'): DataResponse {
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
	public function getFilter($filter, $since = 0, $limit = 50, $previews = false, $object_type = '', $object_id = 0, $sort = 'desc'): DataResponse {
		return $this->get($filter, $since, $limit, $previews, $object_type, $object_id, $sort);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @return DataResponse
	 */
	public function listFilters(): DataResponse {
		$filters = $this->activityManager->getFilters();

		$filters = array_map(function (IFilter $filter) {
			return [
				'id' => $filter->getIdentifier(),
				'name' => $filter->getName(),
				'icon' => $filter->getIcon(),
				'priority' => $filter->getPriority(),
			];
		}, $filters);

		// php 5.6 has problems with usort and objects
		usort($filters, static function (array $a, array $b) {
			if ($a['priority'] === $b['priority']) {
				return ($a['id'] > $b['id']) ? 1 : -1;
			}

			return $a['priority'] - $b['priority'];
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
	protected function get($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort): DataResponse {
		try {
			$this->validateParameters($filter, $since, $limit, $previews, $filterObjectType, $filterObjectId, $sort);
		} catch (InvalidFilterException $e) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		} catch (\OutOfBoundsException $e) {
			return new DataResponse([], Http::STATUS_FORBIDDEN);
		}

		$this->activityManager->setRequirePNG(false);
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
			return new DataResponse([], Http::STATUS_FORBIDDEN);
		} catch (\BadMethodCallException $e) {
			// No activity settings enabled
			return new DataResponse([], Http::STATUS_NO_CONTENT);
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
					if (!empty($activity['objects']) && \is_array($activity['objects'])) {
						foreach ($activity['objects'] as $objectId => $objectName) {
							if (((int) $objectId) === 0 || $objectName === '') {
								// No file, no preview
								continue;
							}

							$activity['previews'][] = $this->getPreview($activity['affecteduser'], (int) $objectId, $objectName);
						}
					} elseif ($activity['object_id']) {
						$activity['previews'][] = $this->getPreview($activity['affecteduser'], (int) $activity['object_id'], $activity['object_name']);
					}
				}
			}

			unset($activity['affecteduser']);
			$preparedActivities[] = $activity;
		}

		return new DataResponse($preparedActivities, Http::STATUS_OK, $headers);
	}

	protected function generateHeaders(array $headers, bool $hasMoreActivities, array $data): array {
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

	protected function getPreview(string $owner, int $fileId, string $filePath): array {
		$info = $this->infoCache->getInfoById($owner, $fileId, $filePath);

		if (!$info['exists'] || $info['view'] !== '') {
			return $this->getPreviewFromPath($fileId, $filePath, $info);
		}

		$preview = [
			'link' => $this->urlGenerator->linkToRouteAbsolute('files.viewcontroller.showFile', ['fileid' => $fileId]),
			'source' => '',
			'mimeType' => 'application/octet-stream',
			'isMimeTypeIcon' => true,
			'fileId' => $fileId,
			'view' => 'files',
			'filename' => basename($filePath),
		];

		// show a preview image if the file still exists
		if ($info['is_dir']) {
			$preview['source'] = $this->getPreviewPathFromMimeType('dir');
			$preview['mimeType'] = 'dir';
		} else {
			$fileInfo = $info['node'] ?? null;
			if (!($fileInfo instanceof FileInfo)) {
				return $this->getPreviewFromPath($fileId, $filePath, $info);
			}

			$preview['filePath'] = $fileInfo->getPath();
			if ($this->preview->isAvailable($fileInfo)) {
				$params = [
					'forceIcon' => 0,
					'a' => 0,
					'x' => 250,
					'y' => 250,
					'fileId' => $fileId,
					'c' => $fileInfo->getEtag(),
				];

				$preview['source'] = $this->urlGenerator->linkToRouteAbsolute('core.Preview.getPreviewByFileId', $params);
				$preview['mimeType'] = $fileInfo->getMimetype() ?: 'application/octet-stream';
				$preview['isMimeTypeIcon'] = false;
			} else {
				$preview['mimeType'] = $fileInfo->getMimetype() ?: 'application/octet-stream';
				$preview['source'] = $this->getPreviewPathFromMimeType($preview['mimeType']);
			}
		}

		return $preview;
	}

	protected function getPreviewFromPath(int $fileId, string $filePath, array $info): array {
		$mimeType = $info['is_dir'] ? 'dir' : $this->mimeTypeDetector->detectPath($filePath);
		return [
			'link' => $this->urlGenerator->linkToRouteAbsolute('files.viewcontroller.showFile', ['fileid' => $fileId]),
			'source' => $this->getPreviewPathFromMimeType($mimeType),
			'mimeType' => $mimeType,
			'isMimeTypeIcon' => true,
			'fileId' => $fileId,
			'view' => $info['view'] ?: 'files',
			'filename' => basename($filePath),
		];
	}

	protected function getPreviewPathFromMimeType(string $mimeType): string {
		$mimeTypeIcon = $this->mimeTypeDetector->mimeTypeIcon($mimeType);
		if (substr($mimeTypeIcon, -4) === '.png') {
			$mimeTypeIcon = substr($mimeTypeIcon, 0, -4) . '.svg';
		}

		return $this->urlGenerator->getAbsoluteURL($mimeTypeIcon);
	}
}
