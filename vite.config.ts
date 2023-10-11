import { createAppConfig } from '@nextcloud/vite-config'
import { join } from 'path'
import { viteStaticCopy } from 'vite-plugin-static-copy'

// replaced by vite
declare const __dirname: string

export default createAppConfig({
	app: join(__dirname, 'src', 'app.ts'),
	sidebar: join(__dirname, 'src', 'sidebar.js'),
	personalSettings: join(__dirname, 'src', 'settings-personal.js'),
	adminSettings: join(__dirname, 'src', 'settings-admin.js'),
}, {
	inlineCSS: { relativeCSSInjection: true },
	config: {
		experimental: {
			renderBuiltUrl(filename) {
				return {
					// already contains the "js/" prefix as it is our output file configuration
					runtime: `OC.filePath('activity', '', '${filename}')`,
				}
			},
		},
		plugins: [
			// publicDir does not work because outDir is the root and not js/
			// So we need this plugin to copy the legacy script into the destination folder
			viteStaticCopy({
				targets: [
					{
						src: 'src/legacy_scripts/*',
						dest: 'js',
						preserveTimestamps: true,
					},
				],
			}),
		],
	},
})
