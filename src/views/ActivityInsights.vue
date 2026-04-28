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
			<!-- ── Top stats row: streak + raw counts ── -->
			<div class="insights__stats">
				<section class="insights-stat insights-stat--streak">
					<span class="insights-stat__value">{{ streak.value }}</span>
					<span class="insights-stat__label">
						<span class="insights-stat__flame" :class="{ 'is-on': streak.value > 0 }">🔥</span>
						{{ streakLabel }}
					</span>
				</section>
				<section class="insights-stat">
					<span class="insights-stat__value">{{ activities.length }}</span>
					<span class="insights-stat__label">{{ t('activity', 'recent activities') }}</span>
				</section>
				<section class="insights-stat">
					<span class="insights-stat__value">{{ todayCount }}</span>
					<span class="insights-stat__label">{{ t('activity', 'today') }}</span>
				</section>
				<section class="insights-stat">
					<span class="insights-stat__value">{{ topCollaborators.length }}</span>
					<span class="insights-stat__label">{{ t('activity', 'people involved') }}</span>
				</section>
			</div>

			<!-- ── 12-week heatmap calendar ── -->
			<section class="insights-card">
				<h2>{{ t('activity', 'Activity over the last {weeks} weeks', { weeks: heatmapWeeks }) }}</h2>
				<div class="insights-heatmap" role="img" :aria-label="t('activity', 'Activity heatmap')">
					<div class="insights-heatmap__weekday-labels" aria-hidden="true">
						<span>{{ t('activity', 'Mon') }}</span>
						<span></span>
						<span>{{ t('activity', 'Wed') }}</span>
						<span></span>
						<span>{{ t('activity', 'Fri') }}</span>
						<span></span>
						<span>{{ t('activity', 'Sun') }}</span>
					</div>
					<div class="insights-heatmap__grid">
						<div
							v-for="(week, wi) in heatmap"
							:key="wi"
							class="insights-heatmap__week">
							<div
								v-for="(cell, di) in week"
								:key="di"
								class="insights-heatmap__cell"
								:class="`level-${cell.level}`"
								:title="cell.iso ? cell.iso + ' — ' + cell.count : ''" />
						</div>
					</div>
					<div class="insights-heatmap__legend">
						<span>{{ t('activity', 'Less') }}</span>
						<span class="insights-heatmap__cell level-0" />
						<span class="insights-heatmap__cell level-1" />
						<span class="insights-heatmap__cell level-2" />
						<span class="insights-heatmap__cell level-3" />
						<span class="insights-heatmap__cell level-4" />
						<span>{{ t('activity', 'More') }}</span>
					</div>
				</div>
			</section>

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
const heatmapWeeks = 12

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
 * Map of YYYY-MM-DD → activity count for every day in the loaded sample.
 * Computed once and reused by streak + heatmap so we don't iterate the
 * activity list multiple times.
 */
const dayCounts = computed<Map<string, number>>(() => {
	const map = new Map<string, number>()
	for (const a of activities.value) {
		const iso = moment(a.datetime).format('YYYY-MM-DD')
		map.set(iso, (map.get(iso) ?? 0) + 1)
	}
	return map
})

/**
 * Consecutive days ending today (or yesterday) with at least one activity.
 * Today has not yet ended, so a missing today doesn't break the streak —
 * we just start counting from yesterday.
 */
const streak = computed(() => {
	const counts = dayCounts.value
	let day = moment().startOf('day')
	if (!counts.has(day.format('YYYY-MM-DD'))) {
		// Today not active yet — try yesterday as the streak's tail.
		day = day.subtract(1, 'day')
		if (!counts.has(day.format('YYYY-MM-DD'))) {
			return { value: 0 }
		}
	}
	let n = 0
	while (counts.has(day.format('YYYY-MM-DD'))) {
		n++
		day = day.subtract(1, 'day')
	}
	return { value: n }
})

const streakLabel = computed(() => {
	const v = streak.value.value
	if (v === 0) return t('activity', 'Start a streak')
	return t('activity', '{n}-day streak', { n: v })
})

const todayCount = computed(() => dayCounts.value.get(moment().format('YYYY-MM-DD')) ?? 0)

type HeatmapCell = { iso: string, count: number, level: 0 | 1 | 2 | 3 | 4 }

/**
 * `heatmapWeeks` × 7 grid of (week, weekday) cells, oldest week first.
 * Each cell carries a level 0-4 used by the CSS to pick a tint, so the
 * style sheet has the colour palette and the JS only buckets counts.
 */
const heatmap = computed<HeatmapCell[][]>(() => {
	// Establish the bucket boundaries from the loaded sample's max so
	// the four "active" levels actually distribute meaningfully.
	const counts = dayCounts.value
	const max = Math.max(1, ...Array.from(counts.values()))
	const step = max / 4

	const weeks: HeatmapCell[][] = []
	// Start from the Monday of the week that begins (heatmapWeeks - 1) weeks ago
	const start = moment().startOf('isoWeek').subtract(heatmapWeeks - 1, 'weeks')
	for (let w = 0; w < heatmapWeeks; w++) {
		const week: HeatmapCell[] = []
		for (let d = 0; d < 7; d++) {
			const date = start.clone().add(w, 'weeks').add(d, 'days')
			// Don't paint future days
			if (date.isAfter(moment(), 'day')) {
				week.push({ iso: '', count: 0, level: 0 })
				continue
			}
			const iso = date.format('YYYY-MM-DD')
			const count = counts.get(iso) ?? 0
			let level: HeatmapCell['level'] = 0
			if (count > 0) {
				if (count <= step)        level = 1
				else if (count <= step*2) level = 2
				else if (count <= step*3) level = 3
				else                      level = 4
			}
			week.push({ iso, count, level })
		}
		weeks.push(week)
	}
	return weeks
})

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

	&__stats {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
		gap: 12px;
		margin-bottom: 20px;
	}
}

// Big number + caption used at the top of the page.  The streak variant
// adds a flame emoji that animates softly when it is non-zero so users
// have a visible reward for consistency.
.insights-stat {
	display: flex;
	flex-direction: column;
	gap: 4px;
	padding: 14px 18px;
	border: 1px solid var(--color-border);
	border-radius: var(--border-radius-large);
	background: var(--color-main-background);

	&__value {
		font-size: 28px;
		font-weight: 700;
		color: var(--color-main-text);
		line-height: 1.1;
	}

	&__label {
		display: inline-flex;
		gap: 4px;
		align-items: center;
		font-size: 13px;
		color: var(--color-text-maxcontrast);
	}

	&__flame {
		display: inline-block;
		filter: grayscale(1);
		opacity: 0.5;

		&.is-on {
			filter: none;
			opacity: 1;
			animation: insights-flame 1.6s ease-in-out infinite alternate;
		}
	}

	&--streak {
		background: linear-gradient(140deg, var(--color-primary-element-light, var(--color-background-hover)) 0%, var(--color-main-background) 80%);
	}
}

@keyframes insights-flame {
	from { transform: scale(1)    rotate(-3deg); }
	to   { transform: scale(1.15) rotate(3deg); }
}

// 12-week × 7-day heatmap calendar.  Layout mirrors GitHub: weekdays
// down the left, weeks across the right.  Levels 0-4 map to a stepped
// tint of the primary colour for a familiar aesthetic.
.insights-heatmap {
	display: grid;
	grid-template-columns: 24px 1fr;
	grid-template-rows: 1fr auto;
	gap: 6px;

	&__weekday-labels {
		display: grid;
		grid-template-rows: repeat(7, 1fr);
		font-size: 10px;
		color: var(--color-text-maxcontrast);

		span {
			line-height: 14px;
		}
	}

	&__grid {
		display: flex;
		gap: 3px;
		min-height: 7 * 14px + 6 * 3px;
	}

	&__week {
		display: grid;
		grid-template-rows: repeat(7, 14px);
		gap: 3px;
		flex: 1;
	}

	&__cell {
		display: inline-block;
		width: 14px;
		height: 14px;
		border-radius: 3px;
		background: var(--color-background-darker);
		transition: transform 120ms ease;

		&:hover {
			transform: scale(1.15);
		}

		&.level-0 { background: var(--color-background-darker); }
		&.level-1 { background: color-mix(in srgb, var(--color-primary-element) 25%, var(--color-background-darker)); }
		&.level-2 { background: color-mix(in srgb, var(--color-primary-element) 50%, var(--color-background-darker)); }
		&.level-3 { background: color-mix(in srgb, var(--color-primary-element) 75%, var(--color-background-darker)); }
		&.level-4 { background: var(--color-primary-element); }
	}

	&__legend {
		grid-column: 1 / -1;
		display: flex;
		gap: 4px;
		align-items: center;
		justify-content: flex-end;
		font-size: 11px;
		color: var(--color-text-maxcontrast);
		margin-top: 4px;
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
