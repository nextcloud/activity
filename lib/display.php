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

/**
 * Class Display
 *
 * @package OCA\Activity
 */
class Display
{
	/**
	 * Show a specific event in the activities
	 * @param array $event An array with all the activity data in it
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
