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
		_plugins: [],

		initialize: function() {
			this.collection = new OCA.Activity.ActivityCollection();
			this.collection.setObjectType('files');
			this.collection.on('request', this._onRequest, this);
			this.collection.on('sync', this._onEndRequest, this);
			this.collection.on('error', this._onError, this);
			this.collection.on('add', this._onAddModel, this);

			this._plugins = OC.Plugins.getPlugins('OCA.Activity.RenderingPlugins');
			_.each(this._plugins, function(plugin) {
				if (_.isFunction(plugin.initialize)) {
					plugin.initialize();
				}
			});
		},

		template: function(data) {
			return OCA.Activity.Templates['activitytabview'](data);
		},

		get$: function() {
			return this.$el;
		},

		getLabel: function() {
			return t('activity', 'Activity');
		},

		getIcon: function() {
			return 'icon-activity';
		},

		setFileInfo: function(fileInfo) {
			this._fileInfo = fileInfo;
			if (this._fileInfo) {
				this.collection.setObjectId(this._fileInfo.get('id'));
				this.collection.reset();
				this.collection.fetch();

				_.each(this._plugins, function(plugin) {
					if (_.isFunction(plugin.setFileInfo)) {
						plugin.setFileInfo('files', fileInfo.get('id'));
					}
				});
			} else {
				this.collection.reset();

				_.each(this._plugins, function(plugin) {
					if (_.isFunction(plugin.resetFileInfo)) {
						plugin.resetFileInfo();
					}
				});
			}
		},

		_onError: function() {
			var $emptyContent = this.$el.find('.emptycontent');
			$emptyContent.removeClass('hidden');
			$emptyContent.find('p').text(t('activity', 'An error occurred while loading activities'));
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
				this.$el.find('.emptycontent').addClass('hidden');
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

			var subject = escapeHTML(activity.get('subject')),
				subject_rich = activity.get('subject_rich');
			if (subject_rich[0].length > 1) {
				subject = OCA.Activity.RichObjectStringParser.parseMessage(subject_rich[0], subject_rich[1]);
			}
			var message = escapeHTML(activity.get('message')),
				message_rich = activity.get('message_rich');
			if (message_rich[0].length > 1) {
				message = OCA.Activity.RichObjectStringParser.parseMessage(message_rich[0], message_rich[1]);
			}

			var output = {
				subject: subject,
				formattedDate: activity.getRelativeDate(),
				formattedDateTooltip: activity.getFullDate(),
				isMonochromeIcon: activity.isMonochromeIcon(),
				timestamp: moment(activity.get('datetime')).valueOf(),
				message: message,
				icon: activity.get('icon')
			};

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
			return OCA.Activity.Templates['activitytabview_activity'](params);
		},

		_onAddModel: function(model, collection, options) {
			var $el = $(this.activityTemplate(this._formatItem(model)));

			_.each(this._plugins, function(plugin) {
				if (_.isFunction(plugin.prepareModelForDisplay)) {
					plugin.prepareModelForDisplay(model, $el, 'ActivityTabView');
				}
			});

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
				if (element.data('user-display-name')) {
					element.avatar(element.data('user'), 21, undefined, false, undefined, element.data('user-display-name'));
				} else {
					element.avatar(element.data('user'), 21);
				}
			});
			$el.find('.avatar-name-wrapper').each(function() {
				var element = $(this);
				var avatar = element.find('.avatar');
				var label = element.find('strong');

				$.merge(avatar, label).contactsMenu(element.data('user'), 0, element);
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
					emptyMessage: t('activity', 'No activity yet'),
					moreLabel: t('activity', 'Load more activities')
				}));
				this.$container = this.$el.find('ul.activities');
			}
		}
	});

	OCA.Activity = OCA.Activity || {};
	OCA.Activity.ActivityTabView = ActivityTabView;
})();
