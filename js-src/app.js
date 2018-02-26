/**
 * @copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 * @copyright (c) 2015 Tom Needham <tom@owncloud.com>
 *
 * @author Tom Needham <tom@owncloud.com>
 * @author Joas Schilling <coding@schilljs.com>
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 */

/* global OC, OCA, $, _, t, define, console */

define(function (require) {
	"use strict";

	return {

		/** @type {Vue|null} */
		vm: null,

		/** @type {number} */
		firstKnownId: 0,

		/** @type {number} */
		lastGivenId: 0,

		/**
		 * Initialise the app
		 */
		initialise: function() {
			var filter = $('#app-content').attr('data-activity-filter');

			// Setup Vue
			var Vue = require('vue');
			Vue.mixin({
				methods: {
					t: function(app, text, vars, count, options) {
						return OC.L10N.translate(app, text, vars, count, options);
					},
					n: function(app, textSingular, textPlural, count, vars, options) {
						return OC.L10N.translatePlural(app, textSingular, textPlural, count, vars, options);
					}
				}
			});

			this.vm = new Vue(require('./components/root.vue'));
			this.vm.filter = filter;

			// Initial call to the activity endpoint
			this._fetch();

			// // Setup the background checker
			// this.interval = setInterval(this._backgroundFetch.bind(this), this.pollInterval);
		},

		/**
		 * Request a new bunch of activities from the server
		 */
		_fetch: function () {
			$.ajax({
				url: OC.linkToOCS('apps/activity/api/v2/activity', 2) + this.vm.filter + '?previews=true&since=' + this.lastGivenId,
				type: 'GET',
				beforeSend: function(xhr) {
					xhr.setRequestHeader('Accept-Language', OC.getLocale());
					xhr.setRequestHeader('Accept', 'application/json');
				},
				success: function(response, status, xhr) {
					this._saveHeaders(xhr.getAllResponseHeaders());
					this.vm.loading = false;

					if (status === 'notmodified') {
						// TODO reached the end, add something
						return;
					}

					if (typeof response !== 'undefined') {
						this.vm.activities = response.ocs.data;
						//this.ignoreScroll -= 1;
					}
				}.bind(this)
			});
		},

		/**
		 * Read the X-Activity-First-Known and X-Activity-Last-Given headers
		 * @param headers
		 */
		_saveHeaders: function(headers) {
			headers = headers.split("\n");
			_.each(headers, function (header) {
				var parts = header.split(':');
				if (parts[0].toLowerCase() === 'x-activity-first-known') {
					this.firstKnownId = parseInt(parts[1].trim(), 10);
				} else if (parts[0].toLowerCase() === 'x-activity-last-given') {
					this.lastGivenId = parseInt(parts[1].trim(), 10);
				}
			}.bind(this));
		// },
		//
		// /**
		//  * Performs the AJAX request to retrieve the notifications
		//  */
		// _fetch: function() {
		// 	var request = $.ajax({
		// 		url: OC.linkToOCS('apps/notifications/api/v2', 2) + 'notifications',
		// 		type: 'GET',
		// 		beforeSend: function (request) {
		// 			request.setRequestHeader('Accept', 'application/json');
		// 		}
		// 	});
		//
		// 	request.done(function(data, statusText, xhr) {
		// 		if (xhr.status === 204) {
		// 			// 204 No Content - Intercept when no notifiers are there.
		// 			this._shutDownNotifications();
		// 		} else if (!_.isUndefined(data) && !_.isUndefined(data.ocs) && !_.isUndefined(data.ocs.data) && _.isArray(data.ocs.data)) {
		// 			this.vm.notifications = data.ocs.data;
		// 		} else {
		// 			console.debug("data.ocs.data is undefined or not an array");
		// 		}
		// 	}.bind(this));
		// 	request.fail(function(xhr) {
		// 		if (xhr.status === 503) {
		// 			// 503 - Maintenance mode
		// 			console.debug('Shutting down notifications: instance is in maintenance mode.');
		// 		} else if (xhr.status === 404) {
		// 			// 404 - App disabled
		// 			console.debug('Shutting down notifications: app is disabled.');
		// 		} else {
		// 			console.error('Shutting down notifications: [' + xhr.status + '] ' + xhr.statusText);
		// 		}
		//
		// 		this._shutDownNotifications();
		// 	}.bind(this));
		// },
		//
		// _backgroundFetch: function() {
		// 	this.vm.backgroundFetching = true;
		// 	this._fetch();
		// },
		//
		// /**
		//  * The app was disabled or has no notifiers, so we can stop polling
		//  * And hide the UI as well
		//  */
		// _shutDownNotifications: function() {
		// 	window.clearInterval(this.interval);
		// 	this.vm.shutdown = true;
		}
	};
});
