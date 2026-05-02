<!--
  - SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div>
		<NcSettingsSection :name="t('activity', 'Notification')">
			<NcCheckboxRadioSwitch
				type="checkbox"
				v-model="emailEnabled"
				@update:model-value="toggleEmailEnabled({ emailEnabled: $event })">
				{{ t('activity', 'Enable notification emails') }}
			</NcCheckboxRadioSwitch>
		</NcSettingsSection>

		<NcSettingsSection
			:name="t('activity', 'Database')"
			:description="databaseDescription">
			<table class="activity-database-table">
				<thead>
					<tr>
						<th>{{ t('activity', 'Table') }}</th>
						<th class="activity-database-table__size">
							{{ t('activity', 'Size on disk') }}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="row in tableRows" :key="row.table">
						<td><code>{{ row.table }}</code></td>
						<td class="activity-database-table__size">{{ row.formatted }}</td>
					</tr>
					<tr v-if="totalBytes !== null" class="activity-database-table__total">
						<th>{{ t('activity', 'Total') }}</th>
						<th class="activity-database-table__size">{{ formatBytes(totalBytes) }}</th>
					</tr>
				</tbody>
			</table>
			<p v-if="!sizesAvailable" class="activity-database-table__hint">
				{{ t('activity', 'Per-table size is only available on MySQL/MariaDB and PostgreSQL.') }}
			</p>

			<NcNoteCard
				v-if="retentionSuggestion"
				type="warning"
				class="activity-database-suggestion">
				<p>{{ retentionSuggestionTitle }}</p>
				<p>{{ retentionSuggestionDetail }}</p>
				<pre class="activity-database-suggestion__snippet">{{ retentionSuggestionSnippet }}</pre>
				<template #actions>
					<NcButton @click="copySuggestionSnippet">
						<template #icon><IconContentCopy :size="16" /></template>
						{{ t('activity', 'Copy config snippet') }}
					</NcButton>
				</template>
			</NcNoteCard>
		</NcSettingsSection>
	</div>
</template>

<script>
import { loadState } from '@nextcloud/initial-state'
import { formatFileSize } from '@nextcloud/files'
import { t } from '@nextcloud/l10n'
import { showSuccess, showError } from '@nextcloud/dialogs'
import { mapActions, mapState } from 'vuex'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcCheckboxRadioSwitch from '@nextcloud/vue/components/NcCheckboxRadioSwitch'
import NcNoteCard from '@nextcloud/vue/components/NcNoteCard'
import NcSettingsSection from '@nextcloud/vue/components/NcSettingsSection'
import IconContentCopy from 'vue-material-design-icons/ContentCopy.vue'

const databaseStats = loadState('activity', 'database_stats', {
	dedicated_connection: false,
	tables: {},
	retention_suggestion: null,
})

export default {
	name: 'AdminSettings',
	components: {
		IconContentCopy,
		NcButton,
		NcCheckboxRadioSwitch,
		NcNoteCard,
		NcSettingsSection,
	},

	data() {
		return {
			databaseStats,
		}
	},

	computed: {
		...mapState({
			emailEnabled: 'emailEnabled',
		}),

		settingDescription() {
			if (this.emailEnabled) {
				return t('activity', 'Choose for which activities you want to get an email or push notification.')
			} else {
				return t('activity', 'Choose for which activities you want to get a push notification.')
			}
		},

		tableRows() {
			return Object.entries(this.databaseStats.tables).map(([table, bytes]) => ({
				table,
				bytes,
				formatted: bytes === null ? t('activity', 'unavailable') : this.formatBytes(bytes),
			}))
		},

		sizesAvailable() {
			return Object.values(this.databaseStats.tables).some((b) => b !== null)
		},

		totalBytes() {
			const values = Object.values(this.databaseStats.tables).filter((b) => b !== null)
			return values.length === 0 ? null : values.reduce((a, b) => a + b, 0)
		},

		databaseDescription() {
			return this.databaseStats.dedicated_connection
				? t('activity', 'Activity is stored in a dedicated database (configured via activity_db* keys in config.php).')
				: t('activity', 'Activity is stored in the main Nextcloud database.')
		},

		retentionSuggestion() {
			return this.databaseStats.retention_suggestion
		},

		retentionSuggestionTitle() {
			const s = this.retentionSuggestion
			return t('activity', 'Activity tables are using {size} on disk.', {
				size: this.formatBytes(s.total_bytes),
			})
		},

		retentionSuggestionDetail() {
			const s = this.retentionSuggestion
			return t(
				'activity',
				'Consider lowering retention from {current} days to {suggested} days. Older entries are removed by the daily expire job; nothing is deleted until that job runs.',
				{ current: s.current_days, suggested: s.suggested_days },
			)
		},

		retentionSuggestionSnippet() {
			const s = this.retentionSuggestion
			return `'${s.config_key}' => ${s.suggested_days},`
		},
	},

	mounted() {
		this.setEndpoint({ endpoint: '/apps/activity/settings/admin' })
	},

	methods: {
		...mapActions([
			'setEndpoint',
			'toggleEmailEnabled',
		]),

		formatBytes(bytes) {
			return formatFileSize(bytes, true)
		},

		async copySuggestionSnippet() {
			try {
				await navigator.clipboard.writeText(this.retentionSuggestionSnippet)
				showSuccess(t('activity', 'Copied. Paste into config/config.php.'))
			} catch (e) {
				showError(t('activity', 'Could not copy to clipboard.'))
			}
		},

		t,
	},
}

</script>

<style lang="scss" scoped>
.activity-database-table {
	border-collapse: collapse;
	margin-top: 8px;

	th, td {
		padding: 4px 12px 4px 0;
		text-align: start;
	}

	&__size {
		text-align: end !important;
		font-variant-numeric: tabular-nums;
		min-width: 8em;
	}

	&__total {
		border-top: 1px solid var(--color-border);
	}

	&__hint {
		margin-top: 8px;
		color: var(--color-text-maxcontrast);
	}
}

.activity-database-suggestion {
	margin-top: 16px;

	&__snippet {
		margin-top: 8px;
		padding: 8px 12px;
		border-radius: var(--border-radius);
		background: var(--color-background-dark);
		font-family: var(--font-face-monospace, monospace);
		font-size: 0.9em;
		white-space: pre;
	}
}
</style>
