<!--
 - @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 -
 - @author Louis Chemineau <louis@chmn.me>
 -
 - @license GNU AGPL version 3 or any later version
 -
 - This program is free software: you can redistribute it and/or modify
 - it under the terms of the GNU Affero General Public License as
 - published by the Free Software Foundation, either version 3 of the
 - License, or (at your option) any later version.
 -
 - This program is distributed in the hope that it will be useful,
 - but WITHOUT ANY WARRANTY; without even the implied warranty of
 - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 - GNU Affero General Public License for more details.
 -
 - You should have received a copy of the GNU Affero General Public License
 - along with this program. If not, see <http://www.gnu.org/licenses/>.
 -
 -->

<template>
	<Content app-name="activity">
		<AppNavigation>
			<template #list>
				<AppNavigationItem :to="{name: 'timeline'}"
					class="app-navigation__activity"
					:title="t('activity', 'All activities')"
					icon="activity"
					exact />
				<AppNavigationItem to="?filter=self" :title="t('activity', 'By you')" icon="icon-user" />
				<AppNavigationItem to="?filter=by" :title="t('activity', 'By others')" icon="icon-group" />
				<AppNavigationItem to="?filter=files_favorites" :title="t('activity', 'Favorites')" icon="icon-favorite" />
				<AppNavigationItem to="?filter=files" :title="t('activity', 'File changes')" icon="icon-folder" />
				<AppNavigationItem to="?filter=files_sharing" :title="t('activity', 'File shares')" icon="icon-share" />
				<AppNavigationItem to="?filter=calendar" :title="t('activity', 'Calendar')" icon="icon-calendar" />
				<AppNavigationItem to="?filter=calendar_todo" :title="t('activity', 'Todo')" icon="icon-todo" />
				<AppNavigationItem to="?filter=comments" :title="t('activity', 'Comments')" icon="icon-comments" />
				<AppNavigationItem to="?filter=contacts" :title="t('activity', 'Contacts')" icon="icon-contacts" />
			</template>
			<template #footer>
				<AppNavigationSettings :title="t('activity', 'Settings')">
					<AppNavigationItem to="?filter=contacts" :title="t('activity', 'Enable RSS Feed')" icon="icon-contacts" />
				</AppNavigationSettings>
			</template>
		</AppNavigation>

		<!-- <div id="app-content">
			<div id="emptycontent" class="hidden">
				<div class="icon-activity" />
				<h2><?php p($l->t('No activity yet')); ?></h2>
				<p><?php p($l->t('This stream will show events like additions, changes & shares')); ?></p>
			</div>

			<div id="container" data-activity-filter="<?php p($_['filter']) ?>" data-avatars-enabled="<?php p($_['avatars']) ?>" />

			<div id="loading_activities" class="icon-loading" />

			<div id="no_more_activities" class="hidden">
				<?php p($l->t('No more events to load')) ?>
			</div>
		</div> -->

		<AppContent :class="{ 'icon-loading': loading }">
			<!--  -->
		</AppContent>
	</Content>
</template>

<script>
// import { generateUrl } from '@nextcloud/router'

import Content from '@nextcloud/vue/dist/Components/Content'
import AppContent from '@nextcloud/vue/dist/Components/AppContent'
import AppNavigation from '@nextcloud/vue/dist/Components/AppNavigation'
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import AppNavigationSettings from '@nextcloud/vue/dist/Components/AppNavigationSettings'

export default {
	name: 'ActivityApp',
	components: {
		Content,
		AppContent,
		AppNavigation,
		AppNavigationItem,
		AppNavigationSettings,
	},
	data() {
		return {
			loading: true,
		}
	},
}
</script>
<style lang="scss" scoped>
.app-content {
	display: flex;
	flex-grow: 1;
	flex-direction: column;
	align-content: space-between;
}

.app-navigation__photos::v-deep .app-navigation-entry-icon.icon-photos {
	background-size: 20px;
}
</style>
