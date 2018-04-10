/**
 * @copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 */

(function(OC, OCA, Handlebars) {
	OCA.Activity = OCA.Activity || {};

	OCA.Activity.RichObjectStringParser = {
		avatarsEnabled: true,

		_fileTemplate: '<a class="filename has-tooltip" href="{{link}}" title="{{title}}">{{name}}</a>',
		_fileNoPathTemplate: '<a class="filename" href="{{link}}">{{name}}</a>',
		_fileRootTemplate: '<a class="filename has-tooltip" href="{{link}}" title="' + t('activity', 'Home') + '"><span class="icon icon-home"></span></a>',

		_systemTagTemplate: '<strong class="systemtag">{{name}}</strong>',

		_emailTemplate: '<a class="email" href="mailto:{{id}}">{{name}}</a>',

		_userLocalTemplate: '<span class="avatar-name-wrapper" data-user="{{id}}"><div class="avatar" data-user="{{id}}" data-user-display-name="{{name}}"></div><strong>{{name}}</strong></span>',
		_userRemoteTemplate: '<strong>{{name}}</strong>',

		_openGraphTemplate: '{{#if link}}<a href="{{link}}">{{/if}}<div id="opengraph-{{id}}" class="opengraph">' +
		'{{#if thumb}}<div class="opengraph-thumb" style="background-image: url(\'{{thumb}}\')"></div>{{/if}}' +
		'<div class="opengraph-name {{#if thumb}}opengraph-with-thumb{{/if}}">{{name}}</div>' +
		'<div class="opengraph-description {{#if thumb}}opengraph-with-thumb{{/if}}">{{description}}</div>' +
		'<span class="opengraph-website">{{website}}</span></div>{{#if link}}</a>{{/if}}',

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
				if (!parameters.hasOwnProperty(parameter) || !parameters[parameter]) {
					// Malformed translation?
					console.error('Potential malformed ROS string: parameter {' + parameter + '} was found in the string but is missing from the parameter list');
					return;
				}

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

				case 'open-graph':
					if (!this.openGraphTemplate) {
						this.openGraphTemplate = Handlebars.compile(this._openGraphTemplate);
					}

					return this.openGraphTemplate(parameter);

				case 'user':
					if (_.isUndefined(parameter.server)) {
						if (!this.userLocalTemplate) {
							this.userLocalTemplate = Handlebars.compile(this._userLocalTemplate);
						}
						return this.userLocalTemplate(parameter);
					}

					if (!this.userRemoteTemplate) {
						this.userRemoteTemplate = Handlebars.compile(this._userRemoteTemplate);
					}

					return this.userRemoteTemplate(parameter);

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
				this.fileRootTemplate = Handlebars.compile(this._fileRootTemplate);
			}

			if (parameter.path === '') {
				return this.fileRootTemplate(parameter);
			}

			var lastSlashPosition = parameter.path.lastIndexOf('/'),
				firstSlashPosition = parameter.path.indexOf('/');
			parameter.path = parameter.path.substring(firstSlashPosition === 0 ? 1 : 0, lastSlashPosition);

			if (!parameter.link) {
				parameter.link = OC.generateUrl('/f/{fileId}', {fileId: parameter.id});
			}

			if (parameter.path === '' || parameter.path === '/') {
				return this.fileNoPathTemplate(parameter);
			}
			return this.fileTemplate(_.extend(parameter, {
				title: parameter.path.length === 0 ? '' : t('activity', 'in {path}', parameter)
			}));
		}
	};

})(OC, OCA, Handlebars);
