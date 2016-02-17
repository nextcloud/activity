/*
 * Copyright (c) 2015
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */

(function() {

	OCA.Activity = OCA.Activity || {};

	/**
	 * @class OCA.Activity.ActivityCollection
	 * @classdesc
	 *
	 * Displays activity information for a given file
	 *
	 */
	var ActivityCollection = OC.Backbone.Collection.extend(
		/** @lends OCA.Activity.ActivityCollection.prototype */ {

		/**
		 * Id of the file for which to filter activities by
		 *
		 * @var int
		 */
		_objectId: null,

		/**
		 * Type of the object to filter by
		 *
		 * @var string
		 */
		_objectType: null,

		model: OCA.Activity.ActivityModel,

		/**
		 * Sets the object id to filter by or null for all.
		 * 
		 * @param {int} objectId file id or null
		 */
		setObjectId: function(objectId) {
			this._objectId = objectId;
		},

		/**
		 * Sets the object type to filter by or null for all.
		 * 
		 * @param {int} objectType file id or null
		 */
		setObjectType: function(objectType) {
			this._objectType = objectType;
		},

		/**
		 *
		 * @param ocsResponse
		 * @param response
		 * @returns {Array}
		 */
		parse: function(ocsResponse, response) {
			if (response.xhr.status === 304) {
				// No activities found
				return [];
			}

			return ocsResponse.ocs.data;
		},

		url: function() {
			var query = {
				format: 'json'
			};
			//var url = OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filter';
			var url = OC.generateUrl('/apps/activity/api/v2/activity') + '/filter';
			if (this._objectId && this._objectType) {
				query.object_type = this._objectType;
				query.object_id = this._objectId;
			}
			url += '?' + OC.buildQueryString(query);
			return url;
		}
	});

	OCA.Activity.ActivityCollection = ActivityCollection;
})();

