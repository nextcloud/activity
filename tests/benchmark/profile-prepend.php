<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 *
 * Loaded through auto_prepend_file: samples requests that carry an
 * X-Bench-Profile header and writes the stacks in collapsed format,
 * and clears the web server's APCu for requests with X-Bench-Clear-Cache.
 */

if (isset($_SERVER['HTTP_X_BENCH_CLEAR_CACHE']) && function_exists('apcu_clear_cache')) {
	apcu_clear_cache();
}

if (isset($_SERVER['HTTP_X_BENCH_PROFILE']) && class_exists(ExcimerProfiler::class)) {
	$benchProfiler = new ExcimerProfiler();
	$benchProfiler->setPeriod(0.001);
	$benchProfiler->setEventType(EXCIMER_REAL);
	$benchProfiler->setMaxDepth(250);
	$benchProfiler->start();

	register_shutdown_function(static function () use ($benchProfiler): void {
		$benchProfiler->stop();
		$label = preg_replace('/[^A-Za-z0-9_.-]/', '', (string)$_SERVER['HTTP_X_BENCH_PROFILE']);
		$dir = '/tmp/bench-profiles/' . $label;
		if (!is_dir($dir)) {
			@mkdir($dir, 0777, true);
		}
		file_put_contents($dir . '/' . uniqid('', true) . '.collapsed', $benchProfiler->getLog()->formatCollapsed());
	});
}
