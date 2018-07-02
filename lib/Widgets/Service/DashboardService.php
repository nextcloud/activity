<?php
/**
 * Nextcloud - Activity Widget for Dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
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

namespace OCA\Activity\Widgets\Service;

use OCA\Dashboard\Api\v1\Dashboard;
use OCA\Dashboard\Service\MiscService;
use OCP\Activity\IEvent;
use OCP\App\IAppManager;
use OCP\AppFramework\QueryException;

class DashboardService {

	/** @var string */
	private $userId;

	/** @var IAppManager */
	private $appManager;

	/** @var FilesActivityService */
	private $filesActivityService;


	/**
	 * ProviderService constructor.
	 *
	 * @param string $userId
	 * @param IAppManager $appManager
	 * @param FilesActivityService $filesActivityService
	 */
	public function __construct(
		$userId, IAppManager $appManager, FilesActivityService $filesActivityService
	) {
		$this->userId = $userId;
		$this->appManager = $appManager;
		$this->filesActivityService = $filesActivityService;
	}


	/**
	 * @param IEvent $event
	 */
	public function dispatchDashboardEvent(IEvent $event) {
		if (!$this->appManager->isInstalled('dashboard')) {
			return;
		}

		if ($event->getApp() === 'files' || $event->getApp() === 'files_sharing') {
			$this->createDashboardEventFromFiles($event->getAffectedUser());

			return;
		}

	}


	/**
	 * @param string $userId
	 */
	private function createDashboardEventFromFiles($userId) {
		$activities = $this->filesActivityService->getFilesActivities();
		try {
			Dashboard::createEvent($userId, 'activity_files', ['filesActivity' => $activities]);
		} catch (QueryException $e) {
		}
	}

}