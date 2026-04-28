/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { RouteRecordRaw } from 'vue-router'

import ActivityAppFeed from './views/ActivityAppFeed.vue'
import ActivityAppNavigation from './views/ActivityAppNavigation.vue'
import ActivityInsights from './views/ActivityInsights.vue'

export const routes = [
	{
		path: '/',
		name: 'root',
		redirect: { path: '/all' },
	},
	{
		// Static path needs to be matched ahead of `/:filter?` so router does
		// not try to load /insights as a filter id.
		path: '/insights',
		name: 'insights',
		components: {
			default: ActivityInsights,
			navigation: ActivityAppNavigation,
		},
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
