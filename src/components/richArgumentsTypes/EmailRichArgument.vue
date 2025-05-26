<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<a :href="`mailto:${id}`">{{ name }}</a>
</template>

<script>
export default {
	name: 'EmailRichArgument',
	props: {
		id: {
			type: String,
			required: true,
		},

		name: {
			type: String,
			required: true,
		},
	},
}
</script>
