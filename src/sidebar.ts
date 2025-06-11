/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { App, Component, ComponentPublicInstance } from 'vue'
import type { ActivityTabType } from './views/ActivityTab.vue'

import LightningBolt from '@mdi/svg/svg/lightning-bolt.svg?raw'
import { translate as t } from '@nextcloud/l10n'
import { createApp } from 'vue'

// Init Activity tab component
let LazyActivityTab: Component | null = null
let activityTabApp: App<Element> | null = null
let activityTabInstance: ComponentPublicInstance<ActivityTabType> | null = null

const activityTab = new OCA.Files.Sidebar.Tab({
	id: 'activity',
	name: t('activity', 'Activity'),
	iconSvg: LightningBolt,

	async mount(el, fileInfo) {
		// only load if needed
		if (LazyActivityTab === null) {
			const { default: ActivityTab } = await import('./views/ActivityTab.vue')
			LazyActivityTab = ActivityTab
		}
		// destroy previous instance if available
		if (activityTabApp) {
			activityTabApp.unmount()
		}
		activityTabApp = createApp(LazyActivityTab)
		// No need to await this, we will show a loading indicator instead
		activityTabInstance = activityTabApp.mount(el)
		activityTabInstance.update(fileInfo)
	},
	update(fileInfo) {
		activityTabInstance!.update(fileInfo)
	},
	destroy() {
		activityTabApp?.unmount()
		activityTabApp = null
	},
})

window.addEventListener('DOMContentLoaded', async function() {
	if (OCA.Files && OCA.Files.Sidebar) {
		OCA.Files.Sidebar.registerTab(activityTab)
		const { default: ActivityTab } = await import('./views/ActivityTab.vue')
		LazyActivityTab = ActivityTab
	}
})
