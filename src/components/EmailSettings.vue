<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
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
			<select
				id="activity_setting_batchtime"
				class="notification-frequency__select"
				name="activity_setting_batchtime"
				@change="setSettingBatchtime({ settingBatchtime: $event.target.value })">
				<option
					:value="EmailFrequency.EMAIL_SEND_ASAP"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_ASAP">
					{{ t('activity', 'As soon as possible') }}
				</option>
				<option
					:value="EmailFrequency.EMAIL_SEND_HOURLY"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_HOURLY">
					{{ t('activity', 'Hourly') }}
				</option>
				<option
					:value="EmailFrequency.EMAIL_SEND_DAILY"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_DAILY">
					{{ t('activity', 'Daily') }}
				</option>
				<option
					:value="EmailFrequency.EMAIL_SEND_WEEKLY"
					:selected="settingBatchtime === EmailFrequency.EMAIL_SEND_WEEKLY">
					{{ t('activity', 'Weekly') }}
				</option>
			</select>
		</p>
	</div>
</template>

<script>
import { t } from '@nextcloud/l10n'
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
		...mapActions(['setSettingBatchtime']),

		t,
	},
}

</script>

<style lang="scss" scoped>
.activity-frequency__label {
	margin-top: 24px;
	display: inline-block;
}

.notification-frequency__select {
	margin-left: calc(var(--default-grid-baseline) * 2);
}

</style>
