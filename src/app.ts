/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
