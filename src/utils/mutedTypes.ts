/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { ref, watch } from 'vue'
import logger from './logger.ts'

const STORAGE_KEY = 'activity:muted-types'

function load(): string[] {
	try {
		const raw = window.localStorage.getItem(STORAGE_KEY)
		if (!raw) return []
		const parsed = JSON.parse(raw)
		if (!Array.isArray(parsed)) return []
		return parsed.filter((v) => typeof v === 'string')
	} catch (e) {
		logger.debug('Failed to load muted activity types', e as Error)
		return []
	}
}

const muted = ref<string[]>(load())

watch(muted, (v) => {
	try {
		window.localStorage.setItem(STORAGE_KEY, JSON.stringify(v))
	} catch (e) {
		logger.warn('Could not persist muted activity types', e as Error)
	}
}, { deep: true })

export function useMutedTypes() {
	return { muted }
}

export function isTypeMuted(type: string): boolean {
	return muted.value.includes(type)
}

export function addMutedType(type: string): void {
	if (!muted.value.includes(type)) {
		muted.value.push(type)
	}
}

export function removeMutedType(type: string): void {
	muted.value = muted.value.filter((t) => t !== type)
}
