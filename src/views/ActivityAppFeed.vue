<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<NcAppContent class="activity-app">
		<div class="activity-app__topbar">
			<h1 class="activity-app__heading">
				<component :is="headingIcon" :size="22" class="activity-app__heading-icon" />
				<span>{{ headingTitle }}</span>
			</h1>
			<button
				type="button"
				class="activity-app__refresh"
				:class="{ 'activity-app__refresh--spinning': refreshing }"
				:title="liveTooltip"
				:aria-label="t('activity', 'Refresh')"
				@click="manualRefresh">
				<IconRefresh :size="18" />
				<span
					v-if="!refreshing"
					class="activity-app__live-dot"
					:title="liveTooltip"
					aria-hidden="true" />
			</button>
			<button
				type="button"
				class="activity-app__filters-toggle"
				:aria-expanded="filtersOpenMobile ? 'true' : 'false'"
				:aria-label="t('activity', 'Toggle filters')"
				@click="filtersOpenMobile = !filtersOpenMobile">
				<IconFilter :size="18" />
				<span v-if="anyFilterActive" class="activity-app__filters-toggle-dot" aria-hidden="true" />
			</button>
			<form
				class="activity-app__filters"
				:class="{ 'activity-app__filters--mobile-open': filtersOpenMobile }"
				role="search"
				:aria-label="t('activity', 'Filter activities')"
				@submit.prevent>
				<input
					v-model="filterText"
					type="search"
					class="activity-app__filters-input activity-app__filters-search"
					:placeholder="t('activity', 'Search…')"
					:aria-label="t('activity', 'Search subject or message')">
				<input
					v-model="filterPerson"
					type="search"
					class="activity-app__filters-input activity-app__filters-person"
					:placeholder="t('activity', 'Person')"
					:aria-label="t('activity', 'Filter by user ID')">
				<input
					v-model="filterPath"
					type="search"
					class="activity-app__filters-input activity-app__filters-path"
					:placeholder="t('activity', 'Path / folder')"
					:aria-label="t('activity', 'Filter by file path or folder')"
					:title="t('activity', 'Match a substring of the file path, e.g. /Photos or .pdf')">
				<input
					v-model="filterFrom"
					type="date"
					class="activity-app__filters-input activity-app__filters-date"
					:max="filterTo || undefined"
					:title="t('activity', 'From date')"
					:aria-label="t('activity', 'From date')">
				<input
					v-model="filterTo"
					type="date"
					class="activity-app__filters-input activity-app__filters-date"
					:min="filterFrom || undefined"
					:title="t('activity', 'To date')"
					:aria-label="t('activity', 'To date')">
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
		</div>
		<p
			v-if="todaySummary"
			class="activity-app__today-summary"
			:title="t('activity', 'Summary of activities recorded today')">
			{{ todaySummary }}
		</p>
		<div v-if="savedViews.length > 0" class="activity-app__saved-views">
			<span class="activity-app__saved-views-label">{{ t('activity', 'Saved views') }}</span>
			<span
				v-for="view in savedViews"
				:key="view.id"
				class="activity-app__saved-view-chip"
				:class="{ 'activity-app__saved-view-chip--alerts': view.alerts }"
				:title="describeSavedView(view)">
				<button
					type="button"
					class="activity-app__saved-view-name"
					@click="applySavedView(view)">
					{{ view.name }}
				</button>
				<button
					type="button"
					class="activity-app__saved-view-bell"
					:title="view.alerts ? t('activity', 'Stop alerting on new matches') : t('activity', 'Alert me about new matches')"
					:aria-pressed="view.alerts ? 'true' : 'false'"
					:aria-label="t('activity', 'Toggle alerts for {name}', { name: view.name })"
					@click.stop="toggleSavedViewAlerts(view)">
					<IconBell v-if="view.alerts" :size="14" />
					<IconBellOutline v-else :size="14" />
				</button>
				<button
					type="button"
					class="activity-app__saved-view-remove"
					:aria-label="t('activity', 'Remove saved view')"
					:title="t('activity', 'Remove saved view')"
					@click.stop="removeSavedView(view.id)">×</button>
			</span>
		</div>
		<Transition name="activity-app__skeleton-fade">
			<ul
				v-if="hasMoreActivites && allActivities.length === 0"
				class="activity-app__skeletons"
				aria-hidden="true">
				<li v-for="n in 6" :key="n" class="activity-skeleton">
					<span class="activity-skeleton__avatar" />
					<span class="activity-skeleton__lines">
						<span class="activity-skeleton__line activity-skeleton__line--subject" />
						<span class="activity-skeleton__line activity-skeleton__line--message" />
					</span>
					<span class="activity-skeleton__date" />
				</li>
			</ul>
		</Transition>
		<NcEmptyContent
			v-if="!hasMoreActivites && allActivities.length === 0"
			class="activity-app__empty-content activity-app__empty-content--decorated"
			:name="t('activity', 'Nothing has happened here yet')"
			:description="emptyDescription">
			<template #icon>
				<div class="activity-app__empty-icon-stage">
					<span class="activity-app__empty-pulse activity-app__empty-pulse--1" />
					<span class="activity-app__empty-pulse activity-app__empty-pulse--2" />
					<NcIconSvgWrapper :svg="appIconSVG" :size="64" class="activity-app__empty-icon" />
				</div>
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
			<section v-if="pinnedActivities.length > 0" class="activity-app__pinned">
				<h2 class="activity-app__pinned-heading">
					<IconPin :size="16" />
					{{ t('activity', 'Pinned') }}
				</h2>
				<ul class="activity-app__pinned-list">
					<ActivityComponent
						v-for="activity in pinnedActivities"
						:key="'pinned-' + activity.id"
						:activity="activity"
						:show-previews="true" />
				</ul>
			</section>
			<TransitionGroup name="activity-fade" tag="div" class="activity-app__groups">
				<ActivityGroup
					v-for="activities, date of groupedActivities"
					:key="date"
					:activities="activities"
					:fresh-ids="freshIds" />
			</TransitionGroup>
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
import { translate as t, translatePlural as n } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { generateOcsUrl } from '@nextcloud/router'
import { useDebounce, useDocumentVisibility, useInfiniteScroll, useDebounceFn } from '@vueuse/core'
import axios from 'axios'
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcLoadingIcon from '@nextcloud/vue/components/NcLoadingIcon'
import IconRefresh from 'vue-material-design-icons/Refresh.vue'
import IconFilter from 'vue-material-design-icons/FilterVariant.vue'
import IconPin from 'vue-material-design-icons/Pin.vue'
import IconBell from 'vue-material-design-icons/Bell.vue'
import IconBellOutline from 'vue-material-design-icons/BellOutline.vue'
import IconViewList from 'vue-material-design-icons/ViewList.vue'
import IconAccount from 'vue-material-design-icons/Account.vue'
import IconAccountGroup from 'vue-material-design-icons/AccountGroup.vue'
import IconFolder from 'vue-material-design-icons/Folder.vue'
import IconStar from 'vue-material-design-icons/Star.vue'
import IconShareVariant from 'vue-material-design-icons/ShareVariant.vue'
import IconCommentText from 'vue-material-design-icons/CommentText.vue'
import IconCalendar from 'vue-material-design-icons/Calendar.vue'
import IconCheckboxMarkedCircle from 'vue-material-design-icons/CheckboxMarkedCircle.vue'
import IconCardAccountDetails from 'vue-material-design-icons/CardAccountDetails.vue'
import IconHistory from 'vue-material-design-icons/History.vue'
import { useMutedTypes } from '../utils/mutedTypes.ts'
import { useMutedUsers } from '../utils/mutedUsers.ts'
import { usePinnedActivities } from '../utils/pinnedActivities.ts'
import { useSavedViews, type SavedView } from '../utils/savedViews.ts'
import { useRouter } from 'vue-router'
import ActivityGroup from '../components/ActivityGroup.vue'
import ActivityComponent from '../components/ActivityComponent.vue'
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
 * Set of activity IDs that arrived via polling (not the initial load).
 * Each row checks against this set and renders a one-shot highlight
 * pulse animation to draw the eye to fresh content.  Entries auto-expire
 * after FRESH_TTL_MS so the pulse only fires once per arrival.
 */
const freshIds = ref<Set<number>>(new Set())
const FRESH_TTL_MS = 1600

function markFresh(ids: number[]): void {
	if (ids.length === 0) return
	const next = new Set(freshIds.value)
	for (const id of ids) next.add(id)
	freshIds.value = next
	// Schedule cleanup so the set doesn't grow unbounded and the
	// animation only plays once per row.
	setTimeout(() => {
		const after = new Set(freshIds.value)
		for (const id of ids) after.delete(id)
		freshIds.value = after
	}, FRESH_TTL_MS)
}

/**
 * Adaptive polling: the next-poll delay shortens when recent polls have
 * brought in fresh content (the user is in an active session, near other
 * collaborators) and lengthens when they've been idle (long stretches
 * with no matches mean we're paying server cost for nothing).
 *
 * Bounds are deliberately wide so the feed feels almost-live during
 * collaboration sessions but doesn't hammer the server when quiet.
 */
const POLL_MIN_MS = 8000
const POLL_MAX_MS = 60000
const POLL_INITIAL_MS = 20000
const pollDelayMs = ref(POLL_INITIAL_MS)

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
 * Free-text search is applied server-side via a `q=` parameter on the
 * OCS endpoint (debounced 300ms to avoid hammering the database while
 * the user is typing) — see watch() further down which resets and
 * refetches when activeSearch changes.  Person and date filters stay
 * client-side over whatever the infinite scroll has paginated in.
 */
const filterText = ref('')
const filterPerson = ref('')
const filterPath = ref('')   // substring match on objectName / file path
const filterFrom = ref('')   // YYYY-MM-DD, inclusive
const filterTo = ref('')     // YYYY-MM-DD, inclusive

/**
 * Mobile-only: whether the filters panel is open.  On wide screens the
 * filters always show inline; the toggle button is hidden via CSS so this
 * value is irrelevant there.
 */
const filtersOpenMobile = ref(false)

// Debounced version of the text filter — driven into the OCS request
// 300ms after the user stops typing.
const activeSearch = useDebounce(filterText, 300)

const anyFilterActive = computed(() =>
	filterText.value.trim() !== ''
		|| filterPerson.value.trim() !== ''
		|| filterPath.value.trim() !== ''
		|| filterFrom.value !== ''
		|| filterTo.value !== '',
)

function resetFilters() {
	filterText.value = ''
	filterPerson.value = ''
	filterPath.value = ''
	filterFrom.value = ''
	filterTo.value = ''
}

/**
 * "Refreshing" flag used to drive the spin animation on the refresh
 * button.  Held true for at least 600ms even on a fast poll so the spin
 * is always visible — instant completion would feel like the click did
 * nothing.
 */
const refreshing = ref(false)

/**
 * Friendly tooltip that doubles as a status read-out: tells the user
 * the app is live-polling and how often.  Updates whenever the
 * adaptive delay changes.
 */
const liveTooltip = computed<string>(() => {
	const seconds = Math.round(pollDelayMs.value / 1000)
	return t('activity', 'Live — checking every ~{n}s. Click to refresh now.', { n: seconds })
})

async function manualRefresh() {
	if (refreshing.value) return
	refreshing.value = true
	const minSpin = new Promise((resolve) => setTimeout(resolve, 600))
	try {
		await Promise.all([pollNewActivities(), minSpin])
	} finally {
		refreshing.value = false
	}
}

/**
 * Activities matching the current filters, before grouping.  Text search
 * already happened server-side, so this only narrows by person + date,
 * and drops any activity whose type the user has muted via the row
 * context menu.
 */
const { muted: mutedTypes } = useMutedTypes()
const { muted: mutedUsers } = useMutedUsers()
const { pinned: pinnedIds } = usePinnedActivities()

/**
 * Best-effort path string for an activity, used by the path filter.
 * Tries the rich subject objects first (these carry a `path` for "file"
 * rich objects), then falls back to objectName / link.  Returns lower-
 * cased so the caller can do a case-insensitive substring compare.
 */
function activityPath(a: ActivityModel): string {
	const objs = a.subjectRichObjects
	for (const key of Object.keys(objs)) {
		const obj = objs[key]
		if (obj && obj.type === 'file') {
			const p = (obj as { path?: unknown }).path
			if (typeof p === 'string' && p !== '') return p.toLowerCase()
			if (typeof obj.name === 'string' && obj.name !== '') return obj.name.toLowerCase()
		}
	}
	if (a.objectName) return a.objectName.toLowerCase()
	if (a.link) return a.link.toLowerCase()
	return ''
}

const filteredActivities = computed<ActivityModel[]>(() => {
	const personActive = filterPerson.value.trim() !== ''
	const pathActive = filterPath.value.trim() !== ''
	const dateActive = filterFrom.value !== '' || filterTo.value !== ''
	const muteTypeActive = mutedTypes.value.length > 0
	const muteUserActive = mutedUsers.value.length > 0

	if (!personActive && !pathActive && !dateActive && !muteTypeActive && !muteUserActive) return allActivities.value

	const person = filterPerson.value.trim().toLowerCase()
	const path = filterPath.value.trim().toLowerCase()
	const fromTs = filterFrom.value ? moment(filterFrom.value).startOf('day').valueOf() : undefined
	const toTs = filterTo.value ? moment(filterTo.value).endOf('day').valueOf() : undefined
	const muteTypeSet = new Set(mutedTypes.value)
	const muteUserSet = new Set(mutedUsers.value)

	return allActivities.value.filter((a) => {
		if (muteTypeSet.has(a.type)) return false
		if (muteUserSet.has(a.user)) return false
		if (fromTs !== undefined && a.timestamp < fromTs) return false
		if (toTs !== undefined && a.timestamp > toTs) return false
		if (person !== '' && !a.user.toLowerCase().includes(person)) return false
		if (path !== '' && !activityPath(a).includes(path)) return false
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
const {
	views: savedViews,
	add: addSavedView,
	remove: removeSavedView,
	setAlerts: setSavedViewAlerts,
	setLastSeenId: setSavedViewLastSeenId,
} = useSavedViews()

function suggestSavedViewName(): string {
	const bits: string[] = []
	if (filterText.value)   bits.push('"' + filterText.value + '"')
	if (filterPerson.value) bits.push('@' + filterPerson.value)
	if (filterPath.value)   bits.push('📁' + filterPath.value)
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
	if (view.path)   parts.push(t('activity', 'Path') + ': ' + view.path)
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
		path: filterPath.value,
		from: filterFrom.value,
		to: filterTo.value,
		alerts: false,
		lastSeenId: 0,
	})
}

function applySavedView(view: SavedView): void {
	filterText.value = view.search
	filterPerson.value = view.person
	filterPath.value = view.path ?? ''
	filterFrom.value = view.from
	filterTo.value = view.to
	if (view.filter && view.filter !== props.filter) {
		// vue-router will re-trigger the props watcher and reload activities
		router.push({ params: { filter: view.filter } })
	}
}

/**
 * Toggle desktop notifications for a saved view.  First time the user
 * enables alerts on any view we ask the browser for Notification
 * permission; after that the answer is cached by the browser and we
 * just respect it.
 */
async function toggleSavedViewAlerts(view: SavedView): Promise<void> {
	const turningOn = !view.alerts
	if (turningOn && typeof window.Notification !== 'undefined') {
		if (window.Notification.permission === 'default') {
			try {
				await window.Notification.requestPermission()
			} catch (e) {
				logger.debug('Notification permission request failed', e as Error)
			}
		}
		if (window.Notification.permission === 'denied') {
			showError(t('activity', 'Browser notifications are blocked. Enable them in your browser settings.'))
			return
		}
	}
	setSavedViewAlerts(view.id, turningOn)
	// Seed lastSeenId with the newest currently-loaded id so the user
	// doesn't get spammed with backfill notifications on first enable.
	if (turningOn && newestActivityId.value !== undefined) {
		setSavedViewLastSeenId(view.id, newestActivityId.value)
	}
}

/**
 * True when an activity matches a saved view's filters.  Mirrors the
 * client-side parts of filteredActivities — server-side text search is
 * skipped here because polling doesn't carry a search query through.
 */
function activityMatchesSavedView(a: ActivityModel, view: SavedView): boolean {
	const person = view.person.trim().toLowerCase()
	if (person !== '' && !a.user.toLowerCase().includes(person)) return false
	const path = (view.path ?? '').trim().toLowerCase()
	if (path !== '' && !activityPath(a).includes(path)) return false
	if (view.from !== '') {
		const fromTs = moment(view.from).startOf('day').valueOf()
		if (a.timestamp < fromTs) return false
	}
	if (view.to !== '') {
		const toTs = moment(view.to).endOf('day').valueOf()
		if (a.timestamp > toTs) return false
	}
	const search = view.search.trim().toLowerCase()
	if (search !== '') {
		const haystack = (a.subject + ' ' + a.message).toLowerCase()
		if (!haystack.includes(search)) return false
	}
	// view.filter is the OCP filter id ('all' | 'self' | 'by' | …) and
	// is enforced server-side; we don't try to recreate that bucketing
	// here.  Alerts on a saved view are best-effort within the
	// currently-active OCP filter.
	return true
}

/**
 * For each alert-subscribed saved view, count newly-arrived activities
 * (id > view.lastSeenId) that match the view's filters and fire one
 * notification per view.  Updates lastSeenId so we don't re-notify on
 * subsequent polls.
 */
function notifyForSavedViews(newlyArrived: ActivityModel[]): void {
	if (newlyArrived.length === 0) return
	if (typeof window.Notification === 'undefined') return
	if (window.Notification.permission !== 'granted') return

	for (const view of savedViews.value) {
		if (!view.alerts) continue
		const fresh = newlyArrived.filter((a) => a.id > view.lastSeenId && activityMatchesSavedView(a, view))
		if (fresh.length === 0) continue
		const newestId = fresh.reduce((m, a) => Math.max(m, a.id), view.lastSeenId)
		setSavedViewLastSeenId(view.id, newestId)
		try {
			const note = new window.Notification(
				n('activity', '{n} new match in "{name}"', '{n} new matches in "{name}"', fresh.length, { name: view.name }),
				{
					body: fresh[0].subject || t('activity', 'Open Activity for details'),
					tag: 'activity-saved-view-' + view.id,
					silent: false,
				},
			)
			note.onclick = () => {
				window.focus()
				applySavedView(view)
				note.close()
			}
		} catch (e) {
			logger.debug('Could not display saved-view notification', e as Error)
		}
	}
}

/**
 * Pinned activities (subset of filteredActivities whose ids are in
 * pinnedIds), preserving the order of pinnedIds (most recently pinned
 * first).  Rendered above the per-day groups so they're always visible
 * regardless of how far the user has scrolled.
 */
const pinnedActivities = computed<ActivityModel[]>(() => {
	if (pinnedIds.value.length === 0) return []
	const pinSet = new Set(pinnedIds.value)
	const byId = new Map<number, ActivityModel>()
	for (const a of filteredActivities.value) {
		if (pinSet.has(a.id)) byId.set(a.id, a)
	}
	const list: ActivityModel[] = []
	for (const id of pinnedIds.value) {
		const a = byId.get(id)
		if (a) list.push(a)
	}
	return list
})

const pinnedSet = computed<Set<number>>(() => new Set(pinnedIds.value))

/**
 * Activities grouped by date (post-filter).  Pinned activities are
 * filtered OUT of the day groups since they render in their own
 * always-visible section at the top — duplicating them would be noise.
 */
const groupedActivities = computed(() => {
	const groups = {} as Record<string, ActivityModel[]>
	const pins = pinnedSet.value
	for (const activity of filteredActivities.value) {
		if (pins.has(activity.id)) continue
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

// Map filter ids to a Material Design icon component.  IDs come from
// server-side IFilter::getIdentifier() implementations across apps —
// this list covers the bundled filters; unknown ids fall back to a
// generic history icon so newly-registered filters still render
// something reasonable.
const filterIcons: Record<string, unknown> = {
	all: IconViewList,
	self: IconAccount,
	by: IconAccountGroup,
	files: IconFolder,
	files_favorites: IconStar,
	files_sharing: IconShareVariant,
	comments: IconCommentText,
	calendar: IconCalendar,
	calendar_todo: IconCheckboxMarkedCircle,
	contacts: IconCardAccountDetails,
}

const headingIcon = computed(() => {
	const id = String(route.params.filter ?? 'all')
	return filterIcons[id] ?? IconHistory
})

/**
 * Bucket an activity into the same families used by the visual type-accent
 * styling.  Kept in sync with GenericActivity.typeFamily so the summary header
 * and the rows tell the same story.
 */
function typeFamily(type: string): 'created' | 'changed' | 'deleted' | 'share' | 'comment' | 'favorite' | 'other' {
	if (type === 'file_created') return 'created'
	if (type === 'file_deleted') return 'deleted'
	if (type === 'file_changed' || type === 'file_restored') return 'changed'
	if (type === 'favorite') return 'favorite'
	if (type === 'comments') return 'comment'
	if (type.startsWith('shared') || type.startsWith('share') || type.startsWith('unshare') || type === 'remote_share' || type.includes('_share')) return 'share'
	return 'other'
}

/**
 * One-line "Today: 12 changes, 3 shares, 1 deletion" summary built from the
 * already-loaded activities.  Returns an empty string when nothing has loaded
 * yet or nothing happened today, so the line collapses cleanly.
 */
const todaySummary = computed<string>(() => {
	if (allActivities.value.length === 0) return ''

	const today = moment().startOf('day')
	const counts: Record<ReturnType<typeof typeFamily>, number> = {
		created: 0, changed: 0, deleted: 0, share: 0, comment: 0, favorite: 0, other: 0,
	}
	let total = 0
	for (const activity of allActivities.value) {
		if (!moment(activity.datetime).isSame(today, 'day')) continue
		counts[typeFamily(activity.type)]++
		total++
	}
	if (total === 0) return ''

	const parts: string[] = []
	if (counts.changed)  parts.push(n('activity', '%n change',     '%n changes',     counts.changed))
	if (counts.created)  parts.push(n('activity', '%n addition',   '%n additions',   counts.created))
	if (counts.deleted)  parts.push(n('activity', '%n deletion',   '%n deletions',   counts.deleted))
	if (counts.share)    parts.push(n('activity', '%n share',      '%n shares',      counts.share))
	if (counts.comment)  parts.push(n('activity', '%n comment',    '%n comments',    counts.comment))
	if (counts.favorite) parts.push(n('activity', '%n favorite',   '%n favorites',   counts.favorite))
	if (counts.other)    parts.push(n('activity', '%n other event','%n other events',counts.other))

	return t('activity', 'Today: {summary}', { summary: parts.join(', ') })
})

/**
 * One of a small set of friendly, situation-aware messages for the empty
 * state.  Picked deterministically from the current filter so reloads on
 * the same page show the same line — randomness here would be jarring.
 */
const emptyDescription = computed<string>(() => {
	const filter = String(route.params.filter ?? 'all')
	const messages = filter === 'self'
		? [
			t('activity', 'When you upload, edit or share a file, it will appear here.'),
			t('activity', 'Your own actions will show up in this stream once you start working.'),
		]
		: filter === 'by'
			? [
				t('activity', 'When someone shares with you or comments on a file you own, it will appear here.'),
				t('activity', 'Activity from collaborators will land here as soon as it happens.'),
			]
			: [
				t('activity', 'Upload a file, share something, or favourite a folder — events will start appearing here as soon as you do.'),
				t('activity', 'This is where your Nextcloud activity lives. Add some files or shares to get started.'),
			]
	// Pick one deterministically by hashing the filter id, so the empty
	// state is stable per page across reloads.
	const idx = filter.split('').reduce((s, c) => (s + c.charCodeAt(0)) % messages.length, 0)
	return messages[idx]
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
		const q = activeSearch.value.trim()
		loading.value = true
		const url = generateOcsUrl('apps/activity/api/v2/activity/{filter}?format=json&previews=true&since={since}', { filter: props.filter, since })
			+ (q !== '' ? '&q=' + encodeURIComponent(q) : '')
		const response = await ncAxios.get(url, { signal })
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
			// If the page was opened with a permalink, retry the scroll
			// after each load — the target may not have appeared in the
			// first batch and infinite scroll keeps pulling more.
			maybeScrollToPermalink()
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
	let gotResults = false
	try {
		const since = String(newestActivityId.value ?? 0)
		const q = activeSearch.value.trim()
		const url = generateOcsUrl('apps/activity/api/v2/activity/{filter}?format=json&previews=true&since={since}&sort=asc', { filter: props.filter, since })
			+ (q !== '' ? '&q=' + encodeURIComponent(q) : '')
		const response = await ncAxios.get(url, { signal })
		if (!signal.aborted && response.data.ocs.data.length > 0) {
			gotResults = true
			const newActivities: ActivityModel[] = response.data.ocs.data.map((raw: IRawActivity) => new ActivityModel(raw))
			// Sort newest first for prepending
			newActivities.sort((a: ActivityModel, b: ActivityModel) => b.id - a.id)
			newestActivityId.value = newActivities[0]!.id
			allActivities.value.unshift(...newActivities)
			markFresh(newActivities.map((a) => a.id))
			notifyForSavedViews(newActivities)

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

	// Adapt the next interval to recent traffic: halve it on a hit
	// (down to POLL_MIN_MS), grow by 25% on a miss (up to POLL_MAX_MS).
	// This keeps the feed feeling near-live during active sessions but
	// pulls back on a quiet day so the server isn't asked uselessly
	// every few seconds.
	if (gotResults) {
		pollDelayMs.value = Math.max(POLL_MIN_MS, Math.floor(pollDelayMs.value / 2))
	} else {
		pollDelayMs.value = Math.min(POLL_MAX_MS, Math.floor(pollDelayMs.value * 1.25))
	}

	// Self-schedule only if polling wasn't stopped while the request was in flight
	if (pollTimer !== undefined) {
		pollTimer = setTimeout(pollNewActivities, pollDelayMs.value)
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
	// knows polling is active even before the first tick fires.  Reset the
	// adaptive delay back to the initial mid-range so a long-tab-idle
	// session resumes responsively when the tab regains focus.
	pollDelayMs.value = POLL_INITIAL_MS
	pollTimer = setTimeout(pollNewActivities, pollDelayMs.value)
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
/**
 * Read `id=…` from the current hash query — used to deep-link to a
 * specific activity (set by the row's "Copy link" button) and scroll
 * to it once it has loaded.
 */
function permalinkTargetId(): number | null {
	try {
		const hash = window.location.hash || ''
		const q = hash.split('?')[1]
		if (!q) return null
		const id = Number(new URLSearchParams(q).get('id'))
		return Number.isFinite(id) && id > 0 ? id : null
	} catch {
		return null
	}
}

/**
 * Once any new activities arrive, see if the page was opened with a
 * permalink and the target row is now in the DOM; if so, scroll to it
 * and disarm so we don't re-jump on subsequent loads.
 */
let permalinkArmed = true
function maybeScrollToPermalink() {
	if (!permalinkArmed) return
	const id = permalinkTargetId()
	if (id === null) {
		permalinkArmed = false
		return
	}
	nextTick(() => {
		const row = document.querySelector(
			'.activity-entry[data-activity-id="' + String(id) + '"]',
		) as HTMLElement | null
		if (row) {
			row.scrollIntoView({ behavior: 'smooth', block: 'center' })
			permalinkArmed = false
		}
	})
}

onMounted(() => {
	loadActivities().then(maybeScrollToPermalink)
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
 * Reload activities when filter or (debounced) free-text search changes —
 * both reset the paginated state and re-pull from the top of the stream
 * so we never mix matches from before/after a query change.
 */
function reloadActivities() {
	requestController.abort()
	requestController = new AbortController()
	allActivities.value = []
	newActivitiesAvailable.value = false
	lastActivityLoaded.value = undefined
	newestActivityId.value = undefined
	hasMoreActivites.value = true
	loadActivities()
}

watch(props, reloadActivities)
watch(activeSearch, reloadActivities)
</script>

<style scoped lang="scss">
.activity-app {
	display: flex;
	flex-direction: column;
	overflow: hidden;

	&__empty-content {
		height: 100%;
	}

	&__empty-content--decorated {
		// Soft radial backdrop behind the icon to make the page feel
		// composed instead of "loading screen with text".
		background:
			radial-gradient(circle at 50% 38%, var(--color-primary-element-light, var(--color-background-hover)) 0%, transparent 60%),
			transparent;
	}

	&__empty-icon-stage {
		position: relative;
		width: 96px;
		height: 96px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	&__empty-icon {
		position: relative;
		z-index: 2;
		opacity: 0.85;
	}

	&__empty-pulse {
		position: absolute;
		inset: 0;
		border-radius: 50%;
		background: var(--color-primary-element);
		opacity: 0.18;
		transform: scale(0.6);
		animation: activity-empty-pulse 2.4s ease-out infinite;

		&--2 {
			animation-delay: 1.2s;
		}
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

	&__skeletons {
		list-style: none;
		padding-inline: 12px;
		width: min(100%, 924px);
		max-width: 924px;
		margin: 16px auto 0;
	}

	// Pinned section sits above the day-grouped feed.  Subtle highlight
	// background + a "PINNED" header so it's obvious these aren't part
	// of today's normal activity stream.
	&__pinned {
		margin-block: 4px 12px;
		padding: 6px 8px 8px;
		border: 1px dashed var(--color-border);
		border-radius: var(--border-radius-large);
		background: linear-gradient(180deg, var(--color-primary-element-light, var(--color-background-hover)) 0%, transparent 60%);
	}

	&__pinned-heading {
		display: flex;
		align-items: center;
		gap: 6px;
		margin: 0 0 4px;
		font-size: 12px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.06em;
		color: var(--color-primary-element);
	}

	&__pinned-list {
		list-style: none;
		margin: 0;
		padding: 0;
		// Pinned rows reuse .activity-entry styling but live outside a
		// day-group, so suppress the rail line that would otherwise
		// dangle in the middle of the section.
		position: relative;

		&::before {
			content: '';
			position: absolute;
			left: 14px;
			top: 4px;
			bottom: 4px;
			width: 2px;
			background: var(--color-primary-element);
			opacity: 0.35;
			pointer-events: none;
		}
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

	&__topbar {
		display: flex;
		align-items: center;
		flex-wrap: wrap;
		gap: 12px;
		// No top padding / margin so the heading's 44px line-box sits
		// flush with the app-navigation toggle (44px square at y=0).
		// Bottom padding gives visual breathing room before the
		// today-summary / saved-views row underneath.
		padding-block: 0 8px;
		margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) var(--app-navigation-padding, 8px);
		border-bottom: 1px solid transparent;
	}

	&__heading {
		flex-shrink: 0;
		display: inline-flex;
		align-items: center;
		gap: 10px;
		margin: 0;
		font-weight: bold;
		font-size: 20px;
		// 44px matches the app-navigation toggle so the heading
		// vertically centres on the same row as the toggle button.
		height: 44px;
		line-height: 44px;
	}

	&__heading-icon {
		display: inline-flex;
		align-items: center;
		color: var(--color-primary-element);
		flex-shrink: 0;
	}

	&__refresh {
		position: relative;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		width: 32px;
		height: 32px;
		padding: 0;
		border: 1px solid var(--color-border);
		border-radius: 50%;
		background: var(--color-main-background);
		color: var(--color-text-maxcontrast);
		cursor: pointer;
		transition: background-color 120ms ease, color 120ms ease, border-color 120ms ease;

		&:hover {
			background: var(--color-background-hover);
			color: var(--color-main-text);
			border-color: var(--color-primary-element);
		}

		&--spinning :deep(svg) {
			animation: activity-refresh-spin 600ms ease-in-out;
		}
	}

	// "Live" pulse dot pinned to the bottom-right of the refresh
	// button.  Constant slow pulse signals that polling is active
	// without distracting the eye away from the feed itself.
	&__live-dot {
		position: absolute;
		bottom: 2px;
		right: 2px;
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: var(--color-success, #46ba61);
		box-shadow: 0 0 0 2px var(--color-main-background);
		animation: activity-live-pulse 2s ease-in-out infinite;
	}

	&__today-summary {
		color: var(--color-text-maxcontrast);
		font-size: 13px;
		font-weight: 500;
		margin-top: 4px;
		margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) var(--app-navigation-padding, 8px);
		margin-bottom: 12px;
		padding: 4px 0;

		// A tiny bullet before the text gives the line some character
		&::before {
			content: '✨';
			margin-inline-end: 6px;
			opacity: 0.7;
		}
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
		padding: 2px 4px 2px 0;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius-pill);
		background: var(--color-background-hover);
		color: var(--color-main-text);
		font-size: 13px;
		transition: background-color 120ms ease, border-color 120ms ease;

		&--alerts {
			border-color: var(--color-primary-element);
			background: var(--color-primary-element-light, var(--color-background-hover));
		}
	}

	&__saved-view-name {
		appearance: none;
		background: transparent;
		border: none;
		color: inherit;
		font: inherit;
		padding: 4px 8px 4px 10px;
		cursor: pointer;
		border-radius: var(--border-radius-pill);

		&:hover, &:focus-visible {
			background: var(--color-background-darker);
		}
	}

	&__saved-view-bell {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 20px;
		height: 20px;
		padding: 0;
		border: none;
		border-radius: 50%;
		background: transparent;
		color: var(--color-text-maxcontrast);
		cursor: pointer;

		&[aria-pressed='true'] {
			color: var(--color-primary-element);
		}

		&:hover, &:focus-visible {
			background: var(--color-background-darker);
		}
	}

	&__saved-view-remove {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 18px;
		height: 18px;
		padding: 0;
		border: none;
		border-radius: 50%;
		background: transparent;
		color: var(--color-text-maxcontrast);
		font-size: 14px;
		line-height: 1;
		cursor: pointer;

		&:hover, &:focus-visible {
			background: var(--color-background-darker);
			color: var(--color-error);
		}
	}

	// Compact inline filter row that lives inside __topbar next to the
	// heading.  Heights match the heading line-height so the bar reads as a
	// single row.  All inputs share a base style; widths are tuned per field.
	&__filters {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 6px;
		flex-grow: 1;
		justify-content: flex-end;
		margin: 0;
		padding: 0;
	}

	&__filters-input {
		height: 30px;
		padding: 0 8px;
		border: 1px solid var(--color-border);
		border-radius: var(--border-radius);
		background: var(--color-main-background);
		color: var(--color-main-text);
		font: inherit;
		font-size: 13px;

		&:focus {
			outline: none;
			border-color: var(--color-primary-element);
		}
	}

	&__filters-search { width: 160px; }
	&__filters-person { width: 110px; }
	&__filters-path   { width: 140px; }
	&__filters-date   { width: 130px; }

	&__filters-count {
		align-self: center;
		color: var(--color-text-maxcontrast);
		font-size: 13px;
	}

	// Mobile-only filter toggle.  Hidden on wide screens — the filter
	// row always shows inline there.  On narrow screens the inputs
	// would consume too much horizontal space and force-wrap into a
	// multi-row mess, so we collapse them behind this button instead.
	&__filters-toggle {
		display: none;
		position: relative;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
		width: 32px;
		height: 32px;
		padding: 0;
		border: 1px solid var(--color-border);
		border-radius: 50%;
		background: var(--color-main-background);
		color: var(--color-text-maxcontrast);
		cursor: pointer;

		&:hover {
			background: var(--color-background-hover);
			color: var(--color-main-text);
			border-color: var(--color-primary-element);
		}

		&[aria-expanded='true'] {
			background: var(--color-primary-element-light, var(--color-background-hover));
			border-color: var(--color-primary-element);
			color: var(--color-primary-element);
		}
	}

	&__filters-toggle-dot {
		position: absolute;
		top: 4px;
		right: 4px;
		width: 8px;
		height: 8px;
		border-radius: 50%;
		background: var(--color-primary-element);
		box-shadow: 0 0 0 2px var(--color-main-background);
	}

	// ── Mobile (≤ 720px): tighten everything ──────────────────────
	@media (max-width: 720px) {
		&__topbar {
			gap: 6px;
			// Less left clearance — the navigation toggle is still 44px
			// but the side margins shrink so the heading has more room.
			margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) 4px;
		}

		&__heading {
			font-size: 17px;
			gap: 6px;
		}

		&__filters-toggle {
			display: inline-flex;
			margin-inline-start: auto;
		}

		&__filters {
			// Drop to a column under the topbar when the toggle is open;
			// hide entirely otherwise.  flex-basis: 100% breaks the row
			// so the filter inputs occupy the full width below the
			// heading instead of squeezing in beside it.
			display: none;
			flex-basis: 100%;
			flex-direction: column;
			align-items: stretch;
			gap: 8px;
			padding-block: 4px 8px;

			&--mobile-open {
				display: flex;
			}
		}

		&__filters-input {
			width: 100% !important;
			height: 36px;
		}

		&__filters-search,
		&__filters-person,
		&__filters-date {
			width: 100%;
		}

		&__today-summary,
		&__saved-views {
			margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) 4px;
		}

		&__container {
			padding-inline: 4px;
		}
	}
}

// Fade newly-arriving activity groups in over a short window so polled
// items don't pop into existence — the user sees them animate in instead.
.activity-fade-enter-active {
	transition: opacity 220ms ease, transform 220ms ease;
}
.activity-fade-enter-from {
	opacity: 0;
	transform: translateY(-4px);
}

// Skeleton-list fades out as the real feed fades in, instead of cutting
// abruptly.  Both states overlap briefly via position: absolute on the
// leaving skeleton.
.activity-app__skeleton-fade-leave-active {
	transition: opacity 320ms ease;
}
.activity-app__skeleton-fade-leave-to {
	opacity: 0;
}

.activity-skeleton {
	display: flex;
	align-items: flex-start;
	gap: 8px;
	padding: 10px 0;
	border-bottom: 1px solid var(--color-border);

	&__avatar {
		flex-shrink: 0;
		width: 20px;
		height: 20px;
		border-radius: 50%;
		background: var(--color-background-darker);
		animation: activity-skeleton-pulse 1.4s ease-in-out infinite;
	}

	&__lines {
		display: flex;
		flex-direction: column;
		gap: 6px;
		flex-grow: 1;
		padding-top: 2px;
	}

	&__line {
		display: block;
		height: 10px;
		border-radius: var(--border-radius);
		background: var(--color-background-darker);
		animation: activity-skeleton-pulse 1.4s ease-in-out infinite;

		&--subject { width: 55%; }
		&--message { width: 80%; opacity: 0.6; }
	}

	&__date {
		flex-shrink: 0;
		width: 56px;
		height: 10px;
		margin-top: 4px;
		border-radius: var(--border-radius);
		background: var(--color-background-darker);
		animation: activity-skeleton-pulse 1.4s ease-in-out infinite;
	}
}

@keyframes activity-skeleton-pulse {
	0%, 100% { opacity: 1; }
	50%      { opacity: 0.5; }
}

@keyframes activity-empty-pulse {
	0%   { transform: scale(0.6); opacity: 0.25; }
	70%  { transform: scale(1.4); opacity: 0;    }
	100% { transform: scale(1.4); opacity: 0;    }
}

@keyframes activity-refresh-spin {
	from { transform: rotate(0deg); }
	to   { transform: rotate(360deg); }
}

@keyframes activity-live-pulse {
	0%, 100% { transform: scale(1);   opacity: 0.85; }
	50%      { transform: scale(1.4); opacity: 0.35; }
}

</style>
