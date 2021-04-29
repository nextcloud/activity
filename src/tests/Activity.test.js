/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GNU AGPL version 3 or any later version
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
import { render } from '@testing-library/vue'
import Vue from 'vue'

import Activity from '../components/Activity.vue'
import ActivityModel from '../models/ActivityModel'

const wsData = require('../../__mocks__/@nextcloud/activity_ws.json')

jest.useFakeTimers()

const currentDate = new Date('2021-05-10T12:00:00+00:00')
const realDateNow = Date.now

beforeAll(() => {
	global.Date.now = jest.fn(() => currentDate.getTime())
})

afterAll(() => {
	global.Date.now = realDateNow
})

test('Display relative date gets updated every minutes', async() => {
	const { baseElement, getByText } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[1]) } })
	expect(baseElement.textContent).toContain('You renamed  Test file - renamed.md  to  Test file - renamed - looooooooong.md')

	getByText('11 days ago')

	const currentDatePlusOneDay = new Date('2021-05-11T12:00:00+00:00')
	global.Date.now = jest.fn(() => currentDatePlusOneDay.getTime())

	jest.advanceTimersByTime(60 * 1000)
	await Vue.nextTick()
	getByText('12 days ago')

	global.Date.now = jest.fn(() => currentDate.getTime())
})

test('Display correct information for renames', async() => {
	const { baseElement, getByText } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[1]) } })

	expect(baseElement.textContent).toContain('You renamed  Test file - renamed.md  to  Test file - renamed - looooooooong.md')

	const originalFileNameElement = getByText('Test file - renamed.md')
	expect(originalFileNameElement.nodeName).toBe('A')

	const newFileNameElement = getByText('Test file - renamed - looooooooong.md')
	expect(newFileNameElement.nodeName).toBe('A')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for comments', async() => {
	const { baseElement } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[4]) } })

	expect(baseElement.textContent).toContain('You commented')

	expect(baseElement.textContent).toContain('A comment')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for favorites', async() => {
	const { baseElement } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[5]) } })

	expect(baseElement.textContent).toContain('Added to favorites')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for unfavorites', async() => {
	const { baseElement } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[6]) } })

	expect(baseElement.textContent).toContain('Removed from favorites')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for changes', async() => {
	const { baseElement, getByText } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[7]) } })

	expect(baseElement.textContent).toContain('You changed  Test file - renamed.md')

	const fileNameElement = getByText('Test file - renamed.md')
	expect(fileNameElement.nodeName).toBe('A')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for tags', async() => {
	const { baseElement } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[9]) } })

	expect(baseElement.textContent).toContain('Added system tag tag1')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for shares', async() => {
	const { baseElement } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[10]) } })
	expect(baseElement.textContent).toContain('Shared as public link')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for moves', async() => {
	const { baseElement, getByText } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[14]) } })

	expect(baseElement.textContent).toContain('You moved  Test file.md  to  Documents')

	const fileNameElement = getByText('Test file.md')
	expect(fileNameElement.nodeName).toBe('A')

	const destinationElement = getByText('Documents')
	expect(destinationElement.nodeName).toBe('A')

	expect(baseElement.innerHTML).toMatchSnapshot()
})

test('Display correct information for creations', async() => {
	const { baseElement, getByText } = render(Activity, { props: { activity: new ActivityModel(wsData.ocs.data[17]) } })

	expect(baseElement.textContent).toContain('You created  Test file.md')

	const fileNameElement = getByText('Test file.md')
	expect(fileNameElement.nodeName).toBe('A')

	expect(baseElement.innerHTML).toMatchSnapshot()
})
