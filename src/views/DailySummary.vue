<template>
	<SettingsSection :title="t('activity', 'Daily activtiy summary')">
		<CheckboxRadioSwitch :checked="activityDigestEnabled" @update:checked="toggleActivityDigestEnabled({activityDigestEnabled: $event})">
			{{ t('activity', 'Send daily activity summary in the morning') }}
		</CheckboxRadioSwitch>
	</SettingsSection>
</template>

<script>
import CheckboxRadioSwitch from '@nextcloud/vue/dist/Components/CheckboxRadioSwitch'
import SettingsSection from '@nextcloud/vue/dist/Components/SettingsSection'
import { mapActions, mapState } from 'vuex'

export default {
	name: 'DailySummary',
	components: {
		CheckboxRadioSwitch,
		SettingsSection,
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
