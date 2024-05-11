/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
/* eslint-disable n/no-unpublished-import */
import { addCommands } from '@nextcloud/cypress'
import { addCompareSnapshotCommand } from 'cypress-visual-regression/dist/command'

import 'cypress-wait-until'

// Add custom commands
addCommands()
addCompareSnapshotCommand()
