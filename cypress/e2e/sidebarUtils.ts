/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { triggerActionForFile } from '../../../../cypress/e2e/files/FilesUtils'

function showSidebarForFile(fileName: string) {
	closeSidebar()
	triggerActionForFile(fileName, 'details')
	cy.get('#app-sidebar-vue').should('be.visible')
}

export function closeSidebar() {
	cy.get('body')
		.then(($body) => {
			if ($body.find('.app-sidebar__close').length !== 0) {
				cy.get('.app-sidebar__close').click({ force: true })
			}
		})
	cy.get('#app-sidebar-vue').should('not.exist')
}

export function showActivityTab(fileName: string) {
	showSidebarForFile(fileName)
	cy.get('#app-sidebar-vue')
		.findByRole('tab', { name: 'Activity' })
		.click()

	cy.get('#app-sidebar-vue')
		.findByRole('tabpanel', { name: 'Activity' })
		.should('be.visible')
}

export function toggleFavorite(fileName: string) {
	cy.intercept('POST', '**/index.php/apps/files/api/v1/files/*').as('setTags')

	triggerActionForFile(fileName, 'favorite')
	cy.wait('@setTags')

	cy.get('.toast-close').click()
}

/**
 * Create a new public link share for a given file
 *
 * @param fileName Name of the file to share
 */
export function createPublicShare(fileName: string) {
	showSidebarForFile(fileName)
	cy.get('#app-sidebar-vue')
		.findByRole('tab', { name: 'Sharing' })
		.click()

	cy.intercept('POST', '**/ocs/v2.php/apps/files_sharing/api/v1/shares').as('createShare')

	cy.get('#app-sidebar-vue')
		.findByRole('tabpanel', { name: 'Sharing' })
		.should('be.visible')
		.findByRole('button', { name: "Create a new share link" })
		.click({ force: true })

	cy.wait('@createShare')
	closeSidebar()
}

export function addTag(fileName: string, tag: string) {
	showSidebarForFile(fileName)

	cy.get('#app-sidebar-vue .app-sidebar-header')
		.should('be.visible')
		.findByRole('button', { name: 'Actions' })
		.click()

	cy.findByRole('menuitem', { name: 'Tags' })
		.should('be.visible')
		.click()

	cy.intercept('PUT', '**/remote.php/dav/systemtags-relations/files/**').as('tag')

	cy.findByLabelText('Search or create collaborative tags')
		.type(`${tag}{enter}{esc}`)

	cy.wait('@tag')
}

export function addComment(fileName: string, comment: string) {
	showSidebarForFile(fileName)
	cy.get('#app-sidebar-vue')
		.findByRole('tab', { name: 'Activity' })
		.click()

	cy.intercept('POST', '**/remote.php/dav/comments/files/*').as('comment')
	cy.get('#app-sidebar-vue')
		.findByRole('textbox', { name: 'New comment' })
		.should('be.visible')
		.type(`{selectAll}${comment}{enter}`)

	cy.wait('@comment')
}
