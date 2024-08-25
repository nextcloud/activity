/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

export function renameFile(fileName: string, newName: string) {
	toggleMenuAction(fileName, 'rename')
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] .files-list__row-rename input`).clear()
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] .files-list__row-rename input`).type(`${newName}.txt`)
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] .files-list__row-rename`).submit()
	cy.get('.toast-close').click()
	cy.wait(500)
}

export function goToDir(dirName: string) {
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${dirName}"]`).click()
	cy.wait(500)
}

export function createFolder (dirName: string) {
	cy.get('.files-list__header .breadcrumb__actions button.action-item__menutoggle').click()
	cy.get('.v-popper__popper').contains('New folder').click()
	cy.contains('Folder name').siblings('input').clear()
	cy.contains('Folder name').siblings('input').type(`${dirName}{enter}`)
	cy.log('Created folder', dirName)
	cy.wait(500)
}

export function moveFile (fileName: string, dirName: string) {
	toggleMenuAction(fileName, 'move-copy')
	cy.get('.file-picker').within(() => {
		cy.get(`[data-filename="${dirName}"]`).click()
		cy.contains(`Move to ${dirName}`).click()
		cy.wait(500)
	})
}

export function getFileListRow(filename: string) {
	return cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${CSS.escape(filename)}"]`)
}

export function toggleMenuAction(filename: string, action: 'details'|'favorite'|'move-copy'|'rename') {
	getFileListRow(filename)
		.find('[data-cy-files-list-row-actions]')
		.should('be.visible')

	getFileListRow(filename)
		.find('[data-cy-files-list-row-actions]')
		.findByRole('button', { name: 'Actions' })
		.should('be.visible')
		.click()

	cy.get(`[data-cy-files-list-row-action="${CSS.escape(action)}"]`)
		.should('be.visible')
		.findByRole('menuitem')
		.click()
}
