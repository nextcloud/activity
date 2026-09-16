<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<section
		class="activity-heatmap"
		:class="{ 'activity-heatmap--loading': loading }"
		:aria-label="t('activity', 'Activity over time')">
		<p class="activity-heatmap__summary">
			{{ summary }}
		</p>

		<div ref="scroller" class="activity-heatmap__scroller">
			<table class="activity-heatmap__grid">
				<caption class="hidden-visually">
					{{ t('activity', 'Activity per day. Each cell is one day; use the arrow keys to move between days and Enter to filter the stream by that day.') }}
				</caption>
				<thead>
					<tr>
						<td class="activity-heatmap__corner" />
						<th
							v-for="(label, column) in monthHeadings"
							:key="`month-${column}`"
							class="activity-heatmap__month"
							scope="col">
							<span v-if="label" class="activity-heatmap__month-label">{{ label }}</span>
							<span v-else class="hidden-visually">{{ columnRangeLabel(column) }}</span>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="row in 7" :key="`row-${row}`">
						<th class="activity-heatmap__weekday" scope="row">
							<span :class="{ 'hidden-visually': !showsWeekdayLabel(row - 1) }">
								{{ weekdayLabels[row - 1] }}
							</span>
						</th>
						<td
							v-for="(week, column) in weeks"
							:key="`cell-${column}-${row}`"
							class="activity-heatmap__cell-wrapper">
							<button
								v-if="week[row - 1]"
								:ref="(element) => registerCell(week[row - 1].date, element)"
								type="button"
								class="activity-heatmap__cell"
								:class="[
									`activity-heatmap__cell--level-${week[row - 1].level}`,
									{
										'activity-heatmap__cell--selected': selectedDates.has(week[row - 1].date),
										'activity-heatmap__cell--partial': week[row - 1].partial,
									},
								]"
								:data-date="week[row - 1].date"
								:tabindex="week[row - 1].date === rovingDate ? 0 : -1"
								:aria-pressed="selectedDates.has(week[row - 1].date)"
								:aria-label="cellLabel(week[row - 1])"
								@click="onCellActivate(week[row - 1], $event)"
								@keydown="onKeydown($event, week[row - 1])"
								@focus="onCellFocus(week[row - 1], $event)"
								@blur="hovered = null"
								@pointerenter="onCellHover(week[row - 1], $event)"
								@pointerleave="hovered = null" />
							<span v-else class="activity-heatmap__cell activity-heatmap__cell--void" aria-hidden="true" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="activity-heatmap__legend">
			<span class="activity-heatmap__legend-caption">{{ t('activity', 'Less') }}</span>
			<span
				v-for="level in legendLevels"
				:key="`legend-${level}`"
				class="activity-heatmap__cell"
				:class="`activity-heatmap__cell--level-${level}`"
				aria-hidden="true" />
			<span class="activity-heatmap__legend-caption">{{ t('activity', 'More') }}</span>
			<span class="hidden-visually">{{ legendDescription }}</span>
		</div>

		<div
			v-if="hovered"
			class="activity-heatmap__tooltip"
			:style="tooltipStyle"
			aria-hidden="true">
			<strong class="activity-heatmap__tooltip-value">{{ tooltipValue }}</strong>
			<span class="activity-heatmap__tooltip-date">{{ tooltipDate }}</span>
		</div>
	</section>
</template>

<script setup lang="ts">
import type { IHeatmapDay } from '../utils/heatmap.ts'

import ncAxios from '@nextcloud/axios'
import { translatePlural as n, translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { generateOcsUrl } from '@nextcloud/router'
import axios from 'axios'
import { computed, nextTick, onUnmounted, ref, watch } from 'vue'
import { formatDateParameter, parseDateParameter } from '../utils/dateRange.ts'
import { addDays, buildDays, groupIntoWeeks, HEATMAP_LEVELS, isSelected, monthLabels } from '../utils/heatmap.ts'
import logger from '../utils/logger.ts'

const props = withDefaults(defineProps<{
	/** The stream filter the counts are scoped to */
	filter: string
	/** Active file name search, so the bars count what the stream lists */
	search?: string
	/** Active account restriction */
	actor?: string
	/** Start of the selected range */
	from?: Date | null
	/** End of the selected range */
	to?: Date | null
	/** Length of the window in days; 52 weeks by default */
	days?: number
}>(), {
	search: '',
	actor: '',
	from: null,
	to: null,
	days: 364,
})

const emit = defineEmits<{
	(event: 'update:from', value: Date | null): void
	(event: 'update:to', value: Date | null): void
}>()

const loading = ref(false)
const windowStart = ref('')
const windowEnd = ref('')
const counts = ref<Record<string, number>>({})
const maxCount = ref(0)
const total = ref(0)
const partialBefore = ref<string | null>(null)

const hovered = ref<IHeatmapDay | null>(null)
const tooltipStyle = ref<Record<string, string>>({})
const rovingDate = ref('')
const scroller = ref<HTMLDivElement>()

/**
 * Anchor for range extension with Shift.
 *
 * Held separately from the selection so extending after a plain click grows from
 * the day that was clicked, not from whichever end of the range happens to be
 * closer.
 */
const anchor = ref<string | null>(null)

const cellElements = new Map<string, HTMLButtonElement>()
let requestController = new AbortController()

const legendLevels = Array.from({ length: HEATMAP_LEVELS + 1 }, (_, level) => level)

const firstDayOfWeek = computed(() => moment.localeData().firstDayOfWeek())

const dayCells = computed(() => buildDays(
	windowStart.value,
	windowEnd.value,
	counts.value,
	maxCount.value,
	partialBefore.value,
))

const weeks = computed(() => groupIntoWeeks(dayCells.value, firstDayOfWeek.value))

const monthHeadings = computed(() => monthLabels(weeks.value, (date) => moment(date).format('MMM')))

const weekdayLabels = computed(() => Array.from({ length: 7 }, (_, row) => {
	// moment's weekday() is already locale-relative, so row 0 is the locale's
	// first day without any manual rotation
	return moment().weekday(row).format('ddd')
}))

/**
 * Dates covered by the current selection.
 *
 * Precomputed into a set because every cell asks about itself on each render,
 * and re-deriving the comparison per cell shows up on a 182 cell grid.
 */
const selectedDates = computed(() => {
	const selected = new Set<string>()
	if (props.from === null && props.to === null) {
		return selected
	}
	for (const day of dayCells.value) {
		if (isSelected(day.date, props.from, props.to)) {
			selected.add(day.date)
		}
	}
	return selected
})

const summary = computed(() => {
	if (windowStart.value === '') {
		return t('activity', 'Loading activity summary')
	}
	const since = moment(parseDateParameter(windowStart.value)!).format('LL')
	return n('activity', '%n activity since {date}', '%n activities since {date}', total.value, { date: since })
})

const legendDescription = computed(() => t(
	'activity',
	'Colour shows how many activities happened on each day, from none to {max}.',
	{ max: maxCount.value },
))

const tooltipValue = computed(() => hovered.value === null
	? ''
	: n('activity', '%n activity', '%n activities', hovered.value.count))

const tooltipDate = computed(() => {
	if (hovered.value === null) {
		return ''
	}
	const formatted = moment(parseDateParameter(hovered.value.date)!).format('LL')
	return hovered.value.partial
		? t('activity', '{date} — count incomplete', { date: formatted })
		: formatted
})

/**
 * Accessible name for a cell.
 *
 * Carries the date and the exact count, so the value never depends on hovering
 * or on reading the colour.
 *
 * @param day - The day the cell represents
 */
function cellLabel(day: IHeatmapDay): string {
	const formatted = moment(parseDateParameter(day.date)!).format('LL')
	const base = n('activity', '%n activity on {date}', '%n activities on {date}', day.count, { date: formatted })
	return day.partial
		? t('activity', '{label} (count incomplete)', { label: base })
		: base
}

/**
 * Only some weekday labels are shown, so the column of text does not crowd the
 * grid; the rest stay available to assistive technology.
 *
 * @param row - Grid row, 0 based
 */
function showsWeekdayLabel(row: number): boolean {
	return row % 2 === 1
}

/**
 * Range a column covers, for assistive technology on unlabelled columns.
 *
 * @param column - Column index
 */
function columnRangeLabel(column: number): string {
	const week = weeks.value[column] ?? []
	const present = week.filter((day): day is IHeatmapDay => day !== null)
	if (present.length === 0) {
		return ''
	}
	return t('activity', 'Week of {date}', {
		date: moment(parseDateParameter(present[0]!.date)!).format('LL'),
	})
}

/**
 * Keep a handle on each cell so keyboard navigation can move focus.
 *
 * @param date - The cell's date
 * @param element - The rendered element, or null when it is torn down
 */
function registerCell(date: string, element: unknown): void {
	if (element instanceof HTMLButtonElement) {
		cellElements.set(date, element)
	} else {
		cellElements.delete(date)
	}
}

/**
 * Position the tooltip against a cell.
 *
 * Anchored with fixed coordinates from the cell's own rect so the tooltip is not
 * clipped by the horizontally scrolling grid it sits inside.
 *
 * @param element - The cell element
 */
function placeTooltip(element: HTMLElement): void {
	const rect = element.getBoundingClientRect()
	tooltipStyle.value = {
		insetInlineStart: `${rect.left + rect.width / 2}px`,
		insetBlockStart: `${rect.top}px`,
	}
}

/**
 * @param day - The hovered day
 * @param event - The pointer event
 */
function onCellHover(day: IHeatmapDay, event: PointerEvent): void {
	hovered.value = day
	placeTooltip(event.currentTarget as HTMLElement)
}

/**
 * Focus shows the same readout as hover, so the keyboard path is not second class.
 *
 * @param day - The focused day
 * @param event - The focus event
 */
function onCellFocus(day: IHeatmapDay, event: FocusEvent): void {
	rovingDate.value = day.date
	hovered.value = day
	placeTooltip(event.currentTarget as HTMLElement)
}

/**
 * Apply a selection, emitting both ends of the range.
 *
 * @param from - First day, `YYYY-MM-DD`
 * @param to - Last day, `YYYY-MM-DD`
 */
function select(from: string, to: string): void {
	emit('update:from', parseDateParameter(from))
	emit('update:to', parseDateParameter(to))
}

/**
 * Clear the current selection, emitting null for both bounds.
 */
function clearSelection(): void {
	emit('update:from', null)
	emit('update:to', null)
}

/**
 * Handle picking a day.
 *
 * Shift extends the range from the anchor; a plain activation selects the single
 * day, or clears the filter when that day is already the whole selection, so the
 * same cell toggles rather than trapping the reader in a one day view.
 *
 * @param day - The activated day
 * @param event - The originating event, for its shift key state
 */
function onCellActivate(day: IHeatmapDay, event: MouseEvent | KeyboardEvent): void {
	if (event.shiftKey && anchor.value !== null) {
		const [from, to] = [anchor.value, day.date].sort()
		select(from!, to!)
		return
	}

	anchor.value = day.date
	const alreadyOnlySelection = selectedDates.value.size === 1 && selectedDates.value.has(day.date)
	if (alreadyOnlySelection) {
		clearSelection()
		return
	}
	select(day.date, day.date)
}

/**
 * Move the roving focus to a date, if it is inside the window.
 *
 * @param date - Target date, `YYYY-MM-DD`
 */
function moveFocus(date: string): void {
	const element = cellElements.get(date)
	if (element === undefined) {
		return
	}
	rovingDate.value = date
	element.focus()
}

/**
 * Grid keyboard model: columns are weeks and rows are weekdays, so the
 * horizontal step is a week and the vertical step a single day.
 *
 * @param event - The key event
 * @param day - The day the focus is currently on
 */
function onKeydown(event: KeyboardEvent, day: IHeatmapDay): void {
	const current = parseDateParameter(day.date)
	if (current === null) {
		return
	}

	const step = (amount: number) => {
		event.preventDefault()
		moveFocus(formatDateParameter(addDays(current, amount)))
	}

	switch (event.key) {
		case 'ArrowLeft':
			step(-7)
			break
		case 'ArrowRight':
			step(7)
			break
		case 'ArrowUp':
			step(-1)
			break
		case 'ArrowDown':
			step(1)
			break
		case 'Home':
			event.preventDefault()
			moveFocus(windowStart.value)
			break
		case 'End':
			event.preventDefault()
			moveFocus(windowEnd.value)
			break
	}
}

/**
 * Load the histogram for the current filter.
 *
 * Supersedes any request still in flight, so a slow response for a previous
 * filter cannot repaint the grid after a newer one has answered.
 */
async function loadHistogram(): Promise<void> {
	requestController.abort()
	requestController = new AbortController()
	const { signal } = requestController

	try {
		loading.value = true
		const url = generateOcsUrl('apps/activity/api/v2/activity/{filter}/histogram', { filter: props.filter })
		const response = await ncAxios.get(url, {
			signal,
			params: {
				format: 'json',
				days: props.days,
				...(props.search === '' ? {} : { search: props.search }),
				...(props.actor === '' ? {} : { actor: props.actor }),
			},
		})
		if (signal.aborted) {
			return
		}

		const data = response.data.ocs.data
		windowStart.value = data.from
		windowEnd.value = data.to
		counts.value = data.counts ?? {}
		maxCount.value = data.max ?? 0
		total.value = data.total ?? 0
		partialBefore.value = data.partial_before ?? null
		if (rovingDate.value < windowStart.value || rovingDate.value > windowEnd.value) {
			// Start the keyboard path on today, the cell a reader looks at first
			rovingDate.value = data.to
		}

		// The window ends today, so the interesting end is the far one. Wait for
		// the grid to exist before scrolling it.
		await nextTick()
		const element = scroller.value
		if (element) {
			element.scrollLeft = element.scrollWidth
		}
	} catch (error) {
		if (signal.aborted || axios.isCancel(error)) {
			return
		}
		// A missing histogram is not worth an error toast over the stream itself;
		// the grid simply stays empty
		logger.debug('Could not load the activity histogram', { error })
	} finally {
		if (!signal.aborted) {
			loading.value = false
		}
	}
}

watch(() => [props.filter, props.search, props.actor, props.days], () => {
	loadHistogram()
}, { immediate: true })

onUnmounted(() => requestController.abort())
</script>

<style scoped lang="scss">
.activity-heatmap {
	// Ramp steps as percentages of the theme's accent mixed into the surface.
	// Mixing toward the background is what makes this a valid sequential scale
	// in both themes without a second palette: --color-primary-element is
	// contrast-adjusted per theme by the server, so in light mode the ramp runs
	// light-to-dark and in dark mode it runs dark-to-light, which is the anchor
	// flip a sequential scale needs.
	// The four steps were validated against both Nextcloud surfaces (#ffffff and
	// #171717) with the default accent: monotone OKLCH lightness, adjacent
	// gaps >= 0.09, single hue, and the lightest step clearing 2:1 against the
	// surface so "one activity" is never mistaken for "none".
	--activity-heat-1: color-mix(in oklab, var(--color-primary-element) 45%, var(--color-main-background));
	--activity-heat-2: color-mix(in oklab, var(--color-primary-element) 63%, var(--color-main-background));
	--activity-heat-3: color-mix(in oklab, var(--color-primary-element) 81%, var(--color-main-background));
	--activity-heat-4: var(--color-primary-element);
	// "No activity" is deliberately not a step of the ramp: a neutral reads as
	// nothing, where the palest blue would read as a small value
	--activity-heat-0: var(--color-background-dark);
	--activity-heat-cell: 13px;
	--activity-heat-gap: 3px;
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-grid-baseline) * 2);

	&--loading {
		// Hold the previous render rather than flashing a skeleton, so the grid
		// never jumps while a new filter loads
		opacity: 0.6;
	}

	&__summary {
		margin: 0;
		color: var(--color-text-maxcontrast);
	}

	&__scroller {
		// The grid keeps its cell size and scrolls instead of squeezing, so a
		// narrow window never turns the squares into slivers
		overflow-x: auto;
		overflow-y: hidden;
		padding-block-end: var(--default-grid-baseline);
	}

	&__grid {
		border-collapse: separate;
		border-spacing: var(--activity-heat-gap);
		// Trim the outer ring of border-spacing so the grid still lines up with
		// the text above it
		margin: calc(var(--activity-heat-gap) * -1);
	}

	&__corner {
		padding: 0;
	}

	&__month {
		// Labels sit on the first column of their month and must not widen it
		position: relative;
		height: 1.2em;
		padding: 0;
		font-weight: normal;
		text-align: start;
	}

	&__month-label {
		position: absolute;
		inset-block-end: 0;
		inset-inline-start: 0;
		color: var(--color-text-maxcontrast);
		font-size: 11px;
		line-height: 1.2;
		white-space: nowrap;
	}

	&__weekday {
		padding: 0;
		padding-inline-end: var(--default-grid-baseline);
		color: var(--color-text-maxcontrast);
		font-size: 11px;
		font-weight: normal;
		text-align: end;
		vertical-align: middle;
		white-space: nowrap;
	}

	&__cell-wrapper {
		padding: 0;
		line-height: 0;
	}

	&__cell {
		display: block;
		box-sizing: border-box;
		width: var(--activity-heat-cell);
		height: var(--activity-heat-cell);
		min-width: 0;
		min-height: 0;
		padding: 0;
		border: none;
		// Rounded, not circular: squares tile into a calendar, circles read as
		// unrelated dots
		border-radius: 3px;
		background-color: var(--activity-heat-0);

		&--level-1 { background-color: var(--activity-heat-1); }
		&--level-2 { background-color: var(--activity-heat-2); }
		&--level-3 { background-color: var(--activity-heat-3); }
		&--level-4 { background-color: var(--activity-heat-4); }

		&--void {
			// Padding day outside the window: holds the column's shape without
			// claiming there were zero activities
			background-color: transparent;
		}

		&--partial {
			// Counts here are known to be incomplete, so the cell is knocked back
			// rather than presented as a measured value
			opacity: 0.45;
		}
	}

	button.activity-heatmap__cell {
		cursor: pointer;

		&:hover {
			// The mark responds to the pointer without changing its value colour
			outline: 2px solid var(--color-main-text);
			outline-offset: 1px;
		}

		&:focus-visible {
			outline: 2px solid var(--color-main-text);
			outline-offset: 2px;
		}

		&--selected {
			// Selection is a ring, never a fill: the fill is spoken for by the
			// value, and repainting it would misstate the day's count
			box-shadow: 0 0 0 2px var(--color-main-background), 0 0 0 3px var(--color-main-text);
		}
	}

	&__legend {
		display: flex;
		align-items: center;
		gap: var(--activity-heat-gap);
		align-self: flex-end;
		color: var(--color-text-maxcontrast);
		font-size: 11px;
	}

	&__legend-caption {
		margin-inline: var(--default-grid-baseline);
	}

	&__tooltip {
		position: fixed;
		z-index: 2000;
		display: flex;
		flex-direction: column;
		transform: translate(-50%, calc(-100% - 8px));
		padding: 6px 10px;
		border-radius: var(--border-radius-large);
		background-color: var(--color-main-background);
		box-shadow: 0 1px 5px var(--color-box-shadow);
		pointer-events: none;
		white-space: nowrap;
	}

	&__tooltip-value {
		// Values lead, the date follows: the reader already knows which cell
		// they are on and wants the number
		color: var(--color-main-text);
	}

	&__tooltip-date {
		color: var(--color-text-maxcontrast);
		font-size: 11px;
	}
}
</style>
