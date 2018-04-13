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

		/**
		 * Initialise the app
		 */
		initialise: function() {
			let filter = $('#app-content').attr('data-activity-filter'),
				feedLink= $('#app-content').attr('data-feed-link');

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
			this.vm.feedLink = feedLink;
		}
	};
});
