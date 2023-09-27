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
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.get(`.files-fileList tr[data-file="${fileName}"] .action-rename`).click()
	cy.get(`.files-fileList tr[data-file="${fileName}"] input.filename`).type(newName).parent().submit()
	cy.wait(500)
}

export function goToDir(dirName: string) {
	cy.get(`.files-fileList tr[data-file="${dirName}"]`).click()
	cy.wait(500)
}

export function createFolder (dirName: string) {
	cy.get('.files-controls .actions > .button.new').click()
	cy.get('.files-controls .actions .newFileMenu a[data-action="folder"]').click()
	cy.get('.files-controls .actions .newFileMenu a[data-action="folder"] input[type="text"]').type(dirName)
	cy.get('.files-controls .actions .newFileMenu a[data-action="folder"] input.icon-confirm').click()
	cy.log('Created folder', dirName)
	cy.wait(500)
}

export function moveFile (fileName: string, dirName: string) {
	cy.get(`.files-fileList tr[data-file="${fileName}"] .icon-more`).click()
	cy.get(`.files-fileList tr[data-file="${fileName}"] .action-movecopy`).click()
	cy.get('.dialog__modal').within(() => {
		cy.get(`tr[data-filename="${dirName}"]`).click()
		cy.contains(`Move to ${dirName}`).click()
	})
	cy.wait(500)
}