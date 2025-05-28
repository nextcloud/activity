/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { IRichObject } from '../models/types.ts'

import NcUserBubble from '@nextcloud/vue/components/NcUserBubble'
import AddressBookRichArgument from '../components/richArgumentsTypes/AddressBookRichArgument.vue'
import CalendarEventRichArgument from '../components/richArgumentsTypes/CalendarEventRichArgument.vue'
import CalendarRichArgument from '../components/richArgumentsTypes/CalendarRichArgument.vue'
import EmailRichArgument from '../components/richArgumentsTypes/EmailRichArgument.vue'
import FileRichArgument from '../components/richArgumentsTypes/FileRichArgument.vue'
import OpenGraphRichArgument from '../components/richArgumentsTypes/OpenGraphRichArgument.vue'
import SystemTagRichArgument from '../components/richArgumentsTypes/SystemTagRichArgument.vue'
import UnknownArgument from '../components/richArgumentsTypes/UnknownArgument.vue'
import UnknownLinkArgument from '../components/richArgumentsTypes/UnknownLinkArgument.vue'

/**
 * Map an collection of rich text objects to rich arguments for the RichText component
 *
 * @param richObjects - The rich text object
 */
export function mapRichObjectsToRichArguments(richObjects: Record<string, IRichObject>): Record<string, IRichObject> {
	const args = {}

	for (const richObjectName in richObjects) {
		args[richObjectName] = mapRichObjectToRichArgument(richObjects[richObjectName])
	}

	return args
}

/**
 * Map rich text object to rich argument for the RichText component
 *
 * @param richObject - The rich text object
 */
export function mapRichObjectToRichArgument(richObject: IRichObject): object {
	switch (richObject.type) {
		case 'file':
			return {
				component: FileRichArgument,
				props: richObject,
			}
		case 'user':
			if (richObject.server) {
				return {
					component: NcUserBubble,
					props: {
						avatarImage: 'icon-user',
						displayName: richObject.name,
						user: richObject.id,
						url: richObject.link,
					},
				}
			}
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
			if (richObject.link) {
				return {
					component: UnknownLinkArgument,
					props: richObject,
				}
			}
			return {
				component: UnknownArgument,
				props: richObject,
			}
	}
}
