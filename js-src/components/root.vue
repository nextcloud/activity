<template>
	<div id="app-content">
		<div id="emptycontent" v-if="!loading && !activities.length">
			<div class="icon-activity"></div>
			<h2>{{ t('activity', 'No activity yet') }}</h2>
			<p>{{ t('activity', 'This stream will show events like additions, changes & shares') }}</p>
		</div>

		<div id="container">
			<activity v-for="a in activities" v-bind="a" :key="a.activity_id"></activity>
		</div>

		<div v-if="loading" id="loading_activities" class="icon-loading"></div>

		<div id="no_more_activities" class="hidden">{{ t('activity', 'No more events to load') }}</div>
	</div>
</template>

<script>
	export default {
		name: "root",

		el: '#app-content',

		data: function () {
			return {
				/** @type {string} */
				filter: 'all',

				/** @type {boolean} */
				loading: true,

				/** @type {array} */
				activities: []
			};
		},

		computed: {
		},

		methods: {
		},

		components: {
			'activity': require('./activity.vue')
		},

		mounted: function () {
			//this._$icon = $(this.$refs.icon);
		}
	}
</script>
