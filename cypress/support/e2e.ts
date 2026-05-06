/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import './commands'

// Ignore resize observer errors of Chrome, they are unrelated and save to ignore
// Also ignore a NC32 files-app TypeError in Electron 118 caused by a keyboard-handling
// module accessing an undefined export — the page still loads correctly.
Cypress.on('uncaught:exception', err =>
	!err.message.includes('ResizeObserver')
	&& !err.message.includes("Cannot read properties of undefined (reading 'Tab')")
)
