/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import axios from '@nextcloud/axios'
import { showError, showSuccess } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'
import Vue from 'vue'
import Vuex from 'vuex'
import logger from '../utils/logger.ts'
import { isActivityEnabled, isOneInputUnChecked } from '../utils/settings.ts'

Vue.use(Vuex)

/**
 * @typedef {object} SettingsState
 * @property {object} setting .
 * @property {Object<string, ActivityGroup>} activityGroups Activity setting groups that should be shown (Files, Calendar, ...)
 * @property {boolean} isEmailSet Whether the user has a valid email address set
 * @property {boolean} emailEnabled Whether activity emails should be sent
 * @property {boolean} activityDigestEnabled Whether the daily digest should be sent
 * @property {0|1|2|3} settingBatchtime How to group the activity emails
 * @property {Array<string>} methods Available methods for activity (push, mail, ...)
 * @property {string} endpoint API endpoint to talk to (user or admin settings)
 */

const store = new Vuex.Store({
	strict: true,
	/** @type {SettingsState} */
	state: {
		setting: loadState('activity', 'setting'),
		activityGroups: loadState('activity', 'activity_groups'),
		isEmailSet: loadState('activity', 'is_email_set'),
		emailEnabled: loadState('activity', 'email_enabled'),
		activityDigestEnabled: loadState('activity', 'activity_digest_enabled', false),
		settingBatchtime: loadState('activity', 'setting_batchtime'),
		methods: loadState('activity', 'methods'),
		endpoint: '',
	},
	getters: {
		/**
		 * Return an array of checked activities.
		 *
		 * @param {SettingsState} state - The current state.
		 * @return {Array<string>}
		 */
		checkedActivities(state) {
			const methodsEnabled = (activityKey, activity) => {
				const methods = []
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
		 * @param {SettingsState} state - The current state.
		 * @param {object} payload - The payload.
		 * @param {string} payload.groupKey - The targeted group
		 * @param {string} payload.activityKey - The targeted activity
		 * @param {string} payload.methodKey - The targeted method
		 * @param {string} payload.value - The value to set
		 */
		SET_METHOD_FOR_METHOD_AND_ACTIVITY(state, { groupKey, activityKey, methodKey, value }) {
			const group = state.activityGroups[groupKey]
			const activity = group.activities[activityKey]

			if (isActivityEnabled(activity, methodKey)) {
				activity[methodKey] = value
			}
		},
		/**
		 * Set the endpoint used to save the settings.
		 *
		 * @param {SettingsState} state - The current state.
		 * @param {object} payload - The payload.
		 * @param {string} payload.endpoint - Where to POST the saveSettings request.
		 */
		SET_ENDPOINT(state, { endpoint }) {
			state.endpoint = endpoint
		},
		/**
		 * Set the batch time.
		 *
		 * @param {SettingsState} state - The current state.
		 * @param {object} payload - The payload.
		 * @param {0|1|2|3} payload.settingBatchtime - The selected batch time.
		 */
		SET_SETTING_BATCHTIME(state, { settingBatchtime }) {
			state.settingBatchtime = settingBatchtime
		},
		/**
		 * Toggle activity digest.
		 *
		 * @param {SettingsState} state - The current state.
		 * @param {object} payload - The payload.
		 * @param {boolean} payload.activityDigestEnabled - Enabled status of the activity digest.
		 */
		TOGGLE_ACTIVITY_DIGEST(state, { activityDigestEnabled }) {
			state.activityDigestEnabled = activityDigestEnabled
		},
		/**
		 * Toggle the availability of mail notifications
		 *
		 * @param {SettingsState} state - The current state.
		 * @param {object} payload - The payload.
		 * @param {boolean} payload.emailEnabled - Enabled status of the email notifications.
		 */
		TOGGLE_EMAIL_ENABLED(state, { emailEnabled }) {
			state.emailEnabled = emailEnabled
		},
	},
	actions: {
		/**
		 * Set the endpoint used to save the settings.
		 *
		 * @param {object} _ - Action context
		 * @param {Function} _.commit - State modifying function
		 * @param {object} payload - The payload.
		 * @param {string} payload.endpoint - Where to POST the saveSettings request.
		 */
		setEndpoint({ commit }, { endpoint }) {
			commit('SET_ENDPOINT', { endpoint })
		},

		/**
		 * Toggle the 'enabled' state of a notification method for a given group/activity/method tuple
		 *
		 * @param {object} _ - Action context
		 * @param {Function} _.commit - State modifying function
		 * @param {Function} _.dispatch - Function to dispatch another action
		 * @param {object} _.state - Current state
		 * @param {object} payload - The payload.
		 * @param {string} payload.groupKey - The targeted group
		 * @param {string} payload.activityKey - The targeted activity
		 * @param {string} payload.methodKey - The targeted method
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
		 * @param {object} _ - Action context
		 * @param {Function} _.commit - State modifying function
		 * @param {Function} _.dispatch - Function to dispatch another action
		 * @param {object} _.state - Current state
		 * @param {object} payload - The payload.
		 * @param {string} payload.groupKey - The targeted group
		 * @param {string} payload.methodKey - The targeted method
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
		 * @param {object} _ - Action context
		 * @param {Function} _.commit - State modifying function
		 * @param {Function} _.dispatch - Function to dispatch another action
		 * @param {object} _.state - Current state
		 * @param {object} payload - The payload.
		 * @param {string} payload.groupKey - The targeted group
		 * @param {string} payload.activityKey - The targeted activity
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
		 * @param {object} _ - Action context
		 * @param {Function} _.commit - State modifying function
		 * @param {Function} _.dispatch - Function to dispatch another action
		 * @param {object} payload - The payload.
		 * @param {0|1|2|3} payload.settingBatchtime - The selected batch time.
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
		 * @param {object} _ - Action context
		 * @param {Function} _.commit - State modifying function
		 * @param {Function} _.dispatch - Function to dispatch another action
		 * @param {object} payload - The payload.
		 * @param {boolean} payload.activityDigestEnabled - Enabled status of the activity digest.
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
		 * @param {object} _ - Action context
		 * @param {Function} _.commit - State modifying function
		 * @param {object} payload - The payload.
		 * @param {boolean} payload.emailEnabled - Enabled status of the email notifications.
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
				logger.error('An error occurred while saving the activity settings', error)
			}
		},

		/**
		 * Save the currently displayed settings
		 *
		 * @param {object} _ - Action context
		 * @param {object} _.state - Current state
		 * @param {object} _.getters - Getter functions for the state
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
				logger.error('An error occurred while saving the activity settings', error)
			}
		},
	},
})

export default store
