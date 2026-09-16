/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { VueWrapper } from '@vue/test-utils'

import { mount } from '@vue/test-utils'
import { describe, expect, it, vi } from 'vitest'
import ActivityAppNavigation from '../views/ActivityAppNavigation.vue'

interface INavigationEntry {
	id: string
	icon?: string
	name: string
	url: string
}

// vi.hoisted runs before the vi.mock factory below, avoiding a TDZ error.
const state = vi.hoisted(() => ({ entries: [] as unknown[], activeId: '' }))

vi.mock('@nextcloud/axios', () => ({ default: { post: vi.fn() } }))
vi.mock('@nextcloud/dialogs', () => ({ showError: vi.fn(), showSuccess: vi.fn() }))

// 'settings' is destructured by the component, so it must return an object.
vi.mock('@nextcloud/initial-state', () => ({
	loadState: vi.fn((app: string, key: string) => {
		if (key === 'settings') {
			return {
				enableAvatars: true,
				rssLink: '',
				personalSettingsLink: '/settings/user/notifications',
			}
		}
		return state.entries
	}),
}))

const stubs = {
	NcAppNavigation: { template: '<div><slot name="list" /></div>' },
	// Upstream derives the slot's `active` from `active || (to && isActive)`, and
	// we pass :to="<filter id>", so comparing `to` is the same discrimination.
	NcAppNavigationItem: {
		template: '<div class="nav-item"><slot name="icon" :active="isActive" /></div>',
		props: ['name', 'to'],
		computed: {
			isActive() {
				return this.to === state.activeId
			},
		},
	},
	NcIconSvgWrapper: { template: '<span class="icon-svg" :data-svg="svg" />', props: ['svg'] },
}

const USER_ICON = 'http://localhost/core/img/actions/user.svg'
const TASKS_ICON = 'http://localhost/core/img/actions/checkmark.svg'
const DECK_ICON = 'http://localhost/apps/deck/img/deck-dark.svg'

// Explicit, so deleting a map entry fails a test instead of silently falling back.
const MAPPED_IDS = [
	'all',
	'by',
	'calendar',
	'circles',
	'comments',
	'contacts',
	'files',
	'files_favorites',
	'files_sharing',
	'security',
	'self',
]

function mountNavigation(entries: INavigationEntry[], activeId = ''): VueWrapper {
	state.entries = entries
	state.activeId = activeId
	return mount(ActivityAppNavigation, { global: { stubs } })
}

function svgFor(wrapper: VueWrapper, id: string): string | undefined {
	return wrapper.get(`[data-navigation="${id}"] .icon-svg`).attributes('data-svg')
}

describe('ActivityAppNavigation', () => {
	it('renders an inline icon for every mapped filter', () => {
		const entries = MAPPED_IDS.map((id) => ({ id, name: id, url: '', icon: USER_ICON }))
		const wrapper = mountNavigation(entries)

		expect(wrapper.findAll('.icon-svg')).toHaveLength(MAPPED_IDS.length)
		expect(wrapper.findAll('img.navigation-icon')).toHaveLength(0)
		for (const id of MAPPED_IDS) {
			expect(svgFor(wrapper, id)).toBeTruthy()
		}
	})

	it('only fills the active entry, leaving its siblings outlined', () => {
		const entries = [
			{ id: 'self', name: 'By you', url: '', icon: USER_ICON },
			{ id: 'comments', name: 'Comments', url: '', icon: USER_ICON },
		]
		const bothOutlined = mountNavigation(entries)
		const commentsActive = mountNavigation(entries, 'comments')

		expect(svgFor(commentsActive, 'comments')).not.toBe(svgFor(bothOutlined, 'comments'))
		expect(svgFor(commentsActive, 'self')).toBe(svgFor(bothOutlined, 'self'))
	})

	it('gives "By others" and "Contacts" distinct glyphs', () => {
		const wrapper = mountNavigation([
			{ id: 'by', name: 'By others', url: '', icon: USER_ICON },
			{ id: 'contacts', name: 'Contacts', url: '', icon: USER_ICON },
		])

		expect(svgFor(wrapper, 'by')).not.toBe(svgFor(wrapper, 'contacts'))
	})

	// Deck is the one that matters: design asked for its real solid mark, so it
	// must stay on the fallback path even while selected.
	it('keeps the backend icon for an unmapped filter, active or not', () => {
		const entry = { id: 'deck', name: 'Deck', url: '', icon: DECK_ICON }

		for (const wrapper of [mountNavigation([entry]), mountNavigation([entry], 'deck')]) {
			expect(wrapper.get('[data-navigation="deck"] img.navigation-icon').attributes('src')).toBe(DECK_ICON)
			expect(wrapper.find('.icon-svg').exists()).toBe(false)
		}
	})

	it('renders both icon paths in the same list', () => {
		const wrapper = mountNavigation([
			{ id: 'self', name: 'By you', url: '', icon: USER_ICON },
			{ id: 'calendar_todo', name: 'Tasks', url: '', icon: TASKS_ICON },
		])

		expect(wrapper.findAll('.icon-svg')).toHaveLength(1)
		expect(wrapper.findAll('img.navigation-icon')).toHaveLength(1)
	})

	it('renders no icon for an unmapped filter with no icon URL', () => {
		const wrapper = mountNavigation([{ id: 'unknown', name: 'Unknown', url: '' }])
		const entry = wrapper.get('[data-navigation="unknown"]')

		expect(entry.find('.icon-svg').exists()).toBe(false)
		expect(entry.find('img').exists()).toBe(false)
	})
})
