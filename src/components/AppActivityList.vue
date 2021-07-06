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
		<!-- error message -->
		<EmptyContent v-if="error" icon="icon-error">
			{{ t('activity', 'An error occurred') }}
			{{ error }}
		</EmptyContent>
		<template v-else>
			<!-- activities content -->
			<ul>
				<Activity
					v-for="activity in activities"
					:key="activity.id"
					:activity="activity" />
			</ul>

			<EmptyContent v-if="activities.length === 0 && !loading" icon="icon-activity">
				{{ t('activity', 'No activity yet') }}
			</EmptyContent>
		</template>
	</div>
</template>

<script>
// import { mapGetters } from 'vuex'

import EmptyContent from '@nextcloud/vue/dist/Components/EmptyContent'

// import cancelableRequest from '../utils/CancelableRequest'
import ActivityFetcher from '../mixins/ActivityFetcher'

export default {
	name: 'Timeline',
	components: {
		EmptyContent,
	},
	props: {
		loading: {
			type: Boolean,
			required: true,
		},
		filters: {
			type: Array,
			default: false
		}
	},
	mixins: [ActivityFetcher],
	data() {
		return {
			activities: [],
			// cancelRequest: null,
			error: null,
		}
	},

	computed: {
		// global lists
		...mapGetters([
			'files',
			'timeline',
		]),
		/**
		 * @return {boolean} - Wether or not there is some activities.
		 */
		isEmpty() {
			return this.activities.length === 0
		},
	},

	beforeMount() {
		this.getActivities()
	},

	// beforeDestroy() {
	// 	// cancel any pending requests
	// 	if (this.cancelRequest) {
	// 		this.cancelRequest('Changed view')
	// 	}
	// 	this.resetState()
	// },

	methods: {
		/** Return next batch of data depending on global offset
		 * @param {boolean} doReturn Returns a Promise with the list instead of a boolean
		 * @returns {Promise<boolean>} Returns a Promise with a boolean that stops infinite loading
		 */
		async getContent(doReturn) {
			if (this.done) {
				return Promise.resolve(true)
			}

			// cancel any pending requests
			if (this.cancelRequest) {
				this.cancelRequest('Changed view')
			}

			// if we don't already have some cached data let's show a loader
			if (this.timeline.length === 0) {
				this.$emit('update:loading', true)
			}

			// done loading even with errors
			const { request, cancel } = cancelableRequest(getPhotos)
			this.cancelRequest = cancel

			const numberOfImagesPerBatch = this.gridConfig.count * 5 // loading 5 rows

			try {
				// Load next batch of images
				const files = await request(this.onlyFavorites, {
					page: this.page,
					perPage: numberOfImagesPerBatch,
					mimesType: this.mimesType,
				})

				// If we get less files than requested that means we got to the end
				if (files.length !== numberOfImagesPerBatch) {
					this.done = true
				}

				this.$store.dispatch('updateTimeline', files)
				this.$store.dispatch('appendFiles', files)

				this.page += 1

				if (doReturn) {
					return Promise.resolve(files)
				}

				return Promise.resolve(false)
			} catch (error) {
				if (error.response && error.response.status) {
					if (error.response.status === 404) {
						this.error = 404
						setTimeout(() => {
							this.$router.push({ name: this.$route.name })
						}, 3000)
					} else {
						this.error = error
					}
				}

				// cancelled request, moving on...
				console.error('Error fetching timeline', error)
				return Promise.resolve(true)
			} finally {
				// done loading even with errors
				this.$emit('update:loading', false)
				this.cancelRequest = null
			}
		},
	},

}
</script>

<style lang="scss" scoped>
$previous: 0;
@each $size, $config in get('sizes') {
	$marginTop: map-get($config, 'marginTop');
	$marginW: map-get($config, 'marginW');
	// if this is the last entry, only use min-width
	$rule: '(min-width: #{$previous}px) and (max-width: #{$size}px)';
	@if $size == 'max' {
		$rule: '(min-width: #{$previous}px)';
	}
	@media #{$rule} {
		.grid-container {
			padding: 0px #{$marginW}px 256px #{$marginW}px;
		}
	}
	$previous: $size;
}
</style>
