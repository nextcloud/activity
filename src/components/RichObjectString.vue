<template>
	<a v-if="wrapMessageInLink" v-html="richObjectString" :href="link"></a>
	<div v-else v-html="richObjectString"></div>
</template>

<script>
	import "../../js/templates2";
	export default {
		name: 'rich-object-string',

		props: [
			'string',
			'parameters',
			'link'
		],

		computed: {
			richObjectString () {
				var subject = this.string,
					regex = /\{([a-z0-9]+)\}/gi,
					matches = subject.match(regex);

				_.each(matches, function (parameter) {
					parameter = parameter.substring(1, parameter.length - 1);
					if (!this.parameters.hasOwnProperty(parameter) || !this.parameters[parameter]) {
						// Malformed translation?
						console.error('Potential malformed ROS string: parameter {' + parameter + '} was found in the string but is missing from the parameter list');
						return;
					}

					var parsed = this.parseParameter(this.parameters[parameter]);
					subject = subject.replace('{' + parameter + '}', parsed);
				}.bind(this));

				return subject;
			},

			wrapMessageInLink () {
				return this.link && this.richObjectString.indexOf('<a ') === -1;
			},
		},

		methods: {

			/**
			 * @param {Object} parameter
			 * @param {string} parameter.type
			 * @param {string} parameter.id
			 * @param {string} parameter.name
			 * @param {string} parameter.link
			 */
			parseParameter (parameter) {
				console.log(Handlebars);
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

						return Handlebars.templates.systemTag({
							name: name
						});

					case 'email':
						return Handlebars.templates.email(parameter);

					case 'open-graph':
						return Handlebars.templates.openGraph(parameter);

					case 'user':
						if (_.isUndefined(parameter.server)) {
							return Handlebars.userLocal(parameter);
						}

						return Handlebars.templates.userRemote(parameter);

					default:
						if (!_.isUndefined(parameter.link)) {
							return Handlebars.templates.unkownLink(parameter);
						}

						return Handlebars.templates.unknown(parameter);
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
			parseFileParameter (parameter) {
				if (parameter.path === '') {
					return Handlebars.templates.fileRoot(parameter);
				}

				var lastSlashPosition = parameter.path.lastIndexOf('/'),
					firstSlashPosition = parameter.path.indexOf('/');
				parameter.path = parameter.path.substring(firstSlashPosition === 0 ? 1 : 0, lastSlashPosition);

				if (!parameter.link) {
					parameter.link = OC.generateUrl('/f/{fileId}', {fileId: parameter.id});
				}

				if (parameter.path === '' || parameter.path === '/') {
					return Handlebars.templates.fileNoPath(parameter);
				}
				return Handlebars.templates.file(_.extend(parameter, {
					title: parameter.path.length === 0 ? '' : t('activity', 'in {path}', parameter)
				}));
			}
		}
	}
</script>
