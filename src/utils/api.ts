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
		OC: Nextcloud.v27.OC
		OCA?: {
			Viewer?: {
				open(options: { path?: string, fileInfo?: unknown }): void
				get mimetypes(): string[]
			}
		}
	}
}

export class ActivityAPI {

	static pluginName = 'OCA.Activity.SidebarPlugin'

	#sidebar_actions = [] as IActivitySidebarAction[]
	#sidebar_factories = [] as IActivityFactory[]
	#sidebar_filters = [] as IActivityFilter[]

	constructor() {
		window.OC.Plugins.attach(ActivityAPI.pluginName, this)
	}

	public destroy() {
		window.OC.Plugins.detach(ActivityAPI.pluginName, this)
		this.#sidebar_actions = []
		this.#sidebar_factories = []
		this.#sidebar_filters = []
	}

	public reload() {
		this.destroy()
		window.OC.Plugins.attach(ActivityAPI.pluginName, this)
	}

	/**
	 * Register an external action that should be shown in the Activity sidebar panel
	 * @param action the action
	 */
	public registerSidebarAction(action: IActivitySidebarAction) {
		this.#sidebar_actions.push(action)
		logger.debug('Registered new sidebar action')
	}

	/**
	 * Register a factory to provide activities not included in the stream
	 * @param factory the factory
	 */
	public registerSidebarEntries(factory: IActivityFactory) {
		this.#sidebar_factories.push(factory)
		logger.debug('Registered new sidebar entry factory')
	}

	/**
	 * Register an filter function to filter out activities on the sidebar, useful together with `registerSidebarEntries`
	 * @param filter The filter to apply
	 */
	public registerSidebarFilter(filter: IActivityFilter) {
		this.#sidebar_filters.push(filter)
		logger.debug('Registered new sidebar filter')
	}

	/**
	 * Get all external actions that should be showed in the Activity panel
	 */
	public getSidebarActions() {
		return this.#sidebar_actions ?? []
	}

	/**
	 * Get all additional activity stream entries for a given file object
	 * @param options Filter options for the additonal entries
	 */
	public async getAdditionalEntries(options: ActivityFactoryQueryOptions) {
		if (this.#sidebar_factories === undefined) {
			return []
		}

		const allPromises = this.#sidebar_factories.map(async (factory) => await factory(options))
		return (await Promise.all(allPromises)).flat()
	}

	/**
	 * Get all sidebar entry filters
	 */
	public getActivityFilters() {
		return this.#sidebar_filters ?? []
	}

}

let api: ActivityAPI | undefined
let numberKnownPlugins = 0

/**
 * Get cached sidebar api or reload on changes
 */
export const getSidebarApi = () => {
	const numberPlugins = window.OC.Plugins.getPlugins(ActivityAPI.pluginName).length

	if (!api) {
		api = new ActivityAPI()
	} else if (numberKnownPlugins !== numberPlugins) {
		api.reload()
	}

	numberKnownPlugins = numberPlugins
	return api
}
