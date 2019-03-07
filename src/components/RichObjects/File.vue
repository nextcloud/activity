<template>
	<a class="filename has-tooltip" :href="link" :title="title">vregergregerg</a>
</template>

<script>
	export default {
		name: 'rich-object-file',

		props: [
			'id',
			'name',
			'path',
			'link'
		],

		methods: {
		}
	}
</script>
