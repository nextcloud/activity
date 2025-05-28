/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { ActivityTabType } from './views/ActivityTab.vue'

import LightningBolt from '@mdi/svg/svg/lightning-bolt.svg?raw'
import { translate as t } from '@nextcloud/l10n'
import { type App } from 'vue'
import { createApp } from 'vue'

// Init Activity tab component
let LazyActivityTab: ActivityTabType | null = null
let activityTabApp: App<Element> | null = null
const activityTab = new OCA.Files.Sidebar.Tab({
	id: 'activity',
	name: t('activity', 'Activity'),
	iconSvg: LightningBolt,

	async mount(el, fileInfo) {
		// only load if needed
		if (LazyActivityTab === null) {
			const { default: ActivityTabDef } = await import('./views/ActivityTab.vue')
			LazyActivityTab = ActivityTabDef
		}
		// destroy previous instance if available
		if (activityTabApp) {
			activityTabApp.unmount()
		}
		activityTabApp = createApp(LazyActivityTab)
		// No need to await this, we will show a loading indicator instead
		activityTabApp._component.methods.update(fileInfo)
		activityTabApp.mount(el)
	},
	update(fileInfo) {
		activityTabApp!._component.methods.update(fileInfo)
	},
	destroy() {
		activityTabApp?.unmount()
		activityTabApp = null
	},
})

window.addEventListener('DOMContentLoaded', async function() {
	if (OCA.Files && OCA.Files.Sidebar) {
		OCA.Files.Sidebar.registerTab(activityTab)
		const { default: ActivityTabDef } = await import('./views/ActivityTab.vue')
		LazyActivityTab = ActivityTabDef
	}
})
