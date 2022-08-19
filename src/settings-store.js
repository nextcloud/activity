/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import Vue from 'vue'
import Vuex from 'vuex'
import { translate as t } from '@nextcloud/l10n'

import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import { loadState } from '@nextcloud/initial-state'
import { showSuccess, showError } from '@nextcloud/dialogs'
import '@nextcloud/dialogs/styles/toast.scss'

import { isActivityEnabled, isOneInputUnChecked } from './helpers/settings'
import logger from './logger'

Vue.use(Vuex)

/**
 * @typedef {object} SettingsState
 * @property {object} setting
 * @property {object<string, ActivityGroup>} activityGroups
 * @property {boolean} isEmailSet
 * @property {boolean} emailEnabled
 * @property {boolean} activityDigestEnabled
 * @property {0|1|2|3} settingBatchtime
 * @property {Array<string>} methods
 * @property {string} endpoint
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
				.map(group => Object.entries(group.activities)) // [[[activityKey, activity], ...], [[activityKey, activity], ...]]
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
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
		 * @param {object} payload - The payload.
		 * @param {boolean} payload.emailEnabled - Enabled status of the email notifications.
		 * @param state
		 */
		TOGGLE_EMAIL_ENABLED(state, { emailEnabled }) {
			state.emailEnabled = emailEnabled
		},
	},
	actions: {
		/**
		 * Set the endpoint used to save the settings.
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
		 * @param {object} payload - The payload.
		 * @param {string} payload.endpoint - Where to POST the saveSettings request.
		 */
		setEndpoint({ commit }, { endpoint }) {
			commit('SET_ENDPOINT', { endpoint })
		},
		/**
		 * Toggle the 'enabled' state of a notification method for a given group/activity/method tuple
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
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
				})

			dispatch('saveSettings')
		},
		/**
		 * Toggle the 'enabled' state of a notification method for a given group/method tuple
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
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
					})
			}

			dispatch('saveSettings')
		},
		/**
		 * Toggle the 'enabled' state of a notification method for a given group/activity tuple
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
		 * @param {object} payload - The payload.
		 * @param {string} payload.groupKey - The targeted group
		 * @param {string} payload.activityKey - The targeted activity
		 */
		toggleMethodsForActivity({ commit, state, dispatch }, { groupKey, activityKey }) {
			const activity = state.activityGroups[groupKey].activities[activityKey]
			const oneInputIsChecked = activity.methods.map(method => isOneInputUnChecked([activity], method)).includes(true)

			for (const methodKey of activity.methods) {
				commit(
					'SET_METHOD_FOR_METHOD_AND_ACTIVITY',
					{
						groupKey,
						activityKey,
						methodKey,
						value: oneInputIsChecked,
					})
			}

			dispatch('saveSettings')
		},
		/**
		 * Set the batch time.
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
		 * @param {object} payload - The payload.
		 * @param {0|1|2|3} payload.settingBatchtime - The selected batch time.
		 */
		setSettingBatchtime({ commit, dispatch }, { settingBatchtime }) {
			commit(
				'SET_SETTING_BATCHTIME',
				{
					settingBatchtime,
				})

			dispatch('saveSettings')
		},
		/**
		 * Toggle the activity digest.
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
		 * @param {object} payload - The payload.
		 * @param {boolean} payload.activityDigestEnabled - Enabled status of the activity digest.
		 */
		toggleActivityDigestEnabled({ commit, dispatch }, { activityDigestEnabled }) {
			commit(
				'TOGGLE_ACTIVITY_DIGEST',
				{
					activityDigestEnabled,
				})

			dispatch('saveSettings')
		},
		/**
		 * Toggle the availability of mail notifications
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} store -
		 * @param {object} payload - The payload.
		 * @param {boolean} payload.emailEnabled - Enabled status of the email notifications.
		 */
		toggleEmailEnabled({ commit }, { emailEnabled }) {
			commit(
				'TOGGLE_EMAIL_ENABLED',
				{
					emailEnabled,
				})

			try {

				OCP.AppConfig.setValue(
					'activity', 'enable_email',
					emailEnabled ? 'yes' : 'no'
				)

				showSuccess(t('activity', 'Your settings have been updated.'))
			} catch (error) {
				showError(t('activity', 'Unable to save the settings'))
				logger.error('An error occurred while saving the activity settings', error)
			}
		},
		/**
		 * Save the currently displayed settings
		 *
		 * @param {import('vuex').ActionContext<SettingsState, SettingsState>} _ -
		 */
		async saveSettings({ state, getters }) {
			try {
				const form = new FormData()
				getters.checkedActivities.forEach(activity => {
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
