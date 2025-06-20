<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
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
				<th
					v-for="(methodName, methodKey) in methods"
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
					<CheckboxInput
						:id="`${activityKey}_${methodKey}`"
						:disabled="!isActivityEnabled(activity, methodKey)"
						:checked="checkedActivities"
						:value="`${activityKey}_${methodKey}`"
						@update:checked="toggleMethodForMethodAndActivity({ groupKey, activityKey, methodKey })">
						{{ actionName(methodKey) }}
					</CheckboxInput>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
import { t } from '@nextcloud/l10n'
import { mapActions, mapGetters, mapState } from 'vuex'
import CheckboxInput from './CheckboxInput.vue'
import { isActivityEnabled } from '../utils/settings.ts'

export default {
	name: 'ActivityGrid',
	components: {
		CheckboxInput,
	},

	computed: {
		...mapGetters(['checkedActivities']),

		...mapState([
			'methods',
			'activityGroups',
		]),
	},

	methods: {
		isActivityEnabled,
		...mapActions(['toggleMethodForMethodAndActivity']),

		actionName(method) {
			if (method === 'email') {
				return t('activity', 'Send email')
			} else {
				return t('activity', 'Send push notification')
			}
		},

		t,
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
