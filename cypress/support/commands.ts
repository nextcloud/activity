/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import { addCommands } from '@nextcloud/e2e-test-server/cypress'

import 'cypress-wait-until'
import '@testing-library/cypress/add-commands'

// Add custom commands
addCommands()
