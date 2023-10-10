/**
 * @copyright Copyright (c) 2023 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import { toggleMenuAction } from "./filesUtils"

function showSidebarForFile(fileName: string) {
	closeSidebar()
	toggleMenuAction(fileName)
	cy.contains('Open details').click()
	cy.get('#app-sidebar-vue').contains('Activity').click()
}

function closeSidebar() {
	cy.get('body')
		.then(($body) => {
			if ($body.find('.app-sidebar__close').length !== 0) {
				cy.get('.app-sidebar__close').click()
			}
		})
}

export function showActivityTab(fileName: string) {
	showSidebarForFile(fileName)
	cy.get('#app-sidebar-vue').contains('Activity').click()
}

export function addToFavorites(fileName: string) {
	toggleMenuAction(fileName)
	cy.contains('Add to favorites').click()
	cy.get('.toast-close').click()
}

export function removeFromFavorites(fileName: string) {
	toggleMenuAction(fileName)
	cy.contains('Remove from favorites').click()
	cy.get('.toast-close').click()
}

export function createPublicShare(fileName: string) {
	toggleMenuAction(fileName)
	cy.contains('Open details').click()
	cy.get('#app-sidebar-vue').contains('Sharing').click()

	cy.get('#app-sidebar-vue [data-id="sharing"]').trigger('click')
	cy.get('#app-sidebar-vue button.new-share-link').trigger('click')
	cy.get('#app-sidebar-vue a.sharing-entry__copy')
}

export function addTag(fileName: string, tag: string) {
	showSidebarForFile(fileName)

	cy.get('.app-sidebar-header__menu button').click()
	cy.get('.action-button__icon.icon-tag').click()
	cy.get('input#system-tags-input').type(`${tag}{enter}{esc}`)

	cy.wait(500)
}

export function addComment(fileName: string, comment: string) {
	showSidebarForFile(fileName)
	cy.get('#app-sidebar-vue').contains('Comments').click()
	cy.get('.comment__editor .rich-contenteditable__input').type(comment)
	cy.get('.comment__submit button').click()

	cy.wait(500)
}
