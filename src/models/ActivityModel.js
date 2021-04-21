/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GNU AGPL version 3 or any later version
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

	_activity;

	/**
	 * Create the activity object
	 *
	 * @param {Object} rawActivity the activity object from the ocs response
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
	 * @returns {int}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get id() {
		return this._activity.id
	}

	/**
	 * Get the activity app
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get app() {
		return this._activity.app
	}

	/**
	 * Get the activity type
	 *
	 * @returns {int}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get type() {
		return this._activity.type
	}

	/**
	 * Get the activity user
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get user() {
		return this._activity.user
	}

	/**
	 * Get the activity subject
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subject() {
		return this._activity.subject
	}

	/**
	 * Get the activity subject_rich template
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subjectRichTemplate() {
		return this._activity.subject_rich[0]
	}

	/**
	 * Get the activity subject_rich objects
	 *
	 * @returns {Object.<string, RichObject>}
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
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get message() {
		return this._activity.message
	}

	/**
	 * Get the activity message_rich template
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get messageRichTemplate() {
		return this._activity.message_rich[0]
	}

	/**
	 * Get the activity message_rich objects
	 *
	 * @returns {Object.<string, RichObject>}
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
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectType() {
		return this._activity.object_type
	}

	/**
	 * Get the activity object_id
	 *
	 * @returns {int}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectId() {
		return this._activity.object_id
	}

	/**
	 * Get the activity object_name
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectName() {
		return this._activity.object_name
	}

	/**
	 * Get the activity link
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get link() {
		return this._activity.link
	}

	/**
	 * Get the activity icon
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get icon() {
		return this._activity.icon
	}

	/**
	 * Get the activity datetime
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get datetime() {
		return this._activity.datetime
	}

	/**
	 * Get the activity formatted date from the current date
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get dateFromNow() {
		return moment(this._activity.datetime).fromNow()
	}

	/**
	 * Get the activity formatted datetime
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get formattedDate() {
		return moment(this._activity.datetime).format('LLL')
	}

	/**
	 * Get the activity timestamp
	 *
	 * @returns {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get timestamp() {
		return moment(this._activity.datetime).unix()
	}

}
