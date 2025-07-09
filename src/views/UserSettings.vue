<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcSettingsSection
		:name="t('activity', 'Activity')"
		:description="settingDescription">
		<ActivityGrid />
		<EmailSettings />
	</NcSettingsSection>
</template>

<script>
import { t } from '@nextcloud/l10n'
import { mapActions, mapState } from 'vuex'
import NcSettingsSection from '@nextcloud/vue/components/NcSettingsSection'
import ActivityGrid from '../components/ActivityGrid.vue'
import EmailSettings from '../components/EmailSettings.vue'

export default {
	name: 'UserSettings',
	components: {
		NcSettingsSection,
		EmailSettings,
		ActivityGrid,
	},

	computed: {
		...mapState({
			emailEnabled: 'emailEnabled',
		}),

		settingDescription() {
			if (this.emailEnabled) {
				return t('activity', 'Choose for which activities you want to get an email or push notification.')
			} else {
				return t('activity', 'Choose for which activities you want to get a push notification.')
			}
		},
	},

	mounted() {
		this.setEndpoint({ endpoint: '/apps/activity/settings' })
	},

	methods: {
		...mapActions(['setEndpoint']),

		t,
	},
}

</script>
