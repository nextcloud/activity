/**
 * @copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 */

(function() {
	OCA.Activity = OCA.Activity || {};

	OCA.Activity.RichObjectStringParser = {
		avatarsEnabled: true,

		_fileTemplate: '<a class="filename has-tooltip" href="{{link}}" title="{{title}}">{{name}}</a>',
		_fileNoPathTemplate: '<a class="filename" href="{{link}}">{{name}}</a>',

		_systemTagTemplate: '<strong class="systemtag">{{name}}</strong>',

		_emailTemplate: '<a class="email" href="mailto:{{id}}">{{name}}</a>',

		_userTemplate: '<strong>{{name}}</strong>',
		_userWithAvatarTemplate: '<div class="avatar" data-user="{{id}}" data-user-display-name="{{name}}"></div>',

		_unknownTemplate: '<strong>{{name}}</strong>',
		_unknownLinkTemplate: '<a href="{{link}}">{{name}}</a>',

		/**
		 * @param {string} subject
		 * @param {Object} parameters
		 * @returns {string}
		 */
		parseMessage: function(subject, parameters) {
			var self = this,
				regex = /\{([a-z0-9]+)\}/gi,
				matches = subject.match(regex);

			_.each(matches, function(parameter) {
				parameter = parameter.substring(1, parameter.length - 1);
				var parsed = self.parseParameter(parameters[parameter]);

				subject = subject.replace('{' + parameter + '}', parsed);
			});

			return subject;
		},

		/**
		 * @param {Object} parameter
		 * @param {string} parameter.type
		 * @param {string} parameter.id
		 * @param {string} parameter.name
		 * @param {string} parameter.link
		 */
		parseParameter: function(parameter) {
			switch (parameter.type) {
				case 'file':
					return this.parseFileParameter(parameter);

				case 'systemtag':
					if (!this.systemTagTemplate) {
						this.systemTagTemplate = Handlebars.compile(this._systemTagTemplate);
					}

					var name = parameter.name;
					if (parameter.visibility !== '1') {
						name = t('activity', '{name} (invisible)', parameter);
					} else if (parameter.assignable !== '1') {
						name = t('activity', '{name} (restricted)', parameter);
					}

					return this.systemTagTemplate({
						name: name
					});

				case 'email':
					if (!this.emailTemplate) {
						this.emailTemplate = Handlebars.compile(this._emailTemplate);
					}

					return this.emailTemplate(parameter);

				case 'user':
					if (!this.userTemplate) {
						var template = this._userTemplate;
						if (this.avatarsEnabled && _.isUndefined(parameter.server)) {
							template = this._userWithAvatarTemplate + template;
						}
						this.userTemplate = Handlebars.compile(template);
					}

					return this.userTemplate(parameter);

				default:
					if (!_.isUndefined(parameter.link)) {
						if (!this.unknownLinkTemplate) {
							this.unknownLinkTemplate = Handlebars.compile(this._unknownLinkTemplate);
						}
						return this.unknownLinkTemplate(parameter);
					}

					if (!this.unknownTemplate) {
						this.unknownTemplate = Handlebars.compile(this._unknownTemplate);
					}
					return this.unknownTemplate(parameter);
			}
		},

		/**
		 * @param {Object} parameter
		 * @param {string} parameter.type
		 * @param {string} parameter.id
		 * @param {string} parameter.name
		 * @param {string} parameter.path
		 * @param {string} parameter.link
		 */
		parseFileParameter: function(parameter) {
			if (!this.fileTemplate) {
				this.fileTemplate = Handlebars.compile(this._fileTemplate);
				this.fileNoPathTemplate = Handlebars.compile(this._fileNoPathTemplate);
			}
			var lastSlashPosition = parameter.path.lastIndexOf('/'),
				firstSlashPosition = parameter.path.indexOf('/');
			parameter.path = parameter.path.substring(firstSlashPosition === 0 ? 1 : 0, lastSlashPosition);

			if (!parameter.link) {
				parameter.link = OC.generateUrl('/f/{fileId}', {fileId: parameter.id})
			}

			if (parameter.path === '' || parameter.path === '/') {
				return this.fileNoPathTemplate(parameter);
			}
			return this.fileTemplate(_.extend(parameter, {
				title: parameter.path.length === 0 ? '' : t('activity', 'in {path}', parameter)
			}));
		}
	};

})();
