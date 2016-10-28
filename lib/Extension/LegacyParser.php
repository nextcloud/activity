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

namespace OCA\Activity\Extension;


use OCA\Activity\DataHelper;
use OCP\Activity\IEvent;

class LegacyParser {

	/** @var DataHelper */
	protected $dataHelper;

	/**
	 * @param DataHelper $dataHelper
	 */
	public function __construct(DataHelper $dataHelper) {
		$this->dataHelper = $dataHelper;
	}

	public function parse(IEvent $event) {

		$event->setParsedSubject($this->dataHelper->translation(
			$event->getApp(),
			$event->getSubject(),
			$this->dataHelper->getParameters($event, 'subject', $event->getSubjectParameters())
		));
		$event->setParsedMessage($this->dataHelper->translation(
			$event->getApp(),
			$event->getMessage(),
			$this->dataHelper->getParameters($event, 'message', $event->getMessageParameters())
		));

		return $event;
	}
}
