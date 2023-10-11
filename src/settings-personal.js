/**
 * @copyright Copyright (c) 2022 Carl Schwan <carl@carlschwan.eu>
 *
 * @license AGPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import Vue from 'vue'
import Vuex from 'vuex'

import UserSettings from './views/UserSettings.vue'
import DailySummary from './views/DailySummary.vue'
import { translate as t, translatePlural as n } from '@nextcloud/l10n'
import store from './settings-store.js'

Vue.prototype.t = t
Vue.prototype.n = n

Vue.use(Vuex)

export default {
	userSetting: new Vue({
		el: '#activity-user-settings',
		store,
		name: 'ActivityPersonalSettings',
		render: h => h(UserSettings),
	}),
	digestSetting: new Vue({
		el: '#activity-digest-user-settings',
		name: 'ActivityDigestPersonalSettings',
		store,
		render: h => h(DailySummary),
	}),
}
