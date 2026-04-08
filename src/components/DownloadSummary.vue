<!--
  - SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div v-if="totalCount > 0" class="download-summary">
		<span class="download-summary__icon">
			<NcIconSvgWrapper :svg="downloadSVG" :size="20" />
		</span>
		<span class="download-summary__text">{{ summaryText }}</span>
	</div>
</template>

<script lang="ts">
import downloadSVG from '@mdi/svg/svg/download-circle.svg?raw'
import axios from '@nextcloud/axios'
import { translate as t, n } from '@nextcloud/l10n'
import { generateOcsUrl } from '@nextcloud/router'
import { defineComponent } from 'vue'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import logger from '../utils/logger.ts'

export default defineComponent({
	name: 'DownloadSummary',

	components: {
		NcIconSvgWrapper,
	},

	props: {
		fileId: {
			type: Number,
			required: true,
		},
	},

	data() {
		return {
			totalCount: 0,
			monthlyCount: 0,
			downloadSVG,
		}
	},

	computed: {
		summaryText(): string {
			if (this.monthlyCount > 0 && this.monthlyCount < this.totalCount) {
				return n(
					'activity',
					'Downloaded %n time (%s in the last 30 days)',
					'Downloaded %n times (%s in the last 30 days)',
					this.totalCount,
					[String(this.monthlyCount)],
				)
			}
			return n(
				'activity',
				'Downloaded %n time',
				'Downloaded %n times',
				this.totalCount,
			)
		},
	},

	watch: {
		fileId: {
			immediate: true,
			handler() {
				this.fetchCounts()
			},
		},
	},

	methods: {
		async fetchCounts() {
			if (!this.fileId) {
				return
			}
			this.totalCount = 0
			this.monthlyCount = 0
			try {
				const response = await axios.get(generateOcsUrl('apps/activity/api/v2/activity/downloads/count'), {
					params: { format: 'json', object_type: 'files', object_id: this.fileId },
				})
				this.totalCount = response.data.ocs.data.total
				this.monthlyCount = response.data.ocs.data.last30d
			} catch (error) {
				logger.error('Failed to fetch download counts', { error })
			}
		},

		t,
		n,
	},
})
</script>

<style scoped lang="scss">
.download-summary {
	display: flex;
	align-items: flex-start;
	padding: 8px 0;
	margin-bottom: calc(var(--default-grid-baseline) * 2);
	color: var(--color-text-maxcontrast);

	&__icon {
		flex-shrink: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 20px;
		height: 20px;
		margin-top: 2px;
		opacity: 0.5;
	}

	&__text {
		padding: 0 5px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
}
</style>
