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

Vue.prototype.t = t
Vue.prototype.n = n

// eslint-disable-next-line
__webpack_nonce__ = btoa(OC.requestToken);
// eslint-disable-next-line
__webpack_public_path__ = OC.linkTo('activity', 'js/');

// Init Activity tab component
let ActivityTabView = null
let ActivityTabInstance = null
const activityTab = new OCA.Files.Sidebar.Tab({
	id: 'activity',
	name: t('activity', 'Activity'),
	icon: 'icon-activity',

	async mount(el, fileInfo, context) {
		const { default: ActivityTab } = await import(/* webpackPreload: true */ './views/ActivityTab.vue')
		ActivityTabView = ActivityTabView ?? Vue.extend(ActivityTab)
		if (ActivityTabInstance) {
			ActivityTabInstance.$destroy()
		}
		ActivityTabInstance = new ActivityTabView({
			// Better integration with vue parent component
			parent: context,
		})
		// Only mount after we have all the info we need
		await ActivityTabInstance.update(fileInfo)
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
