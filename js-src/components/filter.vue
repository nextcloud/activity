<template>
	<li :class="{active: isActive}">
		<a @click="selectFilter">
			<img v-if="icon" :alt="name" :src="icon">
			<span :class="{ 'no-icon': !icon }">{{name}}</span>
		</a>
	</li>
</template>

<script>
	export default {
		name: 'activity-filter',

		props: [
			'id',
			'name',
			'icon',
			'priority'
		],

		computed: {
			isActive: function () {
				return this.id === this.$parent.filter;
			}
		},

		methods: {
			selectFilter: function () {
				this.$parent.setActiveFilter(this.id);
			}
		}
	}
</script>
