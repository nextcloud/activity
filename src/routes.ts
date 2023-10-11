import ActivityFeed from './views/ActivityFeed.vue'

export const routes = [
	{
		path: '/',
		name: 'root',
	},
	{
		path: '/:view',
		component: ActivityFeed,
	},
]
