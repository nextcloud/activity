/*! third party licenses: js/vendor.LICENSE.txt */
import{l as s}from"./logger-YHHKXmsa.mjs";function _(){var e,i,t,r,a,o,d;window.OCA=(e=window.OCA)!=null?e:{},window.OCA.Activity={...window.OCA.Activity,__sidebar_actions:(t=(i=window.OCA.Activity)==null?void 0:i.__sidebar_actions)!=null?t:[],__sidebar_factories:(a=(r=window.OCA.Activity)==null?void 0:r.__sidebar_factories)!=null?a:[],__sidebar_filters:(d=(o=window.OCA.Activity)==null?void 0:o.__sidebar_filters)!=null?d:[],registerSidebarAction(n){window.OCA.Activity.__sidebar_actions.push(n),s.debug("Registered new sidebar action")},registerSidebarEntries(n){window.OCA.Activity.__sidebar_factories.push(n),s.debug("Registered new sidebar actions factory")},registerSidebarFilter(n){window.OCA.Activity.__sidebar_filters.push(n)}},s.info("Activity API registered")}function c(){var e,i,t;return(t=(i=(e=window.OCA)==null?void 0:e.Activity)==null?void 0:i.__sidebar_actions)!=null?t:[]}async function w(e){var i,t;if(((t=(i=window.OCA)==null?void 0:i.Activity)==null?void 0:t.__sidebar_factories)===void 0)return[];const r=window.OCA.Activity.__sidebar_factories.map(async a=>await a(e));return(await Promise.all(r)).flat()}function A(){var e,i,t;return(t=(i=(e=window.OCA)==null?void 0:e.Activity)==null?void 0:i.__sidebar_filters)!=null?t:[]}export{w as a,A as b,c as g,_ as r};