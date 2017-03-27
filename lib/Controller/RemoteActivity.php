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

use OCP\App\IAppManager;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\Files\InvalidPathException;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\Activity\IManager as IActivityManager;
use OCP\IUser;
use OCP\IUserManager;

class RemoteActivity extends OCSController {

	/** @var IDBConnection */
	protected $db;

	/** @var IUserManager */
	protected $userManager;

	/** @var IAppManager */
	protected $appManager;

	/** @var IRootFolder */
	protected $rootFolder;

	/** @var IActivityManager */
	protected $activityManager;

	public function __construct($appName,
								IRequest $request,
								IDBConnection $db,
								IUserManager $userManager,
								IAppManager $appManager,
								IRootFolder $rootFolder,
								IActivityManager $activityManager) {
		parent::__construct($appName, $request);
		$this->db = $db;
		$this->userManager = $userManager;
		$this->appManager = $appManager;
		$this->rootFolder = $rootFolder;
		$this->activityManager = $activityManager;
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 *
	 * @param string $target
	 * @param string $token
	 * @param string $path
	 * @param string $type
	 * @param int $time
	 * @param string $subject
	 * @param string $actor
	 * @return DataResponse
	 */
	public function receiveActivity($target, $token, $path, $type, $time, $subject, $actor) {
		$user = $this->userManager->get($target);
		if (!$user instanceof IUser) {
			return new DataResponse(['user'], Http::STATUS_NOT_FOUND);
		}

		if (!$this->appManager->isInstalled('federatedfilesharing')) {
			return new DataResponse(['app'], Http::STATUS_NOT_FOUND);
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
			return new DataResponse(['share'], Http::STATUS_NOT_FOUND);
		}

		$path = $share['mountpoint'] . $path;

		$userFolder = $this->rootFolder->getUserFolder($user->getUID());
		try {
			$node = $userFolder->get($path);
			$fileId = $node->getId();

		} catch (NotFoundException $e) {
			return new DataResponse(['file'], Http::STATUS_NOT_FOUND);
		} catch (InvalidPathException $e) {
			return new DataResponse(['path'], Http::STATUS_NOT_FOUND);
		}

		$subjectParams = [[$fileId => $path]];
		if ($user->getCloudId() !== $actor) {
			$subjectParams[] = $actor;
		}

		$event = $this->activityManager->generateEvent();
		try {
			$event->setAffectedUser($user->getUID())
				->setApp('files')
				->setType($type)
				->setAuthor($actor)
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
}
