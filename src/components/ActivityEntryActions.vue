<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<NcActions
		v-if="hasActions"
		class="activity-entry-actions"
		size="small"
		:ariaLabel="menuLabel">
		<NcActionButton
			v-if="canOpenInViewer"
			closeAfterClick
			@click="openInViewer">
			<template #icon>
				<Eye :size="20" />
			</template>
			{{ t('activity', 'View') }}
		</NcActionButton>
		<NcActionLink
			v-if="filesLink !== ''"
			:href="filesLink"
			closeAfterClick>
			<template #icon>
				<FolderOutline :size="20" />
			</template>
			{{ t('activity', 'Show in Files') }}
		</NcActionLink>
		<NcActionButton
			v-if="shareableLink !== ''"
			closeAfterClick
			@click="copyLink">
			<template #icon>
				<ContentCopy :size="20" />
			</template>
			{{ t('activity', 'Copy link') }}
		</NcActionButton>
	</NcActions>
</template>

<script setup lang="ts">
import type { IPreview } from '../models/types.ts'

import { showError, showSuccess } from '@nextcloud/dialogs'
import { translate as t } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'
import { computed } from 'vue'
import NcActionButton from '@nextcloud/vue/components/NcActionButton'
import NcActionLink from '@nextcloud/vue/components/NcActionLink'
import NcActions from '@nextcloud/vue/components/NcActions'
import ContentCopy from 'vue-material-design-icons/ContentCopy.vue'
import Eye from 'vue-material-design-icons/Eye.vue'
import FolderOutline from 'vue-material-design-icons/FolderOutline.vue'
import logger from '../utils/logger.ts'

const props = defineProps<{
	/**
	 * The previewed file the actions apply to.
	 *
	 * Scoped to one preview rather than to the activity, because an activity can
	 * carry several files — a bulk upload arrives as one grouped entry with a
	 * thumbnail each — and a single menu per row could only ever act on one of
	 * them, silently picking the first.
	 */
	preview: IPreview
}>()

/**
 * The file id to resolve, or null when the preview carries none.
 */
const fileId = computed(() => props.preview.fileId > 0 ? props.preview.fileId : null)

const menuLabel = computed(() => props.preview.filename === ''
	? t('activity', 'Actions for this file')
	: t('activity', 'Actions for {filename}', { filename: props.preview.filename }))

/**
 * Re-anchor a URL onto the host this session is actually using.
 *
 * Providers hand out absolute URLs, and the host baked into them comes from the
 * server's own configuration rather than from the current request. An activity
 * recorded by a background job carries `overwrite.cli.url`, which is routinely
 * something no browser can reach — `http://localhost:8080` on a typical
 * install. Only the path is trustworthy, and every activity link targets this
 * instance, so the path is all we keep.
 *
 * @param url - The URL to normalise, absolute or relative
 */
function toCurrentOrigin(url: string): string {
	if (url === '') {
		return ''
	}
	try {
		const parsed = new URL(url, window.location.origin)
		return `${window.location.origin}${parsed.pathname}${parsed.search}${parsed.hash}`
	} catch (error) {
		logger.debug('Could not normalise an activity link', { error })
		return ''
	}
}

/**
 * Canonical link to the activity's file, carrying no view preference.
 *
 * Resolving by file id is preferred over the provider's own link: it is built
 * for this instance rather than carrying a host from the server's config, it
 * addresses the file itself where a provider link often only points at the
 * containing directory, and the server redirects it to whichever view actually
 * holds the file. The provider link is the fallback for activities that have no
 * file id to resolve.
 */
const fileRoute = computed(() => {
	if (fileId.value !== null) {
		return generateUrl('/f/{fileId}', { fileId: fileId.value })
	}
	return toCurrentOrigin(props.preview.link ?? '')
})

/**
 * Where "Show in Files" points: the file revealed inside its folder.
 *
 * `/f/<fileid>` opens the file in the Viewer on arrival — the server sets
 * `openfile=true` for anything that is not a folder — so revealing it without
 * opening it has to say so explicitly. Opening is what the View action is for,
 * and doing both from one menu entry is just surprising.
 */
const filesLink = computed(() => {
	if (fileId.value === null) {
		return fileRoute.value
	}
	return `${fileRoute.value}?openfile=false`
})

/**
 * Absolute link for the clipboard.
 *
 * Left at the server's default behaviour: whoever the link is shared with wants
 * the file, not a particular way of looking at the folder it sits in.
 */
const shareableLink = computed(() => toCurrentOrigin(fileRoute.value))

/**
 * Whether the Viewer is loaded and able to display this file.
 */
const canOpenInViewer = computed(() => {
	if (props.preview.filePath === undefined || window?.OCA?.Viewer?.open === undefined) {
		return false
	}
	return window.OCA.Viewer.mimetypes.includes(props.preview.mimeType)
})

const hasActions = computed(() => canOpenInViewer.value || filesLink.value !== '' || shareableLink.value !== '')

/**
 * Open the file in the Viewer overlay rather than navigating away
 */
function openInViewer(): void {
	const filePath = props.preview.filePath
	if (filePath === undefined) {
		return
	}
	try {
		// The stored path is absolute within the instance; the Viewer wants it
		// relative to the user's files root
		window.OCA!.Viewer!.open({ path: filePath.replace(/^\/[^/]+\/files/, '') })
	} catch (error) {
		logger.debug('Could not open the activity target in the viewer', { error })
	}
}

/**
 * Copy a link to this file onto the clipboard
 */
async function copyLink(): Promise<void> {
	// Absent on insecure origins, where copying is not permitted at all
	if (navigator.clipboard === undefined) {
		showError(t('activity', 'Copying the link is not available'))
		return
	}
	try {
		await navigator.clipboard.writeText(shareableLink.value)
		showSuccess(t('activity', 'Link copied to clipboard'))
	} catch (error) {
		logger.error('Could not copy the activity link', { error })
		showError(t('activity', 'Could not copy the link'))
	}
}
</script>

<style scoped lang="scss">
.activity-entry-actions {
	// Sits over the thumbnail's top corner. Absolute rather than laid out, so
	// adding the menu does not change the size or spacing of the grid of
	// thumbnails it sits on.
	position: absolute;
	inset-block-start: 2px;
	inset-inline-end: 2px;
	z-index: 1;

	// A thumbnail can be any colour, so the toggle carries its own surface
	// instead of relying on the image behind it for contrast
	:deep(.button-vue) {
		background-color: var(--color-main-background);
		box-shadow: 0 0 4px var(--color-box-shadow);

		&:hover {
			background-color: var(--color-background-hover);
		}
	}

	// Keep the grid of thumbnails uncluttered: the menu is faded out until its
	// thumbnail is hovered or something inside it takes focus. Faded rather than
	// removed so it stays keyboard reachable, and only on devices with a real
	// pointer so a touch user is never asked to hover to discover it.
	// Revealing on hover belongs to the thumbnail, so the entry component does
	// that part for its own layout.
	@media (hover: hover) {
		opacity: 0;
		transition: opacity 0.1s ease-in-out;

		&:focus-within {
			opacity: 1;
		}
	}
}
</style>
