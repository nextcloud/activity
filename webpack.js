const path = require('path')

const webpackConfig = require('@nextcloud/webpack-vue-config')

webpackConfig.entry = {
	sidebar: path.join(__dirname, 'src', 'sidebar.js'),
	dashboard: path.join(__dirname, 'src', 'dashboard.js'),
	userSettings: path.join(__dirname, 'src', 'userSettings.js'),
	adminSettings: path.join(__dirname, 'src', 'adminSettings.js'),
}

module.exports = webpackConfig
