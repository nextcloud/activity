/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { describe, expect, it } from 'vitest'
import {
	addDays,
	buildDays,
	groupIntoWeeks,
	isSelected,
	levelForCount,
	monthLabels,
} from '../utils/heatmap.ts'

describe('levelForCount', () => {
	it('gives a day with no activity no level at all', () => {
		expect(levelForCount(0, 10)).toBe(0)
	})

	it('scales to quartiles of the busiest day', () => {
		expect(levelForCount(1, 100)).toBe(1)
		expect(levelForCount(25, 100)).toBe(1)
		expect(levelForCount(26, 100)).toBe(2)
		expect(levelForCount(50, 100)).toBe(2)
		expect(levelForCount(75, 100)).toBe(3)
		expect(levelForCount(100, 100)).toBe(4)
	})

	it('does not paint a single activity as the busiest possible day', () => {
		// Proportional buckets would put 1-of-1 at the darkest step, claiming a
		// quiet window is a busy one
		expect(levelForCount(1, 1)).toBe(1)
		expect(levelForCount(1, 3)).toBe(1)
		expect(levelForCount(3, 3)).toBe(3)
	})

	it('never exceeds the number of ramp steps', () => {
		expect(levelForCount(1000, 10)).toBe(4)
	})
})

describe('buildDays', () => {
	it('fills the gaps the API omits', () => {
		const days = buildDays('2024-03-01', '2024-03-04', { '2024-03-01': 2, '2024-03-04': 8 }, 8)

		expect(days.map((day) => day.date)).toEqual(['2024-03-01', '2024-03-02', '2024-03-03', '2024-03-04'])
		expect(days.map((day) => day.count)).toEqual([2, 0, 0, 8])
		expect(days.map((day) => day.level)).toEqual([1, 0, 0, 4])
	})

	it('marks the days whose counts are incomplete', () => {
		const days = buildDays('2024-03-01', '2024-03-03', {}, 0, '2024-03-03')

		expect(days.map((day) => day.partial)).toEqual([true, true, false])
	})

	it('returns nothing for a reversed or unparseable window', () => {
		expect(buildDays('2024-03-04', '2024-03-01', {}, 0)).toEqual([])
		expect(buildDays('nonsense', '2024-03-01', {}, 0)).toEqual([])
	})

	it('crosses a month boundary without losing or repeating a day', () => {
		const days = buildDays('2024-02-27', '2024-03-02', {}, 0)

		// 2024 is a leap year, so 29 February has to be there
		expect(days.map((day) => day.date)).toEqual([
			'2024-02-27',
			'2024-02-28',
			'2024-02-29',
			'2024-03-01',
			'2024-03-02',
		])
	})
})

describe('groupIntoWeeks', () => {
	it('pads the first column so each row is one weekday', () => {
		// 2024-03-01 was a Friday
		const days = buildDays('2024-03-01', '2024-03-10', {}, 0)
		const weeks = groupIntoWeeks(days, 1) // week starts Monday

		// 4 pad cells for Mon–Thu plus 10 days fills exactly two columns
		expect(weeks).toHaveLength(2)
		// Friday is the fifth row when the week starts on Monday
		expect(weeks[0]!.slice(0, 4).every((cell) => cell === null)).toBe(true)
		expect(weeks[0]![4]!.date).toBe('2024-03-01')
	})

	it('respects a locale whose week starts on Sunday', () => {
		const days = buildDays('2024-03-01', '2024-03-10', {}, 0)
		const weeks = groupIntoWeeks(days, 0)

		// Friday is the sixth row when the week starts on Sunday
		expect(weeks[0]![5]!.date).toBe('2024-03-01')
	})

	it('pads the final column so the grid stays rectangular', () => {
		const days = buildDays('2024-03-01', '2024-03-10', {}, 0)
		const weeks = groupIntoWeeks(days, 1)

		expect(weeks.every((week) => week.length === 7)).toBe(true)
	})

	it('has nothing to group for an empty window', () => {
		expect(groupIntoWeeks([], 1)).toEqual([])
	})
})

describe('monthLabels', () => {
	it('labels only the column where a month starts', () => {
		const days = buildDays('2024-02-19', '2024-03-31', {}, 0)
		const weeks = groupIntoWeeks(days, 1)
		const labels = monthLabels(weeks, (date) => String(date.getMonth() + 1))

		// Exactly one label per month present in the window
		expect(labels.filter((label) => label !== null)).toEqual(['2', '3'])
	})
})

describe('isSelected', () => {
	const day = (date: string) => {
		const [y, m, d] = date.split('-').map(Number)
		return new Date(y!, m! - 1, d!)
	}

	it('is false when nothing is selected', () => {
		expect(isSelected('2024-03-01', null, null)).toBe(false)
	})

	it('covers both ends of a closed range', () => {
		expect(isSelected('2024-03-01', day('2024-03-01'), day('2024-03-03'))).toBe(true)
		expect(isSelected('2024-03-03', day('2024-03-01'), day('2024-03-03'))).toBe(true)
		expect(isSelected('2024-03-04', day('2024-03-01'), day('2024-03-03'))).toBe(false)
	})

	it('treats a range with only a start as open ended', () => {
		expect(isSelected('2099-01-01', day('2024-03-01'), null)).toBe(true)
		expect(isSelected('2024-02-29', day('2024-03-01'), null)).toBe(false)
	})

	it('treats a range with only an end as open at the start', () => {
		expect(isSelected('1999-01-01', null, day('2024-03-01'))).toBe(true)
		expect(isSelected('2024-03-02', null, day('2024-03-01'))).toBe(false)
	})
})

describe('addDays', () => {
	it('crosses a month end', () => {
		expect(addDays(new Date(2024, 1, 28), 1).getDate()).toBe(29)
		expect(addDays(new Date(2024, 1, 29), 1).getMonth()).toBe(2)
	})

	it('steps backwards', () => {
		const result = addDays(new Date(2024, 2, 1), -1)
		expect(result.getMonth()).toBe(1)
		expect(result.getDate()).toBe(29)
	})

	it('does not drift across a DST transition', () => {
		// Europe/Berlin springs forward on 2024-03-31; a naive +86400000ms would
		// land on the same calendar day
		const result = addDays(new Date(2024, 2, 31), 1)
		expect(result.getDate()).toBe(1)
		expect(result.getMonth()).toBe(3)
	})
})
