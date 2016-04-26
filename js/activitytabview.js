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
		'<div class="loading hidden" style="height: 50px"></div>' +
		'<ul class="activities hidden">' +
		'    <li class="empty">{{emptyMessage}}</li>' +
		'</ul>' +
		'<input type="button" class="showMore" value="{{moreLabel}}"' +
		'</div>';
	var ACTIVITY_TEMPLATE =
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
		'    </li>';

	/**
	 * @class OCA.Activity.ActivityTabView
	 * @classdesc
	 *
	 * Displays activity information for a given file
	 *
	 */
	var ActivityTabView = OCA.Files.DetailTabView.extend(/** @lends OCA.Activity.ActivityTabView.prototype */ {
		id: 'activityTabView',
		className: 'activityTabView tab',

		events: {
			'click .showMore': '_onClickShowMore'
		},

		_loading: false,

		initialize: function() {
			this.collection = new OCA.Activity.ActivityCollection();
			this.collection.setObjectType('files');
			this.collection.on('request', this._onRequest, this);
			this.collection.on('sync', this._onEndRequest, this);
			this.collection.on('error', this._onError, this);
			this.collection.on('add', this._onAddModel, this);
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
				this.collection.reset();
				this.collection.fetch();
			} else {
				this.collection.reset();
			}
		},

		_onError: function() {
			OC.Notification.showTemporary(t('activity', 'Error loading activities'));
		},

		_onRequest: function() {
			if (this.collection.lastGivenId === 0) {
				this.render();
			}
			this.$el.find('.showMore').addClass('hidden');
		},

		_onEndRequest: function() {
			this.$container.removeClass('hidden');
			this.$el.find('.loading').addClass('hidden');
			if (this.collection.length) {
				this.$container.find('li.empty').addClass('hidden');
			}
			if (this.collection.hasMore) {
				this.$el.find('.showMore').removeClass('hidden');
			}
		},

		_onClickShowMore: function() {
			this.collection.fetch({
				reset: false
			});
		},

		/**
		 * Format an activity model for display
		 *
		 * @param {OCA.Activity.ActivityModel} activity
		 * @return {Object}
		 */
		_formatItem: function(activity) {
			var output = {
				subject: OCA.Activity.Formatter.parseMessage(activity.get('subject_prepared'), false),
				formattedDate: activity.getRelativeDate(),
				formattedDateTooltip: activity.getFullDate(),
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
		},

		activityTemplate: function(params) {
			if (!this._activityTemplate) {
				this._activityTemplate = Handlebars.compile(ACTIVITY_TEMPLATE);
			}

			return this._activityTemplate(params);
		},

		_onAddModel: function(model, collection, options) {
			var $el = $(this.activityTemplate(this._formatItem(model)));
			if (!_.isUndefined(options.at) && collection.length > 1) {
				this.$container.find('li').eq(options.at).before($el);
			} else {
				this.$container.append($el);
			}

			this._postRenderItem($el);
		},

		_postRenderItem: function($el) {
			$el.find('.avatar').each(function() {
				var element = $(this);
				element.avatar(element.data('user'), 28);
			});
			$el.find('.has-tooltip').tooltip({
				placement: 'bottom'
			});
		},


		/**
		 * Renders this details view
		 */
		render: function() {
			if (this._fileInfo) {
				this.$el.html(this.template({
					emptyMessage: t('activity', 'No activities'),
					moreLabel: t('activity', 'Load more activities')
				}));
				this.$container = this.$el.find('ul.activities');
			}
		}
	});

	OCA.Activity = OCA.Activity || {};
	OCA.Activity.ActivityTabView = ActivityTabView;
})();

