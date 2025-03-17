/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
