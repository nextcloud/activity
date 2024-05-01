/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import OC from './OC.js'

import Vue from 'vue'
import { translate as t, translatePlural as n } from '@nextcloud/l10n'

global.OC = OC

Vue.prototype.t = t
Vue.prototype.n = n
