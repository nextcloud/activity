<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<section class="activity-group">
		<h2 class="activity-group__heading" :title="fullDate">
			{{ dateText }}
		</h2>
		<ul>
			<ActivityComponent
				v-for="activity in activities"
				:key="activity.id"
				:activity="activity"
				:showPreviews="true" />
		</ul>
	</section>
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
	// Gap between groups, as padding inside the <section> so the sticky heading stays
	// pinned across it and the next date takes over as its group reaches the top
	padding-block-end: 24px;

	&__heading {
		position: sticky;
		top: 0;
		z-index: 1;
		margin-block: 0;
		// Bottom padding gives the background fade room to complete below the text
		padding-block: 8px 20px;
		// Match the settings-section__name heading size
		font-size: 20px;
		line-height: var(--default-clickable-area);
		// Fade out so entries dissolve as they scroll under the heading
		background: linear-gradient(to bottom, var(--color-main-background) 44%, transparent);

		// Indent the heading to clear the app-navigation toggle, but only by the amount
		// the centring gutter ((100cqi - column width) / 2) doesn't already cover.
		// Clamped to 0 so wide layouts keep it aligned with the entries.
		padding-inline-start: calc(max(
			0px,
			var(--app-navigation-padding) + var(--default-clickable-area)
				- var(--default-grid-baseline)
				- max(0px, (100cqi - var(--activity-feed-max-width)) / 2)
		));
	}
}
</style>
