/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import {
	configureNextcloud,
	runOcc,
	startNextcloud,
	waitOnNextcloud,
} from '@nextcloud/e2e-test-server/docker'

const ip = await startNextcloud(process.env.BRANCH ?? 'master', undefined, { exposePort: 8081 })
await waitOnNextcloud(ip)
await configureNextcloud(['viewer', 'activity'])
await runOcc(['config:system:set', 'no_unsupported_browser_warning', '--value', 'true', '--type', 'boolean'])

console.log(`Nextcloud ready at http://localhost:8081`)

// Keep the process alive so Playwright knows the server is managed.
// The Docker container runs independently and outlives this process.
process.stdin.resume()
