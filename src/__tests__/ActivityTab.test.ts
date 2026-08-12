/*!
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { VueWrapper } from '@vue/test-utils'

import axios from '@nextcloud/axios'
import { flushPromises, mount } from '@vue/test-utils'
import { afterEach, describe, expect, test, vi } from 'vitest'
import { nextTick } from 'vue'
import ActivityTab from '../views/ActivityTab.vue'

vi.mock('@nextcloud/axios')

const ACTIVITY_PATH = '/ocs/v2.php/apps/activity/api/v2/activity/filter'

/**
 * Build an OCS response holding one activity per given id.
 *
 * @param ids - Activity ids to include, used to tell responses apart
 * @param options - Pagination headers the server would send alongside
 */
function responseWithIds(ids: number[], options: { hasMore?: boolean } = {}) {
	return {
		headers: {
			'x-activity-last-given': String(ids.at(-1) ?? ''),
			// The API only sets a next page link while more activities remain
			...(options.hasMore ? { link: '<http://localhost/next>; rel="next"' } : {}),
		},
		data: {
			ocs: {
				meta: { status: 'ok', statuscode: 200, message: 'OK' },
				data: ids.map((id) => ({
					activity_id: id,
					app: 'files',
					type: 'file_changed',
					user: 'admin',
					subject: `Activity ${id}`,
					subject_rich: [`Activity ${id}`, []],
					message: '',
					message_rich: ['', []],
					icon: 'icon.svg',
					datetime: '2021-04-29T13:57:11+00:00',
					object_type: 'files',
					object_id: id,
					object_name: '',
					objects: {},
					link: '',
				})),
			},
		},
	}
}

/** Requests the component made for the activity list, newest last. */
function activityCalls(spy: ReturnType<typeof vi.spyOn>) {
	return spy.mock.calls.filter(([url]) => String(url).endsWith(ACTIVITY_PATH))
}

test('Create ActivityTab', async () => {
	const wrapper = mount(ActivityTab, {
		props: {
			node: { id: 'test' } as any,
		},
	})

	await new Promise<void>((resolve) => waitFor('ul', wrapper, resolve))

	await nextTick()
	expect(wrapper.vm.$data.activities.length).toBe(18)
})

test('DownloadSummary is hidden when node has no public link', async () => {
	const wrapper = mount(ActivityTab, {
		props: {
			node: { id: 'test', fileid: 42 } as any,
		},
	})
	await nextTick()
	expect(wrapper.findComponent({ name: 'DownloadSummary' }).exists()).toBe(false)
})

test('DownloadSummary is shown when node has a public link share', async () => {
	const wrapper = mount(ActivityTab, {
		props: {
			node: { id: 'test', fileid: 42, attributes: { 'share-types': [3] } } as any,
		},
	})
	await nextTick()
	expect(wrapper.findComponent({ name: 'DownloadSummary' }).exists()).toBe(true)
})

function waitFor(query: string, wrapper: VueWrapper, callback: () => void) {
	if (wrapper.find(query).exists()) {
		return callback()
	}
	nextTick(() => waitFor(query, wrapper, callback))
}

describe('request handling', () => {
	afterEach(() => {
		vi.restoreAllMocks()
	})

	test('loads the activity list once per opened file, not twice', async () => {
		const get = vi.spyOn(axios, 'get')

		const wrapper = mount(ActivityTab, {
			props: { node: { id: 'test', fileid: 42 } as any },
		})
		await flushPromises()

		// The node watcher is immediate, so a mounted() hook loading as well
		// would double every request the panel makes
		expect(activityCalls(get)).toHaveLength(1)
		wrapper.unmount()
	})

	test('asks only for the newly selected file when the selection changes', async () => {
		const get = vi.spyOn(axios, 'get')

		const wrapper = mount(ActivityTab, {
			props: { node: { id: 'a', fileid: 1 } as any },
		})
		await flushPromises()
		await wrapper.setProps({ node: { id: 'b', fileid: 2 } as any })
		await flushPromises()

		expect(activityCalls(get)).toHaveLength(2)
		expect(activityCalls(get).map(([, config]) => (config as any).params.object_id)).toEqual([1, 2])
		wrapper.unmount()
	})

	test('keeps the current file’s activity when an earlier response arrives late', async () => {
		const pending: { resolve: (value: unknown) => void, objectId: number }[] = []
		vi.spyOn(axios, 'get').mockImplementation((url: string, config?: any) => {
			return new Promise((resolve) => {
				pending.push({ resolve, objectId: config?.params?.object_id })
			})
		})

		const wrapper = mount(ActivityTab, {
			props: { node: { id: 'a', fileid: 1 } as any },
		})
		await flushPromises()
		await wrapper.setProps({ node: { id: 'b', fileid: 2 } as any })
		await flushPromises()

		expect(pending.map((request) => request.objectId)).toEqual([1, 2])

		// Answer the current file first, then let the superseded request land
		pending[1]!.resolve(responseWithIds([200]))
		await flushPromises()
		pending[0]!.resolve(responseWithIds([100, 101]))
		await flushPromises()

		const shown = (wrapper.vm.$data.activities as { id: number }[]).map((activity) => activity.id)
		expect(shown).toEqual([200])
		wrapper.unmount()
	})

	test('aborts the superseded request instead of leaving it running', async () => {
		const signals: (AbortSignal | undefined)[] = []
		vi.spyOn(axios, 'get').mockImplementation((url: string, config?: any) => {
			signals.push(config?.signal)
			return new Promise(() => {})
		})

		const wrapper = mount(ActivityTab, {
			props: { node: { id: 'a', fileid: 1 } as any },
		})
		await flushPromises()
		await wrapper.setProps({ node: { id: 'b', fileid: 2 } as any })
		await flushPromises()

		expect(signals[0]?.aborted).toBe(true)
		expect(signals[1]?.aborted).toBe(false)

		// Unmounting has to release the last one too
		wrapper.unmount()
		expect(signals[1]?.aborted).toBe(true)
	})

	test('asks for an explicit page size instead of relying on the API default', async () => {
		const get = vi.spyOn(axios, 'get')

		const wrapper = mount(ActivityTab, {
			props: { node: { id: 'test', fileid: 42 } as any },
		})
		await flushPromises()

		const [, config] = activityCalls(get)[0]!
		expect((config as any).params.limit).toBe(50)
		// The first page has no cursor
		expect((config as any).params.since).toBeUndefined()
		wrapper.unmount()
	})

	test('does not surface an error when a request is superseded', async () => {
		const rejections: ((reason: unknown) => void)[] = []
		vi.spyOn(axios, 'get').mockImplementation(() => {
			return new Promise((resolve, reject) => {
				rejections.push(reject)
			})
		})

		const wrapper = mount(ActivityTab, {
			props: { node: { id: 'a', fileid: 1 } as any },
		})
		await flushPromises()
		await wrapper.setProps({ node: { id: 'b', fileid: 2 } as any })
		await flushPromises()

		// axios rejects a cancelled request; that is not a failure to report
		rejections[0]!(new Error('canceled'))
		await flushPromises()

		expect(wrapper.vm.$data.error).toBe('')
		expect(wrapper.vm.$data.loading).toBe(true)
		wrapper.unmount()
	})
})

describe('pagination', () => {
	const LOAD_MORE = '.activity__load-more button'

	afterEach(() => {
		vi.restoreAllMocks()
	})

	/** Mount with the first page already applied. */
	async function mountWithFirstPage(response: unknown) {
		const get = vi.spyOn(axios, 'get').mockResolvedValueOnce(response as never)
		const wrapper = mount(ActivityTab, {
			props: { node: { id: 'test', fileid: 42 } as any },
		})
		await flushPromises()
		return { wrapper, get }
	}

	test('offers to load older activities while the server has more', async () => {
		const { wrapper } = await mountWithFirstPage(responseWithIds([30, 29], { hasMore: true }))

		expect(wrapper.find(LOAD_MORE).exists()).toBe(true)
		wrapper.unmount()
	})

	test('offers nothing more once the list is complete', async () => {
		const { wrapper } = await mountWithFirstPage(responseWithIds([30, 29], { hasMore: false }))

		expect(wrapper.find(LOAD_MORE).exists()).toBe(false)
		wrapper.unmount()
	})

	test('requests the next page from the cursor the server gave', async () => {
		const { wrapper, get } = await mountWithFirstPage(responseWithIds([30, 29], { hasMore: true }))
		get.mockResolvedValueOnce(responseWithIds([28, 27], { hasMore: false }) as never)

		await wrapper.find(LOAD_MORE).trigger('click')
		await flushPromises()

		const [, config] = activityCalls(get)[1]!
		// 29 is the last activity of the first page
		expect((config as any).params.since).toBe('29')
		expect((config as any).params.limit).toBe(50)
		wrapper.unmount()
	})

	test('appends the next page instead of replacing what is shown', async () => {
		const { wrapper, get } = await mountWithFirstPage(responseWithIds([30, 29], { hasMore: true }))
		get.mockResolvedValueOnce(responseWithIds([28, 27], { hasMore: false }) as never)

		await wrapper.find(LOAD_MORE).trigger('click')
		await flushPromises()

		const shown = (wrapper.vm.$data.activities as { id: number }[]).map((activity) => activity.id)
		expect(shown).toEqual([30, 29, 28, 27])
		// Nothing left to offer, so the button is gone
		expect(wrapper.find(LOAD_MORE).exists()).toBe(false)
		wrapper.unmount()
	})

	test('treats a 304 on the next page as the end of the list', async () => {
		const { wrapper, get } = await mountWithFirstPage(responseWithIds([30, 29], { hasMore: true }))
		get.mockRejectedValueOnce({ response: { status: 304 } } as never)

		await wrapper.find(LOAD_MORE).trigger('click')
		await flushPromises()

		const shown = (wrapper.vm.$data.activities as { id: number }[]).map((activity) => activity.id)
		expect(shown).toEqual([30, 29])
		expect(wrapper.find(LOAD_MORE).exists()).toBe(false)
		expect(wrapper.vm.$data.error).toBe('')
		wrapper.unmount()
	})

	test('starts over without a stale cursor when another file is selected', async () => {
		const { wrapper, get } = await mountWithFirstPage(responseWithIds([30, 29], { hasMore: true }))
		get.mockResolvedValueOnce(responseWithIds([9], { hasMore: false }) as never)

		await wrapper.setProps({ node: { id: 'other', fileid: 99 } as any })
		await flushPromises()

		const [, config] = activityCalls(get)[1]!
		expect((config as any).params.object_id).toBe(99)
		// Carrying 29 over would silently skip the newest activities of the new file
		expect((config as any).params.since).toBeUndefined()
		expect((wrapper.vm.$data.activities as { id: number }[]).map((a) => a.id)).toEqual([9])
		wrapper.unmount()
	})
})
