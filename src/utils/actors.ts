/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import ncAxios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'

/**
 * Share type of a regular account, as core's autocomplete expects it.
 */
const SHARE_TYPE_USER = 0

/**
 * Most accounts a name search offers.
 *
 * A dropdown is read, not scrolled through, so a wider net would only bury the
 * match the reader is typing towards.
 */
const ACCOUNT_SEARCH_LIMIT = 25

/**
 * An account that can be filtered by, as reported by the stream.
 */
export interface IStreamActor {
	/**
	 * The account name, to be sent back as the stream's `actor` parameter
	 */
	id: string
	/**
	 * Human readable name, falling back to the account name
	 */
	displayName: string
}

/**
 * The accounts that authored activities in a stream.
 *
 * Deliberately takes no search term or date range: this is the list a
 * restriction is picked from, so narrowing the stream must not narrow it.
 *
 * @param filter - The active stream filter
 * @param signal - Aborts the request when the stream is left
 */
export async function fetchStreamActors(filter: string, signal?: AbortSignal): Promise<IStreamActor[]> {
	const url = generateOcsUrl('apps/activity/api/v2/activity/{filter}/actors', { filter })
	const response = await ncAxios.get(`${url}?format=json`, { signal })
	return response.data.ocs.data as IStreamActor[]
}

/**
 * Accounts on the instance whose name matches a search term.
 *
 * The stream's own account list only names accounts that have already authored
 * something in it, which leaves everyone else unpickable. This covers the rest,
 * through core's autocomplete rather than an account listing of this app's own,
 * so the instance's enumeration rules — which decide who may find whom — apply
 * here exactly as they do in every other account picker.
 *
 * @param term - What the reader typed
 * @param signal - Aborts the request when a newer term supersedes it
 */
export async function searchAccounts(term: string, signal?: AbortSignal): Promise<IStreamActor[]> {
	const parameters = new URLSearchParams({
		format: 'json',
		search: term,
		itemType: '',
		itemId: '',
		limit: String(ACCOUNT_SEARCH_LIMIT),
	})
	parameters.append('shareTypes[]', String(SHARE_TYPE_USER))

	const url = generateOcsUrl('core/autocomplete/get')
	const response = await ncAxios.get(`${url}?${parameters.toString()}`, { signal })

	const results = response.data.ocs.data as { id: string, label: string, source: string }[]
	// Autocomplete also answers with groups, circles and mail addresses, none of
	// which can have authored an activity
	return results
		.filter((result) => result.source === 'users')
		.map((result) => ({ id: result.id, displayName: result.label }))
}
