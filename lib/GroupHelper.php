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

use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\IL10N;
use OCP\RichObjectStrings\InvalidObjectExeption;
use OCP\RichObjectStrings\IValidator;
use Psr\Log\LoggerInterface;

class GroupHelper {
	/** @var IEvent[] */
	protected array $event = [];
	protected int $lastEvent = 0;
	protected bool $allowGrouping = true;

	public function __construct(
		protected IL10N $l,
		protected IManager $activityManager,
		protected IValidator $richObjectValidator,
		protected LoggerInterface $logger
	) {
	}

	public function resetEvents(): void {
		$this->event = [];
		$this->lastEvent = 0;
	}

	public function setL10n(IL10N $l): void {
		$this->l = $l;
	}

	/**
	 * Add an activity to the internal array
	 */
	public function addActivity(array $activity): void {
		$id = (int) $activity['activity_id'];
		$event = $this->arrayToEvent($activity);
		$this->addEvent($id, $event);
	}

	/**
	 * Add an event to the internal array
	 */
	public function addEvent(int $id, IEvent $event): void {
		$language = $this->l->getLanguageCode();

		foreach ($this->activityManager->getProviders() as $provider) {
			try {
				$this->activityManager->setFormattingObject($event->getObjectType(), $event->getObjectId());
				if ($this->allowGrouping && $this->lastEvent !== 0 && isset($this->event[$this->lastEvent])) {
					$event = $provider->parse($language, $event, $this->event[$this->lastEvent]);
				} else {
					$event = $provider->parse($language, $event);
				}
			} catch (\InvalidArgumentException $e) {
			} catch (\Throwable $e) {
				$this->logger->error('Error while parsing activity event', ['exception' => $e]);
			}
		}

		try {
			$this->richObjectValidator->validate($event->getRichSubject(), $event->getRichSubjectParameters());
		} catch (InvalidObjectExeption $e) {
			$this->logger->error(
				$e->getMessage(),
				[
					'app' => 'activity',
					'exception' => $e
				],
			);
			$event->setRichSubject('Rich subject or a parameter for "' . $event->getRichSubject() . '" is malformed', []);
			$event->setParsedSubject('Rich subject or a parameter for "' . $event->getRichSubject() . '" is malformed');
		}

		if ($event->getRichMessage()) {
			try {
				$this->richObjectValidator->validate($event->getRichMessage(), $event->getRichMessageParameters());
			} catch (InvalidObjectExeption $e) {
				$this->logger->error(
					$e->getMessage(),
					[
						'app' => 'activity',
						'exception' => $e
					],
				);
				$event->setRichMessage('Rich message or a parameter is malformed', []);
				$event->setParsedMessage('Rich message or a parameter is malformed');
			}
		}

		$this->activityManager->setFormattingObject('', 0);

		$child = $event->getChildEvent();
		if ($child instanceof IEvent) {
			unset($this->event[$this->lastEvent]);
		}

		if (!$event->getParsedSubject()) {
			$this->logger->debug('Activity "' . $event->getRichSubject() . '" was not parsed by any provider');
			return;
		}

		$this->event[$id] = $event;
		$this->lastEvent = $id;
	}

	/**
	 * Get the prepared activities
	 *
	 * @return array translated activities ready for use
	 */
	public function getActivities(): array {
		$return = [];
		foreach ($this->event as $id => $event) {
			$return[] = $this->eventToArray($event, $id);
		}
		$this->event = [];

		return $return;
	}

	/**
	 * @return IEvent[]
	 */
	public function getEvents(): array {
		$return = $this->event;
		$this->event = [];
		return $return;
	}

	protected function arrayToEvent(array $row): IEvent {
		$event = $this->activityManager->generateEvent();
		$event->setApp((string) $row['app'])
			->setType((string) $row['type'])
			->setAffectedUser((string) $row['affecteduser'])
			->setAuthor((string) $row['user'])
			->setTimestamp((int) $row['timestamp'])
			->setSubject((string) $row['subject'], (array) json_decode($row['subjectparams'], true))
			->setMessage((string) $row['message'], (array) json_decode($row['messageparams'], true))
			->setObject((string) $row['object_type'], (int) $row['object_id'], (string) $row['file'])
			->setLink((string) $row['link']);

		return $event;
	}

	/**
	 * @param (int|string) $id
	 * @psalm-param array-key $id
	 */
	protected function eventToArray(IEvent $event, $id): array {
		return [
			'activity_id' => $id,
			'app' => $event->getApp(),
			'type' => $event->getType(),
			'affecteduser' => $event->getAffectedUser(),
			'user' => $event->getAuthor(),
			'timestamp' => $event->getTimestamp(),
			'subject' => $event->getParsedSubject(),
			'subject_rich' => [
				$event->getRichSubject(),
				$event->getRichSubjectParameters(),
			],
			'message' => $event->getParsedMessage(),
			'message_rich' => [
				$event->getRichMessage(),
				$event->getRichMessageParameters(),
			],
			'object_type' => $event->getObjectType(),
			'object_id' => $event->getObjectId(),
			'object_name' => $event->getObjectName(),
			'objects' => $this->getObjectsFromChildren($event),
			'link' => $event->getLink(),
			'icon' => $event->getIcon(),
		];
	}

	protected function getObjectsFromChildren(IEvent $event): array {
		$child = $event->getChildEvent();
		$objects = [];
		if ($child instanceof IEvent) {
			$objects = $this->getObjectsFromChildren($child);
		}
		if ($event->getObjectId() !== 0 || $event->getObjectName() !== '') {
			$objects[$event->getObjectId()] = $event->getObjectName();
		}
		return $objects;
	}
}
