/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license AGPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
import Vue from 'vue'
import { translate as t, translatePlural as n } from '@nextcloud/l10n'

// eslint-disable-next-line import/no-unresolved
import LightningBolt from '@mdi/svg/svg/lightning-bolt.svg?raw'

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
		const { default: ActivityTab } = await import(/* webpackPreload: true */ './views/ActivityTab.vue')
		ActivityTabView = ActivityTabView ?? Vue.extend(ActivityTab)
	}
})
