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

    <div v-if="loading && !activities.length" id="loading_activities" class="icon-loading"></div>

    <div v-if="activities.length" id="no_more_activities">
      <template v-if="reachedEnd">{{ t('activity', 'No more events to load') }}</template>
      <template v-else-if="loading"><div class="icon-loading"></div></template>
    </div>
  </div>
</template>

<script>
  import api from '../store/api';
  import Vue from 'vue';
  import activity from '../components/activity';
  // import VueLocalStorage from 'vue-localstorage';
  // Vue.use(VueLocalStorage);

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

    watch: {
      '$route' (to, from) {
        this.loading = true;
        this.activities = [];
        this.lastGivenId = 0;
        this.firstKnownId = 0;
        this.ignoreScroll = 0;
        this.reachedEnd = false;
        this.loadActivities(false, true);
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
        this.loading = true;
        var url = OC.linkToOCS('apps/activity/api/v2/activity', 2) + this.filter + '?previews=true';
        if (lookAHead) {
          url += '&since=' + this.firstKnownId;
          url += '&sort=asc';
        } else if (!isReset) {
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
      activity
    },

    mounted() {
      this.loadActivities();
    }
  }
</script>
