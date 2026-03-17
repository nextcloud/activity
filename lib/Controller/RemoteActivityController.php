<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Controller;

use DateTimeInterface;
use OC\Files\Storage\Wrapper\Wrapper;
use OCA\Activity\Extension\Files;
use OCA\Files_Sharing\External\Storage as ExternalStorage;
use OCP\Activity\IManager as IActivityManager;
use OCP\App\IAppManager;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\BruteForceProtection;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\Federation\ICloudIdManager;
use OCP\Files\InvalidPathException;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserManager;

class RemoteActivityController extends OCSController {
	public function __construct(
		$appName,
		IRequest $request,
		protected IDBConnection $db,
		protected IUserManager $userManager,
		protected IAppManager $appManager,
		protected IRootFolder $rootFolder,
		protected IActivityManager $activityManager,
		protected ICloudIdManager $cloudIdManager,
		protected ITimeFactory $timeFactory,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 *
	 * @param string $token
	 * @param string[] $to
	 * @param string[] $actor
	 * @param string $type
	 * @param string $updated
	 * @param string[] $object
	 * @param string[] $target
	 * @param string[] $origin
	 * @return DataResponse
	 */
	#[BruteForceProtection(action: 'receiveActivity')]
	public function receiveActivity($token, array $to, array $actor, $type, $updated, array $object = [], array $target = [], array $origin = []): DataResponse {
		if (!$this->appManager->isEnabledForAnyone('federatedfilesharing')) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		$date = \DateTime::createFromFormat(DateTimeInterface::W3C, $updated);
		if ($date === false || abs($date->getTimestamp() - $this->timeFactory->getTime()) > 600) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		if (!isset($to['type'], $to['name']) || $to['type'] !== 'Person') {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		$user = $this->userManager->get($to['name']);
		if (!$user instanceof IUser) {
			$response = new DataResponse([], Http::STATUS_NOT_FOUND);
			$response->throttle();
			return $response;
		}

		if (!isset($actor['type'], $actor['name']) || $actor['type'] !== 'Person') {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		if ($user->getCloudId() === $actor['name']) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		if (isset($object['name']) && preg_match('/(^|\/)\.\.(\/|$)/', $object['name'])) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		try {
			$resolved = $this->cloudIdManager->resolveCloudId($actor['name']);
			$actorServer = $resolved->getRemote();
			$actorUser = $resolved->getUser();
		} catch (\InvalidArgumentException) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		$internalType = $this->translateType($type);
		if ($internalType === '') {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from('share_external')
			->where($query->expr()->eq('share_token', $query->createNamedParameter($token)))
			->andWhere($query->expr()->eq('user', $query->createNamedParameter($user->getUID())))
			->andWhere($query->expr()->eq('owner', $query->createNamedParameter($actorUser)));

		$result = $query->executeQuery();
		$share = $result->fetch();
		$result->closeCursor();

		if (!is_array($share) || str_starts_with($share['mountpoint'], '{{TemporaryMountPointName#')) {
			$response = new DataResponse([], Http::STATUS_NOT_FOUND);
			$response->throttle();
			return $response;
		}

		$normalizedActorServer = rtrim(strtolower(preg_replace('/^https?:\/\//', '', $actorServer)), '/');
		$normalizedShareRemote = rtrim(strtolower(preg_replace('/^https?:\/\//', '', $share['remote'])), '/');
		if ($normalizedActorServer !== $normalizedShareRemote) {
			return new DataResponse([], Http::STATUS_FORBIDDEN);
		}

		$path2 = null;
		if ($type === 'Move') {
			if (!isset($target['type'], $target['name']) || $target['type'] !== 'Document') {
				return new DataResponse([], Http::STATUS_BAD_REQUEST);
			}

			if (!isset($origin['type'], $origin['name']) || $origin['type'] !== 'Document') {
				return new DataResponse([], Http::STATUS_BAD_REQUEST);
			}

			$path = $share['mountpoint'] . $target['name'];
			$path2 = $share['mountpoint'] . $origin['name'];
		} else {
			if (!isset($object['type'], $object['name']) || $object['type'] !== 'Document') {
				return new DataResponse([], Http::STATUS_BAD_REQUEST);
			}
			$path = $share['mountpoint'] . $object['name'];
		}

		$subject = $this->getSubject($type, $path, $path2);
		if ($subject === '') {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		$userFolder = $this->rootFolder->getUserFolder($user->getUID());
		try {
			$node = $userFolder->get($path);
			$fileId = $node->getId();

			$storage = $node->getStorage();
			if (!$storage->instanceOfStorage(ExternalStorage::class)) {
				$response = new DataResponse([], Http::STATUS_FORBIDDEN);
				$response->throttle();
				return $response;
			}
			while ($storage instanceof Wrapper) {
				$storage = $storage->getWrapperStorage();
			}
			if (!($storage instanceof ExternalStorage) || $storage->getToken() !== $token) {
				$response = new DataResponse([], Http::STATUS_FORBIDDEN);
				$response->throttle();
				return $response;
			}
		} catch (NotFoundException|InvalidPathException) {
			$response = new DataResponse([], Http::STATUS_NOT_FOUND);
			$response->throttle();
			return $response;
		}

		if ($path2 !== null) {
			$secondPath = [$fileId => $path2];
			if ($subject === 'moved_by') {
				try {
					$parent = $node->getParent();
					$secondPath = [$parent->getId() => dirname($path2)];
				} catch (NotFoundException|InvalidPathException) {
				}
			}
			$subjectParams = [$secondPath, $actor['name'], [$fileId => $path]];
		} else {
			$subjectParams = [[$fileId => $path], $actor['name']];
		}

		$event = $this->activityManager->generateEvent();
		try {
			$event->setAffectedUser($user->getUID())
				->setApp('files')
				->setType($internalType)
				->setAuthor($actor['name'])
				->setObject('files', $fileId, $path)
				->setSubject($subject, $subjectParams)
				->setTimestamp($date->getTimestamp());
			$this->activityManager->publish($event);
		} catch (\InvalidArgumentException) {
			return new DataResponse(['activity'], Http::STATUS_BAD_REQUEST);
		} catch (\BadMethodCallException) {
			return new DataResponse(['sending'], Http::STATUS_BAD_REQUEST);
		}

		return new DataResponse();
	}

	/**
	 * @param null|string $path2
	 */
	protected function getSubject(string $type, string $path, ?string $path2) {
		switch ($type) {
			case 'Create':
				return 'created_by';
			case 'Move':
				if ($path2 === null) {
					return '';
				}
				if (basename($path) === basename($path2)) {
					return 'moved_by';
				}
				return 'renamed_by';
			case 'Update':
				return 'changed_by';
			case 'Delete':
				return 'deleted_by';
		}
		return '';
	}

	/**
	 * @param string $type
	 * @return string
	 */
	protected function translateType($type) {
		switch ($type) {
			case 'Create':
				return Files::TYPE_SHARE_CREATED;
			case 'Move':
			case 'Update':
				return Files::TYPE_FILE_CHANGED;
			case 'Delete':
				return Files::TYPE_SHARE_DELETED;
		}
		return '';
	}
}
