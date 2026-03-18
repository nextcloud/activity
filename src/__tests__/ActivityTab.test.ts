/*!
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { VueWrapper } from '@vue/test-utils'

import { mount } from '@vue/test-utils'
import { expect, test, vi } from 'vitest'
import { nextTick } from 'vue'
import ActivityTab from '../views/ActivityTab.vue'

vi.mock('@nextcloud/axios')

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

function waitFor(query: string, wrapper: VueWrapper, callback: () => void) {
	if (wrapper.find(query).exists()) {
		return callback()
	}
	nextTick(() => waitFor(query, wrapper, callback))
}
