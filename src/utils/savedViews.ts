/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { ref, watch } from 'vue'
import logger from './logger.ts'

export interface SavedView {
	id: string
	name: string
	filter: string       // OCP filter id ('all' | 'self' | 'by' | …)
	search: string
	person: string
	path: string         // file-path substring filter
	from: string         // YYYY-MM-DD or empty
	to: string
	alerts: boolean      // whether to notify on new matches
	lastSeenId: number   // highest activity id this view has notified about
}

const STORAGE_KEY = 'activity:saved-views'

/**
 * Load the user's saved views from localStorage.  Defensive: any parse error
 * or shape mismatch resets to an empty list rather than throwing, so a
 * corrupt entry can't lock the user out of the app.  Newer fields default
 * to falsy so older persisted views (from before the field existed) keep
 * working without a migration.
 */
function load(): SavedView[] {
	try {
		const raw = window.localStorage.getItem(STORAGE_KEY)
		if (!raw) return []
		const parsed = JSON.parse(raw)
		if (!Array.isArray(parsed)) return []
		return parsed
			.filter((v) => v && typeof v.id === 'string' && typeof v.name === 'string')
			.map((v) => ({
				id: String(v.id),
				name: String(v.name),
				filter: String(v.filter ?? 'all'),
				search: String(v.search ?? ''),
				person: String(v.person ?? ''),
				path: String(v.path ?? ''),
				from: String(v.from ?? ''),
				to: String(v.to ?? ''),
				alerts: Boolean(v.alerts ?? false),
				lastSeenId: Number(v.lastSeenId ?? 0),
			}))
	} catch (e) {
		logger.debug('Failed to load saved views', e as Error)
		return []
	}
}

function save(views: SavedView[]): void {
	try {
		window.localStorage.setItem(STORAGE_KEY, JSON.stringify(views))
	} catch (e) {
		logger.warn('Could not persist saved views', e as Error)
	}
}

// Single shared reactive list — cheap to share across components since
// localStorage is the source of truth and writes flush back through watch().
const views = ref<SavedView[]>(load())
watch(views, save, { deep: true })

export function useSavedViews() {
	return {
		views,
		add(view: Omit<SavedView, 'id'>): SavedView {
			const created: SavedView = { ...view, id: 'sv-' + Date.now().toString(36) }
			views.value.push(created)
			return created
		},
		remove(id: string): void {
			views.value = views.value.filter((v) => v.id !== id)
		},
		rename(id: string, name: string): void {
			const v = views.value.find((x) => x.id === id)
			if (v) v.name = name
		},
		setAlerts(id: string, on: boolean): void {
			const v = views.value.find((x) => x.id === id)
			if (v) v.alerts = on
		},
		setLastSeenId(id: string, lastSeenId: number): void {
			const v = views.value.find((x) => x.id === id)
			if (v) v.lastSeenId = lastSeenId
		},
	}
}
