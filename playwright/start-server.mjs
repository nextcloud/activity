/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import {
	configureNextcloud,
	runExec,
	runOcc,
	startNextcloud,
	stopNextcloud,
	waitOnNextcloud,
} from '@nextcloud/e2e-test-server/docker'

async function stop() {
	process.stderr.write('Stopping Nextcloud server…\n')
	// Only tear down the container in CI; locally we leave it running so the
	// next test run can reuse it without a slow cold start.
	if (process.env.CI) {
		await stopNextcloud()
	}
	process.exit(0)
}

process.on('SIGTERM', stop)
process.on('SIGINT', stop)

const ip = await startNextcloud(process.env.BRANCH ?? 'master', undefined, { exposePort: 8081 })
await waitOnNextcloud(ip)
await configureNextcloud(['viewer', 'activity'])
await runOcc(['config:system:set', 'no_unsupported_browser_warning', '--value', 'true', '--type', 'boolean'])
await runOcc(['config:system:set', 'appstoreenabled', '--value', 'false', '--type', 'boolean'])
await runExec(['php', '-r', '$db = new SQLite3("data/owncloud.db");$db->busyTimeout(5000);$db->exec("PRAGMA journal_mode = wal;");'])
await runExec(['php', 'cron.php'])

process.stdout.write('Nextcloud ready at http://localhost:8081\n')

// Keep the process alive so Playwright's gracefulShutdown can signal us.
while (true) {
	await new Promise((resolve) => setTimeout(resolve, 5000))
}
