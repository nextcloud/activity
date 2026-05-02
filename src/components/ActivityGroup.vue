<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<section class="activity-group">
		<h2 class="activity-group__heading" :title="fullDate">
			{{ dateText }}
		</h2>
		<ul class="activity-group__list">
			<template v-for="(item, index) in groupedActivities" :key="itemKey(item, index)">
				<CommentThread
					v-if="item.kind === 'thread'"
					:activities="item.activities" />
				<ActivityBurst
					v-else-if="item.kind === 'burst'"
					:activities="item.activities" />
				<ActivityComponent
					v-else
					:activity="item.activity"
					:show-previews="true"
					:fresh="freshIds?.has(item.activity.id) ?? false" />
			</template>
		</ul>
	</section>
</template>

<script setup lang="ts">
import type ActivityModel from '../models/ActivityModel.ts'

import { translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { computed } from 'vue'
import ActivityBurst from './ActivityBurst.vue'
import ActivityComponent from './ActivityComponent.vue'
import CommentThread from './CommentThread.vue'

const props = defineProps<{
	activities: ActivityModel[]
	freshIds?: Set<number>
}>()

/**
 * Bucket consecutive activities into one of three render kinds:
 *
 *   - 'thread': ≥2 consecutive comment activities targeting the same
 *     (objectType, objectId) tuple.  Rendered by CommentThread.
 *
 *   - 'burst': ≥3 consecutive non-comment activities from the same actor
 *     with the same `type`.  Captures things like "user uploaded 7
 *     files" or "user changed 5 files" — bursts of identical events
 *     that would otherwise dominate the feed.  Rendered by
 *     ActivityBurst (collapsed by default, expand to see each row).
 *
 *   - 'item': everything else, rendered as an individual row.
 *
 * The input list is sorted newest-first within a day, so neighbouring
 * entries are also temporally close.
 */
type GroupItem =
	| { kind: 'thread'; activities: ActivityModel[] }
	| { kind: 'burst'; activities: ActivityModel[] }
	| { kind: 'item'; activity: ActivityModel }

const BURST_THRESHOLD = 3

const groupedActivities = computed<GroupItem[]>(() => {
	const items: GroupItem[] = []
	// 'thread' buffers consecutive comments on one target object.
	// 'burst' buffers consecutive same-(actor, type) non-comment events.
	// At most one buffer is active at a time; switching types flushes
	// the other.
	type BufferKind = 'thread' | 'burst' | null
	let bufferKind: BufferKind = null
	let buffer: ActivityModel[] = []

	const flushBuffer = () => {
		if (buffer.length === 0) {
			bufferKind = null
			return
		}
		if (bufferKind === 'thread') {
			if (buffer.length >= 2) {
				items.push({ kind: 'thread', activities: buffer })
			} else {
				items.push({ kind: 'item', activity: buffer[0] })
			}
		} else if (bufferKind === 'burst') {
			if (buffer.length >= BURST_THRESHOLD) {
				items.push({ kind: 'burst', activities: buffer })
			} else {
				for (const a of buffer) {
					items.push({ kind: 'item', activity: a })
				}
			}
		}
		buffer = []
		bufferKind = null
	}

	for (const a of props.activities) {
		const isComment = a.type === 'comments'

		if (isComment) {
			// Try to extend an existing thread, or start a new one
			if (bufferKind === 'thread' && buffer.length > 0) {
				const head = buffer[0]
				const sameTarget = head.objectType === a.objectType
					&& head.objectId === a.objectId
				if (sameTarget) {
					buffer.push(a)
					continue
				}
			}
			flushBuffer()
			bufferKind = 'thread'
			buffer.push(a)
			continue
		}

		// Non-comment: try to extend an existing burst (same actor +
		// same type), or start a new one.  System-actor events still
		// burst together — multiple "system" events of the same type
		// are typically one logical operation.
		if (bufferKind === 'burst' && buffer.length > 0) {
			const head = buffer[0]
			if (head.user === a.user && head.type === a.type) {
				buffer.push(a)
				continue
			}
		}
		flushBuffer()
		bufferKind = 'burst'
		buffer.push(a)
	}
	flushBuffer()
	return items
})

function itemKey(item: GroupItem, index: number): string {
	if (item.kind === 'thread' || item.kind === 'burst') {
		return item.kind + '-' + item.activities[0].id + '-' + item.activities.length
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
		// Sticky so the day label stays pinned to the top of the
		// scroll container while the user scrolls through that day's
		// rows.  z-index keeps it above the rail and marker dots.
		// Background must be opaque so rows scrolling underneath don't
		// bleed through; a small bottom shadow gives the heading a
		// "shelf" cue when content scrolls behind it.
		position: sticky;
		top: 0;
		z-index: 5;
		background: var(--color-main-background);
		line-height: 1.5;
		// Replaced margin-block: 30px 12px — sticky elements don't
		// collapse top-margins against the parent, and we need the
		// content area to extend full-bleed to the edges so the
		// background masks scrolling rows underneath.
		padding-block: 12px 8px;
		margin-block: 18px 4px;
		font-size: 14px;
		font-weight: 600;
		color: var(--color-text-maxcontrast);
		text-transform: uppercase;
		letter-spacing: 0.04em;
		display: flex;
		align-items: center;
		gap: 12px;
		// Soft fade-out at the bottom of the heading so rows
		// scrolling behind it don't appear to slam into a hard edge.
		box-shadow: 0 6px 6px -6px var(--color-box-shadow, rgba(0, 0, 0, 0.12));

		// Subtle hairline that extends from the heading text to the right
		// edge of the column — gives each day a visual "shelf"
		&::after {
			content: '';
			flex: 1;
			height: 1px;
			background: linear-gradient(to right, var(--color-border) 0%, transparent 100%);
		}

		&:first-of-type {
			// Already padding from h1
			margin-block-start: 0;
		}
	}

	// Continuous vertical rail that connects the per-row marker dots
	// down a single day.  The rail terminates at the top and bottom
	// of the <ul>, so it doesn't bleed into the gap before the next
	// day's heading.  The rail x-position must match the dot centre
	// in GenericActivity / ActivityBurst (left: 8px + half of 14px =
	// 15px; rail at left: 14px width: 2px).
	&__list {
		position: relative;
		list-style: none;
		padding: 0;
		margin: 0;

		&::before {
			content: '';
			position: absolute;
			left: 14px;
			top: 4px;
			bottom: 4px;
			width: 2px;
			background: var(--color-border);
			pointer-events: none;
		}
	}
}
</style>
