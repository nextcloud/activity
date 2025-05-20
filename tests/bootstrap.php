<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use OCP\App\IAppManager;
use OCP\Server;
use PHPUnit\Framework\TestCase;

if (!defined('PHPUNIT_RUN')) {
	define('PHPUNIT_RUN', 1);
}

require_once __DIR__ . '/../../../lib/base.php';
require_once __DIR__ . '/../../../tests/autoload.php';

Server::get(IAppManager::class)->loadApp('activity');

// Fix for "Autoload path not allowed: .../files/lib/activity.php"
Server::get(IAppManager::class)->loadApp('files');

// Fix for "Autoload path not allowed: .../files_sharing/lib/activity.php"
Server::get(IAppManager::class)->loadApp('files_sharing');

if (!class_exists(TestCase::class)) {
	require_once('PHPUnit/Autoload.php');
}
