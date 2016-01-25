/*
 * Copyright (c) 2016
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */
(function(){
	OCA.Activity = OCA.Activity || {};

	OCA.Activity.Formatter = {

		_avatars: true,

		setAvatarStatus: function (status) {
			this._avatars = !!status;
		},

		isAvatarsEnabled: function () {
			return this._avatars;
		},

		/**
		 * Parses a message
		 *
		 * @param {String} message
		 * @param {boolean} forceFullMessage
		 * @returns {String}
		 */
		parseMessage: function (message, forceFullMessage) {
			var parsedMessage = this._parseCollection(message, forceFullMessage || false);
			parsedMessage = this._parseParameters(parsedMessage, true);
			return parsedMessage;
		},

		/**
		 * Parses a collection tag
		 *
		 * @param {String} message
		 * @param {boolean} forceFullMessage
		 * @returns {String}
		 */
		_parseCollection: function (message, forceFullMessage) {
			var self = this;

			return message.replace(/<collection>(.*?)<\/collection>/g, function (match, parameterString) {
				var parameterList = parameterString.split('><'),
					parameterListLength = parameterList.length,
					parameters = [];

				for (var i = 0; i < parameterListLength; i++) {
					var parameter = parameterList[i];
					if (i > 0) {
						parameter = '<' + parameter;
					}
					if (i + 1 < parameterListLength) {
						parameter = parameter + '>';
					}

					if (parameterListLength > 5 && i > 2 && !forceFullMessage) {
						parameters.push(self._parseParameters(parameter, false));
					} else {
						parameters.push(self._parseParameters(parameter, true));
					}
				}

				if (parameters.length === 1) {
					return parameters.pop();
				} else if (parameters.length <= 5 || forceFullMessage) {
					var lastParameter = parameters.pop();
					return t('activity', '{parameterList} and {lastParameter}', {
						parameterList: parameters.join(t('activity', ', ')),
						lastParameter: lastParameter
					}, undefined, {
						escape: false
					});
				} else {
					var firstParameters = parameters.slice(0, 3).join(t('activity', ', ')),
						otherParameters = parameters.slice(3).join(t('activity', ', ')),
						listLength = parameters.length;

					return n('activity',
						'{parameterList} and {linkStart}%n more{linkEnd}',
						'{parameterList} and {linkStart}%n more{linkEnd}',
						listLength - 3,
						{
							parameterList: firstParameters,
							linkStart: '<a class="activity-more-link" href="#"><strong class="has-tooltip" title="' + otherParameters + '">',
							linkEnd: '</strong></a>'
						},
						{
							escape: false
						}
					);
				}
			});
		},

		/**
		 * Parses parameters
		 *
		 * @param {String} message
		 * @param {boolean} useHtml
		 * @returns {String}
		 */
		_parseParameters: function (message, useHtml) {
			message = this._parseUntypedParameters(message, useHtml);
			message = this._parseUserParameters(message, useHtml);
			message = this._parseFederatedCloudIDParameters(message, useHtml);
			message = this._parseFileParameters(message, useHtml);

			return message;
		},

		/**
		 * Parses a parameter tag
		 *
		 * @param {String} message
		 * @param {boolean} useHtml
		 * @returns {String}
		 */
		_parseUntypedParameters: function(message, useHtml) {
			return message.replace(/<parameter>(.*?)<\/parameter>/g, function (match, parameter) {
				if (useHtml) {
					return '<strong>' + parameter + '</strong>';
				} else {
					return parameter;
				}
			});
		},

		/**
		 * Parses a user tag
		 *
		 * @param {String} message
		 * @param {boolean} useHtml
		 * @returns {String}
		 */
		_parseUserParameters: function(message, useHtml) {
			var self = this;
			return message.replace(/<user\ display\-name=\"(.*?)\">(.*?)<\/user>/g, function (match, displayName, userId) {
				if (useHtml) {
					var userString = '<strong>' + displayName +  '</strong>';
					if (self.isAvatarsEnabled()) {
						userString = '<div class="avatar" data-user="' + userId + '" data-user-display-name="' + displayName + '"></div>' + userString;
					}

					return userString;
				} else {
					return displayName;
				}
			});
		},

		/**
		 * Parses a federated cloud id tag
		 *
		 * @param {String} message
		 * @param {boolean} useHtml
		 * @returns {String}
		 */
		_parseFederatedCloudIDParameters: function(message, useHtml) {
			return message.replace(/<federated-cloud-id\ display\-name=\"(.*?)\"\ user=\"(.*?)\"\ server=\"(.*?)\">(.*?)<\/federated-cloud-id>/g, function (match, displayName, userId, server, cloudId) {
				if (displayName === cloudId) {
					// No display name from contacts, use a short version of the id in the UI
					displayName = userId + '@…';
				}

				if (useHtml) {
					return '<strong class="has-tooltip" title="' + cloudId + '">' + displayName + '</strong>';
				} else {
					return displayName;
				}
			});
		},

		/**
		 * Parses a file tag
		 *
		 * @param {String} message
		 * @param {boolean} useHtml
		 * @returns {String}
		 */
		_parseFileParameters: function(message, useHtml) {
			return message.replace(/<file\ link=\"(.*?)\"\ id=\"(.*?)\">(.*?)<\/file>/g, function (match, link, fileId, path) {
				var title = '',
					displayPath = path,
					lastSlashPosition = path.lastIndexOf('/');


				if (lastSlashPosition > 0) {
					var dirPath = path.substring(0, lastSlashPosition);
					displayPath = path.substring(lastSlashPosition + 1);

					// No display name from contacts, use a short version of the id in the UI
					title = '" title="' + escapeHTML(t('activity', 'in {directory}', {
						directory: dirPath
					}));
				}

				if (useHtml) {
					return '<a class="filename has-tooltip" href="' + link + title + '">' + displayPath + '</a>';
				} else {
					return path;
				}
			});
		}
	};
})();

