<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<li
		class="activity-entry"
		:class="[`activity-entry--type-${typeFamily}`, { 'activity-entry--highlighted': isHighlighted }]"
		:data-activity-id="activity.id"
		@contextmenu.prevent="onContextMenu">
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
		<div class="activity-entry__row-actions">
			<button
				type="button"
				class="activity-entry__row-action"
				:title="t('activity', 'Copy link to this activity')"
				:aria-label="t('activity', 'Copy link to this activity')"
				@click.stop="copyPermalink">
				<IconLink :size="16" />
			</button>
			<button
				type="button"
				class="activity-entry__row-action"
				:title="t('activity', 'More actions')"
				:aria-label="t('activity', 'More actions')"
				@click.stop="onActionsClick">
				<IconDotsHorizontal :size="16" />
			</button>
		</div>
		<div
			v-if="contextMenuOpen"
			ref="contextMenuEl"
			class="activity-entry__context-menu"
			:style="{ left: contextMenuX + 'px', top: contextMenuY + 'px' }"
			role="menu">
			<button v-if="hasFileTarget" type="button" role="menuitem" @click="contextOpenInFiles">
				<IconFolder :size="16" />
				<span>{{ t('activity', 'Open in Files') }}</span>
			</button>
			<button type="button" role="menuitem" @click="copyPermalink">
				<IconLink :size="16" />
				<span>{{ t('activity', 'Copy link') }}</span>
			</button>
			<button type="button" role="menuitem" @click="contextMuteType">
				<IconBellOff :size="16" />
				<span>{{ t('activity', 'Mute "{type}"', { type: activity.type }) }}</span>
			</button>
		</div>
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
						@mousemove="!preview.isMimeTypeIcon && onPreviewMove($event, index)"
						@mouseleave="onPreviewLeave">
					<!--
						Mirror image rendered as a floating popup that follows
						the cursor (lower-right of the pointer).  Only for
						real previews — MIME-type icons stay 80px and don't
						pop up.
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

import { showSuccess, showError } from '@nextcloud/dialogs'
import { translate as t } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'
import { defineComponent } from 'vue'
import NcAvatar from '@nextcloud/vue/components/NcAvatar'
import NcDateTime from '@nextcloud/vue/components/NcDateTime'
import NcRichText from '@nextcloud/vue/components/NcRichText'
import IconLink from 'vue-material-design-icons/LinkVariant.vue'
import IconDotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
import IconFolder from 'vue-material-design-icons/FolderOpen.vue'
import IconBellOff from 'vue-material-design-icons/BellOff.vue'
import ActivityModel from '../../models/ActivityModel.js'
import logger from '../../utils/logger.js'
import { mapRichObjectsToRichArguments } from '../../utils/richObjects.js'
import { addMutedType, isTypeMuted } from '../../utils/mutedTypes.ts'

export default defineComponent({
	name: 'GenericActivity',
	components: {
		NcAvatar,
		NcDateTime,
		NcRichText,
		IconLink,
		IconDotsHorizontal,
		IconFolder,
		IconBellOff,
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
			// Right-click / actions menu state.  Single instance per row.
			contextMenuOpen: false,
			contextMenuX: 0,
			contextMenuY: 0,
		}
	},

	mounted() {
		document.addEventListener('click', this.closeContextMenuOnDocClick)
		document.addEventListener('keydown', this.closeContextMenuOnEsc)
	},

	beforeUnmount() {
		document.removeEventListener('click', this.closeContextMenuOnDocClick)
		document.removeEventListener('keydown', this.closeContextMenuOnEsc)
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
		 * True when the URL hash contains `id=<this row's id>`.  Used to
		 * paint a soft highlight on a row deep-linked via permalink.
		 */
		isHighlighted() {
			try {
				// Hash format example: "#/all?id=42"
				const hash = window.location.hash || ''
				const q = hash.split('?')[1]
				if (!q) return false
				const params = new URLSearchParams(q)
				return Number(params.get('id')) === this.activity.id
			} catch {
				return false
			}
		},

		/** True when the activity targets a file we know how to deep-link to. */
		hasFileTarget() {
			return this.activity.objectType === 'files'
				&& Number(this.activity.objectId) > 0
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
		 * Place the popup to the lower-right of the cursor so it tracks
		 * the pointer as the user moves over the small thumbnail.  If the
		 * popup would overflow the viewport's right or bottom edge it is
		 * flipped to the other side of the cursor so it stays fully
		 * visible.
		 *
		 * Edge detection uses the popup's CSS max-width / max-height as
		 * an upper-bound fudge — close enough without having to wait for
		 * the image to load and report its real natural size.
		 */
		onPreviewMove(event: MouseEvent, index: number) {
			const offset = 16
			const margin = 8
			const fudgeW = Math.min(window.innerWidth * 0.6, 700)
			const fudgeH = window.innerHeight * 0.7

			let x = event.clientX + offset
			let y = event.clientY + offset
			if (x + fudgeW + margin > window.innerWidth) {
				x = Math.max(margin, event.clientX - offset - fudgeW)
			}
			if (y + fudgeH + margin > window.innerHeight) {
				y = Math.max(margin, event.clientY - offset - fudgeH)
			}

			this.popupX = x
			this.popupY = y
			this.hoveredPreviewIndex = index
		},

		onPreviewLeave() {
			this.hoveredPreviewIndex = -1
		},

		/**
		 * Build a permalink to this activity in the form
		 *   /apps/activity/#/<filter>?id=<activityId>
		 * The current URL is parsed so we keep whichever filter the user
		 * is on; the activity id is appended as a query param under the
		 * hash so the SPA router doesn't need to change to support it.
		 */
		buildPermalink(): string {
			const hash = window.location.hash || '#/all'
			const [path] = hash.split('?')
			const id = String(this.activity.id)
			const url = new URL(window.location.href)
			url.hash = path + '?id=' + encodeURIComponent(id)
			// generateUrl gives us the activity-app prefix correctly
			// even when the page is on a different filter URL.
			return url.toString()
		},

		async copyPermalink() {
			this.closeContextMenu()
			const link = this.buildPermalink()
			try {
				await window.navigator.clipboard.writeText(link)
				showSuccess(t('activity', 'Link copied'))
			} catch (e) {
				logger.debug(e as Error)
				// Non-secure context: surface a copy-prompt fallback
				window.prompt(t('activity', 'Copy this link:'), link)
			}
		},

		onContextMenu(event: MouseEvent) {
			this.contextMenuX = event.clientX
			this.contextMenuY = event.clientY
			this.contextMenuOpen = true
		},

		onActionsClick(event: MouseEvent) {
			const rect = (event.currentTarget as HTMLElement).getBoundingClientRect()
			this.contextMenuX = rect.left
			this.contextMenuY = rect.bottom + 4
			this.contextMenuOpen = !this.contextMenuOpen
		},

		closeContextMenu() {
			this.contextMenuOpen = false
		},

		closeContextMenuOnDocClick(event: MouseEvent) {
			if (!this.contextMenuOpen) return
			const menu = this.$refs.contextMenuEl as HTMLElement | undefined
			if (menu && !menu.contains(event.target as Node)) {
				this.contextMenuOpen = false
			}
		},

		closeContextMenuOnEsc(event: KeyboardEvent) {
			if (event.key === 'Escape') this.contextMenuOpen = false
		},

		contextOpenInFiles() {
			this.closeContextMenu()
			if (this.activity.link) {
				window.open(this.activity.link, '_blank', 'noopener')
				return
			}
			const id = Number(this.activity.objectId)
			if (id > 0) {
				window.open(generateUrl('/f/{id}', { id }), '_blank', 'noopener')
			}
		},

		contextMuteType() {
			this.closeContextMenu()
			addMutedType(this.activity.type)
			showSuccess(t('activity', 'Muted activity type "{type}"', { type: this.activity.type }))
			// Notify the feed so it can hide already-loaded rows of this type
			window.dispatchEvent(new CustomEvent('activity:muted-types-changed'))
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
	// Browser-level virtualization: skip layout / paint for rows
	// outside the viewport.  contain-intrinsic-size reserves a
	// reasonable placeholder height so the scrollbar stays accurate
	// without requiring real layout for thousands of rows.
	content-visibility: auto;
	contain-intrinsic-size: 80px 100%;

	&--type-created     { border-inline-start-color: var(--color-success, #46ba61); }
	&--type-deleted     { border-inline-start-color: var(--color-error,   #e9322d); }
	&--type-changed     { border-inline-start-color: var(--color-primary, #0082c9); }
	&--type-share       { border-inline-start-color: #8e44ad; }
	&--type-comment     { border-inline-start-color: #1abc9c; }
	&--type-favorite    { border-inline-start-color: var(--color-warning, #f4a261); }
	&--type-neutral     { border-inline-start-color: var(--color-border-dark, #d0d0d0); }

	&:hover {
		background-color: var(--color-background-hover);
		// Subtle inset shadow on hover for that "lift" feel
		box-shadow: inset 0 0 0 1px var(--color-border);
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

	// Hover-revealed row actions (copy link + more menu).  Hidden by
	// default so they don't crowd the row; appear on hover or when the
	// menu is open.
	&__row-actions {
		display: flex;
		gap: 2px;
		margin-inline-start: 4px;
		opacity: 0;
		transition: opacity 120ms ease;
	}

	&:hover &__row-actions,
	&:focus-within &__row-actions {
		opacity: 1;
	}

	&__row-action {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 24px;
		height: 24px;
		padding: 0;
		border: none;
		border-radius: 50%;
		background: transparent;
		color: var(--color-text-maxcontrast);
		cursor: pointer;
		transition: background-color 120ms ease, color 120ms ease;

		&:hover, &:focus-visible {
			background: var(--color-background-hover);
			color: var(--color-main-text);
		}
	}

	&__context-menu {
		position: fixed;
		min-width: 180px;
		padding: 4px;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-large);
		background: var(--color-main-background);
		box-shadow: 0 8px 24px var(--color-box-shadow);
		z-index: 1000;

		button {
			display: flex;
			align-items: center;
			gap: 8px;
			width: 100%;
			padding: 6px 10px;
			border: none;
			border-radius: var(--border-radius);
			background: transparent;
			color: var(--color-main-text);
			font: inherit;
			text-align: start;
			cursor: pointer;

			&:hover, &:focus-visible {
				background: var(--color-background-hover);
			}
		}
	}

	// Soft yellow flash + persistent left-rail when a row was deep-linked.
	&--highlighted {
		animation: activity-permalink-flash 1.6s ease-out;
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

@keyframes activity-permalink-flash {
	0%   { background-color: var(--color-warning, #f4a261); }
	100% { background-color: transparent; }
}
</style>
