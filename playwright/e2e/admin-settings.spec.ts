/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { test, expect, type Page, type Locator } from '@playwright/test'
import { login } from '@nextcloud/e2e-test-server/playwright'
import type { User } from '@nextcloud/e2e-test-server'

// The test container always has an admin user with these credentials.
const admin: User = { userId: 'admin', password: 'admin' }

// NcCheckboxRadioSwitch hides the <input>; click the associated label instead.
async function toggleCheckbox(page: Page, cb: Locator) {
	const id = await cb.getAttribute('id')
	await page.locator(`label[for="${id}"]`).scrollIntoViewIfNeeded()
	await page.locator(`label[for="${id}"]`).click()
}

test.describe('Admin settings', () => {
	test.beforeEach(async ({ page }) => {
		await login(page.request, admin)
		await page.goto('/settings/admin/activity')
	})

	test('Email enable toggle is visible', async ({ page }) => {
		await expect(
			page.locator('#activity-admin-settings').getByText('Enable notification emails'),
		).toBeVisible()
	})

	test('Default settings grid is visible', async ({ page }) => {
		await expect(page.locator('#activity-default-settings')).toBeVisible()
		await expect(
			page.locator('#activity-default-settings input[type="checkbox"]').first(),
		).toBeAttached()
	})

	test('Default settings grid toggle persists across reloads', async ({ page }) => {
		const checkboxes = page.locator("#activity-default-settings input[type='checkbox']")

		// Find first non-disabled checkbox to toggle
		let target: Locator | null = null
		for (const cb of await checkboxes.all()) {
			if (!await cb.isDisabled()) {
				target = cb
				break
			}
		}
		if (!target) {
			test.skip()
			return
		}

		const initialState = await target.isChecked()

		const saved = page.waitForResponse(
			r => r.url().includes('/activity/settings/admin') && r.request().method() === 'POST',
		)
		await toggleCheckbox(page, target)
		await saved

		await page.reload()

		const checkboxesAfter = page.locator("#activity-default-settings input[type='checkbox']")
		const targetAfter = (await checkboxesAfter.all())[0]
		await expect(targetAfter).toBeChecked({ checked: !initialState })

		// Restore original state
		const restore = page.waitForResponse(
			r => r.url().includes('/activity/settings/admin') && r.request().method() === 'POST',
		)
		await toggleCheckbox(page, targetAfter)
		await restore
	})
})
