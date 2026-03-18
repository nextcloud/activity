const appName = "activity";
const appVersion = "7.0.0-dev.0";
import { M as translate, m as resolveComponent, e as createBlock, b as openBlock, i as withCtx, h as createVNode, j as createTextVNode, t as toDisplayString, X as createApp } from "./translation-DoG5ZELJ-BRHGJcXa.chunk.mjs";
import { m as mapActions, a as mapState, N as NcSettingsSection, A as ActivityGrid, s as store } from "./settings-store-RZiqWfNg.chunk.mjs";
import { c as _export_sfc, B as NcCheckboxRadioSwitch } from "./NcCheckboxRadioSwitch-BMsPx74L-CGnJCDi8.chunk.mjs";
import "./index-C6VBhB33.chunk.mjs";
import "./index-C1xmmKTZ-BecyqZV8.chunk.mjs";
import "./logger-uIIWoPgu.chunk.mjs";
const _sfc_main$1 = {
  name: "AdminSettings",
  components: {
    NcCheckboxRadioSwitch,
    NcSettingsSection
  },
  computed: {
    ...mapState({
      emailEnabled: "emailEnabled"
    }),
    settingDescription() {
      if (this.emailEnabled) {
        return translate("activity", "Choose for which activities you want to get an email or push notification.");
      } else {
        return translate("activity", "Choose for which activities you want to get a push notification.");
      }
    }
  },
  mounted() {
    this.setEndpoint({ endpoint: "/apps/activity/settings/admin" });
  },
  methods: {
    ...mapActions([
      "setEndpoint",
      "toggleEmailEnabled"
    ]),
    t: translate
  }
};
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcCheckboxRadioSwitch = resolveComponent("NcCheckboxRadioSwitch");
  const _component_NcSettingsSection = resolveComponent("NcSettingsSection");
  return openBlock(), createBlock(_component_NcSettingsSection, {
    name: $options.t("activity", "Notification")
  }, {
    default: withCtx(() => [
      createVNode(_component_NcCheckboxRadioSwitch, {
        type: "checkbox",
        modelValue: _ctx.emailEnabled,
        "onUpdate:modelValue": [
          _cache[0] || (_cache[0] = ($event) => _ctx.emailEnabled = $event),
          _cache[1] || (_cache[1] = ($event) => _ctx.toggleEmailEnabled({ emailEnabled: $event }))
        ]
      }, {
        default: withCtx(() => [
          createTextVNode(
            toDisplayString($options.t("activity", "Enable notification emails")),
            1
            /* TEXT */
          )
        ]),
        _: 1
        /* STABLE */
      }, 8, ["modelValue"])
    ]),
    _: 1
    /* STABLE */
  }, 8, ["name"]);
}
const AdminSettings = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/AdminSettings.vue"]]);
const _sfc_main = {
  name: "DefaultActivitySettings",
  components: {
    ActivityGrid,
    NcSettingsSection
  },
  mounted() {
    this.setEndpoint({ endpoint: "/apps/activity/settings/admin" });
  },
  methods: {
    ...mapActions(["setEndpoint"]),
    t: translate
  }
};
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_ActivityGrid = resolveComponent("ActivityGrid");
  const _component_NcSettingsSection = resolveComponent("NcSettingsSection");
  return openBlock(), createBlock(_component_NcSettingsSection, {
    name: $options.t("activity", "Default settings"),
    description: $options.t("activity", "Configure the default notification settings for new accounts.")
  }, {
    default: withCtx(() => [
      createVNode(_component_ActivityGrid)
    ]),
    _: 1
    /* STABLE */
  }, 8, ["name", "description"]);
}
const DefaultActivitySettings = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/DefaultActivitySettings.vue"]]);
const adminSettingApp = createApp(AdminSettings);
adminSettingApp.use(store);
adminSettingApp.mount("#activity-admin-settings");
const defaultSettingApp = createApp(DefaultActivitySettings);
defaultSettingApp.use(store);
defaultSettingApp.mount("#activity-default-settings");
//# sourceMappingURL=activity-adminSettings.mjs.map
