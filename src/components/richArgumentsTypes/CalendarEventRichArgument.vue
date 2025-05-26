<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<a
		:href="link"
		:title="title">
		<strong>{{ name }}</strong>
	</a>
</template>

<script>

export default {
	name: 'CalendarEventRichArgument',
	props: {
		name: {
			type: String,
			required: true,
		},

		title: {
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
