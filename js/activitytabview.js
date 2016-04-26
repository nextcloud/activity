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
	var TEMPLATE =
		'<div class="activity-section">' +
		'{{#if loading}}' +
		'<div class="loading" style="height: 50px"></div>' +
		'{{end}}' +
		'{{else}}' +
		'<ul>' +
		'{{#each activities}}' +
		'    <li class="activity box">' +
		'        <div class="activity-icon {{typeIconClass}}"></div>' +
		'        <div class="activitysubject">{{{subject}}}</div>' +
		'        <span class="activitytime has-tooltip" title="{{formattedDateTooltip}}">{{formattedDate}}</span>' +
		'        <div class="activitymessage">{{{message}}}</div>' +
		'        {{#if previews}}' +
		'        <div class="previews">' +
		'        {{#each previews}}' +
		'            <img class="preview {{previewClass}}" src="{{source}}" alt="" />' +
		'        {{/each}}' +
		'        </div>' +
		'        {{/if}}' +
		'    </li>' +
		'{{else}}' +
		'    <li class="empty">{{emptyMessage}}</li>' +
		'{{/each}}' +
		'</ul>' +
		'{{/if}}' +
		'</div>';

	/**
	 * Format an activity model for display
	 *
	 * @param {OCA.Activity.ActivityModel} activity
	 * @return {Object}
	 */
	function formatActivity(activity) {
		var timestamp = moment(activity.get('datetime')).valueOf(),
			output = {
				subject: OCA.Activity.Formatter.parseMessage(activity.get('subject_prepared'), false),
				formattedDate: OC.Util.relativeModifiedDate(timestamp),
				formattedDateTooltip: OC.Util.formatDate(timestamp),
				message: OCA.Activity.Formatter.parseMessage(activity.get('message_prepared'), false)
			};

		if (activity.has('typeicon')) {
			output.typeIconClass = activity.get('typeicon') + ' svg';
		}
		/**
		 * Disable previews in the rightside bar,
		 * it's always the same image anyway.
		if (activity.has('previews')) {
			output.previews = _.map(activity.get('previews'), function(data) {
				return {
					previewClass: data.isMimeTypeIcon ? 'preview-mimetype-icon': '',
					source: data.source
				};
			});
		}
		*/
		return output;
	}

	/**
	 * @class OCA.Activity.ActivityTabView
	 * @classdesc
	 *
	 * Displays activity information for a given file
	 *
	 */
	var ActivityTabView = OCA.Files.DetailTabView.extend(
		/** @lends OCA.Activity.ActivityTabView.prototype */ {
		id: 'activityTabView',
		className: 'activityTabView tab',

		_loading: false,

		initialize: function() {
			this.collection = new OCA.Activity.ActivityCollection();
			this.collection.setObjectType('files');
			this.collection.on('request', this._onRequest, this);
			this.collection.on('sync', this._onEndRequest, this);
			this.collection.on('update', this._onChange, this);
			this.collection.on('error', this._onError, this);
		},

		template: function(data) {
			if (!this._template) {
				this._template = Handlebars.compile(TEMPLATE);
			}
			return this._template(data);
		},

		get$: function() {
			return this.$el;
		},

		getLabel: function() {
			return t('activity', 'Activities');
		},

		setFileInfo: function(fileInfo) {
			this._fileInfo = fileInfo;
			if (this._fileInfo) {
				this.collection.setObjectId(this._fileInfo.get('id'));
				this.collection.fetch();
			} else {
				this.collection.reset();
			}
		},

		_onError: function() {
			OC.Notification.showTemporary(t('activity', 'Error loading activities'));
		},

		_onRequest: function() {
			this._loading = true;
			this.render();
		},

		_onEndRequest: function() {
			this._loading = false;
			// empty result ?
			if (!this.collection.length) {
				// render now as there will be no update event
				this.render();
			}
		},

		_onChange: function() {
			this._loading = false;
			this.render();
		},

		/**
		 * Renders this details view
		 */
		render: function() {
			if (this._fileInfo) {
				this.$el.html(this.template({
					loading: this._loading,
					activities: this.collection.map(formatActivity),
					emptyMessage: t('activity', 'No activities')
				}));
				this.$el.find('.avatar').each(function() {
					var element = $(this);
					element.avatar(element.data('user'), 28);
				});
				this.$el.find('.has-tooltip').tooltip({
					placement: 'bottom'
				});
			} else {
				// TODO: render placeholder text?
			}
		}
	});

	OCA.Activity = OCA.Activity || {};
	OCA.Activity.ActivityTabView = ActivityTabView;
})();

