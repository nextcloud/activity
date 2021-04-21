<!--
  - @copyright 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -->

<template>
	<li class="activity-entry">
		<Avatar
			class="activity-entry__icon"
			:disable-menu="true"
			:disable-tooltip="true"
			:url="activity.icon"
			:size="15" />

		<div class="activity-entry__content">
			<RichText class="activity-entry__content__subject" :text="subjectText" :arguments="subjectArguments" />
			<RichText class="activity-entry__content__message" :text="messageText" :arguments="messageArguments" />
		</div>

		<span v-tooltip.bottom="activity.formattedDate" class="activity-entry__date">{{ dateFromNow }}</span>
	</li>
</template>

<script>
import Avatar from '@nextcloud/vue/dist/Components/Avatar'
import UserBubble from '@nextcloud/vue/dist/Components/UserBubble'
import RichText from '@juliushaertl/vue-richtext'
import Tooltip from '@nextcloud/vue/dist/Directives/Tooltip'

import ActivityModel from '../models/ActivityModel'

import FileRichArgument from './richArgumentsTypes/FileRichArgument'
import EmailRichArgument from './richArgumentsTypes/EmailRichArgument'
import SystemTagRichArgument from './richArgumentsTypes/SystemTagRichArgument'
import CalendarEventRichArgument from './richArgumentsTypes/CalendarEventRichArgument'
import OpenGraphRichArgument from './richArgumentsTypes/OpenGraphRichArgument'

export default {
	name: 'Activity',
	components: {
		Avatar,
		RichText,
	},
	directives: {
		tooltip: Tooltip,
	},
	props: {
		/**
		 * The activity to render.
		 */
		activity: {
			type: ActivityModel,
			required: true,
		},
	},
	data() {
		return {
			dateFromNow: '',
			dateInterval: 0,
		}
	},
	computed: {
		/**
		 * @returns {String} The activity's messageRichTemplate. Fallback to message if messageRichTemplate does not exists
		 */
		messageText() {
			return this.activity.messageRichTemplate || this.activity.message
		},
		/**
		 * @returns {Object} A map of rich arguments with a Component to build them.
		 */
		messageArguments() {
			return this.mapRichObjectsToRichArguments(this.activity.messageRichObjects)
		},
		/**
		 * @returns {String} The activity's subjectRichTemplate. Fallback to subject if subjectRichTemplate does not exists
		 */
		subjectText() {
			return this.activity.subjectRichTemplate || this.activity.subject
		},
		/**
		 * @returns {Object} A map of rich arguments with a Component to build them.
		 */
		subjectArguments() {
			return this.mapRichObjectsToRichArguments(this.activity.subjectRichObjects)
		},
	},
	created() {
		this.updateDateFromNow()
		this.dateInterval = setInterval(this.updateDateFromNow, 60 * 1000)
	},
	destroyed() {
		clearInterval(this.dateInterval)
	},
	methods: {
		updateDateFromNow() {
			this.dateFromNow = this.activity.dateFromNow
		},
		/**
		 * Map an collection of rich text objects to rich arguments for the RichText component
		 * @arg {Object.<string, RichObject>} richObjects the collection of rich text objects
		 * @returns {Object.<string, RichArgument>}
		 */
		mapRichObjectsToRichArguments(richObjects) {
			const args = {}

			for (const richObjectName in richObjects) {
				args[richObjectName] = this.mapRichObjectToRichArgument(richObjects[richObjectName])
			}

			return args
		},
		/**
		 * Map rich text object to rich argument for the RichText component
		 * @arg {RichObject} richObject the rich text object
		 * @returns {RichArgument}
		 */
		mapRichObjectToRichArgument(richObject) {
			switch (richObject.type) {
			case 'file':
				return {
					component: FileRichArgument,
					props: richObject,
				}
			case 'user':
				return {
					component: UserBubble,
					props: { displayName: richObject.name, user: richObject.id, url: richObject.link },
				}
			case 'group':
				return {
					component: UserBubble,
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
			case 'calendar-event':
				return {
					component: CalendarEventRichArgument,
					props: richObject,
				}
			default:
				return richObject
			}
		},
	},
}
</script>
<style lang="scss" scoped>
.activity-entry {
	display: flex;
	align-items: flex-start;
	width: 100%;
	height: var(--height);
	min-height: 32px;
	padding: 8px 0;

	&__icon {
		opacity: 0.5;
		margin-top: 4px;
		margin-right: 8px;
	}

	&__content {
		display: flex;
		flex-direction: column;
		flex-grow: 1;
		overflow-wrap: break-word;
		white-space: pre-wrap;
		word-break: break-word;
		overflow: hidden;

		&__message {
			color: var(--color-text-lighter);
		}

		::v-deep a {
			font-weight: bold;

			&:hover {
				opacity: 0.7;
				text-decoration: underline;
			}
		}
	}

	&__date {
		color: var(--color-text-lighter);
		margin-left: 5px;
		flex-shrink: 0;
	}
}
</style>
