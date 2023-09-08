const path = require('path')

const webpackConfig = require('@nextcloud/webpack-vue-config')

webpackConfig.entry = {
	sidebar: path.join(__dirname, 'src', 'sidebar.js'),
	personalSettings: path.join(__dirname, 'src', 'settings-personal.js'),
	adminSettings: path.join(__dirname, 'src', 'settings-admin.js'),
}

webpackConfig.optimization = {
	splitChunks: {
		cacheGroups: {
			defaultVendors: false,
		},
	},
}

webpackConfig.module.rules.push({
	resourceQuery: /raw/,
	type: 'asset/source',
})

module.exports = webpackConfig
