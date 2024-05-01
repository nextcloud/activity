/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import './commands'

// Ignore resize observer errors of Chrome, they are unrelated and save to ignore
Cypress.on('uncaught:exception', err => !err.message.includes('ResizeObserver'))
