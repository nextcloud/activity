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
				<template v-if="navigationItem.icon" #icon>
					<img
						alt=""
						:src="navigationItem.icon"
						class="navigation-icon"
						role="presentation">
				</template>
			</NcAppNavigationItem>
		</template>
		<template #footer>
			<NcAppNavigationSettings :name="t('activity', 'Activity settings')">
				<NcCheckboxRadioSwitch type="switch" v-model="hasRSSLink" @update:model-value="toggleRSSLink">
					{{ t('activity', 'Enable RSS feed') }}
				</NcCheckboxRadioSwitch>
				<NcInputField
					v-if="hasRSSLink"
					v-model="rssLink"
					:label="t('activity', 'RSS feed')"
					:show-trailing-button="true"
					:trailing-button-label="t('activity', 'Copy RSS feed link')"
					readonly="readonly"
					@trailing-button-click="copyRSSLink">
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
import NcInputField from '@nextcloud/vue/components/NcInputField'
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
		&.active .navigation-icon {
			filter: var(--primary-invert-if-dark);
		}

		&:not(.active) .navigation-icon {
			filter: var(--background-invert-if-dark);
		}
	}

	.navigation-icon {
		height: 16px;
		width: 16px;
	}
}
</style>
