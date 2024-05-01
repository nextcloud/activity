/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { toggleMenuAction } from './filesUtils'

function showSidebarForFile(fileName: string) {
	closeSidebar()
	toggleMenuAction(fileName)
	cy.get('[data-cy-files-list-row-action="details"] button').click()
	cy.get('#app-sidebar-vue').should('be.visible')
}

function closeSidebar() {
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
	cy.get('#app-sidebar-vue').contains('Activity').click()
}

export function addToFavorites(fileName: string) {
	toggleMenuAction(fileName)
	cy.get('[data-cy-files-list-row-action="favorite"]').should('contain', 'Add to favorites').click()
	cy.get('.toast-close').click()
}

export function removeFromFavorites(fileName: string) {
	toggleMenuAction(fileName)
	cy.get('[data-cy-files-list-row-action="favorite"]').should('contain', 'Remove from favorites').click()
	cy.get('.toast-close').click()
}

/**
 * Create a new public link share for a given file
 *
 * @param fileName Name of the file to share
 */
export function createPublicShare(fileName: string) {
	showSidebarForFile(fileName)
	cy.get('#app-sidebar-vue').contains('Sharing').click()

	cy.get('#app-sidebar-vue #tab-sharing').should('be.visible')
	cy.get('#app-sidebar-vue button.new-share-link').click({ force: true })
	cy.get('#app-sidebar-vue .sharing-entry__copy').should('be.visible')
	closeSidebar()
}

export function addTag(fileName: string, tag: string) {
	showSidebarForFile(fileName)

	cy.get('.app-sidebar-header__menu button').click()
	cy.get('.action-button__icon.icon-tag').click()
	cy.get('.system-tags input').type(`${tag}{enter}{esc}`)

	cy.wait(500)
}

export function addComment(fileName: string, comment: string) {
	showSidebarForFile(fileName)
	cy.get('#app-sidebar-vue').contains('Activity').click()
	cy.get('.comment__editor .rich-contenteditable__input').type(comment)
	cy.get('.comment__submit button').click()

	cy.wait(500)
}
