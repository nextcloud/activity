/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { test, expect, type Page, type Locator } from '@playwright/test'
import { createRandomUser, login } from '@nextcloud/e2e-test-server/playwright'
import type { User } from '@nextcloud/e2e-test-server'

// NcCheckboxRadioSwitch hides the <input> off-screen; clicking the associated
// <label> is the only reliable way to trigger Vue's change handler.
async function toggleCheckbox(page: Page, cb: Locator) {
	const id = await cb.getAttribute('id')
	await page.locator(`label[for="${id}"]`).scrollIntoViewIfNeeded()
	await page.locator(`label[for="${id}"]`).click()
}

test.describe("User settings persist across reloads", () => {
	let user: User

	test.beforeAll(async () => {
		user = await createRandomUser()
	})

	test.beforeEach(async ({ page }) => {
		await login(page.request, user)
		await page.goto('/settings/user/notifications')
	})

	test('Form survives a reload', async ({ page }) => {
		const checkboxes = page.locator("#activity-user-settings input[type='checkbox']")

		// Uncheck every checkbox, waiting for the settings save after each change.
		// Skip disabled checkboxes (e.g. security_email which is admin-controlled).
		for (const cb of await checkboxes.all()) {
			if (await cb.isChecked() && !await cb.isDisabled()) {
				const saved = page.waitForResponse(r =>
					r.url().includes('/activity/settings') && r.request().method() === 'POST',
				)
				await toggleCheckbox(page, cb)
				await saved
			}
		}

		await page.reload()

		for (const cb of await checkboxes.all()) {
			if (await cb.isChecked() && !await cb.isDisabled()) {
				const saved = page.waitForResponse(r =>
					r.url().includes('/activity/settings') && r.request().method() === 'POST',
				)
				await toggleCheckbox(page, cb)
				await saved
			}
		}

		const checks = [
			['#file_changed_notification', true],
			['#comments_email', true],
			['#comments_notification', true],
			['#calendar_email', true],
			['#calendar_notification', true],
			['#personal_settings_email', true],
			['#personal_settings_notification', true],
		] as [string, boolean][]

		for (const [id, check] of checks) {
			const cb = page.locator(id)
			if (await cb.isChecked() === check) continue
			if (await cb.isDisabled()) continue
			const saved = page.waitForResponse(r =>
				r.url().includes('/activity/settings') && r.request().method() === 'POST',
			)
			await toggleCheckbox(page, cb)
			await saved
		}

		await page.reload()

		await expect(page.locator('#file_changed_email')).not.toBeChecked()
		await expect(page.locator('#file_changed_notification')).toBeChecked()
		await expect(page.locator('#shared_email')).not.toBeChecked()
		await expect(page.locator('#shared_notification')).not.toBeChecked()
		await expect(page.locator('#remote_share_email')).not.toBeChecked()
		await expect(page.locator('#remote_share_notification')).not.toBeChecked()
		await expect(page.locator('#public_links_email')).not.toBeChecked()
		await expect(page.locator('#public_links_notification')).not.toBeChecked()
		await expect(page.locator('#calendar_email')).toBeChecked()
		await expect(page.locator('#calendar_notification')).toBeChecked()
		await expect(page.locator('#calendar_event_email')).not.toBeChecked()
		await expect(page.locator('#calendar_event_notification')).not.toBeChecked()
		await expect(page.locator('#calendar_todo_email')).not.toBeChecked()
		await expect(page.locator('#calendar_todo_notification')).not.toBeChecked()
		await expect(page.locator('#contacts_email')).not.toBeChecked()
		await expect(page.locator('#contacts_notification')).not.toBeChecked()
		await expect(page.locator('#group_settings_email')).not.toBeChecked()
		await expect(page.locator('#group_settings_notification')).not.toBeChecked()
		await expect(page.locator('#personal_settings_email')).not.toBeChecked()
		await expect(page.locator('#personal_settings_notification')).toBeChecked()
		await expect(page.locator('#security_email')).toBeChecked()
		await expect(page.locator('#security_notification')).not.toBeChecked()
		await expect(page.locator('#comments_email')).toBeChecked()
		await expect(page.locator('#comments_notification')).toBeChecked()
		await expect(page.locator('#systemtags_email')).not.toBeChecked()
		await expect(page.locator('#systemtags_notification')).not.toBeChecked()
	})

	test('Notification frequency survives a reload', async ({ page }) => {
		const select = page.locator('.notification-frequency__select')

		let saved = page.waitForResponse(r =>
			r.url().includes('/activity/settings') && r.request().method() === 'POST',
		)
		await select.selectOption('Weekly')
		await saved

		await page.reload()
		await expect(select).toHaveValue('2') // Weekly = 2

		saved = page.waitForResponse(r =>
			r.url().includes('/activity/settings') && r.request().method() === 'POST',
		)
		await select.selectOption('Hourly')
		await saved

		await page.reload()
		await expect(select).toHaveValue('0') // Hourly = 0
	})

	test('Activity summary survives a reload', async ({ page }) => {
		const summaryCheckbox = page
			.locator('.checkbox-radio-switch-checkbox')
			.filter({ hasText: 'Send daily activity summary in the morning' })
			.locator('input')

		let saved = page.waitForResponse(r =>
			r.url().includes('/activity/settings') && r.request().method() === 'POST',
		)
		await summaryCheckbox.check({ force: true })
		await saved
		await expect(summaryCheckbox).toBeChecked()

		await page.reload()
		await summaryCheckbox.scrollIntoViewIfNeeded()
		await expect(summaryCheckbox).toBeChecked()

		saved = page.waitForResponse(r =>
			r.url().includes('/activity/settings') && r.request().method() === 'POST',
		)
		await summaryCheckbox.uncheck({ force: true })
		await saved

		await page.reload()
		await summaryCheckbox.scrollIntoViewIfNeeded()
		await expect(summaryCheckbox).not.toBeChecked()
	})
})
