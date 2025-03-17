<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
		['name' => 'Feed#show', 'url' => '/rss.php', 'verb' => 'GET'],
		['name' => 'Activities#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'Activities#showList', 'url' => '/{filter}', 'verb' => 'GET'],
	],
];
