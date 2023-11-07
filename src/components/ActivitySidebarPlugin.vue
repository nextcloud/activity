<!--
  - @copyright Copyright (c) 2023 Ferdinand Thiessen <opensource@fthiessen.de>
  -
  - @author Ferdinand Thiessen <opensource@fthiessen.de>
  -
  - @license AGPL-3.0-or-later
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<div ref="attachTarget" />
</template>

<script setup lang="ts">
import type { IActivitySidebarAction } from '../utils/ActivityAPI'
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
