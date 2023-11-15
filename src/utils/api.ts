/**
 * @copyright Copyright (c) 2023 Ferdinand Thiessen <opensource@fthiessen.de>
 *
 * @author Ferdinand Thiessen <opensource@fthiessen.de>
 *
 * @license AGPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

import type { ActivityFactoryQueryOptions, IActivityFactory, IActivityFilter, IActivitySidebarAction } from '../models/ActivityAPI'
import logger from './logger'

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
			},
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
