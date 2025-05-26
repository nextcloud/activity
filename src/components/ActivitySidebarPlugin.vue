<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div ref="attachTarget" />
</template>

<script setup lang="ts">
import type { IActivitySidebarAction } from '../models/ActivityAPI.ts'

import { getCurrentInstance, onBeforeUnmount, onMounted, ref } from 'vue'

const props = defineProps<{
	/** The sidebar plugin */
	plugin: IActivitySidebarAction
	fileInfo: object | null
}>()

const emit = defineEmits<{
	(e: 'reload-activities'): void
}>()

const attachTarget = ref<HTMLDivElement>()

onMounted(() => props.plugin.mount(attachTarget.value as HTMLDivElement, {
	context: getCurrentInstance()?.proxy,
	fileInfo: props.fileInfo,
	reload: () => emit('reload-activities'),
}))
onBeforeUnmount(() => props.plugin.unmount())
</script>
