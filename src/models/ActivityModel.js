/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GPL-3.0-or-later
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

import moment from '@nextcloud/moment'

export default class ActivityModel {

	_activity

	/**
	 * Create the activity object
	 *
	 * @param {object} rawActivity the activity object from the ocs response
	 */
	constructor(rawActivity) {
		if (typeof rawActivity !== 'object') {
			throw new Error('Received activity data is not an object.')
		}

		// Sanity checks
		if (typeof rawActivity.activity_id !== 'number') {
			throw new Error('The activity_id argument is not a valid string')
		}
		if (typeof rawActivity.type !== 'string' || rawActivity.type.trim() === '') {
			throw new Error('The activity_id argument is not a valid string')
		}
		if (typeof rawActivity.subject !== 'string' || rawActivity.subject.trim() === '') {
			throw new Error('The activity_id argument is not a valid string')
		}
		if (typeof rawActivity.icon !== 'string' || rawActivity.icon.trim() === '') {
			throw new Error('The activity_id argument is not a valid string')
		}
		if (typeof rawActivity.datetime !== 'string' || rawActivity.datetime.trim() === '') {
			throw new Error('The activity_id argument is not a valid string')
		}

		// store state
		this._activity = rawActivity
	}

	/**
	 * get the activity id
	 *
	 * @return {number}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get id() {
		return this._activity.id
	}

	/**
	 * Get the activity app
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get app() {
		return this._activity.app
	}

	/**
	 * Get the activity type
	 *
	 * @return {number}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get type() {
		return this._activity.type
	}

	/**
	 * Get the activity user
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get user() {
		return this._activity.user
	}

	/**
	 * Get the activity subject
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subject() {
		return this._activity.subject
	}

	/**
	 * Get the activity subject_rich template
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subjectRichTemplate() {
		return this._activity.subject_rich[0]
	}

	/**
	 * Get the activity subject_rich objects
	 *
	 * @return {Object<string, RichObject>}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subjectRichObjects() {
		if (Array.isArray(this._activity.subject_rich[1])) {
			return {}
		}

		return this._activity.subject_rich[1]
	}

	/**
	 * Get the activity message
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get message() {
		return this._activity.message
	}

	/**
	 * Get the activity message_rich template
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get messageRichTemplate() {
		return this._activity.message_rich[0]
	}

	/**
	 * Get the activity message_rich objects
	 *
	 * @return {Object<string, RichObject>}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get messageRichObjects() {
		if (Array.isArray(this._activity.message_rich[1])) {
			return {}
		}

		return this._activity.message_rich[1]
	}

	/**
	 * Get the object_type
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectType() {
		return this._activity.object_type
	}

	/**
	 * Get the activity object_id
	 *
	 * @return {number}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectId() {
		return this._activity.object_id
	}

	/**
	 * Get the activity object_name
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectName() {
		return this._activity.object_name
	}

	/**
	 * Get the activity link
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get link() {
		return this._activity.link
	}

	/**
	 * Get the activity icon
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get icon() {
		return this._activity.icon
	}

	/**
	 * Get the activity datetime
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get datetime() {
		return this._activity.datetime
	}

	/**
	 * Get the activity formatted date from the current date
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get dateFromNow() {
		return moment(this._activity.datetime).fromNow()
	}

	/**
	 * Get the activity formatted datetime
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get formattedDate() {
		return moment(this._activity.datetime).format('LLL')
	}

	/**
	 * Get the activity timestamp
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get timestamp() {
		return moment(this._activity.datetime).unix()
	}

}
