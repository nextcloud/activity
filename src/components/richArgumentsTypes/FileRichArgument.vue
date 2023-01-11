<!--
  - @copyright 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  -
  - @license AGPL-3.0-or-later
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -->

<template>
	<span class="rich-text-file">
		<a v-if="!isRoot && parentPathIsRoot" :href="link">{{ name }}</a>
		<a v-if="!isRoot && !parentPathIsRoot" :title="t('activity', 'in {path}', {path: parentFolder})" :aria-label="t('activity', 'in {path}', {path: parentFolder})" :href="link">{{ name }}</a>
		<span v-if="isRoot" :title="t('activity', 'Home')" aria-label="t('activity', 'Home')" class="icon-home" />
	</span>
</template>

<script>
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
		 *
		 * @return {boolean}
		 */
		isRoot() {
			// eslint-disable-next-line no-console
			return isSamePath(this.path, '')
		},
		/**
		 * Return true if the parent path is the root folder.
		 *
		 * @return {boolean}
		 */
		parentPathIsRoot() {
			return isSamePath(this.parentFolder, '/')
		},
		/**
		 * Remove the file name from the path.
		 *
		 * @return {string}
		 */
		parentFolder() {
			return dirname(this.name)
		},
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
