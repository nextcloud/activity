<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div class="activity-settings">
		<div class="activity-settings__presets">
			<span class="activity-settings__presets-label">{{ t('activity', 'Quick presets') }}</span>
			<NcButton
				type="secondary"
				:title="t('activity', 'Turn off both email and push notifications for every activity')"
				@click="applyPreset('mute')">
				{{ t('activity', 'Mute everything') }}
			</NcButton>
			<NcButton
				type="secondary"
				:title="t('activity', 'Email shares and mentions only — push everything')"
				@click="applyPreset('essentials')">
				{{ t('activity', 'Essentials only') }}
			</NcButton>
			<NcButton
				type="secondary"
				:title="t('activity', 'Receive every activity by email and push')"
				@click="applyPreset('all')">
				{{ t('activity', 'Send everything') }}
			</NcButton>
		</div>

		<section
			v-for="(group, groupKey) in activityGroups"
			:key="groupKey"
			class="activity-card">
			<header class="activity-card__header">
				<h3 class="activity-card__title">{{ group.name }}</h3>
				<div class="activity-card__group-toggles">
					<NcCheckboxRadioSwitch
						v-for="methodKey in methods"
						:key="`group-${groupKey}-${methodKey}`"
						type="switch"
						:checked="isWholeGroupEnabled(group, methodKey)"
						:indeterminate="isGroupPartiallyEnabled(group, methodKey)"
						:title="masterToggleTitle(methodKey)"
						@update:checked="toggleMethodForGroup({ groupKey, methodKey })">
						{{ actionName(methodKey) }}
					</NcCheckboxRadioSwitch>
				</div>
			</header>

			<ul class="activity-card__rows">
				<li
					v-for="(activity, activityKey) in group.activities"
					:key="activityKey"
					class="activity-card__row">
					<!-- eslint-disable-next-line vue/no-v-html -->
					<span class="activity-card__row-label" v-html="activity.desc" />
					<div class="activity-card__row-toggles">
						<NcCheckboxRadioSwitch
							v-for="methodKey in methods"
							:key="`${activityKey}-${methodKey}`"
							type="switch"
							:checked="isActivityMethodChecked(activityKey, methodKey)"
							:disabled="!isActivityEnabled(activity, methodKey)"
							:title="actionName(methodKey)"
							@update:checked="toggleMethodForMethodAndActivity({ groupKey, activityKey, methodKey })">
							<span class="hidden-visually">{{ actionName(methodKey) }}</span>
						</NcCheckboxRadioSwitch>
					</div>
				</li>
			</ul>
		</section>
	</div>
</template>

<script>
import { translate as t } from '@nextcloud/l10n'
import { mapActions, mapGetters, mapState } from 'vuex'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcCheckboxRadioSwitch from '@nextcloud/vue/components/NcCheckboxRadioSwitch'
import { isActivityEnabled } from '../utils/settings.ts'

export default {
	name: 'ActivityGrid',
	components: {
		NcButton,
		NcCheckboxRadioSwitch,
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
		...mapActions(['toggleMethodForMethodAndActivity', 'toggleMethodForGroup']),

		actionName(method) {
			if (method === 'email') {
				return t('activity', 'Send email')
			}
			return t('activity', 'Send push notification')
		},

		masterToggleTitle(method) {
			if (method === 'email') {
				return t('activity', 'Toggle email for every activity in this group')
			}
			return t('activity', 'Toggle push notifications for every activity in this group')
		},

		isActivityMethodChecked(activityKey, methodKey) {
			return this.checkedActivities.includes(`${activityKey}_${methodKey}`)
		},

		isWholeGroupEnabled(group, methodKey) {
			const activities = Object.values(group.activities)
			return activities.length > 0 && activities.every((a) => !!a[methodKey])
		},

		isGroupPartiallyEnabled(group, methodKey) {
			const activities = Object.values(group.activities)
			const some = activities.some((a) => !!a[methodKey])
			const all = activities.every((a) => !!a[methodKey])
			return some && !all
		},

		/**
		 * Bulk-apply a preset by toggling group-level methods.  Reuses the
		 * existing toggleMethodForGroup action for each (group, method) pair
		 * that needs to flip, so the existing save/persist flow runs once
		 * per group instead of once per activity.
		 *
		 * Presets:
		 *  - "mute":       every method off everywhere
		 *  - "essentials": email = off everywhere except sharing/mention
		 *                  groups; push = on everywhere
		 *  - "all":        every method on everywhere
		 */
		applyPreset(name) {
			for (const groupKey in this.activityGroups) {
				const group = this.activityGroups[groupKey]
				for (const methodKey of this.methods) {
					const target = this.presetTarget(name, groupKey, methodKey)
					const current = this.isWholeGroupEnabled(group, methodKey)
					const partial = this.isGroupPartiallyEnabled(group, methodKey)
					// Only dispatch if the group is not already fully aligned with the target
					if (target !== current || partial) {
						this.toggleMethodForGroup({ groupKey, methodKey })
					}
				}
			}
		},

		/**
		 * Whether the given (group, method) should end up on or off after the
		 * preset is applied.  "Sharing"/"mention" group-key heuristics are
		 * intentionally lenient — group keys are translatable display names
		 * in some installs, so we match by case-insensitive substring.
		 */
		presetTarget(name, groupKey, methodKey) {
			if (name === 'all') return true
			if (name === 'mute') return false
			// "essentials": push everywhere; email only on share/mention groups
			if (methodKey !== 'email') return true
			const k = String(groupKey).toLowerCase()
			return k.includes('share') || k.includes('mention') || k.includes('comment')
		},

		t,
	},
}
</script>

<style lang="scss" scoped>
.activity-settings {
	display: flex;
	flex-direction: column;
	gap: 16px;
	max-width: 920px;

	&__presets {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 8px;
		padding: 12px 16px;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-large);
		background: var(--color-background-hover);
	}

	&__presets-label {
		margin-inline-end: 4px;
		font-weight: bold;
		color: var(--color-text-light);
	}
}

.activity-card {
	border: 1px solid var(--color-border);
	border-radius: var(--border-radius-large);
	background: var(--color-main-background);
	overflow: hidden;

	&__header {
		display: flex;
		align-items: center;
		gap: 16px;
		padding: 12px 16px;
		background: var(--color-background-hover);
		border-bottom: 1px solid var(--color-border);
	}

	&__title {
		flex-grow: 1;
		margin: 0;
		font-size: 16px;
		font-weight: bold;
	}

	&__group-toggles {
		display: flex;
		gap: 16px;
		flex-shrink: 0;
	}

	&__rows {
		list-style: none;
		margin: 0;
		padding: 0;
	}

	&__row {
		display: flex;
		align-items: center;
		gap: 16px;
		padding: 8px 16px;
		border-bottom: 1px solid var(--color-border);

		&:last-child {
			border-bottom: none;
		}
	}

	&__row-label {
		flex-grow: 1;
		min-width: 0;
		color: var(--color-main-text);
	}

	&__row-toggles {
		display: flex;
		gap: 16px;
		flex-shrink: 0;
	}
}
</style>
