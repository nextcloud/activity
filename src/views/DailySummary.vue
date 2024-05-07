<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcSettingsSection :name="t('activity', 'Daily activity summary')">
		<NcCheckboxRadioSwitch data-cy-checkbox
			:checked="activityDigestEnabled"
			@update:checked="toggleActivityDigestEnabled({activityDigestEnabled: $event})">
			{{ t('activity', 'Send daily activity summary in the morning') }}
		</NcCheckboxRadioSwitch>
	</NcSettingsSection>
</template>

<script>
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import NcSettingsSection from '@nextcloud/vue/dist/Components/NcSettingsSection.js'
import { mapActions, mapState } from 'vuex'

export default {
	name: 'DailySummary',
	components: {
		NcCheckboxRadioSwitch,
		NcSettingsSection,
	},

	computed: {
		...mapState([
			'activityDigestEnabled',
		]),
	},

	mounted() {
		this.setEndpoint({ endpoint: '/apps/activity/settings' })
	},

	methods: {
		...mapActions([
			'setEndpoint',
			'toggleActivityDigestEnabled',
		]),
	},
}
</script>
