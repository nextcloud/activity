/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { beforeEach, describe, expect, it, vi } from 'vitest'

vi.mock('@nextcloud/axios', () => ({
	default: { get: vi.fn() },
}))

vi.mock(import('@nextcloud/router'), async (importOriginal) => {
	const actual = await importOriginal()
	return {
		...actual,
		generateOcsUrl: vi.fn((template: string, params: Record<string, string> = {}) => Object.entries(params).reduce(
			(url, [key, value]) => url.replace(`{${key}}`, String(value)),
			`/ocs/${template}`,
		)),
	}
})

// Imported after the mocks are registered
import ncAxios from '@nextcloud/axios'
import { fetchStreamActors, searchAccounts } from '../utils/actors.ts'

describe('fetchStreamActors', () => {
	beforeEach(() => {
		vi.clearAllMocks()
	})

	it('requests the accounts of the given stream filter', async () => {
		vi.mocked(ncAxios.get).mockResolvedValue({ data: { ocs: { data: [] } } })

		await fetchStreamActors('files')

		const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
		expect(url).toContain('/api/v2/activity/files/actors')
		// The list is what a restriction is picked from, so it carries none
		expect(url).not.toContain('search=')
		expect(url).not.toContain('from=')
		expect(url).not.toContain('actor=')
	})

	it('returns the accounts the server reported', async () => {
		vi.mocked(ncAxios.get).mockResolvedValue({
			data: { ocs: { data: [{ id: 'alice', displayName: 'Alice Cooper' }] } },
		})

		expect(await fetchStreamActors('all')).toEqual([{ id: 'alice', displayName: 'Alice Cooper' }])
	})

	it('passes the abort signal on to the request', async () => {
		vi.mocked(ncAxios.get).mockResolvedValue({ data: { ocs: { data: [] } } })
		const controller = new AbortController()

		await fetchStreamActors('all', controller.signal)

		expect(vi.mocked(ncAxios.get).mock.calls[0][1]).toEqual({ signal: controller.signal })
	})
})

describe('searchAccounts', () => {
	beforeEach(() => {
		vi.clearAllMocks()
	})

	it('asks core autocomplete for accounts matching the term', async () => {
		vi.mocked(ncAxios.get).mockResolvedValue({ data: { ocs: { data: [] } } })

		await searchAccounts('jon')

		const url = vi.mocked(ncAxios.get).mock.calls[0][0] as string
		// Core's endpoint rather than one of this app's own, so the instance's
		// account enumeration rules apply
		expect(url).toContain('/core/autocomplete/get')
		expect(url).toContain('search=jon')
		expect(url).toContain('shareTypes%5B%5D=0')
	})

	it('keeps accounts and drops everything else autocomplete returns', async () => {
		vi.mocked(ncAxios.get).mockResolvedValue({
			data: {
				ocs: {
					data: [
						{ id: 'jonas', label: 'Jonas Weber', source: 'users' },
						{ id: 'staff', label: 'Staff', source: 'groups' },
						{ id: 'jon@example.com', label: 'Jon', source: 'emails' },
					],
				},
			},
		})

		// Only an account can have authored an activity
		expect(await searchAccounts('jon')).toEqual([{ id: 'jonas', displayName: 'Jonas Weber' }])
	})
})
