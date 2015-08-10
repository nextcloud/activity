<?php

/**
 * ownCloud - Activity App
 *
 * @author Frank Karlitschek
 * @copyright 2013 Frank Karlitschek frank@owncloud.org
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

use OCP\Activity\IExtension;
use OCP\DB;
use OCP\IUser;
use OCP\User;
use OCP\Util;

/**
 * @brief Class for managing the data in the activities
 */
class Data {

	/** @var \OCP\Activity\IManager */
	protected $activityManager;

	/**
	 * @param \OCP\Activity\IManager $activityManager
	 */
	public function __construct(\OCP\Activity\IManager $activityManager) {
		$this->activityManager = $activityManager;
	}

	protected $notificationTypes = array();

	/**
	 * @param \OCP\IL10N $l
	 * @return array Array "stringID of the type" => "translated string description for the setting"
	 */
	public function getNotificationTypes(\OCP\IL10N $l) {
		if (isset($this->notificationTypes[$l->getLanguageCode()])) {
			return $this->notificationTypes[$l->getLanguageCode()];
		}

		// Allow apps to add new notification types
		$notificationTypes = $this->activityManager->getNotificationTypes($l->getLanguageCode());
		$this->notificationTypes[$l->getLanguageCode()] = $notificationTypes;
		return $notificationTypes;
	}

	/**
	 * Send an event into the activity stream
	 *
	 * @param string $app The app where this event is associated with
	 * @param string $subject A short description of the event
	 * @param array  $subjectparams Array with parameters that are filled in the subject
	 * @param string $message A longer description of the event
	 * @param array  $messageparams Array with parameters that are filled in the message
	 * @param string $file The file including path where this event is associated with. (optional)
	 * @param string $link A link where this event is associated with (optional)
	 * @param string $affecteduser If empty the current user will be used
	 * @param string $type Type of the notification
	 * @param int    $prio Priority of the notification
	 * @return bool
	 */
	public static function send($app, $subject, $subjectparams = array(), $message = '', $messageparams = array(), $file = '', $link = '', $affecteduser = '', $type = '', $prio = IExtension::PRIORITY_MEDIUM) {
		$timestamp = time();

		$user = \OC::$server->getUserSession()->getUser();
		if ($user instanceof IUser) {
			$user = $user->getUID();
		} else {
			// Public page or incognito mode
			$user = '';
		}

		if ($affecteduser === '' && $user === '') {
			return false;
		} elseif ($affecteduser === '') {
			$auser = $user;
		} else {
			$auser = $affecteduser;
		}

		// store in DB
		$query = DB::prepare('INSERT INTO `*PREFIX*activity`(`app`, `subject`, `subjectparams`, `message`, `messageparams`, `file`, `link`, `user`, `affecteduser`, `timestamp`, `priority`, `type`)' . ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
		$query->execute(array($app, $subject, json_encode($subjectparams), $message, json_encode($messageparams), $file, $link, $user, $auser, $timestamp, $prio, $type));

		// fire a hook so that other apps like notification systems can connect
		Util::emitHook('OC_Activity', 'post_event', array('app' => $app, 'subject' => $subject, 'user' => $user, 'affecteduser' => $affecteduser, 'message' => $message, 'file' => $file, 'link'=> $link, 'prio' => $prio, 'type' => $type));

		return true;
	}

	/**
	 * @brief Send an event into the activity stream
	 *
	 * @param string $app The app where this event is associated with
	 * @param string $subject A short description of the event
	 * @param array  $subjectParams Array of parameters that are filled in the placeholders
	 * @param string $affectedUser Name of the user we are sending the activity to
	 * @param string $type Type of notification
	 * @param int $latestSendTime Activity time() + batch setting of $affectedUser
	 * @return bool
	 */
	public static function storeMail($app, $subject, array $subjectParams, $affectedUser, $type, $latestSendTime) {
		$timestamp = time();

		// store in DB
		$query = DB::prepare('INSERT INTO `*PREFIX*activity_mq` '
			. ' (`amq_appid`, `amq_subject`, `amq_subjectparams`, `amq_affecteduser`, `amq_timestamp`, `amq_type`, `amq_latest_send`) '
			. ' VALUES(?, ?, ?, ?, ?, ?, ?)');
		$query->execute(array(
			$app,
			$subject,
			json_encode($subjectParams),
			$affectedUser,
			$timestamp,
			$type,
			$latestSendTime,
		));

		// fire a hook so that other apps like notification systems can connect
		Util::emitHook('OC_Activity', 'post_email', array(
			'app'			=> $app,
			'subject'		=> $subject,
			'subjectparams'	=> $subjectParams,
			'affecteduser'	=> $affectedUser,
			'timestamp'		=> $timestamp,
			'type'			=> $type,
			'latest_send'	=> $latestSendTime,
		));

		return true;
	}

	/**
	 * @brief Read a list of events from the activity stream
	 * @param GroupHelper $groupHelper Allows activities to be grouped
	 * @param UserSettings $userSettings Gets the settings of the user
	 * @param int $start The start entry
	 * @param int $count The number of statements to read
	 * @param string $filter Filter the activities
	 * @param string $user User for whom we display the stream
	 * @return array
	 */
	public function read(GroupHelper $groupHelper, UserSettings $userSettings, $start, $count, $filter = 'all', $user = '') {
		// get current user
		$user = ($user !== '') ? $user : User::getUser();
		$groupHelper->setUser($user);

		$enabledNotifications = $userSettings->getNotificationTypes($user, 'stream');
		$enabledNotifications = $this->activityManager->filterNotificationTypes($enabledNotifications, $filter);
		$parameters = array_unique($enabledNotifications);

		// We don't want to display any activities
		if (empty($parameters)) {
			return array();
		}

		$placeholders = implode(',', array_fill(0, sizeof($parameters), '?'));
		$limitActivities = " AND `type` IN (" . $placeholders . ")";
		array_unshift($parameters, $user);

		if ($filter === 'self') {
			$limitActivities .= ' AND `user` = ?';
			$parameters[] = $user;
		}
		else if ($filter === 'by' || $filter === 'all' && !$userSettings->getUserSetting($user, 'setting', 'self')) {
			$limitActivities .= ' AND `user` <> ?';
			$parameters[] = $user;
		}

		list($condition, $params) = $this->activityManager->getQueryForFilter($filter);
		if (!is_null($condition)) {
			$limitActivities .= ' ';
			$limitActivities .= $condition;
			if (is_array($params)) {
				$parameters = array_merge($parameters, $params);
			}
		}

		// fetch from DB
		$query = DB::prepare(
			'SELECT * '
			. ' FROM `*PREFIX*activity` '
			. ' WHERE `affecteduser` = ? ' . $limitActivities
			. ' ORDER BY `timestamp` DESC',
			$count, $start);
		$result = $query->execute($parameters);

		return $this->getActivitiesFromQueryResult($result, $groupHelper);
	}

	/**
	 * Process the result and return the activities
	 *
	 * @param \OC_DB_StatementWrapper|int $result
	 * @param \OCA\Activity\GroupHelper $groupHelper
	 * @return array
	 */
	public function getActivitiesFromQueryResult($result, GroupHelper $groupHelper) {
		if (DB::isError($result)) {
			Util::writeLog('Activity', DB::getErrorMessage($result), Util::ERROR);
		} else {
			while ($row = $result->fetchRow()) {
				$groupHelper->addActivity($row);
			}
		}

		return $groupHelper->getActivities();
	}

	/**
	 * Verify that the filter is valid
	 *
	 * @param string $filterValue
	 * @return string
	 */
	public function validateFilter($filterValue) {
		if (!isset($filterValue)) {
			return 'all';
		}

		switch ($filterValue) {
			case 'by':
			case 'self':
			case 'all':
				return $filterValue;
			default:
				if ($this->activityManager->isFilterValid($filterValue)) {
					return $filterValue;
				}
				return 'all';
		}
	}

	/**
	 * Delete old events
	 *
	 * @param int $expireDays Minimum 1 day
	 * @return null
	 */
	public function expire($expireDays = 365) {
		$ttl = (60 * 60 * 24 * max(1, $expireDays));

		$timelimit = time() - $ttl;
		$this->deleteActivities(array(
			'timestamp' => array($timelimit, '<'),
		));
	}

	/**
	 * Delete activities that match certain conditions
	 *
	 * @param array $conditions Array with conditions that have to be met
	 *                      'field' => 'value'  => `field` = 'value'
	 *    'field' => array('value', 'operator') => `field` operator 'value'
	 * @return null
	 */
	public function deleteActivities($conditions) {
		$sqlWhere = '';
		$sqlParameters = $sqlWhereList = array();
		foreach ($conditions as $column => $comparison) {
			$sqlWhereList[] = " `$column` " . ((is_array($comparison) && isset($comparison[1])) ? $comparison[1] : '=') . ' ? ';
			$sqlParameters[] = (is_array($comparison)) ? $comparison[0] : $comparison;
		}

		if (!empty($sqlWhereList)) {
			$sqlWhere = ' WHERE ' . implode(' AND ', $sqlWhereList);
		}

		$query = DB::prepare(
			'DELETE FROM `*PREFIX*activity`' . $sqlWhere);
		$query->execute($sqlParameters);
	}
}
