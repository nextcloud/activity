<template>
	<div id="content" class="app-notestutorial">
		<AppNavigation>
			<ul>
				<AppNavigationItem v-for="filter in filters"
					:key="filter.id"
					:title="filter.name"
					:to="{name: 'stream', params: {filter: filter.id}}"
					:class="{active: false}" />
			</ul>
		</AppNavigation>
		<AppContent>
			<router-view />
		</AppContent>
	</div>
</template>

<script>
import AppContent from '@nextcloud/vue/dist/Components/AppContent'
import AppNavigation from '@nextcloud/vue/dist/Components/AppNavigation'
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import axios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'

export default {
	name: 'App',
	components: {
		AppContent,
		AppNavigation,
		AppNavigationItem,
	},

	data() {
		return {
			filters: [],
		}
	},

	mounted: function() {
		this.fetchFilters()
	},

	methods: {
		fetchFilters() {
			axios
				.get(generateOcsUrl('apps/activity/api/v2/activity/', 2) + 'filters')
				.then(response => {
					if (response.data !== undefined && response.data.ocs !== undefined && response.data.ocs.data !== undefined && Array.isArray(response.data.ocs.data)) {
						this.filters = response.data.ocs.data
					} else {
						console.debug('Could not properly parse activity filters')
					}
				})
		},
	},
}
</script>
<style scoped>
	#app-content > div {
		width: 100%;
		height: 100%;
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}

	input[type='text'] {
		width: 100%;
	}

	textarea {
		flex-grow: 1;
		width: 100%;
	}
</style>
