<template>
  <div id="app-content" @scroll="onScroll">
    <div id="emptycontent" v-if="!loading && !activities.length">
      <div class="icon-activity"></div>
      <h2>{{ t('activity', 'No activity yet') }}</h2>
      <p>{{ t('activity', 'This stream will show events like additions, changes & shares') }}</p>
    </div>

    <div id="container">
      <activity v-for="(a, index) in activities" v-bind="a" :index="index" :key="a.activity_id" :activities="activities"></activity>
    </div>

    <div v-if="loading" id="loading_activities" class="icon-loading"></div>

    <div v-if="reachedEnd && activities.length" id="no_more_activities">{{ t('activity', 'No more events to load') }}</div>
  </div>
</template>

<script>
	import api from '../store/api';

  export default {
    name: 'ActivityList',

    data() {
      return {
        /** @type {boolean} */
        loading: true,

        /** @type {boolean} */
        reachedEnd: false,

        /** @type {int} */
        lastGivenId: 0,

        /** @type {int} */
        firstKnownId: 0,

        /** @type {array} */
        activities: []
      };
    },

    computed: {
      filter() {
        return this.$route.params.filter || 'all';
      }
    },

    methods: {

      onScroll() {
        if (!this.reachedEnd && this.ignoreScroll <= 0
        ) { // TODO && this.$content.scrollTop() + this.$content.height() > this.$container.height() - 100) {
          this.loadActivities(false, false);
        }
      },

      /**
       * Navigation data is only loaded once on mount
       * @param {boolean} lookAHead
       * @param {boolean} isReset
       */
      loadActivities(lookAHead, isReset) {
        this.ignoreScroll = 1;
        var url = OC.linkToOCS('apps/activity/api/v2/activity', 2) + this.filter + '?previews=true';
        if (lookAHead) {
          url += '&since=' + this.firstKnownId;
          url += '&sort=asc';
        } else {
          url += '&since=' + this.lastGivenId;
        }

        api.get(url).then((response) => {
          this._saveHeaders(response.headers, isReset, lookAHead);
          this.loading = false;
          this.ignoreScroll--;

          if (response.data.ocs.data) {
            this.activities = this.activities.concat(response.data.ocs.data);
          }
        }).catch((err) => {
          this._saveHeaders(err.response.headers, isReset, lookAHead);
          this.loading = false;
          this.ignoreScroll--;

          if (err.response.status === 304 && !lookAHead) {
            this.reachedEnd = true;
          }
        });
      },

      /**
       * Read the X-Activity-First-Known and X-Activity-Last-Given headers
       * @param {string[]} headers
       * @param {boolean} reset
       * @param {boolean} lookAHead
       */
      _saveHeaders(headers, reset, lookAHead) {
        if (reset && typeof headers['x-activity-first-known'] !== 'undefined') {
          this.firstKnownId = parseInt(headers['x-activity-first-known'], 10);
        }

        if (typeof headers['x-activity-last-given'] !== 'undefined') {
          if (lookAHead) {
            this.firstKnownId = parseInt(headers['x-activity-last-given'], 10);
          } else {
            this.lastGivenId = parseInt(headers['x-activity-last-given'], 10);
          }
        }
      }
    },

    components: {
      'activity': require('../components/activity.vue')
    },

    mounted() {
      this.loadActivities();
    }
  }
</script>
