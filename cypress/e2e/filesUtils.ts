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

export function renameFile(fileName: string, newName: string) {
	toggleMenuAction(fileName)
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-action="rename"]`).click()
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] .files-list__row-rename input`).clear()
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] .files-list__row-rename input`).type(`${newName}.txt`)
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] .files-list__row-rename`).submit()
	cy.get('.toast-close').click()
	cy.wait(500)
}

export function goToDir(dirName: string) {
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${dirName}"]`).click()
	cy.wait(500)
}

export function createFolder (dirName: string) {
	cy.get('.files-list__header .breadcrumb__actions button.action-item__menutoggle').click()
	cy.get('.v-popper__popper').contains('New folder').click()
	cy.get('.files-list form.files-list__row-rename input[type="text"]').clear().type(`${dirName}{enter}`)
	cy.log('Created folder', dirName)
	cy.wait(500)
}

export function moveFile (fileName: string, dirName: string) {
	toggleMenuAction(fileName)
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-action="move-copy"]`).click()
	cy.get('.file-picker').within(() => {
		cy.get(`[data-filename="${dirName}"]`).click()
		cy.contains(`Move to ${dirName}`).click()
		cy.wait(500)
	})
}

export function toggleMenuAction(fileName: string) {
	cy.get(`[data-cy-files-list] [data-cy-files-list-row-name="${fileName}"] [data-cy-files-list-row-actions] .action-item__menutoggle`).click()
	cy.get('[data-cy-files-list-row-action]').should('be.visible')
}
