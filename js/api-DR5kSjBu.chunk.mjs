const appName = "activity";
const appVersion = "7.0.0-dev.0";
import { l as logger } from "./logger-uIIWoPgu.chunk.mjs";
function registerGlobalAPI() {
  window.OCA = window.OCA ?? {};
  window.OCA.Activity = {
    ...window.OCA.Activity,
    __sidebar_actions: window.OCA.Activity?.__sidebar_actions ?? [],
    __sidebar_factories: window.OCA.Activity?.__sidebar_factories ?? [],
    __sidebar_filters: window.OCA.Activity?.__sidebar_filters ?? [],
    registerSidebarAction(action) {
      window.OCA.Activity.__sidebar_actions.push(action);
      logger.debug("Registered new sidebar action");
    },
    registerSidebarEntries(factory) {
      window.OCA.Activity.__sidebar_factories.push(factory);
      logger.debug("Registered new sidebar actions factory");
    },
    registerSidebarFilter(filter) {
      window.OCA.Activity.__sidebar_filters.push(filter);
    }
  };
  logger.info("Activity API registered");
}
function getSidebarActions() {
  return window.OCA?.Activity?.__sidebar_actions ?? [];
}
async function getAdditionalEntries(options) {
  if (window.OCA?.Activity?.__sidebar_factories === void 0) {
    return [];
  }
  const entries = [];
  for (const factory of window.OCA.Activity.__sidebar_factories) {
    try {
      entries.push(...await factory(options));
    } catch (error) {
      logger.error("Error loading additional activity entries", { error });
    }
  }
  return entries;
}
function getActivityFilters() {
  return window.OCA?.Activity?.__sidebar_filters ?? [];
}
export {
  getAdditionalEntries as a,
  getSidebarActions as b,
  getActivityFilters as g,
  registerGlobalAPI as r
};
//# sourceMappingURL=api-DR5kSjBu.chunk.mjs.map
