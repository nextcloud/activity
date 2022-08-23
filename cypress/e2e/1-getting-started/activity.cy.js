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

import { randHash } from '../../utils'

const randUser = randHash()

describe('Open test.md in viewer', function () {
	before(function () {
		cy.nextcloudCreateUser(randUser, 'password')
		cy.login(randUser, 'password')

		cy.visit('/apps/files')

		cy.wait(1000)
	})

	after(function () {
		cy.logout()
	})

	it('Has creation activity', function () {
		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'You created')
	})

	it('Has favorite activity', function () {
		cy.addToFavorites('welcome.txt')
		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'Added to favorites')

		cy.removeFromFavorites('welcome.txt')
		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'Removed from favorites')
	})

	it('Has share activity', function () {
		cy.createPublicShare('welcome.txt')
		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'Shared as public link')
	})

	it('Has rename activity', function () {
		cy.renameFile('welcome.txt', 'new name')
		cy.renameFile('new name.txt', 'welcome')

		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'You renamed')
	})

	it('Has move activity', function () {
		cy.createFolder('Test folder')
		cy.moveFile('welcome.txt', 'Test folder')
		cy.goToDir('Test folder')

		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'You moved')
	})

	it('Has tag activity', function () {
		cy.addTag('welcome.txt', 'my_tag')

		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'Added system tag')
	})

	it('Has comment activity', function () {
		cy.addComment('welcome.txt', 'A comment')

		cy.showActivityTab('welcome.txt')
		cy.get('.activity-entry').eq(0).should('contains.text', 'You commented')
	})

})
