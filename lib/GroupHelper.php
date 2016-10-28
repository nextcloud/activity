<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity;

use OCA\Activity\Extension\LegacyParser;
use OCA\Activity\Parameter\IParameter;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\IL10N;

class GroupHelper {
	/** @var IEvent[] */
	protected $event = [];

	/** @var bool */
	protected $allowGrouping;

	/** @var \OCP\Activity\IManager */
	protected $activityManager;

	/** @var \OCA\Activity\DataHelper */
	protected $dataHelper;

	/**
	 * @param \OCP\Activity\IManager $activityManager
	 * @param \OCA\Activity\DataHelper $dataHelper
	 */
	public function __construct(IManager $activityManager, DataHelper $dataHelper) {
		$this->allowGrouping = true;

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
		$event = $this->arrayToEvent($activity);

		$parser = new LegacyParser($this->dataHelper);
		$this->activityManager->setFormattingObject($event->getObjectType(), $event->getObjectId());
		$event = $parser->parse($event);
		$this->activityManager->setFormattingObject('', 0);

		$this->event[] = $event;
	}

	/**
	 * Get the prepared activities
	 *
	 * @return array translated activities ready for use
	 */
	public function getActivities() {
		$return = [];
		foreach ($this->event as $event) {
			$return[] = $this->eventToArray($event);
		}
		$this->event = [];

		return $return;
	}

	/**
	 * @param array $row
	 * @return IEvent
	 */
	protected function arrayToEvent(array $row) {
		$event = $this->activityManager->generateEvent();
		$event->setApp($row['app'])
			->setType($row['type'])
			->setAffectedUser($row['affecteduser'])
			->setAuthor($row['user'])
			->setTimestamp((int) $row['timestamp'])
			->setSubject($row['subject'], $row['subjectparams'])
			->setMessage($row['message'], $row['messageparams'])
			->setObject($row['object_type'], (int) $row['object_id'], $row['file'])
			->setLink($row['link']);

		return $event;
	}

	/**
	 * @param IEvent $event
	 * @return array
	 */
	protected function eventToArray(IEvent $event) {
		return [
			'app' => $event->getApp(),
			'type' => $event->getType(),
			'affecteduser' => $event->getAffectedUser(),
			'user' => $event->getAuthor(),
			'timestamp' => $event->getTimestamp(),
			'subject' => $event->getParsedSubject(),
			'message' => $event->getParsedMessage(),
			'object_type' => $event->getObjectType(),
			'object_id' => $event->getObjectId(),
			'object_name' => $event->getObjectName(),
			'link' => $event->getLink(),
			'icon' => $event->getIcon(),
		];
	}
}
