<!--
  - @copyright 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  - @author Ferdinand Thiessen <opensource@fthiessen.de>
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
		<NcAvatar :class="[applyMonochromeIconColor, 'activity-entry__icon', 'activity-icon', 'avatardiv--unknown']"
			:disable-menu="true"
			:disable-tooltip="true"
			:url="activity.icon"
			:size="20" />

		<div class="activity-entry__content">
			<NcRichText class="activity-entry__content__subject" :text="subjectText" :arguments="subjectArguments" />
			<NcRichText class="activity-entry__content__message" :text="messageText" :arguments="messageArguments" />
		</div>
		<span class="hidden-visually">{{ activity.formattedDate }}</span>
		<NcDateTime class="activity-entry__date"
			:timestamp="timestamp"
			:ignore-seconds="true"
			data-testid="activity-date" />
		<ul v-if="showPreviews" class="activity-entry__preview-wrapper">
			<li v-for="preview, index in activity.previews"
				:key="preview.fileId ?? `preview-${index}`">
				<component :is="preview.link ? 'a' : 'span'"
					class="activity-entry__preview"
					:href="preview.link"
					@click="handlePreviewClick($event, preview)">
					<img class="activity-entry__preview-image"
						:class="{
							'activity-entry__preview-mimetype': preview.isMimeTypeIcon,
						}"
						:src="preview.source"
						:alt="preview.link ? t('activity', 'Open {filename}', { filename: preview.filename }) : ''">
				</component>
			</li>
		</ul>
	</li>
</template>

<script lang="ts">
import type { IPreview } from '../../models/types'

import { translate as t } from '@nextcloud/l10n'
import { defineComponent } from 'vue'
import { mapRichObjectsToRichArguments } from '../../utils/richObjects.js'

import NcAvatar from '@nextcloud/vue/dist/Components/NcAvatar.js'
import NcDateTime from '@nextcloud/vue/dist/Components/NcDateTime.js'
import NcRichText from '@nextcloud/vue/dist/Components/NcRichText.js'

import ActivityModel from '../../models/ActivityModel.js'
import logger from '../../utils/logger.js'

/**
 * @typedef RichObject
 * @type {object}
 * @property {string} id - The id of the riche object.
 * @property {string} type - The type of the file object.
 */

export default defineComponent({
	name: 'GenericActivity',
	components: {
		NcAvatar,
		NcDateTime,
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
	computed: {
		/**
		 * The timestamp of the activity as JS timestamp
		 */
		timestamp() {
			return this.activity.timestamp * 1000
		},
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
			return mapRichObjectsToRichArguments(this.activity.messageRichObjects)
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
			return mapRichObjectsToRichArguments(this.activity.subjectRichObjects)
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
	methods: {
		t,

		/**
		 * Handle clicking a preview
		 * Check if viewer is available and can open the file, if not navigate to it
		 * @param event The click event
		 * @param preview The preview to open
		 */
		handlePreviewClick(event: MouseEvent, preview: IPreview) {
			if (preview.filePath && window?.OCA?.Viewer?.open !== undefined && window.OCA.Viewer.mimetypes.includes(preview.mimeType)) {
				try {
					window.OCA.Viewer.open({ path: preview.filePath.replace(/^\/[^/]+\/files/, '') })
					event.preventDefault()
					event.stopPropagation()
				} catch (error) {
					logger.debug(error as Error)
				}
			}
		},
	},
})
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
		margin-top: 2px;
	}

	.avatardiv  {
		box-sizing: content-box!important;
		background-color: unset !important;

		:deep(img) {
			border-radius: 0 !important;
		}
	}

	&__content {
		display: flex;
		flex-basis: min-content;
		flex-direction: column;
		flex-grow: 1;
		overflow-wrap: break-word;
		white-space: pre-wrap;
		word-break: break-word;
		overflow: hidden;

		&__subject {
			padding: 0 5px;
		}

		&__message {
			color: var(--color-text-lighter);
		}

		:deep(a) {
			font-weight: bold;

			&:hover {
				opacity: 0.7;
				text-decoration: underline;
			}
		}
	}

	&__actions {
		inset-block: -8px;
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
		padding-inline-start: 24px;
		display: flex;
		flex-wrap: wrap;
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
