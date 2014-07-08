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

/**
 * @brief Class for managing the data in the activities
 */
class Data
{
	const PRIORITY_VERYLOW 	= 10;
	const PRIORITY_LOW	= 20;
	const PRIORITY_MEDIUM	= 30;
	const PRIORITY_HIGH	= 40;
	const PRIORITY_VERYHIGH	= 50;

	const TYPE_SHARED = 'shared';
	const TYPE_SHARE_EXPIRED = 'share_expired';
	const TYPE_SHARE_UNSHARED = 'share_unshared';

	const TYPE_SHARE_CREATED = 'file_created';
	const TYPE_SHARE_CHANGED = 'file_changed';
	const TYPE_SHARE_DELETED = 'file_deleted';
	const TYPE_SHARE_RESHARED = 'file_reshared';

	const TYPE_SHARE_DOWNLOADED = 'file_downloaded';
	const TYPE_SHARE_UPLOADED = 'file_uploaded';

	const TYPE_STORAGE_QUOTA_90 = 'storage_quota_90';
	const TYPE_STORAGE_FAILURE = 'storage_failure';

	static protected $notificationTypes = array();

	/**
	 * @param \OC_L10N $l
	 * @return array Array "stringID of the type" => "translated string description for the setting"
	 */
	public static function getNotificationTypes(\OC_L10N $l) {
		if (isset(self::$notificationTypes[$l->getLanguageCode()]))
		{
			return self::$notificationTypes[$l->getLanguageCode()];
		}

		$notificationTypes = array(
			\OCA\Activity\Data::TYPE_SHARED => $l->t('A file or folder has been <strong>shared</strong>'),
//			\OCA\Activity\Data::TYPE_SHARE_UNSHARED => $l->t('Previously shared file or folder has been <strong>unshared</strong>'),
//			\OCA\Activity\Data::TYPE_SHARE_EXPIRED => $l->t('Expiration date of shared file or folder <strong>expired</strong>'),
			\OCA\Activity\Data::TYPE_SHARE_CREATED => $l->t('A new file or folder has been <strong>created</strong> in a shared folder'),
			\OCA\Activity\Data::TYPE_SHARE_CHANGED => $l->t('A file or folder has been <strong>changed</strong> in a shared folder'),
			\OCA\Activity\Data::TYPE_SHARE_DELETED => $l->t('A file or folder has been <strong>deleted</strong> from a shared folder'),
//			\OCA\Activity\Data::TYPE_SHARE_RESHARED => $l->t('A file or folder has been <strong>reshared</strong>'),
//			\OCA\Activity\Data::TYPE_SHARE_DOWNLOADED => $l->t('A file or folder shared via link has been <strong>downloaded</strong>'),
//			\OCA\Activity\Data::TYPE_SHARE_UPLOADED => $l->t('A file has been <strong>uploaded</strong> into a folder shared via link'),
//			\OCA\Activity\Data::TYPE_STORAGE_QUOTA_90 => $l->t('<strong>Storage usage</strong> is at 90%%'),
//			\OCA\Activity\Data::TYPE_STORAGE_FAILURE => $l->t('An <strong>external storage</strong> has an error'),
		);

		// Allow other apps to add new notification types
		$additionalNotificationTypes = \OC::$server->getActivityManager()->getNotificationTypes($l->getLanguageCode());
		$notificationTypes = array_merge($notificationTypes, $additionalNotificationTypes);

		self::$notificationTypes[$l->getLanguageCode()] = $notificationTypes;

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
	public static function send($app, $subject, $subjectparams = array(), $message = '', $messageparams = array(), $file = '', $link = '', $affecteduser = '', $type = '', $prio = Data::PRIORITY_MEDIUM) {
		$timestamp = time();
		$user = \OCP\User::getUser();
		
		if ($affecteduser === '') {
			$auser = \OCP\User::getUser();
		} else {
			$auser = $affecteduser;
		}

		// store in DB
		$query = \OCP\DB::prepare('INSERT INTO `*PREFIX*activity`(`app`, `subject`, `subjectparams`, `message`, `messageparams`, `file`, `link`, `user`, `affecteduser`, `timestamp`, `priority`, `type`)' . ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
		$query->execute(array($app, $subject, serialize($subjectparams), $message, serialize($messageparams), $file, $link, $user, $auser, $timestamp, $prio, $type));

		// fire a hook so that other apps like notification systems can connect
		\OCP\Util::emitHook('OC_Activity', 'post_event', array('app' => $app, 'subject' => $subject, 'user' => $user, 'affecteduser' => $affecteduser, 'message' => $message, 'file' => $file, 'link'=> $link, 'prio' => $prio, 'type' => $type));

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
	 * @param int $latestSendTime Activity time() + batch setting of $affecteduser
	 * @return bool
	 */
	public static function storeMail($app, $subject, array $subjectParams, $affectedUser, $type, $latestSendTime) {
		$timestamp = time();

		// store in DB
		$query = \OCP\DB::prepare('INSERT INTO `*PREFIX*activity_mq` '
			. ' (`amq_appid`, `amq_subject`, `amq_subjectparams`, `amq_affecteduser`, `amq_timestamp`, `amq_type`, `amq_latest_send`) '
			. ' VALUES(?, ?, ?, ?, ?, ?, ?)');
		$query->execute(array(
			$app,
			$subject,
			serialize($subjectParams),
			$affectedUser,
			$timestamp,
			$type,
			$latestSendTime,
		));

		// fire a hook so that other apps like notification systems can connect
		\OCP\Util::emitHook('OC_Activity', 'post_email', array(
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
	 * Filter the activity types
	 *
	 * @param array $types
	 * @param string $filter
	 * @return array
	 */
	public static function filterNotificationTypes($types, $filter) {
		switch ($filter) {
			case 'shares':
				return array_intersect(array(
					Data::TYPE_SHARED,
				), $types);
		}

		// Allow other apps to add new notification types
		return \OC::$server->getActivityManager()->filterNotificationTypes($types, $filter);
	}

	/**
	 * @brief Read a list of events from the activity stream
	 * @param int $start The start entry
	 * @param int $count The number of statements to read
	 * @param string $filter Filter the activities
	 * @param bool $allowGrouping Allow activities to be grouped
	 * @return array
	 */
	public static function read($start, $count, $filter = 'all', $allowGrouping = true) {
		// get current user
		$user = \OCP\User::getUser();
		$enabledNotifications = UserSettings::getNotificationTypes($user, 'stream');
		$enabledNotifications = Data::filterNotificationTypes($enabledNotifications, $filter);

		// We don't want to display any activities
		if (empty($enabledNotifications)) {
			return array();
		}

		$parameters = array($user);
		$limitActivities = " AND `type` IN ('" . implode("','", $enabledNotifications) . "')";

		if ($filter === 'self') {
			$limitActivities .= ' AND `user` = ?';
			$parameters[] = $user;
		}
		else if ($filter === 'by') {
			$limitActivities .= ' AND `user` <> ?';
			$parameters[] = $user;
		}
		else if ($filter !== 'all') {
			switch ($filter) {
				case 'files':
					$limitActivities .= ' AND `app` = ?';
					$parameters[] = 'files';
				break;

				default:
					list($condition, $params) = \OC::$server->getActivityManager()->getQueryForFilter($filter);
					if (!is_null($condition)) {
						$limitActivities .= ' ';
						$limitActivities .= $condition;
						if (is_array($params)) {
							$parameters = array_merge($parameters, $params);
						}
					}
			}
		}

		// fetch from DB
		$query = \OCP\DB::prepare(
			'SELECT * '
			. ' FROM `*PREFIX*activity` '
			. ' WHERE `affecteduser` = ? ' . $limitActivities
			. ' ORDER BY `timestamp` desc',
			$count, $start);
		$result = $query->execute($parameters);

		return self::getActivitiesFromQueryResult($result, $allowGrouping);
	}

	/**
	 * Process the result and return the activities
	 *
	 * @param \OC_DB_StatementWrapper|int $result
	 * @param bool $allowGrouping Allow activities to be grouped
	 * @return array
	 */
	public static function getActivitiesFromQueryResult($result, $allowGrouping = true) {
		$helper = new \OCA\Activity\GroupHelper($allowGrouping);
		if (\OCP\DB::isError($result)) {
			\OCP\Util::writeLog('Activity', \OC_DB::getErrorMessage($result), \OC_Log::ERROR);
		} else {
			while ($row = $result->fetchRow()) {
				$helper->addActivity($row);
			}
		}

		return $helper->getActivities();
	}

	/**
	 * Get the casted page number from $_GET
	 * @return int
	 */
	public static function getPageFromParam() {
		if (isset($_GET['page'])) {
			return (int) $_GET['page'];
		}

		return 1;
	}

	/**
	 * Get the filter from $_GET
	 * @return string
	 */
	public static function getFilterFromParam() {
		if (!isset($_GET['filter']))
			return 'all';

		$filterValue = $_GET['filter'];
		switch ($filterValue) {
			case 'by':
			case 'self':
			case 'shares':
			case 'all':
			case 'files':
				return $filterValue;
			default:
				if (\OC::$server->getActivityManager()->isFilterValid($filterValue)) {
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
	public static function expire($expireDays = 365) {
		$ttl = (60 * 60 * 24 * max(1, $expireDays));

		$timelimit = time() - $ttl;
		self::deleteActivities(array(
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
	public static function deleteActivities($conditions) {
		$sqlWhere = '';
		$sqlParameters = $sqlWhereList = array();
		foreach ($conditions as $column => $comparison) {
			$sqlWhereList[] = " `$column` " . ((is_array($comparison) && isset($comparison[1])) ? $comparison[1] : '=') . ' ? ';
			$sqlParameters[] = (is_array($comparison)) ? $comparison[0] : $comparison;
		}

		if (!empty($sqlWhereList)) {
			$sqlWhere = ' WHERE ' . implode(' AND ', $sqlWhereList);
		}

		$query = \OCP\DB::prepare(
			'DELETE FROM `*PREFIX*activity`' . $sqlWhere);
		$query->execute($sqlParameters);
	}
}
