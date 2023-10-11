/**
 * @copyright Copyright (c) 2023 Nextcloud GmbH
 *
 * @author Ferdinand Thiessen <opensource@fthiessen.de>
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

import Vue from 'vue'
import VueRouter from 'vue-router'
import ActivityApp from './views/ActivityApp.vue'

import { translate, translatePlural } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'

import { routes } from './routes'

// Set up Vue
Vue.prototype.t = translate
Vue.prototype.n = translatePlural

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	base: generateUrl('/apps/activity'),
	linkActiveClass: 'active',
	routes,
})

export default new Vue({
	el: '#content',
	// eslint-disable-next-line vue/match-component-file-name
	name: 'ActivityRoot',
	router,
	render: h => h(ActivityApp),
})
