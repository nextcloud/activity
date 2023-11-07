import type ActivityModel from '../models/ActivityModel.js'
import logger from './logger.js'

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
 * Additional actions that should be shown for an activity
 */
export interface IActivityAction {
	/**
	 * Label of the action
	 */
	label: string
	/**
	 * An optional icon for the action, must be a valid SVG as string
	 */
	icon: string
	/**
	 * The handler that is called on click
	 */
	handler: (activity: ActivityModel) => void
}

type IActivityActionFactory = (options: { activity: ActivityModel, reload: () => void }) => IActivityAction[]

declare global {
	interface Window {
		OCA?: {
			Activity?: {
				/**
				 * Register new actions for a given activity type
				 */
				registerAction: (activityType: string, action: IActivityActionFactory) => void
				/**
				 * Register an external action that should be shown in the Activity sidebar panel
				 */
				registerSidebarAction: (action: IActivitySidebarAction) => void
				__sidebar_actions: IActivitySidebarAction[]
				__activity_actions: Record<string, IActivityActionFactory[]>
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
		__activity_actions: window.OCA.Activity?.__activity_actions ?? {},

		registerSidebarAction(action: IActivitySidebarAction) {
			logger.debug('Registered new sidebar action')
			window.OCA!.Activity!.__sidebar_actions.push(action)
		},

		registerAction(activityType: string, action: IActivityActionFactory) {
			window!.OCA!.Activity!.__activity_actions[activityType] = window.OCA!.Activity!.__activity_actions[activityType] ?? []
			window!.OCA!.Activity!.__activity_actions[activityType]!.push(action)
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
 * Get all additional actions for a given activity type
 * @param activityType The activity type for which to query
 */
export function getActions(activityType: string) {
	return window.OCA?.Activity?.__activity_actions?.[activityType] ?? []
}
