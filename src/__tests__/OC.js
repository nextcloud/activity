/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

export default {
	L10N: {
		translate() {
			return ''
		},
	},

	getLanguage() {
		return 'en-GB'
	},

	getLocale() {
		return 'en_GB'
	},

	isUserAdmin() {
		return true
	},

	Util: {
		naturalSortCompare(a, b) {
			return 0
		},
	},

	config: {
		modRewriteWorking: true,
	},
}
