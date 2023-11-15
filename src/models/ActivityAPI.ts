import ActivityModel from './ActivityModel.js'
import logger from '../utils/logger.js'

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

interface FactoryQueryOptions {
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

type IActivityFactory = (options: FactoryQueryOptions) => Promise<IActivitySidebarEntry[]>

type IActivityFilter = (activity: ActivityModel) => boolean

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
export async function getAdditionalEntries(options: FactoryQueryOptions) {
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
