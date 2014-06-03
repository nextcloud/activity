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

	const EMAIL_SEND_HOURLY = 0;
	const EMAIL_SEND_DAILY = 1;
	const EMAIL_SEND_WEEKLY = 2;

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

	public static function getNotificationTypes($l) {
		return array(
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
	}

	public static function getUserDefaultSetting($method, $type) {
		if ($method == 'setting' && $type == 'batchtime') {
			return 3600;
		}

		$settings = self::getUserDefaultSettings($method);
		return in_array($type, $settings);
	}

	public static function getUserDefaultSettings($method) {
		$settings = array();
		switch ($method) {
			case 'stream':
				$settings[] = Data::TYPE_SHARE_CREATED;
				$settings[] = Data::TYPE_SHARE_CHANGED;
				$settings[] = Data::TYPE_SHARE_DELETED;
//				$settings[] = Data::TYPE_SHARE_RESHARED;
//
//				$settings[] = Data::TYPE_SHARE_DOWNLOADED;

			case 'email':
				$settings[] = Data::TYPE_SHARED;
//				$settings[] = Data::TYPE_SHARE_EXPIRED;
//				$settings[] = Data::TYPE_SHARE_UNSHARED;
//
//				$settings[] = Data::TYPE_SHARE_UPLOADED;
//
//				$settings[] = Data::TYPE_STORAGE_QUOTA_90;
//				$settings[] = Data::TYPE_STORAGE_FAILURE;
		}

		return $settings;
	}

	/**
	 * @brief Send an event into the activity stream
	 * @param string $app The app where this event is associated with
	 * @param string $subject A short description of the event
	 * @param string $message A longer description of the event
	 * @param string $file The file including path where this event is associated with. (optional)
	 * @param string $link A link where this event is associated with (optional)
	 * @return boolean
	 */
	public static function send($app, $subject, $subjectparams = array(), $message = '', $messageparams = array(), $file = '', $link = '', $affecteduser = '', $type = 0, $prio = Data::PRIORITY_MEDIUM) {
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
	 * @param string $user
	 * @param string $method
	 * @param string $type
	 * @return string|int
	 */
	public static function getUserSetting($user, $method, $type) {
		return \OCP\Config::getUserValue(
			$user,
			'activity',
			'notify_' . $method . '_' . $type,
			\OCA\Activity\Data::getUserDefaultSetting($method, $type)
		);
	}

	/**
	 * @param string	$user	Name of the user
	 * @param string	$method	Should be one of 'stream', 'email'
	 * @param string	$filter	Further filter the activities
	 * @return string	Part of the SQL query limiting the activities
	 */
	public static function getUserNotificationTypesQuery($user, $method, $filter) {
		$l = \OC_L10N::get('activity');
		$types = \OCA\Activity\Data::getNotificationTypes($l);

		$userActivities = array();
		foreach ($types as $type => $desc) {
			if (self::getUserSetting($user, $method, $type)) {
				$userActivities[] = $type;
			}
		}

		$userActivities = self::filterNotificationTypes($userActivities, $filter);

		// We don't want to display any activities
		if (empty($userActivities)) {
			return '1 = 0';
		}

		return "`type` IN ('" . implode("','", $userActivities) . "')";
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
		return $types;
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
		$parameters = array($user);
		$limitActivities = 'AND ' . self::getUserNotificationTypesQuery($user, 'stream', $filter);
		if ($filter === 'self') {
			$limitActivities .= ' AND `user` = ?';
			$parameters[] = $user;
		}
		else if ($filter === 'by') {
			$limitActivities .= ' AND `user` <> ?';
			$parameters[] = $user;
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
	 * @brief Get a list of events which contain the query string
	 * @param string $txt The query string
	 * @param int $count The number of statements to read
	 * @return array
	 */
	public static function search($txt, $count) {
		// get current user
		$user = \OCP\User::getUser();
		$limitActivitiesType = 'AND ' . self::getUserNotificationTypesQuery($user, 'stream');

		// search in DB
		$query = \OCP\DB::prepare(
			'SELECT * '
			. ' FROM `*PREFIX*activity` '
			. 'WHERE `affecteduser` = ? AND ((`subject` LIKE ?) OR (`message` LIKE ?) OR (`file` LIKE ?)) ' . $limitActivitiesType
			. 'ORDER BY `timestamp` desc'
			, $count);
		$result = $query->execute(array($user, '%' . $txt . '%', '%' . $txt . '%', '%' . $txt . '%')); //$result = $query->execute(array($user,'%'.$txt.''));

		return self::getActivitiesFromQueryResult($result, false);
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
	 * @brief Show a specific event in the activities
	 * @param array $event An array with all the event data in it
	 */
	public static function show($event) {
		$tmpl = new \OCP\Template('activity', 'activity.box');
		$tmpl->assign('formattedDate', \OCP\Util::formatDate($event['timestamp']));
		$tmpl->assign('formattedTimestamp', \OCP\relative_modified_date($event['timestamp']));
		$tmpl->assign('user', $event['user']);
		$tmpl->assign('displayName', \OCP\User::getDisplayName($event['user']));
		$tmpl->assign('event', $event);
		$tmpl->assign('typeIcon', self::getTypeIcon($event['type']));
		$tmpl->assign('isGrouped', !empty($event['isGrouped']));

		$rootView = new \OC\Files\View('');
		if ($event['file'] !== null){
			$exist = $rootView->file_exists('/' . $event['user'] . '/files' . $event['file']);
			$is_dir = $rootView->is_dir('/' . $event['user'] . '/files' . $event['file']);
			unset($rootView);

			// show a preview image if the file still exists
			if (!$is_dir && $exist) {
				$tmpl->assign('previewLink', \OCP\Util::linkTo('files', 'index.php', array('dir' => dirname($event['file']))));
				$tmpl->assign('previewImageLink',
					\OCP\Util::linkToRoute('core_ajax_preview', array(
						'file' => $event['file'],
						'x' => 150,
						'y' => 150,
					))
				);
			} else if ($exist) {
				$tmpl->assign('previewLink', \OCP\Util::linkTo('files', 'index.php', array('dir' => dirname($event['file']))));
				$tmpl->assign('previewImageLink', \OC_Helper::mimetypeIcon('dir'));
				$tmpl->assign('previewLinkIsDir', true);
			}
		}

		$tmpl->printPage();
	}

	/**
	 * @param string $type
	 * @return string
	 */
	public static function getTypeIcon($type)
	{
		switch ($type)
		{
			case self::TYPE_SHARE_CHANGED:
				return 'icon-change';
			case self::TYPE_SHARE_CREATED:
				return 'icon-add-color';
			case self::TYPE_SHARE_DELETED:
				return 'icon-delete-color';
			case self::TYPE_SHARED:
				return 'icon-share';
		}
		return '';
	}

	/**
	 * Get the casted page number from $_GET
	 * @param string $paramName
	 * @return int
	 */
	public static function getPageFromParam($paramName = 'page') {
		if (isset($_GET[$paramName])) {
			return (int) $_GET[$paramName];
		}

		return 1;
	}

	/**
	 * Get the casted page number from $_GET
	 * @param string $paramName
	 * @return int
	 */
	public static function getFilterFromParam($paramName = 'filter') {
		switch ($_GET[$paramName]) {
			case 'by':
			case 'self':
			case 'shares':
			case 'all':
				return $_GET[$paramName];
			default:
				// @todo Emit event
				return 'all';
		}
	}

	/**
	 * @brief Expire old events
	 * @param int $expireDays Minimum 1 day
	 * @return null
	 */
	public static function expire($expireDays = 365) {
		$ttl = (60 * 60 * 24 * max(1, $expireDays));

		$timelimit = time() - $ttl;
		$query = \OCP\DB::prepare('DELETE FROM `*PREFIX*activity` where `timestamp` < ?');
		$query->execute(array($timelimit));
	}
}
