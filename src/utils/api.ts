/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { ActivityFactoryQueryOptions, IActivityFactory, IActivityFilter, IActivitySidebarAction } from '../models/ActivityAPI.ts'

import logger from './logger.ts'

declare global {
	interface Window {
		OCA?: {
			Activity?: {
				/**
				 * Register new actions for a given activity type
				 */
				registerSidebarEntries: (factory: IActivityFactory) => void
				/**
				 * Register an external action that should be shown in the Activity sidebar panel
				 */
				registerSidebarAction: (action: IActivitySidebarAction) => void
				/**
				 * Register an filter function to filter out activities on the sidebar, useful together with `registerSidebarEntries`
				 */
				registerSidebarFilter: (filter: IActivityFilter) => void
				__sidebar_actions: IActivitySidebarAction[]
				__sidebar_factories: IActivityFactory[]
				__sidebar_filters: IActivityFilter[]
			}
			Viewer?: {
				open(options: { path?: string, fileInfo?: unknown }): void
				get mimetypes(): string[]
			}
		}
	}
}

/**
 * Register the global API
 */
export function registerGlobalAPI() {
	window.OCA = window.OCA ?? {}
	window.OCA.Activity = {
		...window.OCA.Activity,
		__sidebar_actions: window.OCA.Activity?.__sidebar_actions ?? [],
		__sidebar_factories: window.OCA.Activity?.__sidebar_factories ?? [],
		__sidebar_filters: window.OCA.Activity?.__sidebar_filters ?? [],

		registerSidebarAction(action: IActivitySidebarAction) {
			window.OCA!.Activity!.__sidebar_actions.push(action)
			logger.debug('Registered new sidebar action')
		},

		registerSidebarEntries(factory: IActivityFactory) {
			window!.OCA!.Activity!.__sidebar_factories.push(factory)
			logger.debug('Registered new sidebar actions factory')
		},

		registerSidebarFilter(filter: IActivityFilter) {
			window!.OCA!.Activity!.__sidebar_filters.push(filter)
		},
	}

	logger.info('Activity API registered')
}

/**
 * Get all external actions that should be showed in the Activity panel
 */
export function getSidebarActions() {
	return window.OCA?.Activity?.__sidebar_actions ?? []
}

/**
 * Get all additional activity stream entries for a given file object
 *
 * @param options Filter options for the additonal entries
 */
export async function getAdditionalEntries(options: ActivityFactoryQueryOptions) {
	if (window.OCA?.Activity?.__sidebar_factories === undefined) {
		return []
	}

	const allPromises = window.OCA.Activity.__sidebar_factories.map(async (factory) => await factory(options))
	return (await Promise.all(allPromises)).flat()
}

/**
 * Get all sidebar entry filters
 */
export function getActivityFilters() {
	return window.OCA?.Activity?.__sidebar_filters ?? []
}
