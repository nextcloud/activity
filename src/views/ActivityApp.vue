<template>
	<NcContent app-name="activity">
		<ActivityAppNavigation />
		<NcAppContent v-if="hasNoActivity">
			<NcEmptyContent style="height: 100%;"
				:title="t('activity', 'No activity yet')"
				:description="t('activity', 'This stream will show events like additions, changes & shares')">
				<template #icon>
					<NcIconSvgWrapper :svg="IconLogo" />
				</template>
			</NcEmptyContent>
		</NcAppContent>
		<router-view v-else />
	</NcContent>
</template>

<script setup lang="ts">
import ActivityAppNavigation from '../components/ActivityAppNavigation.vue'
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'
import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import NcIconSvgWrapper from '@nextcloud/vue/dist/Components/NcIconSvgWrapper.js'

import IconLogo from '../../img/activity-dark.svg?raw'

import { translate as t } from '@nextcloud/l10n'

const hasNoActivity = true
</script>
