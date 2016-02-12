<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity\Controller;

use OC\Files\View;
use OCA\Activity\Data;
use OCA\Activity\Exception\InvalidFilterException;
use OCA\Activity\GroupHelper;
use OCA\Activity\Navigation;
use OCA\Activity\UserSettings;
use OCA\Activity\ViewInfoCache;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Files;
use OCP\Files\IMimeTypeDetector;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IPreview;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Template;

class Activities extends Controller {

	/** @var \OCA\Activity\Data */
	protected $data;

	/** @var \OCA\Activity\Navigation */
	protected $navigation;

	/** @var \OCP\IConfig */
	protected $config;

	/**
	 * constructor of the controller
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param IConfig $config
	 * @param Data $data
	 * @param Navigation $navigation
	 */
	public function __construct($appName,
								IRequest $request,
								IConfig $config,
								Data $data,
								Navigation $navigation) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->config = $config;
		$this->navigation = $navigation;
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
			'avatars'		=> $this->config->getSystemValue('enable_avatars', true) ? 'yes' : 'no',
			'filter'		=> $filter,
		]);
	}
}
