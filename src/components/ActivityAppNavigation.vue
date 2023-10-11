<template>
	<NcAppNavigation>
		<template #list>
			<NcAppNavigationItem v-for="navigationItem in navigationList"
				:key="navigationItem.id"
				:active="navigationItem.id === currentView"
				:aria-current="navigationItem.id === currentView ? 'page' : null"
				:data-navigation="navigationItem.id"
				:to="navigationItem.id"
				:name="navigationItem.name">
				<template v-if="navigationItem.icon" #icon>
					<img alt=""
						:src="navigationItem.icon"
						class="navigation-icon"
						role="presentation">
				</template>
			</NcAppNavigationItem>
		</template>
		<template #footer>
			<NcAppNavigationSettings :name="t('activity', 'Activity settings')">
				<NcCheckboxRadioSwitch :checked="hasRSSLink">
					{{ t('activity', 'Enable RSS feed') }}
				</NcCheckboxRadioSwitch>
				<NcInputField v-if="hasRSSLink"
					:label="t('activity', 'RSS feed')"
					:show-trailing-button="true"
					:trailing-button-label="t('activity', 'Copy RSS feed link')"
					readonly="readonly">
					<template #trailing-button-icon>
						<IconClipboard :size="20" />
					</template>
					{{ rssLink }}
				</NcInputField>
				<NcButton class="settings-link"
					:href="personalSettingsLink"
					:title="t('activity', 'Personal notification settings')"
					type="tertiary">
					{{ t('activity', 'Personal notification settings') }}
				</NcButton>
			</NcAppNavigationSettings>
		</template>
	</NcAppNavigation>
</template>

<script setup lang="ts">
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import { computed } from 'vue'

import {
	NcAppNavigation,
	NcAppNavigationItem,
	NcAppNavigationSettings,
	NcButton,
	NcCheckboxRadioSwitch,
} from '@nextcloud/vue'

import NcInputField from '@nextcloud/vue/dist/Components/NcInputField.js'
import IconClipboard from 'vue-material-design-icons/Clipboard.vue'

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
	rssLink,
	personalSettingsLink,
} = loadState<IActivitySettings>(appName, 'settings')

const navigationList = loadState<INavigationEntry[]>(appName, 'navigationList')

const hasRSSLink = computed(() => !!rssLink)
const currentView = computed(() => 'all')
</script>

<style lang="scss">
.app-activity {
	.settings-link {
		max-width: 100%;
	}

	.navigation-icon {
		filter: var(--background-invert-if-dark);
		height: 16px;
		width: 16px;
	}
}
</style>
