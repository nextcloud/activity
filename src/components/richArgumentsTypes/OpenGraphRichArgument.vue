<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<a :href="link">
		<div :id="`opengraph-${id}`" class="opengraph">
			<div v-if="thumbIsDefined" class="opengraph-thumb" :style="`background-image: url('${thumb}')`" />
			<div class="opengraph-name" :class="{ 'opengraph-with-thumb': thumbIsDefined }">{{ name }}</div>
			<div class="opengraph-description" :class="{ 'opengraph-with-thumb': thumbIsDefined }">{{ description }}</div>
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
