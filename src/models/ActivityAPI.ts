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
import ActivityModel from './ActivityModel.js'

interface MountOptions {
	/**
	 * Trigger reloading the activities
	 */
	reload: () => void
	// eslint-disable-next-line @typescript-eslint/no-explicit-any
	fileInfo: any
	// eslint-disable-next-line @typescript-eslint/no-explicit-any
	context: any
}

export interface ActivityFactoryQueryOptions {
	/** File to show entries for */
	// eslint-disable-next-line @typescript-eslint/no-explicit-any
	fileInfo: any
	/** Limit the number of entries */
	limit?: number
	/** Offset for the entries queried */
	offset?: number
}

/**
 * An external action that shoud be mounted inside the Activity sidebar panel
 */
export interface IActivitySidebarAction {
	/**
	 * The action is called with the HTML element where is should be mounted
	 */
	mount: (element: HTMLElement, options: MountOptions) => void

	/**
	 * Called just before the sidebar is destroyed to allow plugins to cleanup
	 */
	unmount: () => void
}

/**
 * An external activity entry that should be shown in the activity stream
 */
export interface IActivitySidebarEntry {
	/**
	 * Timestamp for sorting in ms
	 */
	timestamp: number

	/**
	 * The action is called with the HTML element where is should be mounted
	 */
	mount: (element: HTMLElement, options: Omit<MountOptions, 'fileInfo'>) => void

	/**
	 * Called just before the sidebar is destroyed to allow plugins to cleanup
	 */
	unmount: () => void
}

export type IActivityFactory = (options: ActivityFactoryQueryOptions) => Promise<IActivitySidebarEntry[]>

export type IActivityFilter = (activity: ActivityModel) => boolean
