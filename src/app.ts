/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { generateUrl } from '@nextcloud/router'
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import ActivityApp from './views/ActivityApp.vue'
import { routes } from './routes.ts'

const router = createRouter({
	history: createWebHistory(generateUrl('/apps/activity')),
	linkActiveClass: 'active',
	routes,
})

const app = createApp(ActivityApp)
app.use(router)
app.mount('#content')
