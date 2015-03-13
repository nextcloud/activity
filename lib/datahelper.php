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
use OCP\Util;

class DataHelper {
	/** @var \OCP\Activity\IManager */
	protected $activityManager;

	/** @var \OCA\Activity\ParameterHelper */
	protected $parameterHelper;

	/** @var IL10N */
	protected $l;

	public function __construct(IManager $activityManager, ParameterHelper $parameterHelper, IL10N $l) {
		$this->activityManager = $activityManager;
		$this->parameterHelper = $parameterHelper;
		$this->l = $l;
	}

	/**
	 * @param string $user
	 */
	public function setUser($user) {
		$this->parameterHelper->setUser($user);
	}

	/**
	 * @param IL10N $l
	 */
	public function setL10n(IL10N $l) {
		$this->parameterHelper->setL10n($l);
		$this->l = $l;
	}

	/**
	 * @brief Translate an event string with the translations from the app where it was send from
	 * @param string $app The app where this event comes from
	 * @param string $text The text including placeholders
	 * @param array $params The parameter for the placeholder
	 * @param bool $stripPath Shall we strip the path from file names?
	 * @param bool $highlightParams Shall we highlight the parameters in the string?
	 *             They will be highlighted with `<strong>`, all data will be passed through
	 *             \OCP\Util::sanitizeHTML() before, so no XSS is possible.
	 * @return string translated
	 */
	public function translation($app, $text, $params, $stripPath = false, $highlightParams = false) {
		if (!$text) {
			return '';
		}

		$preparedParams = $this->parameterHelper->prepareParameters(
			$params, $this->parameterHelper->getSpecialParameterList($app, $text),
			$stripPath, $highlightParams
		);

		// Allow apps to correctly translate their activities
		$translation = $this->activityManager->translate(
			$app, $text, $preparedParams, $stripPath, $highlightParams, $this->l->getLanguageCode());

		if ($translation !== false) {
			return $translation;
		}

		$l = Util::getL10N($app, $this->l->getLanguageCode());
		return $l->t($text, $preparedParams);
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

		$activity[$message . 'formatted'] = array(
			'trimmed'	=> $this->translation($activity['app'], $activity[$message], $activity[$message . 'params'], true),
			'full'		=> $this->translation($activity['app'], $activity[$message], $activity[$message . 'params']),
			'markup'	=> array(
				'trimmed'	=> $this->translation($activity['app'], $activity[$message], $activity[$message . 'params'], true, true),
				'full'		=> $this->translation($activity['app'], $activity[$message], $activity[$message . 'params'], false, true),
			),
		);

		return $activity;
	}

	/**
	 * Get the parameter array from the parameter string of the database table
	 *
	 * @param string $parameterString can be a JSON string, serialize() or a simple string.
	 * @return array List of Parameters
	 */
	public function getParameters($parameterString) {
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
