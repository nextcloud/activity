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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import { generateOcsUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'

import logger from '../logger'

export default {
	async getActivities() {
		try {
			this.loading = true

			const activities = await axios.get(
				generateOcsUrl('apps/activity/api/v2/activity/filter'),
				{
					params: {
						format: 'json',
						object_type: 'files',
						object_id: this.fileInfo.id,
					},
				})

			this.loading = false

			this.processActivities(activities)
		} catch (error) {
			// Status 304 is not an error.
			if (error.response !== undefined && error.response.status === 304) {
				this.loading = false
				return
			}
			this.error = t('activity', 'Unable to load the activity list')
			this.loading = false
			logger.error('Error loading the activity list', error)
		}
	}
}