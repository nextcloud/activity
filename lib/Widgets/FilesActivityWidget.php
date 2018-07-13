<?php declare(strict_types=1);


/**
 * Nextcloud - Activity Widget for Dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2018, Maxence Lange <maxence@artificial-owl.com>
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
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetSettings;
use OCP\AppFramework\QueryException;
use OCP\IL10N;
use OCP\L10N\IFactory;

class FilesActivityWidget implements IDashboardWidget {


	const WIDGET_ID = 'activity_files';


	/** @var IL10N */
	private $l10n;

	/** @var FilesActivityService */
	private $filesActivityService;

	/** @var IWidgetSettings */
	private $settings;


	public function __construct(IFactory $factory) {
		$this->l10n = $factory->get('activity');
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return self::WIDGET_ID;
	}


	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->l10n->t('Files');
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->l10n->t('Stay updated of your files activity');
	}


	/**
	 * @return array
	 */
	public function getTemplate(): array {
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
	public function widgetSetup(): array {
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
	 * @param IWidgetSettings $settings
	 */
	public function loadWidget(IWidgetSettings $settings) {
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
	 * @param IWidgetRequest $request
	 */
	public function requestWidget(IWidgetRequest $request) {

		$limit = $this->filesActivityService->defineLimit(
			intval($this->settings->getPosition()['height'])
		);

		if ($request->getRequest() === 'getFilesActivity') {
			$request->addResult(
				'filesActivity', $this->filesActivityService->getFilesActivities($limit)
			);
		}
	}

}