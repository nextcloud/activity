<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<component :is="activityComponent" v-bind="activityOptions" @reload="$emit('reload')" />
</template>

<script setup lang="ts">
import type { IActivitySidebarEntry } from '../models/ActivityAPI.ts'

import { computed } from 'vue'
import CommentActivity from './activities/CommentActivity.vue'
import GenericActivity from './activities/GenericActivity.vue'
import PluginActivity from './activities/PluginActivity.vue'
import ActivityModel from '../models/ActivityModel.ts'
import logger from '../utils/logger.ts'

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

defineEmits(['reload'])

const activityOptions = computed(() => props)

const activityComponent = computed(() => {
	if (!(props.activity instanceof ActivityModel)) {
		logger.warn('Activity is not an instance of ActivityModel', { activity: props.activity })
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
