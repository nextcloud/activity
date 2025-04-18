/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

export const getRowForFile = (filename: string) => cy.get(`[data-cy-files-list-row-name="${CSS.escape(filename)}"]`)
export const getActionsForFile = (filename: string) => getRowForFile(filename).find('[data-cy-files-list-row-actions]')
export const getActionButtonForFile = (filename: string) => getActionsForFile(filename).findByRole('button', { name: 'Actions' })

const searchForActionInRow = (row: JQuery<HTMLElement>, actionId: string): Cypress.Chainable<JQuery<HTMLElement>> => {
	const action = row.find(`[data-cy-files-list-row-action="${CSS.escape(actionId)}"]`)
	if (action.length > 0) {
		cy.log('Found action in row')
		return cy.wrap(action)
	}

	// Else look in the action menu
	const menuButtonId = row.find('button[aria-controls]').attr('aria-controls')
	if (menuButtonId === undefined) {
		return cy.wrap(Cypress.$())
	}

	// eslint-disable-next-line no-unused-expressions
	expect(menuButtonId).not.to.be.undefined
	return cy.get(`#${menuButtonId} [data-cy-files-list-row-action="${CSS.escape(actionId)}"]`)
}

export const getActionEntryForFile = (filename: string, actionId: string): Cypress.Chainable<JQuery<HTMLElement>> => {
	// If we cannot find the action in the row, it might be in the action menu
	return getRowForFile(filename).should('be.visible')
		.then(row => searchForActionInRow(row, actionId))
}

export const triggerActionForFile = (filename: string, actionId: string) => {
	// Even if it's inline, we open the action menu to get all actions visible
	getActionButtonForFile(filename).click({ force: true })
	getActionEntryForFile(filename, actionId)
		.find('button').last()
		.should('exist').click({ force: true })
}

export function renameFile(fileName: string, newName: string) {
	// The file must exist and the preview loaded as it locks the file
	getRowForFile(fileName)
		.should('be.visible')
		.find('.files-list__row-icon-preview--loaded')
		.should('exist')

	triggerActionForFile(fileName, 'rename')

	// intercept the move so we can wait for it
	cy.intercept('MOVE', /\/(remote|public)\.php\/dav\/files\//).as('moveFile')

	getRowForFile(fileName).find('[data-cy-files-list-row-name] input').clear()
	getRowForFile(fileName).find('[data-cy-files-list-row-name] input').type(`${newName}{enter}`)
	cy.get('.toast-close').click()
	cy.wait('@moveFile')
}

export function navigateToFolder(dirPath: string) {
	cy.intercept('PROPFIND', /\/remote.php\/dav\/files\//).as('navigateFolder')

	const directories = dirPath.split('/')
	for (const directory of directories) {
		if (directory === '') {
			continue
		}

		getRowForFile(directory).should('be.visible').find('[data-cy-files-list-row-name-link]').click()
		cy.wait('@navigateFolder')
	}
}

export function createFolder (dirName: string) {
	cy.intercept('MKCOL', /\/remote.php\/dav\/files\//).as('createFolder')

	// TODO: replace by proper data-cy selectors
	cy.get('[data-cy-upload-picker] .action-item__menutoggle').first().click()
	cy.get('[data-cy-upload-picker-menu-entry="newFolder"] button').click()
	cy.get('[data-cy-files-new-node-dialog]').should('be.visible')
	cy.get('[data-cy-files-new-node-dialog-input]').type(`{selectall}${dirName}`)
	cy.get('[data-cy-files-new-node-dialog-submit]').click()

	cy.wait('@createFolder')

	getRowForFile(dirName).should('be.visible')
}

export function moveFile (fileName: string, dirName: string) {
	toggleMenuAction(fileName, 'move-copy')
	cy.get('.file-picker').within(() => {
		// intercept the copy so we can wait for it
		cy.intercept('MOVE', /\/(remote|public)\.php\/dav\/files\//).as('moveFile')

		if (dirName === '/') {
			// select home folder
			cy.get('button[title="Home"]').should('be.visible').click()
			// click move
			cy.contains('button', 'Move').should('be.visible').click()
		} else if (dirName === '.') {
			// click move
			cy.contains('button', 'Copy').should('be.visible').click()
		} else {
			const directories = dirName.split('/')
			directories.forEach((directory) => {
				// select the folder
				cy.get(`[data-filename="${directory}"]`).should('be.visible').click()
			})

			// click move
			cy.contains('button', `Move to ${directories.at(-1)}`).should('be.visible').click()
		}

		cy.wait('@moveFile')
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
