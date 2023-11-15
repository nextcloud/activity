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

import type { IRichObject } from '../models/types'

import { NcUserBubble } from '@nextcloud/vue'
import FileRichArgument from '../components/richArgumentsTypes/FileRichArgument.vue'
import EmailRichArgument from '../components/richArgumentsTypes/EmailRichArgument.vue'
import SystemTagRichArgument from '../components/richArgumentsTypes/SystemTagRichArgument.vue'
import CalendarRichArgument from '../components/richArgumentsTypes/CalendarRichArgument.vue'
import CalendarEventRichArgument from '../components/richArgumentsTypes/CalendarEventRichArgument.vue'
import OpenGraphRichArgument from '../components/richArgumentsTypes/OpenGraphRichArgument.vue'
import AddressBookRichArgument from '../components/richArgumentsTypes/AddressBookRichArgument.vue'

/**
 * Map an collection of rich text objects to rich arguments for the RichText component
 *
 * @param {Record<string, IRichObject>} richObjects - The rich text object
 * @return {Record<string, IRichObject>}
 */
export function mapRichObjectsToRichArguments(richObjects: Record<string, IRichObject>) {
	const args = {}

	for (const richObjectName in richObjects) {
		args[richObjectName] = mapRichObjectToRichArgument(richObjects[richObjectName])
	}

	return args
}

/**
 * Map rich text object to rich argument for the RichText component
 *
 * @param {Record<string, IRichObject>} richObject - The rich text object
 * @return {object}}
 */
export function mapRichObjectToRichArgument(richObject: IRichObject) {
	switch (richObject.type) {
	case 'file':
		return {
			component: FileRichArgument,
			props: richObject,
		}
	case 'user':
		return {
			component: NcUserBubble,
			props: { displayName: richObject.name, user: richObject.id, url: richObject.link },
		}
	case 'group':
		return {
			component: NcUserBubble,
			props: {
				avatarImage: 'icon-group',
				displayName: richObject.name,
				primary: true,
			},
		}
	case 'email':
		return {
			component: EmailRichArgument,
			props: richObject,
		}
	case 'systemtag':
		return {
			component: SystemTagRichArgument,
			props: richObject,
		}
	case 'opengraph':
		return {
			component: OpenGraphRichArgument,
			props: richObject,
		}
	case 'calendar':
		return {
			component: CalendarRichArgument,
			props: richObject,
		}
	case 'calendar-event':
		return {
			component: CalendarEventRichArgument,
			props: richObject,
		}
	case 'addressbook':
		return {
			component: AddressBookRichArgument,
			props: richObject,
		}
	default:
		return richObject
	}
}
