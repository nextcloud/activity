<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Controller;

use DateTimeInterface;
use OC\Files\Storage\Wrapper\Wrapper;
use OCA\Activity\ConfigLexicon;
use OCA\Activity\Exception\RemoteActivityException;
use OCA\Activity\Extension\Files;
use OCA\Federation\TrustedServers;
use OCA\Files_Sharing\External\Storage as ExternalStorage;
use OCP\Activity\IManager as IActivityManager;
use OCP\App\IAppManager;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\BruteForceProtection;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\AppFramework\Services\IAppConfig;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\Federation\ICloudIdManager;
use OCP\Files\InvalidPathException;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserManager;
use OCP\Server;
use Psr\Log\LoggerInterface;

class RemoteActivityController extends OCSController {
	public function __construct(
		$appName,
		IRequest $request,
		protected IDBConnection $db,
		protected IUserManager $userManager,
		protected IAppManager $appManager,
		protected IRootFolder $rootFolder,
		protected readonly IAppConfig $appConfig,
		protected IActivityManager $activityManager,
		protected ICloudIdManager $cloudIdManager,
		protected ITimeFactory $timeFactory,
		protected readonly LoggerInterface $logger,
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
	 */
	#[BruteForceProtection(action: 'receiveActivity')]
	public function receiveActivity($token, array $to, array $actor, $type, $updated, array $object = [], array $target = [], array $origin = []): DataResponse {
		if (!$this->appManager->isEnabledForAnyone('federatedfilesharing')) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		try {
			$this->manageActivity($token, $type, $to, $actor, $updated, $object, $target, $origin);
		} catch (RemoteActivityException $e) {
			$this->logger->warning('issue with data from a remote activity', ['exception' => $e]);
			$response = new DataResponse($e->getResponse(), $e->getCode());
			$response->throttle();
			return $response;
		}

		return new DataResponse();
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
	 */
	#[BruteForceProtection(action: 'receiveActivity')]
	public function receiveActivities(string $type, array $items = [], int $totalItems = 0): DataResponse {
		if (!$this->appManager->isEnabledForAnyone('federatedfilesharing')) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		if ($type !== 'Collection' || count($items) <> $totalItems) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		$this->manageCollection($items);
		return new DataResponse();
	}

	protected function manageActivity(
		string $token,
		string $type,
		array $to,
		array $actor,
		string $updated,
		array $object,
		array $target,
		array $origin = [],
	): void {
		if (!isset($to['type'], $to['name']) || $to['type'] !== 'Person') {
			throw new RemoteActivityException('issue with to/type entry', Http::STATUS_BAD_REQUEST);
		}

		$user = $this->userManager->get($to['name']);
		if (!$user instanceof IUser) {
			throw new RemoteActivityException('user not found', Http::STATUS_NOT_FOUND);
		}

		if (!isset($actor['type'], $actor['name']) || $actor['type'] !== 'Person') {
			throw new RemoteActivityException('issue with actor/type entry', Http::STATUS_BAD_REQUEST);
		}

		if ($user->getCloudId() === $actor['name']) {
			throw new RemoteActivityException('issue with actor/name entry', Http::STATUS_BAD_REQUEST);
		}

		if (isset($object['name']) && preg_match('/(^|\/)\.\.(\/|$)/', $object['name'])) {
			throw new RemoteActivityException('issue with object/name entry', Http::STATUS_BAD_REQUEST);
		}

		try {
			$resolved = $this->cloudIdManager->resolveCloudId($actor['name']);
			$actorServer = $resolved->getRemote();
			$actorUser = $resolved->getUser();
		} catch (\InvalidArgumentException) {
			throw new RemoteActivityException('issue with actor/name cloudId', Http::STATUS_BAD_REQUEST);
		}

		$internalType = $this->translateType($type);
		if ($internalType === '') {
			throw new RemoteActivityException('internal type', Http::STATUS_BAD_REQUEST);
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

		$date = \DateTime::createFromFormat(DateTimeInterface::W3C, $updated);
		if (!is_array($share) || str_starts_with($share['mountpoint'], '{{TemporaryMountPointName#')) {
			throw new RemoteActivityException('issue with share mountpoint', Http::STATUS_NOT_FOUND);
		}

		if ($date === false || abs($date->getTimestamp() - $this->timeFactory->getTime()) > $this->calculateTTL($share['remote'] ?? '')) {
			throw new RemoteActivityException('date/TTL is out of range', Http::STATUS_BAD_REQUEST);
		}

		$normalizedActorServer = rtrim(strtolower((string)preg_replace('/^https?:\/\//', '', $actorServer)), '/');
		$normalizedShareRemote = rtrim(strtolower(preg_replace('/^https?:\/\//', '', $share['remote'])), '/');
		if ($normalizedActorServer !== $normalizedShareRemote) {
			throw new RemoteActivityException('', Http::STATUS_FORBIDDEN);
		}

		$path2 = null;
		if ($type === 'Move') {
			if (!isset($target['type'], $target['name']) || $target['type'] !== 'Document') {
				throw new RemoteActivityException('issue with move/target/type', Http::STATUS_BAD_REQUEST);
			}

			if (!isset($origin['type'], $origin['name']) || $origin['type'] !== 'Document') {
				throw new RemoteActivityException('issue with move/origin/type', Http::STATUS_BAD_REQUEST);
			}

			$path = $share['mountpoint'] . $target['name'];
			$path2 = $share['mountpoint'] . $origin['name'];
		} else {
			if (!isset($object['type'], $object['name']) || $object['type'] !== 'Document') {
				throw new RemoteActivityException('issue with object/type', Http::STATUS_BAD_REQUEST);
			}
			$path = $share['mountpoint'] . $object['name'];
		}

		$subject = $this->getSubject($type, $path, $path2);
		if ($subject === '') {
			throw new RemoteActivityException('empty subject', Http::STATUS_BAD_REQUEST);
		}

		$userFolder = $this->rootFolder->getUserFolder($user->getUID());
		try {
			$node = $userFolder->get($path);
			$fileId = $node->getId();

			$storage = $node->getStorage();
			if (!$storage->instanceOfStorage(ExternalStorage::class)) {
				throw new RemoteActivityException('incompatible storage', Http::STATUS_FORBIDDEN);
			}
			while ($storage instanceof Wrapper) {
				$storage = $storage->getWrapperStorage();
			}
			if (!($storage instanceof ExternalStorage) || $storage->getToken() !== $token) {
				throw new RemoteActivityException('incompatible token', Http::STATUS_FORBIDDEN);
			}
		} catch (NotFoundException|InvalidPathException $e) {
			throw new RemoteActivityException('node not found', Http::STATUS_NOT_FOUND, $e);
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

		$this->logger->debug('generating new activity from remote instance', ['token' => $token, 'type' => $type, 'to' => $to, 'actor' => $actor, 'updated' => $updated, 'object' => $object, 'target' => $target, 'origin' => $origin]);
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
			throw new RemoteActivityException('activity', Http::STATUS_BAD_REQUEST, response: ['activity']);
		} catch (\BadMethodCallException) {
			throw new RemoteActivityException('sending', Http::STATUS_BAD_REQUEST, response: ['sending']);
		}
	}

	protected function manageCollection(array $collection): void {
		foreach ($collection as $item) {
			try {
				$this->manageActivity($item['share']['token'] ?? '', $item['type'] ?? [], $item['to'] ?? [], $item['actor'] ?? [], $item['updated'] ?? '', $item['object'] ?? [], $item['target'] ?? [], $item['origin'] ?? []);
			} catch (RemoteActivityException $e) {
				$this->logger->warning('issue with a collection item from a remote activity', ['exception' => $e]);
			}
		}
	}

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
		return match ($type) {
			'Create' => Files::TYPE_SHARE_CREATED,
			'Move', 'Update' => Files::TYPE_FILE_CHANGED,
			'Delete' => Files::TYPE_SHARE_DELETED,
			default => '',
		};
	}

	/**
	 * returns 600, or a higher value if the remote server is trusted
	 */
	protected function calculateTTL(string $remote): int {
		/** @psalm-suppress UndefinedClass \OCA\Federation\TrustedServers */
		if ($remote !== '' && $this->appManager->isEnabledForAnyone('federation') && class_exists(TrustedServers::class)) {
			$trustedServers = Server::get(TrustedServers::class);
			if ($trustedServers->isTrustedServer($remote)) {
				return $this->appConfig->getAppValueInt(ConfigLexicon::TRUSTED_SERVER_TTL);
			}
		}

		return 600;
	}
}
