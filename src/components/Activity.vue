<!--
  - @copyright 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  -
  - @license AGPL-3.0-or-later
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
		<NcAvatar :class="[applyMonochromeIconColor, 'activity-entry__icon', 'activity-icon']"
			:disable-menu="true"
			:disable-tooltip="true"
			:url="activity.icon"
			:size="15" />

		<div class="activity-entry__content">
			<NcRichText class="activity-entry__content__subject" :text="subjectText" :arguments="subjectArguments" />
			<NcRichText class="activity-entry__content__message" :text="messageText" :arguments="messageArguments" />
		</div>
		<span class="hidden-visually">{{ activity.formattedDate }}</span>
		<span :title="activity.formattedDate" class="activity-entry__date">{{ dateFromNow }}</span>
		<div v-if="showPreviews" class="activity-entry__preview-wrapper">
			<component :is="preview.link ? 'a' : 'span'"
				v-for="preview, index in activity.previews"
				:key="preview.fileId ?? `preview-${index}`"
				class="activity-entry__preview"
				:href="preview.link">
				<img class="activity-entry__preview-image"
					:class="{
						'activity-entry__preview-mimetype': preview.isMimeTypeIcon,
					}"
					:src="preview.source"
					:alt="preview.link ? t('activity', 'Open {filename}', preview) : ''">
			</component>
		</div>
	</li>
</template>

<script>
import {
	NcAvatar,
	NcUserBubble,
	NcRichText,
} from '@nextcloud/vue'

import ActivityModel from '../models/ActivityModel.ts'

import FileRichArgument from './richArgumentsTypes/FileRichArgument.vue'
import EmailRichArgument from './richArgumentsTypes/EmailRichArgument.vue'
import SystemTagRichArgument from './richArgumentsTypes/SystemTagRichArgument.vue'
import CalendarEventRichArgument from './richArgumentsTypes/CalendarEventRichArgument.vue'
import OpenGraphRichArgument from './richArgumentsTypes/OpenGraphRichArgument.vue'

/**
 * @typedef RichObject
 * @type {object}
 * @property {string} id - The id of the riche object.
 * @property {string} type - The type of the file object.
 */

export default {
	name: 'Activity',
	components: {
		NcAvatar,
		NcRichText,
	},
	props: {
		/**
		 * The activity to render.
		 */
		activity: {
			type: ActivityModel,
			required: true,
		},
		/**
		 * Whether to show previews
		 */
		showPreviews: {
			type: Boolean,
			default: false,
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
		 * @return {string} The activity's messageRichTemplate. Fallback to message if messageRichTemplate does not exists
		 */
		messageText() {
			return this.activity.messageRichTemplate || this.activity.message
		},
		/**
		 * @return {object} A map of rich arguments with a Component to build them.
		 */
		messageArguments() {
			return this.mapRichObjectsToRichArguments(this.activity.messageRichObjects)
		},
		/**
		 * @return {string} The activity's subjectRichTemplate. Fallback to subject if subjectRichTemplate does not exists
		 */
		subjectText() {
			return this.activity.subjectRichTemplate || this.activity.subject
		},
		/**
		 * @return {object} A map of rich arguments with a Component to build them.
		 */
		subjectArguments() {
			return this.mapRichObjectsToRichArguments(this.activity.subjectRichObjects)
		},
		applyMonochromeIconColor() {
			// copied from https://github.com/nextcloud/activity/blob/db919d45c45356082b17104614018e2c7e691996/js/script.js#L225
			const monochromeIcon = this.activity.type !== 'file_created' && this.activity.type !== 'file_deleted' && this.activity.type !== 'favorite' && !this.activity.icon.endsWith('-color.svg')
			if (monochromeIcon) {
				return 'monochrome'
			}
			return ''
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
		 *
		 * @param {Array.<Object<string, RichObject>>} richObjects - The rich text object
		 * @return {Object<string, object>}
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
		 *
		 * @param {Object<string, RichObject>} richObject - The rich text object
		 * @return {object}}
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
	flex-wrap: wrap;
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

	.avatardiv  {
		background-color: unset !important;
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

	&__preview-wrapper {
		// Force next line
		flex: 0 0 100%;
		// Proper spacing
		gap: 12px;
		// align with content
		margin-inline-start: 24px;
	}

	&__preview:hover {
		opacity: .75;
	}

	&__preview-image {
		height: 50px;
		width: 50px;

		// Only add borders for images, not for MIME types
		&:not(.activity-entry__preview-mimetype) {
			border: 2px solid var(--color-border);
			border-radius: var(--border-radius-large);

			&:hover {
				border-color: var(--color-main-text);
				outline: 2px solid var(--color-main-background);
			}
		}
	}
}
</style>
