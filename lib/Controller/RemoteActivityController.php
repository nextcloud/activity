<?php
/**
 * @copyright Copyright (c) 2017 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Controller;

use OCA\Activity\Extension\Files;
use OCP\Activity\IManager as IActivityManager;
use OCP\App\IAppManager;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\Files\InvalidPathException;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserManager;

class RemoteActivityController extends OCSController {
	public function __construct($appName,
		IRequest $request,
		protected IDBConnection $db,
		protected IUserManager $userManager,
		protected IAppManager $appManager,
		protected IRootFolder $rootFolder,
		protected IActivityManager $activityManager) {
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
	public function receiveActivity($token, array $to, array $actor, $type, $updated, array $object = [], array $target = [], array $origin = []) {
		$date = \DateTime::createFromFormat(\DateTime::W3C, $updated);
		if ($date === false) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}
		$time = $date->getTimestamp();

		if (!isset($to['type'], $to['name']) || $to['type'] !== 'Person') {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		$user = $this->userManager->get($to['name']);
		if (!$user instanceof IUser) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		if (!isset($actor['type'], $actor['name']) || $actor['type'] !== 'Person') {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		if ($user->getCloudId() === $actor['name']) {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
		}

		if (!$this->appManager->isInstalled('federatedfilesharing')) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from('share_external')
			->where($query->expr()->eq('share_token', $query->createNamedParameter($token)))
			->andWhere($query->expr()->eq('user', $query->createNamedParameter($user->getUID())));

		$result = $query->execute();
		$share = $result->fetch();
		$result->closeCursor();

		if (!is_array($share) || strpos($share['mountpoint'], '{{TemporaryMountPointName#') === 0) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		$internalType = $this->translateType($type);
		if ($internalType === '') {
			return new DataResponse([], Http::STATUS_BAD_REQUEST);
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
		} catch (NotFoundException $e) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		} catch (InvalidPathException $e) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		if ($path2 !== null) {
			$secondPath = [$fileId => $path2];
			if ($subject === 'moved_by') {
				try {
					$parent = $node->getParent();
					$secondPath = [$parent->getId() => dirname($path2)];
				} catch (NotFoundException $e) {
				} catch (InvalidPathException $e) {
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
				->setTimestamp($time);
			$this->activityManager->publish($event);
		} catch (\InvalidArgumentException $e) {
			return new DataResponse(['activity'], Http::STATUS_BAD_REQUEST);
		} catch (\BadMethodCallException $e) {
			return new DataResponse(['sending'], Http::STATUS_BAD_REQUEST);
		}

		return new DataResponse();
	}

	/**
	 * @param null|string $path2
	 */
	protected function getSubject(string $type, string $path, string|null $path2) {
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
