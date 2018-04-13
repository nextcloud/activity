<template>
	<div id="content" class="app-activity" role="main">
		<div id="app-navigation">

		  <div v-if="loadingFilters" class="icon-loading"></div>

			<ul v-if="filters.length">
				<activity-filter v-for="f in filters" v-bind="f" :key="f.id"></activity-filter>
			</ul>

			<div id="app-settings" :class="{opened: settingsVisible}">
				<div id="app-settings-header">
					<button class="settings-button" @click="toggleSettings">{{ t('activity', 'Settings') }}</button>
				</div>

				<div id="app-settings-content">
					<input type="checkbox" :checked="feedLink" @click="toggleFeedSetting" id="enable_rss" class="checkbox" />
					<label for="enable_rss">{{ t('activity', 'Enable RSS feed') }}</label>

					<span id="rssurl" :class="{hidden: !feedLink}">
						<label for="feed-link" class="hidden-visually">{{ t('activity', 'RSS feed') }}</label>
						<input id="feed-link" class="feed-link" type="text" readonly="readonly" :value="feedLink" />
						<a class="icon-clippy" data-clipboard-target="#rssurl input"></a>
					</span>
				</div>
			</div>
		</div>

		<div id="app-content" @scroll="onScroll">
			<div id="emptycontent" v-if="!loading && !activities.length">
				<div class="icon-activity"></div>
				<h2>{{ t('activity', 'No activity yet') }}</h2>
				<p>{{ t('activity', 'This stream will show events like additions, changes & shares') }}</p>
			</div>

			<div id="container">
				<activity v-for="(a, index) in activities" v-bind="a" :index="index" :key="a.activity_id" :activities="activities"></activity>
			</div>

			<div v-if="loading" id="loading_activities" class="icon-loading"></div>

			<div v-if="reachedEnd && activities.length" id="no_more_activities">{{ t('activity', 'No more events to load') }}</div>
		</div>
	</div>
</template>

<script>
	import api from '../store/api';

	export default {
		name: "root",

		el: '#content',

		data: function() {
			return {
				/** @type {string} */
				filter: 'all',

				/** @type {array} */
				filters: [],

				/** @type {boolean} */
				loading: true,

				/** @type {boolean} */
				loadingFilters: true,

				/** @type {boolean} */
				settingsVisible: false,

				/** @type {boolean} */
				reachedEnd: false,

				/** @type {string} */
				feedLink: '',

				/** @type {int} */
				lastGivenId: 0,

				/** @type {int} */
				firstKnownId: 0,

				/** @type {array} */
				activities: []
			};
		},

		computed: {
		},

	  watch: {
		},

		methods: {
			/**
			 * Show/hide the settings in the app navigation
			 */
			toggleSettings: function () {
				this.settingsVisible = !this.settingsVisible;
			},

			/**
			 * Enable/disable the RSS Feed
			 */
			toggleFeedSetting: function () {
				api.post(OC.generateUrl('/apps/activity/settings/feed'), {
						enable: !this.feedLink
					})
					.then((response) => { this.feedLink = response.data.data.rsslink; });
			},

			onScroll: function () {
				if (!this.reachedEnd && this.ignoreScroll <= 0
				) { // TODO && this.$content.scrollTop() + this.$content.height() > this.$container.height() - 100) {
					this.loadActivities(false, false);
				}
			},

			/**
			 * Navigation data is only loaded once on mount
			 */
			loadFilters: function () {
				api.get(OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filters').then((response) => {
					this.filters = response.data.ocs.data;
					this.loadingFilters = false;
				});
			},

			/**
			 * Set an active filter
			 * @param {string} filter
			 */
			setActiveFilter: function (filter) {
				if (this.filter === filter) {
					return;
				}

				this.filter = filter;
				this.activities = [];
				this.lastGivenId = 0;
				this.firstKnownId = 0;
				this.ignoreScroll = 0;
				this.loadActivities();
			},

			/**
			 * Navigation data is only loaded once on mount
			 * @param {boolean} lookAHead
			 * @param {boolean} isReset
			 */
			loadActivities: function (lookAHead, isReset) {
				this.ignoreScroll = 1;
				var url = OC.linkToOCS('apps/activity/api/v2/activity', 2) + this.filter + '?previews=true';
				if (lookAHead) {
					url += '&since=' + this.firstKnownId;
					url += '&sort=asc';
				} else {
					url += '&since=' + this.lastGivenId;
				}

				api.get(url).then((response) => {
					this._saveHeaders(response.headers, isReset, lookAHead);
					this.loading = false;
					this.ignoreScroll--;

					if (response.data.ocs.data) {
						this.activities = this.activities.concat(response.data.ocs.data);
					}
				}).catch((err) => {
					this._saveHeaders(err.response.headers, isReset, lookAHead);
					this.loading = false;
					this.ignoreScroll--;

					if (err.response.status === 304 && !lookAHead) {
						this.reachedEnd = true;
					}
				});
			},

			/**
			 * Read the X-Activity-First-Known and X-Activity-Last-Given headers
			 * @param {string[]} headers
			 * @param {boolean} reset
			 * @param {boolean} lookAHead
			 */
			_saveHeaders: function(headers, reset, lookAHead) {
				Object.keys(headers).forEach((header) => {
					if (reset && header === 'x-activity-first-known') {
						this.firstKnownId = parseInt(headers[header].trim(), 10);
					} else if (header === 'x-activity-last-given') {
						if (lookAHead) {
							this.firstKnownId = parseInt(headers[header].trim(), 10);
						} else {
							this.lastGivenId = parseInt(headers[header].trim(), 10);
						}
					}
				});
			}
		},

		components: {
			'activity': require('./activity.vue'),
			'activity-filter': require('./filter.vue')
		},

		mounted: function () {
			this.loadFilters();

			this.loadActivities();
		}
	}
</script>
