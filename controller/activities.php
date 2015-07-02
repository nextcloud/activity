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

namespace OCA\Activity\Controller;

use OC\Files\View;
use OCA\Activity\Data;
use OCA\Activity\Display;
use OCA\Activity\GroupHelper;
use OCA\Activity\Navigation;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Files;
use OCP\IDateTimeFormatter;
use OCP\IPreview;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Template;

class Activities extends Controller {
	const DEFAULT_PAGE_SIZE = 30;

	/** @var \OCA\Activity\Data */
	protected $data;

	/** @var \OCA\Activity\Display */
	protected $display;

	/** @var \OCA\Activity\GroupHelper */
	protected $helper;

	/** @var \OCA\Activity\Navigation */
	protected $navigation;

	/** @var \OCA\Activity\UserSettings */
	protected $settings;

	/** @var IDateTimeFormatter */
	protected $dateTimeFormatter;

	/** @var IPreview */
	protected $preview;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var View */
	protected $view;

	/** @var string */
	protected $user;

	/**
	 * constructor of the controller
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param Data $data
	 * @param Display $display
	 * @param GroupHelper $helper
	 * @param Navigation $navigation
	 * @param UserSettings $settings
	 * @param IDateTimeFormatter $dateTimeFormatter
	 * @param IPreview $preview
	 * @param IURLGenerator $urlGenerator
	 * @param View $view
	 * @param string $user
	 */
	public function __construct($appName,
								IRequest $request,
								Data $data,
								Display $display,
								GroupHelper $helper,
								Navigation $navigation,
								UserSettings $settings,
								IDateTimeFormatter $dateTimeFormatter,
								IPreview $preview,
								IURLGenerator $urlGenerator,
								View $view,
								$user) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->display = $display;
		$this->helper = $helper;
		$this->navigation = $navigation;
		$this->settings = $settings;
		$this->dateTimeFormatter = $dateTimeFormatter;
		$this->preview = $preview;
		$this->urlGenerator = $urlGenerator;
		$this->view = $view;
		$this->user = $user;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $filter
	 * @return TemplateResponse
	 */
	public function showList($filter = 'all') {
		$filter = $this->data->validateFilter($filter);

		return new TemplateResponse('activity', 'stream.body', [
			'appNavigation'	=> $this->navigation->getTemplate($filter),
			'filter'		=> $filter,
		]);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $page
	 * @param string $filter
	 * @return JSONResponse
	 */
	public function fetch($page, $filter = 'all') {
		$pageOffset = $page - 1;
		$filter = $this->data->validateFilter($filter);

		$activities = $this->data->read($this->helper, $this->settings, $pageOffset * self::DEFAULT_PAGE_SIZE, self::DEFAULT_PAGE_SIZE, $filter);

		$preparedActivities = [];
		foreach ($activities as $activity) {
			$activity['relativeTimestamp'] = (string) Template::relative_modified_date($activity['timestamp'], true);
			$activity['readableTimestamp'] = (string) $this->dateTimeFormatter->formatDate($activity['timestamp']);
			$activity['relativeDateTimestamp'] = (string) Template::relative_modified_date($activity['timestamp']);
			$activity['readableDateTimestamp'] = (string) $this->dateTimeFormatter->formatDateTime($activity['timestamp']);

			if (strpos($activity['subjectformatted']['markup']['trimmed'], '<a ') !== false) {
				// We do not link the subject as we create links for the parameters instead
				$activity['link'] = '';
			}

			if ($activity['file']) {
				$this->view->chroot('/' . $activity['affecteduser'] . '/files');
				$exist = $this->view->file_exists($activity['file']);
				$is_dir = $this->view->is_dir($activity['file']);
				$activity['preview'] = [
					'link'			=> $this->getPreviewLink($activity['file'], $is_dir),
					'source'		=> '',
					'isMimeTypeIcon' => true,
				];

				// show a preview image if the file still exists
				if ($is_dir) {
					$activity['preview']['source'] = Template::mimetype_icon('dir');
				} else {
					$mimeType = Files::getMimeType($activity['file']);
					if (!$is_dir && $mimeType && $this->preview->isMimeSupported($mimeType) && $exist) {
						$activity['preview']['isMimeTypeIcon'] = false;
						$activity['preview']['source'] = $this->urlGenerator->linkToRoute('core_ajax_preview', [
							'file' => $activity['file'],
							'x' => 150,
							'y' => 150,
						]);
					} else {
						$activity['preview']['source'] = Template::mimetype_icon($mimeType);
					}
				}
			}

			$preparedActivities[] = $activity;
		}

		return new JSONResponse($preparedActivities);
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
