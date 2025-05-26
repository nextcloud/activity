<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcSettingsSection
		:name="t('activity', 'Default settings')"
		:description="t('activity', 'Configure the default notification settings for new accounts.')">
		<ActivityGrid />
	</NcSettingsSection>
</template>

<script>
import NcSettingsSection from '@nextcloud/vue/dist/Components/NcSettingsSection.js'
import { mapActions } from 'vuex'
import ActivityGrid from '../components/ActivityGrid.vue'

export default {
	name: 'DefaultActivitySettings',
	components: {
		ActivityGrid,
		NcSettingsSection,
	},

	mounted() {
		this.setEndpoint({ endpoint: '/apps/activity/settings/admin' })
	},

	methods: {
		...mapActions(['setEndpoint']),
	},
}

</script>
