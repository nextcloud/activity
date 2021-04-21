/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GNU AGPL version 3 or any later version
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

import ActivityTab from './views/ActivityTab'

Vue.prototype.t = t
Vue.prototype.n = n

// Init Activity tab component
const ActivityTabView = Vue.extend(ActivityTab)

// Init Activity tab component
let ActivityTabInstance = null
const activityTab = new OCA.Files.Sidebar.Tab({
	id: 'activity',
	name: t('activity', 'Activity'),
	icon: 'icon-activity',

	async mount(el, fileInfo, context) {
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

window.addEventListener('DOMContentLoaded', function() {
	if (OCA.Files && OCA.Files.Sidebar) {
		OCA.Files.Sidebar.registerTab(activityTab)
	}
})
