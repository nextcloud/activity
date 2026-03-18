(function() {
  "use strict";
  try {
    if (typeof document != "undefined") {
      var elementStyle = document.createElement("style");
      elementStyle.appendChild(document.createTextNode(".activity-frequency__label[data-v-9fbd66d7] {\n  margin-top: 24px;\n  display: inline-block;\n}\n.notification-frequency__select[data-v-9fbd66d7] {\n  margin-left: calc(var(--default-grid-baseline) * 2);\n}"));
      document.head.appendChild(elementStyle);
    }
  } catch (e) {
    console.error("vite-plugin-css-injected-by-js", e);
  }
})();
const appName = "activity";
const appVersion = "7.0.0-dev.0";
import { M as translate, m as resolveComponent, e as createBlock, b as openBlock, i as withCtx, h as createVNode, j as createTextVNode, t as toDisplayString, a as createElementBlock, E as createCommentVNode, g as createBaseVNode, X as createApp } from "./translation-DoG5ZELJ-BRHGJcXa.chunk.mjs";
import { m as mapActions, a as mapState, N as NcSettingsSection, A as ActivityGrid, s as store } from "./settings-store-RZiqWfNg.chunk.mjs";
import { c as _export_sfc, B as NcCheckboxRadioSwitch } from "./NcCheckboxRadioSwitch-BMsPx74L-CGnJCDi8.chunk.mjs";
import "./index-C6VBhB33.chunk.mjs";
import "./index-C1xmmKTZ-BecyqZV8.chunk.mjs";
import "./logger-uIIWoPgu.chunk.mjs";
const _sfc_main$2 = {
  name: "DailySummary",
  components: {
    NcCheckboxRadioSwitch,
    NcSettingsSection
  },
  computed: {
    ...mapState(["activityDigestEnabled"])
  },
  mounted() {
    this.setEndpoint({ endpoint: "/apps/activity/settings" });
  },
  methods: {
    ...mapActions([
      "setEndpoint",
      "toggleActivityDigestEnabled"
    ]),
    t: translate
  }
};
function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcCheckboxRadioSwitch = resolveComponent("NcCheckboxRadioSwitch");
  const _component_NcSettingsSection = resolveComponent("NcSettingsSection");
  return openBlock(), createBlock(_component_NcSettingsSection, {
    name: $options.t("activity", "Daily activity summary")
  }, {
    default: withCtx(() => [
      createVNode(_component_NcCheckboxRadioSwitch, {
        "data-cy-checkbox": "",
        modelValue: _ctx.activityDigestEnabled,
        "onUpdate:modelValue": [
          _cache[0] || (_cache[0] = ($event) => _ctx.activityDigestEnabled = $event),
          _cache[1] || (_cache[1] = ($event) => _ctx.toggleActivityDigestEnabled({ activityDigestEnabled: $event }))
        ]
      }, {
        default: withCtx(() => [
          createTextVNode(
            toDisplayString($options.t("activity", "Send daily activity summary in the morning")),
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
const DailySummary = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/DailySummary.vue"]]);
const EmailFrequency = {
  EmailFrequency: {
    EMAIL_SEND_HOURLY: 0,
    EMAIL_SEND_DAILY: 1,
    EMAIL_SEND_WEEKLY: 2,
    EMAIL_SEND_ASAP: 3
  }
};
const _sfc_main$1 = {
  name: "EmailSettings",
  data() {
    return {
      EmailFrequency: EmailFrequency.EmailFrequency
    };
  },
  computed: {
    ...mapState([
      "emailEnabled",
      "isEmailSet",
      "settingBatchtime"
    ])
  },
  methods: {
    ...mapActions(["setSettingBatchtime"]),
    t: translate
  }
};
const _hoisted_1 = { key: 0 };
const _hoisted_2 = { key: 0 };
const _hoisted_3 = {
  for: "activity_setting_batchtime",
  class: "activity-frequency__label"
};
const _hoisted_4 = ["value", "selected"];
const _hoisted_5 = ["value", "selected"];
const _hoisted_6 = ["value", "selected"];
const _hoisted_7 = ["value", "selected"];
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  return _ctx.emailEnabled ? (openBlock(), createElementBlock("div", _hoisted_1, [
    !_ctx.isEmailSet ? (openBlock(), createElementBlock("p", _hoisted_2, [
      createBaseVNode(
        "strong",
        null,
        toDisplayString($options.t("activity", "You need to set up your email address before you can receive notification emails.")),
        1
        /* TEXT */
      )
    ])) : createCommentVNode("v-if", true),
    createBaseVNode("p", null, [
      createBaseVNode(
        "label",
        _hoisted_3,
        toDisplayString($options.t("activity", "Send activity emails")),
        1
        /* TEXT */
      ),
      createBaseVNode(
        "select",
        {
          id: "activity_setting_batchtime",
          class: "notification-frequency__select",
          name: "activity_setting_batchtime",
          onChange: _cache[0] || (_cache[0] = ($event) => _ctx.setSettingBatchtime({ settingBatchtime: $event.target.value }))
        },
        [
          createBaseVNode("option", {
            value: $data.EmailFrequency.EMAIL_SEND_ASAP,
            selected: _ctx.settingBatchtime === $data.EmailFrequency.EMAIL_SEND_ASAP
          }, toDisplayString($options.t("activity", "As soon as possible")), 9, _hoisted_4),
          createBaseVNode("option", {
            value: $data.EmailFrequency.EMAIL_SEND_HOURLY,
            selected: _ctx.settingBatchtime === $data.EmailFrequency.EMAIL_SEND_HOURLY
          }, toDisplayString($options.t("activity", "Hourly")), 9, _hoisted_5),
          createBaseVNode("option", {
            value: $data.EmailFrequency.EMAIL_SEND_DAILY,
            selected: _ctx.settingBatchtime === $data.EmailFrequency.EMAIL_SEND_DAILY
          }, toDisplayString($options.t("activity", "Daily")), 9, _hoisted_6),
          createBaseVNode("option", {
            value: $data.EmailFrequency.EMAIL_SEND_WEEKLY,
            selected: _ctx.settingBatchtime === $data.EmailFrequency.EMAIL_SEND_WEEKLY
          }, toDisplayString($options.t("activity", "Weekly")), 9, _hoisted_7)
        ],
        32
        /* NEED_HYDRATION */
      )
    ])
  ])) : createCommentVNode("v-if", true);
}
const EmailSettings = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1], ["__scopeId", "data-v-9fbd66d7"], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/components/EmailSettings.vue"]]);
const _sfc_main = {
  name: "UserSettings",
  components: {
    NcSettingsSection,
    EmailSettings,
    ActivityGrid
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
    this.setEndpoint({ endpoint: "/apps/activity/settings" });
  },
  methods: {
    ...mapActions(["setEndpoint"]),
    t: translate
  }
};
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_ActivityGrid = resolveComponent("ActivityGrid");
  const _component_EmailSettings = resolveComponent("EmailSettings");
  const _component_NcSettingsSection = resolveComponent("NcSettingsSection");
  return openBlock(), createBlock(_component_NcSettingsSection, {
    name: $options.t("activity", "Activity"),
    description: $options.settingDescription
  }, {
    default: withCtx(() => [
      createVNode(_component_ActivityGrid),
      createVNode(_component_EmailSettings)
    ]),
    _: 1
    /* STABLE */
  }, 8, ["name", "description"]);
}
const UserSettings = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/UserSettings.vue"]]);
const userSettingApp = createApp(UserSettings);
userSettingApp.use(store);
userSettingApp.mount("#activity-user-settings");
const digestSettingApp = createApp(DailySummary);
digestSettingApp.use(store);
digestSettingApp.mount("#activity-digest-user-settings");
//# sourceMappingURL=activity-personalSettings.mjs.map
