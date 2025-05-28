<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcContent app-name="activity">
		<router-view name="navigation" />
		<router-view />
	</NcContent>
</template>

<script setup lang="ts">
import NcContent from '@nextcloud/vue/components/NcContent'
</script>
