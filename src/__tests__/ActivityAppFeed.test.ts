/*!
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { VueWrapper } from '@vue/test-utils'

import { flushPromises, mount } from '@vue/test-utils'
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { nextTick } from 'vue'
import ActivityAppFeed from '../views/ActivityAppFeed.vue'
import wsData from '../__mocks__/@nextcloud/activity_ws.json'

// --- Module mocks ---

// vi.hoisted runs before any vi.mock factory, so this ref is available in the
// @vueuse/core factory below without vitest's hoisting causing a TDZ error.
// eslint-disable-next-line @typescript-eslint/no-require-imports
const visibilityRef = vi.hoisted(() => require('vue').ref('visible'))

vi.mock('@nextcloud/axios', () => ({
	default: { get: vi.fn() },
}))

vi.mock('@nextcloud/dialogs', () => ({ showError: vi.fn() }))

vi.mock('@nextcloud/initial-state', () => ({
	loadState: vi.fn(() => [
		{ id: 'all', name: 'All activities', url: '' },
		{ id: 'files', name: 'Files', url: '' },
	]),
}))

vi.mock(import('@nextcloud/router'), async (importOriginal) => {
	const actual = await importOriginal()
	return {
		...actual,
		generateOcsUrl: vi.fn((template: string, params: Record<string, string>) => Object.entries(params).reduce(
			(url, [k, v]) => url.replace(`{${k}}`, String(v)),
			`/ocs/${template}`,
		)),
	}
})

const routerReplace = vi.hoisted(() => vi.fn(() => Promise.resolve()))
const routeQuery = vi.hoisted(() => ({ current: {} as Record<string, string> }))

vi.mock('vue-router', () => ({
	useRoute: vi.fn(() => ({ params: { filter: 'all' }, query: routeQuery.current })),
	useRouter: vi.fn(() => ({ replace: routerReplace })),
}))

vi.mock(import('@vueuse/core'), async (importOriginal) => {
	const actual = await importOriginal()
	return {
		...actual,
		useDocumentVisibility: () => visibilityRef,
		// No-op: prevents scroll-triggered loads from firing automatically in tests
		useInfiniteScroll: vi.fn(),
		// Remove debounce so onScroll fires synchronously in tests
		useDebounceFn: vi.fn((fn: (...args: unknown[]) => unknown) => fn),
	}
})

// Imported after mocks are registered
import ncAxios from '@nextcloud/axios'
import { showError } from '@nextcloud/dialogs'

// --- Constants ---

const POLL_INTERVAL = 30_000

// --- Factories ---

function makeActivityEntry(id: number, datetime = '2024-01-01T12:00:00+00:00') {
	return {
		activity_id: id,
		app: 'files',
		type: 'file_created',
		user: 'admin',
		subject: `Activity ${id}`,
		subject_rich: [`Activity ${id}`, []],
		message: '',
		message_rich: ['', []],
		object_type: 'files',
		object_id: id,
		object_name: `/file-${id}.md`,
		objects: { [id]: `/file-${id}.md` },
		link: '',
		icon: 'http://localhost/apps/files/img/add-color.svg',
		datetime,
	}
}

function makeResponse(activities = wsData.ocs.data, lastGiven = '25') {
	return {
		data: { ocs: { data: activities } },
		headers: { 'x-activity-last-given': lastGiven },
	}
}

function make304Error() {
	const err = new Error('Not Modified') as Error & {
		isAxiosError: boolean
		response: { status: number }
	}
	err.isAxiosError = true
	err.response = { status: 304 }
	return err
}

// --- Component stubs ---

/**
 * Stands in for the real filter bar so feed level tests can drive the filter
 * state directly. The bar itself is covered by ActivityFilterBar.test.ts.
 */
const FilterBarStub = {
	name: 'ActivityFilterBar',
	template: '<div class="activity-filter-bar" />',
	props: ['search', 'from', 'to', 'actor', 'actorOptions'],
	emits: ['update:search', 'update:from', 'update:to', 'update:actor'],
}

const stubs = {
	ActivityFilterBar: FilterBarStub,
	NcAppContent: { template: '<div><slot /></div>' },
	NcEmptyContent: {
		template: '<div :data-name="name"><slot name="icon" /><slot name="action" /></div>',
		props: ['name', 'description'],
	},
	NcButton: {
		template: '<button type="button" @click="$emit(\'click\')"><slot /></button>',
	},
	NcLoadingIcon: true,
	NcIconSvgWrapper: true,
	ActivityGroup: { template: '<div class="activity-group" />' },
}

// --- Mount helper ---

/**
 * Mount the component and flush the initial load to completion.
 * The first axios call returns the fixture data; the second (triggered by the
 * non-scrollable container nextTick check) returns 304 to stop pagination.
 */
async function mountFeed(filter = 'all'): Promise<VueWrapper> {
	vi.mocked(ncAxios.get)
		.mockResolvedValueOnce(makeResponse())
		.mockRejectedValueOnce(make304Error())

	const wrapper = mount(ActivityAppFeed, {
		props: { filter },
		global: { stubs },
	})
	await flushPromises()
	return wrapper
}

// --- Tests ---

describe('ActivityAppFeed', () => {
	beforeEach(() => {
		// Exclude setImmediate from fake timers so flushPromises() keeps working
		vi.useFakeTimers({ toFake: ['setTimeout', 'setInterval', 'clearTimeout', 'clearInterval'] })
		visibilityRef.value = 'visible'
		routeQuery.current = {}
	})

	afterEach(() => {
		vi.useRealTimers()
		vi.clearAllMocks()
	})

	// ---------------------------------------------------------------------------
	describe('initial load', () => {
		it('renders an ActivityGroup for each date after loading', async () => {
			const wrapper = await mountFeed()
			expect(wrapper.findAll('.activity-group').length).toBeGreaterThan(0)
			wrapper.unmount()
		})

		it('shows loading empty state while the first request is in flight', async () => {
			// Never resolves — request stays in flight
			vi.mocked(ncAxios.get).mockReturnValueOnce(new Promise(() => {}))

			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await nextTick()

			expect(wrapper.find('[data-name="Loading activities"]').exists()).toBe(true)
			expect(wrapper.find('[data-name="No activity yet"]').exists()).toBe(false)
			wrapper.unmount()
		})

		it('shows "No activity yet" when the stream is empty (304)', async () => {
			vi.mocked(ncAxios.get).mockRejectedValue(make304Error())

			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await flushPromises()

			expect(wrapper.find('[data-name="No activity yet"]').exists()).toBe(true)
			wrapper.unmount()
		})

		it('calls showError on unexpected network failure', async () => {
			vi.mocked(ncAxios.get).mockRejectedValue(new Error('Network error'))

			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await flushPromises()

			expect(showError).toHaveBeenCalledOnce()
			wrapper.unmount()
		})
	})

	// ---------------------------------------------------------------------------
	describe('polling', () => {
		it('prepends new activities after the poll interval elapses', async () => {
			const wrapper = await mountFeed()
			const countBefore = wrapper.findAll('.activity-group').length

			vi.mocked(ncAxios.get).mockResolvedValueOnce(makeResponse([makeActivityEntry(100)], '100'))
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()

			expect(wrapper.findAll('.activity-group').length).toBeGreaterThan(countBefore)
			wrapper.unmount()
		})

		it('polls with since=0 when no activities were loaded initially', async () => {
			vi.mocked(ncAxios.get).mockRejectedValue(make304Error())
			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await flushPromises()

			vi.mocked(ncAxios.get).mockClear()
			vi.mocked(ncAxios.get).mockResolvedValueOnce(makeResponse([makeActivityEntry(1)], '1'))
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()

			const pollUrl = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			expect(pollUrl).toContain('since=0')
			wrapper.unmount()
		})

		it('does not fire overlapping poll requests', async () => {
			const wrapper = await mountFeed()
			vi.mocked(ncAxios.get).mockClear()

			// Slow poll that never resolves during this test
			let resolvePoll!: (v: unknown) => void
			vi.mocked(ncAxios.get).mockReturnValueOnce(new Promise((resolve) => {
				resolvePoll = resolve
			}))

			vi.advanceTimersByTime(POLL_INTERVAL)
			// pollNewActivities is now suspended at await ncAxios.get.
			// A second interval passes — no new timer has been scheduled yet
			// because the self-scheduling setTimeout is in the finally block.
			vi.advanceTimersByTime(POLL_INTERVAL)
			await nextTick()

			expect(vi.mocked(ncAxios.get)).toHaveBeenCalledTimes(1)

			resolvePoll(makeResponse([makeActivityEntry(100)], '100'))
			await flushPromises()
			wrapper.unmount()
		})

		it('shows "New activities" button when new items arrive and user is scrolled down', async () => {
			const wrapper = await mountFeed()
			Object.defineProperty(
				wrapper.find('.activity-app__container').element,
				'scrollTop',
				{ value: 200, configurable: true },
			)

			vi.mocked(ncAxios.get).mockResolvedValueOnce(makeResponse([makeActivityEntry(100)], '100'))
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()

			expect(wrapper.find('.activity-app__new-activities-indicator').exists()).toBe(true)
			wrapper.unmount()
		})

		it('does not show "New activities" button when user is already near the top', async () => {
			const wrapper = await mountFeed()
			Object.defineProperty(
				wrapper.find('.activity-app__container').element,
				'scrollTop',
				{ value: 0, configurable: true },
			)

			vi.mocked(ncAxios.get).mockResolvedValueOnce(makeResponse([makeActivityEntry(100)], '100'))
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()

			expect(wrapper.find('.activity-app__new-activities-indicator').exists()).toBe(false)
			wrapper.unmount()
		})

		it('stops polling when the tab becomes hidden and resumes when visible', async () => {
			const wrapper = await mountFeed()

			visibilityRef.value = 'hidden'
			await nextTick()

			vi.mocked(ncAxios.get).mockClear()
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()
			expect(vi.mocked(ncAxios.get)).not.toHaveBeenCalled()

			visibilityRef.value = 'visible'
			await nextTick()
			vi.mocked(ncAxios.get).mockResolvedValueOnce(makeResponse([makeActivityEntry(100)], '100'))
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()
			expect(vi.mocked(ncAxios.get)).toHaveBeenCalledOnce()

			wrapper.unmount()
		})
	})

	// ---------------------------------------------------------------------------
	describe('"New activities" button', () => {
		/** Mount a feed and advance the poll timer so the button appears. */
		async function mountWithButton(): Promise<VueWrapper> {
			const wrapper = await mountFeed()
			Object.defineProperty(
				wrapper.find('.activity-app__container').element,
				'scrollTop',
				{ value: 200, configurable: true },
			)
			vi.mocked(ncAxios.get).mockResolvedValueOnce(makeResponse([makeActivityEntry(100)], '100'))
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()
			return wrapper
		}

		it('hides and smooth-scrolls to top on click', async () => {
			const wrapper = await mountWithButton()
			const scrollTo = vi.fn()
			Object.defineProperty(
				wrapper.find('.activity-app__container').element,
				'scrollTo',
				{ value: scrollTo, configurable: true },
			)

			await wrapper.find('.activity-app__new-activities-indicator').trigger('click')

			expect(wrapper.find('.activity-app__new-activities-indicator').exists()).toBe(false)
			expect(scrollTo).toHaveBeenCalledWith({ top: 0, behavior: 'smooth' })
			wrapper.unmount()
		})

		it('auto-hides when the user scrolls back near the top', async () => {
			const wrapper = await mountWithButton()
			const container = wrapper.find('.activity-app__container')

			Object.defineProperty(container.element, 'scrollTop', {
				value: 10,
				configurable: true,
			})
			await container.trigger('scroll')

			expect(wrapper.find('.activity-app__new-activities-indicator').exists()).toBe(false)
			wrapper.unmount()
		})
	})

	// ---------------------------------------------------------------------------
	describe('filter change', () => {
		it('resets activities and reloads when the filter prop changes', async () => {
			const wrapper = await mountFeed()
			expect(wrapper.findAll('.activity-group').length).toBeGreaterThan(0)

			vi.mocked(ncAxios.get).mockRejectedValue(make304Error())
			await wrapper.setProps({ filter: 'files' })
			await flushPromises()

			expect(wrapper.find('[data-name="No activity yet"]').exists()).toBe(true)
			wrapper.unmount()
		})

		it('shows loading state (not "No activity yet") immediately after filter change', async () => {
			const wrapper = await mountFeed()

			vi.mocked(ncAxios.get).mockReturnValueOnce(new Promise(() => {}))
			await wrapper.setProps({ filter: 'files' })
			await nextTick()

			expect(wrapper.find('[data-name="Loading activities"]').exists()).toBe(true)
			expect(wrapper.find('[data-name="No activity yet"]').exists()).toBe(false)
			wrapper.unmount()
		})

		it('keeps search, date range and account out of the picture when nothing is set', async () => {
			const wrapper = await mountFeed()
			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string

			expect(url).not.toContain('search=')
			expect(url).not.toContain('from=')
			expect(url).not.toContain('to=')
			expect(url).not.toContain('actor=')
			wrapper.unmount()
		})

		it('aborts the in-flight load request when the filter changes', async () => {
			let capturedSignal: AbortSignal | undefined
			vi.mocked(ncAxios.get).mockImplementation((_url, config) => {
				capturedSignal = (config as RequestInit | undefined)?.signal as AbortSignal | undefined
				return new Promise(() => {}) // never resolves
			})

			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await nextTick()

			expect(capturedSignal?.aborted).toBe(false)

			vi.mocked(ncAxios.get)
				.mockResolvedValueOnce(makeResponse())
				.mockRejectedValueOnce(make304Error())
			await wrapper.setProps({ filter: 'files' })
			await flushPromises()

			expect(capturedSignal?.aborted).toBe(true)
			wrapper.unmount()
		})
	})

	// ---------------------------------------------------------------------------
	describe('search and date range', () => {
		/** Emit a filter update from the stubbed filter bar and settle the reload. */
		async function setFilter(wrapper: VueWrapper, event: string, value: unknown) {
			vi.mocked(ncAxios.get).mockClear()
			vi.mocked(ncAxios.get).mockRejectedValue(make304Error())
			wrapper.findComponent(FilterBarStub).vm.$emit(event, value)
			await flushPromises()
		}

		it('sends the search term as a query parameter', async () => {
			const wrapper = await mountFeed()
			await setFilter(wrapper, 'update:search', 'quarterly report')

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			// URLSearchParams encodes the space rather than breaking the query
			expect(url).toContain('search=quarterly+report')
			wrapper.unmount()
		})

		it('sends the range start as the first second of that day', async () => {
			const wrapper = await mountFeed()
			const from = new Date(2024, 0, 15)
			await setFilter(wrapper, 'update:from', from)

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			const expected = Math.floor(new Date(2024, 0, 15, 0, 0, 0, 0).getTime() / 1000)
			expect(url).toContain(`from=${expected}`)
			wrapper.unmount()
		})

		it('sends the range end as the last second of that day so the day is included', async () => {
			const wrapper = await mountFeed()
			const to = new Date(2024, 0, 15)
			await setFilter(wrapper, 'update:to', to)

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			const expected = Math.floor(new Date(2024, 0, 15, 23, 59, 59, 999).getTime() / 1000)
			expect(url).toContain(`to=${expected}`)
			wrapper.unmount()
		})

		it('discards already loaded activities when the search changes', async () => {
			const wrapper = await mountFeed()
			expect(wrapper.findAll('.activity-group').length).toBeGreaterThan(0)

			await setFilter(wrapper, 'update:search', 'nothing matches this')

			expect(wrapper.findAll('.activity-group').length).toBe(0)
			wrapper.unmount()
		})

		it('shows a filter specific empty state that can clear the filters', async () => {
			const wrapper = await mountFeed()
			await setFilter(wrapper, 'update:search', 'nothing matches this')

			expect(wrapper.find('[data-name="No matching activities"]').exists()).toBe(true)
			expect(wrapper.find('[data-name="No activity yet"]').exists()).toBe(false)

			vi.mocked(ncAxios.get).mockClear()
			vi.mocked(ncAxios.get)
				.mockResolvedValueOnce(makeResponse())
				.mockRejectedValueOnce(make304Error())
			await wrapper.find('[data-name="No matching activities"] button').trigger('click')
			await flushPromises()

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			expect(url).not.toContain('search=')
			expect(wrapper.find('[data-name="No matching activities"]').exists()).toBe(false)
			wrapper.unmount()
		})

		it('carries the active filters into the polling request', async () => {
			const wrapper = await mountFeed()
			await setFilter(wrapper, 'update:search', 'report')

			vi.mocked(ncAxios.get).mockClear()
			vi.mocked(ncAxios.get).mockResolvedValueOnce(makeResponse([makeActivityEntry(100)], '100'))
			vi.advanceTimersByTime(POLL_INTERVAL)
			await flushPromises()

			const pollUrl = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			expect(pollUrl).toContain('sort=asc')
			expect(pollUrl).toContain('search=report')
			wrapper.unmount()
		})

		it('restores the filters from the URL on mount', async () => {
			routeQuery.current = { search: 'budget', from: '2024-01-15' }
			vi.mocked(ncAxios.get).mockRejectedValue(make304Error())

			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await flushPromises()

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			const expected = Math.floor(new Date(2024, 0, 15, 0, 0, 0, 0).getTime() / 1000)
			expect(url).toContain('search=budget')
			expect(url).toContain(`from=${expected}`)
			wrapper.unmount()
		})

		it('ignores an unparseable date in the URL instead of failing to render', async () => {
			routeQuery.current = { from: 'not-a-date' }
			vi.mocked(ncAxios.get).mockRejectedValue(make304Error())

			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await flushPromises()

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			expect(url).not.toContain('from=')
			expect(wrapper.find('[data-name="No activity yet"]').exists()).toBe(true)
			wrapper.unmount()
		})

		it('sends the selected account as a query parameter', async () => {
			const wrapper = await mountFeed()
			await setFilter(wrapper, 'update:actor', 'alice')

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			expect(url).toContain('actor=alice')
			wrapper.unmount()
		})

		it('offers the accounts behind the loaded activities as options', async () => {
			const wrapper = await mountFeed()

			const options = wrapper.findComponent(FilterBarStub).props('actorOptions') as { id: string }[]
			expect(options.length).toBeGreaterThan(0)
			expect(options.every((option) => typeof option.id === 'string')).toBe(true)
			wrapper.unmount()
		})

		it('keeps the other accounts selectable after filtering to one', async () => {
			const wrapper = await mountFeed()
			const before = (wrapper.findComponent(FilterBarStub).props('actorOptions') as unknown[]).length

			await setFilter(wrapper, 'update:actor', 'alice')

			// The stream now holds only one account, but the list must not shrink
			// or there would be no way back to the others
			const after = (wrapper.findComponent(FilterBarStub).props('actorOptions') as unknown[]).length
			expect(after).toBe(before)
			wrapper.unmount()
		})

		it('restores the account filter from the URL on mount', async () => {
			routeQuery.current = { actor: 'alice' }
			vi.mocked(ncAxios.get).mockRejectedValue(make304Error())

			const wrapper = mount(ActivityAppFeed, { global: { stubs } })
			await flushPromises()

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			expect(url).toContain('actor=alice')
			wrapper.unmount()
		})

		it('clears the account along with the other filters', async () => {
			const wrapper = await mountFeed()
			await setFilter(wrapper, 'update:actor', 'alice')
			expect(wrapper.find('[data-name="No matching activities"]').exists()).toBe(true)

			vi.mocked(ncAxios.get).mockClear()
			vi.mocked(ncAxios.get)
				.mockResolvedValueOnce(makeResponse())
				.mockRejectedValueOnce(make304Error())
			await wrapper.find('[data-name="No matching activities"] button').trigger('click')
			await flushPromises()

			const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
			expect(url).not.toContain('actor=')
			wrapper.unmount()
		})

		it('mirrors the active filters into the URL', async () => {
			const wrapper = await mountFeed()
			routerReplace.mockClear()
			await setFilter(wrapper, 'update:search', 'report')

			expect(routerReplace).toHaveBeenCalledWith({ query: { search: 'report' } })
			wrapper.unmount()
		})
	})
})
