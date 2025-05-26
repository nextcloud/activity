/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { Store } from 'vuex'
import type { SettingsState } from './store/settings-store.ts'

declare module '@nextcloud/vue/Components/*' {
	import type { Component } from 'vue'

	const vue: Component
	export default vue
}

declare module 'vue-material-design-icons/*.vue' {
	import type { DefineComponent } from 'vue'

	const IconVue: DefineComponent<{
		/// `size` defaults to 24
		size?: number
		/// `fillColor` defaults to 'currentColor'
		fillColor?: string
		title?: string
	}>

	export default IconVue
}

declare module '*.svg?raw' {
	const content: string
	export default content
}

declare module 'vue' {
	interface ComponentCustomProperties {
		$store: Store<SettingsState>
	}
}
