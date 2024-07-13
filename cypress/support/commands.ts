/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
/* eslint-disable n/no-unpublished-import */
import { addCommands } from '@nextcloud/cypress'

import 'cypress-wait-until'

import '@testing-library/cypress/add-commands'

// Add custom commands
addCommands()
