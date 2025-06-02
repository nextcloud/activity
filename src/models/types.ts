/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

/**
 * Interface of a raw activity from API
 */
export interface IRichObject {
	/**
	 * ID of the rich object
	 */
	id: string | number
	/**
	 * Name of the rich object
	 */
	name: string
	/**
	 * Type of the rich object
	 */
	type: string

	/**
	 * Additional rich object properties
	 */
	[key: string]: unknown
}

export interface IPreview {
	/**
	 * The URL of the link target
	 */
	link?: string
	/**
	 * The URL of the preview image
	 */
	source: string
	/**
	 * MIME type of the file
	 */
	mimeType: string
	/**
	 * Whether this is a MIME type icon or a real preview of the file
	 */
	isMimeTypeIcon: boolean
	/**
	 * The file ID
	 */
	fileId: number
	filePath?: string
	view: string
	filename: string
}

export interface IRawActivity {
	activity_id: number
	app: string
	type: string
	user: string
	subject: string
	/**
	 * The subject as sting with placeholders and rich object for formatting.
	 *
	 * The rich object might be an empty array due to php associative array to json object conversion
	 */
	subject_rich: [string, Record<string, IRichObject> | readonly never[] ]
	message: string
	/**
	 * The message as sting with placeholders and rich object for formatting.
	 *
	 * The rich object might be an empty array due to php associative array to json object conversion
	 */
	message_rich: [string, Record<string, IRichObject> | readonly never[]]
	object_type: string
	object_id: number
	object_name: string
	link: string
	icon: string
	datetime: string
	previews?: IPreview[]
}

export type FileInfo = {
	id: number
	name: string
	path: string
	mimetype: string
	icon: string
	type: string
	permissions: number
	mtime: number
	etag: string
	mountType: string
	hasPreview: boolean
	sharePermissions: number
	shareAttributes: object[]
}
