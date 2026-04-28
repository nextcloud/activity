/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { ref, watch } from 'vue'
import logger from './logger.ts'

export type Density = 'compact' | 'cozy' | 'comfortable'

const STORAGE_KEY = 'activity:density'
const VALID: Density[] = ['compact', 'cozy', 'comfortable']

function load(): Density {
	try {
		const raw = window.localStorage.getItem(STORAGE_KEY)
		if (raw && (VALID as string[]).includes(raw)) {
			return raw as Density
		}
	} catch (e) {
		logger.debug('Failed to load density preference', e as Error)
	}
	return 'cozy'
}

const density = ref<Density>(load())

watch(density, (value) => {
	try {
		window.localStorage.setItem(STORAGE_KEY, value)
	} catch (e) {
		logger.warn('Could not persist density preference', e as Error)
	}
	// Keep a data-attribute on <html> so CSS can match it from any component
	// without needing to plumb the value through props.
	document.documentElement.setAttribute('data-activity-density', value)
}, { immediate: true })

export function useDensity() {
	return { density }
}
