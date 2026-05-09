/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { ref, watch } from 'vue'
import logger from './logger.ts'

const STORAGE_KEY = 'activity:pinned'

function load(): number[] {
	try {
		const raw = window.localStorage.getItem(STORAGE_KEY)
		if (!raw) return []
		const parsed = JSON.parse(raw)
		if (!Array.isArray(parsed)) return []
		return parsed.filter((v) => typeof v === 'number' && Number.isFinite(v))
	} catch (e) {
		logger.debug('Failed to load pinned activities', e as Error)
		return []
	}
}

const pinned = ref<number[]>(load())

watch(pinned, (v) => {
	try {
		window.localStorage.setItem(STORAGE_KEY, JSON.stringify(v))
	} catch (e) {
		logger.warn('Could not persist pinned activities', e as Error)
	}
}, { deep: true })

export function usePinnedActivities() {
	return { pinned }
}

export function isPinned(id: number): boolean {
	return pinned.value.includes(id)
}

export function pinActivity(id: number): void {
	if (id > 0 && !pinned.value.includes(id)) {
		pinned.value.unshift(id)
	}
}

export function unpinActivity(id: number): void {
	pinned.value = pinned.value.filter((x) => x !== id)
}

export function togglePinned(id: number): void {
	if (isPinned(id)) unpinActivity(id)
	else pinActivity(id)
}
