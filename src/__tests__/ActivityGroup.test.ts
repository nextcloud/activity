/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type ActivityModel from '../models/ActivityModel.ts'

import { shallowMount } from '@vue/test-utils'
import { describe, expect, it } from 'vitest'
import moment from '@nextcloud/moment'
import ActivityGroup from '../components/ActivityGroup.vue'

/**
 * Build a minimal activities prop. The heading only reads the datetime of the
 * first entry to derive its date label, so the rest can be left out.
 */
function mountGroup(datetime: string) {
	return shallowMount(ActivityGroup, {
		props: { activities: [{ id: 1, datetime } as unknown as ActivityModel] },
	})
}

describe('ActivityGroup heading date label', () => {
	it('labels today as "Today" and exposes the full date as the title', () => {
		const wrapper = mountGroup(moment().toISOString())
		const heading = wrapper.get('.activity-group__heading')

		expect(heading.text()).toBe('Today')
		expect(heading.attributes('title')).toBe(moment().format('LL'))
	})

	it('labels the previous day as "Yesterday"', () => {
		const wrapper = mountGroup(moment().subtract(1, 'day').toISOString())
		const heading = wrapper.get('.activity-group__heading')

		expect(heading.text()).toBe('Yesterday')
		expect(heading.attributes('title')).toBe(moment().subtract(1, 'day').format('LL'))
	})

	it('labels older days with the formatted date and no redundant title', () => {
		const date = moment('2020-01-15T12:00:00')
		const wrapper = mountGroup(date.toISOString())
		const heading = wrapper.get('.activity-group__heading')

		expect(heading.text()).toBe(date.format('LL'))
		expect(heading.attributes('title')).toBeUndefined()
	})
})
