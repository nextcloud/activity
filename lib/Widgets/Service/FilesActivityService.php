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

use Exception;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCA\Activity\Widgets\Model\FilesActivity;

class FilesActivityService {

	/** @var string */
	private $userId;

	/** @var Data */
	private $data;

	/** @var GroupHelper */
	private $helper;

	/** @var UserSettings */
	private $settings;


	/**
	 * ProviderService constructor.
	 *
	 * @param string $userId
	 * @param Data $data
	 * @param GroupHelper $helper
	 * @param UserSettings $settings
	 */
	public function __construct($userId, Data $data, GroupHelper $helper, UserSettings $settings) {
		$this->userId = $userId;
		$this->data = $data;
		$this->helper = $helper;
		$this->settings = $settings;
	}


	/**
	 * @param int $limit
	 *
	 * @return FilesActivity[]
	 */
	public function getFilesActivities($limit) {

		try {
			$files = $this->data->get(
				$this->helper, $this->settings, $this->userId, '', $limit, 'desc', 'files'
			);

			$shared = $this->data->get(
				$this->helper, $this->settings, $this->userId, '', $limit, 'desc', 'files_sharing'
			);

			$activities = $this->parseActivities($files['data']);
			$activities = array_merge($activities, $this->parseActivities($shared['data']));

			$this->limitActivities($activities, $limit);

			return $activities;
		} catch (Exception $e) {
			\OC::$server->getLogger()
						->log(
							2,
							'FilesActivityService/getFilesActivity Exception: ' . $e->getMessage()
						);
		}

		return [];
	}


	public function defineLimit($height) {
		if ($height > 7) {
			return 20;
		}
		if ($height > 6) {
			return 15;
		}
		if ($height > 4) {
			return 10;
		}

		return 5;
	}


	/**
	 * @param array $activities
	 *
	 * @return FilesActivity[]
	 */
	private function parseActivities($activities) {
		$parsed = [];
		foreach ($activities as $activity) {
			$parsed[] = $this->parseActivity($activity);
		}

		return $parsed;
	}


	/**
	 * @param $activity
	 *
	 * @return FilesActivity
	 */
	private function parseActivity($activity) {
		$info = FilesActivity::fromData($activity);

		return $info;
	}


	/**
	 * @param array $activities
	 * @param int $limit
	 */
	private function limitActivities(&$activities, $limit) {
		usort($activities, [$this, 'sortActivitiesByTimestamp']);

		$activities = array_slice($activities, 0, $limit);
	}


	/**
	 * @param FilesActivity $a
	 * @param FilesActivity $b
	 *
	 * @return int
	 */
	private function sortActivitiesByTimestamp(FilesActivity $a, FilesActivity $b) {
		return ($b->getTimestamp() - $a->getTimestamp());
	}


}