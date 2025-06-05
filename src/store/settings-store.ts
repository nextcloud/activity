/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { IActivityGroup } from '../models/ActivitySettings.ts'

import axios from '@nextcloud/axios'
import { showError, showSuccess } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'
import { createStore } from 'vuex'
import logger from '../utils/logger.ts'
import { isActivityEnabled, isOneInputUnChecked } from '../utils/settings.ts'

export type SettingsState = {
	setting: object
	activityGroups: IActivityGroup[] // Activity setting groups that should be shown (Files, Calendar, ...)
	isEmailSet: boolean // Whether the user has a valid email address set
	emailEnabled: boolean // Whether activity emails should be sent
	activityDigestEnabled: boolean // Whether the daily digest should be sent
	settingBatchtime: 0 | 1 | 2 | 3 // How to group the activity emails
	methods: Array<string> // Available methods for activity (push, mail, ...)
	endpoint: string // API endpoint to talk to (user or admin settings)
}

const store = new createStore({
	strict: true,
	state: {
		setting: loadState('activity', 'setting'),
		activityGroups: loadState('activity', 'activity_groups'),
		isEmailSet: loadState('activity', 'is_email_set'),
		emailEnabled: loadState('activity', 'email_enabled'),
		activityDigestEnabled: loadState('activity', 'activity_digest_enabled', false),
		settingBatchtime: loadState('activity', 'setting_batchtime'),
		methods: loadState('activity', 'methods'),
		endpoint: '',
	} as SettingsState,
	getters: {
		/**
		 * Return an array of checked activities.
		 *
		 * @param state - The current state.
		 */
		checkedActivities(state: SettingsState) {
			const methodsEnabled = (activityKey, activity) => {
				const methods = [] as { activityKey: string, method: string, activity: object }[]
				if (activity.email) {
					methods.push({ activityKey, method: 'email', activity })
				}
				if (activity.notification) {
					methods.push({ activityKey, method: 'notification', activity })
				}
				return methods
			}

			return Object.values(state.activityGroups)
				.map((group) => Object.entries(group.activities)) // [[[activityKey, activity], ...], [[activityKey, activity], ...]]
				.reduce((acc, val) => acc.concat(val), []) // [[activityKey, activity], ...]
				.map(([activityKey, activity]) => methodsEnabled(activityKey, activity)) // [[{activityKey, method, activity}, ...], ...]
				.reduce((acc, val) => acc.concat(val), [])
				.filter(({ activity, method }) => activity[method])
				.map(({ activityKey, method }) => `${activityKey}_${method}`) // ['enabled_activity_key', ...]
		},
	},
	mutations: {
		/**
		 * Update the 'enabled' state of a notification method for a given group/activity/method tuple
		 *
		 * @param state - The current state.
		 * @param payload - The payload.
		 * @param payload.groupKey - The targeted group
		 * @param payload.activityKey - The targeted activity
		 * @param payload.methodKey - The targeted method
		 * @param payload.value - The value to set
		 */
		SET_METHOD_FOR_METHOD_AND_ACTIVITY(state: SettingsState, { groupKey, activityKey, methodKey, value }) {
			const group = state.activityGroups[groupKey]
			const activity = group.activities[activityKey]

			if (isActivityEnabled(activity, methodKey)) {
				activity[methodKey] = value
			}
		},
		/**
		 * Set the endpoint used to save the settings.
		 *
		 * @param state - The current state.
		 * @param payload - The payload.
		 * @param payload.endpoint - Where to POST the saveSettings request.
		 */
		SET_ENDPOINT(state: SettingsState, { endpoint }) {
			state.endpoint = endpoint
		},
		/**
		 * Set the batch time.
		 *
		 * @param state - The current state.
		 * @param payload - The payload.
		 * @param payload.settingBatchtime - The selected batch time.
		 */
		SET_SETTING_BATCHTIME(state: SettingsState, { settingBatchtime }) {
			state.settingBatchtime = settingBatchtime
		},
		/**
		 * Toggle activity digest.
		 *
		 * @param state - The current state.
		 * @param payload - The payload.
		 * @param payload.activityDigestEnabled - Enabled status of the activity digest.
		 */
		TOGGLE_ACTIVITY_DIGEST(state: SettingsState, { activityDigestEnabled }) {
			state.activityDigestEnabled = activityDigestEnabled
		},
		/**
		 * Toggle the availability of mail notifications
		 *
		 * @param state - The current state.
		 * @param payload - The payload.
		 * @param payload.emailEnabled - Enabled status of the email notifications.
		 */
		TOGGLE_EMAIL_ENABLED(state: SettingsState, { emailEnabled }) {
			state.emailEnabled = emailEnabled
		},
	},
	actions: {
		/**
		 * Set the endpoint used to save the settings.
		 *
		 * @param _ - Action context
		 * @param _.commit - State modifying function
		 * @param payload - The payload.
		 * @param payload.endpoint - Where to POST the saveSettings request.
		 */
		setEndpoint({ commit }, { endpoint }) {
			commit('SET_ENDPOINT', { endpoint })
		},

		/**
		 * Toggle the 'enabled' state of a notification method for a given group/activity/method tuple
		 *
		 * @param _ - Action context
		 * @param _.commit - State modifying function
		 * @param _.dispatch - Function to dispatch another action
		 * @param _.state - Current state
		 * @param payload - The payload.
		 * @param payload.groupKey - The targeted group
		 * @param payload.activityKey - The targeted activity
		 * @param payload.methodKey - The targeted method
		 */
		toggleMethodForMethodAndActivity({ commit, state, dispatch }, { groupKey, activityKey, methodKey }) {
			const activity = state.activityGroups[groupKey].activities[activityKey]
			const oneInputIsChecked = isOneInputUnChecked([activity], methodKey)

			commit(
				'SET_METHOD_FOR_METHOD_AND_ACTIVITY',
				{
					groupKey,
					activityKey,
					methodKey,
					value: oneInputIsChecked,
				},
			)

			dispatch('saveSettings')
		},

		/**
		 * Toggle the 'enabled' state of a notification method for a given group/method tuple
		 *
		 * @param _ - Action context
		 * @param _.commit - State modifying function
		 * @param _.dispatch - Function to dispatch another action
		 * @param _.state - Current state
		 * @param payload - The payload.
		 * @param payload.groupKey - The targeted group
		 * @param payload.methodKey - The targeted method
		 */
		toggleMethodForGroup({ commit, state, dispatch }, { groupKey, methodKey }) {
			const activities = Object.values(state.activityGroups[groupKey].activities)
			const oneInputIsChecked = isOneInputUnChecked(activities, methodKey)

			for (const activityKey in state.activityGroups[groupKey].activities) {
				commit(
					'SET_METHOD_FOR_METHOD_AND_ACTIVITY',
					{
						groupKey,
						activityKey,
						methodKey,
						value: oneInputIsChecked,
					},
				)
			}

			dispatch('saveSettings')
		},

		/**
		 * Toggle the 'enabled' state of a notification method for a given group/activity tuple
		 *
		 * @param _ - Action context
		 * @param _.commit - State modifying function
		 * @param _.dispatch - Function to dispatch another action
		 * @param _.state - Current state
		 * @param payload - The payload.
		 * @param payload.groupKey - The targeted group
		 * @param payload.activityKey - The targeted activity
		 */
		toggleMethodsForActivity({ commit, state, dispatch }, { groupKey, activityKey }) {
			const activity = state.activityGroups[groupKey].activities[activityKey]
			const oneInputIsChecked = activity.methods.map((method) => isOneInputUnChecked([activity], method)).includes(true)

			for (const methodKey of activity.methods) {
				commit(
					'SET_METHOD_FOR_METHOD_AND_ACTIVITY',
					{
						groupKey,
						activityKey,
						methodKey,
						value: oneInputIsChecked,
					},
				)
			}

			dispatch('saveSettings')
		},

		/**
		 * Set the batch time.
		 *
		 * @param _ - Action context
		 * @param _.commit - State modifying function
		 * @param _.dispatch - Function to dispatch another action
		 * @param payload - The payload.
		 * @param payload.settingBatchtime - The selected batch time.
		 */
		setSettingBatchtime({ commit, dispatch }, { settingBatchtime }) {
			commit(
				'SET_SETTING_BATCHTIME',
				{
					settingBatchtime,
				},
			)

			dispatch('saveSettings')
		},

		/**
		 * Toggle the activity digest.
		 *
		 * @param _ - Action context
		 * @param _.commit - State modifying function
		 * @param _.dispatch - Function to dispatch another action
		 * @param payload - The payload.
		 * @param payload.activityDigestEnabled - Enabled status of the activity digest.
		 */
		toggleActivityDigestEnabled({ commit, dispatch }, { activityDigestEnabled }) {
			commit(
				'TOGGLE_ACTIVITY_DIGEST',
				{
					activityDigestEnabled,
				},
			)

			dispatch('saveSettings')
		},

		/**
		 * Toggle the availability of mail notifications
		 *
		 * @param _ - Action context
		 * @param _.commit - State modifying function
		 * @param payload - The payload.
		 * @param payload.emailEnabled - Enabled status of the email notifications.
		 */
		toggleEmailEnabled({ commit }, { emailEnabled }) {
			commit(
				'TOGGLE_EMAIL_ENABLED',
				{
					emailEnabled,
				},
			)

			try {
				OCP.AppConfig.setValue('activity', 'enable_email', emailEnabled ? 'yes' : 'no')

				showSuccess(t('activity', 'Your settings have been updated.'))
			} catch (error) {
				showError(t('activity', 'Unable to save the settings'))
				logger.error('An error occurred while saving the activity settings', { error })
			}
		},

		/**
		 * Save the currently displayed settings
		 *
		 * @param _ - Action context
		 * @param _.state - Current state
		 * @param _.getters - Getter functions for the state
		 */
		async saveSettings({ state, getters }) {
			try {
				const form = new FormData()
				getters.checkedActivities.forEach((activity) => {
					form.append(activity, '1')
				})

				form.append('notify_setting_batchtime', `${state.settingBatchtime}`)
				form.append('activity_digest', `${state.activityDigestEnabled ? 1 : 0}`)

				const response = await axios.post(generateUrl(state.endpoint), form)

				showSuccess(response.data.data.message)
			} catch (error) {
				showError(t('activity', 'Unable to save the settings'))
				logger.error('An error occurred while saving the activity settings', { error })
			}
		},
	},
})

export default store
