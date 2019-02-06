<?php
/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
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

namespace OCA\Activity;

use OCP\Activity\IManager;
use OCP\IL10N;
use OCP\ILogger;
use OCP\RichObjectStrings\IValidator;

class GroupHelperDisabled extends GroupHelper {

	public function __construct(IL10N $l,
								IManager $activityManager,
								IValidator $richObjectValidator,
								ILogger $logger) {
		parent::__construct($l,
							$activityManager,
							$richObjectValidator,
							$logger);
		$this->allowGrouping = false;
	}
}
