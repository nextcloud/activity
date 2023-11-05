<template>
	<NcAppContent :class="$style['app-content']">
		<h2 :class="$style.heading">
			{{ t('activity', 'Your cloud actvities') }}
		</h2>
		<NcEmptyContent v-if="loading && allActivities.length === 0"
			:class="$style['empty-content']"
			:name="t('activity', 'Loading activities')"
			:description="t('activity', 'This stream will show events like additions, changes & shares')">
			<template #icon>
				<NcLoadingIcon :size="36" />
			</template>
		</NcEmptyContent>
		<NcEmptyContent v-else-if="allActivities.length === 0"
			:class="$style['empty-content']"
			:name="t('activity', 'No activity yet')"
			:description="t('activity', 'This stream will show events like additions, changes & shares')">
			<template #icon>
				<NcIconSvgWrapper :svg="appIconSVG" :size="36" />
			</template>
		</NcEmptyContent>
		<div ref="container" :class="$style.container">
			<ActivityGroup v-for="activities, date of groupedActivities" :key="date" :activities="activities" />
		</div>
	</NcAppContent>
</template>

<script setup lang="ts">
// eslint-disable-next-line n/no-extraneous-import
import axios from 'axios'
import ncAxios from '@nextcloud/axios'
import moment from '@nextcloud/moment'
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcIconSvgWrapper from '@nextcloud/vue/dist/Components/NcIconSvgWrapper.js'
import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'
import ActivityModel from '../models/ActivityModel'
import ActivityGroup from '../components/ActivityGroup.vue'

import appIconSVG from '../../img/activity-dark.svg?raw'
import logger from '../logger'

import { showError } from '@nextcloud/dialogs'
import { translate as t } from '@nextcloud/l10n'
import { generateOcsUrl } from '@nextcloud/router'
import { useInfiniteScroll } from '@vueuse/core'
import { computed, onMounted, ref, watch } from 'vue'

const props = defineProps<{
	/**
	 * The currently active activities filter
	 */
	filter: string
}>()

/**
 * Whether activities are currently being loaded
 */
const loading = ref(false)

/**
 * Whether more activites can be loaded
 */
const hasMoreActivites = ref(true)

/**
 * All loaded actvities
 */
const allActivities = ref<ActivityModel[]>([])

/**
 * Last loaded activity
 * This is set by the backend in the API result as a header value for pagination
 */
const lastActivityLoaded = ref<string>()

/**
 * Container element for the activites
 */
const container = ref<HTMLDivElement>()

/**
 * Use infinite scroll for the container and load activities when reaching the bottom
 */
useInfiniteScroll(container, async () => {
	if (hasMoreActivites.value) {
		await loadActivities()
	}
}, {
	distance: 100,
})

/**
 * Activities grouped by date
 */
const groupedActivities = computed(() => {
	const groups = {} as Record<string, ActivityModel[]>
	for (const activity of allActivities.value) {
		const date = moment(activity.datetime).format('LL')
		if (groups[date] === undefined) {
			groups[date] = [activity]
		} else {
			groups[date].push(activity)
		}
	}
	return groups
})

/**
 * Load activites for current filter or load more if already loaded
 */
async function loadActivities() {
	// Skip if already loading
	if (loading.value) {
		return
	}

	try {
		const since = lastActivityLoaded.value ?? '0'
		loading.value = true
		const response = await ncAxios.get(
			generateOcsUrl('apps/activity/api/v2/activity/{filter}?limit=3&format=json&since={since}', { filter: props.filter, since }),
		)
		allActivities.value.push(...response.data.ocs.data.map((raw) => new ActivityModel(raw)))
		lastActivityLoaded.value = response.headers['x-activity-last-given']
		hasMoreActivites.value = true
	} catch (error) {
		// Skip if no activites are available
		if (axios.isAxiosError(error) && error.response?.status === 304) {
			hasMoreActivites.value = false
			return
		}

		logger.error(error as Error)
		showError(t('activity', 'Could not load activites'))
	} finally {
		loading.value = false
	}
}

/**
 * Load activites when mounted
 */
onMounted(() => {
	loadActivities()
})

/**
 * Reload activities when filter changed
 */
watch(props, () => {
	allActivities.value = []
	lastActivityLoaded.value = undefined
	loadActivities()
})
</script>

<style module lang="scss">
.app-content {
	display: flex;
	flex-direction: column;
	overflow: hidden;
}

.empty-content {
	height: 100%;
}

.container {
	display: flex;
	flex-direction: column;

	height: 100%;
	width: min(100%, 924px);
	max-width: 924px;
	margin: 0 auto;
	padding-inline: 12px;
	overflow-y: scroll;
}

.heading {
	line-height: 44px;
	// Align with app navigation toggle
	margin: var(--app-navigation-padding) 0 0 calc(2 * var(--app-navigation-padding) + 44px);
}
</style>
