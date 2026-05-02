<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<li class="comment-thread" :class="{ 'comment-thread--expanded': expanded }">
		<button
			class="comment-thread__toggle"
			type="button"
			:aria-expanded="expanded ? 'true' : 'false'"
			@click="expanded = !expanded">
			<IconChevronRight :size="16" class="comment-thread__chevron" />
			<span class="comment-thread__summary">
				{{ summary }}
			</span>
			<span class="comment-thread__date">
				{{ relativeLatest }}
			</span>
		</button>
		<ul v-if="expanded" class="comment-thread__list">
			<ActivityComponent
				v-for="activity in activities"
				:key="activity.id"
				:activity="activity"
				:show-previews="false" />
		</ul>
	</li>
</template>

<script setup lang="ts">
import type ActivityModel from '../models/ActivityModel.ts'

import { translate as t, translatePlural as n } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { computed, ref } from 'vue'
import IconChevronRight from 'vue-material-design-icons/ChevronRight.vue'
import ActivityComponent from './ActivityComponent.vue'

const props = defineProps<{
	activities: ActivityModel[]
}>()

const expanded = ref(false)

/**
 * Pick a sensible label for the target file/folder/object the thread is
 * about.  Falls back through objectName, the first preview's filename, and
 * finally a generic "this item" so we always render something readable.
 */
const targetName = computed<string>(() => {
	const first = props.activities[0]
	if (first.objectName) return first.objectName
	const preview = first.previews?.[0]
	if (preview?.filename) return preview.filename
	return t('activity', 'this item')
})

const summary = computed<string>(() => {
	const count = props.activities.length
	return n(
		'activity',
		'%n comment on {target}',
		'%n comments on {target}',
		count,
		{ target: targetName.value },
	)
})

const relativeLatest = computed<string>(() => {
	// activities are newest-first inside a group, so [0] is the latest
	return moment(props.activities[0].datetime).fromNow()
})
</script>

<style lang="scss" scoped>
.comment-thread {
	position: relative;
	border-radius: var(--border-radius);
	margin-block: 4px;
	padding-inline-start: 32px;

	// Marker dot anchored to the day-group's vertical rail.  Uses the
	// "comment" type colour to stay consistent with individual comment
	// rows.  See ActivityGroup.scss / GenericActivity.scss for rail
	// geometry — left/top values must match.
	&::before {
		content: '';
		position: absolute;
		left: 8px;
		top: 14px;
		width: 14px;
		height: 14px;
		border-radius: 50%;
		background: #1abc9c;
		box-shadow: 0 0 0 3px var(--color-main-background);
		z-index: 1;
	}

	&__toggle {
		display: flex;
		align-items: center;
		gap: 8px;
		width: 100%;
		padding: 6px 10px;
		background: transparent;
		border: none;
		border-radius: var(--border-radius);
		text-align: start;
		color: var(--color-main-text);
		cursor: pointer;
		font: inherit;

		&:hover, &:focus-visible {
			background: var(--color-background-hover);
		}
	}

	&__chevron {
		flex-shrink: 0;
		transition: transform 150ms ease;
	}

	&--expanded &__chevron {
		transform: rotate(90deg);
	}

	&__summary {
		flex-grow: 1;
		min-width: 0;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		font-weight: 500;
	}

	&__date {
		flex-shrink: 0;
		font-size: 13px;
		color: var(--color-text-lighter);
	}

	&__list {
		list-style: none;
		padding: 4px 0 8px 0;
		margin: 0;

		// Nested rows already sit visually under the parent thread's
		// dot; their own marker dots would land off the rail and read
		// as noise.  Hide them here, but keep the parent thread's dot.
		:deep(.activity-entry::before) {
			display: none;
		}
	}
}
</style>
