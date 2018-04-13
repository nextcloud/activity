<template>
	<div id="app-content" class="app-activity" role="main">
		<app-navigation :menu="menu">
			<template slot="settings-content">
				<input type="checkbox" :checked="feedLink" @click="toggleFeedSetting" id="enable_rss" class="checkbox" />
				<label for="enable_rss">{{ t('activity', 'Enable RSS feed') }}</label>

				<span id="rssurl" :class="{hidden: !feedLink}">
					<label for="feed-link" class="hidden-visually">{{ t('activity', 'RSS feed') }}</label>
					<input id="feed-link" class="feed-link" type="text" readonly="readonly" :value="feedLink" />
					<!--a class="icon-clippy" data-clipboard-target="#rssurl input"></a-->
				</span>
			</template>
		</app-navigation>

		<router-view></router-view>
	</div>
</template>

<script>
import appNavigation from './components/appNavigation';
import api from './store/api';

export default {
	name: 'App',

	data: function () {
		return {
			/** @type {string} */
			feedLink: '',

			/** @type {Object} */
			menu: {
				id: 'filters',
				loading: true,
				items: []
			}
		};
	},

	methods: {

		/**
		 * Navigation data is only loaded once on mount
		 */
		loadFilters: function () {
			api.get(OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filters').then((response) => {
				let menuItems = [];
				response.data.ocs.data.forEach((filter) => {
					menuItems.push({
						id: filter.id,
						text: filter.name,
						iconUrl: filter.icon
					})
				})
				this.menu.items = menuItems;
				this.menu.loading = false;
			});
		},

		/**
		 * Enable/disable the RSS Feed
		 */
		toggleFeedSetting: function () {
			api.post(OC.generateUrl('/apps/activity/settings/feed'), {
					enable: !this.feedLink
				})
				.then((response) => { this.feedLink = response.data.data.rsslink; });
		}
	},
	components: {
		appNavigation
	},

	beforeMount: function () {
		// importing server data into the store
		const appContentElmt = document.getElementById('app-content');

		if (appContentElmt !== null) {
			// this.$store.commit('setActiveFilter', appContentElmt.dataset['activity-filter']);
			// this.$store.commit('setFeedLink', appContentElmt.dataset['feed-link']);
		}
	},

	mounted: function () {
		this.loadFilters();

		// FIXME Clipboard is missing…

		// this.loadActivities();
	}
}
</script>
