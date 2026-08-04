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
 * Build a preview entry as the API would return one.
 *
 * @param overrides - Fields to replace on the preview
 */
function makePreview(overrides: Record<string, unknown> = {}) {
	return {
		fileId: 42,
		filePath: '/alice/files/holiday.png',
		mimeType: 'image/png',
		source: '/core/preview?fileId=42',
		isMimeTypeIcon: false,
		view: 'files',
		filename: 'holiday.png',
		...overrides,
	}
}

/**
 * Mount the menu for one preview.
 *
 * @param overrides - Preview fields to replace
 */
function mountFor(overrides: Record<string, unknown> = {}) {
	return mount(ActivityEntryActions, {
		props: { preview: makePreview(overrides) },
		attachTo: document.body,
	})
}

/**
 * Open the actions menu.
 *
 * NcActions renders its menu into a popover teleported to the document body, so
 * everything below queries the document rather than the wrapper.
 *
 * @param wrapper - The mounted component
 */
async function openMenu(wrapper: VueWrapper) {
	await wrapper.find('.action-item__menutoggle').trigger('click')
	await new Promise((resolve) => setTimeout(resolve, 0))
}

/** Labels of the menu entries, in render order. */
function menuLabels(): string[] {
	return [...document.querySelectorAll('li.action')].map((item) => item.textContent?.trim() ?? '')
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
	item.querySelector('button, a')!.dispatchEvent(new MouseEvent('click', { bubbles: true }))
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

	describe('scope', () => {
		test('names the menu after its own file, so several on a row are distinguishable', () => {
			const wrapper = mountFor({ filename: 'budget.xlsx' })

			expect(wrapper.find('.action-item__menutoggle').attributes('aria-label')).toContain('budget.xlsx')
			wrapper.unmount()
		})

		test('acts on the preview it was given, not on the first file of the activity', async () => {
			const wrapper = mountFor({ fileId: 99, filename: 'second.png' })
			await openMenu(wrapper)

			expect(document.querySelector('a.action-link')!.getAttribute('href')).toBe('/f/99?openfile=false')
			wrapper.unmount()
		})

		test('renders nothing when there is no file to act on', () => {
			const wrapper = mountFor({ fileId: 0, link: undefined })

			// An empty menu on every thumbnail would be a dead affordance
			expect(wrapper.find('.action-item__menutoggle').exists()).toBe(false)
			wrapper.unmount()
		})
	})

	describe('links', () => {
		test('reveals the file in its folder without opening it', async () => {
			const wrapper = mountFor()
			await openMenu(wrapper)

			// /f/<fileid> opens the Viewer on arrival unless told otherwise, which
			// would make "Show in Files" do two things at once
			expect(document.querySelector('a.action-link')!.getAttribute('href')).toBe('/f/42?openfile=false')
			wrapper.unmount()
		})

		test('resolves by file id rather than trusting the host in a provider link', async () => {
			const wrapper = mountFor({ link: 'http://localhost:8080/index.php/apps/files/?dir=/reports' })
			await openMenu(wrapper)

			const href = document.querySelector('a.action-link')!.getAttribute('href')
			expect(href).toBe('/f/42?openfile=false')
			expect(href).not.toContain('localhost')
			wrapper.unmount()
		})

		test('re-anchors the provider link when there is no file id to resolve', async () => {
			const wrapper = mountFor({
				fileId: 0,
				link: 'http://localhost:8080/index.php/apps/files/?dir=/reports',
			})
			await openMenu(wrapper)

			// The path is the only trustworthy part, so it is kept and re-anchored
			expect(document.querySelector('a.action-link')!.getAttribute('href'))
				.toBe('https://cloud.example.com/index.php/apps/files/?dir=/reports')
			wrapper.unmount()
		})

		test('never sends the browser to a host from the server config', async () => {
			// Real captured API output: 9 of these 18 entries carry an absolute
			// http://localhost:8080 link
			const links = wsData.ocs.data.map((raw) => raw.link).filter((link) => (link ?? '') !== '')
			expect(links.length).toBeGreaterThan(0)

			for (const link of links) {
				const wrapper = mountFor({ fileId: 0, link })
				await openMenu(wrapper)

				expect(document.querySelector('a.action-link')!.getAttribute('href')).not.toContain('localhost')
				wrapper.unmount()
				document.body.innerHTML = ''
			}
		})
	})

	describe('viewing', () => {
		test('offers viewing when the Viewer can display the file', async () => {
			window.OCA = { Viewer: { open: vi.fn(), mimetypes: ['image/png'] } } as never
			const wrapper = mountFor()
			await openMenu(wrapper)

			expect(menuLabels()).toEqual(['View', 'Show in Files', 'Copy link'])
			wrapper.unmount()
		})

		test('does not offer viewing a MIME type the Viewer cannot display', async () => {
			window.OCA = { Viewer: { open: vi.fn(), mimetypes: ['image/png'] } } as never
			const wrapper = mountFor({ mimeType: 'application/pdf', filename: 'report.pdf' })
			await openMenu(wrapper)

			expect(menuLabels()).not.toContain('View')
			wrapper.unmount()
		})

		test('does not offer viewing when the Viewer app is not loaded', async () => {
			const wrapper = mountFor()
			await openMenu(wrapper)

			expect(menuLabels()).not.toContain('View')
			wrapper.unmount()
		})

		test('does not offer viewing without a path to open', async () => {
			window.OCA = { Viewer: { open: vi.fn(), mimetypes: ['image/png'] } } as never
			const wrapper = mountFor({ filePath: undefined })
			await openMenu(wrapper)

			expect(menuLabels()).not.toContain('View')
			wrapper.unmount()
		})

		test('opens the file in the Viewer with a path relative to the files root', async () => {
			const open = vi.fn()
			window.OCA = { Viewer: { open, mimetypes: ['image/png'] } } as never
			const wrapper = mountFor({ filePath: '/alice/files/trips/holiday.png' })
			await openMenu(wrapper)
			await clickMenuItem('View')

			expect(open).toHaveBeenCalledWith({ path: '/trips/holiday.png' })
			wrapper.unmount()
		})
	})

	describe('copying', () => {
		test('copies an absolute link, not the relative route', async () => {
			const writeText = vi.fn(() => Promise.resolve())
			Object.defineProperty(navigator, 'clipboard', { value: { writeText }, configurable: true })

			const wrapper = mountFor()
			await openMenu(wrapper)
			await clickMenuItem('Copy link')

			// A relative link on the clipboard would be useless once pasted, and no
			// openfile=false either: that is this menu's reveal behaviour, not
			// something to impose on whoever receives the link
			expect(writeText).toHaveBeenCalledWith('https://cloud.example.com/f/42')
			expect(vi.mocked(showSuccess)).toHaveBeenCalled()
			expect(vi.mocked(showError)).not.toHaveBeenCalled()
			wrapper.unmount()
		})

		test('reports a failed copy instead of claiming success', async () => {
			const writeText = vi.fn(() => Promise.reject(new Error('denied')))
			Object.defineProperty(navigator, 'clipboard', { value: { writeText }, configurable: true })

			const wrapper = mountFor()
			await openMenu(wrapper)
			await clickMenuItem('Copy link')

			expect(vi.mocked(showError)).toHaveBeenCalled()
			expect(vi.mocked(showSuccess)).not.toHaveBeenCalled()
			wrapper.unmount()
		})

		test('says so when the clipboard is unavailable rather than failing silently', async () => {
			Object.defineProperty(navigator, 'clipboard', { value: undefined, configurable: true })

			const wrapper = mountFor()
			await openMenu(wrapper)
			await clickMenuItem('Copy link')

			expect(vi.mocked(showError)).toHaveBeenCalled()
			expect(vi.mocked(showSuccess)).not.toHaveBeenCalled()
			wrapper.unmount()
		})
	})
})
