<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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

use OCA\Activity\Parameter\Factory;
use OCA\Activity\Parameter\IParameter;
use OCA\Activity\Parameter\Collection;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\IL10N;
use OCP\L10N\IFactory;

class DataHelper {
	/** @var \OCP\Activity\IManager */
	protected $activityManager;

	/** @var \OCA\Activity\Parameter\Factory */
	protected $parameterFactory;

	/** @var IFactory */
	protected $l10Nfactory;

	/** @var IL10N */
	protected $l;

	/**
	 * @param IManager $activityManager
	 * @param Factory $parameterFactory
	 * @param IFactory $l10Nfactory
	 * @param IL10N $l
	 */
	public function __construct(IManager $activityManager, Factory $parameterFactory, IFactory $l10Nfactory, IL10N $l) {
		$this->activityManager = $activityManager;
		$this->parameterFactory = $parameterFactory;
		$this->l10Nfactory = $l10Nfactory;
		$this->l = $l;
	}

	/**
	 * @param string $user
	 */
	public function setUser($user) {
		$this->parameterFactory->setUser($user);
	}

	/**
	 * @param IL10N $l
	 */
	public function setL10n(IL10N $l) {
		$this->parameterFactory->setL10n($l);
		$this->l = $l;
	}

	/**
	 * @brief Translate an event string with the translations from the app where it was send from
	 * @param string $app The app where this event comes from
	 * @param string $text The text including placeholders
	 * @param IParameter[] $params The parameter for the placeholder
	 * @return string translated
	 */
	public function translation($app, $text, array $params) {
		if (!$text) {
			return '';
		}

		$preparedParams = [];
		foreach ($params as $parameter) {
			$preparedParams[] = $parameter->format();
		}

		// Allow apps to correctly translate their activities
		$translation = $this->activityManager->translate(
			$app, $text, $preparedParams, false, false, $this->l->getLanguageCode());

		if ($translation !== false) {
			return $translation;
		}

		$l = $this->l10Nfactory->get($app, $this->l->getLanguageCode());
		return $l->t($text, $preparedParams);
	}

	/**
	 * List with special parameters for the message
	 *
	 * @param string $app
	 * @param string $text
	 * @return array
	 */
	protected function getSpecialParameterList($app, $text) {
		$specialParameters = $this->activityManager->getSpecialParameterList($app, $text);

		if ($specialParameters !== false) {
			return $specialParameters;
		}

		return array();
	}

	/**
	 * Format strings for display
	 *
	 * @param array $activity
	 * @param string $message 'subject' or 'message'
	 * @return array Modified $activity
	 */
	public function formatStrings($activity, $message) {
		$activity[$message . 'params'] = $activity[$message . 'params_array'];
		unset($activity[$message . 'params_array']);
		$activity[$message . '_prepared'] = $this->translation($activity['app'], $activity[$message], $activity[$message . 'params']);

		return $activity;
	}

	/**
	 * Get the parameter array from the parameter string of the database table
	 *
	 * @param IEvent $event
	 * @param string $parsing What are we parsing `message` or `subject`
	 * @param string $parameterString can be a JSON string, serialize() or a simple string.
	 * @return array List of Parameters
	 */
	public function getParameters(IEvent $event, $parsing, $parameterString) {
		$parameters = $this->parseParameters($parameterString);
		$parameterTypes = $this->getSpecialParameterList(
			$event->getApp(),
			($parsing === 'subject') ? $event->getSubject() : $event->getMessage()
		);

		foreach ($parameters as $i => $parameter) {
			$parameters[$i] = $this->parameterFactory->get(
				$parameter,
				$event,
				isset($parameterTypes[$i]) ? $parameterTypes[$i] : 'base'
			);
		}

		return $parameters;
	}

	/**
	 * @return Collection
	 */
	public function createCollection() {
		return $this->parameterFactory->createCollection();
	}

	/**
	 * Get the parameter array from the parameter string of the database table
	 *
	 * @param string $parameterString can be a JSON string, serialize() or a simple string.
	 * @return array List of Parameters
	 */
	public function parseParameters($parameterString) {
		if (!is_string($parameterString)) {
			return [];
		}
		$parameters = $parameterString;

		if ($parameterString[0] === '[' && substr($parameterString, -1) === ']' || $parameterString[0] === '"' && substr($parameterString, -1) === '"') {
			// ownCloud 8.1+
			$parameters = json_decode($parameterString, true);
			if ($parameters === null) {
				// Error on json decode
				$parameters = $parameterString;
			}

		} else if (isset($parameterString[7]) && $parameterString[1] === ':' && ($parameterString[0] === 's' && substr($parameterString, -1) === ';' || $parameterString[0] === 'a' && substr($parameterString, -1) === '}')) {
			// ownCloud 7+
			// Min length 8: `s:1:"a";`
			// Accepts: `s:1:"a";` for single string `a:1:{i:0;s:1:"a";}` for array
			$parameters = unserialize($parameterString);
		}

		if (is_array($parameters)) {
			return $parameters;
		}

		// ownCloud <7
		return [$parameters];
	}
}
