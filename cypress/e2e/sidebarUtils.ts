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

function showSidebarForFile(fileName: string) {
	closeSidebar()
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.contains('Details').click()
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
	cy.intercept({ times: 1, method: 'POST', url: `/index.php/apps/files/api/v1/files/${fileName}` }).as('postFavorite')

	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.contains('Add to favorites').click()

	cy.wait('@postFavorite')
}

export function removeFromFavorites(fileName: string) {
	cy.intercept({ times: 1, method: 'POST', url: `/index.php/apps/files/api/v1/files/${fileName}` }).as('postFavorite')

	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.contains('Remove from favorites').click()

	cy.wait('@postFavorite')
}

export function createPublicShare(fileName: string) {
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.contains('Details').click()
	cy.get('#app-sidebar-vue').contains('Sharing').click()

	cy.get('#app-sidebar-vue a#sharing').trigger('click')
	cy.get('#app-sidebar-vue button.new-share-link').trigger('click')
	cy.get('#app-sidebar-vue a.sharing-entry__copy')
}

export function addTag(fileName: string, tag: string) {
	cy.intercept({ times: 1, method: 'PUT', url: '/remote.php/dav/systemtags-relations/files/*/*' }).as('putTag')

	showSidebarForFile(fileName)

	cy.get('.app-sidebar-header__menu .action-item__menutoggle').click()
	cy.get('.action-button__icon.icon-tag').click()
	cy.get('.systemTagsInputField input').type(`${tag}{enter}{esc}`)

	cy.wait('@putTag')
}

export function addComment(fileName: string, comment: string) {
	cy.intercept({ times: 1, method: 'POST', url: '/remote.php/dav/comments/files/*' }).as('postComment')

	showSidebarForFile(fileName)

	cy.get('#app-sidebar-vue').contains('Comments').click()
	cy.get('.comment__editor .rich-contenteditable__input').focus()
	cy.get('.comment__editor .rich-contenteditable__input').type(comment)
	cy.get('button.comment__submit').click()

	cy.wait('@postComment')
}
