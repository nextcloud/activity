<!--
  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<li class="activity-entry comments-activity">
		<NcAvatar
			class="comments-activity__icon"
			:disable-menu="false"
			:user="authorId" />
		<div class="comments-activity__content">
			<NcRichText
				v-if="showPreviews"
				class="comments-activity__title"
				:text="subjectText"
				:arguments="subjectArguments" />
			<div v-else class="comments-activity__title">
				{{ authorDisplayName }}
			</div>
			<NcRichText class="comments-activity__subline" :text="messageText" :arguments="activity.messageRichObjects" />
		</div>
		<span class="hidden-visually">{{ activity.formattedDate }}</span>
		<NcDateTime
			class="comments-activity__date"
			data-testid="activity-date"
			:timestamp="timestamp"
			:ignore-seconds="true" />
	</li>
</template>

<script setup lang="ts">

import type ActivityModel from '../../models/ActivityModel.ts'

import { getCurrentUser } from '@nextcloud/auth'
import { translate as t } from '@nextcloud/l10n'
import { computed } from 'vue'
import NcAvatar from '@nextcloud/vue/components/NcAvatar'
import NcDateTime from '@nextcloud/vue/components/NcDateTime'
import NcRichText from '@nextcloud/vue/components/NcRichText'
import { mapRichObjectsToRichArguments } from '../../utils/richObjects.ts'

const props = defineProps<{
	activity: ActivityModel
	showPreviews: boolean
}>()

/**
 * User ID of the comment's author
 */
const authorId = computed(() => props.activity.subjectRichObjects?.author?.id ?? props.activity.user)
/**
 * Display name of the comment's author
 */
const authorDisplayName = computed(() => {
	if (props.activity.user === getCurrentUser()?.uid) {
		return t('activity', 'You commented')
	}

	return props.activity.subjectRichObjects?.author?.name ?? props.activity.user
})

/**
 * Timestamp of the activity as JS timestamp (ms)
 */
const timestamp = computed(() => props.activity.timestamp)

/**
 * The activity's messageRichTemplate. Fallback to message if messageRichTemplate does not exists
 */
const messageText = computed(() => props.activity.messageRichTemplate || props.activity.message || t('activity', 'Message deleted by author'))

/**
 * The activity's subjectRichTemplate. Fallback to subject if subjectRichTemplate does not exists
 */
const subjectText = computed(() => props.activity.subjectRichTemplate || props.activity.subject)

const subjectArguments = computed(() => mapRichObjectsToRichArguments(props.activity.subjectRichObjects))
</script>

<style scoped lang="scss">
.comments-activity {
	display: flex;
	flex-wrap: nowrap;
	align-items: flex-start;
	width: 100%;
	min-height: 32px;
	padding: 8px 0;
	gap: 8px;

	&__icon {
		flex: 0 0 fit-content;
	}

	&__content {
		flex: 1 0;
		display: flex;
		flex-direction: column;
		width: 100%;
	}

	&__title {
		color: var(--color-text-maxcontrast);
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}

	&__date {
		color: var(--color-text-maxcontrast);
	}
}
</style>
