/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GNU AGPL version 3 or any later version
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

import { translate as t, translatePlural as n } from '@nextcloud/l10n'
import { generateFilePath } from '@nextcloud/router'

import ActivityApp from './views/ActivityApp'

import router from './app-router.js'
// import store from './app-store.js'

Vue.prototype.t = t
Vue.prototype.n = n

// eslint-disable-next-line no-undef, camelcase
__webpack_public_path__ = generateFilePath(appName, '', 'js/')

Vue.use(Vuex)

// eslint-disable-next-line no-new
new Vue({
	el: '#content',
	// eslint-disable-next-line vue/match-component-file-name
	name: 'ActivityApp',
	router,
	render: h => h(ActivityApp),
})
