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

import { createFolder, moveFile, navigateToFolder, renameFile } from './filesUtils'
import { addComment, addTag, addToFavorites, createPublicShare, removeFromFavorites, showActivityTab } from './sidebarUtils'

describe('Check activity listing in the sidebar', { testIsolation: true }, () => {
	beforeEach(function() {
		cy.createRandomUser()
			.then((user) => {
				cy.login(user)
				cy.visit('/apps/files')
			})
	})

	it('Has creation activity', () => {
		showActivityTab('welcome.txt')
		cy.get('.activity-entry').last().should('contains.text', 'You created')
	})

	it('Has favorite activity', () => {
		addToFavorites('welcome.txt')
		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'Added to favorites')

		removeFromFavorites('welcome.txt')
		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'Removed from favorites')
	})

	it('Has share activity', () => {
		createPublicShare('welcome.txt')
		cy.get('body').contains('Link share created').should('exist')
		cy.get('.toast-close').click({ multiple: true })
		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'Shared as public link')
	})

	it('Has rename activity', () => {
		renameFile('welcome.txt', 'new name.txt')
		renameFile('new name.txt', 'welcome.txt')

		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'You renamed')
	})

	it('Has move activity', () => {
		createFolder('Test folder')
		moveFile('welcome.txt', 'Test folder')
		navigateToFolder('Test folder')

		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'You moved')
	})

	it('Has tag activity', () => {
		addTag('welcome.txt', 'my_tag')

		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'Added system tag')
	})

	it('Has comment activity', () => {
		addComment('welcome.txt', 'A comment')

		showActivityTab('welcome.txt')
		cy.get('.comments-activity').first().should('contains.text', 'A comment')
	})

})
