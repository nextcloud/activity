<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<h2 class="activity-group__heading" :title="fullDate">
		{{ dateText }}
	</h2>
	<ul>
		<ActivityComponent
			v-for="activity in activities"
			:key="activity.id"
			:activity="activity"
			:show-previews="true" />
	</ul>
</template>

<script setup lang="ts">
import type ActivityModel from '../models/ActivityModel.ts'

import { translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { computed } from 'vue'
import ActivityComponent from './ActivityComponent.vue'

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
		line-height: 1.5;
		margin-block: 30px 12px;

		&:first-of-type {
			// Already padding from h1
			margin-block-start: 0;
		}
	}
}
</style>
