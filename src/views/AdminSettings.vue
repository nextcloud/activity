<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcSettingsSection :name="t('activity', 'Notification')">
		<NcCheckboxRadioSwitch
			type="checkbox"
			:checked="emailEnabled"
			@update:checked="toggleEmailEnabled({ emailEnabled: $event })">
			{{ t('activity', 'Enable notification emails') }}
		</NcCheckboxRadioSwitch>
	</NcSettingsSection>
</template>

<script>
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import NcSettingsSection from '@nextcloud/vue/dist/Components/NcSettingsSection.js'
import { mapActions, mapState } from 'vuex'

export default {
	name: 'AdminSettings',
	components: {
		NcCheckboxRadioSwitch,
		NcSettingsSection,
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
		this.setEndpoint({ endpoint: '/apps/activity/settings/admin' })
	},

	methods: {
		...mapActions([
			'setEndpoint',
			'toggleEmailEnabled',
		]),
	},
}

</script>
