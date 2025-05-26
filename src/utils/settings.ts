/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { IActivityType } from '../models/ActivitySettings.js'

/**
 * Return wether the notification method can be checked for the activity
 *
 * @param activity - the concerned activity
 * @param methodKey - the concerned method
 */
export function isActivityEnabled(activity: IActivityType, methodKey: string): boolean {
	return activity.methods.includes(methodKey)
}

/**
 * @param activities - List of the activities to check
 * @param methodKey - the method key for which to verify the checked value
 * @return Wether at least one input is checked for the given set of activities
 */
export function isOneInputUnChecked(activities: IActivityType[], methodKey: string): boolean {
	for (const activity of activities) {
		if (isActivityEnabled(activity, methodKey) && !activity[methodKey]) {
			return true
		}
	}

	return false
}
