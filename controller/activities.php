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

use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;

class Activities extends Controller {
	const DEFAULT_PAGE_SIZE = 30;

	/** @var \OCA\Activity\Data */
	protected $data;

	/** @var \OCA\Activity\GroupHelper */
	protected $helper;

	/** @var \OCA\Activity\UserSettings */
	protected $settings;

	/**
	 * constructor of the controller
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param Data $data
	 * @param GroupHelper $helper
	 * @param UserSettings $settings
	 */
	public function __construct($appName, IRequest $request, Data $data, GroupHelper $helper, UserSettings $settings) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->helper = $helper;
		$this->settings = $settings;
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int		$page
	 * @param string	$filter
	 * @return TemplateResponse
	 */
	public function fetch($page, $filter) {
		$pageOffset = $page - 1;
		$filter = $this->data->validateFilter($filter);

		return new TemplateResponse('activity', 'activities.part', [
			'activity' => $this->data->read($this->helper, $this->settings, $pageOffset * self::DEFAULT_PAGE_SIZE, self::DEFAULT_PAGE_SIZE, $filter)
		], '');
	}
}
