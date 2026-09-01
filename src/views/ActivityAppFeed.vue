<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<NcAppContent class="activity-app">
		<!-- Kept for document semantics / screen readers, but visually hidden -->
		<h1 class="activity-app__heading hidden-visually">
			{{ headingTitle }}
		</h1>
		<div class="activity-app__filter">
			<ActivityFilterBar
				v-model:search="searchTerm"
				v-model:from="dateFrom"
				v-model:to="dateTo"
				v-model:actor="actorFilter"
				:actorOptions="actorOptions" />
		</div>
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
			v-else-if="allActivities.length === 0 && hasActiveFilters"
			class="activity-app__empty-content"
			:name="t('activity', 'No matching activities')"
			:description="t('activity', 'No activity matches the current filters')">
			<template #icon>
				<NcIconSvgWrapper :svg="appIconSVG" :size="36" />
			</template>
			<template #action>
				<NcButton variant="primary" @click="clearFilters">
					{{ t('activity', 'Clear filters') }}
				</NcButton>
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
			<div class="activity-app__content">
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
					class="activity-app__end-of-feed">
					{{ t('activity', 'No more activities.') }}
				</div>
			</div>
		</div>
	</NcAppContent>
</template>

<script setup lang="ts">
import type { IRawActivity } from '../models/types.ts'

import ncAxios from '@nextcloud/axios'
import { showError } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { generateOcsUrl } from '@nextcloud/router'
import { useDebounceFn, useDocumentVisibility, useInfiniteScroll } from '@vueuse/core'
import axios from 'axios'
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import NcLoadingIcon from '@nextcloud/vue/components/NcLoadingIcon'
import ActivityFilterBar from '../components/ActivityFilterBar.vue'
import ActivityGroup from '../components/ActivityGroup.vue'
import appIconSVG from '../../img/activity-dark.svg?raw'
import ActivityModel from '../models/ActivityModel.ts'
import { fetchStreamActors } from '../utils/actors.ts'
import {
	endOfDayTimestamp,
	formatDateParameter,
	normalizeSearchTerm,
	parseDateParameter,
	startOfDayTimestamp,
} from '../utils/dateRange.ts'
import logger from '../utils/logger.ts'

interface INavigationEntry {
	id: string
	icon?: string
	name: string
	url: string
}

const props = withDefaults(defineProps<{
	/**
	 * The currently active activities filter
	 */
	filter?: string
}>(), {
	// default to 'all'
	filter: 'all',
})

const navigationList = loadState<INavigationEntry[]>(appName, 'navigationList')

const route = useRoute()
const router = useRouter()

/**
 * Active file name search term. Restored from the URL so a filtered view can
 * be bookmarked and shared.
 */
const searchTerm = ref(typeof route.query.search === 'string' ? normalizeSearchTerm(route.query.search) : '')

/**
 * Start of the active date range, or null when unbounded
 */
const dateFrom = ref<Date | null>(parseDateParameter(route.query.from))

/**
 * End of the active date range, or null when unbounded
 */
const dateTo = ref<Date | null>(parseDateParameter(route.query.to))

/**
 * Account name the stream is restricted to, or an empty string
 */
const actorFilter = ref(typeof route.query.actor === 'string' ? route.query.actor.trim() : '')

/**
 * Accounts that can be filtered by, keyed by account name.
 *
 * Seeded from the stream's account list, so every account the stream contains
 * can be picked and not just the ones behind the activities currently loaded,
 * then topped up from the activities as they come in — that covers accounts
 * beyond the server's cap, and keeps the dropdown usable if the list request
 * failed.
 *
 * Accumulated rather than derived from the current page: once an account has
 * been filtered to, the stream only contains that account, and a derived list
 * would collapse to a single entry with no way back to the others.
 */
const knownActors = ref(new Map<string, string>())

const actorOptions = computed(() => [...knownActors.value.entries()]
	.map(([id, displayName]) => ({ id, displayName, user: id }))
	.sort((a, b) => a.displayName.localeCompare(b.displayName)))

const hasActiveFilters = computed(() => searchTerm.value !== ''
	|| dateFrom.value !== null
	|| dateTo.value !== null
	|| actorFilter.value !== '')

/**
 * Record the accounts behind a batch of activities so they can be filtered on.
 *
 * @param activities - The newly loaded activities
 */
function rememberActors(activities: ActivityModel[]) {
	for (const activity of activities) {
		if (activity.user !== '' && !knownActors.value.has(activity.user)) {
			knownActors.value.set(activity.user, activity.authorDisplayName)
		}
	}
}

/**
 * AbortController for the in-flight account list request, so a filter change
 * cannot apply the previous stream's accounts to the new one.
 */
let actorsController = new AbortController()

/**
 * Load the accounts behind the whole stream, not just the loaded page.
 *
 * Failures are logged and otherwise ignored: the dropdown still works off the
 * accounts collected from the activities themselves, so a missing list is a
 * shorter set of options rather than a broken filter.
 */
async function loadActors() {
	// Its own controller rather than the stream's: narrowing the stream aborts
	// the in-flight activity requests, and the account list is deliberately
	// unaffected by that narrowing, so it must not be cancelled along with them
	actorsController.abort()
	actorsController = new AbortController()
	const { signal } = actorsController
	try {
		const actors = await fetchStreamActors(props.filter, signal)
		if (signal.aborted) {
			return
		}
		for (const actor of actors) {
			knownActors.value.set(actor.id, actor.displayName)
		}
	} catch (error) {
		if (axios.isCancel(error)) {
			return
		}
		logger.error('Could not load the accounts to filter by', { error })
	}
}

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
 * Build an API URL for the current filter, including the active search and
 * date range.
 *
 * The query string is assembled with URLSearchParams so search terms
 * containing spaces, slashes or `&` are encoded correctly.
 *
 * @param extra - Request specific parameters such as the pagination cursor
 */
function buildRequestUrl(extra: Record<string, string>): string {
	const parameters = new URLSearchParams({ format: 'json', previews: 'true', ...extra })
	if (searchTerm.value !== '') {
		parameters.set('search', searchTerm.value)
	}
	const from = startOfDayTimestamp(dateFrom.value)
	if (from > 0) {
		parameters.set('from', String(from))
	}
	const to = endOfDayTimestamp(dateTo.value)
	if (to > 0) {
		parameters.set('to', String(to))
	}
	if (actorFilter.value !== '') {
		parameters.set('actor', actorFilter.value)
	}
	const base = generateOcsUrl('apps/activity/api/v2/activity/{filter}', { filter: props.filter })
	return `${base}?${parameters.toString()}`
}

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
		const response = await ncAxios.get(buildRequestUrl({ since }), { signal })
		if (signal.aborted) {
			return
		}
		const newActivities = response.data.ocs.data.map((raw: IRawActivity) => new ActivityModel(raw))
		rememberActors(newActivities)
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
		const response = await ncAxios.get(buildRequestUrl({ since, sort: 'asc' }), { signal })
		if (!signal.aborted && response.data.ocs.data.length > 0) {
			const newActivities: ActivityModel[] = response.data.ocs.data.map((raw: IRawActivity) => new ActivityModel(raw))
			rememberActors(newActivities)
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

/**
 *
 */
function startPolling() {
	stopPolling()
	// Use a sentinel value so the self-scheduling logic in pollNewActivities
	// knows polling is active even before the first tick fires
	pollTimer = setTimeout(pollNewActivities, POLL_INTERVAL)
}

/**
 *
 */
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
	loadActors()
	startPolling()
})

onUnmounted(() => {
	stopPolling()
	requestController.abort()
	actorsController.abort()
})

watch(visibility, (value) => {
	if (value === 'hidden') {
		stopPolling()
	} else {
		startPolling()
	}
})

/**
 * Discard everything loaded so far and start over.
 *
 * Aborting the in-flight requests first is what keeps a slow response for the
 * previous filter from being appended to the new one's results.
 */
function resetAndReload() {
	requestController.abort()
	requestController = new AbortController()
	allActivities.value = []
	newActivitiesAvailable.value = false
	lastActivityLoaded.value = undefined
	newestActivityId.value = undefined
	hasMoreActivites.value = true
	loadActivities()
}

/**
 * Reload activities when filter changed
 */
watch(props, () => {
	// A different stream can involve entirely different accounts
	knownActors.value.clear()
	resetAndReload()
	loadActors()
})

/**
 * Reload when the search term or date range changed, and mirror the criteria
 * into the URL.
 *
 * `replace` rather than `push` so refining a search does not fill the back
 * button with intermediate states, while the current view stays linkable.
 */
watch([searchTerm, dateFrom, dateTo, actorFilter], () => {
	const query: Record<string, string> = {}
	if (searchTerm.value !== '') {
		query.search = searchTerm.value
	}
	if (actorFilter.value !== '') {
		query.actor = actorFilter.value
	}
	const from = formatDateParameter(dateFrom.value)
	if (from !== '') {
		query.from = from
	}
	const to = formatDateParameter(dateTo.value)
	if (to !== '') {
		query.to = to
	}
	// A redundant navigation is not an error worth surfacing
	router.replace({ query }).catch(() => {})
	resetAndReload()
})

/**
 * Drop every active search and date restriction
 */
function clearFilters() {
	searchTerm.value = ''
	dateFrom.value = null
	dateTo.value = null
	actorFilter.value = ''
}
</script>

<style scoped lang="scss">
.activity-app {
	// Max width of the readable column, also read by the heading indent in ActivityGroup.vue
	--activity-feed-max-width: 924px;
	// How far content has to be indented to clear the app-navigation toggle,
	// which is absolutely positioned at the inline start of the app content.
	// Only the part the centring gutter ((100cqi - column) / 2) doesn't already
	// cover, clamped to 0 so wide layouts stay flush with the entries.
	// Consumed by the filter bar below and by the sticky date headings in
	// ActivityGroup.vue, so the two always line up.
	--activity-feed-nav-indent: max(
		0px,
		var(--app-navigation-padding) + var(--default-clickable-area)
			- var(--default-grid-baseline)
			- max(0px, (100cqi - var(--activity-feed-max-width)) / 2)
	);
	display: flex;
	flex-direction: column;
	overflow: hidden;
	// Query container so the date headings track the content-area width (shrunk by the
	// open app navigation), not the raw viewport
	container: activity-feed / inline-size;

	&__filter {
		// Align the controls with the readable column below them. Deliberately
		// outside the scroll container so it stays put while the feed scrolls
		// and does not compete with the sticky date headings.
		flex: 0 0 auto;
		width: min(100%, var(--activity-feed-max-width));
		max-width: var(--activity-feed-max-width);
		margin: 0 auto;
		padding-inline: 12px;
		// Clear the app-navigation toggle so the first control starts where the
		// date headings do instead of butting up against the toggle
		padding-inline-start: calc(12px + var(--activity-feed-nav-indent));
	}

	&__empty-content {
		// Fill what the filter bar leaves rather than the full height of the
		// app content, which would overflow the clipped parent
		flex: 1 1 auto;
		min-height: 0;
	}

	&__loading-indicator {
		color: var(--color-text-maxcontrast);
		justify-self: center;
		margin-block: 30px 6px;
		text-align: center;
	}

	&__end-of-feed {
		color: var(--color-text-maxcontrast);
		text-align: center;
		// Large bottom margin so the message isn't stuck to the viewport bottom
		margin-block: 30px 30vh;
	}

	&__container {
		// Scroll container, so the scrollbar sits at the edge of app-content
		// rather than beside the narrower content column.
		// min-height: 0 lets it shrink below its content so the filter bar
		// above keeps its space instead of being pushed out.
		flex: 1 1 auto;
		min-height: 0;
		overflow-y: scroll;
	}

	&__content {
		// Clamp the readable column and centre it within the full-width scroller
		display: flex;
		flex-direction: column;

		width: min(100%, var(--activity-feed-max-width));
		max-width: var(--activity-feed-max-width);
		margin: 0 auto;
		padding-inline: 12px;
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
}
</style>
