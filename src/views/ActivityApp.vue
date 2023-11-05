<template>
	<NcContent app-name="activity">
		<router-view name="navigation" />
		<router-view />
	</NcContent>
</template>

<script setup lang="ts">
import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'
</script>
