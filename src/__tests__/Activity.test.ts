/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import { mount } from '@vue/test-utils'
import { afterAll, beforeAll, expect, test, vi } from 'vitest'
import { nextTick } from 'vue'
import ActivityComponent from '../components/ActivityComponent.vue'
import wsData from '../__mocks__/@nextcloud/activity_ws.json'
import ActivityModel from '../models/ActivityModel.js'

const currentDate = new Date('2021-05-02T12:00:00+00:00')
const realDateNow = Date.now

beforeAll(() => {
	vi.useFakeTimers()
	vi.setSystemTime(currentDate)
	window.document.head.setAttribute('data-user', 'admin')
})

afterAll(() => {
	window.Date.now = realDateNow
	vi.useRealTimers()
})

function expectLinkWithText(wrapper, text) {
	const filtered = wrapper.findAll('a').filter((element) => element.text().includes(text))
	expect(filtered.length).toBe(1)

	return filtered
}

test('Every thumbnail carries its own actions menu', async () => {
	// The captured fixture was taken without previews=true, so the previews are
	// added here; the stream itself always requests them
	const activity = new ActivityModel({
		...wsData.ocs.data[1],
		previews: [
			{ fileId: 1, filePath: '/admin/files/a.png', mimeType: 'image/png', source: 's', isMimeTypeIcon: false, view: 'files', filename: 'a.png' },
			{ fileId: 2, filePath: '/admin/files/b.png', mimeType: 'image/png', source: 's', isMimeTypeIcon: false, view: 'files', filename: 'b.png' },
		],
	})
	const wrapper = mount(ActivityComponent, { propsData: { activity, showPreviews: true } })
	await nextTick()

	// One menu per file, not one per row: guards the wiring, while what each menu
	// offers is covered by ActivityEntryActions.test.ts
	const previews = wrapper.findAll('.activity-entry__preview-item')
	expect(previews.length).toBe(2)
	for (const preview of previews) {
		expect(preview.find('.action-item__menutoggle').exists()).toBe(true)
	}
})

test('No actions menu without thumbnails to hang it on', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[1]), showPreviews: false } })
	await nextTick()

	expect(wrapper.find('.action-item__menutoggle').exists()).toBe(false)
})

test('Display relative date gets updated every minutes', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[1]), showPreviews: true } })
	await nextTick()

	expect(wrapper.text()).toContain('You renamed Test file - renamed.md to Test file - renamed - looooooooong.md')
	expect(wrapper.find('[data-testid="activity-date"]').text()).toContain('3 days ago')

	vi.advanceTimersByTime(24 * 60 * 60 * 1000)
	await nextTick()

	expect(wrapper.find('[data-testid="activity-date"]').text()).toContain('4 days ago')
})

test('Display correct information for renames', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[1]), showPreviews: true } })

	expect(wrapper.text()).toContain('You renamed Test file - renamed.md to Test file - renamed - looooooooong.md')

	expectLinkWithText(wrapper, 'Test file - renamed.md')
	expectLinkWithText(wrapper, 'Test file - renamed - looooooooong.md')
})

test('Display correct information for comments', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[4]), showPreviews: true } })

	expect(wrapper.text()).toContain('You commented')

	expect(wrapper.text()).toContain('A comment')
})

test('Display correct information for favorites', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[5]), showPreviews: true } })

	expect(wrapper.text()).toContain('Added to favorites')
})

test('Display correct information for unfavorites', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[6]), showPreviews: true } })

	expect(wrapper.text()).toContain('Removed from favorites')
})

test('Display correct information for changes', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[7]), showPreviews: true } })

	expect(wrapper.text()).toContain('You changed Test file - renamed.md')

	expectLinkWithText(wrapper, 'Test file - renamed.md')
})

test('Display correct information for tags', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[9]), showPreviews: true } })

	expect(wrapper.text()).toContain('Added system tag tag1')
})

test('Display correct information for shares', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[10]), showPreviews: true } })
	expect(wrapper.text()).toContain('Shared as public link')
})

test('Display correct information for moves', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[14]), showPreviews: true } })

	expect(wrapper.text()).toContain('You moved Test file.md to Documents')

	expectLinkWithText(wrapper, 'Test file.md')
	expectLinkWithText(wrapper, 'Documents')
})

test('Display correct information for creations', async () => {
	const wrapper = mount(ActivityComponent, { propsData: { activity: new ActivityModel(wsData.ocs.data[17]), showPreviews: true } })

	expect(wrapper.text()).toContain('You created Test file.md')

	expectLinkWithText(wrapper, 'Test file.md')
})
