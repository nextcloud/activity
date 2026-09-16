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
				// Real responses carry the pagination headers; no `link` here
				// because this fixture is the whole list
				resolve({
					data: wsData,
					headers: {
						'x-activity-last-given': String(wsData.ocs.data.at(-1).activity_id),
					},
				})
			} else if (url.endsWith('/ocs/v2.php/apps/activity/api/v2/activity/downloads/count')) {
				resolve({ data: { ocs: { meta: { status: 'ok', statuscode: 200 }, data: { total: 10, last30d: 3 } } }, headers: {} })
			} else {
				reject(new Error(`URL not defined ${url}`))
			}
		})
	},
}

export default axios
