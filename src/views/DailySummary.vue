<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcSettingsSection :name="t('activity', 'Daily activity summary')">
		<NcCheckboxRadioSwitch
			data-cy-checkbox
			v-model="activityDigestEnabled"
			@update:model-value="toggleActivityDigestEnabled({ activityDigestEnabled: $event })">
			{{ t('activity', 'Send daily activity summary in the morning') }}
		</NcCheckboxRadioSwitch>
	</NcSettingsSection>
</template>

<script>
import { t } from '@nextcloud/l10n'
import { mapActions, mapState } from 'vuex'
import NcCheckboxRadioSwitch from '@nextcloud/vue/components/NcCheckboxRadioSwitch'
import NcSettingsSection from '@nextcloud/vue/components/NcSettingsSection'

export default {
	name: 'DailySummary',
	components: {
		NcCheckboxRadioSwitch,
		NcSettingsSection,
	},

	computed: {
		...mapState(['activityDigestEnabled']),
	},

	mounted() {
		this.setEndpoint({ endpoint: '/apps/activity/settings' })
	},

	methods: {
		...mapActions([
			'setEndpoint',
			'toggleActivityDigestEnabled',
		]),

		t,
	},
}
</script>
