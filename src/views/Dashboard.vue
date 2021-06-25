<!--
Nextcloud - Activity

@author Jakob Röhrl
@copyright 2021 Jakob Röhrl <jakob.roehrl@web.de>

@author Raimund Schlüßler
@copyright 2021 Raimund Schlüßler <raimund.schluessler@mailbox.org>

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
License as published by the Free Software Foundation; either
version 3 of the License, or any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU AFFERO GENERAL PUBLIC LICENSE for more details.

You should have received a copy of the GNU Affero General Public
License along with this library.  If not, see <http://www.gnu.org/licenses/>.

-->
<template>
	<DashboardWidget
		id="activity_panel"
		:items="activities"
		:show-more-text="t('activity', 'activities')"
		:show-more-url="showMoreUrl"
		:loading="loading"
		:half-empty-content-message="t('activity', 'No activities')">
		<template #default="{ item }">
			<!-- {{item._activity}} -->
			<DashboardWidgetItem
				:id="item.activity_id"
				:item="item._activity"
				:target-url="item.link"
				:avatar-username="item.user"
				:main-text="item.subject"
				:sub-text="item.datetime" />
		</template>
	</DashboardWidget>
</template>

<script>
import { DashboardWidget, DashboardWidgetItem } from '@nextcloud/vue-dashboard'
import axios from '@nextcloud/axios'
import ActivityModel from '../models/ActivityModel'
import { generateUrl, generateOcsUrl } from '@nextcloud/router'

export default {
	name: 'Dashboard',
	components: {
		DashboardWidget,
		DashboardWidgetItem,
	},
	data() {
		return {
			loading: true,
			activities: [],
		}
	},
	computed: {
		showMoreUrl() {
			return this.activities.length > 7 ? generateUrl('/apps/activity') : null
		},
	},
	mounted() {
		this.getActivities()
	},
	methods: {
		async getActivities() {
			try {
				this.loading = true
				const activities = await axios.get(generateOcsUrl('apps/activity/api/v2/activity'))
				this.loading = false
				this.processActivities(activities)
			} catch (error) {
				// Status 304 is not an error.
				if (error.response !== undefined && error.response.status === 304) {
					this.loading = false
					return
				}
				this.loading = false
				console.error('Error loading the activity list', error)
			}
		},

		processActivities({ data }) {
			if (data.ocs && data.ocs.data && data.ocs.data.length > 0) {
				// create Activity objects and sort by newest
				this.activities = data.ocs.data
					.map(activity => new ActivityModel(activity))
					.sort((a, b) => b.timestamp - a.timestamp)
			}
			console.log(data)
		},
	},

}
</script>

<style lang="scss">
#activity_panel {
	.calendar-dot {
		height: 1rem;
		width: 1rem;
		margin-top: .2rem;
		border-radius: 50%;
		min-width: 1rem;
		min-height: 1rem;
	}
}
</style>
