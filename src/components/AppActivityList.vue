<!--
 - @copyright Copyright (c) 2019 John Molakvoæ <skjnldsv@protonmail.com>
 -
 - @author John Molakvoæ <skjnldsv@protonmail.com>
 - @author Corentin Mors <medias@pixelswap.fr>
 -
 - @license GNU AGPL version 3 or any later version
 -
 - This program is free software: you can redistribute it and/or modify
 - it under the terms of the GNU Affero General Public License as
 - published by the Free Software Foundation, either version 3 of the
 - License, or (at your option) any later version.
 -
 - This program is distributed in the hope that it will be useful,
 - but WITHOUT ANY WARRANTY; without even the implied warranty of
 - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 - GNU Affero General Public License for more details.
 -
 - You should have received a copy of the GNU Affero General Public License
 - along with this program. If not, see <http://www.gnu.org/licenses/>.
 -
 -->

<template>
	<div v-infinite-scroll="getActivities" class="activities">
		<!-- error message -->
		<EmptyContent v-if="error" icon="icon-error">
			{{ t('activity', 'An error occurred') }} - {{ error }}
		</EmptyContent>
		<div v-else>
			<!-- activities content -->
			<div v-for="group in activitiesByDate" :key="group[0].formattedDay" class="activities__group">
				<h2 v-tooltip.bottom="group[0].formattedDate" class="activities__group__header">
					{{ group[0].formattedDay }}
				</h2>

				<ul class="activities__group__content">
					<Activity
						v-for="activity in group"
						:key="activity.id"
						:show-avatar="true"
						:activity="activity" />
				</ul>
			</div>

			<EmptyContent v-if="activities.length === 0 && !loading" icon="icon-activity">
				{{ t('activity', 'No activity yet') }}
			</EmptyContent>
		</div>
	</div>
</template>

<script>
import { generateOcsUrl } from '@nextcloud/router'
import EmptyContent from '@nextcloud/vue/dist/Components/EmptyContent'
import axios from '@nextcloud/axios'
import Tooltip from '@nextcloud/vue/dist/Directives/Tooltip'

import Activity from './Activity'
import ActivityFetcher from '../mixins/ActivityFetcher'
import InfiniteScroll from '../directives/InfiniteScroll'
import ActivityModel from '../models/ActivityModel'
import logger from '../logger'

export default {
	name: 'AppActivityList',
	components: {
		Activity,
		EmptyContent,
	},
	directives: {
		tooltip: Tooltip,
		'infinite-scroll': InfiniteScroll,
	},
	mixins: [ActivityFetcher],
	props: {
		filter: {
			type: String,
			default: 'all',
		},
	},

	data() {
		return {
			loading: true,
			/** @type {Array<ActivityModel>} */
			activities: [],
			error: null,
			firstKnownId: 0,
			lastGivenId: 0,
			outOfContent: false,
		}
	},

	computed: {
		/**
		 * Group activities by date.
		 *
		 * @returns {Object<string, Array<ActivityModel>>}
		 */
		activitiesByDate() {
			const activitiesByDate = {}

			for (const activity of this.activities) {
				if (activitiesByDate[activity.formattedDay] === undefined) {
					activitiesByDate[activity.formattedDay] = []
				}

				activitiesByDate[activity.formattedDay].push(activity)
			}

			return activitiesByDate
		},
	},
	afterMount() {
		this.getActivities()
	},
	watch: {
		filter() {
			this.resetState()
			this.getActivities()
		},
	},
	methods: {
		resetState() {
			this.activities = []
			this.error = null
			this.firstKnownId = 0
			this.lastGivenId = 0
			this.outOfContent = false
		},
		async getActivities() {
			if (this.outOfContent) {
				return
			}

			try {
				this.loading = true

				const response = await axios.get(
					generateOcsUrl(`apps/activity/api/v2/activity/${this.filter}`),
					{
						params: {
							format: 'json',
							previews: true,
							since: this.lastGivenId,
						},
						headers: {
							'Accept-Language': OC.getLanguage(),
						},
					})

				if (response.headers['x-activity-first-known'] !== undefined) {
					this.firstKnownId = parseInt(response.headers['x-activity-first-known'], 10)
				}

				if (response.headers['x-activity-last-given'] !== undefined) {
					this.lastGivenId = parseInt(response.headers['x-activity-last-given'], 10)
				}

				this.processActivities(response)
			} catch (error) {
				// Status 304 is not an error, it just means that we are out of content.
				if (error.response !== undefined && error.response.status === 304) {
					this.outOfContent = true
					return
				}
				this.error = t('activity', 'Unable to load the activity list')
				logger.error('Error loading the activity list', error)
			} finally {
				this.loading = false
			}
		},
		/**
		* Process the current activity data
		* and init activities[]
		*
		* @param {Object} activity the activity ocs api request data
		* @param {Object} activity.data the request data
		*/
		processActivities({ data }) {
			if (data.ocs && data.ocs.data && data.ocs.data.length > 0) {
				// create Activity objects and sort by newest
				const newActivities = data.ocs.data
					.map(activity => new ActivityModel(activity))
					.sort((a, b) => b.timestamp - a.timestamp)

				this.activities.push(...newActivities)

				logger.debug(`Processed ${this.activities.length} activity(ies)`, { activities: this.activities, fileInfo: this.fileInfo })
			}
		},
	},
}
</script>
<style lang="scss" scoped>
.activities {
	padding: 30px;
	// Add space the the first header is not to close to the hamburger menu.
	padding-top: 32px;
	// TODO - use variable
	height: calc(100vh - 50px);
	overflow: scroll;
}

.activities__group {
	margin-bottom: 60px;

	&__header {
		margin-left: 20px;
		// Center the tooltip bellow the text.
		width: fit-content;
	}
}
</style>
