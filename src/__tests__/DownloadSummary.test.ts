/*!
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { mount } from '@vue/test-utils'
import { expect, test, vi } from 'vitest'
import { nextTick } from 'vue'
import DownloadSummary from '../components/DownloadSummary.vue'

vi.mock('@nextcloud/axios')

test('shows nothing when no downloads', async () => {
	const axios = await import('@nextcloud/axios')
	vi.spyOn(axios.default, 'get').mockResolvedValue({
		data: { ocs: { meta: { status: 'ok', statuscode: 200 }, data: { total: 0, last30d: 0 } } },
	})

	const wrapper = mount(DownloadSummary, { props: { fileId: 42 } })
	await nextTick()
	await nextTick()

	expect(wrapper.find('.download-summary').exists()).toBe(false)
})

test('shows total count only when monthly equals total', async () => {
	const axios = await import('@nextcloud/axios')
	vi.spyOn(axios.default, 'get').mockResolvedValue({
		data: { ocs: { meta: { status: 'ok', statuscode: 200 }, data: { total: 5, last30d: 5 } } },
	})

	const wrapper = mount(DownloadSummary, { props: { fileId: 42 } })
	await nextTick()
	await nextTick()

	expect(wrapper.find('.download-summary').exists()).toBe(true)
	expect(wrapper.find('.download-summary__text').text()).toContain('Downloaded')
	expect(wrapper.find('.download-summary__text').text()).not.toContain('last 30 days')
})

test('shows monthly breakdown when monthly is less than total', async () => {
	const axios = await import('@nextcloud/axios')
	vi.spyOn(axios.default, 'get').mockResolvedValue({
		data: { ocs: { meta: { status: 'ok', statuscode: 200 }, data: { total: 10, last30d: 3 } } },
	})

	const wrapper = mount(DownloadSummary, { props: { fileId: 42 } })
	await nextTick()
	await nextTick()

	expect(wrapper.find('.download-summary').exists()).toBe(true)
	expect(wrapper.find('.download-summary__text').text()).toContain('last 30 days')
})

test('refetches when fileId changes', async () => {
	const axios = await import('@nextcloud/axios')
	const spy = vi.spyOn(axios.default, 'get').mockResolvedValue({
		data: { ocs: { meta: { status: 'ok', statuscode: 200 }, data: { total: 2, last30d: 2 } } },
	})

	const wrapper = mount(DownloadSummary, { props: { fileId: 42 } })
	await nextTick()

	spy.mockClear()

	await wrapper.setProps({ fileId: 99 })
	await nextTick()

	expect(spy).toHaveBeenCalledTimes(1)
})
