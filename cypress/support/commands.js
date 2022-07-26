/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
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

import axios from '@nextcloud/axios'

const url = Cypress.config('baseUrl').replace(/\/index.php\/?$/g, '')
Cypress.env('baseUrl', url)

Cypress.Commands.add('login', (user, password, route = '/apps/files') => {
	cy.clearCookies()
	Cypress.Cookies.defaults({
		preserve: /^(oc|nc)/,
	})
	cy.visit(route)
	cy.get('input[name=user]').type(user)
	cy.get('input[name=password]').type(password)
	cy.get('.submit-wrapper input[type=submit]').click()
	cy.url().should('include', route)
})

Cypress.Commands.add('logout', () => {
	Cypress.Cookies.defaults({
		preserve: [],
	})

	cy.clearLocalStorage()
	cy.clearCookies()

	Cypress.Cookies.defaults({
		preserve: /^(oc|nc)/,
	})
})

Cypress.Commands.add('nextcloudCreateUser', (user, password) => {
	cy.request({
		method: 'POST',
		url: `${Cypress.env('baseUrl')}/ocs/v1.php/cloud/users?format=json`,
		form: true,
		body: {
			userid: user,
			password,
		},
		auth: { user: 'admin', pass: 'admin' },
		headers: {
			'OCS-ApiRequest': 'true',
			'Content-Type': 'application/x-www-form-urlencoded',
		},
	}).then(response => {
		cy.log(`Created user ${user}`, response.status)
	})
})

Cypress.Commands.add('createFolder', dirName => {
	cy.get('.files-controls .actions > .button.new').click()
	cy.get('.files-controls .actions .newFileMenu a[data-action="folder"]').click()
	cy.get('.files-controls .actions .newFileMenu a[data-action="folder"] input[type="text"]').type(dirName)
	cy.get('.files-controls .actions .newFileMenu a[data-action="folder"] input.icon-confirm').click()
	cy.log('Created folder', dirName)
	cy.wait(500)
})

Cypress.Commands.add('moveFile', (fileName, dirName) => {
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.get(`.files-fileList tr[data-file="${fileName}"] .action-movecopy`).click()
	cy.get(`.oc-dialog tr[data-entryname="${dirName}"]`).click()
	cy.contains(`Move to ${dirName}`).click()
	cy.wait(500)
})

Cypress.Commands.add('showSidebarForFile', fileName => {
	cy.hideSidebar('welcome.txt')
	cy.get('.files-fileList tr[data-file="welcome.txt"] .icon-more').click()
	cy.contains('Details').click()
	cy.get('#app-sidebar-vue').contains('Activity').click()
})

Cypress.Commands.add('hideSidebar', fileName => {
	cy.get('body')
		.then(($body) => {
			if ($body.find('.app-sidebar__close').length !== 0) {
				cy.get('.app-sidebar__close').click()
			}
		})
})

Cypress.Commands.add('showActivityTab', fileName => {
	cy.showSidebarForFile()
	cy.get('#app-sidebar-vue').contains('Activity').click()
})

Cypress.Commands.add('addToFavorites', fileName => {
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.contains('Add to favorites').click()
})

Cypress.Commands.add('removeFromFavorites', fileName => {
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.contains('Remove from favorites').click()
})

Cypress.Commands.add('createPublicShare', fileName => {
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.contains('Details').click()
	cy.get('#app-sidebar-vue').contains('Sharing').click()

	cy.get('#app-sidebar-vue a#sharing').trigger('click')
	cy.get('#app-sidebar-vue button.new-share-link').trigger('click')
	cy.get('#app-sidebar-vue a.sharing-entry__copy')
})

Cypress.Commands.add('renameFile', (fileName, newName) => {
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.get(`.files-fileList tr[data-file="${fileName}"] .action-rename`).click()
	cy.get(`.files-fileList tr[data-file="${fileName}"] input.filename`).type(newName).parent().submit()
	cy.wait(500)
})

Cypress.Commands.add('goToDir', (dirName) => {
	cy.get(`.files-fileList tr[data-file="${dirName}"]`).click()
	cy.wait(500)
})

Cypress.Commands.add('addTag', (fileName, tag) => {
	cy.showSidebarForFile('welcome.txt')

	cy.get('.app-sidebar-header__menu .action-item__menutoggle').click()
	cy.get('.action-button__icon.icon-tag').click()
	cy.get('.systemTagsInputField input').type('my_tag{enter}{esc}')

	cy.wait(500)
})

Cypress.Commands.add('addComment', (fileName, comment) => {
	cy.showSidebarForFile('welcome.txt')
	cy.get('#app-sidebar-vue').contains('Comments').click()
	cy.get('.comment__editor .rich-contenteditable__input').type(comment)
	cy.get('input.comment__submit').click()

	cy.wait(500)
})
