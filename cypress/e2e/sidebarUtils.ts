/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { toggleMenuAction } from './filesUtils'

function showSidebarForFile(fileName: string) {
	closeSidebar()
	toggleMenuAction(fileName, 'details')
	cy.get('#app-sidebar-vue').should('be.visible')
}

export function closeSidebar() {
	cy.get('body')
		.then(($body) => {
			if ($body.find('.app-sidebar__close').length !== 0) {
				// {force: true} as it might be hidden behind toasts
				cy.get('[data-cy-sidebar] .app-sidebar__close').click({ force: true })
			}
		})
	cy.get('#app-sidebar-vue').should('not.exist')
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

export function addTag(fileName: string, tag: string) {
	showSidebarForFile(fileName)

	cy.get('#app-sidebar-vue .app-sidebar-header')
		.should('be.visible')
		.findByRole('button', { name: 'Actions' })
		.click()

	cy.findByRole('menuitem', { name: 'Tags' })
		.should('be.visible')
		.click()

	cy.intercept('POST', '/remote.php/dav/systemtags').as('tag')

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

export function randHash() {
	return Math.random().toString(36).replace(/[^a-z]+/g, '').slice(0, 10)
}
