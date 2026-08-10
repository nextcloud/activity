/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

/**
 * Shortest term the API accepts. Kept in sync with
 * `SearchCriteria::MIN_TERM_LENGTH` so the UI never sends a request that the
 * backend would reject with a 400.
 */
export const MIN_SEARCH_TERM_LENGTH = 2

/**
 * Longest term the API accepts, mirroring `SearchCriteria::MAX_TERM_LENGTH`.
 */
export const MAX_SEARCH_TERM_LENGTH = 255

/**
 * Check whether a date is usable.
 *
 * Native date inputs hand out an `Invalid Date` while the user is still typing,
 * so every helper here has to tolerate one.
 *
 * @param date - The date to check
 */
function isValidDate(date: Date | null | undefined): date is Date {
	return date instanceof Date && !Number.isNaN(date.getTime())
}

/**
 * Unix timestamp of the first second of the day the given date falls on.
 *
 * The day boundary is resolved in the viewer's local timezone, which is the one
 * they picked the date in.
 *
 * @param date - The selected date, or null when the range is open ended
 * @return The timestamp in seconds, or 0 when there is no lower bound
 */
export function startOfDayTimestamp(date: Date | null | undefined): number {
	if (!isValidDate(date)) {
		return 0
	}
	const start = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0, 0)
	return Math.floor(start.getTime() / 1000)
}

/**
 * Unix timestamp of the last second of the day the given date falls on.
 *
 * Rounding up to the end of the day is what makes an inclusive "to" bound
 * behave the way people read it: picking today as the end of the range has to
 * include everything that happened today, not just the midnight boundary.
 *
 * @param date - The selected date, or null when the range is open ended
 * @return The timestamp in seconds, or 0 when there is no upper bound
 */
export function endOfDayTimestamp(date: Date | null | undefined): number {
	if (!isValidDate(date)) {
		return 0
	}
	const end = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59, 999)
	return Math.floor(end.getTime() / 1000)
}

/**
 * Serialise a date for the URL query as a local `YYYY-MM-DD` string.
 *
 * Deliberately not `toISOString()`: that converts to UTC first and would shift
 * the date by a day for anyone east or west of Greenwich.
 *
 * @param date - The date to serialise
 * @return The formatted date, or an empty string when there is no date
 */
export function formatDateParameter(date: Date | null | undefined): string {
	if (!isValidDate(date)) {
		return ''
	}
	const year = String(date.getFullYear()).padStart(4, '0')
	const month = String(date.getMonth() + 1).padStart(2, '0')
	const day = String(date.getDate()).padStart(2, '0')
	return `${year}-${month}-${day}`
}

/**
 * Parse a `YYYY-MM-DD` string from the URL query back into a local date.
 *
 * Anything malformed yields null so a hand-edited or truncated URL degrades to
 * "no filter" instead of throwing during render.
 *
 * @param value - The raw query parameter
 * @return The parsed date, or null when it cannot be parsed
 */
export function parseDateParameter(value: unknown): Date | null {
	if (typeof value !== 'string') {
		return null
	}
	const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value)
	if (match === null) {
		return null
	}
	const year = Number(match[1])
	const month = Number(match[2]) - 1
	const day = Number(match[3])
	const date = new Date(year, month, day)
	// JavaScript silently rolls impossible dates over (2026-02-31 becomes March),
	// so verify the components survived the round trip
	if (date.getFullYear() !== year || date.getMonth() !== month || date.getDate() !== day) {
		return null
	}
	return date
}

/**
 * Local midnight, a given number of days before today.
 *
 * @param days - How many days to go back; 0 is the start of today
 * @return The resulting date
 */
export function daysAgo(days: number): Date {
	const date = new Date()
	date.setHours(0, 0, 0, 0)
	date.setDate(date.getDate() - days)
	return date
}

/**
 * Normalise a raw search input into something the API will accept.
 *
 * Terms below the minimum length are treated as "not searching yet" rather than
 * as an error, so typing the first character of a query does not produce a
 * failed request.
 *
 * @param value - The raw input value
 * @return The usable term, or an empty string when there is none
 */
export function normalizeSearchTerm(value: string): string {
	const term = value.trim()
	if (term.length < MIN_SEARCH_TERM_LENGTH) {
		return ''
	}
	return term.slice(0, MAX_SEARCH_TERM_LENGTH)
}
