/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

export function renameFile(fileName: string, newName: string) {
	toggleMenuAction(fileName)
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-action="rename"]`).click()
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
	toggleMenuAction(fileName)
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-action="move-copy"]`).click()
	cy.get('.file-picker').within(() => {
		cy.get(`[data-filename="${dirName}"]`).click()
		cy.contains(`Move to ${dirName}`).click()
		cy.wait(500)
	})
}

export function toggleMenuAction(fileName: string) {
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] [data-cy-files-list-row-actions] .action-item__menutoggle`).click()
	cy.get('[data-cy-files-list-row-action]').should('be.visible')
}
