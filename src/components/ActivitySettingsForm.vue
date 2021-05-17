<!--
  - @copyright 2021 Louis Chemineau <louis@chmn.me>
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
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -->

<template>
	<div>
		<!-- TODO: should I use the global grid class ? -->
		<table v-for="(group, groupKey) in activityGroups" :key="groupKey" class="grid groups">
			<tbody>
				<tr>
					<th colspan="3" class="groups__name">
						{{ group.name }}
					</th>
				</tr>

				<tr>
					<th v-for="(methodName, methodKey) in methods"
						:key="methodKey"
						class="groups__method"
						@click="toggleMethodForGroup({groupKey, methodKey})">
						{{ methodName }}
					</th>
					<th />
				</tr>

				<tr v-for="(activity, activityKey) in group.activities" :key="activityKey" class="groups__activity">
					<td v-for="(methodName, methodKey) in methods" :key="methodKey">
						<div class="groups__activity__checkbox">
							<CheckboxRadioSwitch
								:id="`${activityKey}_${methodKey}`"
								:disabled="!isActivityEnabled(activity, methodKey)"
								:checked="checkedActivities"
								name="selected_activities"
								:value="`${activityKey}_${methodKey}`"
								type="checkbox"
								@update:checked="toggleMethodForMethodAndActivity({groupKey, activityKey, methodKey})" />
						</div>
					</td>
					<!-- eslint-disable vue/no-v-html -->
					<td class="groups__activity__label"
						@click="toggleMethodsForActivity({groupKey, activityKey})"
						v-html="activity.desc" />
					<!-- eslint-enable vue/no-v-html -->
				</tr>
			</tbody>
		</table>

		<div v-if="emailEnabled" class="notification-frequency">
			<div class="notification-frequency__warning">
				<strong v-if="!isEmailSet">{{ t('activity', 'You need to set up your email address before you can receive notification emails.') }}</strong>
			</div>

			<div>
				<label for="notify_setting_batchtime" class="notification-frequency__label">{{ t('activity', 'Send notification emails:') }}</label>
				<select
					class="notification-frequency__select"
					name="notify_setting_batchtime"
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
			</div>
		</div>
	</div>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'

import CheckboxRadioSwitch from '@nextcloud/vue/dist/Components/CheckboxRadioSwitch'

import EmailFrequency from '../models/EmailFrequency'
import { isActivityEnabled } from '../helpers/settings'

export default {
	name: 'ActivitySettingsForm',
	components: {
		CheckboxRadioSwitch,
	},
	data() {
		return {
			EmailFrequency: EmailFrequency.EmailFrequency,
		}
	},
	computed: {
		...mapGetters([
			'checkedActivities',
		]),
		...mapState([
			'methods',
			'activityGroups',
			'emailEnabled',
			'isEmailSet',
			'settingBatchtime',
		]),
	},
	methods: {
		isActivityEnabled,
		...mapActions([
			'toggleMethodForMethodAndActivity',
			'toggleMethodForGroup',
			'toggleMethodsForActivity',
			'setSettingBatchtime',
		]),
	},
}
</script>
<style lang="scss" scoped>
.groups {
	max-width: 550px;
	white-space: normal;
	margin-top: 12px;

	tr {
		height: 2.5em;
	}

	&__name {
		font-size: 1.3em;
		color: var(--color-main-text);
		padding: 0.5em;
	}

	&__method {
		width: 5em;
		text-align: center;
	}

	&__activity {
		&__checkbox {
			display: flex;
			justify-content: center;
		}
	}
}

.notification-frequency {
	margin-top: 24px;

	&__warning {
		margin-bottom: 12px;
	}

	&__label {
		margin-right: 12px;
	}
}
</style>
