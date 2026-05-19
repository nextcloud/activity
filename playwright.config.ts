/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { defineConfig, devices } from '@playwright/test'

// Defaults to the isolated test container on port 8081 started by start-server.mjs.
// Override with PLAYWRIGHT_BASE_URL to use an existing instance, e.g.:
//   PLAYWRIGHT_BASE_URL=http://nextcloud.local npx playwright test
// Note: createRandomUser() uses Docker exec into nextcloud-e2e-test-server_activity,
// so it only works against the managed test container, not the main dev instance.
const baseURL = process.env.PLAYWRIGHT_BASE_URL ?? 'http://localhost:8081'

export default defineConfig({
	testDir: './playwright',

	fullyParallel: true,
	forbidOnly: !!process.env.CI,
	retries: process.env.CI ? 1 : 0,
	workers: 1,

	reporter: process.env.CI ? [['blob'], ['dot'], ['github']] : 'html',

	use: {
		baseURL: baseURL + '/index.php/',
		trace: 'on-first-retry',
		video: 'on-first-retry',
	},

	projects: [
		{
			name: 'chromium',
			use: { ...devices['Desktop Chrome'] },
		},
	],

	webServer: {
		command: 'node playwright/start-server.mjs',
		// Wait for the ready log line instead of polling the URL — avoids false
		// positives where the HTTP port opens before Nextcloud finishes booting.
		wait: {
			stdout: /Nextcloud ready at/,
		},
		gracefulShutdown: {
			signal: 'SIGTERM',
			timeout: 10000,
		},
		// Reuse a container left running from a previous local run.
		// In CI the container won't exist yet, so start-server.mjs will create it.
		reuseExistingServer: !process.env.CI,
		stdout: 'pipe',
		stderr: 'pipe',
		timeout: 5 * 60 * 1000,
	},
})
