/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { VueWrapper } from '@vue/test-utils'

import { showError, showSuccess } from '@nextcloud/dialogs'
import { mount } from '@vue/test-utils'
import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest'
import ActivityEntryActions from '../components/ActivityEntryActions.vue'
import wsData from '../__mocks__/@nextcloud/activity_ws.json'
import ActivityModel from '../models/ActivityModel.ts'

vi.mock('@nextcloud/dialogs', () => ({ showError: vi.fn(), showSuccess: vi.fn() }))

vi.mock(import('@nextcloud/router'), async (importOriginal) => {
	const actual = await importOriginal()
	return {
		...actual,
		generateUrl: vi.fn((template: string, params: Record<string, string> = {}) => Object.entries(params).reduce(
			(url, [key, value]) => url.replace(`{${key}}`, String(value)),
			template,
		)),
	}
})

/**
 * Build an activity, overriding only what a given test cares about.
 *
 * @param overrides - Fields to replace on the raw activity
 */
function makeActivity(overrides: Record<string, unknown> = {}) {
	return new ActivityModel({
		activity_id: 1,
		app: 'files',
		type: 'file_changed',
		user: 'alice',
		subject: 'You changed report.pdf',
		subject_rich: ['You changed report.pdf', []],
		message: '',
		message_rich: ['', []],
		icon: 'icon.svg',
		datetime: '2024-01-15T10:00:00+00:00',
		object_type: 'files',
		object_id: 42,
		object_name: '/report.pdf',
		objects: {},
		link: '',
		...overrides,
	} as never)
}

/**
 * Build a preview entry as the API would return one.
 *
 * @param overrides - Fields to replace on the preview
 */
function makePreview(overrides: Record<string, unknown> = {}) {
	return {
		fileId: 7,
		filePath: '/alice/files/holiday.png',
		mimeType: 'image/png',
		source: '/core/preview?fileId=7',
		isMimeTypeIcon: false,
		view: 'files',
		filename: 'holiday.png',
		...overrides,
	}
}

/**
 * Open the actions menu.
 *
 * NcActions renders its menu into a popover teleported to the document body,
 * so everything below queries the document rather than the wrapper.
 *
 * @param wrapper - The mounted component
 */
async function openMenu(wrapper: VueWrapper) {
	await wrapper.find('.action-item__menutoggle').trigger('click')
	await new Promise((resolve) => setTimeout(resolve, 0))
}

/** Labels of the menu entries, in render order. */
function menuLabels(): string[] {
	return [...document.querySelectorAll('li.action')]
		.map((item) => item.textContent?.trim() ?? '')
}

/**
 * Click a menu entry by its visible label.
 *
 * @param label - The entry's text
 */
async function clickMenuItem(label: string) {
	const item = [...document.querySelectorAll('li.action')]
		.find((candidate) => candidate.textContent?.trim() === label)
	if (item === undefined) {
		throw new Error(`No menu entry labelled "${label}" (found: ${menuLabels().join(', ')})`)
	}
	const control = item.querySelector('button, a')!
	control.dispatchEvent(new MouseEvent('click', { bubbles: true }))
	await new Promise((resolve) => setTimeout(resolve, 0))
}

describe('ActivityEntryActions', () => {
	beforeEach(() => {
		// The toast mocks come from a module factory, so their call history
		// outlives restoreAllMocks() and has to be cleared explicitly
		vi.clearAllMocks()
		Object.defineProperty(window, 'location', {
			value: { origin: 'https://cloud.example.com' },
			writable: true,
		})
	})

	afterEach(() => {
		vi.restoreAllMocks()
		delete window.OCA
		document.body.innerHTML = ''
	})

	test('reveals the file in its folder without opening it', async () => {
		const wrapper = mount(ActivityEntryActions, { props: { activity: makeActivity() }, attachTo: document.body })
		await openMenu(wrapper)

		// /f/<fileid> opens the Viewer on arrival unless told otherwise, which
		// would make "Show in Files" do two things at once
		expect(document.querySelector('a.action-link')!.getAttribute('href')).toBe('/f/42?openfile=false')
		wrapper.unmount()
	})

	test('does not suppress opening on a link it did not build', async () => {
		const activity = makeActivity({
			object_type: 'calendar',
			object_id: 0,
			link: 'https://cloud.example.com/index.php/apps/calendar/',
		})
		const wrapper = mount(ActivityEntryActions, { props: { activity }, attachTo: document.body })
		await openMenu(wrapper)

		// openfile is a parameter of the files route, meaningless elsewhere
		expect(document.querySelector('a.action-link')!.getAttribute('href'))
			.toBe('https://cloud.example.com/index.php/apps/calendar/')
		wrapper.unmount()
	})

	test('resolves by file id rather than trusting the host in the provider link', async () => {
		// Providers build absolute URLs from the server's own configuration. An
		// activity recorded by a background job carries overwrite.cli.url, which
		// is commonly localhost and unreachable from the browser.
		const activity = makeActivity({ link: 'http://localhost:8080/index.php/apps/files/?dir=/reports' })
		const wrapper = mount(ActivityEntryActions, { props: { activity }, attachTo: document.body })
		await openMenu(wrapper)

		const href = document.querySelector('a.action-link')!.getAttribute('href')
		expect(href).toBe('/f/42?openfile=false')
		expect(href).not.toContain('localhost')
		wrapper.unmount()
	})

	test('re-anchors the provider link when there is no file id to resolve', async () => {
		const activity = makeActivity({
			object_type: 'calendar',
			object_id: 0,
			link: 'http://localhost:8080/index.php/apps/calendar/?view=month',
		})
		const wrapper = mount(ActivityEntryActions, { props: { activity }, attachTo: document.body })
		await openMenu(wrapper)

		// The path is the only trustworthy part, so it is kept and re-anchored
		expect(document.querySelector('a.action-link')!.getAttribute('href'))
			.toBe('https://cloud.example.com/index.php/apps/calendar/?view=month')
		wrapper.unmount()
	})

	test('never sends the browser to a host from the server config', async () => {
		// Real captured API output: 9 of these 18 entries carry an absolute
		// http://localhost:8080 link, which is what made "Show in Files" dead
		const withLinks = wsData.ocs.data.filter((raw) => (raw.link ?? '') !== '')
		expect(withLinks.length).toBeGreaterThan(0)

		for (const raw of withLinks) {
			const wrapper = mount(ActivityEntryActions, {
				props: { activity: new ActivityModel(raw as never) },
				attachTo: document.body,
			})
			await openMenu(wrapper)

			const href = document.querySelector('a.action-link')!.getAttribute('href')
			expect(href).not.toContain('localhost')
			wrapper.unmount()
			document.body.innerHTML = ''
		}
	})

	test('renders nothing for an activity with no file behind it', () => {
		const activity = makeActivity({ object_type: 'comments', object_id: 0, link: '' })
		const wrapper = mount(ActivityEntryActions, { props: { activity }, attachTo: document.body })

		// An empty menu would be a dead affordance on every such row
		expect(wrapper.find('.action-item__menutoggle').exists()).toBe(false)
		wrapper.unmount()
	})

	test('offers viewing when the Viewer can display the file', async () => {
		window.OCA = { Viewer: { open: vi.fn(), mimetypes: ['image/png'] } } as never
		const wrapper = mount(ActivityEntryActions, {
			props: { activity: makeActivity({ previews: [makePreview()] }) },
			attachTo: document.body,
		})
		await openMenu(wrapper)

		expect(menuLabels()).toEqual(['View', 'Show in Files', 'Copy link'])
		wrapper.unmount()
	})

	test('does not offer viewing a MIME type the Viewer cannot display', async () => {
		window.OCA = { Viewer: { open: vi.fn(), mimetypes: ['image/png'] } } as never
		const activity = makeActivity({
			previews: [makePreview({ mimeType: 'application/pdf', filename: 'report.pdf' })],
		})
		const wrapper = mount(ActivityEntryActions, { props: { activity }, attachTo: document.body })
		await openMenu(wrapper)

		expect(menuLabels()).not.toContain('View')
		wrapper.unmount()
	})

	test('does not offer viewing when previews were never requested', async () => {
		// The sidebar loads without previews, so there is no MIME type to judge
		window.OCA = { Viewer: { open: vi.fn(), mimetypes: ['image/png'] } } as never
		const wrapper = mount(ActivityEntryActions, { props: { activity: makeActivity() }, attachTo: document.body })
		await openMenu(wrapper)

		expect(menuLabels()).not.toContain('View')
		wrapper.unmount()
	})

	test('does not offer viewing when the Viewer app is not loaded', async () => {
		const wrapper = mount(ActivityEntryActions, {
			props: { activity: makeActivity({ previews: [makePreview()] }) },
			attachTo: document.body,
		})
		await openMenu(wrapper)

		expect(menuLabels()).not.toContain('View')
		wrapper.unmount()
	})

	test('opens the file in the Viewer with a path relative to the files root', async () => {
		const open = vi.fn()
		window.OCA = { Viewer: { open, mimetypes: ['image/png'] } } as never
		const activity = makeActivity({
			previews: [makePreview({ filePath: '/alice/files/trips/holiday.png' })],
		})
		const wrapper = mount(ActivityEntryActions, { props: { activity }, attachTo: document.body })
		await openMenu(wrapper)
		await clickMenuItem('View')

		expect(open).toHaveBeenCalledWith({ path: '/trips/holiday.png' })
		wrapper.unmount()
	})

	test('copies an absolute link, not the relative route', async () => {
		const writeText = vi.fn(() => Promise.resolve())
		Object.defineProperty(navigator, 'clipboard', { value: { writeText }, configurable: true })

		const wrapper = mount(ActivityEntryActions, { props: { activity: makeActivity() }, attachTo: document.body })
		await openMenu(wrapper)
		await clickMenuItem('Copy link')

		// A relative link on the clipboard would be useless once pasted anywhere.
		// No openfile=false either: that is this menu's reveal behaviour, not
		// something to impose on whoever receives the link.
		expect(writeText).toHaveBeenCalledWith('https://cloud.example.com/f/42')
		expect(vi.mocked(showSuccess)).toHaveBeenCalled()
		expect(vi.mocked(showError)).not.toHaveBeenCalled()
		wrapper.unmount()
	})

	test('never copies a host the browser cannot reach', async () => {
		const writeText = vi.fn(() => Promise.resolve())
		Object.defineProperty(navigator, 'clipboard', { value: { writeText }, configurable: true })

		const activity = makeActivity({
			object_type: 'calendar',
			object_id: 0,
			link: 'http://localhost:8080/index.php/apps/calendar/?view=month',
		})
		const wrapper = mount(ActivityEntryActions, { props: { activity }, attachTo: document.body })
		await openMenu(wrapper)
		await clickMenuItem('Copy link')

		expect(writeText).toHaveBeenCalledWith('https://cloud.example.com/index.php/apps/calendar/?view=month')
		wrapper.unmount()
	})

	test('reports a failed copy instead of claiming success', async () => {
		const writeText = vi.fn(() => Promise.reject(new Error('denied')))
		Object.defineProperty(navigator, 'clipboard', { value: { writeText }, configurable: true })

		const wrapper = mount(ActivityEntryActions, { props: { activity: makeActivity() }, attachTo: document.body })
		await openMenu(wrapper)
		await clickMenuItem('Copy link')

		expect(vi.mocked(showError)).toHaveBeenCalled()
		expect(vi.mocked(showSuccess)).not.toHaveBeenCalled()
		wrapper.unmount()
	})

	test('says so when the clipboard is unavailable rather than failing silently', async () => {
		Object.defineProperty(navigator, 'clipboard', { value: undefined, configurable: true })

		const wrapper = mount(ActivityEntryActions, { props: { activity: makeActivity() }, attachTo: document.body })
		await openMenu(wrapper)
		await clickMenuItem('Copy link')

		expect(vi.mocked(showError)).toHaveBeenCalled()
		expect(vi.mocked(showSuccess)).not.toHaveBeenCalled()
		wrapper.unmount()
	})
})
