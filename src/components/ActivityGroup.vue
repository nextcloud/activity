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
	// Separate consecutive groups. Inside the <section> (not a margin between them)
	// so the date stays pinned across the gap and the next date docks right as the
	// group ends, instead of the push feeling early.
	padding-block-end: 24px;

	&__heading {
		position: sticky;
		top: 0;
		z-index: 1;
		// Match the line box to the navigation toggle so the date text lines up with
		// it vertically (centred in a clickable-area-tall row), with no extra
		// whitespace above. Sticking within the per-group <section> makes each new
		// date push the previous one up and out of the way.
		margin-block: 0;
		// Bottom padding only gives the fade more room to complete; because the
		// heading sticks within its <section>, it does not affect when the push starts
		padding-block: 8px 20px;
		// Match the settings-section__name heading size
		font-size: 20px;
		line-height: var(--default-clickable-area);
		// Solid behind the text, then a long, gentle fade to transparent (onset kept
		// at ~32px from the top) so entries dissolve out gradually as they scroll under
		background: linear-gradient(to bottom, var(--color-main-background) 44%, transparent);

		// Indent the heading to clear the app navigation toggle, eased in by the content
		// width. It is the toggle clearance (--app-navigation-padding + clickable-area),
		// less the space the content already has on its left: the centring gutter
		// ((100cqi - column width) / 2, where 100cqi is the content-area width and the
		// column width is --activity-feed-max-width) and its 12px padding. A little
		// breathing room (+8px) minus that 12px padding nets to one negative grid
		// baseline (-4px). Clamped to 0, so it never affects alignment with the entries
		// once the gutter clears the toggle.
		padding-inline-start: calc(max(
			0px,
			var(--app-navigation-padding, 8px) + var(--default-clickable-area)
				- var(--default-grid-baseline, 4px)
				- max(0px, (100cqi - var(--activity-feed-max-width)) / 2)
		));
	}
}
</style>
