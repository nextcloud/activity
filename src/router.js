import Vue from 'vue'
import Router from 'vue-router'
import { generateUrl } from '@nextcloud/router'

const Stream = () => import('./views/FilteredStream')

Vue.use(Router)

export default new Router({
	base: generateUrl('/apps/activity/'),
	linkActiveClass: 'active',
	routes: [
		{
			path: '/?filter=:filter',
			name: 'stream',
			component: Stream,
		},
	],
})
