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

return [
	'ocs' => [
		['name' => 'RemoteActivity#receiveActivity', 'url' => '/api/v2/remote/{token}', 'verb' => 'POST'],
		['name' => 'APIv1#get', 'url' => '/activity', 'verb' => 'GET', 'root' => '/cloud'],
		['name' => 'APIv2#getDefault', 'url' => '/api/v2/activity', 'verb' => 'GET'],
		['name' => 'APIv2#listFilters', 'url' => '/api/v2/activity/filters', 'verb' => 'GET'],
		['name' => 'APIv2#getFilter', 'url' => '/api/v2/activity/{filter}', 'verb' => 'GET'],
	],
	'routes' => [
		['name' => 'Settings#personal', 'url' => '/settings', 'verb' => 'POST'],
		['name' => 'Settings#admin', 'url' => '/settings/admin', 'verb' => 'POST'],
		['name' => 'Settings#feed', 'url' => '/settings/feed', 'verb' => 'POST'],
		['name' => 'Activities#showList', 'url' => '/', 'verb' => 'GET'],
		['name' => 'Feed#show', 'url' => '/rss.php', 'verb' => 'GET'],
	],
];
