<!--
  - @copyright 2023 Ferdinand Thiessen <opensource@fthiessen.de>
  -
  - @author Ferdinand Thiessen <opensource@fthiessen.de>
  -
  - @license AGPL-3.0-or-later
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -->
<template>
	<li class="activity-entry comments-activity">
		<NcAvatar class="comments-activity__icon"
			:disable-menu="false"
			:user="authorId" />
		<div class="comments-activity__content">
			<NcRichText v-if="showPreviews"
				class="comments-activity__title"
				:text="subjectText"
				:arguments="subjectArguments" />
			<div v-else class="comments-activity__title">
				{{ authorDisplayName }}
			</div>
			<NcRichText class="comments-activity__subline" :text="messageText" :arguments="activity.messageRichObjects" />
		</div>
		<span class="hidden-visually">{{ activity.formattedDate }}</span>
		<NcDateTime class="comments-activity__date"
			data-testid="activity-date"
			:timestamp="timestamp"
			:ignore-seconds="true" />
	</li>
</template>

<script setup lang="ts">
import type ActivityModel from '../../models/ActivityModel'
import NcAvatar from '@nextcloud/vue/dist/Components/NcAvatar.js'
import NcDateTime from '@nextcloud/vue/dist/Components/NcDateTime.js'
import NcRichText from '@nextcloud/vue/dist/Components/NcRichText.js'
import { getCurrentUser } from '@nextcloud/auth'
import { computed } from 'vue'
import { translate as t } from '@nextcloud/l10n'
import { mapRichObjectsToRichArguments } from '../../utils/richObjects'

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
const timestamp = computed(() => props.activity.timestamp * 1000)

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
