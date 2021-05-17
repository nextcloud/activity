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
	<div>
		<SettingsSection
			:title="t('activity', 'Notifications')">
			<form>
				<CheckboxRadioSwitch
					type="checkbox"
					:checked="emailEnabled"
					@update:checked="toggleEmailEnabled({emailEnabled: $event})">
					{{ t('activity', 'Enable notification emails') }}
				</CheckboxRadioSwitch>
			</form>
		</SettingsSection>

		<SettingsSection
			ref="activity-setting-form"
			:title="t('activity', 'Default settings')"
			:description="t('activity', 'Configure the default notification settings for new users.')">
			<form>
				<ActivitySettingsForm />
			</form>
		</SettingsSection>
	</div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

import SettingsSection from '@nextcloud/vue/dist/Components/SettingsSection'
import CheckboxRadioSwitch from '@nextcloud/vue/dist/Components/CheckboxRadioSwitch'

import ActivitySettingsForm from '../components/ActivitySettingsForm'

export default {
	name: 'AdminSettings',
	components: {
		ActivitySettingsForm,
		SettingsSection,
		CheckboxRadioSwitch,
	},

	computed: {
		...mapState({
			emailEnabled: 'emailEnabled',
		}),
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
