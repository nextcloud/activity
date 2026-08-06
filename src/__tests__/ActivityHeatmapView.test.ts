/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { flushPromises, mount } from '@vue/test-utils'
import { beforeEach, describe, expect, it, vi } from 'vitest'
import ActivityHeatmapView from '../views/ActivityHeatmapView.vue'

const routerPush = vi.hoisted(() => vi.fn(() => Promise.resolve()))

vi.mock('vue-router', () => ({
	useRouter: vi.fn(() => ({ push: routerPush })),
}))

/**
 * Stands in for the grid so this file is about the view's own job: turning a
 * selection into a stream URL. The grid itself is covered by
 * ActivityHeatmap.test.ts.
 */
const HeatmapStub = {
	name: 'ActivityHeatmap',
	template: '<div class="activity-heatmap" />',
	props: ['filter', 'search', 'actor', 'from', 'to', 'days'],
	emits: ['update:from', 'update:to'],
}

const stubs = {
	ActivityHeatmap: HeatmapStub,
	NcAppContent: { template: '<div><slot /></div>' },
	NcButton: {
		template: '<button type="button" :disabled="disabled" @click="$emit(\'click\')"><slot /></button>',
		props: ['disabled', 'variant'],
	},
}

/** Mount the view. */
function mountView() {
	return mount(ActivityHeatmapView, { global: { stubs } })
}

describe('ActivityHeatmapView', () => {
	beforeEach(() => {
		vi.clearAllMocks()
	})

	it('counts against the unfiltered stream', () => {
		const wrapper = mountView()

		expect(wrapper.findComponent(HeatmapStub).props('filter')).toBe('all')
		wrapper.unmount()
	})

	it('offers nothing to open until a period is picked', () => {
		const wrapper = mountView()

		expect(wrapper.find('button').attributes('disabled')).toBeDefined()
		wrapper.unmount()
	})

	it('opens the stream restricted to the picked day', async () => {
		const wrapper = mountView()
		const grid = wrapper.findComponent(HeatmapStub)

		grid.vm.$emit('update:from', new Date(2024, 2, 5))
		grid.vm.$emit('update:to', new Date(2024, 2, 5))
		await flushPromises()
		await wrapper.find('button').trigger('click')

		// The stream restores its filters from the URL, so the range travels there
		expect(routerPush).toHaveBeenCalledWith({
			path: '/all',
			query: { from: '2024-03-05', to: '2024-03-05' },
		})
		wrapper.unmount()
	})

	it('carries a multi-day period across', async () => {
		const wrapper = mountView()
		const grid = wrapper.findComponent(HeatmapStub)

		grid.vm.$emit('update:from', new Date(2024, 2, 4))
		grid.vm.$emit('update:to', new Date(2024, 2, 10))
		await flushPromises()
		await wrapper.find('button').trigger('click')

		expect(routerPush).toHaveBeenCalledWith({
			path: '/all',
			query: { from: '2024-03-04', to: '2024-03-10' },
		})
		wrapper.unmount()
	})

	it('names the period on the action so the click is predictable', async () => {
		const wrapper = mountView()
		const grid = wrapper.findComponent(HeatmapStub)

		grid.vm.$emit('update:from', new Date(2024, 2, 4))
		grid.vm.$emit('update:to', new Date(2024, 2, 10))
		await flushPromises()

		const label = wrapper.find('button').text()
		expect(label).toContain('2024')
		expect(wrapper.find('button').attributes('disabled')).toBeUndefined()
		wrapper.unmount()
	})

	it('drops the selection without leaving the overview', async () => {
		const wrapper = mountView()
		const grid = wrapper.findComponent(HeatmapStub)

		grid.vm.$emit('update:from', new Date(2024, 2, 4))
		grid.vm.$emit('update:to', new Date(2024, 2, 10))
		await flushPromises()

		const clear = wrapper.findAll('button').at(-1)!
		await clear.trigger('click')
		await flushPromises()

		expect(grid.props('from')).toBeNull()
		expect(grid.props('to')).toBeNull()
		// Clearing is not navigation; the reader stays on the overview
		expect(routerPush).not.toHaveBeenCalled()
		expect(wrapper.find('button').attributes('disabled')).toBeDefined()
		wrapper.unmount()
	})

	it('offers no clear action when nothing is selected', () => {
		const wrapper = mountView()

		expect(wrapper.findAll('button')).toHaveLength(1)
		wrapper.unmount()
	})

	it('omits an unset bound rather than sending an empty one', async () => {
		const wrapper = mountView()
		const grid = wrapper.findComponent(HeatmapStub)

		grid.vm.$emit('update:from', new Date(2024, 2, 4))
		await flushPromises()
		await wrapper.find('button').trigger('click')

		expect(routerPush).toHaveBeenCalledWith({ path: '/all', query: { from: '2024-03-04' } })
		wrapper.unmount()
	})
})
