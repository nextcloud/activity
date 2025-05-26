<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<strong>{{ name }}</strong>
</template>

<script>
export default {
	name: 'SystemTagRichArgument',
	props: {
		name: {
			type: String,
			required: true,
		},
	},
}
</script>

<style scoped>
strong {
	padding: 2px 5px;
	border-radius: 3px;
	background-color: var(--color-background-darker);
	font-weight: normal;
}
</style>
