import type { RouteConfig } from 'vue-router'
import ActivityFeed from './views/ActivityFeed.vue'

export const routes = [
	{
		path: '/',
		name: 'root',
		redirect: { path: '/all' },
	},
	{
		path: '/:view',
		component: ActivityFeed,
		props: true,
	},
] as RouteConfig[]
