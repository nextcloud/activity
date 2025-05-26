/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import LightningBolt from '@mdi/svg/svg/lightning-bolt.svg?raw'
import { translatePlural as n, translate as t } from '@nextcloud/l10n'
import Vue from 'vue'

Vue.prototype.t = t
Vue.prototype.n = n

// Init Activity tab component
let ActivityTabView = null
let ActivityTabInstance = null
const activityTab = new OCA.Files.Sidebar.Tab({
	id: 'activity',
	name: t('activity', 'Activity'),
	iconSvg: LightningBolt,

	async mount(el, fileInfo, context) {
		// only load if needed
		if (ActivityTabView === null) {
			const { default: ActivityTab } = await import('./views/ActivityTab.vue')
			ActivityTabView = ActivityTabView ?? Vue.extend(ActivityTab)
		}
		// destroy previous instance if available
		if (ActivityTabInstance) {
			ActivityTabInstance.$destroy()
		}
		ActivityTabInstance = new ActivityTabView({
			// Better integration with vue parent component
			parent: context,
		})
		// No need to await this, we will show a loading indicator instead
		ActivityTabInstance.update(fileInfo)
		ActivityTabInstance.$mount(el)
	},
	update(fileInfo) {
		ActivityTabInstance.update(fileInfo)
	},
	destroy() {
		ActivityTabInstance.$destroy()
		ActivityTabInstance = null
	},
})

window.addEventListener('DOMContentLoaded', async function() {
	if (OCA.Files && OCA.Files.Sidebar) {
		OCA.Files.Sidebar.registerTab(activityTab)
		const { default: ActivityTab } = await import('./views/ActivityTab.vue')
		ActivityTabView = ActivityTabView ?? Vue.extend(ActivityTab)
	}
})
