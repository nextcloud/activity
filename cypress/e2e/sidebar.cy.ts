/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { createFolder, getFileListRow, navigateToFolder, moveFile, renameFile } from './filesUtils'
import { addComment, addTag, createPublicShare, toggleFavorite, showActivityTab, randHash } from './sidebarUtils'

describe('Check activity listing in the sidebar', { testIsolation: true }, () => {
	beforeEach(function() {
		cy.createRandomUser()
			.then((user) => {
				cy.login(user)
				cy.visit('/apps/files')
				// Wait for page loaded
				getFileListRow('welcome.txt')
					.should('be.visible')
			})
	})

	it('Has creation activity', () => {
		showActivityTab('welcome.txt')
		cy.get('.activity-entry').last().should('contains.text', 'You created')
	})

	it('Has favorite activity', () => {
		toggleFavorite('welcome.txt')
		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'Added to favorites')

		cy.visit('/apps/files')
		getFileListRow('welcome.txt').should('be.visible')

		toggleFavorite('welcome.txt')
		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'Removed from favorites')
	})

	it('Has share activity', () => {
		createPublicShare('welcome.txt')
		cy.visit('/apps/files')
		getFileListRow('welcome.txt').should('be.visible')

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
		addTag('welcome.txt', `my_tag_${randHash()}`)
		cy.visit('/apps/files')

		showActivityTab('welcome.txt')
		cy.get('.activity-entry').first().should('contains.text', 'Added system tag')
	})

	it('Has comment activity', () => {
		addComment('welcome.txt', 'A comment')
		cy.visit('/apps/files')

		showActivityTab('welcome.txt')
		cy.get('.comments-activity').first().should('contains.text', 'A comment')
	})

})
