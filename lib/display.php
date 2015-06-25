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

use OC\Files\View;
use OCP\Template;
use OCP\User;
use OCP\Util;

/**
 * Class Display
 *
 * @package OCA\Activity
 */
class Display {
	/** @var \OCP\IDateTimeFormatter */
	protected $dateTimeFormatter;

	/** @var \OCP\IPreview */
	protected $preview;

	/** @var \OCP\IURLGenerator */
	protected $urlGenerator;

	/** @var View */
	protected $view;

	/**
	 * Constructor
	 *
	 * @param \OCP\IDateTimeFormatter $dateTimeFormatter
	 * @param \OCP\IPreview $preview
	 * @param \OCP\IURLGenerator $urlGenerator
	 * @param View $view
	 */
	public function __construct(\OCP\IDateTimeFormatter $dateTimeFormatter,
								\OCP\IPreview $preview,
								\OCP\IURLGenerator $urlGenerator,
								\OC\Files\View $view) {
		$this->view = $view;
		$this->preview = $preview;
		$this->dateTimeFormatter = $dateTimeFormatter;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * Get the template for a specific activity-event in the activities
	 *
	 * @param array $activity An array with all the activity data in it
	 * @return string
	 */
	public function show($activity) {
		$tmpl = new Template('activity', 'stream.item');
		$tmpl->assign('formattedDate', $this->dateTimeFormatter->formatDateTime($activity['timestamp']));
		$tmpl->assign('formattedTimestamp', Template::relative_modified_date($activity['timestamp']));
		$tmpl->assign('user', $activity['user']);
		$tmpl->assign('displayName', User::getDisplayName($activity['user']));

		if (strpos($activity['subjectformatted']['markup']['trimmed'], '<a ') !== false) {
			// We do not link the subject as we create links for the parameters instead
			$activity['link'] = '';
		}

		$tmpl->assign('event', $activity);

		if ($activity['file']) {
			$this->view->chroot('/' . $activity['affecteduser'] . '/files');
			$exist = $this->view->file_exists($activity['file']);
			$is_dir = $this->view->is_dir($activity['file']);
			$tmpl->assign('previewLink', $this->getPreviewLink($activity['file'], $is_dir));

			// show a preview image if the file still exists
			$mimeType = \OCP\Files::getMimeType($activity['file']);
			if ($mimeType && !$is_dir && $this->preview->isMimeSupported($mimeType) && $exist) {
				$tmpl->assign('previewImageLink',
					$this->urlGenerator->linkToRoute('core_ajax_preview', array(
						'file' => $activity['file'],
						'x' => 150,
						'y' => 150,
					))
				);
			} else {
				$tmpl->assign('previewImageLink', Template::mimetype_icon($is_dir ? 'dir' : $mimeType));
				$tmpl->assign('previewLinkIsDir', true);
			}
		}

		return $tmpl->fetchPage();
	}

	/**
	 * @param string $path
	 * @param bool $isDir
	 * @return string
	 */
	protected function getPreviewLink($path, $isDir) {
		if ($isDir) {
			return $this->urlGenerator->linkTo('files', 'index.php', array('dir' => $path));
		} else {
			$parentDir = (substr_count($path, '/') === 1) ? '/' : dirname($path);
			$fileName = basename($path);
			return $this->urlGenerator->linkTo('files', 'index.php', array(
				'dir' => $parentDir,
				'scrollto' => $fileName,
			));
		}

	}
}
