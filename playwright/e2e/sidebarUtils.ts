/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { Page } from '@playwright/test'
import { expect } from '@playwright/test'
import { getRowForFile, toggleMenuAction } from './filesUtils.ts'

export const randHash = () =>
	Math.random().toString(36).replace(/[^a-z]+/g, '').slice(0, 10)

async function showSidebarForFile(page: Page, fileName: string) {
	await toggleMenuAction(page, fileName, 'details')
	await expect(page.locator('#app-sidebar-vue')).toBeVisible()
}

export async function closeSidebar(page: Page) {
	await page.locator('[data-cy-sidebar] .app-sidebar__close').click({ force: true })
	await expect(page.locator('[data-cy-sidebar]')).not.toBeVisible()
}

export async function closeToasts(page: Page) {
	for (const btn of await page.locator('button.toast-close').all()) {
		await btn.click()
	}
}

export async function showActivityTab(page: Page, fileName: string) {
	const activitiesResponse = page.waitForResponse(/\/ocs\/v2\.php\/apps\/activity\/api\/v2\/activity\/filter/)

	await showSidebarForFile(page, fileName)

	await page.locator('#app-sidebar-vue').getByRole('tab', { name: 'Activity' }).click({ force: true })
	await expect(page.locator('#app-sidebar-vue').getByRole('tabpanel', { name: 'Activity' })).toBeVisible()

	await activitiesResponse
}

export async function toggleFavorite(page: Page, fileName: string) {
	const tagResponse = page.waitForResponse(/\/index\.php\/apps\/files\/api\/v1\/files\//)
	await toggleMenuAction(page, fileName, 'favorite')
	await tagResponse
	await page.locator('.toast-close').click()
}

export async function createPublicShare(page: Page, fileName: string) {
	await showSidebarForFile(page, fileName)
	await page.locator('#app-sidebar-vue').getByRole('tab', { name: 'Sharing' }).click()

	const shareResponse = page.waitForResponse(/\/ocs\/v2\.php\/apps\/files_sharing\/api\/v1\/shares/)

	await expect(page.locator('#app-sidebar-vue').getByRole('tabpanel', { name: 'Sharing' })).toBeVisible()
	await page.locator('#app-sidebar-vue').getByRole('button', { name: 'Create a new share link' }).click({ force: true })

	await shareResponse
	await closeSidebar(page)
}

export async function addTag(page: Page, fileName: string, newTag: string) {
	await getRowForFile(page, fileName).locator('.files-list__row-icon-preview--loaded').waitFor()

	await toggleMenuAction(page, fileName, 'systemtags:bulk' as 'details')

	const createTagResponse = page.waitForResponse(/\/remote\.php\/dav\/systemtags$/)

	await page.locator('[data-cy-systemtags-picker-input]').fill(newTag)
	await expect(page.locator('[data-cy-systemtags-picker-tag]')).toHaveCount(0)
	await page.locator('[data-cy-systemtags-picker-button-create]').click()

	await createTagResponse

	await expect(
		page.locator('[data-cy-systemtags-picker-tag]')
			.filter({ hasText: newTag })
			.locator('input[type="checkbox"]'),
	).toBeChecked()

	const assignResponse = page.waitForResponse(/\/remote\.php\/dav\/systemtags\/.*\/files/)
	await page.locator('[data-cy-systemtags-picker-button-submit]').click()
	await assignResponse

	await expect(page.locator('[data-cy-systemtags-picker]')).not.toBeVisible()
}
