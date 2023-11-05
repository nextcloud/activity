/**
 * @copyright Copyright (c) 2023 Nextcloud GmbH
 *
 * @author Ferdinand Thiessen <opensource@fthiessen.de>
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
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
	view: string
	filename: string
}

export interface IRawActivity {
	activity_id: number
	app: string
	type: string,
	user: string,
	subject: string
	/**
	 * The subject as sting with placeholders and rich object for formatting.
	 *
	 * The rich object might be an empty array due to php associative array to json object conversion
	 */
	subject_rich: [string, Record<string, IRichObject> | readonly never[] ],
	message: string,
	/**
	 * The message as sting with placeholders and rich object for formatting.
	 *
	 * The rich object might be an empty array due to php associative array to json object conversion
	 */
	message_rich: [string, Record<string, IRichObject> | readonly never[]],
	object_type: string
	object_id: number,
	object_name: string
	link: string
	icon: string
	datetime: string
	previews?: IPreview[]
}
