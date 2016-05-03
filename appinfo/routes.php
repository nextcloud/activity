<?php
/**
 * @author Frank Karlitschek <frank@owncloud.org>
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Lukas Reschke <lukas@owncloud.com>
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

use OCP\API;

$app = new \OCA\Activity\AppInfo\Application();

// Register an OCS API call
//API::register(
//	'get',
//	'/apps/activity/api/v2/activity',
//	array($application->getContainer()->query('OCA\Activity\Controller\OCSEndPoint'), 'getDefault'),
//	'activity'
//);
//API::register(
//	'get',
//	'/apps/activity/api/v2/activity/{filter}',
//	array($application->getContainer()->query('OCA\Activity\Controller\OCSEndPoint'), 'getFilter'),
//	'activity'
//);
API::register(
	'get',
	'/cloud/activity',
	array('OCA\Activity\Api', 'get'),
	'activity'
);

$app->registerRoutes($this, ['routes' => [
	['name' => 'Settings#personal', 'url' => '/settings', 'verb' => 'POST'],
	['name' => 'Settings#feed', 'url' => '/settings/feed', 'verb' => 'POST'],
	['name' => 'Activities#showList', 'url' => '/', 'verb' => 'GET'],
	['name' => 'Feed#show', 'url' => '/rss.php', 'verb' => 'GET'],
	['name' => 'EndPoint#getDefault', 'url' => '/api/v2/activity', 'verb' => 'GET'],
	['name' => 'EndPoint#getFilter', 'url' => '/api/v2/activity/{filter}', 'verb' => 'GET'],
]]);
