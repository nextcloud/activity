/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { translatePlural as n, translate as t } from '@nextcloud/l10n'
import Vue from 'vue'
import Vuex from 'vuex'
import AdminSettings from './views/AdminSettings.vue'
import DefaultActivitySettings from './views/DefaultActivitySettings.vue'
import store from './store/settings-store.js'

Vue.prototype.t = t
Vue.prototype.n = n

Vue.use(Vuex)

export default {
	adminSetting: new Vue({
		el: '#activity-admin-settings',
		store,
		name: 'ActivityPersonalSettings',
		render: (h) => h(AdminSettings),
	}),
	defaultSetting: new Vue({
		el: '#activity-default-settings',
		store,
		name: 'ActivityDefaultSettings',
		render: (h) => h(DefaultActivitySettings),
	}),
}
