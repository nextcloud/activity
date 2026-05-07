<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
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
use OCP\Notification\IManager as INotificationManager;

class APIv2Controller extends OCSController {
	protected string $filter = 'all';
	protected int $since = 0;
	protected int $limit = 50;
	protected string $sort = 'desc';
	protected string $objectType = '';
	protected int $objectId = 0;
	protected string $user = '';
	protected bool $loadPreviews = false;

	public function __construct(
		$appName,
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
		protected INotificationManager $notificationManager,
	) {
		parent::__construct($appName, $request);
		$this->activityManager = $activityManager;
	}

	/**
	 * @throws InvalidFilterException when the filter is invalid
	 * @throws \OutOfBoundsException when no user is given
	 */
	protected function validateParameters(string $filter, int $since, int $limit, bool $previews, string $objectType, int $objectId, string $sort): void {
		$this->filter = \is_string($filter) ? $filter : 'all';
		if ($this->filter !== $this->data->validateFilter($this->filter)) {
			throw new InvalidFilterException('Invalid filter');
		}
		$this->since = (int)$since;
		$this->limit = (int)$limit;
		$this->loadPreviews = (bool)$previews;
		$this->objectType = (string)$objectType;
		$this->objectId = (int)$objectId;
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
	 */
	public function getDefault(int $since = 0, int $limit = 50, bool $previews = false, string $object_type = '', int $object_id = 0, string $sort = 'desc'): DataResponse {
		return $this->get('all', $since, $limit, $previews, $object_type, $object_id, $sort);
	}

	/**
	 * @NoAdminRequired
	 */
	public function getFilter(string $filter, int $since = 0, int $limit = 50, bool $previews = false, string $object_type = '', int $object_id = 0, string $sort = 'desc'): DataResponse {
		return $this->get($filter, $since, $limit, $previews, $object_type, $object_id, $sort);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param string $object_type Object type to count downloads for (must be 'files')
	 * @param int $object_id File ID
	 * @return DataResponse
	 */
	public function getDownloadCount(string $object_type = 'files', int $object_id = 0): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			return new DataResponse([], Http::STATUS_FORBIDDEN);
		}

		if ($object_type !== 'files' || $object_id <= 0) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		$total = $this->data->countDownloads($user->getUID(), $object_id);
		$last30d = $this->data->countDownloads($user->getUID(), $object_id, time() - 30 * 24 * 3600);

		return new DataResponse(['total' => $total, 'last30d' => $last30d]);
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

	protected function get(string $filter, int $since, int $limit, bool $previews, string $filterObjectType, int $filterObjectId, string $sort): DataResponse {
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
							if (((int)$objectId) === 0 || $objectName === '') {
								// No file, no preview
								continue;
							}

							$activity['previews'][] = $this->getPreview($activity['affecteduser'], (int)$objectId, $objectName);
						}
					} elseif ($activity['object_id']) {
						$activity['previews'][] = $this->getPreview($activity['affecteduser'], (int)$activity['object_id'], $activity['object_name']);
					}
				}
			}

			unset($activity['affecteduser']);
			$preparedActivities[] = $activity;
		}

		// When viewing activities for a specific file, mark corresponding activity
		// notifications as processed so they clear from the notification bell
		if ($this->objectType !== '' && $this->objectId !== 0 && !empty($this->user)) {
			$deferred = $this->notificationManager->defer();
			foreach ($preparedActivities as $activity) {
				$notification = $this->notificationManager->createNotification();
				$notification->setApp($activity['app'] ?? 'activity')
					->setUser($this->user)
					->setObject('activity_notification', (string)$activity['activity_id']);
				$this->notificationManager->markProcessed($notification);
			}
			if ($deferred) {
				$this->notificationManager->flush();
			}
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
