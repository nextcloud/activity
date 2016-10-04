/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

$(function() {
	OCA.Activity = OCA.Activity || {};

	OCA.Activity.FeedSettings = {
		urlInput: null,
		enableCheckbox: null,

		init: function () {
			this.urlInput = $('#rssurl');
			this.enableCheckbox = $('#enable_rss');
			this.enableCheckbox.change(_.bind(this._toggle, this));
		},

		_toggle: function (event) {
			var self = this;

			$.ajax({
				url: OC.generateUrl('/apps/activity/settings/feed'),
				type: 'post',
				data: {
					enable: event.target.checked
				},
				success: function(response) {
					if (event.target.checked) {
						self.urlInput.find('input').val(response.data.rsslink);
						self.urlInput.removeClass('hidden');
					} else {
						self.urlInput.addClass('hidden');
						self.urlInput.find('input').val(response.data.rsslink);
					}
				}
			});
		}
	};

	// Clipboard!
	var clipboard = new Clipboard('#rssurl .icon-clippy');
	clipboard.on('success', function(e) {
		$input = $(e.trigger);
		$input.tooltip({placement: 'top', trigger: 'manual', title: t('core', 'Copied!')});
		$input.tooltip('show');
		_.delay(function() {
			$input.tooltip('hide');
		}, 3000);
	});
	clipboard.on('error', function (e) {
		$input = $(e.trigger);
		var actionMsg = '';
		if (/iPhone|iPad/i.test(navigator.userAgent)) {
			actionMsg = t('core', 'Not supported!');
		} else if (/Mac/i.test(navigator.userAgent)) {
			actionMsg = t('core', 'Press ⌘-C to copy.');
		} else {
			actionMsg = t('core', 'Press Ctrl-C to copy.');
		}

		$input.tooltip({
			placement: 'top',
			trigger: 'manual',
			title: actionMsg
		});
		$input.tooltip('show');
		_.delay(function () {
			$input.tooltip('hide');
		}, 3000);
	});

	OCA.Activity.FeedSettings.init();
});
