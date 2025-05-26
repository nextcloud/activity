/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { translatePlural as n, translate as t } from '@nextcloud/l10n'
import Vue from 'vue'
import Vuex from 'vuex'
import DailySummary from './views/DailySummary.vue'
import UserSettings from './views/UserSettings.vue'
import store from './store/settings-store.js'

Vue.prototype.t = t
Vue.prototype.n = n

Vue.use(Vuex)

export default {
	userSetting: new Vue({
		el: '#activity-user-settings',
		store,
		name: 'ActivityPersonalSettings',
		render: (h) => h(UserSettings),
	}),
	digestSetting: new Vue({
		el: '#activity-digest-user-settings',
		name: 'ActivityDigestPersonalSettings',
		store,
		render: (h) => h(DailySummary),
	}),
}
