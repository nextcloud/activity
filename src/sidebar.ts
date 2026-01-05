/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import LightningBolt from '@mdi/svg/svg/lightning-bolt.svg?raw'
import { registerSidebarTab } from '@nextcloud/files'
import { t } from '@nextcloud/l10n'
import { defineAsyncComponent, defineCustomElement } from 'vue'

const sidebarTabTag = 'activity-files-sidebar-tab'

if (window.customElements.get(sidebarTabTag) === undefined) {
	window.customElements.define(
		sidebarTabTag,
		defineCustomElement(defineAsyncComponent(() => import('./views/ActivityTab.vue')), { shadowRoot: false }),
	)

	registerSidebarTab({
		id: 'activity',
		order: 50,
		displayName: t('activity', 'Activity'),
		iconSvgInline: LightningBolt,
		tagName: sidebarTabTag,
		enabled() {
			return true
		},
	})
}
