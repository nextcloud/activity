<?php
/**
 * Nextcloud - Activity Widget for Dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Widgets;


use OCA\Activity\AppInfo\Application;
use OCA\Activity\Widgets\Service\FilesActivityService;
use OCA\Dashboard\IDashboardWidget;
use OCA\Dashboard\Model\WidgetRequest;
use OCA\Dashboard\Model\WidgetSettings;
use OCP\AppFramework\QueryException;

class FilesActivityWidget implements IDashboardWidget {


	const WIDGET_ID = 'activity_files';


	/** @var FilesActivityService */
	private $filesActivityService;

	/** @var WidgetSettings */
	private $settings;


	/**
	 * @return string
	 */
	public function getId() {
		return self::WIDGET_ID;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return 'Files Activity';
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return 'Stay updated';
	}


	/**
	 * @return array
	 */
	public function getTemplate() {
		return [
			'app'      => 'activity',
			'icon'     => 'icon-folder',
			'css'      => 'widgets/filesActivity',
			'js'       => 'widgets/filesActivity',
			'content'  => 'widgets/filesActivity',
			'function' => 'OCA.DashBoard.filesActivity.init'
		];
	}


	/**
	 * @return array
	 */
	public function widgetSetup() {
		return [
			'size'   => [
				'min'     => [
					'width'  => 5,
					'height' => 2
				],
				'default' => [
					'width'  => 6,
					'height' => 3
				]
			],
			'push'   => 'OCA.DashBoard.filesActivity.push',
			'resize' => 'OCA.DashBoard.filesActivity.onResize'
		];
	}


	/**
	 * @param WidgetSettings $settings
	 */
	public function loadWidget($settings) {
		$app = new Application();

		$this->settings = $settings;
		$container = $app->getContainer();
		try {
			$this->filesActivityService = $container->query(FilesActivityService::class);
		} catch (QueryException $e) {
			return;
		}
	}


	/**
	 * @param WidgetRequest $request
	 */
	public function requestWidget(WidgetRequest $request) {

		$limit = $this->filesActivityService->defineLimit($this->settings->getPosition()['height']);
		if ($request->getRequest() === 'getFilesActivity') {
			$request->addResult(
				'filesActivity', $this->filesActivityService->getFilesActivities($limit)
			);
		}
	}

}