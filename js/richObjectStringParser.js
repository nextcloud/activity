/**
 * @copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 */

(function(OC, OCA) {
	OCA.Activity = OCA.Activity || {};

	OCA.Activity.RichObjectStringParser = {
		avatarsEnabled: true,

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
					var name = parameter.name;
					if (parameter.visibility !== '1') {
						name = t('activity', '{name} (invisible)', parameter);
					} else if (parameter.assignable !== '1') {
						name = t('activity', '{name} (restricted)', parameter);
					}

					return OCA.Activity.Templates.systemTag({
						name: name
					});

				case 'email':
					return OCA.Activity.Templates.email(parameter);

				case 'open-graph':
					return OCA.Activity.Templates.openGraph(parameter);

				case 'user':
					if (_.isUndefined(parameter.server)) {
						return OCA.Activity.Templates.userLocal(parameter);
					}

					return OCA.Activity.Templates.userRemote(parameter);

				default:
					if (!_.isUndefined(parameter.link)) {
						return OCA.Activity.Templates.unkownLink(parameter);
					}

					return OCA.Activity.Templates.unknown(parameter);
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
			if (parameter.path === '') {
				return OCA.Activity.Templates.fileRoot(parameter);
			}

			var lastSlashPosition = parameter.path.lastIndexOf('/'),
				firstSlashPosition = parameter.path.indexOf('/');
			parameter.path = parameter.path.substring(firstSlashPosition === 0 ? 1 : 0, lastSlashPosition);

			if (!parameter.link) {
				parameter.link = OC.generateUrl('/f/{fileId}', {fileId: parameter.id});
			}

			if (parameter.path === '' || parameter.path === '/') {
				return OCA.Activity.Templates.fileNoPath(parameter);
			}
			return OCA.Activity.Templates.file(_.extend(parameter, {
				title: parameter.path.length === 0 ? '' : t('activity', 'in {path}', parameter)
			}));
		}
	};

})(OC, OCA);
