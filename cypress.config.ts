import { configureNextcloud, runOcc, startNextcloud, stopNextcloud, waitOnNextcloud } from '@nextcloud/e2e-test-server'
import { defineConfig } from 'cypress'
import vitePreprocessor from 'cypress-vite'

export default defineConfig({
	projectId: '5bsgwk',

	// 16/9 screen ratio
	viewportWidth: 1280,
	viewportHeight: 720,

	requestTimeout: 20000,

	retries: {
		runMode: 0,
		// do not retry in `cypress open`
		openMode: 0,
	},

	// Needed to trigger `after:run` events with cypress open
	experimentalInteractiveRunEvents: true,

	// disabled if running in CI but enabled in debug mode
	video: !process.env.CI || !!process.env.RUNNER_DEBUG,

	// faster video processing
	videoCompression: false,

	// Visual regression testing
	env: {
		visualRegressionType: 'regression',
		visualRegressionFailSilently: false,
	},

	screenshotsFolder: 'cypress/snapshots/actual',
	trashAssetsBeforeRuns: true,

	e2e: {
		// Exclude the documentation screenshot spec from regular CI runs.
		// It is run explicitly by the screenshots.yml workflow_dispatch workflow.
		excludeSpecPattern: '**/screenshots.cy.ts',

		// We've imported your old cypress plugins here.
		// You may want to clean this up later by importing these.
		async setupNodeEvents(on, config) {
			// Fix browserslist extend https://github.com/cypress-io/cypress/issues/2983#issuecomment-570616682
			on('file:preprocessor', vitePreprocessor({ configFile: false }))

			// This allows to store global data (e.g. the name of a snapshot)
			// because Cypress.env() and other options are local to the current spec file.
			const data = {}
			on('task', {
				setVariable({ key, value }) {
					data[key] = value
					return null
				},
				getVariable({ key }) {
					return data[key] ?? null
				},
			})

			// Disable spell checking to prevent rendering differences
			on('before:browser:launch', (browser, launchOptions) => {
				if (browser.family === 'chromium' && browser.name !== 'electron') {
					launchOptions.preferences.default['browser.enable_spellchecking'] = false
					return launchOptions
				}

				if (browser.family === 'firefox') {
					launchOptions.preferences['layout.spellcheckDefault'] = 0
					return launchOptions
				}

				if (browser.name === 'electron') {
					launchOptions.preferences.spellcheck = false
					return launchOptions
				}
			})

			// Remove container after run
			on('after:run', () => {
				if (!process.env.CI) {
					stopNextcloud()
				}
			})

			// Before the browser launches
			// starting Nextcloud testing container
			const ip = await startNextcloud(process.env.BRANCH || 'master', undefined, { exposePort: 8080 })
			// Setting container's IP as base Url
			config.baseUrl = `http://${ip}/index.php`
			await waitOnNextcloud(ip)
			await configureNextcloud(['viewer', 'activity'])
			// NC 34+ requires Chrome 142+ but Cypress uses Electron 118 — disable the browser check
			await runOcc(['config:system:set', 'no_unsupported_browser_warning', '--value', 'true', '--type', 'boolean'])
			return config
		},
	},

	component: {
		devServer: {
			framework: 'vue',
			bundler: 'vite',
		},
	},
})
