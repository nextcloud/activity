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

namespace OCA\Activity\BackgroundJob;

use OC\BackgroundJob\TimedJob;
use OCA\Activity\AppInfo\Application;
use OCA\Activity\Data;
use OCP\IConfig;

/**
 * Class ExpireActivities
 *
 * @package OCA\Activity\BackgroundJob
 */
class ExpireActivities extends TimedJob {
	/** @var Data */
	protected $data;
	/** @var IConfig */
	protected $config;

	/**
	 * @param Data $data
	 * @param IConfig $config
	 */
	public function __construct(Data $data = null, IConfig $config = null) {
		// Run once per day
		$this->setInterval(60 * 60 * 24);

		if ($data === null || $config === null) {
			$this->fixDIForJobs();
		} else {
			$this->data = $data;
			$this->config = $config;
		}
	}

	protected function fixDIForJobs() {
		$application = new Application();

		$this->data = $application->getContainer()->query('ActivityData');
		$this->config = \OC::$server->getConfig();
	}

	protected function run($argument) {
		// Remove activities that are older then one year
		$expireDays = $this->config->getSystemValue('activity_expire_days', 365);
		$this->data->expire($expireDays);
	}
}
