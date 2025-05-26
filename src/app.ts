/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { translate, translatePlural } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'
import Vue from 'vue'
import VueRouter from 'vue-router'
import ActivityApp from './views/ActivityApp.vue'
import { routes } from './routes.ts'

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
	name: 'ActivityRoot',
	router,
	render: (h) => h(ActivityApp),
})
