/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

export interface IActivityType {
	/** The activity's description */
	desc: string
	/** List of available methods to send a notification */
	methods: string[]
	/** Enabled state of a method */
	[key: string]: boolean | string | string[]
}

export interface IActivityGroup {
	/** The name of the activity group */
	name: string
	/** List of activities */
	activities: IActivityType[]
}
