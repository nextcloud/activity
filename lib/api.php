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
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity;

/**
 * Class Api
 *
 * @package OCA\Activity
 */
class Api
{
	const DEFAULT_LIMIT = 30;

	static public function get() {
		$app = new AppInfo\Application();
		/** @var Data $data */
		$data = $app->getContainer()->query('ActivityData');

		$start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
		$count = isset($_GET['count']) ? (int) $_GET['count'] : self::DEFAULT_LIMIT;
		$user = $app->getContainer()->getServer()->getUserSession()->getUser()->getUID();

		if ($start !== 0) {
			$start = self::getSinceFromOffset($user, $start);
		}

		$activities = $data->get(
			$app->getContainer()->query('GroupHelper'),
			$app->getContainer()->query('UserSettings'),
			$user, $start, $count, 'desc', 'all'
		);

		$entries = array();
		foreach($activities['data'] as $entry) {
			$entries[] = array(
				'id' => $entry['activity_id'],
				'subject' => self::parseMessage($entry['subject_prepared']),
				'message' => self::parseMessage($entry['message_prepared']),
				'file' => $entry['object_name'],
				'link' => $entry['link'],
				'date' => date('c', $entry['timestamp']),
			);
		}

		return new \OC_OCS_Result($entries);
	}

	/**
	 * @param string $user
	 * @param int $offset
	 * @return int
	 */
	protected static function getSinceFromOffset($user, $offset) {
		$query = \OC::$server->getDatabaseConnection()->getQueryBuilder();
		$query->select('activity_id')
			->from('activity')
			->where($query->expr()->eq('affecteduser', $query->createNamedParameter($user)))
			->orderBy('activity_id', 'desc')
			->setFirstResult($offset - 1)
			->setMaxResults(1);

		$result = $query->execute();
		$row = $result->fetch();
		$result->closeCursor();

		if ($row) {
			return (int) $row['activity_id'];
		}

		return 0;
	}

	/**
	 * Parse the parameters in the subject and message
	 *
	 * @param string $message
	 * @return string
	 */
	protected static function parseMessage($message) {
		$message = self::parseCollections($message);
		$message = self::parseParameters($message);
		return $message;
	}

	/**
	 * Parse collections
	 *
	 * @param string $message
	 * @return string
	 */
	protected static function parseCollections($message) {
		return preg_replace_callback('/<collection>(.*?)<\/collection>/', function($match) {
			$parameterList = explode('><', $match[1]);
			$parameterListLength = sizeof($parameterList);

			$parameters = [];
			for ($i = 0; $i < $parameterListLength; $i++) {
				$parameter = $parameterList[$i];
				if ($i > 0) {
					$parameter = '<' . $parameter;
				}
				if ($i + 1 < $parameterListLength) {
					$parameter = $parameter . '>';
				}

				$parameters[] = self::parseParameters($parameter);
			}
			if ($parameterListLength === 1) {
				return array_pop($parameters);
			} else {
				$l = \OC::$server->getL10NFactory()->get('activity');
				$lastParameter = array_pop($parameters);
				return $l->t('%s and %s', [
					implode($l->t(', '), $parameters),
					$lastParameter,
				]);
			}
		}, $message);
	}

	/**
	 * Parse the parameters in the subject and message
	 *
	 * @param string $message
	 * @return string
	 */
	protected static function parseParameters($message) {
		$message = self::parseUntypedParameters($message);
		$message = self::parseUserParameters($message);
		$message = self::parseFederatedCloudIDParameters($message);
		$message = self::parseFileParameters($message);
		return $message;
	}

	/**
	 * Display the parameter value
	 *
	 * @param string $message
	 * @return string
	 */
	protected static function parseUntypedParameters($message) {
		return preg_replace_callback('/<parameter>(.*?)<\/parameter>/', function($match) {
			return $match[1];
		}, $message);
	}

	/**
	 * Display the users display name
	 *
	 * @param string $message
	 * @return string
	 */
	protected static function parseUserParameters($message) {
		return preg_replace_callback('/<user\ display\-name=\"(.*?)\">(.*?)<\/user>/', function($match) {
			return $match[1];
		}, $message);
	}

	/**
	 * Display the full cloud id
	 *
	 * @param string $message
	 * @return string
	 */
	protected static function parseFederatedCloudIDParameters($message) {
		return preg_replace_callback('/<federated-cloud-id\ display\-name=\"(.*?)\"\ user=\"(.*?)\"\ server=\"(.*?)\">(.*?)<\/federated-cloud-id>/', function($match) {
			return $match[1];
		}, $message);
	}

	/**
	 * Display the path for files
	 *
	 * @param string $message
	 * @return string
	 */
	protected static function parseFileParameters($message) {
		return preg_replace_callback('/<file\ link=\"(.*?)\"\ id=\"(.*?)\">(.*?)<\/file>/', function($match) {
			return $match[3];
		}, $message);
	}
}
