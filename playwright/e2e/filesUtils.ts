/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { Page } from '@playwright/test'
import { expect } from '@playwright/test'

// CSS.escape is a browser API unavailable in Node.js (where Playwright specs run)
const cssEscape = (value: string) => value.replace(/([^\w-])/g, '\\$1')

export const getRowForFile = (page: Page, filename: string) =>
	page.locator(`[data-cy-files-list-row-name="${cssEscape(filename)}"]`)

export const getActionButtonForFile = (page: Page, filename: string) =>
	getRowForFile(page, filename)
		.locator('[data-cy-files-list-row-actions]')
		.getByRole('button', { name: 'Actions' })

export const triggerActionForFile = async (page: Page, filename: string, actionId: string) => {
	await getActionButtonForFile(page, filename).click({ force: true })
	await page
		.locator(`[data-cy-files-list-row-action="${cssEscape(actionId)}"]`)
		.getByRole('menuitem')
		.last()
		.click({ force: true })
}

export const toggleMenuAction = async (
	page: Page,
	filename: string,
	action: 'details' | 'favorite' | 'move-copy' | 'rename',
) => {
	// Wait for preview to load before interacting — ensures the row has finished
	// receiving metadata updates so the Actions menu won't be interrupted by a re-render.
	await getRowForFile(page, filename)
		.locator('.files-list__row-icon-preview--loaded')
		.waitFor()

	const fileRow = getRowForFile(page, filename)
	await fileRow.scrollIntoViewIfNeeded()
	// Hover first so the Actions button transitions to its active state.
	await fileRow.hover()
	const actionsBtn = fileRow
		.locator('[data-cy-files-list-row-actions]')
		.getByRole('button', { name: 'Actions' })
	await actionsBtn.click()

	const menuItem = page
		.locator(`[data-cy-files-list-row-action="${cssEscape(action)}"]`)
		.getByRole('menuitem')
	await menuItem.waitFor({ state: 'visible' })
	await menuItem.click({ force: true })
}

export const getFileListRow = (page: Page, filename: string) =>
	page.locator(`[data-cy-files-list-row-name="${cssEscape(filename)}"]`)

export async function renameFile(page: Page, fileName: string, newName: string) {
	await getRowForFile(page, fileName)
		.locator('.files-list__row-icon-preview--loaded')
		.waitFor()

	await triggerActionForFile(page, fileName, 'rename')

	const moveResponse = page.waitForResponse(/\/(remote|public)\.php\/dav\/files\//)
	await getRowForFile(page, fileName).locator('[data-cy-files-list-row-name] input').clear()
	await getRowForFile(page, fileName).locator('[data-cy-files-list-row-name] input').fill(`${newName}`)
	await getRowForFile(page, fileName).locator('[data-cy-files-list-row-name] input').press('Enter')
	await moveResponse
}

export async function navigateToFolder(page: Page, dirPath: string) {
	const directories = dirPath.split('/').filter(Boolean)
	for (const directory of directories) {
		const navResponse = page.waitForResponse(/\/remote\.php\/dav\/files\//)
		await getRowForFile(page, directory).locator('[data-cy-files-list-row-name-link]').click()
		await navResponse
	}
}

export async function createFolder(page: Page, dirName: string) {
	const mkcolResponse = page.waitForResponse(/\/remote\.php\/dav\/files\//)

	await page.locator('[data-cy-upload-picker] .action-item__menutoggle').first().click()
	await page.locator('[data-cy-upload-picker-menu-entry="newFolder"] button').click()
	await expect(page.locator('[data-cy-files-new-node-dialog]')).toBeVisible()
	await page.locator('[data-cy-files-new-node-dialog-input]').fill(dirName)
	await page.locator('[data-cy-files-new-node-dialog-submit]').click()
	await mkcolResponse

	await expect(getRowForFile(page, dirName)).toBeVisible()
}

export async function moveFile(page: Page, fileName: string, dirName: string) {
	await toggleMenuAction(page, fileName, 'move-copy')

	await page.locator('.file-picker').waitFor()

	const moveResponse = page.waitForResponse(/\/(remote|public)\.php\/dav\/files\//)

	if (dirName === '/') {
		await page.locator('.file-picker').getByRole('button', { name: 'Home' }).click()
		await page.locator('.file-picker').getByRole('button', { name: 'Move' }).click()
	} else {
		const directories = dirName.split('/').filter(Boolean)
		for (const directory of directories) {
			await page.locator('.file-picker').locator(`[data-filename="${directory}"]`).click()
		}
		await page.locator('.file-picker').getByRole('button', { name: `Move to ${directories.at(-1)}` }).click()
	}

	await moveResponse
}
