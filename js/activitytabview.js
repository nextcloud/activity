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
		'<div>' +
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
		'        <div class="activitymessage">{{message}}</div>' +
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
		var output = {
			subject: activity.get('subjectformatted').markup.trimmed,
			formattedDate: activity.get('relativeDateTimestamp'),
			formattedDateTooltip: activity.get('readableDateTimestamp'),
			message: activity.get('messageformatted').markup.trimmed
		};

		if (activity.has('typeicon')) {
			output.typeIconClass = activity.get('typeicon') + ' svg';
		}
		if (activity.has('previews')) {
			output.previews = _.map(activity.get('previews'), function(data) {
				return {
					previewClass: data.isMimeTypeIcon ? 'preview-mimetype-icon': '',
					source: data.source
				};
			});
		}
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

		/**
		 * @type {OCA.Activity.ActivityCollection}
		 */
		_activities: null,

		initialize: function() {
			this._activities = new OCA.Activity.ActivityCollection();
			this._activities.setObjectType('files');
			this._activities.on('request', this._onRequest, this);
			this._activities.on('sync', this._onChange, this);
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
				this._activities.setObjectId(this._fileInfo.get('id'));
				this._activities.fetch();
			} else {
				this._activities.reset();
			}
		},

		_onRequest: function() {
			this._loading = true;
			this.render();
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
					activities: this._activities.map(formatActivity),
					emptyMessage: t('activity', 'No activities')
				}));
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

