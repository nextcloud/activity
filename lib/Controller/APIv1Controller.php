<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Controller;

use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IDBConnection;
use OCP\IRequest;

class APIv1Controller extends OCSController {
	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param Data $data
	 * @param GroupHelper $groupHelper
	 * @param UserSettings $userSettings
	 * @param CurrentUser $currentUser
	 */
	public function __construct(
		$appName,
		IRequest $request,
		protected Data $data,
		protected GroupHelper $groupHelper,
		protected UserSettings $userSettings,
		protected CurrentUser $currentUser,
		protected IDBConnection $dbConnection,
	) {
		parent::__construct($appName, $request);
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
		$query = $this->dbConnection->getQueryBuilder();
		$query->select('activity_id')
			->from('activity')
			->where($query->expr()->eq('affecteduser', $query->createNamedParameter($this->currentUser->getUID())))
			->orderBy('activity_id', 'desc')
			->setFirstResult($offset - 1)
			->setMaxResults(1);

		$result = $query->executeQuery();
		$row = $result->fetch();
		$result->closeCursor();

		if ($row) {
			return (int)$row['activity_id'];
		}

		return 0;
	}
}
