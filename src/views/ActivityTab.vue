<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div
		:class="{ 'icon-loading': loading }"
		class="activity">
		<!-- error message -->
		<NcEmptyContent v-if="error || fileInfo === null" :name="error">
			<template #icon>
				<NcIconSvgWrapper :svg="lightningBoltSVG" />
			</template>
		</NcEmptyContent>
		<template v-else>
			<!-- activities actions -->
			<div v-if="sidebarPlugins.length > 0" class="activity__actions">
				<ActivitySidebarPlugin
					v-for="plugin, index of sidebarPlugins"
					:key="index"
					:plugin="plugin"
					:file-info="fileInfo"
					@reload-activities="getActivities()" />
			</div>

			<!-- activities content -->
			<NcEmptyContent
				v-if="loading"
				class="activity__empty-content"
				:name="t('activity', 'Loading activities')">
				<template #icon>
					<NcLoadingIcon />
				</template>
			</NcEmptyContent>
			<NcEmptyContent
				v-else-if="activities.length === 0"
				class="activity__empty-content"
				:name="t('activity', 'No activity yet')">
				<template #icon>
					<span class="icon-activity" />
				</template>
			</NcEmptyContent>
			<ul v-else class="activity__list">
				<ActivityComponent
					v-for="activity in activities"
					:key="activity.id"
					:activity="activity"
					:show-previews="false"
					@reload="getActivities()" />
			</ul>
		</template>
	</div>
</template>

<script lang='ts'>
import type { IActivitySidebarAction, IActivitySidebarEntry } from '../models/ActivityAPI.ts'

import lightningBoltSVG from '@mdi/svg/svg/lightning-bolt.svg?raw'
import axios from '@nextcloud/axios'
import { translate as t } from '@nextcloud/l10n'
import { generateOcsUrl } from '@nextcloud/router'
import { defineComponent, nextTick } from 'vue'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import NcLoadingIcon from '@nextcloud/vue/components/NcLoadingIcon'
import ActivityComponent from '../components/ActivityComponent.vue'
import ActivitySidebarPlugin from '../components/ActivitySidebarPlugin.vue'
import ActivityModel from '../models/ActivityModel.ts'
import { getActivityFilters, getAdditionalEntries, getSidebarActions } from '../utils/api.ts'
import logger from '../utils/logger.ts'

const ActivityTab = defineComponent({
	name: 'ActivityTab',
	components: {
		ActivityComponent,
		NcEmptyContent,
		NcIconSvgWrapper,
		NcLoadingIcon,
		ActivitySidebarPlugin,
	},

	expose: ['update'],

	data() {
		return {
			error: '',
			loading: true,
			fileInfo: null,
			activities: [] as (IActivitySidebarEntry | ActivityModel)[],
			lightningBoltSVG,
			sidebarPlugins: [] as IActivitySidebarAction[],
		}
	},

	mounted() {
		this.sidebarPlugins = getSidebarActions()
	},

	methods: {
		/**
		 * Update current fileInfo and fetch new activities
		 *
		 * @param fileInfo the current file FileInfo
		 */
		async update(fileInfo) {
			this.sidebarPlugins = []
			const sidebarPlugins = getSidebarActions()
			if (sidebarPlugins.length > 0) {
				nextTick(() => {
					this.sidebarPlugins = sidebarPlugins
				})
			}

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
				logger.error('Error loading the activity list', { error })
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
			} catch (error) {
				// Status 304 is not an error.
				if (error.response !== undefined && error.response.status === 304) {
					return []
				}
				throw error
			}
		},

		/**
		 * Process the API response activities and apply filter
		 *
		 * @param activities the activites
		 */
		processActivities(activities): ActivityModel[] {
			activities = activities.map((activity) => new ActivityModel(activity))

			logger.debug(`Processed ${activities.length} activity(ies)`, { activities, fileInfo: this.fileInfo })

			const filters = getActivityFilters()
			return activities.filter((activity) => !filters || filters.every((filter) => filter(activity)))
		},

		t,
	},
})

export default ActivityTab
export type ActivityTabType = typeof ActivityTab
</script>

<style scoped lang="scss">
.activity {
	display: flex;
	flex-direction: column;
	overflow: hidden;
	height: 100%;

	&__actions {
		display: flex;
		flex-direction: column;
		width: 100%;
	}

	&__list {
		flex-grow: 1;
		overflow: scroll;
	}

	&__empty-content {
		height: 100%;
	}
}

:deep(.empty-content__icon span) {
	background-size: 64px;
	width: 64px;
	height: 64px;
}
</style>
