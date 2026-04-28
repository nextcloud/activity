<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcAppContent class="insights">
		<h1 class="insights__heading">{{ t('activity', 'Insights') }}</h1>
		<p class="insights__subheading">
			{{ t('activity', 'A look at your recent activity, computed from the {n} most-recent events.', { n: sampleSize }) }}
		</p>

		<NcLoadingIcon v-if="loading" :size="32" class="insights__loading" />

		<template v-else>
			<section class="insights-card">
				<h2>{{ t('activity', 'Last 7 days') }}</h2>
				<div class="insights-card__sparkline-wrapper">
					<svg
						class="insights-card__sparkline"
						:viewBox="`0 0 ${sparklineWidth} ${sparklineHeight}`"
						preserveAspectRatio="none"
						aria-hidden="true">
						<polyline
							:points="sparklinePoints"
							fill="none"
							stroke="var(--color-primary-element)"
							stroke-width="2" />
						<polygon
							:points="sparklineFillPoints"
							:fill="'var(--color-primary-element)'"
							opacity="0.15" />
					</svg>
					<ul class="insights-card__day-labels">
						<li v-for="bucket in last7Days" :key="bucket.iso">
							<span class="insights-card__day-count">{{ bucket.count }}</span>
							<span class="insights-card__day-label">{{ bucket.label }}</span>
						</li>
					</ul>
				</div>
			</section>

			<div class="insights__row">
				<section class="insights-card">
					<h2>{{ t('activity', 'Top collaborators') }}</h2>
					<ol v-if="topCollaborators.length > 0" class="insights-card__list">
						<li v-for="entry in topCollaborators" :key="entry.user">
							<NcAvatar :user="entry.user" :size="24" :disable-menu="true" />
							<span class="insights-card__list-label">{{ entry.user }}</span>
							<span class="insights-card__list-count">{{ entry.count }}</span>
						</li>
					</ol>
					<p v-else class="insights-card__empty">{{ t('activity', 'No collaborator data yet.') }}</p>
				</section>

				<section class="insights-card">
					<h2>{{ t('activity', 'Most-touched files') }}</h2>
					<ol v-if="topFiles.length > 0" class="insights-card__list">
						<li v-for="entry in topFiles" :key="entry.name">
							<span class="insights-card__list-label">{{ entry.name }}</span>
							<span class="insights-card__list-count">{{ entry.count }}</span>
						</li>
					</ol>
					<p v-else class="insights-card__empty">{{ t('activity', 'No file data yet.') }}</p>
				</section>
			</div>

			<section class="insights-card">
				<h2>{{ t('activity', 'Event types') }}</h2>
				<ul class="insights-card__bars">
					<li v-for="entry in typeBreakdown" :key="entry.family" :class="`type-${entry.family}`">
						<span class="insights-card__bar-label">{{ familyLabel(entry.family) }}</span>
						<span class="insights-card__bar-track">
							<span
								class="insights-card__bar-fill"
								:style="{ width: ((entry.count / typeMax) * 100) + '%' }" />
						</span>
						<span class="insights-card__bar-count">{{ entry.count }}</span>
					</li>
				</ul>
			</section>
		</template>
	</NcAppContent>
</template>

<script setup lang="ts">
import ncAxios from '@nextcloud/axios'
import { translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { generateOcsUrl } from '@nextcloud/router'
import { computed, onMounted, ref } from 'vue'
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import NcAvatar from '@nextcloud/vue/components/NcAvatar'
import NcLoadingIcon from '@nextcloud/vue/components/NcLoadingIcon'
import ActivityModel from '../models/ActivityModel.ts'
import type { IRawActivity } from '../models/types.ts'
import logger from '../utils/logger.ts'

const sampleSize = 200
const sparklineWidth = 280
const sparklineHeight = 60

const loading = ref(true)
const activities = ref<ActivityModel[]>([])

type Family = 'created' | 'changed' | 'deleted' | 'share' | 'comment' | 'favorite' | 'other'

function familyOf(type: string): Family {
	if (type === 'file_created') return 'created'
	if (type === 'file_deleted') return 'deleted'
	if (type === 'file_changed' || type === 'file_restored') return 'changed'
	if (type === 'favorite') return 'favorite'
	if (type === 'comments') return 'comment'
	if (type.startsWith('shared') || type.startsWith('share') || type.startsWith('unshare') || type === 'remote_share' || type.includes('_share')) return 'share'
	return 'other'
}

const familyLabels: Record<Family, () => string> = {
	created:  () => t('activity', 'Additions'),
	changed:  () => t('activity', 'Changes'),
	deleted:  () => t('activity', 'Deletions'),
	share:    () => t('activity', 'Shares'),
	comment:  () => t('activity', 'Comments'),
	favorite: () => t('activity', 'Favourites'),
	other:    () => t('activity', 'Other'),
}
function familyLabel(f: Family) { return familyLabels[f]() }

/**
 * 7-day rolling activity buckets, oldest day first, used to drive both the
 * sparkline polyline and the day-labels strip below it.
 */
const last7Days = computed(() => {
	const buckets: { iso: string, label: string, count: number }[] = []
	for (let i = 6; i >= 0; i--) {
		const day = moment().subtract(i, 'days').startOf('day')
		buckets.push({
			iso: day.format('YYYY-MM-DD'),
			label: day.format('dd'),
			count: 0,
		})
	}
	const indexByIso = new Map(buckets.map((b, i) => [b.iso, i]))
	for (const a of activities.value) {
		const iso = moment(a.datetime).format('YYYY-MM-DD')
		const idx = indexByIso.get(iso)
		if (idx !== undefined) buckets[idx].count++
	}
	return buckets
})

const sparklineMax = computed(() => Math.max(1, ...last7Days.value.map((b) => b.count)))

const sparklinePoints = computed(() => {
	const n = last7Days.value.length
	if (n === 0) return ''
	const stepX = sparklineWidth / (n - 1 || 1)
	return last7Days.value
		.map((b, i) => {
			const x = i * stepX
			const y = sparklineHeight - (b.count / sparklineMax.value) * (sparklineHeight - 4) - 2
			return `${x},${y}`
		})
		.join(' ')
})

const sparklineFillPoints = computed(() => {
	const line = sparklinePoints.value
	if (!line) return ''
	return `0,${sparklineHeight} ${line} ${sparklineWidth},${sparklineHeight}`
})

const topCollaborators = computed(() => {
	const counts = new Map<string, number>()
	for (const a of activities.value) {
		const user = a.user
		if (!user || user === 'system') continue
		counts.set(user, (counts.get(user) ?? 0) + 1)
	}
	return Array.from(counts.entries())
		.map(([user, count]) => ({ user, count }))
		.sort((a, b) => b.count - a.count)
		.slice(0, 5)
})

const topFiles = computed(() => {
	const counts = new Map<string, number>()
	for (const a of activities.value) {
		const name = a.objectName
		if (!name) continue
		counts.set(name, (counts.get(name) ?? 0) + 1)
	}
	return Array.from(counts.entries())
		.map(([name, count]) => ({ name, count }))
		.sort((a, b) => b.count - a.count)
		.slice(0, 5)
})

const typeBreakdown = computed(() => {
	const counts: Record<Family, number> = {
		created: 0, changed: 0, deleted: 0, share: 0, comment: 0, favorite: 0, other: 0,
	}
	for (const a of activities.value) counts[familyOf(a.type)]++
	return Object.entries(counts)
		.map(([family, count]) => ({ family: family as Family, count }))
		.sort((a, b) => b.count - a.count)
})

const typeMax = computed(() => Math.max(1, ...typeBreakdown.value.map((e) => e.count)))

onMounted(async () => {
	try {
		const response = await ncAxios.get(
			generateOcsUrl('apps/activity/api/v2/activity/all?format=json&previews=false&limit={limit}', { limit: sampleSize }),
		)
		activities.value = response.data.ocs.data.map((raw: IRawActivity) => new ActivityModel(raw))
	} catch (e) {
		logger.error('Failed to load activity sample for insights', e as Error)
	} finally {
		loading.value = false
	}
})
</script>

<style lang="scss" scoped>
.insights {
	padding: 16px 24px 32px;
	overflow-y: auto;

	&__heading {
		font-size: 24px;
		font-weight: bold;
		margin-bottom: 4px;
	}

	&__subheading {
		color: var(--color-text-maxcontrast);
		margin-bottom: 24px;
	}

	&__loading {
		margin-top: 48px;
	}

	&__row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 16px;

		@media (max-width: 720px) {
			grid-template-columns: 1fr;
		}
	}
}

.insights-card {
	border: 1px solid var(--color-border);
	border-radius: var(--border-radius-large);
	background: var(--color-main-background);
	padding: 16px 20px;
	margin-bottom: 16px;

	h2 {
		font-size: 16px;
		margin: 0 0 12px;
	}

	&__sparkline-wrapper {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	&__sparkline {
		width: 100%;
		height: 60px;
	}

	&__day-labels {
		list-style: none;
		display: grid;
		grid-template-columns: repeat(7, 1fr);
		padding: 0;
		margin: 0;
		text-align: center;
		font-size: 12px;
		color: var(--color-text-maxcontrast);
	}

	&__day-count {
		display: block;
		font-weight: bold;
		color: var(--color-main-text);
	}

	&__list {
		list-style: none;
		padding: 0;
		margin: 0;

		li {
			display: flex;
			align-items: center;
			gap: 8px;
			padding: 6px 0;
			border-bottom: 1px solid var(--color-border);

			&:last-child {
				border-bottom: none;
			}
		}
	}

	&__list-label {
		flex-grow: 1;
		min-width: 0;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	&__list-count {
		font-variant-numeric: tabular-nums;
		color: var(--color-text-maxcontrast);
	}

	&__empty {
		color: var(--color-text-maxcontrast);
		font-style: italic;
	}

	&__bars {
		list-style: none;
		padding: 0;
		margin: 0;
		display: flex;
		flex-direction: column;
		gap: 8px;

		li {
			display: grid;
			grid-template-columns: 100px 1fr 40px;
			align-items: center;
			gap: 8px;
		}
	}

	&__bar-label {
		font-size: 13px;
		color: var(--color-text-maxcontrast);
	}

	&__bar-track {
		display: block;
		height: 10px;
		background: var(--color-background-darker);
		border-radius: var(--border-radius);
		overflow: hidden;
	}

	&__bar-fill {
		display: block;
		height: 100%;
		background: var(--color-primary-element);
		transition: width 200ms ease;
	}

	&__bar-count {
		text-align: right;
		font-variant-numeric: tabular-nums;
	}

	li.type-created  .insights-card__bar-fill { background: var(--color-success, #46ba61); }
	li.type-deleted  .insights-card__bar-fill { background: var(--color-error,   #e9322d); }
	li.type-share    .insights-card__bar-fill { background: #8e44ad; }
	li.type-comment  .insights-card__bar-fill { background: #1abc9c; }
	li.type-favorite .insights-card__bar-fill { background: var(--color-warning, #f4a261); }
}
</style>
