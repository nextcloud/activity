<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<li ref="activityMount" />
</template>

<script setup lang="ts">
import type { IActivitySidebarEntry } from '../../models/ActivityAPI.ts'

import { getCurrentInstance, onMounted, onUnmounted, ref } from 'vue'

const props = defineProps<{
	activity: IActivitySidebarEntry
}>()

const emit = defineEmits<{
	(e: 'reload'): void
}>()

const activityMount = ref<HTMLLIElement>()

onMounted(() => props.activity.mount(activityMount.value as HTMLLIElement, { context: getCurrentInstance()?.proxy, reload: () => emit('reload') }))
onUnmounted(() => props.activity.unmount())
</script>
