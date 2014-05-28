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

class DataHelper
{
	/**
	 * Prepares the parameters before we use them in the subject or message
	 * @param string $app
	 * @param string $text
	 * @param array $params
	 * @param mixed $filePosition Position of a file in $params
	 * @param bool $stripPath Shall we remove the path from the filename
	 * @param bool $highlightParams
	 * @return array
	 */
	public static function prepareFilesParams($app, $text, $params, $filePosition = false, $stripPath = false, $highlightParams = false) {
		if ($app === 'files' && $text) {
			$preparedParams = array();
			foreach ($params as $i => $param) {
				if ($stripPath === true && $filePosition === $i) {
					// Remove the path from the file string
					$param = substr($param, strrpos($param, '/') + 1);
				}

				if ($highlightParams) {
					$preparedParams[] = '<strong>' . \OC_Util::sanitizeHTML($param) . '</strong>';
				} else {
					$preparedParams[] = $param;
				}
			}
			return $preparedParams;
		}
		return $params;
	}

	/**
	 * @brief Translate an event string with the translations from the app where it was send from
	 * @param string $app The app where this event comes from
	 * @param string $text The text including placeholders
	 * @param array $params The parameter for the placeholder
	 * @param bool $stripPath Shall we strip the path from file names?
	 * @param bool $highlightParams Shall we highlight the parameters in the string?
	 *             They will be highlighted with `<strong>`, all data will be passed through
	 *             \OC_Util::sanitizeHTML() before, so no XSS is possible.
	 * @param \OC_L10N $l Language object, if you want to use a different language (f.e. to send an email)
	 * @return string translated
	 */
	public static function translation($app, $text, $params, $stripPath = false, $highlightParams = false, \OC_L10N $l = null) {
		if (!$text) {
			return '';
		}
		if ($l === null) {
			$l = \OCP\Util::getL10N('activity');
		}

		if ($app === 'files') {
			$params = self::prepareFilesParams($app, $text, $params, 0, $stripPath, $highlightParams);
			if ($text === 'created_self') {
				return $l->t('You created %1$s', $params);
			}
			else if ($text === 'created_by') {
				return $l->t('%2$s created %1$s', $params);
			}
			else if ($text === 'changed_self') {
				return $l->t('You changed %1$s', $params);
			}
			else if ($text === 'changed_by') {
				return $l->t('%2$s changed %1$s', $params);
			}
			else if ($text === 'deleted_self') {
				return $l->t('You deleted %1$s', $params);
			}
			else if ($text === 'deleted_by') {
				return $l->t('%2$s deleted %1$s', $params);
			}
			else if ($text === 'shared_user_self') {
				return $l->t('You shared %1$s with %2$s', $params);
			}
			else if ($text === 'shared_group_self') {
				return $l->t('You shared %1$s with group %2$s', $params);
			}
			else if ($text === 'shared_with_by') {
				return $l->t('%2$s shared %1$s with you', $params);
			}
			else if ($text === 'shared_link_self') {
				return $l->t('You shared %1$s', $params);
			}

			return $l->t($text, $params);
		} else {
			$l = \OCP\Util::getL10N($app);
			return $l->t($text, $params);
		}
	}
}
