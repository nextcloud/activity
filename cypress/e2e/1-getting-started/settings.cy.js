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

/// <reference types="Cypress" />

import { randHash } from '../../utils'
const randUser = randHash()

describe('Check that user\'s settings survive a reload', () => {
	before(() => {
		cy.nextcloudCreateUser(randUser, 'password')
		cy.login(randUser, 'password')
		cy.visit('/settings/user/notifications')
		cy.wait(1000)
	})

	after(() => {
		cy.logout()
	})

	it('Form survive a reload', () => {
		cy.get("#app-content input[type='checkbox']").uncheck({ force: true }).should('not.be.checked')

		cy.reload()

		cy.get("#app-content input[type='checkbox']").uncheck({ force: true }).should('not.be.checked')

		cy.get("#file_changed_notification").check({ force: true })
		cy.contains("A calendar was modified").click()
		cy.contains("Comments for files").click()
		cy.contains("Your password or email was modified").click()

		cy.reload()

		cy.get("#file_changed_email").should('not.be.checked')
		cy.get("#file_changed_notification").should('be.checked')
		cy.get("#shared_email").should('not.be.checked')
		cy.get("#shared_notification").should('not.be.checked')
		cy.get("#remote_share_email").should('not.be.checked')
		cy.get("#remote_share_notification").should('not.be.checked')
		cy.get("#public_links_email").should('not.be.checked')
		cy.get("#public_links_notification").should('not.be.checked')
		cy.get("#calendar_email").should('be.checked')
		cy.get("#calendar_notification").should('be.checked')
		cy.get("#calendar_event_email").should('not.be.checked')
		cy.get("#calendar_event_notification").should('not.be.checked')
		cy.get("#calendar_todo_email").should('not.be.checked')
		cy.get("#calendar_todo_notification").should('not.be.checked')
		cy.get("#contacts_email").should('not.be.checked')
		cy.get("#contacts_notification").should('not.be.checked')
		cy.get("#group_settings_email").should('not.be.checked')
		cy.get("#group_settings_notification").should('not.be.checked')
		cy.get("#personal_settings_email").should('not.be.checked')
		cy.get("#personal_settings_notification").should('be.checked')
		cy.get("#security_email").should('be.checked')
		cy.get("#security_notification").should('not.be.checked')
		cy.get("#comments_email").should('be.checked')
		cy.get("#comments_notification").should('be.checked')
		cy.get("#systemtags_email").should('not.be.checked')
		cy.get("#systemtags_notification").should('not.be.checked')
	})

	it('Notification frequency survive a reload', () => {
		cy.get(".notification-frequency__select").select("Weekly")

		cy.wait(200)
		cy.reload()

		cy.get('.notification-frequency__select').find(':selected').contains('Weekly')
		cy.get(".notification-frequency__select").select("Hourly")

		cy.wait(200)
		cy.reload()

		cy.get('.notification-frequency__select').find(':selected').contains('Hourly')
	})

	it('Activity summary survive a reload', () => {
		cy.intercept({ method: 'POST', url: '**/activity/settings' }).as("apiCall");

		cy.get("#app-content")
			.contains("Send daily activity summary in the morning")
			.find('input')
			.check({ force: true })
			.should('be.checked')
			.wait('@apiCall')
			.reload()

		cy.get("#app-content")
			.contains("Send daily activity summary in the morning")
			.find('input')
			.should('be.checked')
			.uncheck({ force: true })
			.wait('@apiCall')
			.reload()

		cy.get("#app-content")
			.contains("Send daily activity summary in the morning")
			.find('input')
			.should('not.be.checked')
	})
})
