import type { UserConfig } from 'vitest/node'

import { createAppConfig } from '@nextcloud/vite-config'
import { join } from 'path'

// replaced by vite
declare const __dirname: string

export default createAppConfig({
	api: join(__dirname, 'src', 'api.ts'),
	app: join(__dirname, 'src', 'app.ts'),
	sidebar: join(__dirname, 'src', 'sidebar.ts'),
	personalSettings: join(__dirname, 'src', 'settings-personal.ts'),
	adminSettings: join(__dirname, 'src', 'settings-admin.ts'),
}, {
	inlineCSS: { relativeCSSInjection: true },
	extractLicenseInformation: true,
	config: {
		// Setup for vitest unit tests
		test: {
			environment: 'happy-dom',
			coverage: {
				all: true,
				clean: true,
				extension: ['.js', '.ts', '.vue'],
				provider: 'v8',
			},
			root: 'src/',
			deps: {
				moduleDirectories: ['node_modules', '../node_modules'],
			},
			cache: {
				dir: '../node_modules/.vitest',
			},
			server: {
				deps: {
					inline: [/@nextcloud\/vue/, /@mdi\/svg/],
				},
			},
		} as UserConfig,
	},
})
