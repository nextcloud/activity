<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<h2 class="activity-group__heading" :title="fullDate">
		{{ dateText }}
	</h2>
	<ul class="activity-group__list">
		<template v-for="(item, index) in threadedActivities" :key="itemKey(item, index)">
			<CommentThread
				v-if="item.thread"
				:activities="item.activities" />
			<ActivityComponent
				v-else
				:activity="item.activity"
				:show-previews="true" />
		</template>
	</ul>
</template>

<script setup lang="ts">
import type ActivityModel from '../models/ActivityModel.ts'

import { translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { computed } from 'vue'
import ActivityComponent from './ActivityComponent.vue'
import CommentThread from './CommentThread.vue'

const props = defineProps<{
	activities: ActivityModel[]
}>()

/**
 * Bucket consecutive comment activities on the same target object into
 * threads.  Two activities thread together when:
 *   - both have type === 'comments',
 *   - they target the same (objectType, objectId) tuple, and
 *   - they are consecutive in the input list (the list is already sorted
 *     newest-first per day, so neighbouring entries are temporally close).
 *
 * A single comment doesn't form a thread — it falls through to the regular
 * ActivityComponent so the row still gets a preview and is fully readable.
 */
type ThreadItem =
	| { thread: true; activities: ActivityModel[] }
	| { thread: false; activity: ActivityModel }

const threadedActivities = computed<ThreadItem[]>(() => {
	const items: ThreadItem[] = []
	let buffer: ActivityModel[] = []

	const flushBuffer = () => {
		if (buffer.length === 0) return
		if (buffer.length >= 2) {
			items.push({ thread: true, activities: buffer })
		} else {
			items.push({ thread: false, activity: buffer[0] })
		}
		buffer = []
	}

	for (const a of props.activities) {
		const isComment = a.type === 'comments'
		if (!isComment) {
			flushBuffer()
			items.push({ thread: false, activity: a })
			continue
		}
		const head = buffer[0]
		const sameTarget = head
			&& head.objectType === a.objectType
			&& head.objectId === a.objectId
		if (head && !sameTarget) {
			flushBuffer()
		}
		buffer.push(a)
	}
	flushBuffer()
	return items
})

function itemKey(item: ThreadItem, index: number): string {
	if (item.thread) {
		return 'thread-' + item.activities[0].id + '-' + item.activities.length
	}
	return 'item-' + item.activity.id + '-' + index
}

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
