<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<component :is="activityComponent" v-bind="activityOptions" @reload="$emit('reload')" />
</template>

<script setup lang="ts">
import type { IActivitySidebarEntry } from '../models/ActivityAPI'
import { computed } from 'vue'
import ActivityModel from '../models/ActivityModel'
import CommentActivity from './activities/CommentActivity.vue'
import GenericActivity from './activities/GenericActivity.vue'
import PluginActivity from './activities/PluginActivity.vue'

const props = defineProps<{
	/**
	 * The activity to render.
	 */
	activity: ActivityModel | IActivitySidebarEntry

	/**
	 * Whether to show previews
	 */
	showPreviews: boolean
}>()

const activityOptions = computed(() => props)

const activityComponent = computed(() => {
	if (!(props.activity instanceof ActivityModel)) {
		console.warn(props.activity)
		return PluginActivity
	}

	switch (props.activity.type) {
	case 'comments':
		return CommentActivity
	default:
		return GenericActivity
	}
})
</script>
