/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { User } from "@nextcloud/cypress"

describe('Check that user\'s settings survive a reload', () => {
	let user: User

	before(() => {
		cy.createRandomUser()
			.then(_user => user = _user)
	})

	beforeEach(() => {
		cy.login(user)
		cy.visit('/settings/user/notifications')
	})

	it('Form survive a reload', () => {
		cy.get("#app-content input[type='checkbox']").uncheck({ force: true })
		cy.get("#app-content input[type='checkbox']").should('not.be.checked')

		cy.reload()

		cy.get("#app-content input[type='checkbox']").uncheck({ force: true })
		cy.get("#app-content input[type='checkbox']").should('not.be.checked')

		cy.get('#file_changed_notification').check({ force: true })
		cy.get('#comments_email').check({ force: true })
		cy.get('#comments_notification').check({ force: true })
		cy.get('#calendar_email').check({ force: true })
		cy.get('#calendar_notification').check({ force: true })
		cy.get('#personal_settings_email').check({ force: true })
		cy.get('#personal_settings_notification').check({ force: true })
		cy.reload()

		cy.get('#file_changed_email').should('not.be.checked')
		cy.get('#file_changed_notification').should('be.checked')
		cy.get('#shared_email').should('not.be.checked')
		cy.get('#shared_notification').should('not.be.checked')
		cy.get('#remote_share_email').should('not.be.checked')
		cy.get('#remote_share_notification').should('not.be.checked')
		cy.get('#public_links_email').should('not.be.checked')
		cy.get('#public_links_notification').should('not.be.checked')
		cy.get('#calendar_email').should('be.checked')
		cy.get('#calendar_notification').should('be.checked')
		cy.get('#calendar_event_email').should('not.be.checked')
		cy.get('#calendar_event_notification').should('not.be.checked')
		cy.get('#calendar_todo_email').should('not.be.checked')
		cy.get('#calendar_todo_notification').should('not.be.checked')
		cy.get('#contacts_email').should('not.be.checked')
		cy.get('#contacts_notification').should('not.be.checked')
		cy.get('#group_settings_email').should('not.be.checked')
		cy.get('#group_settings_notification').should('not.be.checked')
		cy.get('#personal_settings_email').should('not.be.checked')
		cy.get('#personal_settings_notification').should('be.checked')
		cy.get('#security_email').should('be.checked')
		cy.get('#security_notification').should('not.be.checked')
		cy.get('#comments_email').should('be.checked')
		cy.get('#comments_notification').should('be.checked')
		cy.get('#systemtags_email').should('not.be.checked')
		cy.get('#systemtags_notification').should('not.be.checked')
	})

	it('Notification frequency survive a reload', () => {
		cy.intercept({ method: 'POST', url: '**/activity/settings' }).as('apiCall')

		cy.get('.notification-frequency__select').select('Weekly')

		cy.wait('@apiCall')
			.reload()

		cy.get('.notification-frequency__select').find(':selected').contains('Weekly')
		cy.get('.notification-frequency__select').select('Hourly')

		cy.wait('@apiCall')
			.reload()

		cy.get('.notification-frequency__select').find(':selected').contains('Hourly')
	})

	it('Activity summary survive a reload', () => {
		cy.intercept({ method: 'POST', url: '**/activity/settings' }).as('apiCall')

		cy.contains('.checkbox-radio-switch-checkbox', 'Send daily activity summary in the morning')
			.find('input')
			.check({ force: true })
		cy.contains('.checkbox-radio-switch-checkbox', 'Send daily activity summary in the morning')
			.find('input')
			.should('be.checked')
		cy.wait('@apiCall')
			.reload()

		cy.contains('.checkbox-radio-switch-checkbox', 'Send daily activity summary in the morning')
			.scrollIntoView()
			.find('input')
			.should('be.checked')

		cy.contains('.checkbox-radio-switch-checkbox', 'Send daily activity summary in the morning')
			.find('input')
			.uncheck({ force: true })
		cy.wait('@apiCall')
			.reload()

		cy.contains('.checkbox-radio-switch-checkbox', 'Send daily activity summary in the morning')
			.scrollIntoView()
			.find('input')
			.should('not.be.checked')
	})
})
