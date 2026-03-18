(function() {
  "use strict";
  try {
    if (typeof document != "undefined") {
      var elementStyle = document.createElement("style");
      elementStyle.appendChild(document.createTextNode("/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-9cedb949] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.settings-section[data-v-9cedb949] {\n  display: block;\n  padding: 0 0 calc(var(--default-grid-baseline) * 5) 0;\n  margin: calc(var(--default-grid-baseline) * 7);\n  width: min(900px, 100% - var(--default-grid-baseline) * 7 * 2);\n}\n.settings-section[data-v-9cedb949]:not(:last-child) {\n  border-bottom: 1px solid var(--color-border);\n}\n.settings-section__name[data-v-9cedb949] {\n  display: inline-flex;\n  align-items: center;\n  justify-content: center;\n  max-width: 900px;\n  margin-top: 0;\n}\n.settings-section__info[data-v-9cedb949] {\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  width: var(--default-clickable-area);\n  height: var(--default-clickable-area);\n  margin: calc((var(--default-clickable-area) - 16px) / 2 * -1);\n  margin-inline-start: 0;\n  color: var(--color-text-maxcontrast);\n}\n.settings-section__info[data-v-9cedb949]:hover, .settings-section__info[data-v-9cedb949]:focus, .settings-section__info[data-v-9cedb949]:active {\n  color: var(--color-main-text);\n}\n.settings-section__desc[data-v-9cedb949] {\n  margin-top: -0.2em;\n  margin-bottom: 1em;\n  color: var(--color-text-maxcontrast);\n  max-width: 900px;\n}.checkbox-radio-switch[data-v-3a8c4572] {\n  display: flex;\n}\n.checkbox-radio-switch__input[data-v-3a8c4572] {\n  position: fixed;\n  z-index: -1;\n  top: -5000px;\n  left: -5000px;\n  opacity: 0;\n}\n.checkbox-radio-switch__label[data-v-3a8c4572] {\n  display: flex;\n  align-items: center;\n  user-select: none;\n  height: 32px;\n  width: 32px;\n  border-radius: 44px;\n  padding: 0;\n  margin: 2px;\n}\n.checkbox-radio-switch__label[data-v-3a8c4572], .checkbox-radio-switch__label[data-v-3a8c4572] * {\n  cursor: pointer;\n}\n.checkbox-radio-switch__icon[data-v-3a8c4572] {\n  margin-right: 4px;\n  margin-left: 4px;\n  color: var(--color-primary-element);\n  width: var(--icon-size);\n  height: var(--icon-size);\n}\n.checkbox-radio-switch--disabled .checkbox-radio-switch__label[data-v-3a8c4572] {\n  opacity: 0.7;\n}\n.checkbox-radio-switch--disabled .checkbox-radio-switch__label .checkbox-radio-switch__icon[data-v-3a8c4572] {\n  color: var(--color-text-light);\n}\n.checkbox-radio-switch:not(.checkbox-radio-switch--disabled) .checkbox-radio-switch__input:hover + .checkbox-radio-switch__label[data-v-3a8c4572], .checkbox-radio-switch:not(.checkbox-radio-switch--disabled) .checkbox-radio-switch__input:focus + .checkbox-radio-switch__label[data-v-3a8c4572] {\n  background-color: var(--color-primary-element-light);\n}\n.checkbox-radio-switch:not(.checkbox-radio-switch--disabled) .checkbox-radio-switch__input:focus + .checkbox-radio-switch__label[data-v-3a8c4572] {\n  box-shadow: 0 0 0 2px var(--color-primary-element);\n}\n.checkbox-radio-switch-switch:not(.checkbox-radio-switch--checked) .checkbox-radio-switch__icon[data-v-3a8c4572] {\n  color: var(--color-text-lighter);\n}\n.checkbox-radio-switch-switch.checkbox-radio-switch--disabled.checkbox-radio-switch--checked .checkbox-radio-switch__icon[data-v-3a8c4572] {\n  color: var(--color-primary-element-light);\n}\n.checkbox-radio-switch .sr-only[data-v-3a8c4572] {\n  position: absolute;\n  left: -10000px;\n  top: auto;\n  width: 1px;\n  height: 1px;\n  overflow: hidden;\n}table.grid th[data-v-c94b8050] {\n  color: var(--color-text-light);\n  height: 44px;\n}\ntable.grid .group-header th[data-v-c94b8050] {\n  padding-top: 16px;\n  height: 60px;\n}\ntable.grid th.group-header-section[data-v-c94b8050] {\n  font-size: 16px;\n  font-weight: bold;\n}\ntable.grid th.activity_select_group[data-v-c94b8050] {\n  color: var(--color-main-text);\n}\n.sr-only[data-v-c94b8050] {\n  position: absolute;\n  left: -10000px;\n  top: auto;\n  width: 1px;\n  height: 1px;\n  overflow: hidden;\n}"));
      document.head.appendChild(elementStyle);
    }
  } catch (e) {
    console.error("vite-plugin-css-injected-by-js", e);
  }
})();
const appName = "activity";
const appVersion = "7.0.0-dev.0";
import { $ as effectScope, a0 as reactive, s as watch, c as computed, d as defineComponent, a as createElementBlock, b as openBlock, g as createBaseVNode, E as createCommentVNode, f as renderSlot, j as createTextVNode, t as toDisplayString, u as unref, h as createVNode, G as mergeProps, e as createBlock, i as withCtx, z as resolveDynamicComponent, C as normalizeStyle, n as normalizeClass, M as translate, m as resolveComponent, F as Fragment, N as renderList } from "./translation-DoG5ZELJ-BRHGJcXa.chunk.mjs";
import { r as register, E as t26, _ as _export_sfc, a as t, c as _export_sfc$1, d as loadState, j as cancelableClient, A as generateUrl } from "./NcCheckboxRadioSwitch-BMsPx74L-CGnJCDi8.chunk.mjs";
import { a as showSuccess, s as showError } from "./index-C1xmmKTZ-BecyqZV8.chunk.mjs";
import { l as logger } from "./logger-uIIWoPgu.chunk.mjs";
function getDevtoolsGlobalHook() {
  return getTarget().__VUE_DEVTOOLS_GLOBAL_HOOK__;
}
function getTarget() {
  return typeof navigator !== "undefined" && typeof window !== "undefined" ? window : typeof globalThis !== "undefined" ? globalThis : {};
}
const isProxyAvailable = typeof Proxy === "function";
const HOOK_SETUP = "devtools-plugin:setup";
const HOOK_PLUGIN_SETTINGS_SET = "plugin:settings:set";
let supported;
let perf;
function isPerformanceSupported() {
  var _a;
  if (supported !== void 0) {
    return supported;
  }
  if (typeof window !== "undefined" && window.performance) {
    supported = true;
    perf = window.performance;
  } else if (typeof globalThis !== "undefined" && ((_a = globalThis.perf_hooks) === null || _a === void 0 ? void 0 : _a.performance)) {
    supported = true;
    perf = globalThis.perf_hooks.performance;
  } else {
    supported = false;
  }
  return supported;
}
function now() {
  return isPerformanceSupported() ? perf.now() : Date.now();
}
class ApiProxy {
  constructor(plugin, hook) {
    this.target = null;
    this.targetQueue = [];
    this.onQueue = [];
    this.plugin = plugin;
    this.hook = hook;
    const defaultSettings = {};
    if (plugin.settings) {
      for (const id in plugin.settings) {
        const item = plugin.settings[id];
        defaultSettings[id] = item.defaultValue;
      }
    }
    const localSettingsSaveId = `__vue-devtools-plugin-settings__${plugin.id}`;
    let currentSettings = Object.assign({}, defaultSettings);
    try {
      const raw = localStorage.getItem(localSettingsSaveId);
      const data = JSON.parse(raw);
      Object.assign(currentSettings, data);
    } catch (e) {
    }
    this.fallbacks = {
      getSettings() {
        return currentSettings;
      },
      setSettings(value) {
        try {
          localStorage.setItem(localSettingsSaveId, JSON.stringify(value));
        } catch (e) {
        }
        currentSettings = value;
      },
      now() {
        return now();
      }
    };
    if (hook) {
      hook.on(HOOK_PLUGIN_SETTINGS_SET, (pluginId, value) => {
        if (pluginId === this.plugin.id) {
          this.fallbacks.setSettings(value);
        }
      });
    }
    this.proxiedOn = new Proxy({}, {
      get: (_target, prop) => {
        if (this.target) {
          return this.target.on[prop];
        } else {
          return (...args) => {
            this.onQueue.push({
              method: prop,
              args
            });
          };
        }
      }
    });
    this.proxiedTarget = new Proxy({}, {
      get: (_target, prop) => {
        if (this.target) {
          return this.target[prop];
        } else if (prop === "on") {
          return this.proxiedOn;
        } else if (Object.keys(this.fallbacks).includes(prop)) {
          return (...args) => {
            this.targetQueue.push({
              method: prop,
              args,
              resolve: () => {
              }
            });
            return this.fallbacks[prop](...args);
          };
        } else {
          return (...args) => {
            return new Promise((resolve) => {
              this.targetQueue.push({
                method: prop,
                args,
                resolve
              });
            });
          };
        }
      }
    });
  }
  async setRealTarget(target) {
    this.target = target;
    for (const item of this.onQueue) {
      this.target.on[item.method](...item.args);
    }
    for (const item of this.targetQueue) {
      item.resolve(await this.target[item.method](...item.args));
    }
  }
}
function setupDevtoolsPlugin(pluginDescriptor, setupFn) {
  const descriptor = pluginDescriptor;
  const target = getTarget();
  const hook = getDevtoolsGlobalHook();
  const enableProxy = isProxyAvailable && descriptor.enableEarlyProxy;
  if (hook && (target.__VUE_DEVTOOLS_PLUGIN_API_AVAILABLE__ || !enableProxy)) {
    hook.emit(HOOK_SETUP, pluginDescriptor, setupFn);
  } else {
    const proxy = enableProxy ? new ApiProxy(descriptor, hook) : null;
    const list = target.__VUE_DEVTOOLS_PLUGINS__ = target.__VUE_DEVTOOLS_PLUGINS__ || [];
    list.push({
      pluginDescriptor: descriptor,
      setupFn,
      proxy
    });
    if (proxy) {
      setupFn(proxy.proxiedTarget);
    }
  }
}
/*!
 * vuex v4.1.0
 * (c) 2022 Evan You
 * @license MIT
 */
var storeKey = "store";
function forEachValue(obj, fn) {
  Object.keys(obj).forEach(function(key) {
    return fn(obj[key], key);
  });
}
function isObject(obj) {
  return obj !== null && typeof obj === "object";
}
function isPromise(val) {
  return val && typeof val.then === "function";
}
function assert(condition, msg) {
  if (!condition) {
    throw new Error("[vuex] " + msg);
  }
}
function partial(fn, arg) {
  return function() {
    return fn(arg);
  };
}
function genericSubscribe(fn, subs, options) {
  if (subs.indexOf(fn) < 0) {
    options && options.prepend ? subs.unshift(fn) : subs.push(fn);
  }
  return function() {
    var i = subs.indexOf(fn);
    if (i > -1) {
      subs.splice(i, 1);
    }
  };
}
function resetStore(store2, hot) {
  store2._actions = /* @__PURE__ */ Object.create(null);
  store2._mutations = /* @__PURE__ */ Object.create(null);
  store2._wrappedGetters = /* @__PURE__ */ Object.create(null);
  store2._modulesNamespaceMap = /* @__PURE__ */ Object.create(null);
  var state = store2.state;
  installModule(store2, state, [], store2._modules.root, true);
  resetStoreState(store2, state, hot);
}
function resetStoreState(store2, state, hot) {
  var oldState = store2._state;
  var oldScope = store2._scope;
  store2.getters = {};
  store2._makeLocalGettersCache = /* @__PURE__ */ Object.create(null);
  var wrappedGetters = store2._wrappedGetters;
  var computedObj = {};
  var computedCache = {};
  var scope = effectScope(true);
  scope.run(function() {
    forEachValue(wrappedGetters, function(fn, key) {
      computedObj[key] = partial(fn, store2);
      computedCache[key] = computed(function() {
        return computedObj[key]();
      });
      Object.defineProperty(store2.getters, key, {
        get: function() {
          return computedCache[key].value;
        },
        enumerable: true
        // for local getters
      });
    });
  });
  store2._state = reactive({
    data: state
  });
  store2._scope = scope;
  if (store2.strict) {
    enableStrictMode(store2);
  }
  if (oldState) {
    if (hot) {
      store2._withCommit(function() {
        oldState.data = null;
      });
    }
  }
  if (oldScope) {
    oldScope.stop();
  }
}
function installModule(store2, rootState, path, module, hot) {
  var isRoot = !path.length;
  var namespace = store2._modules.getNamespace(path);
  if (module.namespaced) {
    if (store2._modulesNamespaceMap[namespace] && true) {
      console.error("[vuex] duplicate namespace " + namespace + " for the namespaced module " + path.join("/"));
    }
    store2._modulesNamespaceMap[namespace] = module;
  }
  if (!isRoot && !hot) {
    var parentState = getNestedState(rootState, path.slice(0, -1));
    var moduleName = path[path.length - 1];
    store2._withCommit(function() {
      {
        if (moduleName in parentState) {
          console.warn(
            '[vuex] state field "' + moduleName + '" was overridden by a module with the same name at "' + path.join(".") + '"'
          );
        }
      }
      parentState[moduleName] = module.state;
    });
  }
  var local = module.context = makeLocalContext(store2, namespace, path);
  module.forEachMutation(function(mutation, key) {
    var namespacedType = namespace + key;
    registerMutation(store2, namespacedType, mutation, local);
  });
  module.forEachAction(function(action, key) {
    var type = action.root ? key : namespace + key;
    var handler = action.handler || action;
    registerAction(store2, type, handler, local);
  });
  module.forEachGetter(function(getter, key) {
    var namespacedType = namespace + key;
    registerGetter(store2, namespacedType, getter, local);
  });
  module.forEachChild(function(child, key) {
    installModule(store2, rootState, path.concat(key), child, hot);
  });
}
function makeLocalContext(store2, namespace, path) {
  var noNamespace = namespace === "";
  var local = {
    dispatch: noNamespace ? store2.dispatch : function(_type, _payload, _options) {
      var args = unifyObjectStyle(_type, _payload, _options);
      var payload = args.payload;
      var options = args.options;
      var type = args.type;
      if (!options || !options.root) {
        type = namespace + type;
        if (!store2._actions[type]) {
          console.error("[vuex] unknown local action type: " + args.type + ", global type: " + type);
          return;
        }
      }
      return store2.dispatch(type, payload);
    },
    commit: noNamespace ? store2.commit : function(_type, _payload, _options) {
      var args = unifyObjectStyle(_type, _payload, _options);
      var payload = args.payload;
      var options = args.options;
      var type = args.type;
      if (!options || !options.root) {
        type = namespace + type;
        if (!store2._mutations[type]) {
          console.error("[vuex] unknown local mutation type: " + args.type + ", global type: " + type);
          return;
        }
      }
      store2.commit(type, payload, options);
    }
  };
  Object.defineProperties(local, {
    getters: {
      get: noNamespace ? function() {
        return store2.getters;
      } : function() {
        return makeLocalGetters(store2, namespace);
      }
    },
    state: {
      get: function() {
        return getNestedState(store2.state, path);
      }
    }
  });
  return local;
}
function makeLocalGetters(store2, namespace) {
  if (!store2._makeLocalGettersCache[namespace]) {
    var gettersProxy = {};
    var splitPos = namespace.length;
    Object.keys(store2.getters).forEach(function(type) {
      if (type.slice(0, splitPos) !== namespace) {
        return;
      }
      var localType = type.slice(splitPos);
      Object.defineProperty(gettersProxy, localType, {
        get: function() {
          return store2.getters[type];
        },
        enumerable: true
      });
    });
    store2._makeLocalGettersCache[namespace] = gettersProxy;
  }
  return store2._makeLocalGettersCache[namespace];
}
function registerMutation(store2, type, handler, local) {
  var entry = store2._mutations[type] || (store2._mutations[type] = []);
  entry.push(function wrappedMutationHandler(payload) {
    handler.call(store2, local.state, payload);
  });
}
function registerAction(store2, type, handler, local) {
  var entry = store2._actions[type] || (store2._actions[type] = []);
  entry.push(function wrappedActionHandler(payload) {
    var res = handler.call(store2, {
      dispatch: local.dispatch,
      commit: local.commit,
      getters: local.getters,
      state: local.state,
      rootGetters: store2.getters,
      rootState: store2.state
    }, payload);
    if (!isPromise(res)) {
      res = Promise.resolve(res);
    }
    if (store2._devtoolHook) {
      return res.catch(function(err) {
        store2._devtoolHook.emit("vuex:error", err);
        throw err;
      });
    } else {
      return res;
    }
  });
}
function registerGetter(store2, type, rawGetter, local) {
  if (store2._wrappedGetters[type]) {
    {
      console.error("[vuex] duplicate getter key: " + type);
    }
    return;
  }
  store2._wrappedGetters[type] = function wrappedGetter(store22) {
    return rawGetter(
      local.state,
      // local state
      local.getters,
      // local getters
      store22.state,
      // root state
      store22.getters
      // root getters
    );
  };
}
function enableStrictMode(store2) {
  watch(function() {
    return store2._state.data;
  }, function() {
    {
      assert(store2._committing, "do not mutate vuex store state outside mutation handlers.");
    }
  }, { deep: true, flush: "sync" });
}
function getNestedState(state, path) {
  return path.reduce(function(state2, key) {
    return state2[key];
  }, state);
}
function unifyObjectStyle(type, payload, options) {
  if (isObject(type) && type.type) {
    options = payload;
    payload = type;
    type = type.type;
  }
  {
    assert(typeof type === "string", "expects string as the type, but found " + typeof type + ".");
  }
  return { type, payload, options };
}
var LABEL_VUEX_BINDINGS = "vuex bindings";
var MUTATIONS_LAYER_ID = "vuex:mutations";
var ACTIONS_LAYER_ID = "vuex:actions";
var INSPECTOR_ID = "vuex";
var actionId = 0;
function addDevtools(app, store2) {
  setupDevtoolsPlugin(
    {
      id: "org.vuejs.vuex",
      app,
      label: "Vuex",
      homepage: "https://next.vuex.vuejs.org/",
      logo: "https://vuejs.org/images/icons/favicon-96x96.png",
      packageName: "vuex",
      componentStateTypes: [LABEL_VUEX_BINDINGS]
    },
    function(api) {
      api.addTimelineLayer({
        id: MUTATIONS_LAYER_ID,
        label: "Vuex Mutations",
        color: COLOR_LIME_500
      });
      api.addTimelineLayer({
        id: ACTIONS_LAYER_ID,
        label: "Vuex Actions",
        color: COLOR_LIME_500
      });
      api.addInspector({
        id: INSPECTOR_ID,
        label: "Vuex",
        icon: "storage",
        treeFilterPlaceholder: "Filter stores..."
      });
      api.on.getInspectorTree(function(payload) {
        if (payload.app === app && payload.inspectorId === INSPECTOR_ID) {
          if (payload.filter) {
            var nodes = [];
            flattenStoreForInspectorTree(nodes, store2._modules.root, payload.filter, "");
            payload.rootNodes = nodes;
          } else {
            payload.rootNodes = [
              formatStoreForInspectorTree(store2._modules.root, "")
            ];
          }
        }
      });
      api.on.getInspectorState(function(payload) {
        if (payload.app === app && payload.inspectorId === INSPECTOR_ID) {
          var modulePath = payload.nodeId;
          makeLocalGetters(store2, modulePath);
          payload.state = formatStoreForInspectorState(
            getStoreModule(store2._modules, modulePath),
            modulePath === "root" ? store2.getters : store2._makeLocalGettersCache,
            modulePath
          );
        }
      });
      api.on.editInspectorState(function(payload) {
        if (payload.app === app && payload.inspectorId === INSPECTOR_ID) {
          var modulePath = payload.nodeId;
          var path = payload.path;
          if (modulePath !== "root") {
            path = modulePath.split("/").filter(Boolean).concat(path);
          }
          store2._withCommit(function() {
            payload.set(store2._state.data, path, payload.state.value);
          });
        }
      });
      store2.subscribe(function(mutation, state) {
        var data = {};
        if (mutation.payload) {
          data.payload = mutation.payload;
        }
        data.state = state;
        api.notifyComponentUpdate();
        api.sendInspectorTree(INSPECTOR_ID);
        api.sendInspectorState(INSPECTOR_ID);
        api.addTimelineEvent({
          layerId: MUTATIONS_LAYER_ID,
          event: {
            time: Date.now(),
            title: mutation.type,
            data
          }
        });
      });
      store2.subscribeAction({
        before: function(action, state) {
          var data = {};
          if (action.payload) {
            data.payload = action.payload;
          }
          action._id = actionId++;
          action._time = Date.now();
          data.state = state;
          api.addTimelineEvent({
            layerId: ACTIONS_LAYER_ID,
            event: {
              time: action._time,
              title: action.type,
              groupId: action._id,
              subtitle: "start",
              data
            }
          });
        },
        after: function(action, state) {
          var data = {};
          var duration = Date.now() - action._time;
          data.duration = {
            _custom: {
              type: "duration",
              display: duration + "ms",
              tooltip: "Action duration",
              value: duration
            }
          };
          if (action.payload) {
            data.payload = action.payload;
          }
          data.state = state;
          api.addTimelineEvent({
            layerId: ACTIONS_LAYER_ID,
            event: {
              time: Date.now(),
              title: action.type,
              groupId: action._id,
              subtitle: "end",
              data
            }
          });
        }
      });
    }
  );
}
var COLOR_LIME_500 = 8702998;
var COLOR_DARK = 6710886;
var COLOR_WHITE = 16777215;
var TAG_NAMESPACED = {
  label: "namespaced",
  textColor: COLOR_WHITE,
  backgroundColor: COLOR_DARK
};
function extractNameFromPath(path) {
  return path && path !== "root" ? path.split("/").slice(-2, -1)[0] : "Root";
}
function formatStoreForInspectorTree(module, path) {
  return {
    id: path || "root",
    // all modules end with a `/`, we want the last segment only
    // cart/ -> cart
    // nested/cart/ -> cart
    label: extractNameFromPath(path),
    tags: module.namespaced ? [TAG_NAMESPACED] : [],
    children: Object.keys(module._children).map(
      function(moduleName) {
        return formatStoreForInspectorTree(
          module._children[moduleName],
          path + moduleName + "/"
        );
      }
    )
  };
}
function flattenStoreForInspectorTree(result, module, filter, path) {
  if (path.includes(filter)) {
    result.push({
      id: path || "root",
      label: path.endsWith("/") ? path.slice(0, path.length - 1) : path || "Root",
      tags: module.namespaced ? [TAG_NAMESPACED] : []
    });
  }
  Object.keys(module._children).forEach(function(moduleName) {
    flattenStoreForInspectorTree(result, module._children[moduleName], filter, path + moduleName + "/");
  });
}
function formatStoreForInspectorState(module, getters, path) {
  getters = path === "root" ? getters : getters[path];
  var gettersKeys = Object.keys(getters);
  var storeState = {
    state: Object.keys(module.state).map(function(key) {
      return {
        key,
        editable: true,
        value: module.state[key]
      };
    })
  };
  if (gettersKeys.length) {
    var tree = transformPathsToObjectTree(getters);
    storeState.getters = Object.keys(tree).map(function(key) {
      return {
        key: key.endsWith("/") ? extractNameFromPath(key) : key,
        editable: false,
        value: canThrow(function() {
          return tree[key];
        })
      };
    });
  }
  return storeState;
}
function transformPathsToObjectTree(getters) {
  var result = {};
  Object.keys(getters).forEach(function(key) {
    var path = key.split("/");
    if (path.length > 1) {
      var target = result;
      var leafKey = path.pop();
      path.forEach(function(p) {
        if (!target[p]) {
          target[p] = {
            _custom: {
              value: {},
              display: p,
              tooltip: "Module",
              abstract: true
            }
          };
        }
        target = target[p]._custom.value;
      });
      target[leafKey] = canThrow(function() {
        return getters[key];
      });
    } else {
      result[key] = canThrow(function() {
        return getters[key];
      });
    }
  });
  return result;
}
function getStoreModule(moduleMap, path) {
  var names = path.split("/").filter(function(n) {
    return n;
  });
  return names.reduce(
    function(module, moduleName, i) {
      var child = module[moduleName];
      if (!child) {
        throw new Error('Missing module "' + moduleName + '" for path "' + path + '".');
      }
      return i === names.length - 1 ? child : child._children;
    },
    path === "root" ? moduleMap : moduleMap.root._children
  );
}
function canThrow(cb) {
  try {
    return cb();
  } catch (e) {
    return e;
  }
}
var Module = function Module2(rawModule, runtime) {
  this.runtime = runtime;
  this._children = /* @__PURE__ */ Object.create(null);
  this._rawModule = rawModule;
  var rawState = rawModule.state;
  this.state = (typeof rawState === "function" ? rawState() : rawState) || {};
};
var prototypeAccessors$1 = { namespaced: { configurable: true } };
prototypeAccessors$1.namespaced.get = function() {
  return !!this._rawModule.namespaced;
};
Module.prototype.addChild = function addChild(key, module) {
  this._children[key] = module;
};
Module.prototype.removeChild = function removeChild(key) {
  delete this._children[key];
};
Module.prototype.getChild = function getChild(key) {
  return this._children[key];
};
Module.prototype.hasChild = function hasChild(key) {
  return key in this._children;
};
Module.prototype.update = function update(rawModule) {
  this._rawModule.namespaced = rawModule.namespaced;
  if (rawModule.actions) {
    this._rawModule.actions = rawModule.actions;
  }
  if (rawModule.mutations) {
    this._rawModule.mutations = rawModule.mutations;
  }
  if (rawModule.getters) {
    this._rawModule.getters = rawModule.getters;
  }
};
Module.prototype.forEachChild = function forEachChild(fn) {
  forEachValue(this._children, fn);
};
Module.prototype.forEachGetter = function forEachGetter(fn) {
  if (this._rawModule.getters) {
    forEachValue(this._rawModule.getters, fn);
  }
};
Module.prototype.forEachAction = function forEachAction(fn) {
  if (this._rawModule.actions) {
    forEachValue(this._rawModule.actions, fn);
  }
};
Module.prototype.forEachMutation = function forEachMutation(fn) {
  if (this._rawModule.mutations) {
    forEachValue(this._rawModule.mutations, fn);
  }
};
Object.defineProperties(Module.prototype, prototypeAccessors$1);
var ModuleCollection = function ModuleCollection2(rawRootModule) {
  this.register([], rawRootModule, false);
};
ModuleCollection.prototype.get = function get(path) {
  return path.reduce(function(module, key) {
    return module.getChild(key);
  }, this.root);
};
ModuleCollection.prototype.getNamespace = function getNamespace(path) {
  var module = this.root;
  return path.reduce(function(namespace, key) {
    module = module.getChild(key);
    return namespace + (module.namespaced ? key + "/" : "");
  }, "");
};
ModuleCollection.prototype.update = function update$1(rawRootModule) {
  update2([], this.root, rawRootModule);
};
ModuleCollection.prototype.register = function register2(path, rawModule, runtime) {
  var this$1$1 = this;
  if (runtime === void 0) runtime = true;
  {
    assertRawModule(path, rawModule);
  }
  var newModule = new Module(rawModule, runtime);
  if (path.length === 0) {
    this.root = newModule;
  } else {
    var parent = this.get(path.slice(0, -1));
    parent.addChild(path[path.length - 1], newModule);
  }
  if (rawModule.modules) {
    forEachValue(rawModule.modules, function(rawChildModule, key) {
      this$1$1.register(path.concat(key), rawChildModule, runtime);
    });
  }
};
ModuleCollection.prototype.unregister = function unregister(path) {
  var parent = this.get(path.slice(0, -1));
  var key = path[path.length - 1];
  var child = parent.getChild(key);
  if (!child) {
    {
      console.warn(
        "[vuex] trying to unregister module '" + key + "', which is not registered"
      );
    }
    return;
  }
  if (!child.runtime) {
    return;
  }
  parent.removeChild(key);
};
ModuleCollection.prototype.isRegistered = function isRegistered(path) {
  var parent = this.get(path.slice(0, -1));
  var key = path[path.length - 1];
  if (parent) {
    return parent.hasChild(key);
  }
  return false;
};
function update2(path, targetModule, newModule) {
  {
    assertRawModule(path, newModule);
  }
  targetModule.update(newModule);
  if (newModule.modules) {
    for (var key in newModule.modules) {
      if (!targetModule.getChild(key)) {
        {
          console.warn(
            "[vuex] trying to add a new module '" + key + "' on hot reloading, manual reload is needed"
          );
        }
        return;
      }
      update2(
        path.concat(key),
        targetModule.getChild(key),
        newModule.modules[key]
      );
    }
  }
}
var functionAssert = {
  assert: function(value) {
    return typeof value === "function";
  },
  expected: "function"
};
var objectAssert = {
  assert: function(value) {
    return typeof value === "function" || typeof value === "object" && typeof value.handler === "function";
  },
  expected: 'function or object with "handler" function'
};
var assertTypes = {
  getters: functionAssert,
  mutations: functionAssert,
  actions: objectAssert
};
function assertRawModule(path, rawModule) {
  Object.keys(assertTypes).forEach(function(key) {
    if (!rawModule[key]) {
      return;
    }
    var assertOptions = assertTypes[key];
    forEachValue(rawModule[key], function(value, type) {
      assert(
        assertOptions.assert(value),
        makeAssertionMessage(path, key, type, value, assertOptions.expected)
      );
    });
  });
}
function makeAssertionMessage(path, key, type, value, expected) {
  var buf = key + " should be " + expected + ' but "' + key + "." + type + '"';
  if (path.length > 0) {
    buf += ' in module "' + path.join(".") + '"';
  }
  buf += " is " + JSON.stringify(value) + ".";
  return buf;
}
function createStore(options) {
  return new Store(options);
}
var Store = function Store2(options) {
  var this$1$1 = this;
  if (options === void 0) options = {};
  {
    assert(typeof Promise !== "undefined", "vuex requires a Promise polyfill in this browser.");
    assert(this instanceof Store2, "store must be called with the new operator.");
  }
  var plugins = options.plugins;
  if (plugins === void 0) plugins = [];
  var strict = options.strict;
  if (strict === void 0) strict = false;
  var devtools = options.devtools;
  this._committing = false;
  this._actions = /* @__PURE__ */ Object.create(null);
  this._actionSubscribers = [];
  this._mutations = /* @__PURE__ */ Object.create(null);
  this._wrappedGetters = /* @__PURE__ */ Object.create(null);
  this._modules = new ModuleCollection(options);
  this._modulesNamespaceMap = /* @__PURE__ */ Object.create(null);
  this._subscribers = [];
  this._makeLocalGettersCache = /* @__PURE__ */ Object.create(null);
  this._scope = null;
  this._devtools = devtools;
  var store2 = this;
  var ref = this;
  var dispatch2 = ref.dispatch;
  var commit2 = ref.commit;
  this.dispatch = function boundDispatch(type, payload) {
    return dispatch2.call(store2, type, payload);
  };
  this.commit = function boundCommit(type, payload, options2) {
    return commit2.call(store2, type, payload, options2);
  };
  this.strict = strict;
  var state = this._modules.root.state;
  installModule(this, state, [], this._modules.root);
  resetStoreState(this, state);
  plugins.forEach(function(plugin) {
    return plugin(this$1$1);
  });
};
var prototypeAccessors = { state: { configurable: true } };
Store.prototype.install = function install(app, injectKey) {
  app.provide(injectKey || storeKey, this);
  app.config.globalProperties.$store = this;
  var useDevtools = this._devtools !== void 0 ? this._devtools : true;
  if (useDevtools) {
    addDevtools(app, this);
  }
};
prototypeAccessors.state.get = function() {
  return this._state.data;
};
prototypeAccessors.state.set = function(v) {
  {
    assert(false, "use store.replaceState() to explicit replace store state.");
  }
};
Store.prototype.commit = function commit(_type, _payload, _options) {
  var this$1$1 = this;
  var ref = unifyObjectStyle(_type, _payload, _options);
  var type = ref.type;
  var payload = ref.payload;
  var options = ref.options;
  var mutation = { type, payload };
  var entry = this._mutations[type];
  if (!entry) {
    {
      console.error("[vuex] unknown mutation type: " + type);
    }
    return;
  }
  this._withCommit(function() {
    entry.forEach(function commitIterator(handler) {
      handler(payload);
    });
  });
  this._subscribers.slice().forEach(function(sub) {
    return sub(mutation, this$1$1.state);
  });
  if (options && options.silent) {
    console.warn(
      "[vuex] mutation type: " + type + ". Silent option has been removed. Use the filter functionality in the vue-devtools"
    );
  }
};
Store.prototype.dispatch = function dispatch(_type, _payload) {
  var this$1$1 = this;
  var ref = unifyObjectStyle(_type, _payload);
  var type = ref.type;
  var payload = ref.payload;
  var action = { type, payload };
  var entry = this._actions[type];
  if (!entry) {
    {
      console.error("[vuex] unknown action type: " + type);
    }
    return;
  }
  try {
    this._actionSubscribers.slice().filter(function(sub) {
      return sub.before;
    }).forEach(function(sub) {
      return sub.before(action, this$1$1.state);
    });
  } catch (e) {
    {
      console.warn("[vuex] error in before action subscribers: ");
      console.error(e);
    }
  }
  var result = entry.length > 1 ? Promise.all(entry.map(function(handler) {
    return handler(payload);
  })) : entry[0](payload);
  return new Promise(function(resolve, reject) {
    result.then(function(res) {
      try {
        this$1$1._actionSubscribers.filter(function(sub) {
          return sub.after;
        }).forEach(function(sub) {
          return sub.after(action, this$1$1.state);
        });
      } catch (e) {
        {
          console.warn("[vuex] error in after action subscribers: ");
          console.error(e);
        }
      }
      resolve(res);
    }, function(error) {
      try {
        this$1$1._actionSubscribers.filter(function(sub) {
          return sub.error;
        }).forEach(function(sub) {
          return sub.error(action, this$1$1.state, error);
        });
      } catch (e) {
        {
          console.warn("[vuex] error in error action subscribers: ");
          console.error(e);
        }
      }
      reject(error);
    });
  });
};
Store.prototype.subscribe = function subscribe(fn, options) {
  return genericSubscribe(fn, this._subscribers, options);
};
Store.prototype.subscribeAction = function subscribeAction(fn, options) {
  var subs = typeof fn === "function" ? { before: fn } : fn;
  return genericSubscribe(subs, this._actionSubscribers, options);
};
Store.prototype.watch = function watch$1(getter, cb, options) {
  var this$1$1 = this;
  {
    assert(typeof getter === "function", "store.watch only accepts a function.");
  }
  return watch(function() {
    return getter(this$1$1.state, this$1$1.getters);
  }, cb, Object.assign({}, options));
};
Store.prototype.replaceState = function replaceState(state) {
  var this$1$1 = this;
  this._withCommit(function() {
    this$1$1._state.data = state;
  });
};
Store.prototype.registerModule = function registerModule(path, rawModule, options) {
  if (options === void 0) options = {};
  if (typeof path === "string") {
    path = [path];
  }
  {
    assert(Array.isArray(path), "module path must be a string or an Array.");
    assert(path.length > 0, "cannot register the root module by using registerModule.");
  }
  this._modules.register(path, rawModule);
  installModule(this, this.state, path, this._modules.get(path), options.preserveState);
  resetStoreState(this, this.state);
};
Store.prototype.unregisterModule = function unregisterModule(path) {
  var this$1$1 = this;
  if (typeof path === "string") {
    path = [path];
  }
  {
    assert(Array.isArray(path), "module path must be a string or an Array.");
  }
  this._modules.unregister(path);
  this._withCommit(function() {
    var parentState = getNestedState(this$1$1.state, path.slice(0, -1));
    delete parentState[path[path.length - 1]];
  });
  resetStore(this);
};
Store.prototype.hasModule = function hasModule(path) {
  if (typeof path === "string") {
    path = [path];
  }
  {
    assert(Array.isArray(path), "module path must be a string or an Array.");
  }
  return this._modules.isRegistered(path);
};
Store.prototype.hotUpdate = function hotUpdate(newOptions) {
  this._modules.update(newOptions);
  resetStore(this, true);
};
Store.prototype._withCommit = function _withCommit(fn) {
  var committing = this._committing;
  this._committing = true;
  fn();
  this._committing = committing;
};
Object.defineProperties(Store.prototype, prototypeAccessors);
var mapState = normalizeNamespace(function(namespace, states) {
  var res = {};
  if (!isValidMap(states)) {
    console.error("[vuex] mapState: mapper parameter must be either an Array or an Object");
  }
  normalizeMap(states).forEach(function(ref) {
    var key = ref.key;
    var val = ref.val;
    res[key] = function mappedState() {
      var state = this.$store.state;
      var getters = this.$store.getters;
      if (namespace) {
        var module = getModuleByNamespace(this.$store, "mapState", namespace);
        if (!module) {
          return;
        }
        state = module.context.state;
        getters = module.context.getters;
      }
      return typeof val === "function" ? val.call(this, state, getters) : state[val];
    };
    res[key].vuex = true;
  });
  return res;
});
var mapGetters = normalizeNamespace(function(namespace, getters) {
  var res = {};
  if (!isValidMap(getters)) {
    console.error("[vuex] mapGetters: mapper parameter must be either an Array or an Object");
  }
  normalizeMap(getters).forEach(function(ref) {
    var key = ref.key;
    var val = ref.val;
    val = namespace + val;
    res[key] = function mappedGetter() {
      if (namespace && !getModuleByNamespace(this.$store, "mapGetters", namespace)) {
        return;
      }
      if (!(val in this.$store.getters)) {
        console.error("[vuex] unknown getter: " + val);
        return;
      }
      return this.$store.getters[val];
    };
    res[key].vuex = true;
  });
  return res;
});
var mapActions = normalizeNamespace(function(namespace, actions) {
  var res = {};
  if (!isValidMap(actions)) {
    console.error("[vuex] mapActions: mapper parameter must be either an Array or an Object");
  }
  normalizeMap(actions).forEach(function(ref) {
    var key = ref.key;
    var val = ref.val;
    res[key] = function mappedAction() {
      var args = [], len = arguments.length;
      while (len--) args[len] = arguments[len];
      var dispatch2 = this.$store.dispatch;
      if (namespace) {
        var module = getModuleByNamespace(this.$store, "mapActions", namespace);
        if (!module) {
          return;
        }
        dispatch2 = module.context.dispatch;
      }
      return typeof val === "function" ? val.apply(this, [dispatch2].concat(args)) : dispatch2.apply(this.$store, [val].concat(args));
    };
  });
  return res;
});
function normalizeMap(map) {
  if (!isValidMap(map)) {
    return [];
  }
  return Array.isArray(map) ? map.map(function(key) {
    return { key, val: key };
  }) : Object.keys(map).map(function(key) {
    return { key, val: map[key] };
  });
}
function isValidMap(map) {
  return Array.isArray(map) || isObject(map);
}
function normalizeNamespace(fn) {
  return function(namespace, map) {
    if (typeof namespace !== "string") {
      map = namespace;
      namespace = "";
    } else if (namespace.charAt(namespace.length - 1) !== "/") {
      namespace += "/";
    }
    return fn(namespace, map);
  };
}
function getModuleByNamespace(store2, helper, namespace) {
  var module = store2._modulesNamespaceMap[namespace];
  if (!module) {
    console.error("[vuex] module namespace not found in " + helper + "(): " + namespace);
  }
  return module;
}
const _sfc_main$1$1 = {
  name: "HelpCircleIcon",
  emits: ["click"],
  props: {
    title: {
      type: String
    },
    fillColor: {
      type: String,
      default: "currentColor"
    },
    size: {
      type: Number,
      default: 24
    }
  }
};
const _hoisted_1$1$1 = ["aria-hidden", "aria-label"];
const _hoisted_2$1$1 = ["fill", "width", "height"];
const _hoisted_3$1$1 = { d: "M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" };
const _hoisted_4$1$1 = { key: 0 };
function _sfc_render$6(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon help-circle-icon",
    role: "img",
    onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("click", $event))
  }), [
    (openBlock(), createElementBlock("svg", {
      fill: $props.fillColor,
      class: "material-design-icon__svg",
      width: $props.size,
      height: $props.size,
      viewBox: "0 0 24 24"
    }, [
      createBaseVNode("path", _hoisted_3$1$1, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$1$1, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$1$1))
  ], 16, _hoisted_1$1$1);
}
const HelpCircle = /* @__PURE__ */ _export_sfc(_sfc_main$1$1, [["render", _sfc_render$6]]);
register(t26);
const _hoisted_1$6 = { class: "settings-section" };
const _hoisted_2$6 = { class: "settings-section__name" };
const _hoisted_3$6 = ["aria-label", "href", "title"];
const _hoisted_4$5 = {
  key: 0,
  class: "settings-section__desc"
};
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "NcSettingsSection",
  props: {
    name: {},
    description: { default: "" },
    docUrl: { default: "" }
  },
  setup(__props) {
    const ariaLabel = t("External documentation");
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1$6, [
        createBaseVNode("h2", _hoisted_2$6, [
          createTextVNode(toDisplayString(_ctx.name) + " ", 1),
          _ctx.docUrl ? (openBlock(), createElementBlock("a", {
            key: 0,
            "aria-label": unref(ariaLabel),
            class: "settings-section__info",
            href: _ctx.docUrl,
            rel: "noreferrer nofollow",
            target: "_blank",
            title: unref(ariaLabel)
          }, [
            createVNode(HelpCircle, { size: 20 })
          ], 8, _hoisted_3$6)) : createCommentVNode("", true)
        ]),
        _ctx.description ? (openBlock(), createElementBlock("p", _hoisted_4$5, toDisplayString(_ctx.description), 1)) : createCommentVNode("", true),
        renderSlot(_ctx.$slots, "default", {}, void 0, true)
      ]);
    };
  }
});
const NcSettingsSection = /* @__PURE__ */ _export_sfc(_sfc_main$6, [["__scopeId", "data-v-9cedb949"]]);
const _sfc_main$5 = {
  name: "CheckboxBlankIcon",
  emits: ["click"],
  props: {
    title: {
      type: String
    },
    fillColor: {
      type: String,
      default: "currentColor"
    },
    size: {
      type: Number,
      default: 24
    }
  }
};
const _hoisted_1$5 = ["aria-hidden", "aria-label"];
const _hoisted_2$5 = ["fill", "width", "height"];
const _hoisted_3$5 = { d: "M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" };
const _hoisted_4$4 = { key: 0 };
function _sfc_render$5(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon checkbox-blank-icon",
    role: "img",
    onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("click", $event))
  }), [
    (openBlock(), createElementBlock("svg", {
      fill: $props.fillColor,
      class: "material-design-icon__svg",
      width: $props.size,
      height: $props.size,
      viewBox: "0 0 24 24"
    }, [
      createBaseVNode("path", _hoisted_3$5, [
        $props.title ? (openBlock(), createElementBlock(
          "title",
          _hoisted_4$4,
          toDisplayString($props.title),
          1
          /* TEXT */
        )) : createCommentVNode("v-if", true)
      ])
    ], 8, _hoisted_2$5))
  ], 16, _hoisted_1$5);
}
const CheckboxBlank = /* @__PURE__ */ _export_sfc$1(_sfc_main$5, [["render", _sfc_render$5], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/node_modules/vue-material-design-icons/CheckboxBlank.vue"]]);
const _sfc_main$4 = {
  name: "CheckboxBlankOutlineIcon",
  emits: ["click"],
  props: {
    title: {
      type: String
    },
    fillColor: {
      type: String,
      default: "currentColor"
    },
    size: {
      type: Number,
      default: 24
    }
  }
};
const _hoisted_1$4 = ["aria-hidden", "aria-label"];
const _hoisted_2$4 = ["fill", "width", "height"];
const _hoisted_3$4 = { d: "M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V19H5V5H19Z" };
const _hoisted_4$3 = { key: 0 };
function _sfc_render$4(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon checkbox-blank-outline-icon",
    role: "img",
    onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("click", $event))
  }), [
    (openBlock(), createElementBlock("svg", {
      fill: $props.fillColor,
      class: "material-design-icon__svg",
      width: $props.size,
      height: $props.size,
      viewBox: "0 0 24 24"
    }, [
      createBaseVNode("path", _hoisted_3$4, [
        $props.title ? (openBlock(), createElementBlock(
          "title",
          _hoisted_4$3,
          toDisplayString($props.title),
          1
          /* TEXT */
        )) : createCommentVNode("v-if", true)
      ])
    ], 8, _hoisted_2$4))
  ], 16, _hoisted_1$4);
}
const CheckboxBlankOutline = /* @__PURE__ */ _export_sfc$1(_sfc_main$4, [["render", _sfc_render$4], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/node_modules/vue-material-design-icons/CheckboxBlankOutline.vue"]]);
const _sfc_main$3 = {
  name: "CheckboxMarkedIcon",
  emits: ["click"],
  props: {
    title: {
      type: String
    },
    fillColor: {
      type: String,
      default: "currentColor"
    },
    size: {
      type: Number,
      default: 24
    }
  }
};
const _hoisted_1$3 = ["aria-hidden", "aria-label"];
const _hoisted_2$3 = ["fill", "width", "height"];
const _hoisted_3$3 = { d: "M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" };
const _hoisted_4$2 = { key: 0 };
function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon checkbox-marked-icon",
    role: "img",
    onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("click", $event))
  }), [
    (openBlock(), createElementBlock("svg", {
      fill: $props.fillColor,
      class: "material-design-icon__svg",
      width: $props.size,
      height: $props.size,
      viewBox: "0 0 24 24"
    }, [
      createBaseVNode("path", _hoisted_3$3, [
        $props.title ? (openBlock(), createElementBlock(
          "title",
          _hoisted_4$2,
          toDisplayString($props.title),
          1
          /* TEXT */
        )) : createCommentVNode("v-if", true)
      ])
    ], 8, _hoisted_2$3))
  ], 16, _hoisted_1$3);
}
const CheckboxMarked = /* @__PURE__ */ _export_sfc$1(_sfc_main$3, [["render", _sfc_render$3], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/node_modules/vue-material-design-icons/CheckboxMarked.vue"]]);
const _sfc_main$2 = {
  name: "MinusBoxIcon",
  emits: ["click"],
  props: {
    title: {
      type: String
    },
    fillColor: {
      type: String,
      default: "currentColor"
    },
    size: {
      type: Number,
      default: 24
    }
  }
};
const _hoisted_1$2 = ["aria-hidden", "aria-label"];
const _hoisted_2$2 = ["fill", "width", "height"];
const _hoisted_3$2 = { d: "M17,13H7V11H17M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" };
const _hoisted_4$1 = { key: 0 };
function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon minus-box-icon",
    role: "img",
    onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("click", $event))
  }), [
    (openBlock(), createElementBlock("svg", {
      fill: $props.fillColor,
      class: "material-design-icon__svg",
      width: $props.size,
      height: $props.size,
      viewBox: "0 0 24 24"
    }, [
      createBaseVNode("path", _hoisted_3$2, [
        $props.title ? (openBlock(), createElementBlock(
          "title",
          _hoisted_4$1,
          toDisplayString($props.title),
          1
          /* TEXT */
        )) : createCommentVNode("v-if", true)
      ])
    ], 8, _hoisted_2$2))
  ], 16, _hoisted_1$2);
}
const MinusBox = /* @__PURE__ */ _export_sfc$1(_sfc_main$2, [["render", _sfc_render$2], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/node_modules/vue-material-design-icons/MinusBox.vue"]]);
const _sfc_main$1 = {
  name: "CheckboxInput",
  props: {
    /**
     * Unique id attribute of the input
     */
    id: {
      type: String,
      required: true,
      validator: (id) => id.trim() !== ""
    },
    /**
     * Checked state. To be used with `:value.sync`
     */
    checked: {
      type: [Boolean, Array, String],
      default: false
    },
    /**
     * Value to be synced on check
     */
    value: {
      type: String,
      default: null
    },
    /**
     * Disabled state
     */
    disabled: {
      type: Boolean,
      default: false
    },
    /**
     * Indeterminate state
     */
    indeterminate: {
      type: Boolean,
      default: false
    },
    /**
     * Wrapping element tag
     */
    wrapperElement: {
      type: String,
      default: "span"
    },
    /**
     * Input name. Required for radio, optional for checkbox
     */
    name: {
      type: String,
      default: null
    }
  },
  emits: ["update:checked"],
  computed: {
    /**
     * Icon size
     */
    size() {
      return 24;
    },
    /**
     * Css local variables for this component
     */
    cssVars() {
      return {
        "--icon-size": this.size + "px"
      };
    },
    isChecked() {
      return [...this.checked].indexOf(this.value) > -1;
    },
    /**
     * Returns the proper Material icon depending on the select case
     */
    checkboxRadioIconElement() {
      if (this.indeterminate) {
        return MinusBox;
      }
      if (this.disabled && !this.isChecked) {
        return CheckboxBlank;
      }
      if (this.isChecked) {
        return CheckboxMarked;
      }
      return CheckboxBlankOutline;
    }
  },
  methods: {
    onToggle() {
      if (this.disabled) {
        return;
      }
      if (typeof this.checked === "boolean") {
        this.$emit("update:checked", !this.isChecked);
        return;
      }
      const values = this.getInputsSet().filter((input) => input.checked).map((input) => input.value);
      this.$emit("update:checked", values);
    },
    /**
     * Get the input set based on this name
     */
    getInputsSet() {
      return [...document.getElementsByName(this.name)];
    }
  }
};
const _hoisted_1$1 = ["id", "checked", "disabled", "indeterminate", "name", "value"];
const _hoisted_2$1 = ["for"];
const _hoisted_3$1 = { class: "sr-only" };
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createBlock(resolveDynamicComponent($props.wrapperElement), {
    class: normalizeClass([{
      "checkbox-radio-switch--checked": $options.isChecked,
      "checkbox-radio-switch--disabled": $props.disabled,
      "checkbox-radio-switch--indeterminate": $props.indeterminate
    }, "checkbox-radio-switch checkbox-radio-switch-checkbox"]),
    style: normalizeStyle($options.cssVars)
  }, {
    default: withCtx(() => [
      createBaseVNode("input", {
        id: $props.id,
        checked: $options.isChecked,
        disabled: $props.disabled,
        indeterminate: $props.indeterminate,
        name: $props.name,
        type: "checkbox",
        value: $props.value,
        class: "checkbox-radio-switch__input",
        onChange: _cache[0] || (_cache[0] = (...args) => $options.onToggle && $options.onToggle(...args))
      }, null, 40, _hoisted_1$1),
      createBaseVNode("label", {
        for: $props.id,
        class: "checkbox-radio-switch__label"
      }, [
        (openBlock(), createBlock(resolveDynamicComponent($options.checkboxRadioIconElement), {
          size: $options.size,
          class: "checkbox-radio-switch__icon",
          "aria-hidden": "true"
        }, null, 8, ["size"])),
        createBaseVNode("span", _hoisted_3$1, [
          renderSlot(_ctx.$slots, "default", {}, void 0, true)
        ])
      ], 8, _hoisted_2$1)
    ]),
    _: 3
    /* FORWARDED */
  }, 8, ["class", "style"]);
}
const CheckboxInput = /* @__PURE__ */ _export_sfc$1(_sfc_main$1, [["render", _sfc_render$1], ["__scopeId", "data-v-3a8c4572"], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/components/CheckboxInput.vue"]]);
function isActivityEnabled(activity, methodKey) {
  return activity.methods.includes(methodKey);
}
function isOneInputUnChecked(activities, methodKey) {
  for (const activity of activities) {
    if (isActivityEnabled(activity, methodKey) && !activity[methodKey]) {
      return true;
    }
  }
  return false;
}
const _sfc_main = {
  name: "ActivityGrid",
  components: {
    CheckboxInput
  },
  computed: {
    ...mapGetters(["checkedActivities"]),
    ...mapState([
      "methods",
      "activityGroups"
    ])
  },
  methods: {
    isActivityEnabled,
    ...mapActions(["toggleMethodForMethodAndActivity"]),
    actionName(method) {
      if (method === "email") {
        return translate("activity", "Send email");
      } else {
        return translate("activity", "Send push notification");
      }
    },
    t: translate
  }
};
const _hoisted_1 = { class: "grid activitysettings" };
const _hoisted_2 = { class: "sr-only" };
const _hoisted_3 = { class: "group-header" };
const _hoisted_4 = { class: "group-header-section" };
const _hoisted_5 = { scope: "row" };
const _hoisted_6 = ["innerHTML"];
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_CheckboxInput = resolveComponent("CheckboxInput");
  return openBlock(), createElementBlock("table", _hoisted_1, [
    createBaseVNode(
      "caption",
      _hoisted_2,
      toDisplayString($options.t("activity", "Activity notification configuration")),
      1
      /* TEXT */
    ),
    (openBlock(true), createElementBlock(
      Fragment,
      null,
      renderList(_ctx.activityGroups, (group, groupKey) => {
        return openBlock(), createElementBlock("tbody", { key: groupKey }, [
          createBaseVNode("tr", _hoisted_3, [
            createBaseVNode(
              "th",
              _hoisted_4,
              toDisplayString(group.name),
              1
              /* TEXT */
            ),
            (openBlock(true), createElementBlock(
              Fragment,
              null,
              renderList(_ctx.methods, (methodName, methodKey) => {
                return openBlock(), createElementBlock(
                  "th",
                  {
                    key: methodKey,
                    class: "activity_select_group"
                  },
                  toDisplayString(methodName),
                  1
                  /* TEXT */
                );
              }),
              128
              /* KEYED_FRAGMENT */
            ))
          ]),
          (openBlock(true), createElementBlock(
            Fragment,
            null,
            renderList(group.activities, (activity, activityKey) => {
              return openBlock(), createElementBlock("tr", { key: activityKey }, [
                createBaseVNode("th", _hoisted_5, [
                  createCommentVNode(" eslint-disable vue/no-v-html "),
                  createBaseVNode("span", {
                    innerHTML: activity.desc
                  }, null, 8, _hoisted_6),
                  createCommentVNode(" eslint-enable vue/no-v-html ")
                ]),
                (openBlock(true), createElementBlock(
                  Fragment,
                  null,
                  renderList(_ctx.methods, (methodName, methodKey) => {
                    return openBlock(), createElementBlock("td", { key: methodKey }, [
                      createVNode(_component_CheckboxInput, {
                        id: `${activityKey}_${methodKey}`,
                        disabled: !$options.isActivityEnabled(activity, methodKey),
                        checked: _ctx.checkedActivities,
                        value: `${activityKey}_${methodKey}`,
                        "onUpdate:checked": ($event) => _ctx.toggleMethodForMethodAndActivity({ groupKey, activityKey, methodKey })
                      }, {
                        default: withCtx(() => [
                          createTextVNode(
                            toDisplayString($options.actionName(methodKey)),
                            1
                            /* TEXT */
                          )
                        ]),
                        _: 2
                        /* DYNAMIC */
                      }, 1032, ["id", "disabled", "checked", "value", "onUpdate:checked"])
                    ]);
                  }),
                  128
                  /* KEYED_FRAGMENT */
                ))
              ]);
            }),
            128
            /* KEYED_FRAGMENT */
          ))
        ]);
      }),
      128
      /* KEYED_FRAGMENT */
    ))
  ]);
}
const ActivityGrid = /* @__PURE__ */ _export_sfc$1(_sfc_main, [["render", _sfc_render], ["__scopeId", "data-v-c94b8050"], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/components/ActivityGrid.vue"]]);
const store = new createStore({
  strict: true,
  state: {
    setting: loadState("activity", "setting"),
    activityGroups: loadState("activity", "activity_groups"),
    isEmailSet: loadState("activity", "is_email_set"),
    emailEnabled: loadState("activity", "email_enabled"),
    activityDigestEnabled: loadState("activity", "activity_digest_enabled", false),
    settingBatchtime: loadState("activity", "setting_batchtime"),
    methods: loadState("activity", "methods"),
    endpoint: ""
  },
  getters: {
    /**
     * Return an array of checked activities.
     *
     * @param state - The current state.
     */
    checkedActivities(state) {
      const methodsEnabled = (activityKey, activity) => {
        const methods = [];
        if (activity.email) {
          methods.push({ activityKey, method: "email", activity });
        }
        if (activity.notification) {
          methods.push({ activityKey, method: "notification", activity });
        }
        return methods;
      };
      return Object.values(state.activityGroups).map((group) => Object.entries(group.activities)).reduce((acc, val) => acc.concat(val), []).map(([activityKey, activity]) => methodsEnabled(activityKey, activity)).reduce((acc, val) => acc.concat(val), []).filter(({ activity, method }) => activity[method]).map(({ activityKey, method }) => `${activityKey}_${method}`);
    }
  },
  mutations: {
    /**
     * Update the 'enabled' state of a notification method for a given group/activity/method tuple
     *
     * @param state - The current state.
     * @param payload - The payload.
     * @param payload.groupKey - The targeted group
     * @param payload.activityKey - The targeted activity
     * @param payload.methodKey - The targeted method
     * @param payload.value - The value to set
     */
    SET_METHOD_FOR_METHOD_AND_ACTIVITY(state, { groupKey, activityKey, methodKey, value }) {
      const group = state.activityGroups[groupKey];
      const activity = group.activities[activityKey];
      if (isActivityEnabled(activity, methodKey)) {
        activity[methodKey] = value;
      }
    },
    /**
     * Set the endpoint used to save the settings.
     *
     * @param state - The current state.
     * @param payload - The payload.
     * @param payload.endpoint - Where to POST the saveSettings request.
     */
    SET_ENDPOINT(state, { endpoint }) {
      state.endpoint = endpoint;
    },
    /**
     * Set the batch time.
     *
     * @param state - The current state.
     * @param payload - The payload.
     * @param payload.settingBatchtime - The selected batch time.
     */
    SET_SETTING_BATCHTIME(state, { settingBatchtime }) {
      state.settingBatchtime = settingBatchtime;
    },
    /**
     * Toggle activity digest.
     *
     * @param state - The current state.
     * @param payload - The payload.
     * @param payload.activityDigestEnabled - Enabled status of the activity digest.
     */
    TOGGLE_ACTIVITY_DIGEST(state, { activityDigestEnabled }) {
      state.activityDigestEnabled = activityDigestEnabled;
    },
    /**
     * Toggle the availability of mail notifications
     *
     * @param state - The current state.
     * @param payload - The payload.
     * @param payload.emailEnabled - Enabled status of the email notifications.
     */
    TOGGLE_EMAIL_ENABLED(state, { emailEnabled }) {
      state.emailEnabled = emailEnabled;
    }
  },
  actions: {
    /**
     * Set the endpoint used to save the settings.
     *
     * @param _ - Action context
     * @param _.commit - State modifying function
     * @param payload - The payload.
     * @param payload.endpoint - Where to POST the saveSettings request.
     */
    setEndpoint({ commit: commit2 }, { endpoint }) {
      commit2("SET_ENDPOINT", { endpoint });
    },
    /**
     * Toggle the 'enabled' state of a notification method for a given group/activity/method tuple
     *
     * @param _ - Action context
     * @param _.commit - State modifying function
     * @param _.dispatch - Function to dispatch another action
     * @param _.state - Current state
     * @param payload - The payload.
     * @param payload.groupKey - The targeted group
     * @param payload.activityKey - The targeted activity
     * @param payload.methodKey - The targeted method
     */
    toggleMethodForMethodAndActivity({ commit: commit2, state, dispatch: dispatch2 }, { groupKey, activityKey, methodKey }) {
      const activity = state.activityGroups[groupKey].activities[activityKey];
      const oneInputIsChecked = isOneInputUnChecked([activity], methodKey);
      commit2(
        "SET_METHOD_FOR_METHOD_AND_ACTIVITY",
        {
          groupKey,
          activityKey,
          methodKey,
          value: oneInputIsChecked
        }
      );
      dispatch2("saveSettings");
    },
    /**
     * Toggle the 'enabled' state of a notification method for a given group/method tuple
     *
     * @param _ - Action context
     * @param _.commit - State modifying function
     * @param _.dispatch - Function to dispatch another action
     * @param _.state - Current state
     * @param payload - The payload.
     * @param payload.groupKey - The targeted group
     * @param payload.methodKey - The targeted method
     */
    toggleMethodForGroup({ commit: commit2, state, dispatch: dispatch2 }, { groupKey, methodKey }) {
      const activities = Object.values(state.activityGroups[groupKey].activities);
      const oneInputIsChecked = isOneInputUnChecked(activities, methodKey);
      for (const activityKey in state.activityGroups[groupKey].activities) {
        commit2(
          "SET_METHOD_FOR_METHOD_AND_ACTIVITY",
          {
            groupKey,
            activityKey,
            methodKey,
            value: oneInputIsChecked
          }
        );
      }
      dispatch2("saveSettings");
    },
    /**
     * Toggle the 'enabled' state of a notification method for a given group/activity tuple
     *
     * @param _ - Action context
     * @param _.commit - State modifying function
     * @param _.dispatch - Function to dispatch another action
     * @param _.state - Current state
     * @param payload - The payload.
     * @param payload.groupKey - The targeted group
     * @param payload.activityKey - The targeted activity
     */
    toggleMethodsForActivity({ commit: commit2, state, dispatch: dispatch2 }, { groupKey, activityKey }) {
      const activity = state.activityGroups[groupKey].activities[activityKey];
      const oneInputIsChecked = activity.methods.map((method) => isOneInputUnChecked([activity], method)).includes(true);
      for (const methodKey of activity.methods) {
        commit2(
          "SET_METHOD_FOR_METHOD_AND_ACTIVITY",
          {
            groupKey,
            activityKey,
            methodKey,
            value: oneInputIsChecked
          }
        );
      }
      dispatch2("saveSettings");
    },
    /**
     * Set the batch time.
     *
     * @param _ - Action context
     * @param _.commit - State modifying function
     * @param _.dispatch - Function to dispatch another action
     * @param payload - The payload.
     * @param payload.settingBatchtime - The selected batch time.
     */
    setSettingBatchtime({ commit: commit2, dispatch: dispatch2 }, { settingBatchtime }) {
      commit2(
        "SET_SETTING_BATCHTIME",
        {
          settingBatchtime
        }
      );
      dispatch2("saveSettings");
    },
    /**
     * Toggle the activity digest.
     *
     * @param _ - Action context
     * @param _.commit - State modifying function
     * @param _.dispatch - Function to dispatch another action
     * @param payload - The payload.
     * @param payload.activityDigestEnabled - Enabled status of the activity digest.
     */
    toggleActivityDigestEnabled({ commit: commit2, dispatch: dispatch2 }, { activityDigestEnabled }) {
      commit2(
        "TOGGLE_ACTIVITY_DIGEST",
        {
          activityDigestEnabled
        }
      );
      dispatch2("saveSettings");
    },
    /**
     * Toggle the availability of mail notifications
     *
     * @param _ - Action context
     * @param _.commit - State modifying function
     * @param payload - The payload.
     * @param payload.emailEnabled - Enabled status of the email notifications.
     */
    toggleEmailEnabled({ commit: commit2 }, { emailEnabled }) {
      commit2(
        "TOGGLE_EMAIL_ENABLED",
        {
          emailEnabled
        }
      );
      try {
        OCP.AppConfig.setValue("activity", "enable_email", emailEnabled ? "yes" : "no");
        showSuccess(translate("activity", "Your settings have been updated."));
      } catch (error) {
        showError(translate("activity", "Unable to save the settings"));
        logger.error("An error occurred while saving the activity settings", { error });
      }
    },
    /**
     * Save the currently displayed settings
     *
     * @param _ - Action context
     * @param _.state - Current state
     * @param _.getters - Getter functions for the state
     */
    async saveSettings({ state, getters }) {
      try {
        const form = new FormData();
        getters.checkedActivities.forEach((activity) => {
          form.append(activity, "1");
        });
        form.append("notify_setting_batchtime", `${state.settingBatchtime}`);
        form.append("activity_digest", `${state.activityDigestEnabled ? 1 : 0}`);
        const response = await cancelableClient.post(generateUrl(state.endpoint), form);
        showSuccess(response.data.data.message);
      } catch (error) {
        showError(translate("activity", "Unable to save the settings"));
        logger.error("An error occurred while saving the activity settings", { error });
      }
    }
  }
});
export {
  ActivityGrid as A,
  NcSettingsSection as N,
  mapState as a,
  mapActions as m,
  store as s
};
//# sourceMappingURL=settings-store-RZiqWfNg.chunk.mjs.map
