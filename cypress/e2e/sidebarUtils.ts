/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { toggleMenuAction, triggerActionForFile } from './filesUtils.ts'

function showSidebarForFile(fileName: string) {
	toggleMenuAction(fileName, 'details')
	cy.get('#app-sidebar-vue').should('be.visible')
}

/**
 * Close the sidebar
 */
export function closeSidebar() {
	// {force: true} as it might be hidden behind toasts
	cy.get('[data-cy-sidebar] .app-sidebar__close')
		.click({ force: true })
	cy.get('[data-cy-sidebar]')
		.should('not.be.visible')
	cy.wait(500)
	cy.url()
		.should('not.contain', 'opendetails')
}

export function showActivityTab(fileName: string) {
	cy.intercept('GET', '/ocs/v2.php/apps/activity/api/v2/activity/filter**').as('getActivities')

	showSidebarForFile(fileName)

	// {force: true} as it might be hidden behind toasts
	cy.get('#app-sidebar-vue')
		.findByRole('tab', { name: 'Activity' })
		.click({ force: true })

	cy.get('#app-sidebar-vue')
		.findByRole('tabpanel', { name: 'Activity' })
		.should('be.visible')

	cy.wait('@getActivities')
}

export function toggleFavorite(fileName: string) {
	cy.intercept('POST', '**/index.php/apps/files/api/v1/files/*').as('setTags')

	toggleMenuAction(fileName, 'favorite')
	cy.wait('@setTags')

	cy.get('.toast-close').click()
}

/**
 * Create a new public link share for a given file
 *
 * @param fileName Name of the file to share
 */
export function createPublicShare(fileName: string) {
	cy.intercept('POST', '/ocs/v2.php/apps/files_sharing/api/v1/shares').as('createPublicShare')

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

	cy.wait('@createPublicShare')
}

/**
 * Add a system tag to a file
 *
 * @param fileName - The filename to asign the tag
 * @param newTag - The new tag
 */
export function addTag(fileName: string, newTag: string) {
	triggerActionForFile(fileName, 'systemtags:bulk')

	cy.intercept('POST', '/remote.php/dav/systemtags').as('createTag')
	cy.intercept('PROPFIND', '/remote.php/dav/systemtags/*/files').as('getTagData')
	cy.intercept('PROPPATCH', '/remote.php/dav/systemtags/*/files').as('assignTagData')

	cy.get('[data-cy-systemtags-picker-input]').type(newTag)

	cy.get('[data-cy-systemtags-picker-tag]').should('have.length', 0)
	cy.get('[data-cy-systemtags-picker-button-create]').should('be.visible')
	cy.get('[data-cy-systemtags-picker-button-create]').click()

	cy.wait('@createTag')
	// Verify the new tag is selected by default
	cy.get('[data-cy-systemtags-picker-tag]').contains(newTag)
		.parents('[data-cy-systemtags-picker-tag]')
		.findByRole('checkbox', { hidden: true }).should('be.checked')

	// Apply changes
	cy.get('[data-cy-systemtags-picker-button-submit]').click()

	cy.wait('@assignTagData')
	cy.get('[data-cy-systemtags-picker]').should('not.exist')
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

export function randHash() {
	return Math.random().toString(36).replace(/[^a-z]+/g, '').slice(0, 10)
}
