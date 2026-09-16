/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { formatDateParameter, parseDateParameter } from './dateRange.ts'

/**
 * How many filled steps the colour ramp has.
 *
 * Four, plus the empty state, keeps the classes distinguishable — past roughly
 * seven bins adjacent shades blur into each other and the chart stops encoding
 * anything.
 */
export const HEATMAP_LEVELS = 4

/**
 * One day of the histogram.
 */
export interface IHeatmapDay {
	/** Local calendar date, `YYYY-MM-DD` */
	date: string
	/** Number of activities on that day */
	count: number
	/** Ramp step, 0 for a day with no activity */
	level: number
	/** Whether the count is known to be incomplete */
	partial: boolean
}

/**
 * Assign a day's count to a step of the ramp.
 *
 * Two regimes on purpose. When the busiest day in the window is itself small,
 * proportional buckets would paint a single activity as "most active", so counts
 * map straight onto steps. Once there is a real spread, the steps are quartiles
 * of the maximum, which is what makes the busy periods stand out.
 *
 * @param count - Activities on the day
 * @param max - Busiest day in the window
 */
export function levelForCount(count: number, max: number): number {
	if (count <= 0) {
		return 0
	}
	if (max <= HEATMAP_LEVELS) {
		return Math.min(count, HEATMAP_LEVELS)
	}
	return Math.min(HEATMAP_LEVELS, Math.ceil((count / max) * HEATMAP_LEVELS))
}

/**
 * Add days to a `YYYY-MM-DD` date, staying in local time.
 *
 * @param date - The starting date
 * @param days - How many days to add
 */
export function addDays(date: Date, days: number): Date {
	const result = new Date(date.getFullYear(), date.getMonth(), date.getDate())
	result.setDate(result.getDate() + days)
	return result
}

/**
 * Build the day list the grid renders, one entry per day in the window.
 *
 * Days the server did not report are included with a count of zero: the payload
 * only carries days that saw activity, and the grid still needs a cell for every
 * square in between.
 *
 * @param from - First day of the window, `YYYY-MM-DD`
 * @param to - Last day of the window, `YYYY-MM-DD`
 * @param counts - Activity counts keyed by date, as returned by the API
 * @param max - Busiest day in the window
 * @param partialBefore - Date from which counts are incomplete, if any
 */
export function buildDays(
	from: string,
	to: string,
	counts: Record<string, number>,
	max: number,
	partialBefore: string | null = null,
): IHeatmapDay[] {
	const start = parseDateParameter(from)
	const end = parseDateParameter(to)
	if (start === null || end === null || start > end) {
		return []
	}

	const days: IHeatmapDay[] = []
	for (let cursor = start; cursor <= end; cursor = addDays(cursor, 1)) {
		const date = formatDateParameter(cursor)
		const count = counts[date] ?? 0
		days.push({
			date,
			count,
			level: levelForCount(count, max),
			// String comparison is safe here: ISO dates sort lexicographically
			partial: partialBefore !== null && date < partialBefore,
		})
	}
	return days
}

/**
 * Group days into calendar weeks for the grid.
 *
 * Each column is one week and each row one weekday, so the leading and trailing
 * partial weeks are padded with nulls rather than shifting every later column
 * onto the wrong row.
 *
 * @param days - The window's days, in order
 * @param firstDayOfWeek - Weekday the locale starts on, 0 = Sunday
 */
export function groupIntoWeeks(days: IHeatmapDay[], firstDayOfWeek: number): (IHeatmapDay | null)[][] {
	if (days.length === 0) {
		return []
	}

	const weeks: (IHeatmapDay | null)[][] = []
	let week: (IHeatmapDay | null)[] = []

	const rowFor = (date: string): number => {
		const parsed = parseDateParameter(date)!
		return (parsed.getDay() - firstDayOfWeek + 7) % 7
	}

	// Pad the first column so day one sits on its real weekday
	for (let row = 0; row < rowFor(days[0]!.date); row++) {
		week.push(null)
	}

	for (const day of days) {
		week.push(day)
		if (week.length === 7) {
			weeks.push(week)
			week = []
		}
	}

	if (week.length > 0) {
		while (week.length < 7) {
			week.push(null)
		}
		weeks.push(week)
	}

	return weeks
}

/**
 * Month labels for the columns, placed on the first column of each month.
 *
 * Returns one entry per column so the header row stays aligned with the grid
 * without absolute positioning; columns that do not start a month get null.
 *
 * @param weeks - The grouped weeks
 * @param formatMonth - Formats a date as a short month name
 */
export function monthLabels(
	weeks: (IHeatmapDay | null)[][],
	formatMonth: (date: Date) => string,
): (string | null)[] {
	let lastMonth = ''
	return weeks.map((week) => {
		const firstDay = week.find((day): day is IHeatmapDay => day !== null)
		if (firstDay === undefined) {
			return null
		}
		const parsed = parseDateParameter(firstDay.date)!
		const key = `${parsed.getFullYear()}-${parsed.getMonth()}`
		if (key === lastMonth) {
			return null
		}
		lastMonth = key
		return formatMonth(parsed)
	})
}

/**
 * Whether a day falls inside the selected range.
 *
 * An open ended selection still highlights: a range with only a start covers
 * everything from that day onwards.
 *
 * @param date - The day to test, `YYYY-MM-DD`
 * @param from - Start of the selection, or null
 * @param to - End of the selection, or null
 */
export function isSelected(date: string, from: Date | null, to: Date | null): boolean {
	if (from === null && to === null) {
		return false
	}
	const start = from === null ? '' : formatDateParameter(from)
	const end = to === null ? '9999-12-31' : formatDateParameter(to)
	return date >= start && date <= end
}
