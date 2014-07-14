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

class ParameterHelper
{
	/**
	 * Prepares the parameters before we use them in the subject or message
	 * @param \OC_L10N $l Language object, if you want to use a different language (f.e. to send an email)
	 * @param string $text
	 * @param array $params
	 * @param array $paramTypes Type of parameters, if they need special handling
	 * @param bool $stripPath Shall we remove the path from the filename
	 * @param bool $highlightParams
	 * @return array
	 */
	public static function prepareParameters(\OC_L10N $l, $text, $params, $paramTypes = array(), $stripPath = false, $highlightParams = false) {
		if (!$text) {
			return $params;
		}

		$preparedParams = array();
		foreach ($params as $i => $param) {
			if (is_array($param)) {
				$preparedParams[] = self::prepareArrayParameter($l, $param, $paramTypes[$i], $stripPath, $highlightParams);
			} else {
				$preparedParams[] = self::prepareStringParameter($param, isset($paramTypes[$i]) ? $paramTypes[$i] : '', $stripPath, $highlightParams);
			}
		}
		return $preparedParams;
	}

	/**
	 * Prepares a string parameter before we use it in the subject or message
	 *
	 * @param string $param
	 * @param string $paramType Type of parameter, if it needs special handling
	 * @param bool $stripPath Shall we remove the path from the filename
	 * @param bool $highlightParams
	 * @return string
	 */
	public static function prepareStringParameter($param, $paramType, $stripPath, $highlightParams) {
		if ($paramType === 'file') {
			return self::prepareFileParam($param, $stripPath, $highlightParams);
		} else if ($paramType === 'username') {
			return self::prepareUserParam($param, $highlightParams);
		}
		return self::prepareParam($param, $highlightParams);
	}

	/**
	 * Prepares an array parameter before we use it in the subject or message
	 *
	 * @param \OC_L10N $l Language object, if you want to use a different language (f.e. to send an email)
	 * @param array $params
	 * @param string $paramType Type of parameters, if it needs special handling
	 * @param bool $stripPath Shall we remove the path from the filename
	 * @param bool $highlightParams
	 * @return string
	 */
	public static function prepareArrayParameter(\OC_L10N $l, $params, $paramType, $stripPath, $highlightParams) {
		$parameterList = $plainParameterList = array();
		foreach ($params as $parameter) {
			if ($paramType === 'file') {
				$parameterList[] = self::prepareFileParam($parameter, $stripPath, $highlightParams);
				$plainParameterList[] = self::prepareFileParam($parameter, false, false);
			} else {
				$parameterList[] = self::prepareParam($parameter, $highlightParams);
				$plainParameterList[] = self::prepareParam($parameter, false);
			}
		}
		return self::joinParameterList($l, $parameterList, $plainParameterList, $highlightParams);
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
		$displayName = \OC_User::getDisplayName($param);
		$param = \OC_Util::sanitizeHTML($param);
		$displayName = \OC_Util::sanitizeHTML($displayName);

		if ($highlightParams) {
			return '<div class="avatar" data-user="' . $param . '"></div>'
				. '<strong>' . $displayName . '</strong>';
		} else {
			return $displayName;
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
		$param = self::fixLegacyFilename($param);

		$parent_dir = (substr_count($param, '/') == 1) ? '/' : dirname($param);
		$fileLink = \OCP\Util::linkTo('files', 'index.php', array('dir' => $parent_dir));
		$param = trim($param, '/');

		if (!$stripPath) {
			if (!$highlightParams) {
				return $param;
			}
			return '<a class="filename" href="' . $fileLink . '">' . \OC_Util::sanitizeHTML($param) . '</a>';
		}

		if (!$highlightParams) {
			return self::stripPathFromFilename($param);
		}

		$title = $param;
		$title = ' title="' . \OC_Util::sanitizeHTML($title) . '"';
		$newParam = self::stripPathFromFilename($param);
		return '<a class="filename tooltip" href="' . $fileLink . '"' . $title . '>' . \OC_Util::sanitizeHTML($newParam) . '</a>';
	}

	/**
	 * Prepend leading slash to filenames of legacy activities
	 * @param string $filename
	 * @return string
	 */
	protected static function fixLegacyFilename($filename) {
		if (strpos($filename, '/') !== 0) {
			return '/' . $filename;
		}
		return $filename;
	}

	/**
	 * Remove the path from the file string
	 * @param string $filename
	 * @return string
	 */
	protected static function stripPathFromFilename($filename) {
		if (strrpos($filename, '/') !== false) {
			// Remove the path from the file string
			return substr($filename, strrpos($filename, '/') + 1);
		}
		return $filename;
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
	 * List with special parameters for the message
	 *
	 * @param string $app
	 * @param string $text
	 * @return array
	 */
	public static function getSpecialParameterList($app, $text) {
		if ($app === 'files' && $text === 'shared_group_self') {
			return array(0 => 'file');
		}
		else if ($app === 'files') {
			return array(0 => 'file', 1 => 'username');
		}
		return array();
	}
}
