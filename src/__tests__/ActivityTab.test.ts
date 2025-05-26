/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import { mount } from '@vue/test-utils'
import { expect, test, vi } from 'vitest'
import ActivityTab from '../views/ActivityTab.vue'

vi.mock('@nextcloud/axios')

test('Create ActivityTab', async () => {
	const wrapper = mount(ActivityTab, {})

	expect(wrapper.vm.$data.activities.length).toBe(0)

	await wrapper.vm.update({ id: 'test' })

	expect(wrapper.vm.$data.activities.length).toBe(18)
})
