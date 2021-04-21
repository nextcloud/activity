<!--
  - @copyright 2021 Louis Chemineau <louis@chmn.me>
  -
  - @author Louis Chemineau <louis@chmn.me>
  -
  - @license GNU AGPL version 3 or any later version
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
	<a :href="link">
		<div :id="`opengraph-${id}`" class="opengraph">
			<div v-if="thumbIsDefined" class="opengraph-thumb" :style="`background-image: url('${thumb}')`" />
			<div class="opengraph-name" :class="{'opengraph-with-thumb': thumbIsDefined}">{{ name }}</div>
			<div class="opengraph-description" :class="{'opengraph-with-thumb': thumbIsDefined}">{{ description }}</div>
			<span class="opengraph-website">{{ website }}</span>
		</div>
	</a>
</template>

<script>
export default {
	name: 'OpenGraphRichArgument',
	props: {
		id: {
			type: String,
			required: true,
		},
		name: {
			type: String,
			required: true,
		},
		description: {
			type: String,
			required: true,
		},
		thumb: {
			type: String,
			default: '',
		},
		link: {
			type: String,
			required: true,
		},
		website: {
			type: String,
			required: true,
		},
	},
	computed: {
		thumbIsDefined() {
			return this.thumb !== ''
		},
	},
}
</script>
