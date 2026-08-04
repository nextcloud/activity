/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { describe, expect, it } from 'vitest'
import {
	daysAgo,
	endOfDayTimestamp,
	formatDateParameter,
	MAX_SEARCH_TERM_LENGTH,
	normalizeSearchTerm,
	parseDateParameter,
	startOfDayTimestamp,
} from '../utils/dateRange.ts'

describe('startOfDayTimestamp', () => {
	it('returns the first second of the day in local time', () => {
		const timestamp = startOfDayTimestamp(new Date(2024, 0, 15, 17, 42, 13))
		expect(timestamp).toBe(Math.floor(new Date(2024, 0, 15, 0, 0, 0, 0).getTime() / 1000))
	})

	it('treats a missing or invalid date as unbounded', () => {
		expect(startOfDayTimestamp(null)).toBe(0)
		expect(startOfDayTimestamp(undefined)).toBe(0)
		expect(startOfDayTimestamp(new Date('nonsense'))).toBe(0)
	})
})

describe('endOfDayTimestamp', () => {
	it('returns the last second of the day so the whole day is included', () => {
		const timestamp = endOfDayTimestamp(new Date(2024, 0, 15, 3, 0, 0))
		expect(timestamp).toBe(Math.floor(new Date(2024, 0, 15, 23, 59, 59, 999).getTime() / 1000))
	})

	it('is always later than the start of the same day', () => {
		const date = new Date(2024, 5, 1)
		expect(endOfDayTimestamp(date)).toBeGreaterThan(startOfDayTimestamp(date))
	})

	it('treats a missing or invalid date as unbounded', () => {
		expect(endOfDayTimestamp(null)).toBe(0)
		expect(endOfDayTimestamp(new Date('nonsense'))).toBe(0)
	})
})

describe('formatDateParameter', () => {
	it('formats using local date components, not UTC', () => {
		// 23:30 local would already be the next day in UTC for positive offsets
		// and the previous day for negative ones
		expect(formatDateParameter(new Date(2024, 0, 15, 23, 30))).toBe('2024-01-15')
		expect(formatDateParameter(new Date(2024, 0, 15, 0, 30))).toBe('2024-01-15')
	})

	it('zero pads month and day', () => {
		expect(formatDateParameter(new Date(2024, 2, 5))).toBe('2024-03-05')
	})

	it('returns an empty string when there is no date', () => {
		expect(formatDateParameter(null)).toBe('')
		expect(formatDateParameter(new Date('nonsense'))).toBe('')
	})
})

describe('parseDateParameter', () => {
	it('parses a date at local midnight', () => {
		const parsed = parseDateParameter('2024-01-15')
		expect(parsed).toEqual(new Date(2024, 0, 15))
		expect(parsed?.getHours()).toBe(0)
	})

	it('round trips with formatDateParameter', () => {
		const original = new Date(2024, 10, 3)
		expect(parseDateParameter(formatDateParameter(original))).toEqual(original)
	})

	it('rejects dates that do not exist rather than rolling them over', () => {
		expect(parseDateParameter('2026-02-31')).toBeNull()
		expect(parseDateParameter('2024-13-01')).toBeNull()
	})

	it('rejects malformed input so a hand-edited URL degrades to no filter', () => {
		expect(parseDateParameter('not-a-date')).toBeNull()
		expect(parseDateParameter('2024-1-5')).toBeNull()
		expect(parseDateParameter('')).toBeNull()
		expect(parseDateParameter(undefined)).toBeNull()
		expect(parseDateParameter(['2024-01-15'])).toBeNull()
	})
})

describe('daysAgo', () => {
	it('returns local midnight today for zero', () => {
		const today = daysAgo(0)
		const now = new Date()
		expect(today.getDate()).toBe(now.getDate())
		expect(today.getHours()).toBe(0)
		expect(today.getMinutes()).toBe(0)
	})

	it('goes back the requested number of days', () => {
		const difference = daysAgo(0).getTime() - daysAgo(7).getTime()
		// Allow for a DST transition inside the window
		expect(difference / (24 * 60 * 60 * 1000)).toBeCloseTo(7, 0)
	})
})

describe('normalizeSearchTerm', () => {
	it('trims surrounding whitespace', () => {
		expect(normalizeSearchTerm('  report  ')).toBe('report')
	})

	it('treats a term below the minimum length as no search', () => {
		expect(normalizeSearchTerm('a')).toBe('')
		expect(normalizeSearchTerm('   ')).toBe('')
		expect(normalizeSearchTerm('')).toBe('')
	})

	it('accepts a term at the minimum length', () => {
		expect(normalizeSearchTerm('ab')).toBe('ab')
	})

	it('truncates to the maximum the API accepts', () => {
		expect(normalizeSearchTerm('x'.repeat(400))).toHaveLength(MAX_SEARCH_TERM_LENGTH)
	})

	it('leaves wildcard characters alone for the backend to escape', () => {
		expect(normalizeSearchTerm('100%_done')).toBe('100%_done')
	})
})
