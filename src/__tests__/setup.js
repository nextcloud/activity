/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { translatePlural as n, translate as t } from '@nextcloud/l10n'
import Vue from 'vue'

Vue.prototype.t = t
Vue.prototype.n = n
