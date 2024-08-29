/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { IPreview, IRawActivity, IRichObject } from './types'
import moment from '@nextcloud/moment'

export default class ActivityModel {

	_activity: IRawActivity

	/**
	 * Create the activity object
	 *
	 * @param {IRawActivity} rawActivity the activity object from the ocs response
	 */
	constructor(rawActivity: IRawActivity) {
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
	get id(): number {
		return this._activity.activity_id
	}

	/**
	 * Get the app causing the activity
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get app(): string {
		return this._activity.app
	}

	/**
	 * Get the activity type
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get type(): string {
		return this._activity.type
	}

	/**
	 * Get the user ID of the user causing the activity or affected by the activity
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get user(): string {
		return this._activity.user
	}

	/**
	 * Get the activity subject
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subject(): string {
		return this._activity.subject
	}

	/**
	 * Get the activity subject_rich template
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subjectRichTemplate(): string {
		return this._activity.subject_rich[0]
	}

	/**
	 * Get the activity subject_rich objects
	 *
	 * @return {Record<string, IRichObject>}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get subjectRichObjects(): Record<string, IRichObject> {
		if (Array.isArray(this._activity.subject_rich[1])) {
			return {}
		}

		return this._activity.subject_rich[1] as Record<string, IRichObject>
	}

	/**
	 * Get the activity message
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get message(): string {
		return this._activity.message
	}

	/**
	 * Get the activity message_rich template
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get messageRichTemplate(): string {
		return this._activity.message_rich[0]
	}

	/**
	 * Get the activity message_rich objects
	 *
	 * @return {Record<string, IRichObject>}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get messageRichObjects(): Record<string, IRichObject> {
		if (!Array.isArray(this._activity.message_rich[1])) {
			return {}
		}

		return this._activity.message_rich[1] as Record<string, IRichObject>
	}

	/**
	 * Get the object_type
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectType(): string {
		return this._activity.object_type
	}

	/**
	 * Get the activity object_id
	 *
	 * @return {number}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectId(): number {
		return this._activity.object_id
	}

	/**
	 * Get the activity object_name
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get objectName(): string {
		return this._activity.object_name
	}

	/**
	 * Get the activity link
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get link(): string {
		return this._activity.link
	}

	/**
	 * Get the activity icon
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get icon(): string {
		return this._activity.icon
	}

	/**
	 * Get the activity datetime
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get datetime(): string {
		return this._activity.datetime
	}

	/**
	 * Get the activity formatted date from the current date
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get dateFromNow(): string {
		return moment(this._activity.datetime).fromNow()
	}

	/**
	 * Get the activity formatted datetime
	 *
	 * @return {string}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get formattedDate(): string {
		return moment(this._activity.datetime).format('LLL')
	}

	/**
	 * Get the activity timestamp
	 *
	 * @return {number}
	 * @readonly
	 * @memberof ActivityModel
	 */
	get timestamp(): number {
		return moment(this._activity.datetime).valueOf()
	}

	/**
	 * Get previews of affected files
	 */
	get previews(): IPreview[] {
		return this._activity.previews ?? []
	}

}
