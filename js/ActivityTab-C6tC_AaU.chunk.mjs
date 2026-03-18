(function() {
  "use strict";
  try {
    if (typeof document != "undefined") {
      var elementStyle = document.createElement("style");
      elementStyle.appendChild(document.createTextNode(".activity[data-v-c28f4480] {\n  display: flex;\n  flex-direction: column;\n  overflow: hidden;\n  height: 100%;\n}\n.activity__actions[data-v-c28f4480] {\n  display: flex;\n  flex-direction: column;\n  width: 100%;\n}\n.activity__list[data-v-c28f4480] {\n  flex-grow: 1;\n  overflow: scroll;\n}\n.activity__empty-content[data-v-c28f4480] {\n  height: 100%;\n}\n[data-v-c28f4480] .empty-content__icon span {\n  background-size: 64px;\n  width: 64px;\n  height: 64px;\n}"));
      document.head.appendChild(elementStyle);
    }
  } catch (e) {
    console.error("vite-plugin-css-injected-by-js", e);
  }
})();
const appName = "activity";
const appVersion = "7.0.0-dev.0";
import { l as lightningBoltSVG } from "./activity-sidebar.mjs";
import { c as _export_sfc, n as NcLoadingIcon, b as NcIconSvgWrapper, j as cancelableClient, k as generateOcsUrl } from "./NcCheckboxRadioSwitch-BMsPx74L-CGnJCDi8.chunk.mjs";
import { d as defineComponent, x as onMounted, r as ref, B as getCurrentInstance, y as onBeforeUnmount, a as createElementBlock, b as openBlock, M as translate, l as nextTick, m as resolveComponent, E as createCommentVNode, e as createBlock, i as withCtx, h as createVNode, F as Fragment, N as renderList, n as normalizeClass } from "./translation-DoG5ZELJ-BRHGJcXa.chunk.mjs";
import { N as NcEmptyContent, A as ActivityComponent, h as ActivityModel } from "./ActivityComponent-CxDKYEWx.chunk.mjs";
import { g as getActivityFilters, a as getAdditionalEntries, b as getSidebarActions } from "./api-DR5kSjBu.chunk.mjs";
import { l as logger } from "./logger-uIIWoPgu.chunk.mjs";
import "./preload-helper-BNx4Cq8O.chunk.mjs";
import "./index-C6VBhB33.chunk.mjs";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ActivitySidebarPlugin",
  props: {
    plugin: { type: Object, required: true },
    node: { type: null, required: true }
  },
  emits: ["reload-activities"],
  setup(__props, { expose: __expose, emit: __emit }) {
    __expose();
    const props = __props;
    const emit = __emit;
    const attachTarget = ref();
    onMounted(() => props.plugin.mount(attachTarget.value, {
      node: props.node,
      context: getCurrentInstance()?.proxy ?? void 0,
      reload: () => emit("reload-activities")
    }));
    onBeforeUnmount(() => props.plugin.unmount());
    const __returned__ = { props, emit, attachTarget };
    Object.defineProperty(__returned__, "__isScriptSetup", { enumerable: false, value: true });
    return __returned__;
  }
});
const _hoisted_1$1 = { ref: "attachTarget" };
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock(
    "div",
    _hoisted_1$1,
    null,
    512
    /* NEED_PATCH */
  );
}
const ActivitySidebarPlugin = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render$1], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/components/ActivitySidebarPlugin.vue"]]);
const ActivityTab$1 = defineComponent({
  name: "ActivityTab",
  components: {
    ActivityComponent,
    NcEmptyContent,
    NcIconSvgWrapper,
    NcLoadingIcon,
    ActivitySidebarPlugin
  },
  props: {
    /**
     * The node currently displayed in the sidebar
     */
    node: {
      type: Object,
      required: true
    },
    /**
     * The folder shown in the files app
     */
    // eslint-disable-next-line vue/no-unused-properties
    folder: {
      type: Object,
      required: false,
      default: void 0
    },
    /**
     * The view shown in the files app
     */
    // eslint-disable-next-line vue/no-unused-properties
    view: {
      type: Object,
      required: false,
      default: void 0
    }
  },
  expose: ["update"],
  data() {
    return {
      error: "",
      loading: true,
      activities: [],
      lightningBoltSVG,
      sidebarPlugins: []
    };
  },
  watch: {
    node: {
      immediate: true,
      async handler() {
        await this.update();
      }
    }
  },
  async mounted() {
    if (this.node) {
      await this.update();
    }
  },
  methods: {
    /**
     * Update current view and fetch new activities
     */
    async update() {
      this.sidebarPlugins = [];
      const sidebarPlugins = getSidebarActions();
      if (sidebarPlugins.length > 0) {
        nextTick(() => {
          this.sidebarPlugins = sidebarPlugins;
        });
      }
      this.resetState();
      await this.getActivities();
    },
    /**
     * Get the existing activities
     */
    async getActivities() {
      try {
        this.loading = true;
        const activities = await this.processActivities(await this.loadRealActivities());
        const otherEntries = await getAdditionalEntries({ node: this.node });
        this.activities = [...activities, ...otherEntries].sort((a, b) => b.timestamp - a.timestamp);
      } catch (error) {
        this.error = translate("activity", "Unable to load the activity list");
        logger.error("Error loading the activity list", { error });
      } finally {
        this.loading = false;
      }
    },
    /**
     * Reset the current view to its default state
     */
    resetState() {
      this.loading = true;
      this.error = "";
      this.activities = [];
    },
    /**
     * Load activites from API
     */
    async loadRealActivities() {
      try {
        const { data } = await cancelableClient.get(
          generateOcsUrl("apps/activity/api/v2/activity/filter"),
          {
            params: {
              format: "json",
              object_type: "files",
              object_id: this.node.fileid
            }
          }
        );
        return data.ocs.data;
      } catch (error) {
        if (error.response !== void 0 && error.response.status === 304) {
          return [];
        }
        throw error;
      }
    },
    /**
     * Process the API response activities and apply filter
     *
     * @param activities the activites
     */
    processActivities(activities) {
      activities = activities.map((activity) => new ActivityModel(activity));
      logger.debug(`Processed ${activities.length} activity(ies)`, {
        activities,
        node: this.node
      });
      const filters = getActivityFilters();
      return activities.filter((activity) => !filters || filters.every((filter) => filter(activity)));
    },
    t: translate
  }
});
const _hoisted_1 = {
  key: 0,
  class: "activity__actions"
};
const _hoisted_2 = {
  key: 3,
  class: "activity__list"
};
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcIconSvgWrapper = resolveComponent("NcIconSvgWrapper");
  const _component_NcEmptyContent = resolveComponent("NcEmptyContent");
  const _component_ActivitySidebarPlugin = resolveComponent("ActivitySidebarPlugin");
  const _component_NcLoadingIcon = resolveComponent("NcLoadingIcon");
  const _component_ActivityComponent = resolveComponent("ActivityComponent");
  return openBlock(), createElementBlock(
    "div",
    {
      class: normalizeClass([{ "icon-loading": _ctx.loading }, "activity"])
    },
    [
      createCommentVNode(" error message "),
      _ctx.error || !_ctx.node ? (openBlock(), createBlock(_component_NcEmptyContent, {
        key: 0,
        name: _ctx.error
      }, {
        icon: withCtx(() => [
          createVNode(_component_NcIconSvgWrapper, { svg: _ctx.lightningBoltSVG }, null, 8, ["svg"])
        ]),
        _: 1
        /* STABLE */
      }, 8, ["name"])) : (openBlock(), createElementBlock(
        Fragment,
        { key: 1 },
        [
          createCommentVNode(" activities actions "),
          _ctx.sidebarPlugins.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_1, [
            (openBlock(true), createElementBlock(
              Fragment,
              null,
              renderList(_ctx.sidebarPlugins, (plugin, index) => {
                return openBlock(), createBlock(_component_ActivitySidebarPlugin, {
                  key: index,
                  plugin,
                  node: _ctx.node,
                  onReloadActivities: _cache[0] || (_cache[0] = ($event) => _ctx.getActivities())
                }, null, 8, ["plugin", "node"]);
              }),
              128
              /* KEYED_FRAGMENT */
            ))
          ])) : createCommentVNode("v-if", true),
          createCommentVNode(" activities content "),
          _ctx.loading ? (openBlock(), createBlock(_component_NcEmptyContent, {
            key: 1,
            class: "activity__empty-content",
            name: _ctx.t("activity", "Loading activities")
          }, {
            icon: withCtx(() => [
              createVNode(_component_NcLoadingIcon)
            ]),
            _: 1
            /* STABLE */
          }, 8, ["name"])) : _ctx.activities.length === 0 ? (openBlock(), createBlock(_component_NcEmptyContent, {
            key: 2,
            class: "activity__empty-content",
            name: _ctx.t("activity", "No activity yet")
          }, {
            icon: withCtx(() => [
              createVNode(_component_NcIconSvgWrapper, { svg: _ctx.lightningBoltSVG }, null, 8, ["svg"])
            ]),
            _: 1
            /* STABLE */
          }, 8, ["name"])) : (openBlock(), createElementBlock("ul", _hoisted_2, [
            (openBlock(true), createElementBlock(
              Fragment,
              null,
              renderList(_ctx.activities, (activity) => {
                return openBlock(), createBlock(_component_ActivityComponent, {
                  key: activity.id,
                  activity,
                  "show-previews": false,
                  onReload: _cache[1] || (_cache[1] = ($event) => _ctx.getActivities())
                }, null, 8, ["activity"]);
              }),
              128
              /* KEYED_FRAGMENT */
            ))
          ]))
        ],
        64
        /* STABLE_FRAGMENT */
      ))
    ],
    2
    /* CLASS */
  );
}
const ActivityTab = /* @__PURE__ */ _export_sfc(ActivityTab$1, [["render", _sfc_render], ["__scopeId", "data-v-c28f4480"], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/ActivityTab.vue"]]);
export {
  ActivityTab as default
};
//# sourceMappingURL=ActivityTab-C6tC_AaU.chunk.mjs.map
