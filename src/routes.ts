/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { RouteRecordRaw } from 'vue-router'

import ActivityAppFeed from './views/ActivityAppFeed.vue'
import ActivityAppNavigation from './views/ActivityAppNavigation.vue'

export const routes = [
	{
		path: '/',
		name: 'root',
		redirect: { path: '/all' },
	},
	{
		path: '/:filter?',
		components: {
			default: ActivityAppFeed,
			navigation: ActivityAppNavigation,
		},
		props: {
			default: true,
		},
	},
] as RouteRecordRaw[]
