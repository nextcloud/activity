<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<li class="activity-burst" :class="`activity-burst--type-${typeFamily}`">
		<button
			class="activity-burst__toggle"
			type="button"
			:aria-expanded="expanded ? 'true' : 'false'"
			@click="expanded = !expanded">
			<NcAvatar
				v-if="hasActor"
				class="activity-burst__avatar"
				:user="firstActivity.user"
				:size="32"
				:disable-menu="true"
				:show-user-status="false" />
			<span v-else class="activity-burst__avatar activity-burst__avatar--system" aria-hidden="true" />
			<IconChevronRight :size="16" class="activity-burst__chevron" />
			<span class="activity-burst__summary">
				{{ summary }}
			</span>
			<span class="activity-burst__date">
				{{ relativeLatest }}
			</span>
		</button>
		<ul v-if="expanded" class="activity-burst__list">
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
import NcAvatar from '@nextcloud/vue/components/NcAvatar'
import ActivityComponent from './ActivityComponent.vue'

const props = defineProps<{
	activities: ActivityModel[]
}>()

const expanded = ref(false)

const firstActivity = computed(() => props.activities[0])

const hasActor = computed<boolean>(() => {
	const uid = firstActivity.value.user
	return typeof uid === 'string' && uid !== '' && uid !== 'system'
})

// Bucket the burst's type into the same visual families used by
// GenericActivity so the marker dot colour stays consistent between a
// burst and the individual rows it expands into.
const typeFamily = computed<string>(() => {
	const type = firstActivity.value.type
	if (type === 'file_created') return 'created'
	if (type === 'file_deleted') return 'deleted'
	if (type === 'file_changed' || type === 'file_restored') return 'changed'
	if (type === 'favorite') return 'favorite'
	if (type.startsWith('shared') || type.startsWith('share') || type.startsWith('unshare') || type.includes('_share')) return 'share'
	if (type === 'remote_share') return 'share'
	return 'neutral'
})

// Single-line burst summary.  Branches on type family so the verb
// matches what the individual rows would have said; unknown families
// fall through to a generic "%n events" so we never invent a verb for
// an activity type we don't recognise.
const summary = computed<string>(() => {
	const count = props.activities.length
	switch (typeFamily.value) {
		case 'created':
			return n('activity', '%n item created', '%n items created', count)
		case 'deleted':
			return n('activity', '%n item deleted', '%n items deleted', count)
		case 'changed':
			return n('activity', '%n item changed', '%n items changed', count)
		case 'favorite':
			return n('activity', '%n item favorited', '%n items favorited', count)
		case 'share':
			return n('activity', '%n share update', '%n share updates', count)
		default:
			return n('activity', '%n activity', '%n activities', count)
	}
})

const relativeLatest = computed<string>(() => {
	// activities are newest-first inside a day group, so [0] is the latest
	return moment(firstActivity.value.datetime).fromNow()
})
</script>

<style lang="scss" scoped>
.activity-burst {
	position: relative;
	margin-block: 4px;
	padding-inline-start: 32px;
	border-radius: var(--border-radius);

	// Marker dot anchored to the day-group's vertical rail.  Coloured by
	// type family so a burst reads at-a-glance the same way an individual
	// row does.
	&::before {
		content: '';
		position: absolute;
		left: 8px;
		top: 18px;
		width: 14px;
		height: 14px;
		border-radius: 50%;
		background: var(--rail-dot-color, var(--color-border-dark));
		// Light ring punches the dot through the underlying rail line so
		// the rail visually breaks at each marker instead of running
		// behind it.
		box-shadow: 0 0 0 3px var(--color-main-background);
		z-index: 1;
	}

	&--type-created   { --rail-dot-color: var(--color-success, #46ba61); }
	&--type-deleted   { --rail-dot-color: var(--color-error,   #e9322d); }
	&--type-changed   { --rail-dot-color: var(--color-primary, #0082c9); }
	&--type-share     { --rail-dot-color: #8e44ad; }
	&--type-favorite  { --rail-dot-color: var(--color-warning, #f4a261); }
	&--type-neutral   { --rail-dot-color: var(--color-border-dark, #d0d0d0); }

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

	&__avatar {
		flex-shrink: 0;

		&--system {
			width: 32px;
			height: 32px;
			border-radius: 50%;
			background: var(--color-background-dark);
		}
	}

	&__chevron {
		flex-shrink: 0;
		color: var(--color-text-maxcontrast);
		transition: transform 150ms ease;
	}

	&[aria-expanded='true'] &__chevron,
	&__toggle[aria-expanded='true'] &__chevron {
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

		// Nested rows would otherwise paint their own marker dots at
		// an offset that doesn't align to the day-group's rail.  Hide
		// them so only the burst's own dot remains visible.
		:deep(.activity-entry::before) {
			display: none;
		}
	}
}
</style>
