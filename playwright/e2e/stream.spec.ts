/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { test, expect, type Page, type Locator } from '@playwright/test'
import { createRandomUser, login } from '@nextcloud/e2e-test-server/playwright'
import { getFileListRow } from './filesUtils.ts'

// NcCheckboxRadioSwitch hides the <input>; clicking the label is the reliable way.
async function toggleCheckbox(page: Page, cb: Locator) {
	const id = await cb.getAttribute('id')
	await page.locator(`label[for="${id}"]`).scrollIntoViewIfNeeded()
	await page.locator(`label[for="${id}"]`).click()
}

// Navigate without a leading slash so Playwright prepends the baseURL's index.php/
// prefix. The Vue Router base is generateUrl('/apps/activity') = /index.php/apps/activity,
// so the URL must go through index.php for the SPA router to match.
// Navigate directly to /all to avoid the client-side redirect from /.
const STREAM_URL = 'apps/activity/all'

test.beforeEach(async ({ page }) => {
	const user = await createRandomUser()
	await login(page.request, user)
	// Seed activity: visiting the files app triggers file creation events
	await page.goto('/apps/files')
	await expect(getFileListRow(page, 'welcome.txt')).toBeVisible()
})

test('Shows activity entries on load', async ({ page }) => {
	await page.goto(STREAM_URL)
	// Wait for the Vue app to boot and either show activities or an empty state
	await expect(
		page.locator('.activity-entry, .activity-app__empty-content').first(),
	).toBeVisible({ timeout: 30000 })
	await expect(page.locator('.activity-entry').first()).toBeVisible()
})

test('Heading reflects the active filter', async ({ page }) => {
	await page.goto(STREAM_URL)
	await expect(page.locator('.activity-app__heading')).toBeVisible({ timeout: 30000 })
	await expect(page.locator('.activity-app__heading')).toContainText('All activities')
})

test('Navigation filter loads filtered stream', async ({ page }) => {
	await page.goto(STREAM_URL)
	await expect(page.locator('.activity-app__heading')).toBeVisible({ timeout: 30000 })

	await expect(page.locator('[data-navigation="all"]')).toBeVisible()

	const filesResponse = page.waitForResponse(/apps\/activity\/api\/v2\/activity\/files/)
	await page.locator('[data-navigation="files"]').click()
	await filesResponse

	await expect(page.locator('.activity-app__heading')).toContainText('File')
})

test('RSS feed toggle shows and hides the feed URL', async ({ page }) => {
	await page.goto(STREAM_URL)
	await expect(page.locator('.activity-app__heading')).toBeVisible({ timeout: 30000 })

	await expect(page.getByRole('textbox', { name: 'RSS feed' })).not.toBeVisible()

	// Open the navigation settings section
	await page.getByRole('button', { name: 'Activity settings' }).click()

	// NcCheckboxRadioSwitch type="switch" wraps the input inside the label —
	// click the visible wrapper span, not label[for="..."].
	const rssSwitch = page.locator('.checkbox-radio-switch').filter({ hasText: 'Enable RSS feed' })
	await rssSwitch.scrollIntoViewIfNeeded()

	const enableResponse = page.waitForResponse(
		r => r.url().includes('/activity/settings/feed') && r.request().method() === 'POST',
	)
	await rssSwitch.click()
	await enableResponse

	await expect(page.getByRole('textbox', { name: 'RSS feed' })).toBeVisible()

	const disableResponse = page.waitForResponse(
		r => r.url().includes('/activity/settings/feed') && r.request().method() === 'POST',
	)
	await rssSwitch.click()
	await disableResponse

	await expect(page.getByRole('textbox', { name: 'RSS feed' })).not.toBeVisible()
})
