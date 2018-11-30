<template>
	<div id="content" class="app-activity">
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
		<router-view></router-view>
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
	data () {
		return {
			feedLink: ''
		}
	},

	computed: {
		filters() {
			return this.$store.getters.getFilters;
		},
		menu () {
			return {
				items: this.filters,
				loading: this.loading
			};
		}
	},
	methods: {
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

		onCopy: function () {
			OC.Notification.showTemporary(t('activity', 'Feed link copied!'))
		},

		goBack () {
			window.history.length > 1
				? this.$router.go(-1)
				: this.$router.push('/')
		}
	},
	beforeMount () {
		this.$store.dispatch('fetchFilters');
		this.loadFeedLink();
	}
}
</script>
