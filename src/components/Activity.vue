<!--
	- @copyright 2023 Ferdinand Thiessen <opensource@fthiessen.de>
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
	- along with this program.  If not, see <http://www.gnu.org/licenses/>.
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
