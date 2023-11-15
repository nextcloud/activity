/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license AGPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
import { mount } from '@vue/test-utils'
import { afterAll, beforeAll, expect, test, vi } from 'vitest'
import { nextTick } from 'vue'

import Activity from '../components/Activity.vue'
import ActivityModel from '../models/ActivityModel.js'

import wsData from '../__mocks__/@nextcloud/activity_ws.json'

const currentDate = new Date('2021-05-02T12:00:00+00:00')
const realDateNow = Date.now

beforeAll(() => {
	vi.useFakeTimers()
	window.document.head.setAttribute('data-user', 'admin')
	window.Date.now = vi.fn(() => currentDate.getTime())
})

afterAll(() => {
	window.Date.now = realDateNow
	vi.useRealTimers()
})

const expectLinkWithText = (wrapper, text) => {
	const filtered = wrapper.findAll('a').filter((element) => element.text().includes(text))
	expect(filtered.length).toBe(1)

	return filtered
}

test('Display relative date gets updated every minutes', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[1]), showPreviews: true } })
	expect(wrapper.text()).toContain('You renamed Test file - renamed.md to Test file - renamed - looooooooong.md')

	expect(wrapper.find('[data-testid="activity-date"]').text()).toContain('3 days ago')

	const currentDatePlusOneDay = new Date('2021-05-03T12:00:00+00:00')
	window.Date.now = vi.fn(() => currentDatePlusOneDay.getTime())

	vi.advanceTimersByTime(60 * 1000)
	await nextTick()
	expect(wrapper.find('[data-testid="activity-date"]').text()).toContain('4 days ago')

	window.Date.now = vi.fn(() => currentDate.getTime())
})

test('Display correct information for renames', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[1]), showPreviews: true } })

	expect(wrapper.text()).toContain('You renamed Test file - renamed.md to Test file - renamed - looooooooong.md')

	expectLinkWithText(wrapper, 'Test file - renamed.md')
	expectLinkWithText(wrapper, 'Test file - renamed - looooooooong.md')
})

test('Display correct information for comments', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[4]), showPreviews: true } })

	expect(wrapper.text()).toContain('You commented')

	expect(wrapper.text()).toContain('A comment')
})

test('Display correct information for favorites', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[5]), showPreviews: true } })

	expect(wrapper.text()).toContain('Added to favorites')
})

test('Display correct information for unfavorites', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[6]), showPreviews: true } })

	expect(wrapper.text()).toContain('Removed from favorites')
})

test('Display correct information for changes', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[7]), showPreviews: true } })

	expect(wrapper.text()).toContain('You changed Test file - renamed.md')

	expectLinkWithText(wrapper, 'Test file - renamed.md')
})

test('Display correct information for tags', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[9]), showPreviews: true } })

	expect(wrapper.text()).toContain('Added system tag tag1')
})

test('Display correct information for shares', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[10]), showPreviews: true } })
	expect(wrapper.text()).toContain('Shared as public link')
})

test('Display correct information for moves', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[14]), showPreviews: true } })

	expect(wrapper.text()).toContain('You moved Test file.md to Documents')

	expectLinkWithText(wrapper, 'Test file.md')
	expectLinkWithText(wrapper, 'Documents')
})

test('Display correct information for creations', async () => {
	const wrapper = mount(Activity, { propsData: { activity: new ActivityModel(wsData.ocs.data[17]), showPreviews: true } })

	expect(wrapper.text()).toContain('You created Test file.md')

	expectLinkWithText(wrapper, 'Test file.md')
})
