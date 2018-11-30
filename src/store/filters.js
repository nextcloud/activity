/*
 * @copyright Copyright (c) 2018 Julius Härtl <jus@bitgrid.net>
 *
 * @author Julius Härtl <jus@bitgrid.net>
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

import axios from 'axios';
import Vue from 'vue';

const state = {
	filters: {},
	activities: []
};

const mutations = {
	addFilter(state, filter) {
		Vue.set(state.filters, filter.id, filter);
	},

	reset(state) {
		state.activities = [];
	}
};

const getters = {
	getFilters(state) {
		return state.filters;
	},
	getFilterIcon(state) {
		return function(id) {
			return typeof state.filters[id] !== 'undefined' ? state.filters[id].iconUrl : OC.imagePath('activity', 'activity-dark')
		}
	}
};

const actions = {

	fetchFilters(context) {
		return axios
			.get(OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filters', { headers: { requesttoken: OC.requestToken } })
			.then(response => {
				if (!_.isUndefined(response.data) && !_.isUndefined(response.data.ocs) && !_.isUndefined(response.data.ocs.data) && _.isArray(response.data.ocs.data)) {
					response.data.ocs.data.forEach(function(filter) {
						context.commit('addFilter', {
							id: filter.id,
							router: {
								name: (filter.id !== 'all') ? 'activity-filter' : 'activity-base',
								params: {
									filter: filter.id
								}
							},
							iconUrl: filter.icon,
							text: filter.name
						});
					});
				} else {
					console.debug("data.ocs.data is undefined or not an array");
				}
			})
			.catch(() => {
				OC.Notification.showTemporary(t('activity', 'Failed to load activity filters'));
			});
	},

};

export default { state, mutations, getters, actions };
