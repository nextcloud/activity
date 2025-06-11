/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { createApp } from 'vue'
import DailySummary from './views/DailySummary.vue'
import UserSettings from './views/UserSettings.vue'
import store from './store/settings-store.js'

const userSettingApp = createApp(UserSettings)
userSettingApp.use(store)
userSettingApp.mount('#activity-user-settings')

const digestSettingApp = createApp(DailySummary)
digestSettingApp.use(store)
digestSettingApp.mount('#activity-digest-user-settings')
