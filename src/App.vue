<template>
	<div id="content" class="app-vueexample">
		<div id="app-navigation">
			<app-navigation :menu="menu">
				<template slot="settings-content">
					<input type="checkbox" :checked="!!feedLink" @click="toggleFeedLink" id="enable_rss" class="checkbox" />
					<label for="enable_rss">{{ t('activity', 'Enable RSS feed') }}</label>

					<span id="rssurl" :class="{ hidden: !feedLink }">
					<label for="feed-link" class="hidden-visually">{{ t('activity', 'RSS feed') }}</label>
					<input id="feed-link" class="feed-link" type="text" readonly="readonly" v-clipboard:copy="feedLink" v-clipboard:success="onCopy" :value="feedLink" />
					<a class="icon-clippy" v-clipboard:copy="feedLink" v-clipboard:success="onCopy"></a>
					</span>
				</template>
			</app-navigation>
		</div>
		<div id="app-content">
			htrhtr
		</div>
	</div>
</template>

<script>
import {
	AppNavigation
} from 'nextcloud-vue'
import axios from 'axios'

export default {
	name: 'App',
	components: {
		AppNavigation
	},
	data: function() {
		return {
			feedLink: '',
			filters: []
		}
	},

	computed: {
		menu () {
			return {
				items: this.filters,
				loading: false
			};
		}
	},
	methods: {
		loadFilters () {
			axios
				.get(OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filters', { headers: { requesttoken: OC.requestToken } })
				.then(response => {
					if (!_.isUndefined(response.data) && !_.isUndefined(response.data.ocs) && !_.isUndefined(response.data.ocs.data) && _.isArray(response.data.ocs.data)) {
						response.data.ocs.data.forEach(function(filter) {
							this.filters.push({
								id: filter.id,
								href: filter.id === 'all' ? OC.generateUrl('apps/activity') : OC.generateUrl('apps/activity') + '?filter=' + filter.id,
								// router: OC.generateUrl('apps/activity/' + filter.id),
								iconUrl: filter.icon,
								text: filter.name,
								classes: (/*this.$route.params.filter || */'all') === filter.id ? 'active' : ''
							});
						}.bind(this));
					} else {
						console.debug("data.ocs.data is undefined or not an array");
					}
				})
				.catch(() => {
					OC.Notification.showTemporary(t('activity', 'Failed to load activity filters'));
				});
		},

		loadFeedLink () {
			axios
				.get(OC.linkToOCS('apps/activity/api/v2', 2) + 'feed', { headers: { requesttoken: OC.requestToken } })
				.then(response => {
					console.log(response.data.ocs.data);
					if (!_.isUndefined(response.data) && !_.isUndefined(response.data.ocs) && !_.isUndefined(response.data.ocs.data)) {
						this.feedLink = response.data.ocs.data.feedLink;
					} else {
						console.debug("data.ocs.data is undefined or not an array");
					}
				});
		},

		toggleFeedLink () {
			axios
				.post(OC.linkToOCS('apps/activity/api/v2', 2) + 'feed', {
						enable: !this.feedLink
					}, { headers: { requesttoken: OC.requestToken } })
				.then(response => {
					if (!_.isUndefined(response.data) && !_.isUndefined(response.data.ocs) && !_.isUndefined(response.data.ocs.data)) {
						this.feedLink = response.data.ocs.data.feedLink;
					} else {
						console.debug("data.ocs.data is undefined or not an array");
					}
				});
		},

		onCopy: function (e) {
			OC.Notification.showTemporary(t('activity', 'Feed link copied!'))
		}
	},
	mounted () {
		this.loadFilters();
		this.loadFeedLink();
	}
}
</script>
