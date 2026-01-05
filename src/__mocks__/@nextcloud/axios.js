/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import wsData from './activity_ws.json' with { type: 'json' }

const axios = {
	/**
	 * @param {string} url URL to get
	 */
	get(url) {
		return new Promise((resolve, reject) => {
			if (url.endsWith('/ocs/v2.php/apps/activity/api/v2/activity/filter')) {
				resolve({ data: wsData })
			} else {
				reject(new Error(`URL not defined ${url}`))
			}
		})
	},
}

export default axios
