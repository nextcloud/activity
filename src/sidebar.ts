/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import LightningBolt from '@mdi/svg/svg/lightning-bolt.svg?raw'
import { translate as t } from '@nextcloud/l10n'
import { type App } from 'vue'
import { createApp } from 'vue'

// Init Activity tab component
let ActivityTab = null
let ActivityTabInstance: App<Element> | null = null
const activityTab = new OCA.Files.Sidebar.Tab({
	id: 'activity',
	name: t('activity', 'Activity'),
	iconSvg: LightningBolt,

	async mount(el, fileInfo) {
		// only load if needed
		if (ActivityTab === null) {
			const { default: ActivityTabDef } = await import('./views/ActivityTab.vue')
			ActivityTab = ActivityTabDef
		}
		// destroy previous instance if available
		if (ActivityTabInstance) {
			ActivityTabInstance.unmount()
		}
		ActivityTabInstance = createApp({ extends: ActivityTab })
		// No need to await this, we will show a loading indicator instead
		ActivityTabInstance.update(fileInfo)
		ActivityTabInstance.mount(el)
	},
	update(fileInfo) {
		ActivityTabInstance!.update(fileInfo)
	},
	unmount() {
		ActivityTabInstance?.unmount()
		ActivityTabInstance = null
	},
})

window.addEventListener('DOMContentLoaded', async function() {
	if (OCA.Files && OCA.Files.Sidebar) {
		OCA.Files.Sidebar.registerTab(activityTab)
		const { default: ActivityTabDef } = await import('./views/ActivityTab.vue')
		ActivityTab = ActivityTabDef
	}
})
