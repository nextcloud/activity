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
	/**
	 * @class OCA.Activity.ActivityModel
	 * @classdesc
	 *
	 * Displays activity information for a given file
	 *
	 */
	var ActivityModel = OC.Backbone.Model.extend(/** @lends OCA.Activity.ActivityModel.prototype */{
		/**
		 *
		 * @returns int UNIX milliseconds timestamp
		 */
		getUnixMilliseconds: function () {
			if (_.isUndefined(this.unixMilliseconds)) {
				this.unixMilliseconds = moment(this.get('datetime')).valueOf();
			}
			return this.unixMilliseconds;
		},

		/**
		 * @returns string E.g. "seconds ago"
		 */
		getRelativeDate: function () {
			return OC.Util.relativeModifiedDate(this.getUnixMilliseconds());
		},

		/**
		 * @returns string E.g. "April 26, 2016 10:53 AM"
		 */
		getFullDate: function () {
			return OC.Util.formatDate(this.getUnixMilliseconds());
		},

		/**
		 * @returns bool
		 */
		isMonochromeIcon: function () {
			return this.get('type') !== 'file_created' && this.get('type') !== 'file_deleted' && this.get('type') !== 'favorite';
		}
	});

	OCA.Activity = OCA.Activity || {};
	OCA.Activity.ActivityModel = ActivityModel;
})();

