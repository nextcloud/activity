/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { test, expect } from '@playwright/test'
import { createRandomUser, login } from '@nextcloud/e2e-test-server/playwright'
import { createFolder, getFileListRow, moveFile, navigateToFolder, renameFile } from './filesUtils.ts'
import { addTag, closeToasts, createPublicShare, randHash, showActivityTab, toggleFavorite } from './sidebarUtils.ts'

// Each test gets a fresh user so activity logs are clean and isolated.
test.beforeEach(async ({ page }) => {
	const user = await createRandomUser()
	await login(page.request, user)
	await page.goto('/apps/files')
	await expect(getFileListRow(page, 'welcome.txt')).toBeVisible()
})

test('Has creation activity', async ({ page }) => {
	await showActivityTab(page, 'welcome.txt')
	await expect(page.locator('.activity-entry').last()).toContainText('You created')
})

test('Has favorite activity', async ({ page }) => {
	await toggleFavorite(page, 'welcome.txt')
	await showActivityTab(page, 'welcome.txt')
	await expect(page.locator('.activity-entry').first()).toContainText('Added to favorites')

	await page.goto('/apps/files')
	await expect(getFileListRow(page, 'welcome.txt')).toBeVisible()

	await toggleFavorite(page, 'welcome.txt')
	await showActivityTab(page, 'welcome.txt')
	await expect(page.locator('.activity-entry').first()).toContainText('Removed from favorites')
})

test('Has share activity', async ({ page }) => {
	await createPublicShare(page, 'welcome.txt')
	await showActivityTab(page, 'welcome.txt')
	await expect(page.locator('.activity-entry').filter({ hasText: 'Shared as public link' }).first()).toBeVisible()
})

test('Has rename activity', async ({ page }) => {
	await renameFile(page, 'welcome.txt', 'new name.txt')
	await renameFile(page, 'new name.txt', 'welcome.txt')

	await showActivityTab(page, 'welcome.txt')
	await expect(page.locator('.activity-entry').first()).toContainText('You renamed')
})

test('Has move activity', async ({ page }) => {
	await createFolder(page, 'Test folder')
	await moveFile(page, 'welcome.txt', 'Test folder')
	await closeToasts(page)
	await navigateToFolder(page, 'Test folder')
	await showActivityTab(page, 'welcome.txt')
	await expect(page.locator('.activity-entry').first()).toContainText('You moved')
})

test('Has tag activity', async ({ page }) => {
	await addTag(page, 'welcome.txt', `my_tag_${randHash()}`)
	await page.goto('/apps/files')

	await showActivityTab(page, 'welcome.txt')
	await expect(page.locator('.activity-entry').first()).toContainText('Added system tag')
})

// TODO: re-enable when comments app is fixed
test.skip('Has comment activity', async ({ page }) => {
	// addComment not implemented yet
})
