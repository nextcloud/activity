/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { User } from '@nextcloud/cypress'
import { createFolder, getFileListRow, renameFile } from './filesUtils.ts'
import { addTag, showActivityTab } from './sidebarUtils.ts'

/**
 * Take a named screenshot with consistent settings for documentation.
 * Screenshots are organized into subdirectories:
 *   cypress/snapshots/actual/user/   — user-facing views
 *   cypress/snapshots/actual/admin/  — admin settings
 */
function docScreenshot(name: string, options: Partial<Cypress.ScreenshotOptions> = {}) {
	// Let animations, loaders and toasts settle
	cy.wait(500)
	cy.screenshot(name, {
		capture: 'viewport',
		overwrite: true,
		...options,
	})
}

describe('Documentation screenshots — user views', { testIsolation: false }, () => {
	let user: User

	before(() => {
		cy.createRandomUser()
			.then((_user) => {
				user = _user
				cy.login(user)
			})

		// Seed activity: generate several types of events so the stream looks realistic
		cy.visit('/apps/files')
		getFileListRow('welcome.txt').should('be.visible')

		createFolder('Project notes')
		renameFile('welcome.txt', 'readme.txt')

		// Revisit to let the file list settle after rename
		cy.visit('/apps/files')
		getFileListRow('readme.txt').should('be.visible')

		addTag('readme.txt', 'important')
	})

	describe('Activity stream', () => {

		it('Activity app — main stream (all filter)', () => {
			cy.visit('/apps/activity')
			cy.get('.activity-entry').should('have.length.at.least', 3)
			docScreenshot('user/activity-stream-all')
		})

		it('Activity app — "by you" filter', () => {
			cy.visit('/apps/activity')
			cy.get('.activity-entry').should('have.length.at.least', 1)
			cy.get('[data-navigation="self"]').click()
			cy.get('.activity-entry').should('have.length.at.least', 1)
			docScreenshot('user/activity-stream-self')
		})

		it('Activity app — "by others" filter', () => {
			cy.visit('/apps/activity')
			cy.get('.activity-entry').should('have.length.at.least', 1)
			cy.get('[data-navigation="by"]').click()
			// May be empty — that is expected for a single-user setup
			docScreenshot('user/activity-stream-by-others')
		})

		it('Activity app — file changes filter', () => {
			cy.visit('/apps/activity')
			cy.get('.activity-entry').should('have.length.at.least', 1)
			cy.get('[data-navigation="files"]').click()
			cy.get('.activity-entry').should('have.length.at.least', 1)
			docScreenshot('user/activity-stream-file-changes')
		})
	})

	describe('Files sidebar activity tab', () => {
		it('Sidebar — activity tab for a file', () => {
			cy.visit('/apps/files')
			getFileListRow('readme.txt').should('be.visible')
			showActivityTab('readme.txt')
			cy.get('.activity-entry').should('have.length.at.least', 1)
			docScreenshot('user/activity-sidebar')
		})
	})

	describe('Personal notification settings', () => {
		it('Personal settings — notification preferences', () => {
			cy.visit('/settings/user/notifications')
			cy.get("#app-content input[type='checkbox']").should('have.length.at.least', 1)
			docScreenshot('user/activity-settings-personal')
		})
	})
})

describe('Documentation screenshots — admin views', () => {

	it('Admin settings — notification toggle and default settings', () => {
		// Log in as the default admin user provided by the Docker container
		const admin = new User('admin', 'admin')
		cy.login(admin)
		cy.visit('/settings/admin/activity')
		cy.get('#activity-admin-settings').should('be.visible')
		cy.get('#activity-default-settings').should('be.visible')
		docScreenshot('admin/activity-settings-admin')
	})
})
