/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { createApp } from 'vue'
import AdminSettings from './views/AdminSettings.vue'
import DefaultActivitySettings from './views/DefaultActivitySettings.vue'
import store from './store/settings-store.ts'

const adminSettingApp = createApp(AdminSettings)
adminSettingApp.use(store)
adminSettingApp.mount('#activity-admin-settings')

const defaultSettingApp = createApp(DefaultActivitySettings)
defaultSettingApp.use(store)
defaultSettingApp.mount('#activity-default-settings')
