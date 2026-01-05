<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div ref="attachTarget" />
</template>

<script setup lang="ts">
import type { INode } from '@nextcloud/files'
import type { IActivitySidebarAction } from '../models/ActivityAPI.ts'

import { getCurrentInstance, onBeforeUnmount, onMounted, ref } from 'vue'

const props = defineProps<{
	/** The sidebar plugin */
	plugin: IActivitySidebarAction
	/**
	 * The current node (file or folder)
	 */
	node: INode
}>()

const emit = defineEmits<{
	(e: 'reload-activities'): void
}>()

const attachTarget = ref<HTMLDivElement>()

onMounted(() => props.plugin.mount(attachTarget.value as HTMLDivElement, {
	node: props.node,
	context: getCurrentInstance()?.proxy ?? undefined,
	reload: () => emit('reload-activities'),
}))
onBeforeUnmount(() => props.plugin.unmount())
</script>
