<?php
/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
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

use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

class APIv1Controller extends OCSController {
	/** @var Data */
	protected $data;

	/** @var GroupHelper */
	protected $groupHelper;

	/** @var UserSettings */
	protected $userSettings;

	/** @var CurrentUser */
	protected $currentUser;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param Data $data
	 * @param GroupHelper $groupHelper
	 * @param UserSettings $userSettings
	 * @param CurrentUser $currentUser
	 */
	public function __construct($appName,
								IRequest $request,
								Data $data,
								GroupHelper $groupHelper,
								UserSettings $userSettings,
								CurrentUser $currentUser) {
		parent::__construct($appName, $request);

		$this->data = $data;
		$this->userSettings = $userSettings;
		$this->groupHelper = $groupHelper;
		$this->currentUser = $currentUser;
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $start
	 * @param int $count
	 * @return DataResponse
	 */
	public function get($start = 0, $count = 30) {
		if ($start !== 0) {
			$start = $this->getSinceFromOffset($start);
		}

		$activities = $this->data->get(
			$this->groupHelper,
			$this->userSettings,
			$this->currentUser->getUID(), $start, $count, 'desc', 'all'
		);

		$entries = [];
		foreach ($activities['data'] as $entry) {
			$entries[] = [
				'id' => $entry['activity_id'],
				'subject' => $entry['subject'],
				'message' => $entry['message'],
				'file' => $entry['object_name'],
				'link' => $entry['link'],
				'date' => date('c', $entry['timestamp']),
			];
		}

		return new DataResponse($entries);
	}

	/**
	 * @param int $offset
	 * @return int
	 */
	protected function getSinceFromOffset($offset) {
		$query = \OC::$server->getDatabaseConnection()->getQueryBuilder();
		$query->select('activity_id')
			->from('activity')
			->where($query->expr()->eq('affecteduser', $query->createNamedParameter($this->currentUser->getUID())))
			->orderBy('activity_id', 'desc')
			->setFirstResult($offset - 1)
			->setMaxResults(1);

		$result = $query->execute();
		$row = $result->fetch();
		$result->closeCursor();

		if ($row) {
			return (int) $row['activity_id'];
		}

		return 0;
	}
}
