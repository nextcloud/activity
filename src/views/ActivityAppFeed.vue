<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<NcAppContent class="activity-app">
		<h1 class="activity-app__heading">
			{{ headingTitle }}
		</h1>
		<div v-if="savedViews.length > 0" class="activity-app__saved-views">
			<span class="activity-app__saved-views-label">{{ t('activity', 'Saved views') }}</span>
			<button
				v-for="view in savedViews"
				:key="view.id"
				type="button"
				class="activity-app__saved-view-chip"
				:title="describeSavedView(view)"
				@click="applySavedView(view)">
				{{ view.name }}
				<span
					class="activity-app__saved-view-remove"
					role="button"
					:aria-label="t('activity', 'Remove saved view')"
					:title="t('activity', 'Remove saved view')"
					@click.stop="removeSavedView(view.id)">×</span>
			</button>
		</div>
		<form
			class="activity-app__filters"
			role="search"
			:aria-label="t('activity', 'Filter activities')"
			@submit.prevent>
			<NcTextField
				v-model="filterText"
				class="activity-app__filters-search"
				:label="t('activity', 'Search')"
				:placeholder="t('activity', 'Search subject or message…')"
				trailing-button-icon="close"
				:show-trailing-button="filterText.length > 0"
				:trailing-button-label="t('activity', 'Clear search')"
				@trailing-button-click="filterText = ''" />
			<NcTextField
				v-model="filterPerson"
				class="activity-app__filters-person"
				:label="t('activity', 'Person')"
				:placeholder="t('activity', 'User ID')"
				trailing-button-icon="close"
				:show-trailing-button="filterPerson.length > 0"
				:trailing-button-label="t('activity', 'Clear person filter')"
				@trailing-button-click="filterPerson = ''" />
			<label class="activity-app__filters-date">
				<span class="activity-app__filters-label">{{ t('activity', 'From') }}</span>
				<input v-model="filterFrom" type="date" :max="filterTo || undefined">
			</label>
			<label class="activity-app__filters-date">
				<span class="activity-app__filters-label">{{ t('activity', 'To') }}</span>
				<input v-model="filterTo" type="date" :min="filterFrom || undefined">
			</label>
			<NcButton
				v-if="anyFilterActive"
				type="tertiary"
				@click="resetFilters">
				{{ t('activity', 'Reset') }}
			</NcButton>
			<NcButton
				v-if="anyFilterActive"
				type="secondary"
				:title="t('activity', 'Save the current filter combination as a one-click view')"
				@click="saveCurrentView">
				{{ t('activity', 'Save view') }}
			</NcButton>
			<span v-if="anyFilterActive" class="activity-app__filters-count">
				{{ filterMatchCount }}
			</span>
		</form>
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
import NcTextField from '@nextcloud/vue/components/NcTextField'
import { useSavedViews, type SavedView } from '../utils/savedViews.ts'
import { useRouter } from 'vue-router'
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
 * Client-side filter state.
 *
 * These narrow the already-loaded activity list — the OCS endpoint does not
 * yet accept search/date/actor parameters, so filtering happens in the
 * browser over whatever has been paginated in.  Infinite scroll keeps
 * pulling more under the same filter, so the user can dial in by scrolling.
 */
const filterText = ref('')
const filterPerson = ref('')
const filterFrom = ref('')   // YYYY-MM-DD, inclusive
const filterTo = ref('')     // YYYY-MM-DD, inclusive

const anyFilterActive = computed(() =>
	filterText.value.trim() !== ''
		|| filterPerson.value.trim() !== ''
		|| filterFrom.value !== ''
		|| filterTo.value !== '',
)

function resetFilters() {
	filterText.value = ''
	filterPerson.value = ''
	filterFrom.value = ''
	filterTo.value = ''
}

/**
 * Activities matching the current filters, before grouping.
 */
const filteredActivities = computed<ActivityModel[]>(() => {
	if (!anyFilterActive.value) return allActivities.value

	const text = filterText.value.trim().toLowerCase()
	const person = filterPerson.value.trim().toLowerCase()
	const fromTs = filterFrom.value ? moment(filterFrom.value).startOf('day').valueOf() : undefined
	const toTs = filterTo.value ? moment(filterTo.value).endOf('day').valueOf() : undefined

	return allActivities.value.filter((a) => {
		if (fromTs !== undefined && a.timestamp < fromTs) return false
		if (toTs !== undefined && a.timestamp > toTs) return false
		if (person !== '' && !a.user.toLowerCase().includes(person)) return false
		if (text !== '') {
			const hay = (a.subject + ' ' + a.message).toLowerCase()
			if (!hay.includes(text)) return false
		}
		return true
	})
})

const filterMatchCount = computed(() =>
	t('activity', '{n} matching', { n: filteredActivities.value.length }),
)

/**
 * Saved-views composable — list lives in localStorage so users can keep
 * favourite filter combinations without backend changes.
 */
const router = useRouter()
const { views: savedViews, add: addSavedView, remove: removeSavedView } = useSavedViews()

function suggestSavedViewName(): string {
	const bits: string[] = []
	if (filterText.value)   bits.push('"' + filterText.value + '"')
	if (filterPerson.value) bits.push('@' + filterPerson.value)
	if (filterFrom.value || filterTo.value) {
		bits.push((filterFrom.value || '…') + '–' + (filterTo.value || '…'))
	}
	bits.push(props.filter)
	return bits.join(' ')
}

function describeSavedView(view: SavedView): string {
	const parts: string[] = []
	parts.push(t('activity', 'Filter') + ': ' + view.filter)
	if (view.search) parts.push(t('activity', 'Search') + ': ' + view.search)
	if (view.person) parts.push(t('activity', 'Person') + ': ' + view.person)
	if (view.from)   parts.push(t('activity', 'From') + ': ' + view.from)
	if (view.to)     parts.push(t('activity', 'To') + ': ' + view.to)
	return parts.join(' • ')
}

function saveCurrentView(): void {
	const suggested = suggestSavedViewName()
	// eslint-disable-next-line no-alert
	const name = window.prompt(t('activity', 'Name this view'), suggested)
	if (name === null) return
	const trimmed = name.trim()
	if (trimmed === '') return
	addSavedView({
		name: trimmed,
		filter: props.filter,
		search: filterText.value,
		person: filterPerson.value,
		from: filterFrom.value,
		to: filterTo.value,
	})
}

function applySavedView(view: SavedView): void {
	filterText.value = view.search
	filterPerson.value = view.person
	filterFrom.value = view.from
	filterTo.value = view.to
	if (view.filter && view.filter !== props.filter) {
		// vue-router will re-trigger the props watcher and reload activities
		router.push({ params: { filter: view.filter } })
	}
}

/**
 * Activities grouped by date (post-filter).
 */
const groupedActivities = computed(() => {
	const groups = {} as Record<string, ActivityModel[]>
	for (const activity of filteredActivities.value) {
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

	&__saved-views {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 6px;
		padding: 4px 12px;
		margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) var(--app-navigation-padding, 8px);
	}

	&__saved-views-label {
		margin-inline-end: 4px;
		color: var(--color-text-maxcontrast);
		font-size: 13px;
	}

	&__saved-view-chip {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 4px 10px;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-pill);
		background: var(--color-background-hover);
		color: var(--color-main-text);
		font-size: 13px;
		cursor: pointer;
		transition: background-color 120ms ease, border-color 120ms ease;

		&:hover, &:focus-visible {
			background: var(--color-primary-element-light, var(--color-background-darker));
			border-color: var(--color-primary-element);
		}
	}

	&__saved-view-remove {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 16px;
		height: 16px;
		border-radius: 50%;
		color: var(--color-text-maxcontrast);
		font-size: 14px;
		line-height: 1;

		&:hover {
			background: var(--color-background-darker);
			color: var(--color-error);
		}
	}

	&__filters {
		display: flex;
		flex-wrap: wrap;
		align-items: end;
		gap: 8px;
		padding: 8px 12px 12px;
		margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) var(--app-navigation-padding, 8px);
	}

	&__filters-search {
		flex-grow: 2;
		min-width: 200px;
	}

	&__filters-person {
		flex-grow: 1;
		min-width: 140px;
	}

	&__filters-date {
		display: flex;
		flex-direction: column;
		gap: 4px;
		font-size: 13px;
		color: var(--color-text-maxcontrast);

		input[type="date"] {
			height: 36px;
			padding: 0 8px;
			border: 2px solid var(--color-border);
			border-radius: var(--border-radius-large);
			background: var(--color-main-background);
			color: var(--color-main-text);
			font-size: inherit;
		}
	}

	&__filters-label {
		padding-inline-start: 8px;
	}

	&__filters-count {
		align-self: center;
		color: var(--color-text-maxcontrast);
		font-size: 13px;
	}
}
</style>
