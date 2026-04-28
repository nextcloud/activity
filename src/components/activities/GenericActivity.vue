<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<li class="activity-entry" :class="`activity-entry--type-${typeFamily}`">
		<div class="activity-entry__avatar">
			<NcAvatar
				v-if="hasActor"
				class="activity-entry__avatar-actor"
				:user="activity.user"
				:size="32"
				:disable-menu="false"
				:show-user-status="false" />
			<NcAvatar
				v-else
				class="activity-entry__avatar-event activity-icon avatardiv--unknown"
				:class="[applyMonochromeIconColor]"
				:disable-menu="true"
				:disable-tooltip="true"
				:url="activity.icon"
				:size="32" />
			<img
				v-if="hasActor"
				class="activity-entry__avatar-badge"
				:src="activity.icon"
				:alt="''"
				role="presentation">
		</div>

		<div class="activity-entry__content">
			<NcRichText class="activity-entry__content__subject" :text="subjectText" :arguments="subjectArguments" />
			<NcRichText class="activity-entry__content__message" :text="messageText" :arguments="messageArguments" />
		</div>
		<span class="hidden-visually">{{ activity.formattedDate }}</span>
		<NcDateTime
			class="activity-entry__date"
			:timestamp="timestamp"
			:ignore-seconds="true"
			data-testid="activity-date" />
		<ul v-if="showPreviews" class="activity-entry__preview-wrapper">
			<li
				v-for="preview, index in activity.previews"
				:key="preview.fileId ?? `preview-${index}`">
				<component
					:is="preview.link ? 'a' : 'span'"
					class="activity-entry__preview"
					:href="preview.link"
					@click="handlePreviewClick($event, preview)">
					<img
						class="activity-entry__preview-image"
						:class="{
							'activity-entry__preview-mimetype': preview.isMimeTypeIcon,
						}"
						:src="preview.source"
						:alt="preview.link ? t('activity', 'Open {filename}', { filename: preview.filename }) : ''"
						@mouseenter="!preview.isMimeTypeIcon && onPreviewEnter($event, index)"
						@mouseleave="onPreviewLeave">
					<!--
						Mirror image rendered as a floating popup anchored
						beneath the small thumbnail.  Only for real previews —
						MIME-type icons stay 80px and don't pop up.
					-->
					<img
						v-if="!preview.isMimeTypeIcon && hoveredPreviewIndex === index"
						class="activity-entry__preview-popup"
						:style="{ left: popupX + 'px', top: popupY + 'px' }"
						:src="preview.source"
						alt=""
						aria-hidden="true">
				</component>
			</li>
		</ul>
	</li>
</template>

<script lang="ts">
import type { IPreview } from '../../models/types.ts'

import { translate as t } from '@nextcloud/l10n'
import { defineComponent } from 'vue'
import NcAvatar from '@nextcloud/vue/components/NcAvatar'
import NcDateTime from '@nextcloud/vue/components/NcDateTime'
import NcRichText from '@nextcloud/vue/components/NcRichText'
import ActivityModel from '../../models/ActivityModel.js'
import logger from '../../utils/logger.js'
import { mapRichObjectsToRichArguments } from '../../utils/richObjects.js'

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

	data() {
		return {
			// Index of the preview the cursor is currently over (or -1 when
			// no preview is hovered).  Only one popup is visible per row at
			// a time, which is what the v-if on the popup binds against.
			hoveredPreviewIndex: -1 as number,
			// Viewport coordinates of the popup's top-left corner, kept in
			// sync with the cursor by onPreviewMove.
			popupX: 0,
			popupY: 0,
		}
	},

	computed: {
		/**
		 * The timestamp of the activity as JS timestamp
		 */
		timestamp() {
			return this.activity.timestamp
		},

		/**
		 * @return The activity's messageRichTemplate. Fallback to message if messageRichTemplate does not exists
		 */
		messageText() {
			return this.activity.messageRichTemplate || this.activity.message
		},

		/**
		 * @return A map of rich arguments with a Component to build them.
		 */
		messageArguments() {
			return mapRichObjectsToRichArguments(this.activity.messageRichObjects)
		},

		/**
		 * @return The activity's subjectRichTemplate. Fallback to subject if subjectRichTemplate does not exists
		 */
		subjectText() {
			return this.activity.subjectRichTemplate || this.activity.subject
		},

		/**
		 * @return A map of rich arguments with a Component to build them.
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

		/**
		 * Whether the activity has a known actor whose user avatar can be rendered.
		 * "system" / empty user IDs fall back to the legacy event-type icon so we
		 * don't mis-attribute automated events to a real person.
		 */
		hasActor() {
			const uid = this.activity.user
			return typeof uid === 'string' && uid !== '' && uid !== 'system'
		},

		/**
		 * Bucket the activity into a small set of visual families used for the
		 * left-edge type accent.  Activities not matched here fall through to
		 * the "neutral" bucket and render without an accent so unknown types
		 * never get a misleading colour.
		 */
		typeFamily() {
			const type = this.activity.type
			if (type === 'file_created') return 'created'
			if (type === 'file_deleted') return 'deleted'
			if (type === 'file_changed' || type === 'file_restored') return 'changed'
			if (type === 'favorite') return 'favorite'
			if (type === 'comments') return 'comment'
			if (type.startsWith('shared') || type.startsWith('share') || type.startsWith('unshare') || type.includes('_share')) return 'share'
			if (type === 'remote_share') return 'share'
			return 'neutral'
		},
	},

	methods: {
		t,

		/**
		 * Handle clicking a preview
		 * Check if viewer is available and can open the file, if not navigate to it
		 *
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

		/**
		 * Anchor the popup just below the thumbnail the cursor entered,
		 * left-aligned with it.  If the popup would overflow the viewport
		 * to the right we shift it left so it stays fully on-screen; if
		 * there is not enough room below the thumbnail we flip it above.
		 *
		 * Edge detection uses the popup's CSS max-width / max-height as
		 * an upper-bound fudge — close enough without having to wait for
		 * the image to load and report its real natural size.
		 */
		onPreviewEnter(event: MouseEvent, index: number) {
			const target = event.currentTarget as HTMLElement | null
			if (!target) return
			const rect = target.getBoundingClientRect()
			const offset = 8
			const margin = 8
			const fudgeW = Math.min(window.innerWidth * 0.6, 700)
			const fudgeH = window.innerHeight * 0.7

			let x = rect.left
			let y = rect.bottom + offset
			if (x + fudgeW + margin > window.innerWidth) {
				x = Math.max(margin, window.innerWidth - margin - fudgeW)
			}
			if (y + fudgeH + margin > window.innerHeight) {
				y = Math.max(margin, rect.top - offset - fudgeH)
			}

			this.popupX = x
			this.popupY = y
			this.hoveredPreviewIndex = index
		},

		onPreviewLeave() {
			this.hoveredPreviewIndex = -1
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
	padding: 8px 0 8px 12px;
	border-inline-start: 3px solid transparent;
	border-radius: var(--border-radius);
	transition: background-color 150ms ease, border-color 150ms ease;

	&--type-created     { border-inline-start-color: var(--color-success, #46ba61); }
	&--type-deleted     { border-inline-start-color: var(--color-error,   #e9322d); }
	&--type-changed     { border-inline-start-color: var(--color-primary, #0082c9); }
	&--type-share       { border-inline-start-color: #8e44ad; }
	&--type-comment     { border-inline-start-color: #1abc9c; }
	&--type-favorite    { border-inline-start-color: var(--color-warning, #f4a261); }
	&--type-neutral     { border-inline-start-color: var(--color-border-dark, #d0d0d0); }

	&:hover {
		background-color: var(--color-background-hover);
	}

	&__avatar {
		position: relative;
		flex-shrink: 0;
		margin-top: 2px;
		margin-inline-end: 8px;
		width: 32px;
		height: 32px;
	}

	&__avatar-event {
		opacity: 0.5;
	}

	&__avatar-badge {
		position: absolute;
		right: -2px;
		bottom: -2px;
		width: 16px;
		height: 16px;
		padding: 2px;
		border-radius: 50%;
		background: var(--color-main-background);
		box-shadow: 0 0 0 1px var(--color-border);
		// Most event icons are monochrome SVGs that read better tinted
		filter: var(--background-invert-if-dark);
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
		height: 80px;
		width: 80px;
		object-fit: cover;
		transition: border-color 150ms ease;

		&:not(.activity-entry__preview-mimetype) {
			border: 2px solid var(--color-border);
			border-radius: var(--border-radius-large);

			&:hover {
				border-color: var(--color-primary-element);
			}
		}
	}

	// Floating preview popup that follows the cursor (lower-right of
	// the pointer).  `top` and `left` are written as inline styles by
	// the component on every mousemove; the rules here only handle
	// presentation.  No border or outline — the shadow alone separates
	// it from the page underneath.  pointer-events: none keeps the
	// cursor on the thumbnail so click + hover stay on the same target.
	&__preview-popup {
		position: fixed;
		max-width: min(60vw, 700px);
		max-height: 70vh;
		width: auto;
		height: auto;
		border: none;
		outline: none;
		border-radius: var(--border-radius-large);
		box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
		background: var(--color-main-background);
		pointer-events: none;
		z-index: 1000;
	}
}
</style>
