/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { mount } from '@vue/test-utils'
import { beforeEach, describe, expect, test, vi } from 'vitest'
import { createStore } from 'vuex'

// ── Mocks (hoisted; run before any module is imported) ──────────────────────

// loadState is called at module level in AdminSettings.vue; mock before import.
vi.mock('@nextcloud/initial-state', () => ({
	loadState: vi.fn((_app: string, _key: string, def: unknown) => def),
}))

vi.mock('@nextcloud/files', () => ({
	formatFileSize: (bytes: number) => `${bytes} B`,
}))

// The store singleton tries to call loadState at import time — stub it out.
vi.mock('../store/settings-store', () => ({ default: null }))

// @nextcloud/dialogs imports NcDialog from @nextcloud/vue, which has a .css sidecar
// that Node can't load as ESM (not in server.deps.inline). Mock to break the chain.
vi.mock('@nextcloud/dialogs', () => ({
	showSuccess: vi.fn(),
	showError: vi.fn(),
}))

// ── Helpers ─────────────────────────────────────────────────────────────────

import AdminSettings from '../views/AdminSettings.vue'

const makeStore = () =>
	createStore({
		state: { emailEnabled: true },
		actions: {
			setEndpoint: vi.fn(),
			toggleEmailEnabled: vi.fn(),
		},
	})

const defaultStats = {
	dedicated_connection: false,
	tables: { activity: 1024, activity_mq: 512 },
	retention_suggestion: null,
}

function mountWith(databaseStats = defaultStats) {
	const wrapper = mount(AdminSettings, {
		global: { plugins: [makeStore()] },
	})
	// The module-level loadState runs only once (with the default); override
	// databaseStats on the instance so each test sees its own data.
	wrapper.vm.databaseStats = databaseStats
	return wrapper
}

// ── Tests ───────────────────────────────────────────────────────────────────

describe('AdminSettings — database table', () => {
	beforeEach(() => vi.clearAllMocks())

	test('renders a row for each table with formatted sizes', async () => {
		const wrapper = mountWith({ ...defaultStats, tables: { activity: 2048, activity_mq: 1024 } })
		await wrapper.vm.$nextTick()

		const rows = wrapper.findAll('tbody tr')
		// Two data rows + one total row
		expect(rows.length).toBe(3)
		expect(rows[0].text()).toContain('activity')
		expect(rows[0].text()).toContain('2048 B')
		expect(rows[1].text()).toContain('activity_mq')
		expect(rows[1].text()).toContain('1024 B')
	})

	test('shows "unavailable" for null sizes and omits total row', async () => {
		const wrapper = mountWith({ ...defaultStats, tables: { activity: null, activity_mq: null } })
		await wrapper.vm.$nextTick()

		const rows = wrapper.findAll('tbody tr')
		expect(rows.length).toBe(2)
		expect(rows[0].text()).toContain('unavailable')
		expect(rows[1].text()).toContain('unavailable')
	})

	test('shows SQLite hint when no sizes are available', async () => {
		const wrapper = mountWith({ ...defaultStats, tables: { activity: null, activity_mq: null } })
		await wrapper.vm.$nextTick()

		expect(wrapper.find('.activity-database-table__hint').exists()).toBe(true)
	})

	test('does not show SQLite hint when sizes are available', async () => {
		const wrapper = mountWith()
		await wrapper.vm.$nextTick()

		expect(wrapper.find('.activity-database-table__hint').exists()).toBe(false)
	})

	test('shows dedicated-connection description when configured', async () => {
		const wrapper = mountWith({ ...defaultStats, dedicated_connection: true })
		await wrapper.vm.$nextTick()

		expect(wrapper.vm.databaseDescription).toContain('dedicated database')
	})

	test('shows main-database description when not dedicated', async () => {
		const wrapper = mountWith({ ...defaultStats, dedicated_connection: false })
		await wrapper.vm.$nextTick()

		expect(wrapper.vm.databaseDescription).toContain('main Nextcloud database')
	})
})

describe('AdminSettings — retention suggestion', () => {
	beforeEach(() => vi.clearAllMocks())

	test('does not show NcNoteCard when retention_suggestion is null', async () => {
		const wrapper = mountWith({ ...defaultStats, retention_suggestion: null })
		await wrapper.vm.$nextTick()

		expect(wrapper.findComponent({ name: 'NcNoteCard' }).exists()).toBe(false)
	})

	test('shows NcNoteCard when retention_suggestion is set', async () => {
		const wrapper = mountWith({
			...defaultStats,
			retention_suggestion: {
				total_bytes: 11_000_000_000,
				current_days: 365,
				suggested_days: 30,
				config_key: 'activity_expire_days',
			},
		})
		await wrapper.vm.$nextTick()

		expect(wrapper.findComponent({ name: 'NcNoteCard' }).exists()).toBe(true)
	})

	test('snippet contains the config.php line to paste', async () => {
		const wrapper = mountWith({
			...defaultStats,
			retention_suggestion: {
				total_bytes: 6_000_000_000,
				current_days: 365,
				suggested_days: 90,
				config_key: 'activity_expire_days',
			},
		})
		await wrapper.vm.$nextTick()

		expect(wrapper.vm.retentionSuggestionSnippet).toBe("'activity_expire_days' => 90,")
	})

	test('snippet is rendered inside the note card', async () => {
		const wrapper = mountWith({
			...defaultStats,
			retention_suggestion: {
				total_bytes: 6_000_000_000,
				current_days: 365,
				suggested_days: 90,
				config_key: 'activity_expire_days',
			},
		})
		await wrapper.vm.$nextTick()

		const snippet = wrapper.find('.activity-database-suggestion__snippet')
		expect(snippet.text()).toBe("'activity_expire_days' => 90,")
	})
})
