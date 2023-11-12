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
	<table class="grid activitysettings">
		<caption class="sr-only">
			{{ t('activity', 'Activity notification configuration') }}
		</caption>
		<tbody v-for="(group, groupKey) in activityGroups" :key="groupKey">
			<tr class="group-header">
				<th class="group-header-section">
					{{ group.name }}
				</th>
				<th v-for="(methodName, methodKey) in methods"
					:key="methodKey"
					class="activity_select_group">
					{{ methodName }}
				</th>
			</tr>
			<tr v-for="(activity, activityKey) in group.activities" :key="activityKey">
				<th scope="row">
					<!-- eslint-disable vue/no-v-html -->
					<span v-html="activity.desc" />
					<!-- eslint-enable vue/no-v-html -->
				</th>
				<td v-for="(methodName, methodKey) in methods" :key="methodKey">
					<Checkbox :id="`${activityKey}_${methodKey}`"
						:disabled="!isActivityEnabled(activity, methodKey)"
						:checked="checkedActivities"
						:value="`${activityKey}_${methodKey}`"
						@update:checked="toggleMethodForMethodAndActivity({groupKey, activityKey, methodKey})">
						{{ actionName(methodKey) }}
					</Checkbox>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import Checkbox from './Checkbox.vue'
import { isActivityEnabled } from '../utils/settings.ts'

export default {
	name: 'ActivityGrid',
	components: {
		Checkbox,
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
		]),
		actionName(method) {
			if (method === 'email') {
				return t('activity', 'Send email')
			} else {
				return t('activity', 'Send push notification')
			}
		},
	},
}
</script>

<style lang="scss" scoped>

table.grid th {
	color: var(--color-text-light);
	height: 44px;
}

table.grid .group-header {
	th {
		padding-top: 16px;
		height: 60px;

		&.activity_select_group {
			padding-left: 20px;
		}
	}
}

table.grid th.group-header-section {
	// Copy of core/apps.scss h3 styles
	font-size: 16px;
	font-weight: bold;
}

table.grid th.activity_select_group {
	color: var(--color-main-text);
}

.sr-only {
	position:absolute;
	left:-10000px;
	top:auto;
	width:1px;
	height:1px;
	overflow:hidden;
}
</style>
