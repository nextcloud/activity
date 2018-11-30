<template>
	<div id="app-content">
		<virtual-list :size="40" :remain="8" v-if="activities.length > 0">
			<activity v-for="a in activities" v-bind="a" :key="a.activity_id"></activity>
		</virtual-list>

		<div class="emptycontent" :class="{ hidden: !!activities.length }">
			<img :src="filterIcon">
			<h2>{{ t('activity', 'No activity yet') }}</h2>
			<p>{{emptyContentMessage}}</p>
		</div>
	</div>
</template>

<script>
import Activity from '../components/Activity.vue'
import VirtualScrollList from 'vue-virtual-scroll-list'

export default {
	name: 'ActivityFilter',
	computed: {
		activities () {
			return this.$store.getters.getActivities;
		},
		filterIcon () {
			return this.$store.getters.getFilterIcon(this.$route.params.filter);
		},
		emptyContentMessage () {
			if (this.$route.params.filter === 'all') {
				return t('activity', 'This stream will show events like additions, changes & shares')
			}
			return t('activity', 'There are no events for this filter');
		}
	},
	components: { activity: Activity, 'virtual-list': VirtualScrollList }
}
</script>
