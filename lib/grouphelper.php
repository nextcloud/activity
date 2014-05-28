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

class GroupHelper
{
	/** @var array */
	protected $activities = array();

	/** @var array */
	protected $openGroup = array();

	/** @var string */
	protected $groupKey = '';

	/** @var bool */
	protected $allowGrouping;

	public function __construct($allowGrouping) {
		$this->allowGrouping = $allowGrouping;
	}

	/**
	 * Add an activity to the internal array
	 *
	 * @param array $activity
	 */
	public function addActivity($activity) {
		$activity['subjectparams'] = unserialize($activity['subjectparams']);
		$activity['messageparams'] = unserialize($activity['messageparams']);

		if (!$this->allowGrouping) {
			$this->activities[] = $activity;
		} else {
			if ($this->getGroupKey($activity) && $this->getGroupKey($activity) === $this->groupKey) {
				$parameter = $this->getGroupParameter($activity);
				if ($parameter !== false) {
					if (!is_array($this->openGroup['subjectparams'][$parameter])) {
						$this->openGroup['subjectparams'][$parameter] = array($this->openGroup['subjectparams'][$parameter]);
					}
					$this->openGroup['subjectparams'][$parameter][] = $activity['subjectparams'][$parameter];
				}
			} else {
				if (!empty($this->openGroup)) {
					$this->activities[] = $this->openGroup;
				}

				$this->groupKey = $this->getGroupKey($activity);
				$this->openGroup = $activity;
			}
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
		return $activity['app'] . '|' . $activity['user'] . '|' . $activity['subject'];
	}

	protected function getGroupParameter($activity) {
		if (!$this->allowGrouping) {
			return false;
		}

		if ($activity['app'] === 'files') {
			switch ($activity['subject']) {
				case 'created_self':
				case 'created_by':
				case 'changed_self':
				case 'changed_by':
				case 'deleted_self':
				case 'deleted_by':
					return 0;
			}
		}
		return false;
	}

	/**
	 * Get the prepared activities
	 *
	 * @return array translated activities ready for use
	 */
	public function getActivities() {
		if (!empty($this->openGroup)) {
			$this->activities[] = $this->openGroup;
		}

		$return = array();
		foreach ($this->activities as $activity) {
			$activity['subject_short'] = DataHelper::translation($activity['app'], $activity['subject'], $activity['subjectparams'], true);
			$activity['message_short'] = DataHelper::translation($activity['app'], $activity['message'], $activity['messageparams'], true);

			$activity['subject_long'] = DataHelper::translation($activity['app'], $activity['subject'], $activity['subjectparams']);
			$activity['message_long'] = DataHelper::translation($activity['app'], $activity['message'], $activity['messageparams']);

			$return[] = $activity;
		}

		return $return;
	}
}
