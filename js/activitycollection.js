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

		firstKnownId: 0,
		lastGivenId: 0,
		hasMore: false,

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
			this.firstKnownId = 0;
			this.lastGivenId = 0;
			this.hasMore = false;
		},

		/**
		 * Sets the object type to filter by or null for all.
		 * 
		 * @param {string} objectType string
		 */
		setObjectType: function(objectType) {
			this._objectType = objectType;
			this.firstKnownId = 0;
			this.lastGivenId = 0;
			this.hasMore = false;
		},

		/**
		 *
		 * @param ocsResponse
		 * @param response
		 * @returns {Array}
		 */
		parse: function(ocsResponse, response) {
			this._saveHeaders(response.xhr.getAllResponseHeaders());

			if (response.xhr.status === 304) {
				// No activities found
				return [];
			}

			return ocsResponse.ocs.data;
		},

		/**
		 * Read the X-Activity-First-Known and X-Activity-Last-Given headers
		 * @param headers
		 */
		_saveHeaders: function(headers) {
			var self = this;
			this.hasMore = false;

			headers = headers.split("\n");
			_.each(headers, function (header) {
				var parts = header.split(':');
				if (parts[0].toLowerCase() === 'x-activity-first-known') {
					self.firstKnownId = parseInt(parts[1].trim(), 10);
				} else if (parts[0].toLowerCase() === 'x-activity-last-given') {
					self.lastGivenId = parseInt(parts[1].trim(), 10);
				} else if (parts[0].toLowerCase() === 'link') {
					self.hasMore = true;
				}
			});
		},

		url: function() {
			var query = {
				format: 'json'
			};
			//var url = OC.linkToOCS('apps/activity/api/v2/activity', 2) + 'filter';
			var url = OC.generateUrl('/apps/activity/api/v2/activity') + '/filter';
			if (this.lastGivenId) {
				query.since = this.lastGivenId;
			}
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

