<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<NcAppContent class="activity-app">
		<h1 class="activity-app__heading">
			{{ headingTitle }}
		</h1>
		<NcEmptyContent
			v-if="hasMoreActivites && allActivities.length === 0"
			class="activity-app__empty-content"
			:name="t('activity', 'Loading activities')"
			:description="t('activity', 'This stream will show events like additions, changes & shares')">
			<template #icon>
				<NcLoadingIcon :size="36" />
			</template>
		</NcEmptyContent>
		<NcEmptyContent
			v-else-if="allActivities.length === 0"
			class="activity-app__empty-content"
			:name="t('activity', 'No activity yet')"
			:description="t('activity', 'This stream will show events like additions, changes & shares')">
			<template #icon>
				<NcIconSvgWrapper :svg="appIconSVG" :size="36" />
			</template>
		</NcEmptyContent>
		<div ref="container" class="activity-app__container" @scroll="onScroll">
			<NcButton
				v-if="newActivitiesAvailable"
				class="activity-app__new-activities-indicator"
				type="button"
				@click="scrollToTop">
				{{ t('activity', 'New activities') }}
			</NcButton>
			<ActivityGroup v-for="activities, date of groupedActivities" :key="date" :activities="activities" />
			<!-- Only show if not showing the inital empty content for loading -->
			<NcLoadingIcon
				v-if="hasMoreActivites && allActivities.length > 0"
				:name="t('activity', 'Loading more activities')"
				:size="64"
				class="activity-app__loading-indicator" />
			<div
				v-else-if="!hasMoreActivites && allActivities.length > 0"
				class="activity-app__loading-indicator">
				{{ t('activity', 'No more activities.') }}
			</div>
		</div>
	</NcAppContent>
</template>

<script setup lang="ts">
import ncAxios from '@nextcloud/axios'
import { showError } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { generateOcsUrl } from '@nextcloud/router'
import { useDocumentVisibility, useInfiniteScroll, useDebounceFn } from '@vueuse/core'
import axios from 'axios'
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcLoadingIcon from '@nextcloud/vue/components/NcLoadingIcon'
import ActivityGroup from '../components/ActivityGroup.vue'
import appIconSVG from '../../img/activity-dark.svg?raw'
import ActivityModel from '../models/ActivityModel.ts'
import type { IRawActivity } from '../models/types.ts'
import logger from '../utils/logger.ts'

interface INavigationEntry {
	id: string
	icon?: string
	name: string
	url: string
}

const navigationList = loadState<INavigationEntry[]>(appName, 'navigationList')

const route = useRoute()

const props = withDefaults(defineProps<{
	/**
	 * The currently active activities filter
	 */
	filter?: string
}>(), {
	// default to 'all'
	filter: 'all',
})

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
 * Last loaded activity (oldest) for backward pagination
 * This is set by the backend in the API result as a header value for pagination
 */
const lastActivityLoaded = ref<string>()

/**
 * First loaded activity ID (newest) for polling new activities
 */
const newestActivityId = ref<number>()

/**
 * Whether new activities have been prepended while the user was scrolled
 * away from the top — used to show the "New activities" navigation button
 */
const newActivitiesAvailable = ref(false)

/**
 * Polling interval in milliseconds
 */
const POLL_INTERVAL = 30000

/**
 * Polling timer reference (setTimeout-based for self-scheduling)
 */
let pollTimer: ReturnType<typeof setTimeout> | undefined

/**
 * AbortController for in-flight load and poll requests.
 * Replaced on filter change and aborted on unmount so stale responses
 * are never applied to the wrong filter's state.
 */
let requestController = new AbortController()

/**
 * Document visibility for pausing polling when tab is hidden
 */
const visibility = useDocumentVisibility()

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

const headingTitle = computed(() => {
	return navigationList.find((navigationEl) => navigationEl.id === route.params.filter).name
})

/**
 * Load activities for current filter or load more if already loaded
 */
async function loadActivities() {
	// Skip if already loading
	if (loading.value) {
		return
	}

	const { signal } = requestController
	try {
		const since = lastActivityLoaded.value ?? '0'
		loading.value = true
		const response = await ncAxios.get(generateOcsUrl('apps/activity/api/v2/activity/{filter}?format=json&previews=true&since={since}', { filter: props.filter, since }), { signal })
		if (signal.aborted) {
			return
		}
		const newActivities = response.data.ocs.data.map((raw: IRawActivity) => new ActivityModel(raw))
		allActivities.value.push(...newActivities)
		lastActivityLoaded.value = response.headers['x-activity-last-given']
		hasMoreActivites.value = true

		// Track the newest activity ID for polling
		if (newestActivityId.value === undefined && newActivities.length > 0) {
			newestActivityId.value = newActivities[0].id
		}

		nextTick(async () => {
			if (container.value && container.value.clientHeight === container.value.scrollHeight) {
				// Container is non-scrollable, thus useInfiniteScroll isn't triggered
				// Do it manually to ensure there are no activities to fetch anymore
				await loadActivities()
			}
		})
	} catch (error) {
		if (axios.isCancel(error)) {
			return
		}
		// Skip if no activities are available
		if (axios.isAxiosError(error) && error.response?.status === 304) {
			hasMoreActivites.value = false
			return
		}

		logger.error(error as Error)
		showError(t('activity', 'Could not load activities'))
	} finally {
		// Don't clear the loading flag if this request was superseded — the
		// replacement loadActivities() call has already set it to true.
		if (!signal.aborted) {
			loading.value = false
		}
	}
}

/**
 * Poll for new activities and either prepend them directly (when near top)
 * or queue them so the user can load them without disrupting their scroll position
 */
async function pollNewActivities() {
	const { signal } = requestController
	try {
		const since = String(newestActivityId.value ?? 0)
		const response = await ncAxios.get(generateOcsUrl('apps/activity/api/v2/activity/{filter}?format=json&previews=true&since={since}&sort=asc', { filter: props.filter, since }), { signal })
		if (!signal.aborted && response.data.ocs.data.length > 0) {
			const newActivities: ActivityModel[] = response.data.ocs.data.map((raw: IRawActivity) => new ActivityModel(raw))
			// Sort newest first for prepending
			newActivities.sort((a: ActivityModel, b: ActivityModel) => b.id - a.id)
			newestActivityId.value = newActivities[0]!.id
			allActivities.value.unshift(...newActivities)

			// Show the navigation button only when the user is not already at the top
			// (browser scroll anchoring keeps their reading position stable on prepend)
			const isNearTop = !container.value || container.value.scrollTop < 50
			if (!isNearTop) {
				newActivitiesAvailable.value = true
			}
		}
	} catch (error) {
		// Silently ignore cancellations and polling errors (304 = no new activities)
		if (!axios.isCancel(error) && (!axios.isAxiosError(error) || error.response?.status !== 304)) {
			logger.error(error as Error)
		}
	}

	// Self-schedule only if polling wasn't stopped while the request was in flight
	if (pollTimer !== undefined) {
		pollTimer = setTimeout(pollNewActivities, POLL_INTERVAL)
	}
}

/**
 * Scroll to the top of the container to reveal the newly prepended activities
 */
function scrollToTop() {
	newActivitiesAvailable.value = false
	container.value?.scrollTo({ top: 0, behavior: 'smooth' })
}

/**
 * Hide the "New activities" button once the user scrolls back near the top
 */
const onScroll = useDebounceFn(() => {
	if (container.value && container.value.scrollTop < 50) {
		newActivitiesAvailable.value = false
	}
}, 100)

function startPolling() {
	stopPolling()
	// Use a sentinel value so the self-scheduling logic in pollNewActivities
	// knows polling is active even before the first tick fires
	pollTimer = setTimeout(pollNewActivities, POLL_INTERVAL)
}

function stopPolling() {
	if (pollTimer !== undefined) {
		clearTimeout(pollTimer)
		pollTimer = undefined
	}
}

/**
 * Load activities when mounted and start polling
 */
onMounted(() => {
	loadActivities()
	startPolling()
})

onUnmounted(() => {
	stopPolling()
	requestController.abort()
})

watch(visibility, (value) => {
	if (value === 'hidden') {
		stopPolling()
	} else {
		startPolling()
	}
})

/**
 * Reload activities when filter changed
 */
watch(props, () => {
	requestController.abort()
	requestController = new AbortController()
	allActivities.value = []
	newActivitiesAvailable.value = false
	lastActivityLoaded.value = undefined
	newestActivityId.value = undefined
	hasMoreActivites.value = true
	loadActivities()
})
</script>

<style scoped lang="scss">
.activity-app {
	display: flex;
	flex-direction: column;
	overflow: hidden;

	&__empty-content {
		height: 100%;
	}

	&__loading-indicator {
		color: var(--color-text-maxcontrast);
		justify-self: center;
		margin-block: 30px 6px;
		text-align: center;
	}

	&__container {
		display: flex;
		flex-direction: column;

		height: 100%;
		width: min(100%, 924px);
		max-width: 924px;
		margin: 0 auto;
		padding-inline: 12px;
		overflow-y: scroll;
	}

	&__new-activities-indicator {
		position: sticky;
		top: 8px;
		align-self: center;
		z-index: 10;
		padding: 6px 16px;
		border-radius: var(--border-radius-pill);
		border: none;
		background-color: var(--color-primary-element);
		color: var(--color-primary-element-text);
		font-weight: bold;
		cursor: pointer;
		box-shadow: 0 2px 8px var(--color-box-shadow);

		&:hover {
			background-color: var(--color-primary-element-hover);
		}
	}

	&__heading {
		font-weight: bold;
		font-size: 20px;
		line-height: 44px; // to align height with the app navigation toggle
		// Align with app navigation toggle
		margin-top: 1px;
		margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) var(--app-navigation-padding, 8px);
	}
}
</style>
