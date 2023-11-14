/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import type { IActivityType } from '../models/ActivitySettings.js'

/**
 * Return wether the notification method can be checked for the activity
 *
 * @param activity - the concerned activity
 * @param methodKey - the concerned method
 * @return {boolean}
 */
export function isActivityEnabled(activity: IActivityType, methodKey: string) {
	return activity.methods.includes(methodKey)
}

/**
 * @param activities - List of the activities to check
 * @param methodKey - the method key for which to verify the checked value
 * @return {boolean} Wether at least one input is checked for the given set of activities
 */
export function isOneInputUnChecked(activities: IActivityType[], methodKey: string) {
	for (const activity of activities) {
		if (isActivityEnabled(activity, methodKey) && !activity[methodKey]) {
			return true
		}
	}

	return false
}
