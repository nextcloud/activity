<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity;

use OCP\Activity\IManager;
use OCP\IL10N;

class GroupHelper
{
	/** @var array */
	protected $activities = array();

	/** @var array */
	protected $openGroup = array();

	/** @var string */
	protected $groupKey = '';

	/** @var int */
	protected $groupTime = 0;

	/** @var bool */
	protected $allowGrouping;

	/** @var \OCP\Activity\IManager */
	protected $activityManager;

	/** @var \OCA\Activity\DataHelper */
	protected $dataHelper;

	/**
	 * @param \OCP\Activity\IManager $activityManager
	 * @param \OCA\Activity\DataHelper $dataHelper
	 * @param bool $allowGrouping
	 */
	public function __construct(IManager $activityManager, DataHelper $dataHelper, $allowGrouping) {
		$this->allowGrouping = $allowGrouping;

		$this->activityManager = $activityManager;
		$this->dataHelper = $dataHelper;
	}

	/**
	 * @param string $user
	 */
	public function setUser($user) {
		$this->dataHelper->setUser($user);
	}

	/**
	 * @param IL10N $l
	 */
	public function setL10n(IL10N $l) {
		$this->dataHelper->setL10n($l);
	}

	/**
	 * Add an activity to the internal array
	 *
	 * @param array $activity
	 */
	public function addActivity($activity) {
		$activity['subjectparams_array'] = $this->dataHelper->getParameters($activity['subjectparams']);
		$activity['messageparams_array'] = $this->dataHelper->getParameters($activity['messageparams']);

		$groupKey = $this->getGroupKey($activity);
		if ($groupKey === false) {
			if (!empty($this->openGroup)) {
				$this->activities[] = $this->openGroup;
				$this->openGroup = array();
				$this->groupKey = '';
				$this->groupTime = 0;
			}
			$this->activities[] = $activity;
			return;
		}

		// Only group when the event has the same group key
		// and the time difference is not bigger than 3 days.
		if ($groupKey === $this->groupKey &&
			abs($activity['timestamp'] - $this->groupTime) < (3 * 24 * 60 * 60)
			&& (!isset($this->openGroup['activity_ids']) || sizeof($this->openGroup['activity_ids']) <= 5)
		) {
			$parameter = $this->getGroupParameter($activity);
			if ($parameter !== false) {
				if (!is_array($this->openGroup['subjectparams_array'][$parameter])) {
					$this->openGroup['subjectparams_array'][$parameter] = array($this->openGroup['subjectparams_array'][$parameter]);
				}
				if (!isset($this->openGroup['activity_ids'])) {
					$this->openGroup['activity_ids'] = [(int) $this->openGroup['activity_id']];
					$this->openGroup['files'] = [
						(int) $this->openGroup['object_id'] => (string) $this->openGroup['file']
					];
				}

				$this->openGroup['subjectparams_array'][$parameter][] = $activity['subjectparams_array'][$parameter];
				$this->openGroup['subjectparams_array'][$parameter] = array_unique($this->openGroup['subjectparams_array'][$parameter]);
				$this->openGroup['activity_ids'][] = (int) $activity['activity_id'];

				$this->openGroup['files'][(int) $activity['object_id']] = (string) $activity['file'];
			}
		} else {
			$this->closeOpenGroup();

			$this->groupKey = $groupKey;
			$this->groupTime = $activity['timestamp'];
			$this->openGroup = $activity;
		}
	}

	/**
	 * Closes the currently open group and adds it to the list of activities
	 */
	protected function closeOpenGroup() {
		if (!empty($this->openGroup)) {
			$this->activities[] = $this->openGroup;
		}
	}

	/**
	 * Get grouping key for an activity
	 *
	 * @param array $activity
	 * @return false|string False, if grouping is not allowed, grouping key otherwise
	 */
	protected function getGroupKey($activity) {
		if ($this->getGroupParameter($activity) === false) {
			return false;
		}

		// FIXME
		// Non-local users are currently not distinguishable, so grouping them might
		// remove the information how many different users performed the same action.
		// So we do not group them anymore, until we found another solution.
		if ($activity['user'] === '') {
			return false;
		}

		return $activity['app'] . '|' . $activity['user'] . '|' . $activity['subject'] . '|' . $activity['object_type'];
	}

	/**
	 * Get the parameter which is the varying part
	 *
	 * @param array $activity
	 * @return bool|int False if the activity should not be grouped, parameter position otherwise
	 */
	protected function getGroupParameter($activity) {
		if (!$this->allowGrouping) {
			return false;
		}

		// Allow other apps to group their notifications
		return $this->activityManager->getGroupParameter($activity);
	}

	/**
	 * Get the prepared activities
	 *
	 * @return array translated activities ready for use
	 */
	public function getActivities() {
		$this->closeOpenGroup();

		$return = array();
		foreach ($this->activities as $activity) {
			$this->activityManager->setFormattingObject($activity['object_type'], $activity['object_id']);
			$activity = $this->dataHelper->formatStrings($activity, 'subject');
			$activity = $this->dataHelper->formatStrings($activity, 'message');

			$activity['typeicon'] = $this->activityManager->getTypeIcon($activity['type']);
			$return[] = $activity;
		}
		$this->activityManager->setFormattingObject('', 0);

		return $return;
	}
}
