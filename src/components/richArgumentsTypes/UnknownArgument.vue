<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<strong>{{ name }}</strong>
</template>

<script>
export default {
	name: 'UnknownArgument',
	props: {
		name: {
			type: String,
			required: true,
		},
	},
}
</script>
