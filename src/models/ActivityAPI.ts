/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import type ActivityModel from './ActivityModel.js'

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
