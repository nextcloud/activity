/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { triggerActionForFile } from '../../../../cypress/e2e/files/FilesUtils'

export function renameFile(fileName: string, newName: string) {

	triggerActionForFile(fileName, 'rename')
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
	triggerActionForFile(fileName, 'move-copy')
	cy.get('.file-picker').within(() => {
		cy.get(`[data-filename="${dirName}"]`).click()
		cy.contains(`Move to ${dirName}`).click()
		cy.wait(500)
	})
}

export function getFileListRow(filename: string) {
	return cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${CSS.escape(filename)}"]`)
}

// export const getActionsForFile = (filename: string) => getRowForFile(filename).find('[data-cy-files-list-row-actions]')
// export const getActionButtonForFile = (filename: string) => getActionsForFile(filename).findByRole('button', { name: 'Actions' })
// export const getActionEntryForFile = (filename: string, actionId: string): Cypress.Chainable<JQuery<HTMLElement>> => {
// 	// If we cannot find the action in the row, it might be in the action menu
// 	return getRowForFile(filename).should('be.visible')
// 		.then(row => searchForActionInRow(row, actionId))
// }
// export const triggerActionForFile = (filename: string, actionId: string) => {
// 	// Even if it's inline, we open the action menu to get all actions visible
// 	getActionButtonForFile(filename).click({ force: true })
// 	getActionEntryForFile(filename, actionId)
// 		.find('button').last()
// 		.should('exist').click({ force: true })
// }
//
// const searchForActionInRow = (row: JQuery<HTMLElement>, actionId: string): Cypress.Chainable<JQuery<HTMLElement>> => {
// 	const action = row.find(`[data-cy-files-list-row-action="${CSS.escape(actionId)}"]`)
// 	if (action.length > 0) {
// 		cy.log('Found action in row')
// 		return cy.wrap(action)
// 	}
//
// 	// Else look in the action menu
// 	const menuButtonId = row.find('button[aria-controls]').attr('aria-controls')
// 	if (menuButtonId === undefined) {
// 		return cy.wrap(Cypress.$())
// 	}
//
// 	// eslint-disable-next-line no-unused-expressions
// 	expect(menuButtonId).not.to.be.undefined
// 	return cy.get(`#${menuButtonId} [data-cy-files-list-row-action="${CSS.escape(actionId)}"]`)
// }
