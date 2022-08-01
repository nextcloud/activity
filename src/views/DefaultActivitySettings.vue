<!--
  - @copyright Copyright (c) 2022 Carl Schwan <carl@carlschwan.eu>
  -
  - @license AGPL-3.0-or-later
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
	<SettingsSection :title="t('activity', 'Default settings')"
		:description="t('activity', 'Configure the default notification settings for new users.')">
		<ActivityGrid />
	</SettingsSection>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import SettingsSection from '@nextcloud/vue/dist/Components/SettingsSection'
import ActivityGrid from '../components/ActivityGrid'

export default {
	name: 'DefaultActivitySettings',
	components: {
		ActivityGrid,
		SettingsSection,
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

	computed: {
		...mapState({
			emailEnabled: 'emailEnabled',
		}),
	},
}

</script>
