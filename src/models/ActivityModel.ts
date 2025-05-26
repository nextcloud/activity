/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { IPreview, IRawActivity, IRichObject } from './types.ts'

import moment from '@nextcloud/moment'

export default class ActivityModel {
	_activity: IRawActivity

	/**
	 * Create the activity object
	 *
	 * @param rawActivity the activity object from the ocs response
	 */
	constructor(rawActivity: IRawActivity) {
		if (typeof rawActivity !== 'object') {
			throw new Error('Received activity data is not an object.')
		}

		// Sanity checks
		if (typeof rawActivity.activity_id !== 'number') {
			throw new Error('The activity_id argument is not a number')
		}
		if (typeof rawActivity.type !== 'string' || rawActivity.type.trim() === '') {
			throw new Error('The type argument is not a valid string')
		}
		if (typeof rawActivity.subject !== 'string' || rawActivity.subject.trim() === '') {
			throw new Error('The subject argument is not a valid string')
		}
		if (typeof rawActivity.icon !== 'string' || rawActivity.icon.trim() === '') {
			throw new Error('The icon argument is not a valid string')
		}
		if (typeof rawActivity.datetime !== 'string' || rawActivity.datetime.trim() === '') {
			throw new Error('The datetime argument is not a valid string')
		}

		// store state
		this._activity = rawActivity
	}

	/**
	 * get the activity id
	 */
	get id(): number {
		return this._activity.activity_id
	}

	/**
	 * Get the app causing the activity
	 */
	get app(): string {
		return this._activity.app
	}

	/**
	 * Get the activity type
	 */
	get type(): string {
		return this._activity.type
	}

	/**
	 * Get the user ID of the user causing the activity or affected by the activity
	 */
	get user(): string {
		return this._activity.user
	}

	/**
	 * Get the activity subject
	 */
	get subject(): string {
		return this._activity.subject
	}

	/**
	 * Get the activity subject_rich template
	 */
	get subjectRichTemplate(): string {
		return this._activity.subject_rich[0]
	}

	/**
	 * Get the activity subject_rich objects
	 */
	get subjectRichObjects(): Record<string, IRichObject> {
		if (Array.isArray(this._activity.subject_rich[1])) {
			return {}
		}

		return this._activity.subject_rich[1] as Record<string, IRichObject>
	}

	/**
	 * Get the activity message
	 */
	get message(): string {
		return this._activity.message
	}

	/**
	 * Get the activity message_rich template
	 */
	get messageRichTemplate(): string {
		return this._activity.message_rich[0]
	}

	/**
	 * Get the activity message_rich objects
	 */
	get messageRichObjects(): Record<string, IRichObject> {
		if (!Array.isArray(this._activity.message_rich[1])) {
			return {}
		}

		return this._activity.message_rich[1] as Record<string, IRichObject>
	}

	/**
	 * Get the object_type
	 */
	get objectType(): string {
		return this._activity.object_type
	}

	/**
	 * Get the activity object_id
	 */
	get objectId(): number {
		return this._activity.object_id
	}

	/**
	 * Get the activity object_name
	 */
	get objectName(): string {
		return this._activity.object_name
	}

	/**
	 * Get the activity link
	 */
	get link(): string {
		return this._activity.link
	}

	/**
	 * Get the activity icon
	 */
	get icon(): string {
		return this._activity.icon
	}

	/**
	 * Get the activity datetime
	 */
	get datetime(): string {
		return this._activity.datetime
	}

	/**
	 * Get the activity formatted date from the current date
	 */
	get dateFromNow(): string {
		return moment(this._activity.datetime).fromNow()
	}

	/**
	 * Get the activity formatted datetime
	 */
	get formattedDate(): string {
		return moment(this._activity.datetime).format('LLL')
	}

	/**
	 * Get the activity timestamp
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
