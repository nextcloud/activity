<!--
  - @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<form ref="activity-setting-form">
		<SettingsSection
			:title="t('activity', 'Notifications')"
			:description="description">
			<ActivitySettingsForm />
		</SettingsSection>

		<SettingsSection
			:title="t('activity', 'Activity')"
			class="summary-form">
			<CheckboxRadioSwitch
				type="checkbox"
				:checked="activityDigestEnabled"
				@update:checked="toggleActivityDigestEnabled({activityDigestEnabled: $event})">
				{{ t('activity', 'Send daily activity summary in the morning') }}
			</CheckboxRadioSwitch>
		</SettingsSection>
	</form>
</template>

<script>
import { mapActions, mapState } from 'vuex'

import SettingsSection from '@nextcloud/vue/dist/Components/SettingsSection'
import CheckboxRadioSwitch from '@nextcloud/vue/dist/Components/CheckboxRadioSwitch'

import ActivitySettingsForm from '../components/ActivitySettingsForm'

export default {
	name: 'UserSettings',
	components: {
		ActivitySettingsForm,
		SettingsSection,
		CheckboxRadioSwitch,
	},

	computed: {
		...mapState([
			'emailEnabled',
			'activityDigestEnabled',
		]),
		description() {
			if (this.emailEnabled) {
				return this.t('activity', 'Choose for which activities you want to get an email or push notification.')
			} else {
				return this.t('activity', 'Choose for which activities you want to get a push notification.')
			}
		},
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
