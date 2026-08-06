/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { VueWrapper } from '@vue/test-utils'

import ncAxios from '@nextcloud/axios'
import { flushPromises, mount } from '@vue/test-utils'
import { beforeEach, describe, expect, it, vi } from 'vitest'
import ActivityHeatmap from '../components/ActivityHeatmap.vue'

vi.mock('@nextcloud/axios', () => ({ default: { get: vi.fn() } }))

vi.mock(import('@nextcloud/router'), async (importOriginal) => {
	const actual = await importOriginal()
	return {
		...actual,
		generateOcsUrl: vi.fn((template: string, params: Record<string, string> = {}) => Object.entries(params).reduce(
			(url, [key, value]) => url.replace(`{${key}}`, String(value)),
			`/ocs/${template}`,
		)),
	}
})

const stubs = {
	NcButton: { template: '<button type="button" @click="$emit(\'click\')"><slot /></button>' },
}

/**
 * Build a histogram response.
 *
 * @param counts - Activity counts keyed by date
 * @param options - Window and scale overrides
 */
function makeHistogram(
	counts: Record<string, number>,
	options: { from?: string, to?: string, max?: number, partialBefore?: string | null } = {},
) {
	const values = Object.values(counts)
	return {
		data: {
			ocs: {
				meta: { status: 'ok', statuscode: 200 },
				data: {
					from: options.from ?? '2024-03-01',
					to: options.to ?? '2024-03-14',
					counts,
					max: options.max ?? (values.length === 0 ? 0 : Math.max(...values)),
					total: values.reduce((sum, value) => sum + value, 0),
					partial_before: options.partialBefore ?? null,
				},
			},
		},
	}
}

/**
 * Mount with a histogram already loaded.
 *
 * @param response - The histogram payload
 * @param props - Additional props
 */
async function mountHeatmap(response: unknown = makeHistogram({ '2024-03-05': 4 }), props: Record<string, unknown> = {}) {
	vi.mocked(ncAxios.get).mockResolvedValue(response as never)
	const wrapper = mount(ActivityHeatmap, {
		props: { filter: 'all', ...props },
		global: { stubs },
	})
	await flushPromises()
	return wrapper
}

/** Every rendered day cell, in DOM order. */
function cells(wrapper: VueWrapper) {
	return wrapper.findAll('button.activity-heatmap__cell')
}

/**
 * Find a cell by its date.
 *
 * @param wrapper - The mounted component
 * @param date - Date to look for, YYYY-MM-DD
 */
function cellFor(wrapper: VueWrapper, date: string) {
	return wrapper.get(`button.activity-heatmap__cell[data-date="${date}"]`)
}

/** Date of the single cell currently exposed to Tab. */
function rovingDate(wrapper: VueWrapper): string | undefined {
	return cells(wrapper).find((cell) => cell.attributes('tabindex') === '0')?.attributes('data-date')
}

describe('ActivityHeatmap', () => {
	beforeEach(() => {
		vi.clearAllMocks()
	})

	describe('loading', () => {
		it('asks for the histogram of the active filter', async () => {
			const wrapper = await mountHeatmap()

			const [url, config] = vi.mocked(ncAxios.get).mock.calls[0]!
			expect(url).toContain('/activity/all/histogram')
			expect((config as any).params.days).toBe(364)
			wrapper.unmount()
		})

		it('scopes the counts by the active search and account', async () => {
			const wrapper = await mountHeatmap(makeHistogram({}), { search: 'report', actor: 'alice' })

			const [, config] = vi.mocked(ncAxios.get).mock.calls[0]!
			expect((config as any).params.search).toBe('report')
			expect((config as any).params.actor).toBe('alice')
			wrapper.unmount()
		})

		it('leaves search and account out entirely when unset', async () => {
			const wrapper = await mountHeatmap()

			const [, config] = vi.mocked(ncAxios.get).mock.calls[0]!
			expect((config as any).params.search).toBeUndefined()
			expect((config as any).params.actor).toBeUndefined()
			wrapper.unmount()
		})

		it('reloads when the filter changes and abandons the previous request', async () => {
			const signals: (AbortSignal | undefined)[] = []
			vi.mocked(ncAxios.get).mockImplementation((_url, config) => {
				signals.push((config as any)?.signal)
				return new Promise(() => {}) as never
			})

			const wrapper = mount(ActivityHeatmap, { props: { filter: 'all' }, global: { stubs } })
			await flushPromises()
			await wrapper.setProps({ filter: 'files' })
			await flushPromises()

			expect(signals).toHaveLength(2)
			expect(signals[0]?.aborted).toBe(true)
			expect(signals[1]?.aborted).toBe(false)
			wrapper.unmount()
		})

		it('leaves the grid empty rather than throwing when the request fails', async () => {
			vi.mocked(ncAxios.get).mockRejectedValue(new Error('boom') as never)
			const wrapper = mount(ActivityHeatmap, { props: { filter: 'all' }, global: { stubs } })
			await flushPromises()

			expect(cells(wrapper)).toHaveLength(0)
			expect(wrapper.find('.activity-heatmap').exists()).toBe(true)
			wrapper.unmount()
		})
	})

	describe('rendering', () => {
		it('renders one cell per day in the window', async () => {
			const wrapper = await mountHeatmap(makeHistogram({}, { from: '2024-03-01', to: '2024-03-14' }))

			// 14 inclusive days
			expect(cells(wrapper)).toHaveLength(14)
			wrapper.unmount()
		})

		it('puts the exact count and date in each cell name, not just the colour', async () => {
			const wrapper = await mountHeatmap(makeHistogram({ '2024-03-05': 4 }))

			const cell = cellFor(wrapper, '2024-03-05')
			expect(cell.attributes('aria-label')).toContain('4')
			expect(cell.attributes('aria-label')).toContain('2024')
			wrapper.unmount()
		})

		it('steps the colour class with the count', async () => {
			const wrapper = await mountHeatmap(makeHistogram(
				{ '2024-03-02': 1, '2024-03-04': 5, '2024-03-06': 10, '2024-03-08': 20 },
				{ max: 20 },
			))

			expect(cellFor(wrapper, '2024-03-01').classes()).toContain('activity-heatmap__cell--level-0')
			expect(cellFor(wrapper, '2024-03-02').classes()).toContain('activity-heatmap__cell--level-1')
			expect(cellFor(wrapper, '2024-03-06').classes()).toContain('activity-heatmap__cell--level-2')
			expect(cellFor(wrapper, '2024-03-08').classes()).toContain('activity-heatmap__cell--level-4')
			wrapper.unmount()
		})

		it('marks days whose counts are known to be incomplete', async () => {
			const wrapper = await mountHeatmap(makeHistogram({}, { partialBefore: '2024-03-05' }))

			expect(cellFor(wrapper, '2024-03-02').classes()).toContain('activity-heatmap__cell--partial')
			expect(cellFor(wrapper, '2024-03-06').classes()).not.toContain('activity-heatmap__cell--partial')
			wrapper.unmount()
		})

		it('ships a legend so the scale is not colour-only guesswork', async () => {
			const wrapper = await mountHeatmap()

			expect(wrapper.find('.activity-heatmap__legend').exists()).toBe(true)
			// One swatch per step plus the empty state
			expect(wrapper.findAll('.activity-heatmap__legend .activity-heatmap__cell')).toHaveLength(5)
			wrapper.unmount()
		})

		it('is a real table, so the values have a non-visual twin', async () => {
			const wrapper = await mountHeatmap()

			expect(wrapper.find('table caption').exists()).toBe(true)
			expect(wrapper.findAll('th[scope="row"]').length).toBe(7)
			wrapper.unmount()
		})
	})

	describe('selecting a range', () => {
		it('filters to a single day when one is picked', async () => {
			const wrapper = await mountHeatmap()

			await cellFor(wrapper, '2024-03-05').trigger('click')

			const from = wrapper.emitted('update:from')!.at(-1)![0] as Date
			const to = wrapper.emitted('update:to')!.at(-1)![0] as Date
			expect(from.getDate()).toBe(5)
			expect(to.getDate()).toBe(5)
			wrapper.unmount()
		})

		it('extends the range from the anchor when shift is held', async () => {
			const wrapper = await mountHeatmap()

			await cellFor(wrapper, '2024-03-05').trigger('click')
			await cellFor(wrapper, '2024-03-09').trigger('click', { shiftKey: true })

			const from = wrapper.emitted('update:from')!.at(-1)![0] as Date
			const to = wrapper.emitted('update:to')!.at(-1)![0] as Date
			expect(from.getDate()).toBe(5)
			expect(to.getDate()).toBe(9)
			wrapper.unmount()
		})

		it('orders the range even when shift-clicking backwards', async () => {
			const wrapper = await mountHeatmap()

			await cellFor(wrapper, '2024-03-09').trigger('click')
			await cellFor(wrapper, '2024-03-05').trigger('click', { shiftKey: true })

			const from = wrapper.emitted('update:from')!.at(-1)![0] as Date
			const to = wrapper.emitted('update:to')!.at(-1)![0] as Date
			expect(from.getDate()).toBe(5)
			expect(to.getDate()).toBe(9)
			wrapper.unmount()
		})

		it('clears the filter when the only selected day is picked again', async () => {
			const day = new Date(2024, 2, 5)
			const wrapper = await mountHeatmap(makeHistogram({ '2024-03-05': 4 }), { from: day, to: day })

			await cellFor(wrapper, '2024-03-05').trigger('click')

			// Otherwise the reader is stuck in a one-day view with no way back
			expect(wrapper.emitted('update:from')!.at(-1)![0]).toBeNull()
			expect(wrapper.emitted('update:to')!.at(-1)![0]).toBeNull()
			wrapper.unmount()
		})

		it('marks the selected days with a ring rather than recolouring them', async () => {
			const wrapper = await mountHeatmap(
				makeHistogram({ '2024-03-05': 4 }),
				{ from: new Date(2024, 2, 4), to: new Date(2024, 2, 6) },
			)

			const selected = cellFor(wrapper, '2024-03-05')
			expect(selected.classes()).toContain('activity-heatmap__cell--selected')
			// The value class is untouched, so the ring cannot misstate the count
			expect(selected.classes()).toContain('activity-heatmap__cell--level-4')
			expect(selected.attributes('aria-pressed')).toBe('true')
			expect(cellFor(wrapper, '2024-03-08').classes()).not.toContain('activity-heatmap__cell--selected')
			wrapper.unmount()
		})
	})

	describe('keyboard', () => {
		it('exposes exactly one cell to Tab, so the grid is not 182 stops', async () => {
			const wrapper = await mountHeatmap()

			const tabbable = cells(wrapper).filter((cell) => cell.attributes('tabindex') === '0')
			expect(tabbable).toHaveLength(1)
			wrapper.unmount()
		})

		it('starts the keyboard path on the most recent day', async () => {
			const wrapper = await mountHeatmap(makeHistogram({}, { from: '2024-03-01', to: '2024-03-14' }))

			expect(rovingDate(wrapper)).toBe('2024-03-14')
			wrapper.unmount()
		})

		it('moves a day at a time vertically and a week horizontally', async () => {
			const wrapper = await mountHeatmap(makeHistogram({}, { from: '2024-03-01', to: '2024-03-20' }))
			const start = cellFor(wrapper, '2024-03-10')

			await start.trigger('focus')
			await start.trigger('keydown', { key: 'ArrowDown' })
			expect(rovingDate(wrapper)).toBe('2024-03-11')

			await cellFor(wrapper, '2024-03-11').trigger('keydown', { key: 'ArrowRight' })
			// One column right is the same weekday a week later
			expect(rovingDate(wrapper)).toBe('2024-03-18')
			wrapper.unmount()
		})

		it('jumps to the ends of the window with Home and End', async () => {
			const wrapper = await mountHeatmap(makeHistogram({}, { from: '2024-03-01', to: '2024-03-20' }))
			const start = cellFor(wrapper, '2024-03-10')

			await start.trigger('keydown', { key: 'Home' })
			expect(rovingDate(wrapper)).toBe('2024-03-01')

			await cellFor(wrapper, '2024-03-01').trigger('keydown', { key: 'End' })
			expect(rovingDate(wrapper)).toBe('2024-03-20')
			wrapper.unmount()
		})

		it('does not walk past the start of the window', async () => {
			const wrapper = await mountHeatmap(makeHistogram({}, { from: '2024-03-01', to: '2024-03-20' }))
			const first = cellFor(wrapper, '2024-03-01')

			await first.trigger('focus')
			await first.trigger('keydown', { key: 'ArrowUp' })

			// Focus stays put rather than moving to a cell that does not exist
			expect(rovingDate(wrapper)).toBe('2024-03-01')
			wrapper.unmount()
		})

		it('shows the same readout on focus as on hover', async () => {
			const wrapper = await mountHeatmap(makeHistogram({ '2024-03-05': 4 }))

			await cellFor(wrapper, '2024-03-05').trigger('focus')

			const tooltip = wrapper.find('.activity-heatmap__tooltip')
			expect(tooltip.exists()).toBe(true)
			expect(tooltip.text()).toContain('4')
			wrapper.unmount()
		})
	})
})
