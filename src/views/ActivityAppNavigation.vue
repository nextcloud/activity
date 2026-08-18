<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcAppNavigation :aria-label="t('activity', 'Activity')">
		<template #list>
			<NcAppNavigationItem
				v-for="navigationItem in navigationList"
				:key="navigationItem.id"
				:data-navigation="navigationItem.id"
				:to="navigationItem.id"
				:name="navigationItem.name">
				<template #icon="{ active }">
					<NcIconSvgWrapper
						v-if="FILTER_ICONS[navigationItem.id]"
						:svg="iconFor(navigationItem.id, active)" />
					<img
						v-else-if="navigationItem.icon"
						alt=""
						:src="navigationItem.icon"
						class="navigation-icon"
						role="presentation">
				</template>
			</NcAppNavigationItem>
			<!--
				Last, and after the stream filters: this is an overview of the
				activity rather than another way of filtering it
			-->
			<NcAppNavigationItem
				data-navigation="heatmap"
				:to="{ name: 'heatmap' }"
				:name="t('activity', 'Heatmap')">
				<template #icon>
					<IconChartBoxOutline :size="20" />
				</template>
			</NcAppNavigationItem>
		</template>
		<template #footer>
			<NcAppNavigationSettings :name="t('activity', 'Activity settings')">
				<NcCheckboxRadioSwitch v-model="hasRSSLink" type="switch" @update:modelValue="toggleRSSLink">
					{{ t('activity', 'Enable RSS feed') }}
				</NcCheckboxRadioSwitch>
				<NcInputField
					v-if="hasRSSLink"
					v-model="rssLink"
					:label="t('activity', 'RSS feed')"
					:showTrailingButton="true"
					:trailingButtonLabel="t('activity', 'Copy RSS feed link')"
					readonly="readonly"
					@trailingButtonClick="copyRSSLink">
					<template #trailing-button-icon>
						<IconContentCopy :size="20" />
					</template>
				</NcInputField>
				<NcButton
					class="settings-link"
					:href="personalSettingsLink"
					:title="t('activity', 'Personal notification settings')"
					variant="tertiary">
					{{ t('activity', 'Personal notification settings') }}
				</NcButton>
			</NcAppNavigationSettings>
		</template>
	</NcAppNavigation>
</template>

<script setup lang="ts">
import accountGroupOutlineSvg from '@mdi/svg/svg/account-group-outline.svg?raw'
import accountGroupSvg from '@mdi/svg/svg/account-group.svg?raw'
import accountMultipleOutlineSvg from '@mdi/svg/svg/account-multiple-outline.svg?raw'
import accountMultipleSvg from '@mdi/svg/svg/account-multiple.svg?raw'
import accountOutlineSvg from '@mdi/svg/svg/account-outline.svg?raw'
import accountPlusOutlineSvg from '@mdi/svg/svg/account-plus-outline.svg?raw'
import accountPlusSvg from '@mdi/svg/svg/account-plus.svg?raw'
import accountSvg from '@mdi/svg/svg/account.svg?raw'
import calendarOutlineSvg from '@mdi/svg/svg/calendar-outline.svg?raw'
import calendarSvg from '@mdi/svg/svg/calendar.svg?raw'
import cardAccountDetailsOutlineSvg from '@mdi/svg/svg/card-account-details-outline.svg?raw'
import cardAccountDetailsSvg from '@mdi/svg/svg/card-account-details.svg?raw'
import folderOutlineSvg from '@mdi/svg/svg/folder-outline.svg?raw'
import folderSvg from '@mdi/svg/svg/folder.svg?raw'
import lightningBoltOutlineSvg from '@mdi/svg/svg/lightning-bolt-outline.svg?raw'
import lightningBoltSvg from '@mdi/svg/svg/lightning-bolt.svg?raw'
import lockOutlineSvg from '@mdi/svg/svg/lock-outline.svg?raw'
import lockSvg from '@mdi/svg/svg/lock.svg?raw'
import messageOutlineSvg from '@mdi/svg/svg/message-outline.svg?raw'
import messageSvg from '@mdi/svg/svg/message.svg?raw'
import starOutlineSvg from '@mdi/svg/svg/star-outline.svg?raw'
import starSvg from '@mdi/svg/svg/star.svg?raw'
import axios from '@nextcloud/axios'
import { showError, showSuccess } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'
import { computed, ref } from 'vue'
import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import NcAppNavigationItem from '@nextcloud/vue/components/NcAppNavigationItem'
import NcAppNavigationSettings from '@nextcloud/vue/components/NcAppNavigationSettings'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcCheckboxRadioSwitch from '@nextcloud/vue/components/NcCheckboxRadioSwitch'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import NcInputField from '@nextcloud/vue/components/NcInputField'
import IconChartBoxOutline from 'vue-material-design-icons/ChartBoxOutline.vue'
import IconContentCopy from 'vue-material-design-icons/ContentCopy.vue'
import logger from '../utils/logger.ts'

// Types
interface INavigationEntry {
	id: string
	icon?: string
	name: string
	url: string
}

interface IActivitySettings {
	enableAvatars: boolean
	rssLink: string
	personalSettingsLink: string
}

interface IIconPair {
	outline: string
	filled: string
}

const FILTER_ICONS: Record<string, IIconPair> = {
	all: { outline: lightningBoltOutlineSvg, filled: lightningBoltSvg },
	by: { outline: accountMultipleOutlineSvg, filled: accountMultipleSvg },
	calendar: { outline: calendarOutlineSvg, filled: calendarSvg },
	circles: { outline: accountGroupOutlineSvg, filled: accountGroupSvg },
	comments: { outline: messageOutlineSvg, filled: messageSvg },
	contacts: { outline: cardAccountDetailsOutlineSvg, filled: cardAccountDetailsSvg },
	files: { outline: folderOutlineSvg, filled: folderSvg },
	files_favorites: { outline: starOutlineSvg, filled: starSvg },
	files_sharing: { outline: accountPlusOutlineSvg, filled: accountPlusSvg },
	security: { outline: lockOutlineSvg, filled: lockSvg },
	self: { outline: accountOutlineSvg, filled: accountSvg },
}

/**
 * Filled glyph when the entry is the selected one, outlined otherwise.
 *
 * @param id filter identifier
 * @param active whether this entry is selected
 */
function iconFor(id: string, active: boolean): string | undefined {
	const pair = FILTER_ICONS[id]
	return active ? pair?.filled : pair?.outline
}

// Variables and methods
const {
	rssLink: initialRSSLink,
	personalSettingsLink,
} = loadState<IActivitySettings>(appName, 'settings')

const navigationList = loadState<INavigationEntry[]>(appName, 'navigationList')

/**
 * The current rss link, either a valid URL or an empty string
 */
const rssLink = ref(initialRSSLink)

/**
 * True if a RSS link is configures / enabled. False otherwise.
 */
const hasRSSLink = computed(() => !!rssLink.value)

/**
 * Toggle the enabled state of the RSS link
 */
async function toggleRSSLink() {
	try {
		const { data } = await axios.post<{ data: { rsslink: string } }>(generateUrl('/apps/activity/settings/feed'), {
			enable: !hasRSSLink.value,
		})
		rssLink.value = data.data.rsslink
	} catch (e) {
		showError(t('activity', 'Could not enable RSS link'))
		logger.error(e as Error)
	}
}

/**
 * Copy the RSS link to the clipboard
 */
async function copyRSSLink() {
	try {
		window.navigator.clipboard.writeText(rssLink.value)
		showSuccess(t('activity', 'RSS link copied to clipboard'))
	} catch (e) {
		logger.debug(e as Error)
		// If the user is running Nextcloud in a non secure context (secure = HTTPS or HTTP on localhost) then Clipboard API fail and the user must copy manually
		window.prompt(t('activity', 'Could not copy the RSS link, please copy manually:'), rssLink.value)
	}
}
</script>

<style lang="scss">
.app-activity {
	.settings-link {
		max-width: 100%;
	}

	.app-navigation-entry {

		// NC34+ active design uses --color-primary-element-light background
		// with --color-main-text, so icons follow the same invert logic as non-active items.
		// Legacy NC<34 used the full primary color background, requiring --primary-invert-if-dark.
		&.active.app-navigation-entry--legacy .navigation-icon {
			filter: var(--primary-invert-if-dark);
		}

		&.active:not(.app-navigation-entry--legacy) .navigation-icon,
		&:not(.active) .navigation-icon {
			filter: var(--background-invert-if-dark);
		}
	}

	.navigation-icon {
		height: 20px;
		width: 20px;
	}
}
</style>
