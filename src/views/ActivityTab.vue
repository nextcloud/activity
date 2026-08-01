<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div
		:class="{ 'icon-loading': loading }"
		class="activity">
		<!-- error message -->
		<NcEmptyContent v-if="error || !node" :name="error">
			<template #icon>
				<NcIconSvgWrapper :svg="lightningBoltSVG" />
			</template>
		</NcEmptyContent>
		<template v-else>
			<!-- activities actions -->
			<div v-if="sidebarPlugins.length > 0" class="activity__actions">
				<ActivitySidebarPlugin
					v-for="(plugin, index) of sidebarPlugins"
					:key="index"
					:plugin="plugin"
					:node="node"
					@reloadActivities="getActivities()" />
			</div>

			<!-- download summary -->
			<DownloadSummary
				v-if="hasPublicLink && node.fileid"
				:fileId="node.fileid" />

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
					<NcIconSvgWrapper :svg="lightningBoltSVG" />
				</template>
			</NcEmptyContent>
			<ul v-else class="activity__list">
				<ActivityComponent
					v-for="activity in activities"
					:key="activity.id"
					:activity="activity"
					:showPreviews="false"
					@reload="getActivities()" />
			</ul>
		</template>
	</div>
</template>

<script lang='ts'>
import type { IFolder, INode, IView } from '@nextcloud/files'
import type { PropType } from 'vue'
import type { IActivitySidebarAction, IActivitySidebarEntry } from '../models/ActivityAPI.ts'

import lightningBoltSVG from '@mdi/svg/svg/lightning-bolt.svg?raw'
import axios from '@nextcloud/axios'
import { translate as t } from '@nextcloud/l10n'
import { generateOcsUrl } from '@nextcloud/router'
import { ShareType } from '@nextcloud/sharing'
import { defineComponent, nextTick } from 'vue'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import NcLoadingIcon from '@nextcloud/vue/components/NcLoadingIcon'
import ActivityComponent from '../components/ActivityComponent.vue'
import ActivitySidebarPlugin from '../components/ActivitySidebarPlugin.vue'
import DownloadSummary from '../components/DownloadSummary.vue'
import ActivityModel from '../models/ActivityModel.ts'
import { getActivityFilters, getAdditionalEntries, getSidebarActions } from '../utils/api.ts'
import logger from '../utils/logger.ts'

const ActivityTab = defineComponent({
	name: 'ActivityTab',

	components: {
		ActivityComponent,
		DownloadSummary,
		NcEmptyContent,
		NcIconSvgWrapper,
		NcLoadingIcon,
		ActivitySidebarPlugin,
	},

	props: {
		/**
		 * The node currently displayed in the sidebar
		 */
		node: {
			type: Object as PropType<INode>,
			required: true,
		},

		/**
		 * The folder shown in the files app
		 */
		// eslint-disable-next-line vue/no-unused-properties
		folder: {
			type: Object as PropType<IFolder | undefined>,
			required: false,
			default: undefined,
		},

		/**
		 * The view shown in the files app
		 */
		// eslint-disable-next-line vue/no-unused-properties
		view: {
			type: Object as PropType<IView | undefined>,
			required: false,
			default: undefined,
		},
	},

	expose: ['update'],

	data() {
		return {
			error: '',
			loading: true,
			activities: [] as (IActivitySidebarEntry | ActivityModel)[],
			lightningBoltSVG,
			sidebarPlugins: [] as IActivitySidebarAction[],
			// Only ever read inside methods, never from the template. Vue 3
			// leaves class instances it does not recognise unproxied, so the
			// controller keeps working: abort() needs its original receiver.
			requestController: null as AbortController | null,
		}
	},

	computed: {
		hasPublicLink(): boolean {
			const shareTypes = Object.values(this.node?.attributes?.['share-types'] ?? {}).flat() as number[]
			return shareTypes.includes(ShareType.Link)
		},
	},

	watch: {
		// `immediate` covers the initial load as well, so there is deliberately
		// no mounted() hook doing the same thing: having both meant every
		// sidebar open fired two identical requests
		node: {
			immediate: true,
			async handler() {
				await this.update()
			},
		},
	},

	beforeUnmount() {
		this.requestController?.abort()
	},

	methods: {
		/**
		 * Update current view and fetch new activities
		 */
		async update() {
			this.sidebarPlugins = []
			const sidebarPlugins = getSidebarActions()
			if (sidebarPlugins.length > 0) {
				nextTick(() => {
					this.sidebarPlugins = sidebarPlugins
				})
			}

			this.resetState()
			await this.getActivities()
		},

		/**
		 * Get the existing activities
		 *
		 * Any request still in flight is superseded first. Without that, moving
		 * between files faster than the server answers would let an earlier
		 * response arrive last and leave the panel showing another file's
		 * activity, since the responses are applied in arrival order rather
		 * than request order.
		 */
		async getActivities() {
			this.requestController?.abort()
			const controller = new AbortController()
			this.requestController = controller
			const { signal } = controller

			try {
				this.loading = true

				const activities = await this.processActivities(await this.loadRealActivities(signal))
				// Plugin supplied entries cannot be aborted, so their result is
				// discarded below instead
				const otherEntries = await getAdditionalEntries({ node: this.node })
				if (signal.aborted) {
					return
				}
				this.activities = [...activities, ...otherEntries].sort((a, b) => b.timestamp - a.timestamp)
			} catch (error) {
				if (signal.aborted) {
					// Cancelled in favour of a newer request, which owns the
					// state from here on
					return
				}
				this.error = t('activity', 'Unable to load the activity list')
				logger.error('Error loading the activity list', { error })
			} finally {
				// A newer request has already set this back to true
				if (!signal.aborted) {
					this.loading = false
				}
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
		 *
		 * @param signal - Aborted when a newer request supersedes this one
		 */
		async loadRealActivities(signal?: AbortSignal) {
			try {
				const { data } = await axios.get(
					generateOcsUrl('apps/activity/api/v2/activity/filter'),
					{
						signal,
						params: {
							format: 'json',
							object_type: 'files',
							object_id: this.node.fileid,
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

			logger.debug(`Processed ${activities.length} activity(ies)`, {
				activities,
				node: this.node,
			})

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
