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
	 * @param \OC_L10N $l Language object, if you want to use a different language (f.e. to send an email)
	 * @param string $app
	 * @param string $text
	 * @param array $params
	 * @param array $paramTypes Type of parameters, if they need special handling
	 * @param bool $stripPath Shall we remove the path from the filename
	 * @param bool $highlightParams
	 * @return array
	 */
	public static function prepareParameters(\OC_L10N $l, $app, $text, $params, $paramTypes = array(), $stripPath = false, $highlightParams = false) {
		if ($app === 'files' && $text) {
			$preparedParams = array();
			foreach ($params as $i => $param) {
				if (is_array($param)) {
					$parameterList = $plainParameterList = array();
					foreach ($param as $parameter) {
						if (isset($paramTypes[$i]) && $paramTypes[$i] === 'file') {
							$parameterList[] = self::prepareFileParam($parameter, $stripPath, $highlightParams);
							$plainParameterList[] = self::prepareFileParam($parameter, false, false);
						} else {
							$parameterList[] = self::prepareParam($parameter, $highlightParams);
							$plainParameterList[] = self::prepareParam($parameter, false);
						}
					}
					$preparedParams[] = self::joinParameterList($l, $parameterList, $plainParameterList, $highlightParams);
				} else {
					if (isset($paramTypes[$i]) && $paramTypes[$i] === 'file') {
						$preparedParams[] = self::prepareFileParam($param, $stripPath, $highlightParams);
					} else if (isset($paramTypes[$i]) && $paramTypes[$i] === 'username') {
						$preparedParams[] = self::prepareUserParam($param, $highlightParams);
					} else {
						$preparedParams[] = self::prepareParam($param, $highlightParams);
					}
				}
			}
			return $preparedParams;
		}
		return $params;
	}

	/**
	 * Prepares a parameter for usage by adding highlights
	 *
	 * @param string $param
	 * @param bool $highlightParams
	 * @return string
	 */
	protected static function prepareParam($param, $highlightParams) {
		if ($highlightParams) {
			return '<strong>' . \OC_Util::sanitizeHTML($param) . '</strong>';
		} else {
			return $param;
		}
	}

	/**
	 * Prepares a user name parameter for usage
	 *
	 * Add an avatar to usernames
	 *
	 * @param string $param
	 * @param bool $highlightParams
	 * @return string
	 */
	protected static function prepareUserParam($param, $highlightParams) {
		if ($highlightParams) {
			$param = \OC_Util::sanitizeHTML($param);
			return '<div class="avatar" data-user="' . $param . '"></div>'
				. '<strong>' . $param . '</strong>';
		} else {
			return $param;
		}
	}

	/**
	 * Prepares a file parameter for usage
	 *
	 * Removes the path from filenames and adds highlights
	 *
	 * @param string $param
	 * @param bool $stripPath Shall we remove the path from the filename
	 * @param bool $highlightParams
	 * @return string
	 */
	protected static function prepareFileParam($param, $stripPath, $highlightParams) {
		if (strpos($param, '/') !== 0) {
			// Prepend leading slash to legacy activities
			$param = '/' . $param;
		}

		$parent_dir = (substr_count($param, '/') == 1) ? '/' : dirname($param);
		$fileLink = \OCP\Util::linkTo('files', 'index.php', array('dir' => $parent_dir));

		// Remove the path from the file string
		$param = substr($param, 1);

		$newParam = $param;
		if ($stripPath && strrpos($param, '/') !== false) {
			// Remove the path from the file string
			$newParam = substr($param, strrpos($param, '/') + 1);
		}

		if (!$highlightParams) {
			return $newParam;
		}

		if (!$stripPath) {
			return '<a class="filename" href="' . $fileLink . '">' . \OC_Util::sanitizeHTML($newParam) . '</a>';
		}

		$title = ' title="' . \OC_Util::sanitizeHTML($param) . '"';
		return '<a class="filename tooltip" href="' . $fileLink . '"' . $title . '>' . \OC_Util::sanitizeHTML($newParam) . '</a>';

	}

	/**
	 * Returns a list of grouped parameters
	 *
	 * 2 parameters are joined by "and":
	 * => A and B
	 * Up to 5 parameters are joined by "," and "and":
	 * => A, B, C, D and E
	 * More than 5 parameters are joined by "," and trimmed:
	 * => A, B, C and #n more
	 *
	 * @param \OC_L10N $l
	 * @param array $parameterList
	 * @param array $plainParameterList
	 * @param bool $highlightParams
	 * @return string
	 */
	protected static function joinParameterList(\OC_L10N $l, $parameterList, $plainParameterList, $highlightParams) {
		if (empty($parameterList)) {
			return '';
		}

		$count = sizeof($parameterList);
		$lastItem = array_pop($parameterList);

		if ($count == 1)
		{
			return $lastItem;
		}
		else if ($count == 2)
		{
			$firstItem = array_pop($parameterList);
			return $l->t('%s and %s', array($firstItem, $lastItem));
		}
		else if ($count <= 5)
		{
			$list = implode($l->t(', '), $parameterList);
			return $l->t('%s and %s', array($list, $lastItem));
		}

		$firstParams = array_slice($parameterList, 0, 3);
		$firstList = implode($l->t(', '), $firstParams);
		$trimmedParams = array_slice($plainParameterList, 3);
		$trimmedList = implode($l->t(', '), $trimmedParams);
		if ($highlightParams) {
			return $l->n(
				'%s and <strong class="tooltip" title="%s">%n more</strong>',
				'%s and <strong class="tooltip" title="%s">%n more</strong>',
				$count - 3,
				array($firstList, $trimmedList));
		}
		return $l->n('%s and %n more', '%s and %n more', $count - 3, array($firstList));
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
			$preparedParams = self::prepareParameters(
				$l, $app, $text,
				$params, array(0 => 'file', 1 => 'username'),
				$stripPath, $highlightParams
			);
			if ($text === 'created_self') {
				return $l->t('You created %1$s', $preparedParams);
			}
			else if ($text === 'created_by') {
				return $l->t('%2$s created %1$s', $preparedParams);
			}
			else if ($text === 'changed_self') {
				return $l->t('You changed %1$s', $preparedParams);
			}
			else if ($text === 'changed_by') {
				return $l->t('%2$s changed %1$s', $preparedParams);
			}
			else if ($text === 'deleted_self') {
				return $l->t('You deleted %1$s', $preparedParams);
			}
			else if ($text === 'deleted_by') {
				return $l->t('%2$s deleted %1$s', $preparedParams);
			}
			else if ($text === 'shared_user_self') {
				return $l->t('You shared %1$s with %2$s', $preparedParams);
			}
			else if ($text === 'shared_group_self') {
				// Second parameter is not a username here
				$preparedParams = self::prepareParameters(
					$l, $app, $text,
					$params, array(0 => 'file'),
					$stripPath, $highlightParams
				);
				return $l->t('You shared %1$s with group %2$s', $preparedParams);
			}
			else if ($text === 'shared_with_by') {
				return $l->t('%2$s shared %1$s with you', $preparedParams);
			}
			else if ($text === 'shared_link_self') {
				return $l->t('You shared %1$s', $preparedParams);
			}

			return $l->t($text, $preparedParams);
		} else {
			$l = \OCP\Util::getL10N($app);
			return $l->t($text, $params);
		}
	}

	/**
	 * Process the rows from the database and also groups them if requested
	 *
	 * @param array $activities
	 * @param bool $allowGrouping
	 * @return array
	 */
	public static function prepareActivities($activities, $allowGrouping = true) {
		$helper = new \OCA\Activity\GroupHelper($allowGrouping);

		foreach ($activities as $row) {
			$helper->addActivity($row);
		}

		return $helper->getActivities();
	}

	/**
	 * Format strings for display
	 *
	 * @param array $activity
	 * @param string $message 'subject' or 'message'
	 * @return array Modified $activity
	 */
	public static function formatStrings($activity, $message) {
		$activity[$message . 'params'] = $activity[$message . 'params_array'];
		unset($activity[$message . 'params_array']);

		$activity[$message . 'formatted'] = array(
			'trimmed'	=> DataHelper::translation($activity['app'], $activity[$message], $activity[$message . 'params'], true),
			'full'		=> DataHelper::translation($activity['app'], $activity[$message], $activity[$message . 'params']),
			'markup'	=> array(
				'trimmed'	=> DataHelper::translation($activity['app'], $activity[$message], $activity[$message . 'params'], true, true),
				'full'		=> DataHelper::translation($activity['app'], $activity[$message], $activity[$message . 'params'], false, true),
			),
		);

		return $activity;
	}

	/**
	 * Get the icon for a given activity type
	 *
	 * @param string $type
	 * @return string CSS class which adds the icon
	 */
	public static function getTypeIcon($type)
	{
		switch ($type)
		{
			case Data::TYPE_SHARE_CHANGED:
				return 'icon-change';
			case Data::TYPE_SHARE_CREATED:
				return 'icon-add-color';
			case Data::TYPE_SHARE_DELETED:
				return 'icon-delete-color';
			case Data::TYPE_SHARED:
				return 'icon-share';
		}
		return '';
	}
}
