<!--
  - @copyright 2020 Christoph Wurst <christoph@winzerhof-wurst.at>
  -
  - @author 2020 Christoph Wurst <christoph@winzerhof-wurst.at>
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
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -->

<template>
	<NoActivities v-if="activities.length === 0" />
	<Stream v-else :activities="activities" />
</template>

<script>
import {findAll} from '../ActivityService'
import NoActivities from "../components/NoActivities";
import Stream from "../components/Stream";

export default {
	name: 'FilteredStream',
	components: {Stream, NoActivities},
	data () {
		return {
			activities: [],
		}
	},
	async mounted () {
		this.activities = await findAll(this.$route.params.filter || 'all')
	},
}
</script>

<style scoped>

</style>
