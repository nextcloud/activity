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
	<div v-if="emailEnabled">
		<p v-if="!isEmailSet">
			<strong>{{ t('activity', 'You need to set up your email address before you can receive notification emails.') }}</strong>
		</p>
		<p>
			<label for="activity_setting_batchtime" class="activity-frequency__label">
				{{ t('activity', 'Send activity emails') }}
			</label>
			<select id="activity_setting_batchtime"
				class="notification-frequency__select"
				name="activity_setting_batchtime"
				@change="setSettingBatchtime({settingBatchtime: $event.target.value})">
				<option :value="EmailFrequency.EMAIL_SEND_ASAP"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_ASAP">
					{{ t('activity', 'As soon as possible') }}
				</option>
				<option :value="EmailFrequency.EMAIL_SEND_HOURLY"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_HOURLY">
					{{ t('activity', 'Hourly') }}
				</option>
				<option :value="EmailFrequency.EMAIL_SEND_DAILY"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_DAILY">
					{{ t('activity', 'Daily') }}
				</option>
				<option :value="EmailFrequency.EMAIL_SEND_WEEKLY"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_WEEKLY">
					{{ t('activity', 'Weekly') }}
				</option>
			</select>
		</p>
	</div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import EmailFrequency from '../models/EmailFrequency.js'

export default {
	name: 'EmailSettings',

	data() {
		return {
			EmailFrequency: EmailFrequency.EmailFrequency,
		}
	},
	computed: {
		...mapState([
			'emailEnabled',
			'isEmailSet',
			'settingBatchtime',
		]),
	},
	methods: {
		...mapActions([
			'setSettingBatchtime',
		]),
	},
}

</script>

<style lang="scss" scoped>
.activity-frequency__label {
	margin-top: 24px;
	display: inline-block;
}
</style>
