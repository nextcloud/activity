<!--
  - @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  - @author Stephan Orbaugh <stephan.orbaugh@nextcloud.com>
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
		<!-- Editor -->
		<Comment v-bind="editorData"
			:auto-complete="autoComplete"
			:user-data="userData"
			:editor="true"
			:ressource-id="fileInfo.id"
			class="comments__writer"
			@new="getActivities" />

		<!-- error message -->
		<NcEmptyContent v-if="error" :title="error">
			<template #icon>
				<span class="icon-error" />
			</template>
		</NcEmptyContent>
		<template v-else>
			<!-- activities content -->
			<ul>
				<Activity v-for="activity in activities"
					:key="activity.id"
					:activity="activity" />
			</ul>

			<NcEmptyContent v-if="activities.length === 0 && !loading"
				:title="t('activity', 'No activity yet')">
				<template #icon>
					<span class="icon-activity" />
				</template>
			</NcEmptyContent>
		</template>
	</div>
</template>

<script>
import { generateOcsUrl } from '@nextcloud/router'
import { getCurrentUser } from '@nextcloud/auth'
import { translate as t } from '@nextcloud/l10n'
import axios from '@nextcloud/axios'
import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'

import Activity from '../components/Activity.vue'
import ActivityModel from '../models/ActivityModel.js'

import Comment from '../components/Comment.vue'
import logger from '../logger.js'
import CommentMixin from '../mixins/CommentMixin.js'
import RichEditorMixin from '@nextcloud/vue/dist/Mixins/richEditor.js'
import { loadState } from '@nextcloud/initial-state'

export default {
	name: 'ActivityTab',
	components: {
		Activity,
		NcEmptyContent,
		Comment,
	},
	mixins: [RichEditorMixin, CommentMixin],
	props: {
		actorDisplayName: {
			type: String,
			required: true,
		},
		actorId: {
			type: String,
			required: true,
		},
		creationDateTime: {
			type: String,
			default: null,
		},

		/**
		 * Force the editor display
		 */
		editor: {
			type: Boolean,
			default: false,
		},

		/**
		 * Provide the autocompletion data
		 */
		autoComplete: {
			type: Function,
			required: true,
		},

		tag: {
			type: String,
			default: 'div',
		},
	},
	data() {
		return {
			error: '',
			loading: true,
			fileInfo: null,
			activities: [],
			localMessage: '',
			userData: {},
			editorData: {
				actorDisplayName: getCurrentUser().displayName,
				actorId: getCurrentUser().uid,
				key: 'editor',
			},
		}
	},
	computed: {
		isEmptyMessage() {
			return !this.localMessage || this.localMessage.trim() === ''
		},
	},
	watch: {
		// If the data change, update the local value
		message(message) {
			this.updateLocalMessage(message)
		},
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

				const activities = await axios.get(
					generateOcsUrl('apps/activity/api/v2/activity/filter'),
					{
						params: {
							format: 'json',
							object_type: 'files',
							object_id: this.fileInfo.id,
						},
					})

				this.loading = false

				this.processActivities(activities)
			} catch (error) {
				// Status 304 is not an error.
				if (error.response !== undefined && error.response.status === 304) {
					this.loading = false
					return
				}
				this.error = t('activity', 'Unable to load the activity list')
				this.loading = false
				console.error('Error loading the activity list', error)
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
		 * Process the current activity data
		 * and init activities[]
		 *
		 * @param {object} activity the activity ocs api request data
		 * @param {object} activity.data the request data
		 */
		processActivities({ data }) {
			if (data.ocs && data.ocs.data && data.ocs.data.length > 0) {
				// create Activity objects and sort by newest
				this.activities = data.ocs.data
					.map(activity => new ActivityModel(activity))
					.sort((a, b) => b.timestamp - a.timestamp)

				logger.debug(`Processed ${this.activities.length} activity(ies)`, { activities: this.activities, fileInfo: this.fileInfo })
			}
		},

		t,

		/**
		 * Update local Message on outer change
		 *
		 * @param {string} message the message to set
		 */
		updateLocalMessage(message) {
			this.localMessage = message.toString()
		},

		/**
		 * Autocomplete @mentions
		 *
		 * @param {string} search the query
		 * @param {Function} callback the callback to process the results with
		 */
		async autoComplete(search, callback) {
			const results = await axios.get(generateOcsUrl('core/autocomplete/get'), {
				params: {
					search,
					itemType: 'files',
					itemId: this.fileInfo.id,
					sorter: 'commenters|share-recipients',
					limit: loadState('comments', 'maxAutoCompleteResults'),
				},
			})
			// Save user data so it can be used by the editor to replace mentions
			results.data.ocs.data.forEach(user => { this.userData[user.id] = user })
			return callback(Object.values(this.userData))
		},
	},
}
</script>

<style scoped lang="scss">
::v-deep .empty-content__icon span {
	background-size: 64px;
	width: 64px;
	height: 64px;
}
</style>
