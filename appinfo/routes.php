<?php

/**
 * ownCloud - Activity App
 *
 * @author Frank Karlitschek
 * @copyright 2013 Frank Karlitschek frank@owncloud.org
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

namespace OCA\Activity\AppInfo;

use OCP\API;

$application = new Application();

// Register an OCS API call
API::register(
	'get',
	'/apps/activity/api/v2/activity',
	array($application->getContainer()->query('OCA\Activity\Controller\OCSEndPoint'), 'getDefault'),
	'activity'
);
API::register(
	'get',
	'/apps/activity/api/v2/activity/{filter}',
	array($application->getContainer()->query('OCA\Activity\Controller\OCSEndPoint'), 'getFilter'),
	'activity'
);
API::register(
	'get',
	'/cloud/activity',
	array('OCA\Activity\Api', 'get'),
	'activity'
);

$application->registerRoutes($this, ['routes' => [
	['name' => 'Settings#personal', 'url' => '/settings', 'verb' => 'POST'],
	['name' => 'Settings#feed', 'url' => '/settings/feed', 'verb' => 'POST'],
	['name' => 'Activities#showList', 'url' => '/', 'verb' => 'GET'],
	['name' => 'Feed#show', 'url' => '/rss.php', 'verb' => 'GET'],
]]);
