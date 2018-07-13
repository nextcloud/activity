<?php declare(strict_types=1);


/**
 * Nextcloud - Activity Widget for Dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2018, Maxence Lange <maxence@artificial-owl.com>
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

use OCA\Activity\Widgets\FilesActivityWidget;
use OCP\Activity\IEvent;
use OCP\App\IAppManager;

class DashboardService {

	/** @var string */
	private $userId;

	/** @var IAppManager */
	private $appManager;


	/**
	 * DashboardService constructor.
	 *
	 * @param string $userId
	 * @param IAppManager $appManager
	 */
	public function __construct($userId, IAppManager $appManager) {
		$this->userId = $userId;
		$this->appManager = $appManager;
	}


	/**
	 * @param IEvent $event
	 */
	public function dispatchDashboardEvent(IEvent $event) {
		if (!$this->appManager->isInstalled('dashboard')
			|| !class_exists('OCA\Dashboard\Api\v1\Dashboard')) {
			return;
		}

		if ($event->getApp() === 'files' || $event->getApp() === 'files_sharing') {
			OCA\Dashboard\Api\v1\Dashboard::createUserEvent(
				FilesActivityWidget::WIDGET_ID, $event->getAffectedUser(),
				['filesActivity' => 'refresh']
			);
		}
	}

}