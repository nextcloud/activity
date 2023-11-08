<template>
	<Fragment>
		<h3 class="activity-group__heading" :title="fullDate">
			{{ dateText }}
		</h3>
		<ul>
			<Activity v-for="activity in activities"
				:key="activity.id"
				:activity="activity"
				:show-previews="true" />
		</ul>
	</Fragment>
</template>

<script setup lang="ts">
import type ActivityModel from '../models/ActivityModel'

import moment from '@nextcloud/moment'
import Activity from './Activity.vue'

import { translate as t } from '@nextcloud/l10n'
import { Fragment } from 'vue-frag'
import { computed } from 'vue'

const props = defineProps<{
	activities: ActivityModel[]
}>()

/**
 * Title to show for the date either Today, Yesterday or the full date
 */
const dateText = computed(() => {
	const today = moment()
	const yesterday = moment().subtract(1, 'day')
	const first = moment(props.activities[0].datetime)

	if (first.isSame(today, 'day')) {
		return t('activity', 'Today')
	} else if (first.isSame(yesterday, 'day')) {
		return t('activity', 'Yesterday')
	}
	return first.format('LL')
})

/**
 * If the heading is yesterday or today, show the full date as title, otherwise no title is shown
 */
const fullDate = computed(() => {
	const formatted = moment(props.activities[0].datetime).format('LL')
	return formatted !== dateText.value ? formatted : undefined
})
</script>

<style scoped lang="scss">
.activity-group {
	&__heading {
		font-weight: bold;
		font-size: 20px;
		margin-block: 30px 12px;
		line-height: 30px;
	}
}
</style>
