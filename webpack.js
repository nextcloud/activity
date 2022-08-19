const path = require('path')

const webpackConfig = require('@nextcloud/webpack-vue-config')

webpackConfig.entry = {
	sidebar: path.join(__dirname, 'src', 'sidebar.js'),
	dashboard: path.join(__dirname, 'src', 'dashboard.js'),
	personalSettings: path.join(__dirname, 'src', 'settings-personal.js'),
	adminSettings: path.join(__dirname, 'src', 'settings-admin.js'),
}

module.exports = webpackConfig
