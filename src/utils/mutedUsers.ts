/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { ref, watch } from 'vue'
import logger from './logger.ts'

const STORAGE_KEY = 'activity:muted-users'

function load(): string[] {
	try {
		const raw = window.localStorage.getItem(STORAGE_KEY)
		if (!raw) return []
		const parsed = JSON.parse(raw)
		if (!Array.isArray(parsed)) return []
		return parsed.filter((v) => typeof v === 'string')
	} catch (e) {
		logger.debug('Failed to load muted activity users', e as Error)
		return []
	}
}

const muted = ref<string[]>(load())

watch(muted, (v) => {
	try {
		window.localStorage.setItem(STORAGE_KEY, JSON.stringify(v))
	} catch (e) {
		logger.warn('Could not persist muted activity users', e as Error)
	}
}, { deep: true })

export function useMutedUsers() {
	return { muted }
}

export function isUserMuted(uid: string): boolean {
	return muted.value.includes(uid)
}

export function addMutedUser(uid: string): void {
	if (uid && !muted.value.includes(uid)) {
		muted.value.push(uid)
	}
}

export function removeMutedUser(uid: string): void {
	muted.value = muted.value.filter((u) => u !== uid)
}
