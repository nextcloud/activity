<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<div class="activity-filter">
		<div class="activity-filter__controls">
			<NcTextField
				v-model="searchInput"
				class="activity-filter__search"
				type="search"
				:label="t('activity', 'Search by file name')"
				:showTrailingButton="searchInput !== ''"
				:trailingButtonLabel="t('activity', 'Clear search')"
				trailingButtonIcon="close"
				:helperText="searchHelperText"
				@trailingButtonClick="clearSearch">
				<template #icon>
					<Magnify :size="20" />
				</template>
			</NcTextField>

			<template v-if="actorOptions.length > 0">
				<label class="hidden-visually" :for="actorInputId">
					{{ t('activity', 'Filter by account') }}
				</label>
				<NcSelectUsers
					class="activity-filter__actor"
					:inputId="actorInputId"
					labelOutside
					:options="actorOptions"
					:modelValue="selectedActorOption"
					:placeholder="t('activity', 'Anyone')"
					@update:modelValue="onActorChange" />
			</template>

			<NcActions
				class="activity-filter__range"
				forceMenu
				:menuName="dateRangeLabel"
				:aria-label="t('activity', 'Filter activities by date')">
				<template #icon>
					<CalendarRange :size="20" />
				</template>
				<NcActionButton
					v-for="preset of presets"
					:key="preset.id"
					type="radio"
					:modelValue="selectedPreset"
					:value="preset.id"
					@click="applyPreset(preset.id)">
					{{ preset.label }}
				</NcActionButton>
			</NcActions>

			<NcButton
				v-if="hasActiveFilters"
				variant="tertiary"
				@click="clearAll">
				{{ t('activity', 'Clear filters') }}
			</NcButton>
		</div>

		<div v-if="selectedPreset === 'custom'" class="activity-filter__custom-range">
			<NcDateTimePickerNative
				:modelValue="from"
				class="activity-filter__date"
				type="date"
				:label="t('activity', 'From')"
				:max="to ?? today"
				@update:modelValue="emit('update:from', $event)" />
			<NcDateTimePickerNative
				:modelValue="to"
				class="activity-filter__date"
				type="date"
				:label="t('activity', 'To')"
				:min="from"
				:max="today"
				@update:modelValue="emit('update:to', $event)" />
		</div>
	</div>
</template>

<script setup lang="ts">
import { translate as t } from '@nextcloud/l10n'
import { useDebounceFn } from '@vueuse/core'
import { computed, ref, useId, watch } from 'vue'
import NcActionButton from '@nextcloud/vue/components/NcActionButton'
import NcActions from '@nextcloud/vue/components/NcActions'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcDateTimePickerNative from '@nextcloud/vue/components/NcDateTimePickerNative'
import NcSelectUsers from '@nextcloud/vue/components/NcSelectUsers'
import NcTextField from '@nextcloud/vue/components/NcTextField'
import CalendarRange from 'vue-material-design-icons/CalendarRange.vue'
import Magnify from 'vue-material-design-icons/Magnify.vue'
import { daysAgo, MIN_SEARCH_TERM_LENGTH, normalizeSearchTerm } from '../utils/dateRange.ts'

const props = defineProps<{
	/**
	 * The active file name search term, or an empty string
	 */
	search: string
	/**
	 * Start of the active date range, or null when unbounded
	 */
	from: Date | null
	/**
	 * End of the active date range, or null when unbounded
	 */
	to: Date | null
	/**
	 * Account name the stream is restricted to, or an empty string
	 */
	actor: string
	/**
	 * Accounts that can be picked, derived from the activities loaded so far
	 */
	actorOptions: ActorOption[]
}>()

const emit = defineEmits<{
	(event: 'update:search', value: string): void
	(event: 'update:from', value: Date | null): void
	(event: 'update:to', value: Date | null): void
	(event: 'update:actor', value: string): void
}>()

const actorInputId = useId()

/**
 * The option matching the active account.
 *
 * Synthesised when the account is not among the options, so an account
 * restored from the URL still shows up before its activities have loaded.
 */
const selectedActorOption = computed<ActorOption | null>(() => {
	if (props.actor === '') {
		return null
	}
	return props.actorOptions.find((option) => option.id === props.actor)
		?? { id: props.actor, displayName: props.actor, user: props.actor }
})

/**
 * Handle a pick from the account dropdown.
 *
 * @param option - The chosen account, or null when it was cleared
 */
function onActorChange(option: ActorOption | null): void {
	emit('update:actor', option?.id ?? '')
}

/**
 * How long to wait after the last keystroke before querying the server.
 */
const SEARCH_DEBOUNCE_MS = 300

type PresetId = 'any' | 'today' | 'week' | 'month' | 'custom'

/**
 * Shape NcSelectUsers expects for each entry.
 */
interface ActorOption {
	id: string
	displayName: string
	user: string
}

const today = new Date()

const presets = computed(() => [
	{ id: 'any' as const, label: t('activity', 'Any time') },
	{ id: 'today' as const, label: t('activity', 'Today') },
	{ id: 'week' as const, label: t('activity', 'Last 7 days') },
	{ id: 'month' as const, label: t('activity', 'Last 30 days') },
	{ id: 'custom' as const, label: t('activity', 'Custom range') },
])

/**
 * Which preset the current range corresponds to.
 *
 * Kept as its own ref rather than derived, because "custom" has to stay
 * selected while the user is still picking dates and both bounds are null.
 */
const selectedPreset = ref<PresetId>(matchPreset())

/**
 * Map the incoming range back onto a preset.
 *
 * Rolling presets have an open upper bound, so a range with a `to` is always
 * a custom one.
 */
function matchPreset(): PresetId {
	if (props.from === null && props.to === null) {
		return 'any'
	}
	if (props.to === null && props.from !== null) {
		for (const [id, days] of [['today', 0], ['week', 6], ['month', 29]] as const) {
			if (props.from.getTime() === daysAgo(days).getTime()) {
				return id
			}
		}
	}
	return 'custom'
}

/**
 * Switch to a preset range.
 *
 * "Custom" keeps the current bounds so the pickers open on what is already
 * active instead of resetting the user's work.
 *
 * @param id - The preset to activate
 */
function applyPreset(id: PresetId): void {
	const wasSelected = selectedPreset.value === id
	selectedPreset.value = id
	if (id === 'custom') {
		// Keep whatever range is already active and let the user adjust it
		return
	}
	if (wasSelected) {
		// Re-emitting would hand the parent a fresh Date object for the same
		// day, which reads as a change and would refetch the whole stream
		return
	}
	emit('update:to', null)
	emit('update:from', id === 'any' ? null : daysAgo({ today: 0, week: 6, month: 29 }[id]))
}

const dateRangeLabel = computed(() => {
	const preset = presets.value.find((entry) => entry.id === selectedPreset.value)
	if (selectedPreset.value !== 'custom') {
		return preset?.label ?? t('activity', 'Any time')
	}
	if (props.from !== null && props.to !== null) {
		return t('activity', '{from} to {to}', {
			from: props.from.toLocaleDateString(),
			to: props.to.toLocaleDateString(),
		})
	}
	if (props.from !== null) {
		return t('activity', 'Since {from}', { from: props.from.toLocaleDateString() })
	}
	if (props.to !== null) {
		return t('activity', 'Until {to}', { to: props.to.toLocaleDateString() })
	}
	return t('activity', 'Custom range')
})

const hasActiveFilters = computed(() => props.search !== '' || props.from !== null || props.to !== null || props.actor !== '')

/**
 * Local, undebounced mirror of the search field so typing stays responsive.
 */
const searchInput = ref(props.search)

const searchHelperText = computed(() => {
	const term = searchInput.value.trim()
	if (term.length > 0 && term.length < MIN_SEARCH_TERM_LENGTH) {
		return t('activity', 'Enter at least {count} characters to search', { count: MIN_SEARCH_TERM_LENGTH })
	}
	return ''
})

/**
 * The last term handed to the parent.
 *
 * Tracked separately from `props.search` because the parent has not re-rendered
 * yet at the moment we emit, and comparing against the stale prop would make
 * the input watcher emit the same term a second time.
 */
let lastEmittedSearch = props.search

/**
 * Hand a term to the parent, skipping no-op updates.
 *
 * @param term - The normalised term, or an empty string for no search
 */
function emitSearch(term: string): void {
	if (term === lastEmittedSearch) {
		return
	}
	lastEmittedSearch = term
	emit('update:search', term)
}

const emitSearchDebounced = useDebounceFn((value: string) => {
	emitSearch(normalizeSearchTerm(value))
}, SEARCH_DEBOUNCE_MS)

watch(searchInput, (value) => {
	if (normalizeSearchTerm(value) === lastEmittedSearch) {
		// Nothing the backend would see differently, e.g. trailing whitespace
		// or a term that is still too short
		return
	}
	emitSearchDebounced(value)
})

// Reflect changes made elsewhere, such as the "Clear filters" action in the
// empty state, back into the input
watch(() => props.search, (value) => {
	lastEmittedSearch = value
	if (normalizeSearchTerm(searchInput.value) !== value) {
		searchInput.value = value
	}
})

// Re-derive the preset when the range is changed from outside the component
watch(() => [props.from, props.to], () => {
	if (selectedPreset.value === 'custom' && (props.from !== null || props.to !== null)) {
		return
	}
	selectedPreset.value = matchPreset()
})

/**
 * Drop the search term without touching the date range.
 */
function clearSearch(): void {
	searchInput.value = ''
	emitSearch('')
}

/**
 * Reset every criterion back to the unfiltered stream.
 */
function clearAll(): void {
	searchInput.value = ''
	selectedPreset.value = 'any'
	emitSearch('')
	emit('update:from', null)
	emit('update:to', null)
	emit('update:actor', '')
}
</script>

<style scoped lang="scss">
.activity-filter {
	// One height for every control in the row, so the search field, the account
	// select and the date button read as a single set
	--activity-filter-control-height: var(--default-clickable-area);
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-grid-baseline) * 2);
	// Top offset matches the app-navigation toggle's own `top`, so the first
	// row starts on the same line as it
	padding-block: var(--app-navigation-padding) calc(var(--default-grid-baseline) * 2);

	&__controls {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		// Give the row the app-navigation toggle's height so their centres agree
		min-height: var(--activity-filter-control-height);
		gap: calc(var(--default-grid-baseline) * 2);

		// These components are built for stacked forms and carry vertical
		// margins to match (the text field a top one, the select a bottom
		// one). Laid out in a row those margins are what knocks the controls
		// off a shared centre line, so drop them.
		:deep(.input-field),
		:deep(.v-select.select),
		:deep(.action-item) {
			margin: 0;
		}

		// The search field, the account select and the date button come from
		// three different components, each with its own intrinsic sizing, so
		// left alone they render at three different heights. Pin every outer
		// box to the standard clickable area instead of relying on their
		// defaults happening to agree.
		:deep(.input-field__main-wrapper),
		:deep(.button-vue),
		:deep(.vs__dropdown-toggle) {
			box-sizing: border-box;
			height: var(--activity-filter-control-height);
			min-height: var(--activity-filter-control-height);
		}

		:deep(.vs__dropdown-toggle) {
			// vue-select pads the toggle and then sizes its inner rows against
			// the full clickable area, which is what makes it the tall one.
			// Dropping the padding lets that inner content fit the pinned
			// height rather than overflow into the toggle's own scroll area.
			padding: 0;
		}
	}

	&__search {
		// Stay comfortably readable but let the date control sit beside it
		flex: 1 1 240px;
		min-width: 0;
	}

	&__actor {
		// Wide enough for a display name, but the search field wins the space
		flex: 0 1 200px;
		min-width: 150px;
	}

	&__range {
		flex: 0 0 auto;
	}

	&__custom-range {
		display: flex;
		flex-wrap: wrap;
		gap: calc(var(--default-grid-baseline) * 2);

		// Keep the two date inputs on the same height as the row above them
		:deep(.native-datetime-picker__input) {
			box-sizing: border-box;
			height: var(--activity-filter-control-height);
		}
	}

	&__date {
		flex: 1 1 180px;
		min-width: 0;
	}
}
</style>
