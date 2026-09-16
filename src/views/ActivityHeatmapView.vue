<!--
  - SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<NcAppContent class="activity-heatmap-view">
		<div class="activity-heatmap-view__inner">
			<h1 class="activity-heatmap-view__heading">
				{{ t('activity', 'Activity over time') }}
			</h1>
			<p class="activity-heatmap-view__intro">
				{{ t('activity', 'Pick a day to see what happened, or hold Shift and pick a second day for a period.') }}
			</p>

			<ActivityHeatmap
				v-model:from="dateFrom"
				v-model:to="dateTo"
				:filter="filter" />

			<div class="activity-heatmap-view__actions">
				<NcButton
					:disabled="!hasSelection"
					variant="primary"
					@click="showSelection">
					<template #icon>
						<IconFormatListBulleted :size="20" />
					</template>
					{{ showSelectionLabel }}
				</NcButton>
				<NcButton
					v-if="hasSelection"
					variant="tertiary"
					@click="clearSelection">
					{{ t('activity', 'Clear selection') }}
				</NcButton>
			</div>
		</div>
	</NcAppContent>
</template>

<script setup lang="ts">
import { translate as t } from '@nextcloud/l10n'
import moment from '@nextcloud/moment'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import NcButton from '@nextcloud/vue/components/NcButton'
import IconFormatListBulleted from 'vue-material-design-icons/FormatListBulleted.vue'
import ActivityHeatmap from '../components/ActivityHeatmap.vue'
import { formatDateParameter } from '../utils/dateRange.ts'

/**
 * Which stream the counts are taken from.
 *
 * Fixed to the unfiltered stream: this view is an overview, and the reader has
 * not chosen a type filter to inherit here.
 */
const filter = 'all'

const router = useRouter()

const dateFrom = ref<Date | null>(null)
const dateTo = ref<Date | null>(null)

const hasSelection = computed(() => dateFrom.value !== null || dateTo.value !== null)

const showSelectionLabel = computed(() => {
	if (dateFrom.value === null || dateTo.value === null) {
		return t('activity', 'Show these activities')
	}
	if (formatDateParameter(dateFrom.value) === formatDateParameter(dateTo.value)) {
		return t('activity', 'Show {date}', { date: moment(dateFrom.value).format('LL') })
	}
	return t('activity', 'Show {from} to {to}', {
		from: moment(dateFrom.value).format('LL'),
		to: moment(dateTo.value).format('LL'),
	})
})

/**
 * Drop the selected period without leaving the overview
 */
function clearSelection(): void {
	dateFrom.value = null
	dateTo.value = null
}

/**
 * Open the stream restricted to the selected period.
 *
 * The range travels as query parameters because the stream already restores its
 * filters from the URL, which also makes the resulting view linkable.
 */
function showSelection(): void {
	const query: Record<string, string> = {}
	const from = formatDateParameter(dateFrom.value)
	const to = formatDateParameter(dateTo.value)
	if (from !== '') {
		query.from = from
	}
	if (to !== '') {
		query.to = to
	}
	router.push({ path: `/${filter}`, query }).catch(() => {})
}
</script>

<style scoped lang="scss">
.activity-heatmap-view {
	overflow: auto;

	&__inner {
		// Wider than the stream's reading column: a year of weeks needs the room,
		// and there is no prose here to keep narrow
		box-sizing: border-box;
		max-width: 1100px;
		margin: 0 auto;
		padding: calc(var(--default-grid-baseline) * 4) calc(var(--default-grid-baseline) * 5);
		display: flex;
		flex-direction: column;
		gap: calc(var(--default-grid-baseline) * 3);
	}

	&__heading {
		margin: 0;
		font-size: 20px;
		font-weight: bold;
	}

	&__intro {
		margin: 0;
		color: var(--color-text-maxcontrast);
	}

	&__actions {
		display: flex;
		align-items: center;
		// Both actions sit together below the grid: they only become meaningful
		// once something is selected, so they follow it rather than lead it
		gap: calc(var(--default-grid-baseline) * 2);
		margin-block-start: var(--default-grid-baseline);
	}
}
</style>
