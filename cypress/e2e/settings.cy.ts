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

		cy.contains('[data-cy-checkbox]', 'Send daily activity summary in the morning')
			.find('input')
			.check({ force: true })
		cy.contains('[data-cy-checkbox]', 'Send daily activity summary in the morning')
			.find('input')
			.should('be.checked')
		cy.wait('@apiCall')
			.reload()

		cy.contains('[data-cy-checkbox]', 'Send daily activity summary in the morning')
			.scrollIntoView()
			.find('input')
			.should('be.checked')

		cy.contains('[data-cy-checkbox]', 'Send daily activity summary in the morning')
			.find('input')
			.uncheck({ force: true })
		cy.wait('@apiCall')
			.reload()

		cy.contains('[data-cy-checkbox]', 'Send daily activity summary in the morning')
			.scrollIntoView()
			.find('input')
			.should('not.be.checked')
	})
})
