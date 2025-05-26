<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<span class="rich-text-file">
		<a v-if="!isRoot && parentPathIsRoot" :href="link">{{ name }}</a>
		<a
			v-if="!isRoot && !parentPathIsRoot"
			:title="t('activity', 'in {path}', { path: parentFolder })"
			:aria-label="t('activity', 'in {path}', { path: parentFolder })"
			:href="link">{{ name }}</a>
		<span
			v-if="isRoot"
			:title="t('activity', 'Home')"
			aria-label="t('activity', 'Home')"
			class="icon-home" />
	</span>
</template>

<script>
import { translate as t } from '@nextcloud/l10n'
import { dirname, isSamePath } from '@nextcloud/paths'

export default {
	name: 'FileRichArgument',
	props: {
		name: {
			type: String,
			required: true,
		},

		path: {
			type: String,
			required: true,
		},

		link: {
			type: String,
			required: true,
		},
	},

	computed: {
		/**
		 * Return true if the path is the root folder.
		 */
		isRoot() {
			return isSamePath(this.path, '')
		},

		/**
		 * Return true if the parent path is the root folder.
		 */
		parentPathIsRoot() {
			return isSamePath(this.parentFolder, '/')
		},

		/**
		 * Remove the file name from the path.
		 */
		parentFolder() {
			return dirname(this.name)
		},
	},

	methods: {
		t,
	},
}
</script>

<style lang="scss" scoped>
.rich-text-file {
	.icon-home {
		display: inline-block;
		vertical-align: text-top;
	}
}
</style>
