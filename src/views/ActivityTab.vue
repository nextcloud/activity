<!--
  - @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  -
  - @license AGPL-3.0-or-later
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<div :class="{ 'icon-loading': loading }">
		<!-- error message -->
		<NcEmptyContent v-if="error" :name="error">
			<template #icon>
				<NcIconSvgWrapper :svg="lightningBoltSVG" />
			</template>
		</NcEmptyContent>
		<template v-else>
			<!-- activities actions -->
			<div v-if="sidebarPlugins.length > 0" class="activity__actions">
				<ActivitySidebarPlugin v-for="plugin,index of sidebarPlugins"
					:key="index"
					:plugin="plugin"
					:file-info="fileInfo"
					@reload-activities="getActivities()" />
			</div>

			<!-- activities content -->
			<NcEmptyContent v-if="loading"
				:name="t('activity', 'Loading activities')">
				<template #icon>
					<NcLoadingIcon />
				</template>
			</NcEmptyContent>
			<NcEmptyContent v-else-if="activities.length === 0"
				:name="t('activity', 'No activity yet')">
				<template #icon>
					<span class="icon-activity" />
				</template>
			</NcEmptyContent>
			<ul v-else>
				<Activity v-for="activity in activities"
					:key="activity.id"
					:activity="activity"
					:show-previews="false"
					@reload="getActivities()" />
			</ul>
		</template>
	</div>
</template>

<script>
import axios from '@nextcloud/axios'

import { generateOcsUrl } from '@nextcloud/router'
import { translate as t } from '@nextcloud/l10n'
import { NcEmptyContent, NcIconSvgWrapper, NcLoadingIcon } from '@nextcloud/vue'
import { getAdditionalEntries, getSidebarActions, getActivityFilters } from '../utils/api.ts'

import logger from '../utils/logger.ts'
import Activity from '../components/Activity.vue'
import ActivityModel from '../models/ActivityModel.ts'
import ActivitySidebarPlugin from '../components/ActivitySidebarPlugin.vue'

import lightningBoltSVG from '@mdi/svg/svg/lightning-bolt.svg?raw'

export default {
	name: 'ActivityTab',
	components: {
		Activity,
		NcEmptyContent,
		NcIconSvgWrapper,
		NcLoadingIcon,
		ActivitySidebarPlugin,
	},
	data() {
		return {
			error: '',
			loading: true,
			fileInfo: null,
			activities: [],
			lightningBoltSVG,
			sidebarPlugins: [],
		}
	},
	mounted() {
		this.sidebarPlugins = getSidebarActions()
	},
	methods: {
		/**
		 * Update current fileInfo and fetch new activities
		 *
		 * @param {object} fileInfo the current file FileInfo
		 */
		async update(fileInfo) {
			this.fileInfo = fileInfo
			this.resetState()
			await this.getActivities()
		},
		/**
		 * Get the existing activities
		 */
		async getActivities() {
			try {
				this.loading = true

				const activities = await this.processActivities(await this.loadRealActivities())
				const otherEntries = await getAdditionalEntries({ fileInfo: this.fileInfo })
				this.activities = [...activities, ...otherEntries].sort((a, b) => b.timestamp - a.timestamp)
			} catch (error) {
				this.error = t('activity', 'Unable to load the activity list')
				console.error('Error loading the activity list', error)
			} finally {
				this.loading = false
			}
		},
		/**
		 * Reset the current view to its default state
		 */
		resetState() {
			this.loading = true
			this.error = ''
			this.activities = []
		},

		/**
		 * Load activites from API
		 */
		async loadRealActivities() {
			try {
				const { data } = await axios.get(
					generateOcsUrl('apps/activity/api/v2/activity/filter'),
					{
						params: {
							format: 'json',
							object_type: 'files',
							object_id: this.fileInfo.id,
						},
					},
				)
				return data.ocs.data
			} catch (e) {
				// Status 304 is not an error.
				if (error.response !== undefined && error.response.status === 304) {
					return []
				}
				throw e
			}
		},

		/**
		 * Process the API response activities and apply filter
		 *
		 * @param {any[]} activities the activites
		 */
		processActivities(activities) {
			activities = activities.map(activity => new ActivityModel(activity))

			logger.debug(`Processed ${activities.length} activity(ies)`, { activities, fileInfo: this.fileInfo })

			const filters = getActivityFilters()
			return activities.filter((activity) => !filters || filters.every((filter) => filter(activity)))
		},

		t,
	},
}
</script>

<style scoped lang="scss">
:deep(.empty-content__icon span) {
	background-size: 64px;
	width: 64px;
	height: 64px;
}

.activity__actions {
	display: flex;
	flex-direction: column;
	width: 100%;
}
</style>
