<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<a :href="link">{{ name }}</a>
</template>

<script>
export default {
	name: 'UnknownLinkArgument',
	props: {
		name: {
			type: String,
			required: true,
		},

		link: {
			type: String,
			required: true,
		},
	},
}
</script>
