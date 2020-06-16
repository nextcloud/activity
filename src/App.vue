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

export default {
	name: 'App',
	components: {
		AppContent,
		AppNavigation,
		AppNavigationItem,
	},
	data() {
		return {
			filters: [
				{
					id: 'all',
					name: 'All activities',
				},
				{
					id: 'yours',
					name: 'Your activity',
				},
			],
		}
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
