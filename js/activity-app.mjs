(function() {
  "use strict";
  try {
    if (typeof document != "undefined") {
      var elementStyle = document.createElement("style");
      elementStyle.appendChild(document.createTextNode('/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n#skip-actions.vue-skip-actions:focus-within {\n  top: 0 !important;\n  inset-inline-start: 0 !important;\n  width: 100vw;\n  height: 100vh;\n  padding: var(--body-container-margin) !important;\n  backdrop-filter: brightness(50%);\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-d9b0d7e8] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.vue-skip-actions__container[data-v-d9b0d7e8] {\n  background-color: var(--color-main-background);\n  border-radius: var(--border-radius-element);\n  padding: 22px;\n}\n.vue-skip-actions__headline[data-v-d9b0d7e8] {\n  font-weight: bold;\n  font-size: 20px;\n  line-height: 30px;\n  margin-bottom: 12px;\n}\n.vue-skip-actions__buttons[data-v-d9b0d7e8] {\n  display: flex;\n  flex-wrap: wrap;\n  gap: 12px;\n}\n.vue-skip-actions__buttons[data-v-d9b0d7e8] > * {\n  flex: 1 0 fit-content;\n}\n.vue-skip-actions__image[data-v-d9b0d7e8] {\n  margin-top: 12px;\n}\n.vue-skip-actions__image[data-v-d9b0d7e8]:dir(rtl) {\n  transform: rotateY(180deg);\n}\n.content[data-v-d9b0d7e8] {\n  display: flex;\n  width: calc(100% - var(--body-container-margin) * 2);\n  border-radius: var(--body-container-radius);\n  height: var(--body-height);\n  overflow: hidden;\n  padding: 0;\n}\n.content[data-v-d9b0d7e8]:not(.with-sidebar--full) {\n  position: fixed;\n}\n.content[data-v-d9b0d7e8], .content[data-v-d9b0d7e8] * {\n  box-sizing: border-box;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-a28923a1] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.app-details-toggle[data-v-a28923a1] {\n  position: sticky;\n  width: var(--default-clickable-area);\n  height: var(--default-clickable-area);\n  padding: calc((var(--default-clickable-area) - 16px) / 2);\n  cursor: pointer;\n  opacity: 0.6;\n  transform: rotate(180deg);\n  background-color: var(--color-main-background);\n  z-index: 2000;\n  top: var(--app-navigation-padding);\n  inset-inline-start: calc(var(--default-clickable-area) + var(--app-navigation-padding) * 2);\n}\n.app-details-toggle--mobile[data-v-a28923a1] {\n  inset-inline-start: var(--app-navigation-padding);\n}\n.app-details-toggle[data-v-a28923a1]:active, .app-details-toggle[data-v-a28923a1]:hover, .app-details-toggle[data-v-a28923a1]:focus {\n  opacity: 1;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-563c4ac4] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.app-content[data-v-563c4ac4] {\n  position: initial;\n  z-index: 1000;\n  flex-basis: 100vw;\n  height: 100%;\n  margin: 0 !important;\n  background-color: var(--color-main-background);\n  min-width: 0;\n}\n.app-content[data-v-563c4ac4]:not(.app-content--has-list) {\n  overflow: auto;\n}\n.app-content-wrapper[data-v-563c4ac4] {\n  position: relative;\n  width: 100%;\n  height: 100%;\n}\n.app-content-wrapper--no-split.app-content-wrapper--show-list[data-v-563c4ac4]  .app-content-list {\n  display: flex;\n}\n.app-content-wrapper--no-split.app-content-wrapper--show-list[data-v-563c4ac4]  .app-content-details {\n  display: none;\n}\n.app-content-wrapper--no-split.app-content-wrapper--show-details[data-v-563c4ac4]  .app-content-list {\n  display: none;\n}\n.app-content-wrapper--no-split.app-content-wrapper--show-details[data-v-563c4ac4]  .app-content-details {\n  display: block;\n}\n[data-v-563c4ac4] .splitpanes.default-theme .app-content-list {\n  max-width: none;\n  /* Thin scrollbar is hard to catch on resizable columns */\n  scrollbar-width: auto;\n}\n[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__pane {\n  background-color: transparent;\n  transition: none;\n}\n[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__pane-list {\n  min-width: 300px;\n  position: sticky;\n}\n@media only screen and (width < 1024px) {\n[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__pane-list {\n    display: none;\n}\n}\n[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__pane-details {\n  overflow-y: auto;\n}\n@media only screen and (width < 1024px) {\n[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__pane-details {\n    min-width: 100%;\n}\n}\n[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__splitter {\n  background-color: var(--color-main-background);\n}\n[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__splitter::before,[data-v-563c4ac4] .splitpanes.default-theme .splitpanes__splitter::after {\n  background-color: var(--color-border);\n}\n[data-v-563c4ac4] .splitpanes.default-theme.splitpanes--vertical .splitpanes__splitter {\n  border-inline-start: 1px solid var(--color-border);\n}\n[data-v-563c4ac4] .splitpanes.default-theme.splitpanes--horizontal .splitpanes__splitter {\n  border-top: 1px solid var(--color-border);\n}\n.app-content-wrapper--show-list[data-v-563c4ac4] .app-content-list {\n  max-width: none;\n}.splitpanes{display:flex;width:100%;height:100%}.splitpanes--vertical{flex-direction:row}.splitpanes--horizontal{flex-direction:column}.splitpanes--dragging .splitpanes__pane,*:has(.splitpanes--dragging){-webkit-user-select:none;user-select:none;pointer-events:none}.splitpanes__pane{width:100%;height:100%;overflow:hidden}.splitpanes--vertical .splitpanes__pane{transition:width .2s ease-out;will-change:width}.splitpanes--horizontal .splitpanes__pane{transition:height .2s ease-out;will-change:height}.splitpanes--dragging .splitpanes__pane{transition:none}.splitpanes__splitter{touch-action:none}.splitpanes--vertical>.splitpanes__splitter{min-width:1px;cursor:col-resize}.splitpanes--horizontal>.splitpanes__splitter{min-height:1px;cursor:row-resize}.default-theme.splitpanes .splitpanes__pane{background-color:#f2f2f2}.default-theme.splitpanes .splitpanes__splitter{background-color:#fff;box-sizing:border-box;position:relative;flex-shrink:0}.default-theme.splitpanes .splitpanes__splitter:before,.default-theme.splitpanes .splitpanes__splitter:after{content:"";position:absolute;top:50%;left:50%;background-color:#00000026;transition:background-color .3s}.default-theme.splitpanes .splitpanes__splitter:hover:before,.default-theme.splitpanes .splitpanes__splitter:hover:after{background-color:#00000040}.default-theme.splitpanes .splitpanes__splitter:first-child{cursor:auto}.default-theme.splitpanes .splitpanes .splitpanes__splitter{z-index:1}.default-theme.splitpanes--vertical>.splitpanes__splitter,.default-theme .splitpanes--vertical>.splitpanes__splitter{width:7px;border-left:1px solid #eee;margin-left:-1px}.default-theme.splitpanes--vertical>.splitpanes__splitter:before,.default-theme.splitpanes--vertical>.splitpanes__splitter:after,.default-theme .splitpanes--vertical>.splitpanes__splitter:before,.default-theme .splitpanes--vertical>.splitpanes__splitter:after{transform:translateY(-50%);width:1px;height:30px}.default-theme.splitpanes--vertical>.splitpanes__splitter:before,.default-theme .splitpanes--vertical>.splitpanes__splitter:before{margin-left:-2px}.default-theme.splitpanes--vertical>.splitpanes__splitter:after,.default-theme .splitpanes--vertical>.splitpanes__splitter:after{margin-left:1px}.default-theme.splitpanes--horizontal>.splitpanes__splitter,.default-theme .splitpanes--horizontal>.splitpanes__splitter{height:7px;border-top:1px solid #eee;margin-top:-1px}.default-theme.splitpanes--horizontal>.splitpanes__splitter:before,.default-theme.splitpanes--horizontal>.splitpanes__splitter:after,.default-theme .splitpanes--horizontal>.splitpanes__splitter:before,.default-theme .splitpanes--horizontal>.splitpanes__splitter:after{transform:translate(-50%);width:30px;height:1px}.default-theme.splitpanes--horizontal>.splitpanes__splitter:before,.default-theme .splitpanes--horizontal>.splitpanes__splitter:before{margin-top:-2px}.default-theme.splitpanes--horizontal>.splitpanes__splitter:after,.default-theme .splitpanes--horizontal>.splitpanes__splitter:after{margin-top:1px}\n.activity-group__heading[data-v-c27bd1ea] {\n  line-height: 1.5;\n  margin-block: 30px 12px;\n}\n.activity-group__heading[data-v-c27bd1ea]:first-of-type {\n  margin-block-start: 0;\n}.activity-app[data-v-8f0835f4] {\n  display: flex;\n  flex-direction: column;\n  overflow: hidden;\n}\n.activity-app__empty-content[data-v-8f0835f4] {\n  height: 100%;\n}\n.activity-app__loading-indicator[data-v-8f0835f4] {\n  color: var(--color-text-maxcontrast);\n  justify-self: center;\n  margin-block: 30px 6px;\n  text-align: center;\n}\n.activity-app__container[data-v-8f0835f4] {\n  display: flex;\n  flex-direction: column;\n  height: 100%;\n  width: min(100%, 924px);\n  max-width: 924px;\n  margin: 0 auto;\n  padding-inline: 12px;\n  overflow-y: scroll;\n}\n.activity-app__new-activities-indicator[data-v-8f0835f4] {\n  position: sticky;\n  top: 8px;\n  align-self: center;\n  z-index: 10;\n  padding: 6px 16px;\n  border-radius: var(--border-radius-pill);\n  border: none;\n  background-color: var(--color-primary-element);\n  color: var(--color-primary-element-text);\n  font-weight: bold;\n  cursor: pointer;\n  box-shadow: 0 2px 8px var(--color-box-shadow);\n}\n.activity-app__new-activities-indicator[data-v-8f0835f4]:hover {\n  background-color: var(--color-primary-element-hover);\n}\n.activity-app__heading[data-v-8f0835f4] {\n  font-weight: bold;\n  font-size: 20px;\n  line-height: 44px;\n  margin-top: 1px;\n  margin-inline: calc(2 * var(--app-navigation-padding, 8px) + 44px) var(--app-navigation-padding, 8px);\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-5a15295d] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.app-navigation-toggle-wrapper[data-v-5a15295d] {\n  position: absolute;\n  top: var(--app-navigation-padding);\n  inset-inline-end: calc(0px - var(--app-navigation-padding));\n  margin-inline-end: calc(-1 * var(--default-clickable-area));\n}\nbutton.app-navigation-toggle[data-v-5a15295d] {\n  background-color: var(--color-main-background);\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.app-navigation,\n.app-content {\n  /** Distance of the app navigation toggle and the first navigation item to the top edge of the app content container */\n  --app-navigation-padding: calc(var(--default-grid-baseline, 4px) * 2);\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-d5ce90cd] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.app-navigation[data-v-d5ce90cd] {\n  --color-text-maxcontrast: var(--color-text-maxcontrast-background-blur, var(--color-text-maxcontrast-default));\n  transition: transform var(--animation-quick), margin var(--animation-quick);\n  width: 300px;\n  --app-navigation-max-width: calc(100vw - (var(--app-navigation-padding) + var(--default-clickable-area) + var(--default-grid-baseline)));\n  max-width: var(--app-navigation-max-width);\n  position: relative;\n  top: 0;\n  inset-inline-start: 0;\n  padding: 0px;\n  z-index: 1800;\n  height: 100%;\n  -webkit-user-select: none;\n  -moz-user-select: none;\n  -ms-user-select: none;\n  user-select: none;\n  flex-grow: 0;\n  flex-shrink: 0;\n  background-color: var(--color-main-background-blur, var(--color-main-background));\n  -webkit-backdrop-filter: var(--filter-background-blur, none);\n  backdrop-filter: var(--filter-background-blur, none);\n}\n.app-navigation--closed[data-v-d5ce90cd] {\n  margin-inline-start: calc(-1 * min(300px, var(--app-navigation-max-width)));\n}\n.app-navigation__search[data-v-d5ce90cd] {\n  width: 100%;\n}\n.app-navigation__body[data-v-d5ce90cd] {\n  overflow-y: scroll;\n}\n.app-navigation__content > ul[data-v-d5ce90cd] {\n  position: relative;\n  width: 100%;\n  overflow-x: hidden;\n  overflow-y: auto;\n  display: flex;\n  flex-direction: column;\n  gap: var(--default-grid-baseline, 4px);\n  padding: var(--app-navigation-padding);\n}\n.app-navigation .app-navigation__list[data-v-d5ce90cd] {\n  height: 100%;\n}\n.app-navigation__body--no-list[data-v-d5ce90cd] {\n  flex: 1 1 auto;\n  overflow: auto;\n  height: 100%;\n}\n.app-navigation__content[data-v-d5ce90cd] {\n  height: 100%;\n  display: flex;\n  flex-direction: column;\n}\n[data-themes*=highcontrast] .app-navigation[data-v-d5ce90cd] {\n  border-inline-end: 1px solid var(--color-border);\n}\n@media only screen and (max-width: 1024px) {\n.app-navigation[data-v-d5ce90cd] {\n    position: absolute;\n    border-inline-end: 1px solid var(--color-border);\n}\n}\n@media only screen and (max-width: 512px) {\n.app-navigation[data-v-d5ce90cd] {\n    z-index: 1400;\n}\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-d72957ed] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.app-navigation-list[data-v-d72957ed] {\n  position: relative;\n  width: 100%;\n  overflow-x: hidden;\n  overflow-y: auto;\n  display: flex;\n  flex-direction: column;\n  gap: var(--default-grid-baseline, 4px);\n  padding: var(--app-navigation-padding);\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-e6236e50] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.icon-collapse[data-v-e6236e50] {\n  position: relative;\n  inset-inline-end: 0;\n}\n.icon-collapse[data-v-e6236e50]:hover {\n  background-color: var(--color-background-dark) !important;\n}\n.icon-collapse--active[data-v-e6236e50]:hover {\n  background-color: var(--color-primary-element) !important;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-f925f8d0] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n\n/**\n * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n.app-navigation-entry[data-v-f925f8d0] {\n  position: relative;\n  display: flex;\n  flex-shrink: 0;\n  flex-wrap: wrap;\n  width: 100%;\n  min-height: var(--default-clickable-area);\n  transition: background-color var(--animation-quick) ease-in-out;\n  transition: background-color 200ms ease-in-out;\n  border-radius: var(--border-radius-element);\n}\n.app-navigation-entry-wrapper[data-v-f925f8d0] {\n  position: relative;\n  display: flex;\n  flex-shrink: 0;\n  flex-wrap: wrap;\n  width: 100%;\n}\n.app-navigation-entry-wrapper.app-navigation-entry--collapsible:not(.app-navigation-entry--opened) > ul[data-v-f925f8d0] {\n  display: none;\n}\n.app-navigation-entry.active[data-v-f925f8d0] {\n  background-color: var(--color-primary-element) !important;\n}\n.app-navigation-entry.active[data-v-f925f8d0]:hover {\n  background-color: var(--color-primary-element-hover) !important;\n}\n.app-navigation-entry.active .app-navigation-entry-link[data-v-f925f8d0], .app-navigation-entry.active .app-navigation-entry-button[data-v-f925f8d0] {\n  color: var(--color-primary-element-text) !important;\n}\n.app-navigation-entry[data-v-f925f8d0]:focus-within, .app-navigation-entry[data-v-f925f8d0]:hover {\n  background-color: var(--color-background-hover);\n}\n.app-navigation-entry.active .app-navigation-entry__children[data-v-f925f8d0], .app-navigation-entry:focus-within .app-navigation-entry__children[data-v-f925f8d0], .app-navigation-entry:hover .app-navigation-entry__children[data-v-f925f8d0] {\n  background-color: var(--color-main-background);\n}\n.app-navigation-entry.active .app-navigation-entry__utils .app-navigation-entry__actions[data-v-f925f8d0], .app-navigation-entry.app-navigation-entry--deleted .app-navigation-entry__utils .app-navigation-entry__actions[data-v-f925f8d0], .app-navigation-entry:focus .app-navigation-entry__utils .app-navigation-entry__actions[data-v-f925f8d0], .app-navigation-entry:focus-within .app-navigation-entry__utils .app-navigation-entry__actions[data-v-f925f8d0], .app-navigation-entry:hover .app-navigation-entry__utils .app-navigation-entry__actions[data-v-f925f8d0] {\n  display: inline-block;\n}\n.app-navigation-entry .app-navigation-entry__actions[data-v-f925f8d0]:hover .button-vue {\n  background-color: var(--color-background-dark) !important;\n}\n.app-navigation-entry.active .app-navigation-entry__actions[data-v-f925f8d0]:hover .button-vue {\n  background-color: var(--color-primary-element) !important;\n}\n.app-navigation-entry[data-v-f925f8d0] {\n  /* hide deletion/collapse of subitems */\n}\n.app-navigation-entry.app-navigation-entry--deleted > ul[data-v-f925f8d0] {\n  display: none;\n}\n.app-navigation-entry:not(.app-navigation-entry--editing) .app-navigation-entry-link[data-v-f925f8d0], .app-navigation-entry:not(.app-navigation-entry--editing) .app-navigation-entry-button[data-v-f925f8d0] {\n  padding-inline-end: calc((var(--default-clickable-area) - 16px) / 2);\n}\n.app-navigation-entry .app-navigation-entry-link[data-v-f925f8d0], .app-navigation-entry .app-navigation-entry-button[data-v-f925f8d0] {\n  z-index: 100; /* above the bullet to allow click*/\n  display: flex;\n  overflow: hidden;\n  flex: 1 1 0;\n  min-height: var(--default-clickable-area);\n  padding: 0;\n  white-space: nowrap;\n  color: var(--color-main-text);\n  background-repeat: no-repeat;\n  background-position: calc((var(--default-clickable-area) - 16px) / 2) center;\n  background-size: 16px 16px;\n  line-height: var(--default-clickable-area);\n}\n.app-navigation-entry .app-navigation-entry-link .app-navigation-entry-icon[data-v-f925f8d0], .app-navigation-entry .app-navigation-entry-button .app-navigation-entry-icon[data-v-f925f8d0] {\n  display: flex;\n  align-items: center;\n  flex: 0 0 var(--default-clickable-area);\n  justify-content: center;\n  width: var(--default-clickable-area);\n  height: var(--default-clickable-area);\n  background-size: 16px 16px;\n  background-repeat: no-repeat;\n  background-position: calc((var(--default-clickable-area) - 16px) / 2) center;\n}\n.app-navigation-entry .app-navigation-entry-link .app-navigation-entry__name[data-v-f925f8d0], .app-navigation-entry .app-navigation-entry-button .app-navigation-entry__name[data-v-f925f8d0] {\n  overflow: hidden;\n  max-width: 100%;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n}\n.app-navigation-entry .app-navigation-entry-link .editingContainer[data-v-f925f8d0], .app-navigation-entry .app-navigation-entry-button .editingContainer[data-v-f925f8d0] {\n  width: calc(100% - var(--default-clickable-area));\n  margin: auto;\n}\n.app-navigation-entry .app-navigation-entry-link[data-v-f925f8d0]:focus-visible, .app-navigation-entry .app-navigation-entry-button[data-v-f925f8d0]:focus-visible {\n  box-shadow: 0 0 0 4px var(--color-main-background);\n  outline: 2px solid var(--color-main-text);\n  border-radius: var(--border-radius-element);\n}\n\n/* Second level nesting for lists */\n.app-navigation-entry__children[data-v-f925f8d0] {\n  --app-navigation-item-child-offset: 10px;\n  position: relative;\n  display: flex;\n  flex: 0 1 auto;\n  flex-direction: column;\n  width: 100%;\n  gap: var(--default-grid-baseline, 4px);\n  padding-inline-start: var(--app-navigation-item-child-offset);\n}\n.app-navigation-entry__children .app-navigation-entry[data-v-f925f8d0] {\n  display: inline-flex;\n  flex-wrap: wrap;\n}\n.app-navigation-entry__children .app-navigation-entry__children .app-navigation-entry__children .app-navigation-entry__children .app-navigation-entry__children .app-navigation-entry__children .app-navigation-entry__children[data-v-f925f8d0] {\n  --app-navigation-item-child-offset: 0;\n}\n\n/* Deleted entries */\n.app-navigation-entry__deleted[data-v-f925f8d0] {\n  display: inline-flex;\n  flex: 1 1 0;\n  padding-inline-start: calc(var(--default-clickable-area) - (var(--default-clickable-area) - 16px) / 2) !important;\n}\n.app-navigation-entry__deleted .app-navigation-entry__deleted-description[data-v-f925f8d0] {\n  position: relative;\n  overflow: hidden;\n  flex: 1 1 0;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n  line-height: var(--default-clickable-area);\n}\n\n/* counter and actions */\n.app-navigation-entry__utils[data-v-f925f8d0] {\n  display: flex;\n  min-width: var(--default-clickable-area);\n  align-items: center;\n  flex: 0 1 auto;\n  justify-content: flex-end;\n}\n.app-navigation-entry__utils.app-navigation-entry__utils--display-actions .action-item.app-navigation-entry__actions[data-v-f925f8d0] {\n  display: inline-block;\n}\n.app-navigation-entry__utils[data-v-f925f8d0] {\n  /* counter */\n}\n.app-navigation-entry__utils .app-navigation-entry__counter-wrapper[data-v-f925f8d0] {\n  margin-inline-end: calc(var(--default-grid-baseline) * 2);\n  display: flex;\n  align-items: center;\n  flex: 0 1 auto;\n}\n.app-navigation-entry__utils[data-v-f925f8d0] {\n  /* actions */\n}\n.app-navigation-entry__utils .action-item.app-navigation-entry__actions[data-v-f925f8d0] {\n  display: none;\n}\n\n/* editing state */\n.app-navigation-entry--editing .app-navigation-entry-edit[data-v-f925f8d0] {\n  z-index: 250;\n  opacity: 1;\n}\n\n/* deleted state */\n.app-navigation-entry--deleted .app-navigation-entry-deleted[data-v-f925f8d0] {\n  z-index: 250;\n  transform: translateX(0);\n}\n\n/* pinned state */\n.app-navigation-entry--pinned[data-v-f925f8d0] {\n  order: 2;\n  margin-top: auto;\n}\n.app-navigation-entry--pinned ~ .app-navigation-entry--pinned[data-v-f925f8d0] {\n  margin-top: 0;\n}\n[data-themes*=highcontrast] .app-navigation-entry[data-v-f925f8d0]:active {\n  background-color: var(--color-primary-element-light-hover) !important;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-dd457d48] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.app-navigation-input-confirm[data-v-dd457d48] {\n  flex: 1 0 100%;\n  width: 100%;\n}\n.app-navigation-input-confirm form[data-v-dd457d48] {\n  display: flex;\n}\n.app-navigation-input-confirm__input[data-v-dd457d48] {\n  height: 34px;\n  flex: 1 1 100%;\n  font-size: 100% !important;\n  margin: 5px !important;\n  margin-inline-start: -8px !important;\n  padding: 7px !important;\n}\n.app-navigation-input-confirm__input[data-v-dd457d48]:active, .app-navigation-input-confirm__input[data-v-dd457d48]:focus, .app-navigation-input-confirm__input[data-v-dd457d48]:hover {\n  outline: none;\n  background-color: var(--color-main-background);\n  color: var(--color-main-text);\n  border-color: var(--color-primary-element);\n}\n._container_RFk6U {\n	margin-top: auto;\n	padding: var(--default-grid-baseline);\n}\n._header_2CtDS {\n	margin-block: 0 var(--default-grid-baseline);\n	margin-inline: var(--default-grid-baseline);\n}\n\n/* Overwrite the padding to match NcAppNavigationItem */\n._button_tAyis {\n	padding-inline: 0 calc((var(--default-clickable-area) - 16px) / 2) !important;\n.button-vue__text {\n		font-weight: normal;\n}\n}\n._content_--KYD {\n	display: block;\n	padding: 10px;\n\n	/* prevent scrolled contents from stopping too early */\n	margin-bottom: calc(-1 * var(--default-grid-baseline));\n\n	/* restrict height of settings and make scrollable */\n	max-height: 300px;\n	overflow-y: auto;\n}\n._animationActive_DAG1p {\n	transition-duration: var(--animation-slow);\n	transition-property: max-height, padding;\n	overflow-y: hidden !important;\n}\n._animationStop_fWYQj {\n	max-height: 0 !important;\n	padding: 0 10px !important;\n}\n.app-activity .settings-link {\n  max-width: 100%;\n}\n.app-activity .app-navigation-entry.active .navigation-icon {\n  filter: var(--primary-invert-if-dark);\n}\n.app-activity .app-navigation-entry:not(.active) .navigation-icon {\n  filter: var(--background-invert-if-dark);\n}\n.app-activity .navigation-icon {\n  height: 16px;\n  width: 16px;\n}'));
      document.head.appendChild(elementStyle);
    }
  } catch (e) {
    console.error("vite-plugin-css-injected-by-js", e);
  }
})();
const appName = "activity";
const appVersion = "7.0.0-dev.0";
import { r as register, _ as _export_sfc, t as t30, a as t, N as NcButton, u as useIsMobile, b as NcIconSvgWrapper, c as _export_sfc$1, l as logger, d as loadState, e as t27, i as isRtl, f as isLegacy, g as createElementId, h as useRoute, j as cancelableClient, k as generateOcsUrl, m as axios, n as NcLoadingIcon, o as t20, p as createFocusTrap, q as getTrapStack, s as t14, v as t21, w as t50, x as t23, y as NcActions, z as t46, A as generateUrl, B as NcCheckboxRadioSwitch, C as createRouter, D as createWebHistory } from "./NcCheckboxRadioSwitch-BMsPx74L-CGnJCDi8.chunk.mjs";
import { d as defineComponent, p as provide, c as computed, r as ref, o as onBeforeMount, a as createElementBlock, b as openBlock, e as createBlock, f as renderSlot, g as createBaseVNode, w as withDirectives, t as toDisplayString, u as unref, h as createVNode, v as vShow, i as withCtx, j as createTextVNode, k as withModifiers, T as Teleport, n as normalizeClass, l as nextTick, m as resolveComponent, q as useSlots, s as watch, x as onMounted, y as onBeforeUnmount, z as resolveDynamicComponent, A as inject, B as getCurrentInstance, C as normalizeStyle, D as h, E as createCommentVNode, F as Fragment, G as mergeProps, H as mergeModels, I as useModel, J as useAttrs, K as useTemplateRef, L as warn, M as translate, N as renderList, O as onUnmounted, P as watchEffect, Q as withKeys, R as vModelText, S as normalizeProps, U as guardReactiveProps, V as Transition, W as createSlots, X as createApp } from "./translation-DoG5ZELJ-BRHGJcXa.chunk.mjs";
import { e as emit, g as getBuilder, s as subscribe, u as unsubscribe } from "./index-C6VBhB33.chunk.mjs";
import { s as showError, a as showSuccess } from "./index-C1xmmKTZ-BecyqZV8.chunk.mjs";
import { g as getCapabilities, u as useSwipe, m as mdiArrowRight, a as mdiCheck, b as mdiAlertCircleOutline, A as ActivityComponent, c as moment, d as useInfiniteScroll, e as useDebounceFn, f as useDocumentVisibility, h as ActivityModel, N as NcEmptyContent, i as mdiMenuOpen, j as mdiMenu, k as NcActionButton, o as onClickOutside, l as mdiCog, n as mdiCogOutline } from "./ActivityComponent-CxDKYEWx.chunk.mjs";
import { l as logger$1 } from "./logger-uIIWoPgu.chunk.mjs";
import "./preload-helper-BNx4Cq8O.chunk.mjs";
/*!
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
const HAS_APP_NAVIGATION_KEY = /* @__PURE__ */ Symbol.for("NcContent:setHasAppNavigation");
const CONTENT_SELECTOR_KEY = /* @__PURE__ */ Symbol.for("NcContent:selector");
register(t30);
const contentSvg = '<!--\n  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors\n  - SPDX-License-Identifier: AGPL-3.0-or-later\n-->\n<svg width="395" height="314" viewBox="0 0 395 314" fill="none" xmlns="http://www.w3.org/2000/svg">\n<rect width="395" height="314" rx="11" fill="#439DCD"/>\n<rect x="13" y="51" width="366" height="248" rx="8" fill="white"/>\n<rect x="22" y="111" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="127" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="63" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="191" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="143" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="79" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="159" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="95" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="175" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<path d="M288 145C277.56 147.8 265.32 149 254 149C242.68 149 230.44 147.8 220 145L218 153C225.44 155 234 156.32 242 157V209H250V185H258V209H266V157C274 156.32 282.56 155 290 153L288 145ZM254 145C258.4 145 262 141.4 262 137C262 132.6 258.4 129 254 129C249.6 129 246 132.6 246 137C246 141.4 249.6 145 254 145Z" fill="#DEDEDE"/>\n<path d="M43.5358 13C38.6641 13 34.535 16.2415 33.2552 20.6333C32.143 18.3038 29.7327 16.6718 26.9564 16.6718C23.1385 16.6718 20 19.7521 20 23.4993C20 27.2465 23.1385 30.3282 26.9564 30.3282C29.7327 30.3282 32.1429 28.6952 33.2552 26.3653C34.535 30.7575 38.6641 34 43.5358 34C48.3715 34 52.4796 30.8064 53.7921 26.4637C54.9249 28.7407 57.3053 30.3282 60.0421 30.3282C63.8601 30.3282 67 27.2465 67 23.4993C67 19.7521 63.8601 16.6718 60.0421 16.6718C57.3053 16.6718 54.9249 18.2583 53.7921 20.5349C52.4796 16.1926 48.3715 13 43.5358 13ZM43.5358 17.0079C47.2134 17.0079 50.1512 19.8899 50.1512 23.4993C50.1512 27.1087 47.2134 29.9921 43.5358 29.9921C39.8583 29.9921 36.9218 27.1087 36.9218 23.4993C36.9218 19.8899 39.8583 17.0079 43.5358 17.0079ZM26.9564 20.6797C28.5677 20.6797 29.8307 21.9179 29.8307 23.4993C29.8307 25.0807 28.5677 26.3203 26.9564 26.3203C25.3452 26.3203 24.0836 25.0807 24.0836 23.4993C24.0836 21.9179 25.3452 20.6797 26.9564 20.6797ZM60.0421 20.6797C61.6534 20.6797 62.9164 21.9179 62.9164 23.4993C62.9164 25.0807 61.6534 26.3203 60.0421 26.3203C58.4309 26.3203 57.1693 25.0807 57.1693 23.4993C57.1693 21.9179 58.4309 20.6797 60.0421 20.6797Z" fill="white"/>\n<rect x="79" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="99" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="119" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="139" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="159" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="179" y="20" width="8" height="8" rx="4" fill="white"/>\n<path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C5.37258 0 0 5.37259 0 12V302C0 308.627 5.37259 314 12 314H383C389.627 314 395 308.627 395 302V12C395 5.37258 389.627 0 383 0H12ZM140 44C132.268 44 126 50.268 126 58V292C126 299.732 132.268 306 140 306H372C379.732 306 386 299.732 386 292V58C386 50.268 379.732 44 372 44H140Z" fill="black" fill-opacity="0.35"/>\n</svg>\n';
const navigationSvg = '<!--\n  - SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors\n  - SPDX-License-Identifier: AGPL-3.0-or-later\n-->\n<svg width="395" height="314" viewBox="0 0 395 314" fill="none" xmlns="http://www.w3.org/2000/svg">\n<rect width="395" height="314" rx="11" fill="#439DCD"/>\n<rect x="13" y="51" width="366" height="248" rx="8" fill="white"/>\n<rect x="22" y="111" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="127" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="63" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="191" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="143" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="79" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="159" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="95" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<rect x="22" y="175" width="92" height="12" rx="6" fill="#DEDEDE"/>\n<path d="M288 145C277.56 147.8 265.32 149 254 149C242.68 149 230.44 147.8 220 145L218 153C225.44 155 234 156.32 242 157V209H250V185H258V209H266V157C274 156.32 282.56 155 290 153L288 145ZM254 145C258.4 145 262 141.4 262 137C262 132.6 258.4 129 254 129C249.6 129 246 132.6 246 137C246 141.4 249.6 145 254 145Z" fill="#DEDEDE"/>\n<path d="M43.5358 13C38.6641 13 34.535 16.2415 33.2552 20.6333C32.143 18.3038 29.7327 16.6718 26.9564 16.6718C23.1385 16.6718 20 19.7521 20 23.4993C20 27.2465 23.1385 30.3282 26.9564 30.3282C29.7327 30.3282 32.1429 28.6952 33.2552 26.3653C34.535 30.7575 38.6641 34 43.5358 34C48.3715 34 52.4796 30.8064 53.7921 26.4637C54.9249 28.7407 57.3053 30.3282 60.0421 30.3282C63.8601 30.3282 67 27.2465 67 23.4993C67 19.7521 63.8601 16.6718 60.0421 16.6718C57.3053 16.6718 54.9249 18.2583 53.7921 20.5349C52.4796 16.1926 48.3715 13 43.5358 13ZM43.5358 17.0079C47.2134 17.0079 50.1512 19.8899 50.1512 23.4993C50.1512 27.1087 47.2134 29.9921 43.5358 29.9921C39.8583 29.9921 36.9218 27.1087 36.9218 23.4993C36.9218 19.8899 39.8583 17.0079 43.5358 17.0079ZM26.9564 20.6797C28.5677 20.6797 29.8307 21.9179 29.8307 23.4993C29.8307 25.0807 28.5677 26.3203 26.9564 26.3203C25.3452 26.3203 24.0836 25.0807 24.0836 23.4993C24.0836 21.9179 25.3452 20.6797 26.9564 20.6797ZM60.0421 20.6797C61.6534 20.6797 62.9164 21.9179 62.9164 23.4993C62.9164 25.0807 61.6534 26.3203 60.0421 26.3203C58.4309 26.3203 57.1693 25.0807 57.1693 23.4993C57.1693 21.9179 58.4309 20.6797 60.0421 20.6797Z" fill="white"/>\n<rect x="79" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="99" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="119" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="139" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="159" y="20" width="8" height="8" rx="4" fill="white"/>\n<rect x="179" y="20" width="8" height="8" rx="4" fill="white"/>\n<path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C5.37258 0 0 5.37259 0 12V302C0 308.627 5.37259 314 12 314H383C389.627 314 395 308.627 395 302V12C395 5.37258 389.627 0 383 0H12ZM112 44C119.732 44 126 50.268 126 58V292C126 299.732 119.732 306 112 306H20C12.268 306 6 299.732 6 292V58C6 50.268 12.268 44 20 44H112Z" fill="black" fill-opacity="0.35"/>\n</svg>\n';
const _hoisted_1$f = { class: "vue-skip-actions__container" };
const _hoisted_2$d = { class: "vue-skip-actions__headline" };
const _hoisted_3$9 = { class: "vue-skip-actions__buttons" };
const _sfc_main$i = /* @__PURE__ */ defineComponent({
  __name: "NcContent",
  props: {
    appName: {}
  },
  setup(__props) {
    const props = __props;
    provide(HAS_APP_NAVIGATION_KEY, setAppNavigation);
    provide(CONTENT_SELECTOR_KEY, "#content-vue");
    provide("appName", computed(() => props.appName));
    const isMobile = useIsMobile();
    const hasAppNavigation = ref(false);
    const currentFocus = ref();
    const currentImage = computed(() => currentFocus.value === "navigation" ? navigationSvg : contentSvg);
    onBeforeMount(() => {
      const container2 = document.getElementById("skip-actions");
      if (container2) {
        container2.innerHTML = "";
        container2.classList.add("vue-skip-actions");
      }
    });
    function openAppNavigation() {
      emit("toggle-navigation", { open: true });
      nextTick(() => {
        window.location.hash = "app-navigation-vue";
        document.getElementById("app-navigation-vue").focus();
      });
    }
    function setAppNavigation(value) {
      hasAppNavigation.value = value;
      if (!currentFocus.value) {
        currentFocus.value = "navigation";
      }
    }
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        id: "content-vue",
        class: normalizeClass(["content", `app-${_ctx.appName.toLowerCase()}`])
      }, [
        (openBlock(), createBlock(Teleport, { to: "#skip-actions" }, [
          createBaseVNode("div", _hoisted_1$f, [
            createBaseVNode("div", _hoisted_2$d, toDisplayString(unref(t)("Keyboard navigation help")), 1),
            createBaseVNode("div", _hoisted_3$9, [
              withDirectives(createVNode(NcButton, {
                href: "#app-navigation-vue",
                variant: "tertiary",
                onClick: withModifiers(openAppNavigation, ["prevent"]),
                onFocusin: _cache[0] || (_cache[0] = ($event) => currentFocus.value = "navigation"),
                onMouseover: _cache[1] || (_cache[1] = ($event) => currentFocus.value = "navigation")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(unref(t)("Skip to app navigation")), 1)
                ]),
                _: 1
              }, 512), [
                [vShow, hasAppNavigation.value]
              ]),
              createVNode(NcButton, {
                href: "#app-content-vue",
                variant: "tertiary",
                onFocusin: _cache[2] || (_cache[2] = ($event) => currentFocus.value = "content"),
                onMouseover: _cache[3] || (_cache[3] = ($event) => currentFocus.value = "content")
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(unref(t)("Skip to main content")), 1)
                ]),
                _: 1
              })
            ]),
            withDirectives(createVNode(NcIconSvgWrapper, {
              class: "vue-skip-actions__image",
              svg: currentImage.value,
              size: "auto"
            }, null, 8, ["svg"]), [
              [vShow, !unref(isMobile)]
            ])
          ])
        ])),
        renderSlot(_ctx.$slots, "default", {}, void 0, true)
      ], 2);
    };
  }
});
const NcContent = /* @__PURE__ */ _export_sfc(_sfc_main$i, [["__scopeId", "data-v-d9b0d7e8"]]);
const _sfc_main$h = /* @__PURE__ */ defineComponent({
  __name: "ActivityApp",
  setup(__props, { expose: __expose }) {
    __expose();
    const __returned__ = { get NcContent() {
      return NcContent;
    } };
    Object.defineProperty(__returned__, "__isScriptSetup", { enumerable: false, value: true });
    return __returned__;
  }
});
function _sfc_render$e(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_router_view = resolveComponent("router-view");
  return openBlock(), createBlock($setup["NcContent"], { "app-name": "activity" }, {
    default: withCtx(() => [
      createVNode(_component_router_view, { name: "navigation" }),
      createVNode(_component_router_view)
    ]),
    _: 1
    /* STABLE */
  });
}
const ActivityApp = /* @__PURE__ */ _export_sfc$1(_sfc_main$h, [["render", _sfc_render$e], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/ActivityApp.vue"]]);
const Pe = {
  __name: "splitpanes",
  props: {
    horizontal: { type: Boolean, default: false },
    pushOtherPanes: { type: Boolean, default: true },
    maximizePanes: { type: Boolean, default: true },
    // Maximize pane on splitter double click/tap.
    rtl: { type: Boolean, default: false },
    // Right to left direction.
    firstSplitter: { type: Boolean, default: false }
  },
  emits: [
    "ready",
    "resize",
    "resized",
    "pane-click",
    "pane-maximize",
    "pane-add",
    "pane-remove",
    "splitter-click",
    "splitter-dblclick"
  ],
  setup(D, { emit: h$1 }) {
    const y = h$1, u = D, E = useSlots(), l = ref([]), M = computed(() => l.value.reduce((e, n) => (e[~~n.id] = n) && e, {})), m = computed(() => l.value.length), x = ref(null), S = ref(false), c = ref({
      mouseDown: false,
      dragging: false,
      activeSplitter: null,
      cursorOffset: 0
      // Cursor offset within the splitter.
    }), f = ref({
      // Used to detect double click on touch devices.
      splitter: null,
      timeoutId: null
    }), _ = computed(() => ({
      [`splitpanes splitpanes--${u.horizontal ? "horizontal" : "vertical"}`]: true,
      "splitpanes--dragging": c.value.dragging
    })), R = () => {
      document.addEventListener("mousemove", r, { passive: false }), document.addEventListener("mouseup", P), "ontouchstart" in window && (document.addEventListener("touchmove", r, { passive: false }), document.addEventListener("touchend", P));
    }, O = () => {
      document.removeEventListener("mousemove", r, { passive: false }), document.removeEventListener("mouseup", P), "ontouchstart" in window && (document.removeEventListener("touchmove", r, { passive: false }), document.removeEventListener("touchend", P));
    }, b = (e, n) => {
      const t2 = e.target.closest(".splitpanes__splitter");
      if (t2) {
        const { left: i, top: a } = t2.getBoundingClientRect(), { clientX: s, clientY: o } = "ontouchstart" in window && e.touches ? e.touches[0] : e;
        c.value.cursorOffset = u.horizontal ? o - a : s - i;
      }
      R(), c.value.mouseDown = true, c.value.activeSplitter = n;
    }, r = (e) => {
      c.value.mouseDown && (e.preventDefault(), c.value.dragging = true, requestAnimationFrame(() => {
        K(I(e)), d("resize", { event: e }, true);
      }));
    }, P = (e) => {
      c.value.dragging && (window.getSelection().removeAllRanges(), d("resized", { event: e }, true)), c.value.mouseDown = false, c.value.activeSplitter = null, setTimeout(() => {
        c.value.dragging = false, O();
      }, 100);
    }, A = (e, n) => {
      "ontouchstart" in window && (e.preventDefault(), f.value.splitter === n ? (clearTimeout(f.value.timeoutId), f.value.timeoutId = null, U(e, n), f.value.splitter = null) : (f.value.splitter = n, f.value.timeoutId = setTimeout(() => f.value.splitter = null, 500))), c.value.dragging || d("splitter-click", { event: e, index: n }, true);
    }, U = (e, n) => {
      if (d("splitter-dblclick", { event: e, index: n }, true), u.maximizePanes) {
        let t2 = 0;
        l.value = l.value.map((i, a) => (i.size = a === n ? i.max : i.min, a !== n && (t2 += i.min), i)), l.value[n].size -= t2, d("pane-maximize", { event: e, index: n, pane: l.value[n] }), d("resized", { event: e, index: n }, true);
      }
    }, W = (e, n) => {
      d("pane-click", {
        event: e,
        index: M.value[n].index,
        pane: M.value[n]
      });
    }, I = (e) => {
      const n = x.value.getBoundingClientRect(), { clientX: t2, clientY: i } = "ontouchstart" in window && e.touches ? e.touches[0] : e;
      return {
        x: t2 - (u.horizontal ? 0 : c.value.cursorOffset) - n.left,
        y: i - (u.horizontal ? c.value.cursorOffset : 0) - n.top
      };
    }, J = (e) => {
      e = e[u.horizontal ? "y" : "x"];
      const n = x.value[u.horizontal ? "clientHeight" : "clientWidth"];
      return u.rtl && !u.horizontal && (e = n - e), e * 100 / n;
    }, K = (e) => {
      const n = c.value.activeSplitter;
      let t2 = {
        prevPanesSize: $(n),
        nextPanesSize: N(n),
        prevReachedMinPanes: 0,
        nextReachedMinPanes: 0
      };
      const i = 0 + (u.pushOtherPanes ? 0 : t2.prevPanesSize), a = 100 - (u.pushOtherPanes ? 0 : t2.nextPanesSize), s = Math.max(Math.min(J(e), a), i);
      let o = [n, n + 1], v = l.value[o[0]] || null, p = l.value[o[1]] || null;
      const H = v.max < 100 && s >= v.max + t2.prevPanesSize, ue = p.max < 100 && s <= 100 - (p.max + N(n + 1));
      if (H || ue) {
        H ? (v.size = v.max, p.size = Math.max(100 - v.max - t2.prevPanesSize - t2.nextPanesSize, 0)) : (v.size = Math.max(100 - p.max - t2.prevPanesSize - N(n + 1), 0), p.size = p.max);
        return;
      }
      if (u.pushOtherPanes) {
        const j = Q(t2, s);
        if (!j) return;
        ({ sums: t2, panesToResize: o } = j), v = l.value[o[0]] || null, p = l.value[o[1]] || null;
      }
      v !== null && (v.size = Math.min(Math.max(s - t2.prevPanesSize - t2.prevReachedMinPanes, v.min), v.max)), p !== null && (p.size = Math.min(Math.max(100 - s - t2.nextPanesSize - t2.nextReachedMinPanes, p.min), p.max));
    }, Q = (e, n) => {
      const t2 = c.value.activeSplitter, i = [t2, t2 + 1];
      return n < e.prevPanesSize + l.value[i[0]].min && (i[0] = V(t2).index, e.prevReachedMinPanes = 0, i[0] < t2 && l.value.forEach((a, s) => {
        s > i[0] && s <= t2 && (a.size = a.min, e.prevReachedMinPanes += a.min);
      }), e.prevPanesSize = $(i[0]), i[0] === void 0) ? (e.prevReachedMinPanes = 0, l.value[0].size = l.value[0].min, l.value.forEach((a, s) => {
        s > 0 && s <= t2 && (a.size = a.min, e.prevReachedMinPanes += a.min);
      }), l.value[i[1]].size = 100 - e.prevReachedMinPanes - l.value[0].min - e.prevPanesSize - e.nextPanesSize, null) : n > 100 - e.nextPanesSize - l.value[i[1]].min && (i[1] = Z(t2).index, e.nextReachedMinPanes = 0, i[1] > t2 + 1 && l.value.forEach((a, s) => {
        s > t2 && s < i[1] && (a.size = a.min, e.nextReachedMinPanes += a.min);
      }), e.nextPanesSize = N(i[1] - 1), i[1] === void 0) ? (e.nextReachedMinPanes = 0, l.value.forEach((a, s) => {
        s < m.value - 1 && s >= t2 + 1 && (a.size = a.min, e.nextReachedMinPanes += a.min);
      }), l.value[i[0]].size = 100 - e.prevPanesSize - N(i[0] - 1), null) : { sums: e, panesToResize: i };
    }, $ = (e) => l.value.reduce((n, t2, i) => n + (i < e ? t2.size : 0), 0), N = (e) => l.value.reduce((n, t2, i) => n + (i > e + 1 ? t2.size : 0), 0), V = (e) => [...l.value].reverse().find((t2) => t2.index < e && t2.size > t2.min) || {}, Z = (e) => l.value.find((t2) => t2.index > e + 1 && t2.size > t2.min) || {}, ee = () => {
      var n;
      const e = Array.from(((n = x.value) == null ? void 0 : n.children) || []);
      for (const t2 of e) {
        const i = t2.classList.contains("splitpanes__pane"), a = t2.classList.contains("splitpanes__splitter");
        !i && !a && (t2.remove(), console.warn("Splitpanes: Only <pane> elements are allowed at the root of <splitpanes>. One of your DOM nodes was removed."));
      }
    }, F = (e, n, t2 = false) => {
      const i = e - 1, a = document.createElement("div");
      a.classList.add("splitpanes__splitter"), t2 || (a.onmousedown = (s) => b(s, i), typeof window < "u" && "ontouchstart" in window && (a.ontouchstart = (s) => b(s, i)), a.onclick = (s) => A(s, i + 1)), a.ondblclick = (s) => U(s, i + 1), n.parentNode.insertBefore(a, n);
    }, ne = (e) => {
      e.onmousedown = void 0, e.onclick = void 0, e.ondblclick = void 0, e.remove();
    }, C = () => {
      var t2;
      const e = Array.from(((t2 = x.value) == null ? void 0 : t2.children) || []);
      for (const i of e)
        i.className.includes("splitpanes__splitter") && ne(i);
      let n = 0;
      for (const i of e)
        i.className.includes("splitpanes__pane") && (!n && u.firstSplitter ? F(n, i, true) : n && F(n, i), n++);
    }, ie = ({ uid: e, ...n }) => {
      const t2 = M.value[e];
      for (const [i, a] of Object.entries(n)) t2[i] = a;
    }, te = (e) => {
      var t2;
      let n = -1;
      Array.from(((t2 = x.value) == null ? void 0 : t2.children) || []).some((i) => (i.className.includes("splitpanes__pane") && n++, i.isSameNode(e.el))), l.value.splice(n, 0, { ...e, index: n }), l.value.forEach((i, a) => i.index = a), S.value && nextTick(() => {
        C(), L({ addedPane: l.value[n] }), d("pane-add", { pane: l.value[n] });
      });
    }, ae = (e) => {
      const n = l.value.findIndex((i) => i.id === e);
      l.value[n].el = null;
      const t2 = l.value.splice(n, 1)[0];
      l.value.forEach((i, a) => i.index = a), nextTick(() => {
        C(), d("pane-remove", { pane: t2 }), L({ removedPane: { ...t2 } });
      });
    }, L = (e = {}) => {
      !e.addedPane && !e.removedPane ? le() : l.value.some((n) => n.givenSize !== null || n.min || n.max < 100) ? oe(e) : se(), S.value && d("resized");
    }, se = () => {
      const e = 100 / m.value;
      let n = 0;
      const t2 = [], i = [];
      for (const a of l.value)
        a.size = Math.max(Math.min(e, a.max), a.min), n -= a.size, a.size >= a.max && t2.push(a.id), a.size <= a.min && i.push(a.id);
      n > 0.1 && q(n, t2, i);
    }, le = () => {
      let e = 100;
      const n = [], t2 = [];
      let i = 0;
      for (const s of l.value)
        e -= s.size, s.givenSize !== null && i++, s.size >= s.max && n.push(s.id), s.size <= s.min && t2.push(s.id);
      let a = 100;
      if (e > 0.1) {
        for (const s of l.value)
          s.givenSize === null && (s.size = Math.max(Math.min(e / (m.value - i), s.max), s.min)), a -= s.size;
        a > 0.1 && q(a, n, t2);
      }
    }, oe = ({ addedPane: e, removedPane: n } = {}) => {
      let t2 = 100 / m.value, i = 0;
      const a = [], s = [];
      ((e == null ? void 0 : e.givenSize) ?? null) !== null && (t2 = (100 - e.givenSize) / (m.value - 1));
      for (const o of l.value)
        i -= o.size, o.size >= o.max && a.push(o.id), o.size <= o.min && s.push(o.id);
      if (!(Math.abs(i) < 0.1)) {
        for (const o of l.value)
          (e == null ? void 0 : e.givenSize) !== null && (e == null ? void 0 : e.id) === o.id || (o.size = Math.max(Math.min(t2, o.max), o.min)), i -= o.size, o.size >= o.max && a.push(o.id), o.size <= o.min && s.push(o.id);
        i > 0.1 && q(i, a, s);
      }
    }, q = (e, n, t2) => {
      let i;
      e > 0 ? i = e / (m.value - n.length) : i = e / (m.value - t2.length), l.value.forEach((a, s) => {
        if (e > 0 && !n.includes(a.id)) {
          const o = Math.max(Math.min(a.size + i, a.max), a.min), v = o - a.size;
          e -= v, a.size = o;
        } else if (!t2.includes(a.id)) {
          const o = Math.max(Math.min(a.size + i, a.max), a.min), v = o - a.size;
          e -= v, a.size = o;
        }
      }), Math.abs(e) > 0.1 && nextTick(() => {
        S.value && console.warn("Splitpanes: Could not resize panes correctly due to their constraints.");
      });
    }, d = (e, n = void 0, t2 = false) => {
      const i = (n == null ? void 0 : n.index) ?? c.value.activeSplitter ?? null;
      y(e, {
        ...n,
        ...i !== null && { index: i },
        ...t2 && i !== null && {
          prevPane: l.value[i - (u.firstSplitter ? 1 : 0)],
          nextPane: l.value[i + (u.firstSplitter ? 0 : 1)]
        },
        panes: l.value.map((a) => ({ min: a.min, max: a.max, size: a.size }))
      });
    };
    watch(() => u.firstSplitter, () => C()), onMounted(() => {
      ee(), C(), L(), d("ready"), S.value = true;
    }), onBeforeUnmount(() => S.value = false);
    const re = () => {
      var e;
      return h(
        "div",
        { ref: x, class: _.value },
        (e = E.default) == null ? void 0 : e.call(E)
      );
    };
    return provide("panes", l), provide("indexedPanes", M), provide("horizontal", computed(() => u.horizontal)), provide("requestUpdate", ie), provide("onPaneAdd", te), provide("onPaneRemove", ae), provide("onPaneClick", W), (e, n) => (openBlock(), createBlock(resolveDynamicComponent(re)));
  }
}, ge = {
  __name: "pane",
  props: {
    size: { type: [Number, String] },
    minSize: { type: [Number, String], default: 0 },
    maxSize: { type: [Number, String], default: 100 }
  },
  setup(D) {
    var b;
    const h2 = D, y = inject("requestUpdate"), u = inject("onPaneAdd"), E = inject("horizontal"), l = inject("onPaneRemove"), M = inject("onPaneClick"), m = (b = getCurrentInstance()) == null ? void 0 : b.uid, x = inject("indexedPanes"), S = computed(() => x.value[m]), c = ref(null), f = computed(() => {
      const r = isNaN(h2.size) || h2.size === void 0 ? 0 : parseFloat(h2.size);
      return Math.max(Math.min(r, R.value), _.value);
    }), _ = computed(() => {
      const r = parseFloat(h2.minSize);
      return isNaN(r) ? 0 : r;
    }), R = computed(() => {
      const r = parseFloat(h2.maxSize);
      return isNaN(r) ? 100 : r;
    }), O = computed(() => {
      var r;
      return `${E.value ? "height" : "width"}: ${(r = S.value) == null ? void 0 : r.size}%`;
    });
    return watch(() => f.value, (r) => y({ uid: m, size: r })), watch(() => _.value, (r) => y({ uid: m, min: r })), watch(() => R.value, (r) => y({ uid: m, max: r })), onMounted(() => {
      u({
        id: m,
        el: c.value,
        min: _.value,
        max: R.value,
        // The given size (useful to know the user intention).
        givenSize: h2.size === void 0 ? null : f.value,
        size: f.value
        // The computed current size at any time.
      });
    }), onBeforeUnmount(() => l(m)), (r, P) => (openBlock(), createElementBlock("div", {
      ref_key: "paneEl",
      ref: c,
      class: "splitpanes__pane",
      onClick: P[0] || (P[0] = (A) => unref(M)(A, r._.uid)),
      style: normalizeStyle(O.value)
    }, [
      renderSlot(r.$slots, "default")
    ], 4));
  }
};
/*!
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
function once(func) {
  let wasCalled = false;
  let result;
  return (...args) => {
    if (!wasCalled) {
      wasCalled = true;
      result = func(...args);
    }
    return result;
  };
}
let realAppName = "missing-app-name";
try {
  realAppName = appName;
} catch {
  logger.error("The `@nextcloud/vue` library was used without setting / replacing the `appName`.");
}
const APP_NAME = realAppName;
let realAppVersion = "";
try {
  realAppVersion = appVersion;
} catch {
  logger.error("The `@nextcloud/vue` library was used without setting / replacing the `appVersion`.");
}
function useAppName() {
  return inject("appName", APP_NAME);
}
const useLocalizedAppName = once(() => {
  const apps = loadState("core", "apps", []);
  const realAppName2 = useAppName();
  return apps.find(({ id }) => id === realAppName2)?.name ?? realAppName2;
});
register(t27);
const _sfc_main$1$3 = /* @__PURE__ */ defineComponent({
  __name: "NcAppContentDetailsToggle",
  setup(__props) {
    const isMobile = useIsMobile();
    watch(isMobile, toggleAppNavigationButton);
    onMounted(() => {
      toggleAppNavigationButton(isMobile.value);
    });
    onBeforeUnmount(() => {
      if (isMobile.value) {
        toggleAppNavigationButton(false);
      }
    });
    function toggleAppNavigationButton(hide = true) {
      const appNavigationToggle = document.querySelector(".app-navigation .app-navigation-toggle");
      if (appNavigationToggle) {
        appNavigationToggle.style.display = hide ? "none" : "";
        if (hide === true) {
          emit("toggle-navigation", { open: false });
        }
      }
    }
    return (_ctx, _cache) => {
      return openBlock(), createBlock(unref(NcButton), {
        "aria-label": unref(t)("Go back to the list"),
        class: normalizeClass(["app-details-toggle", { "app-details-toggle--mobile": unref(isMobile) }]),
        title: unref(t)("Go back to the list"),
        variant: "tertiary"
      }, {
        icon: withCtx(() => [
          createVNode(unref(NcIconSvgWrapper), {
            directional: "",
            path: unref(mdiArrowRight)
          }, null, 8, ["path"])
        ]),
        _: 1
      }, 8, ["aria-label", "class", "title"]);
    };
  }
});
const NcAppContentDetailsToggle = /* @__PURE__ */ _export_sfc(_sfc_main$1$3, [["__scopeId", "data-v-a28923a1"]]);
const browserStorage = getBuilder("nextcloud").persist().build();
const instanceName = getCapabilities().theming?.name ?? "Nextcloud";
const _sfc_main$g = {
  name: "NcAppContent",
  components: {
    NcAppContentDetailsToggle,
    Pane: ge,
    Splitpanes: Pe
  },
  props: {
    /**
     * Allows to disable the control by swipe of the app navigation open state.
     */
    disableSwipe: {
      type: Boolean,
      default: false
    },
    /**
     * Allows you to set the default width of the resizable list in % on vertical-split
     * or respectively the default height on horizontal-split.
     *
     * Must be between `listMinWidth` and `listMaxWidth`.
     */
    listSize: {
      type: Number,
      default: 20
    },
    /**
     * Allows you to set the minimum width of the list column in % on vertical-split
     * or respectively the minimum height on horizontal-split.
     */
    listMinWidth: {
      type: Number,
      default: 15
    },
    /**
     * Allows you to set the maximum width of the list column in % on vertical-split
     * or respectively the maximum height on horizontal-split.
     */
    listMaxWidth: {
      type: Number,
      default: 40
    },
    /**
     * Specify the config key for the pane config sizes
     * Default is the global var appName if you use the webpack-vue-config
     */
    paneConfigKey: {
      type: String,
      default: ""
    },
    /**
     * When in mobile view, only the list or the details are shown.
     *
     * If you provide a list, you need to provide a variable
     * that will be set to true by the user when an element of
     * the list gets selected. The details will then show a back
     * arrow to return to the list that will update this prop to false.
     */
    showDetails: {
      type: Boolean,
      default: true
    },
    /**
     * Content layout used when there is a list together with content:
     * - `vertical-split` - a 2-column layout with list and default content separated vertically
     * - `no-split` - a single column layout; List is shown when `showDetails` is `false`, otherwise the default slot content is shown with a back button to return to the list.
     * - 'horizontal-split' - a 2-column layout with list and default content separated horizontally
     * On mobile screen `no-split` layout is forced.
     */
    layout: {
      type: String,
      default: "vertical-split",
      validator(value) {
        return ["no-split", "vertical-split", "horizontal-split"].includes(value);
      }
    },
    /**
     * Specify the `<h1>` page heading
     */
    pageHeading: {
      type: String,
      default: null
    },
    /**
     * Allow setting the page's `<title>`
     *
     * If a page heading is set it defaults to `{pageHeading} - {appName} - {instanceName}` e.g. `Favorites - Files - MyPersonalCloud`.
     * When the page heading and the app name is the same only one is used, e.g. `Files - Files - MyPersonalCloud` is shown as `Files - MyPersonalCloud`.
     * When setting the prop then the following format will be used: `{pageTitle} - {instanceName}`
     */
    pageTitle: {
      type: String,
      default: null
    }
  },
  emits: [
    "update:showDetails",
    "resizeList"
  ],
  setup() {
    return {
      appName: useAppName(),
      localizedAppName: useLocalizedAppName(),
      isMobile: useIsMobile(),
      isRtl
    };
  },
  data() {
    return {
      contentHeight: 0,
      swiping: {},
      listPaneSize: this.restorePaneConfig()
    };
  },
  computed: {
    paneConfigID() {
      if (this.paneConfigKey !== "") {
        return `pane-list-size-${this.paneConfigKey}`;
      }
      try {
        return `pane-list-size-${this.appName}`;
      } catch {
        logger.info("[NcAppContent]: falling back to global nextcloud pane config");
        return "pane-list-size-nextcloud";
      }
    },
    detailsPaneSize() {
      if (this.listPaneSize) {
        return 100 - this.listPaneSize;
      }
      return this.paneDefaults.details.size;
    },
    paneDefaults() {
      return {
        list: {
          size: this.listSize,
          min: this.listMinWidth,
          max: this.listMaxWidth
        },
        // set the inverse values of the details column
        // based on the provided (or default) values of the list column
        details: {
          size: 100 - this.listSize,
          min: 100 - this.listMaxWidth,
          max: 100 - this.listMinWidth
        }
      };
    },
    realPageTitle() {
      const entries = /* @__PURE__ */ new Set();
      if (this.pageTitle) {
        for (const part of this.pageTitle.split(" - ")) {
          entries.add(part);
        }
      } else if (this.pageHeading) {
        for (const part of this.pageHeading.split(" - ")) {
          entries.add(part);
        }
        if (entries.size > 0) {
          entries.add(this.localizedAppName);
        }
      } else {
        return null;
      }
      entries.add(instanceName);
      return [...entries.values()].join(" - ");
    }
  },
  watch: {
    realPageTitle: {
      immediate: true,
      handler() {
        if (this.realPageTitle !== null) {
          document.title = this.realPageTitle;
        }
      }
    },
    paneConfigKey: {
      immediate: true,
      handler() {
        this.restorePaneConfig();
      }
    }
  },
  mounted() {
    if (!this.disableSwipe) {
      this.swiping = useSwipe(this.$el, {
        onSwipeEnd: this.handleSwipe
      });
    }
    this.restorePaneConfig();
  },
  methods: {
    /**
     * handle the swipe event
     *
     * @param {TouchEvent} e The touch event
     * @param {import('@vueuse/core').SwipeDirection} direction The swipe direction of the event
     */
    handleSwipe(e, direction) {
      const minSwipeX = 70;
      const touchZone = 300;
      if (Math.abs(this.swiping.lengthX) > minSwipeX) {
        if (this.swiping.coordsStart.x < touchZone / 2 && direction === "right") {
          emit("toggle-navigation", {
            open: true
          });
        } else if (this.swiping.coordsStart.x < touchZone * 1.5 && direction === "left") {
          emit("toggle-navigation", {
            open: false
          });
        }
      }
    },
    handlePaneResize(event) {
      const listPaneSize = parseInt(event.panes[0].size, 10);
      browserStorage.setItem(this.paneConfigID, JSON.stringify(listPaneSize));
      this.listPaneSize = listPaneSize;
      this.$emit("resizeList", { size: listPaneSize });
      logger.debug("[NcAppContent] pane config", { listPaneSize });
    },
    // browserStorage is not reactive, we need to update this manually
    restorePaneConfig() {
      const listPaneSize = parseInt(browserStorage.getItem(this.paneConfigID), 10);
      if (!isNaN(listPaneSize) && listPaneSize !== this.listPaneSize) {
        logger.debug("[NcAppContent] pane config", { listPaneSize });
        this.listPaneSize = listPaneSize;
        return listPaneSize;
      }
    },
    /**
     * The user clicked the back arrow from the details view
     */
    hideDetails() {
      this.$emit("update:showDetails", false);
    }
  }
};
const _hoisted_1$e = {
  key: 0,
  class: "hidden-visually"
};
const _hoisted_2$c = {
  key: 1,
  class: "app-content-wrapper"
};
function _sfc_render$d(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcAppContentDetailsToggle = resolveComponent("NcAppContentDetailsToggle");
  const _component_Pane = resolveComponent("Pane");
  const _component_Splitpanes = resolveComponent("Splitpanes");
  return openBlock(), createElementBlock("main", {
    id: "app-content-vue",
    class: normalizeClass(["app-content no-snapper", { "app-content--has-list": !!_ctx.$slots.list }])
  }, [
    $props.pageHeading ? (openBlock(), createElementBlock("h1", _hoisted_1$e, toDisplayString($props.pageHeading), 1)) : createCommentVNode("", true),
    !!_ctx.$slots.list ? (openBlock(), createElementBlock(Fragment, { key: 1 }, [
      $setup.isMobile || $props.layout === "no-split" ? (openBlock(), createElementBlock("div", {
        key: 0,
        class: normalizeClass(["app-content-wrapper app-content-wrapper--no-split", {
          "app-content-wrapper--show-details": $props.showDetails,
          "app-content-wrapper--show-list": !$props.showDetails,
          "app-content-wrapper--mobile": $setup.isMobile
        }])
      }, [
        $props.showDetails ? (openBlock(), createBlock(_component_NcAppContentDetailsToggle, {
          key: 0,
          onClick: withModifiers($options.hideDetails, ["stop", "prevent"])
        }, null, 8, ["onClick"])) : createCommentVNode("", true),
        withDirectives(createBaseVNode("div", null, [
          renderSlot(_ctx.$slots, "list", {}, void 0, true)
        ], 512), [
          [vShow, !$props.showDetails]
        ]),
        $props.showDetails ? renderSlot(_ctx.$slots, "default", { key: 1 }, void 0, true) : createCommentVNode("", true)
      ], 2)) : $props.layout === "vertical-split" || $props.layout === "horizontal-split" ? (openBlock(), createElementBlock("div", _hoisted_2$c, [
        createVNode(_component_Splitpanes, {
          horizontal: $props.layout === "horizontal-split",
          class: normalizeClass(["default-theme", {
            "splitpanes--horizontal": $props.layout === "horizontal-split",
            "splitpanes--vertical": $props.layout === "vertical-split"
          }]),
          rtl: $setup.isRtl,
          onResized: $options.handlePaneResize
        }, {
          default: withCtx(() => [
            createVNode(_component_Pane, {
              class: "splitpanes__pane-list",
              size: $data.listPaneSize || $options.paneDefaults.list.size,
              minSize: $options.paneDefaults.list.min,
              maxSize: $options.paneDefaults.list.max
            }, {
              default: withCtx(() => [
                renderSlot(_ctx.$slots, "list", {}, void 0, true)
              ]),
              _: 3
            }, 8, ["size", "minSize", "maxSize"]),
            createVNode(_component_Pane, {
              class: "splitpanes__pane-details",
              size: $options.detailsPaneSize,
              minSize: $options.paneDefaults.details.min,
              maxSize: $options.paneDefaults.details.max
            }, {
              default: withCtx(() => [
                renderSlot(_ctx.$slots, "default", {}, void 0, true)
              ]),
              _: 3
            }, 8, ["size", "minSize", "maxSize"])
          ]),
          _: 3
        }, 8, ["horizontal", "class", "rtl", "onResized"])
      ])) : createCommentVNode("", true)
    ], 64)) : createCommentVNode("", true),
    !_ctx.$slots.list ? renderSlot(_ctx.$slots, "default", { key: 2 }, void 0, true) : createCommentVNode("", true)
  ], 2);
}
const NcAppContent = /* @__PURE__ */ _export_sfc(_sfc_main$g, [["render", _sfc_render$d], ["__scopeId", "data-v-563c4ac4"]]);
const _sfc_main$f = {
  name: "CloseIcon",
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
const _hoisted_1$d = ["aria-hidden", "aria-label"];
const _hoisted_2$b = ["fill", "width", "height"];
const _hoisted_3$8 = { d: "M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" };
const _hoisted_4$8 = { key: 0 };
function _sfc_render$c(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon close-icon",
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
      createBaseVNode("path", _hoisted_3$8, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$8, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$b))
  ], 16, _hoisted_1$d);
}
const IconClose = /* @__PURE__ */ _export_sfc(_sfc_main$f, [["render", _sfc_render$c]]);
const _sfc_main$e = {
  name: "ChevronDownIcon",
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
const _hoisted_1$c = ["aria-hidden", "aria-label"];
const _hoisted_2$a = ["fill", "width", "height"];
const _hoisted_3$7 = { d: "M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" };
const _hoisted_4$7 = { key: 0 };
function _sfc_render$b(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon chevron-down-icon",
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
      createBaseVNode("path", _hoisted_3$7, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$7, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$a))
  ], 16, _hoisted_1$c);
}
const ChevronDown = /* @__PURE__ */ _export_sfc(_sfc_main$e, [["render", _sfc_render$b]]);
const _hoisted_1$b = { class: "input-field__main-wrapper" };
const _hoisted_2$9 = ["id", "aria-describedby", "disabled", "placeholder", "type", "value"];
const _hoisted_3$6 = ["for"];
const _hoisted_4$6 = { class: "input-field__icon input-field__icon--leading" };
const _hoisted_5$1 = {
  key: 2,
  class: "input-field__icon input-field__icon--trailing"
};
const _hoisted_6$1 = ["id"];
const _sfc_main$d = /* @__PURE__ */ defineComponent({
  ...{
    inheritAttrs: false
  },
  __name: "NcInputField",
  props: /* @__PURE__ */ mergeModels({
    class: { default: "" },
    inputClass: { default: "" },
    id: { default: () => createElementId() },
    label: { default: void 0 },
    labelOutside: { type: Boolean },
    type: { default: "text" },
    placeholder: { default: void 0 },
    showTrailingButton: { type: Boolean },
    trailingButtonLabel: { default: void 0 },
    success: { type: Boolean },
    error: { type: Boolean },
    helperText: { default: "" },
    disabled: { type: Boolean },
    pill: { type: Boolean }
  }, {
    "modelValue": { required: true },
    "modelModifiers": {}
  }),
  emits: /* @__PURE__ */ mergeModels(["trailingButtonClick"], ["update:modelValue"]),
  setup(__props, { expose: __expose, emit: __emit }) {
    const modelValue = useModel(__props, "modelValue");
    const props = __props;
    const emit2 = __emit;
    __expose({
      focus,
      select
    });
    const attrs = useAttrs();
    const inputElement = useTemplateRef("input");
    const hasTrailingIcon = computed(() => props.showTrailingButton || props.success);
    const internalPlaceholder = computed(() => {
      if (props.placeholder) {
        return props.placeholder;
      }
      if (props.label) {
        return isLegacy ? props.label : "";
      }
      return void 0;
    });
    const isValidLabel = computed(() => {
      const isValidLabel2 = props.label || props.labelOutside;
      if (!isValidLabel2) {
        warn("You need to add a label to the NcInputField component. Either use the prop label or use an external one, as per the example in the documentation.");
      }
      return isValidLabel2;
    });
    const ariaDescribedby = computed(() => {
      const ariaDescribedby2 = [];
      if (props.helperText) {
        ariaDescribedby2.push(`${props.id}-helper-text`);
      }
      if (attrs["aria-describedby"]) {
        ariaDescribedby2.push(String(attrs["aria-describedby"]));
      }
      return ariaDescribedby2.join(" ") || void 0;
    });
    function focus(options) {
      inputElement.value.focus(options);
    }
    function select() {
      inputElement.value.select();
    }
    function handleInput(event) {
      const target = event.target;
      modelValue.value = props.type === "number" && typeof modelValue.value === "number" ? parseFloat(target.value) : target.value;
    }
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        class: normalizeClass(["input-field", [{
          "input-field--disabled": _ctx.disabled,
          "input-field--error": _ctx.error,
          "input-field--label-outside": _ctx.labelOutside || !isValidLabel.value,
          "input-field--leading-icon": !!_ctx.$slots.icon,
          "input-field--trailing-icon": hasTrailingIcon.value,
          "input-field--pill": _ctx.pill,
          "input-field--success": _ctx.success,
          "input-field--legacy": unref(isLegacy)
        }, _ctx.$props.class]])
      }, [
        createBaseVNode("div", _hoisted_1$b, [
          createBaseVNode("input", mergeProps(_ctx.$attrs, {
            id: _ctx.id,
            ref: "input",
            "aria-describedby": ariaDescribedby.value,
            "aria-live": "polite",
            class: ["input-field__input", _ctx.inputClass],
            disabled: _ctx.disabled,
            placeholder: internalPlaceholder.value,
            type: _ctx.type,
            value: modelValue.value.toString(),
            onInput: handleInput
          }), null, 16, _hoisted_2$9),
          !_ctx.labelOutside && isValidLabel.value ? (openBlock(), createElementBlock("label", {
            key: 0,
            class: "input-field__label",
            for: _ctx.id
          }, toDisplayString(_ctx.label), 9, _hoisted_3$6)) : createCommentVNode("", true),
          withDirectives(createBaseVNode("div", _hoisted_4$6, [
            renderSlot(_ctx.$slots, "icon", {}, void 0, true)
          ], 512), [
            [vShow, !!_ctx.$slots.icon]
          ]),
          _ctx.showTrailingButton ? (openBlock(), createBlock(NcButton, {
            key: 1,
            class: "input-field__trailing-button",
            "aria-label": _ctx.trailingButtonLabel,
            disabled: _ctx.disabled,
            variant: "tertiary-no-background",
            onClick: _cache[0] || (_cache[0] = ($event) => emit2("trailingButtonClick", $event))
          }, {
            icon: withCtx(() => [
              renderSlot(_ctx.$slots, "trailing-button-icon", {}, void 0, true)
            ]),
            _: 3
          }, 8, ["aria-label", "disabled"])) : _ctx.success || _ctx.error ? (openBlock(), createElementBlock("div", _hoisted_5$1, [
            _ctx.success ? (openBlock(), createBlock(NcIconSvgWrapper, {
              key: 0,
              path: unref(mdiCheck)
            }, null, 8, ["path"])) : (openBlock(), createBlock(NcIconSvgWrapper, {
              key: 1,
              path: unref(mdiAlertCircleOutline)
            }, null, 8, ["path"]))
          ])) : createCommentVNode("", true)
        ]),
        _ctx.helperText ? (openBlock(), createElementBlock("p", {
          key: 0,
          id: `${_ctx.id}-helper-text`,
          class: "input-field__helper-text-message"
        }, [
          _ctx.success ? (openBlock(), createBlock(NcIconSvgWrapper, {
            key: 0,
            class: "input-field__helper-text-message__icon",
            path: unref(mdiCheck),
            inline: ""
          }, null, 8, ["path"])) : _ctx.error ? (openBlock(), createBlock(NcIconSvgWrapper, {
            key: 1,
            class: "input-field__helper-text-message__icon",
            path: unref(mdiAlertCircleOutline),
            inline: ""
          }, null, 8, ["path"])) : createCommentVNode("", true),
          createTextVNode(" " + toDisplayString(_ctx.helperText), 1)
        ], 8, _hoisted_6$1)) : createCommentVNode("", true)
      ], 2);
    };
  }
});
const NcInputField = /* @__PURE__ */ _export_sfc(_sfc_main$d, [["__scopeId", "data-v-bfba6aa6"]]);
const _sfc_main$c = /* @__PURE__ */ defineComponent({
  __name: "ActivityGroup",
  props: {
    activities: { type: Array, required: true }
  },
  setup(__props, { expose: __expose }) {
    __expose();
    const props = __props;
    const dateText = computed(() => {
      const today = moment();
      const yesterday = moment().subtract(1, "day");
      const first = moment(props.activities[0].datetime);
      if (first.isSame(today, "day")) {
        return translate("activity", "Today");
      } else if (first.isSame(yesterday, "day")) {
        return translate("activity", "Yesterday");
      }
      return first.format("LL");
    });
    const fullDate = computed(() => {
      const formatted = moment(props.activities[0].datetime).format("LL");
      return formatted !== dateText.value ? formatted : void 0;
    });
    const __returned__ = { props, dateText, fullDate, ActivityComponent };
    Object.defineProperty(__returned__, "__isScriptSetup", { enumerable: false, value: true });
    return __returned__;
  }
});
const _hoisted_1$a = ["title"];
function _sfc_render$a(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock(
    Fragment,
    null,
    [
      createBaseVNode("h2", {
        class: "activity-group__heading",
        title: $setup.fullDate
      }, toDisplayString($setup.dateText), 9, _hoisted_1$a),
      createBaseVNode("ul", null, [
        (openBlock(true), createElementBlock(
          Fragment,
          null,
          renderList($props.activities, (activity) => {
            return openBlock(), createBlock($setup["ActivityComponent"], {
              key: activity.id,
              activity,
              "show-previews": true
            }, null, 8, ["activity"]);
          }),
          128
          /* KEYED_FRAGMENT */
        ))
      ])
    ],
    64
    /* STABLE_FRAGMENT */
  );
}
const ActivityGroup = /* @__PURE__ */ _export_sfc$1(_sfc_main$c, [["render", _sfc_render$a], ["__scopeId", "data-v-c27bd1ea"], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/components/ActivityGroup.vue"]]);
const appIconSVG = '<svg viewBox="0 0 32 32" height="32" width="32" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2"><path d="m8 22 1-7H4l9-13h2l-1 8h6L10 22H8Z" style="fill-rule:nonzero" transform="matrix(1.5 0 0 1.5 -2 -2)"/></svg>';
const POLL_INTERVAL = 3e4;
const _sfc_main$b = /* @__PURE__ */ defineComponent({
  __name: "ActivityAppFeed",
  props: {
    filter: { type: String, required: false, default: "all" }
  },
  setup(__props, { expose: __expose }) {
    __expose();
    const navigationList = loadState(appName, "navigationList");
    const route = useRoute();
    const props = __props;
    const loading = ref(false);
    const hasMoreActivites = ref(true);
    const allActivities = ref([]);
    const lastActivityLoaded = ref();
    const newestActivityId = ref();
    const newActivitiesAvailable = ref(false);
    let pollTimer;
    let requestController = new AbortController();
    const visibility = useDocumentVisibility();
    const container2 = ref();
    useInfiniteScroll(container2, async () => {
      if (hasMoreActivites.value) {
        await loadActivities();
      }
    }, {
      distance: 100
    });
    const groupedActivities = computed(() => {
      const groups = {};
      for (const activity of allActivities.value) {
        const date = moment(activity.datetime).format("LL");
        if (groups[date] === void 0) {
          groups[date] = [activity];
        } else {
          groups[date].push(activity);
        }
      }
      return groups;
    });
    const headingTitle = computed(() => {
      return navigationList.find((navigationEl) => navigationEl.id === route.params.filter).name;
    });
    async function loadActivities() {
      if (loading.value) {
        return;
      }
      const { signal } = requestController;
      try {
        const since = lastActivityLoaded.value ?? "0";
        loading.value = true;
        const response = await cancelableClient.get(generateOcsUrl("apps/activity/api/v2/activity/{filter}?format=json&previews=true&since={since}", { filter: props.filter, since }), { signal });
        if (signal.aborted) {
          return;
        }
        const newActivities = response.data.ocs.data.map((raw) => new ActivityModel(raw));
        allActivities.value.push(...newActivities);
        lastActivityLoaded.value = response.headers["x-activity-last-given"];
        hasMoreActivites.value = true;
        if (newestActivityId.value === void 0 && newActivities.length > 0) {
          newestActivityId.value = newActivities[0].id;
        }
        nextTick(async () => {
          if (container2.value && container2.value.clientHeight === container2.value.scrollHeight) {
            await loadActivities();
          }
        });
      } catch (error) {
        if (axios.isCancel(error)) {
          return;
        }
        if (axios.isAxiosError(error) && error.response?.status === 304) {
          hasMoreActivites.value = false;
          return;
        }
        logger$1.error(error);
        showError(translate("activity", "Could not load activities"));
      } finally {
        if (!signal.aborted) {
          loading.value = false;
        }
      }
    }
    async function pollNewActivities() {
      const { signal } = requestController;
      try {
        const since = String(newestActivityId.value ?? 0);
        const response = await cancelableClient.get(generateOcsUrl("apps/activity/api/v2/activity/{filter}?format=json&previews=true&since={since}&sort=asc", { filter: props.filter, since }), { signal });
        if (!signal.aborted && response.data.ocs.data.length > 0) {
          const newActivities = response.data.ocs.data.map((raw) => new ActivityModel(raw));
          newActivities.sort((a, b) => b.id - a.id);
          newestActivityId.value = newActivities[0].id;
          allActivities.value.unshift(...newActivities);
          const isNearTop = !container2.value || container2.value.scrollTop < 50;
          if (!isNearTop) {
            newActivitiesAvailable.value = true;
          }
        }
      } catch (error) {
        if (!axios.isCancel(error) && (!axios.isAxiosError(error) || error.response?.status !== 304)) {
          logger$1.error(error);
        }
      }
      if (pollTimer !== void 0) {
        pollTimer = setTimeout(pollNewActivities, POLL_INTERVAL);
      }
    }
    function scrollToTop() {
      newActivitiesAvailable.value = false;
      container2.value?.scrollTo({ top: 0, behavior: "smooth" });
    }
    const onScroll = useDebounceFn(() => {
      if (container2.value && container2.value.scrollTop < 50) {
        newActivitiesAvailable.value = false;
      }
    }, 100);
    function startPolling() {
      stopPolling();
      pollTimer = setTimeout(pollNewActivities, POLL_INTERVAL);
    }
    function stopPolling() {
      if (pollTimer !== void 0) {
        clearTimeout(pollTimer);
        pollTimer = void 0;
      }
    }
    onMounted(() => {
      loadActivities();
      startPolling();
    });
    onUnmounted(() => {
      stopPolling();
      requestController.abort();
    });
    watch(visibility, (value) => {
      if (value === "hidden") {
        stopPolling();
      } else {
        startPolling();
      }
    });
    watch(props, () => {
      requestController.abort();
      requestController = new AbortController();
      allActivities.value = [];
      newActivitiesAvailable.value = false;
      lastActivityLoaded.value = void 0;
      newestActivityId.value = void 0;
      hasMoreActivites.value = true;
      loadActivities();
    });
    const __returned__ = { navigationList, route, props, loading, hasMoreActivites, allActivities, lastActivityLoaded, newestActivityId, newActivitiesAvailable, POLL_INTERVAL, get pollTimer() {
      return pollTimer;
    }, set pollTimer(v) {
      pollTimer = v;
    }, get requestController() {
      return requestController;
    }, set requestController(v) {
      requestController = v;
    }, visibility, container: container2, groupedActivities, headingTitle, loadActivities, pollNewActivities, scrollToTop, onScroll, startPolling, stopPolling, get t() {
      return translate;
    }, get NcAppContent() {
      return NcAppContent;
    }, get NcEmptyContent() {
      return NcEmptyContent;
    }, get NcIconSvgWrapper() {
      return NcIconSvgWrapper;
    }, get NcButton() {
      return NcButton;
    }, get NcLoadingIcon() {
      return NcLoadingIcon;
    }, ActivityGroup, get appIconSVG() {
      return appIconSVG;
    } };
    Object.defineProperty(__returned__, "__isScriptSetup", { enumerable: false, value: true });
    return __returned__;
  }
});
const _hoisted_1$9 = { class: "activity-app__heading" };
const _hoisted_2$8 = {
  key: 2,
  class: "activity-app__loading-indicator"
};
function _sfc_render$9(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createBlock($setup["NcAppContent"], { class: "activity-app" }, {
    default: withCtx(() => [
      createBaseVNode(
        "h1",
        _hoisted_1$9,
        toDisplayString($setup.headingTitle),
        1
        /* TEXT */
      ),
      $setup.hasMoreActivites && $setup.allActivities.length === 0 ? (openBlock(), createBlock($setup["NcEmptyContent"], {
        key: 0,
        class: "activity-app__empty-content",
        name: $setup.t("activity", "Loading activities"),
        description: $setup.t("activity", "This stream will show events like additions, changes & shares")
      }, {
        icon: withCtx(() => [
          createVNode($setup["NcLoadingIcon"], { size: 36 })
        ]),
        _: 1
        /* STABLE */
      }, 8, ["name", "description"])) : $setup.allActivities.length === 0 ? (openBlock(), createBlock($setup["NcEmptyContent"], {
        key: 1,
        class: "activity-app__empty-content",
        name: $setup.t("activity", "No activity yet"),
        description: $setup.t("activity", "This stream will show events like additions, changes & shares")
      }, {
        icon: withCtx(() => [
          createVNode($setup["NcIconSvgWrapper"], {
            svg: $setup.appIconSVG,
            size: 36
          }, null, 8, ["svg"])
        ]),
        _: 1
        /* STABLE */
      }, 8, ["name", "description"])) : createCommentVNode("v-if", true),
      createBaseVNode(
        "div",
        {
          ref: "container",
          class: "activity-app__container",
          onScroll: _cache[0] || (_cache[0] = (...args) => $setup.onScroll && $setup.onScroll(...args))
        },
        [
          $setup.newActivitiesAvailable ? (openBlock(), createBlock($setup["NcButton"], {
            key: 0,
            class: "activity-app__new-activities-indicator",
            type: "button",
            onClick: $setup.scrollToTop
          }, {
            default: withCtx(() => [
              createTextVNode(
                toDisplayString($setup.t("activity", "New activities")),
                1
                /* TEXT */
              )
            ]),
            _: 1
            /* STABLE */
          })) : createCommentVNode("v-if", true),
          (openBlock(true), createElementBlock(
            Fragment,
            null,
            renderList($setup.groupedActivities, (activities, date) => {
              return openBlock(), createBlock($setup["ActivityGroup"], {
                key: date,
                activities
              }, null, 8, ["activities"]);
            }),
            128
            /* KEYED_FRAGMENT */
          )),
          createCommentVNode(" Only show if not showing the inital empty content for loading "),
          $setup.hasMoreActivites && $setup.allActivities.length > 0 ? (openBlock(), createBlock($setup["NcLoadingIcon"], {
            key: 1,
            name: $setup.t("activity", "Loading more activities"),
            size: 64,
            class: "activity-app__loading-indicator"
          }, null, 8, ["name"])) : !$setup.hasMoreActivites && $setup.allActivities.length > 0 ? (openBlock(), createElementBlock(
            "div",
            _hoisted_2$8,
            toDisplayString($setup.t("activity", "No more activities.")),
            1
            /* TEXT */
          )) : createCommentVNode("v-if", true)
        ],
        544
        /* NEED_HYDRATION, NEED_PATCH */
      )
    ]),
    _: 1
    /* STABLE */
  });
}
const ActivityAppFeed = /* @__PURE__ */ _export_sfc$1(_sfc_main$b, [["render", _sfc_render$9], ["__scopeId", "data-v-8f0835f4"], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/ActivityAppFeed.vue"]]);
const _sfc_main$a = {
  name: "NcAppNavigationList"
};
const _hoisted_1$8 = { class: "app-navigation-list" };
function _sfc_render$8(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("ul", _hoisted_1$8, [
    renderSlot(_ctx.$slots, "default", {}, void 0, true)
  ]);
}
const NcAppNavigationList = /* @__PURE__ */ _export_sfc(_sfc_main$a, [["render", _sfc_render$8], ["__scopeId", "data-v-d72957ed"]]);
register(t20);
const _hoisted_1$1$2 = { class: "app-navigation-toggle-wrapper" };
const _sfc_main$1$2 = /* @__PURE__ */ defineComponent({
  __name: "NcAppNavigationToggle",
  props: {
    "open": { type: Boolean, ...{ required: true } },
    "openModifiers": {}
  },
  emits: ["update:open"],
  setup(__props) {
    const open = useModel(__props, "open");
    const title = computed(() => open.value ? t("Close navigation") : t("Open navigation"));
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1$1$2, [
        createVNode(unref(NcButton), {
          class: "app-navigation-toggle",
          "aria-controls": "app-navigation-vue",
          "aria-expanded": open.value ? "true" : "false",
          "aria-label": title.value,
          title: title.value,
          variant: "tertiary",
          onClick: _cache[0] || (_cache[0] = ($event) => open.value = !open.value)
        }, {
          icon: withCtx(() => [
            createVNode(NcIconSvgWrapper, {
              path: open.value ? unref(mdiMenuOpen) : unref(mdiMenu)
            }, null, 8, ["path"])
          ]),
          _: 1
        }, 8, ["aria-expanded", "aria-label", "title"])
      ]);
    };
  }
});
const NcAppNavigationToggle = /* @__PURE__ */ _export_sfc(_sfc_main$1$2, [["__scopeId", "data-v-5a15295d"]]);
const _hoisted_1$7 = ["aria-hidden", "aria-label", "aria-labelledby", "inert"];
const _hoisted_2$7 = { class: "app-navigation__search" };
const _sfc_main$9 = /* @__PURE__ */ defineComponent({
  __name: "NcAppNavigation",
  props: {
    ariaLabel: {},
    ariaLabelledby: {}
  },
  setup(__props) {
    const props = __props;
    let focusTrap;
    const setHasAppNavigation = inject(
      HAS_APP_NAVIGATION_KEY,
      () => warn("NcAppNavigation is not mounted inside NcContent, this is probably an error."),
      false
    );
    const appNavigationContainerElement = useTemplateRef("appNavigationContainer");
    const isMobile = useIsMobile();
    const open = ref(!isMobile.value);
    watchEffect(() => {
      if (!props.ariaLabel && !props.ariaLabelledby) {
        warn("NcAppNavigation requires either `ariaLabel` or `ariaLabelledby` to be set for accessibility.");
      }
    });
    watch(isMobile, () => {
      open.value = !isMobile.value;
    });
    watch(open, () => {
      toggleFocusTrap();
    });
    onMounted(() => {
      setHasAppNavigation(true);
      subscribe("toggle-navigation", toggleNavigationByEventBus);
      emit("navigation-toggled", {
        open: open.value
      });
      focusTrap = createFocusTrap(appNavigationContainerElement.value, {
        allowOutsideClick: true,
        fallbackFocus: appNavigationContainerElement.value,
        trapStack: getTrapStack(),
        escapeDeactivates: false
      });
      toggleFocusTrap();
    });
    onUnmounted(() => {
      setHasAppNavigation(false);
      unsubscribe("toggle-navigation", toggleNavigationByEventBus);
      focusTrap.deactivate();
    });
    function toggleNavigation(state) {
      if (open.value === state) {
        emit("navigation-toggled", {
          open: open.value
        });
        return;
      }
      open.value = state === void 0 ? !open.value : state;
      const bodyStyles = getComputedStyle(document.body);
      const animationLength = parseInt(bodyStyles.getPropertyValue("--animation-quick")) || 100;
      setTimeout(() => {
        emit("navigation-toggled", {
          open: open.value
        });
      }, 1.5 * animationLength);
    }
    function toggleNavigationByEventBus({ open: open2 }) {
      return toggleNavigation(open2);
    }
    function toggleFocusTrap() {
      if (isMobile.value && open.value) {
        focusTrap.activate();
      } else {
        focusTrap.deactivate();
      }
    }
    function handleEsc() {
      if (isMobile.value) {
        toggleNavigation(false);
      }
    }
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        ref: "appNavigationContainer",
        class: normalizeClass(["app-navigation", { "app-navigation--closed": !open.value }])
      }, [
        createBaseVNode("nav", {
          id: "app-navigation-vue",
          "aria-hidden": open.value ? "false" : "true",
          "aria-label": _ctx.ariaLabel || void 0,
          "aria-labelledby": _ctx.ariaLabelledby || void 0,
          class: "app-navigation__content",
          inert: !open.value || void 0,
          onKeydown: withKeys(handleEsc, ["esc"])
        }, [
          createBaseVNode("div", _hoisted_2$7, [
            renderSlot(_ctx.$slots, "search", {}, void 0, true)
          ]),
          createBaseVNode("div", {
            class: normalizeClass(["app-navigation__body", { "app-navigation__body--no-list": !_ctx.$slots.list }])
          }, [
            renderSlot(_ctx.$slots, "default", {}, void 0, true)
          ], 2),
          _ctx.$slots.list ? (openBlock(), createBlock(NcAppNavigationList, {
            key: 0,
            class: "app-navigation__list"
          }, {
            default: withCtx(() => [
              renderSlot(_ctx.$slots, "list", {}, void 0, true)
            ]),
            _: 3
          })) : createCommentVNode("", true),
          renderSlot(_ctx.$slots, "footer", {}, void 0, true)
        ], 40, _hoisted_1$7),
        createVNode(NcAppNavigationToggle, {
          open: open.value,
          "onUpdate:open": toggleNavigation
        }, null, 8, ["open"])
      ], 2);
    };
  }
});
const NcAppNavigation = /* @__PURE__ */ _export_sfc(_sfc_main$9, [["__scopeId", "data-v-d5ce90cd"]]);
const _sfc_main$8 = {
  name: "ChevronUpIcon",
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
const _hoisted_1$6 = ["aria-hidden", "aria-label"];
const _hoisted_2$6 = ["fill", "width", "height"];
const _hoisted_3$5 = { d: "M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z" };
const _hoisted_4$5 = { key: 0 };
function _sfc_render$7(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon chevron-up-icon",
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
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$5, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$6))
  ], 16, _hoisted_1$6);
}
const ChevronUp = /* @__PURE__ */ _export_sfc(_sfc_main$8, [["render", _sfc_render$7]]);
const _sfc_main$7 = {
  name: "ArrowRightIcon",
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
const _hoisted_3$4 = { d: "M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" };
const _hoisted_4$4 = { key: 0 };
function _sfc_render$6(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon arrow-right-icon",
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
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$4, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$5))
  ], 16, _hoisted_1$5);
}
const IconArrowRight = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["render", _sfc_render$6]]);
register(t14);
const _sfc_main$6 = {
  name: "NcInputConfirmCancel",
  components: {
    IconArrowRight,
    IconClose,
    NcButton
  },
  props: {
    /**
     * If this element is used on a primary element set to true for primary styling.
     */
    primary: {
      default: false,
      type: Boolean
    },
    /**
     * Placeholder of the edit field
     */
    placeholder: {
      default: "",
      type: String
    },
    /**
     * The current name (model value)
     */
    modelValue: {
      default: "",
      type: String
    }
  },
  emits: [
    "cancel",
    "confirm",
    "update:modelValue"
  ],
  data() {
    return {
      labelConfirm: t("Confirm changes"),
      labelCancel: t("Cancel changes")
    };
  },
  computed: {
    valueModel: {
      get() {
        return this.modelValue;
      },
      set(newValue) {
        this.$emit("update:modelValue", newValue);
      }
    }
  },
  methods: {
    confirm() {
      this.$emit("confirm");
    },
    cancel() {
      this.$emit("cancel");
    },
    focusInput() {
      this.$refs.input.focus();
    }
  }
};
const _hoisted_1$4 = { class: "app-navigation-input-confirm" };
const _hoisted_2$4 = ["placeholder"];
function _sfc_render$5(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_IconArrowRight = resolveComponent("IconArrowRight");
  const _component_NcButton = resolveComponent("NcButton");
  const _component_IconClose = resolveComponent("IconClose");
  return openBlock(), createElementBlock("div", _hoisted_1$4, [
    createBaseVNode("form", {
      onSubmit: _cache[1] || (_cache[1] = withModifiers((...args) => $options.confirm && $options.confirm(...args), ["prevent"])),
      onKeydown: _cache[2] || (_cache[2] = withKeys(withModifiers((...args) => $options.cancel && $options.cancel(...args), ["exact", "stop", "prevent"]), ["esc"])),
      onClick: _cache[3] || (_cache[3] = withModifiers(() => {
      }, ["stop", "prevent"]))
    }, [
      withDirectives(createBaseVNode("input", {
        ref: "input",
        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => $options.valueModel = $event),
        type: "text",
        class: "app-navigation-input-confirm__input",
        placeholder: $props.placeholder
      }, null, 8, _hoisted_2$4), [
        [vModelText, $options.valueModel]
      ]),
      createVNode(_component_NcButton, {
        "aria-label": $data.labelConfirm,
        type: "submit",
        variant: "primary",
        onClick: withModifiers($options.confirm, ["stop", "prevent"])
      }, {
        icon: withCtx(() => [
          createVNode(_component_IconArrowRight, { size: 20 })
        ]),
        _: 1
      }, 8, ["aria-label", "onClick"]),
      createVNode(_component_NcButton, {
        "aria-label": $data.labelCancel,
        type: "reset",
        variant: $props.primary ? "primary" : "tertiary",
        onClick: withModifiers($options.cancel, ["stop", "prevent"])
      }, {
        icon: withCtx(() => [
          createVNode(_component_IconClose, { size: 20 })
        ]),
        _: 1
      }, 8, ["aria-label", "variant", "onClick"])
    ], 32)
  ]);
}
const NcInputConfirmCancel = /* @__PURE__ */ _export_sfc(_sfc_main$6, [["render", _sfc_render$5], ["__scopeId", "data-v-dd457d48"]]);
const _sfc_main$5 = defineComponent({
  name: "NcVNodes",
  props: {
    /**
     * The vnodes to render
     */
    vnodes: {
      type: [Array, Object],
      default: null
    }
  },
  /**
   * The render function to display the component
   */
  render() {
    return this.vnodes || this.$slots?.default?.({});
  }
});
const _sfc_main$3 = {
  name: "PencilIcon",
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
const _hoisted_1$2$1 = ["aria-hidden", "aria-label"];
const _hoisted_2$2 = ["fill", "width", "height"];
const _hoisted_3$2 = { d: "M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" };
const _hoisted_4$2 = { key: 0 };
function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon pencil-icon",
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
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$2, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$2))
  ], 16, _hoisted_1$2$1);
}
const Pencil = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3]]);
const _sfc_main$2$1 = {
  name: "UndoIcon",
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
const _hoisted_2$1 = ["fill", "width", "height"];
const _hoisted_3$1 = { d: "M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z" };
const _hoisted_4$1 = { key: 0 };
function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon undo-icon",
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
      createBaseVNode("path", _hoisted_3$1, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$1, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$1))
  ], 16, _hoisted_1$1$1);
}
const Undo = /* @__PURE__ */ _export_sfc(_sfc_main$2$1, [["render", _sfc_render$2]]);
register(t21);
const _sfc_main$1$1 = {
  name: "NcAppNavigationIconCollapsible",
  components: {
    NcButton,
    ChevronDown,
    ChevronUp
  },
  props: {
    /**
     * Is the list currently open (or collapsed)
     */
    open: {
      type: Boolean,
      required: true
    },
    /**
     * Is the navigation item currently active.
     */
    active: {
      type: Boolean,
      required: true
    }
  },
  emits: ["click"],
  computed: {
    labelButton() {
      return this.open ? t("Collapse menu") : t("Open menu");
    }
  },
  methods: {
    onClick(e) {
      this.$emit("click", e);
    }
  }
};
function _sfc_render$1$1(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_ChevronUp = resolveComponent("ChevronUp");
  const _component_ChevronDown = resolveComponent("ChevronDown");
  const _component_NcButton = resolveComponent("NcButton");
  return openBlock(), createBlock(_component_NcButton, {
    class: normalizeClass(["icon-collapse", {
      "icon-collapse--active": $props.active,
      "icon-collapse--open": $props.open
    }]),
    "aria-label": $options.labelButton,
    variant: $props.active ? "tertiary-on-primary" : "tertiary",
    onClick: $options.onClick
  }, {
    icon: withCtx(() => [
      $props.open ? (openBlock(), createBlock(_component_ChevronUp, {
        key: 0,
        size: 20
      })) : (openBlock(), createBlock(_component_ChevronDown, {
        key: 1,
        size: 20
      }))
    ]),
    _: 1
  }, 8, ["class", "aria-label", "variant", "onClick"]);
}
const NcAppNavigationIconCollapsible = /* @__PURE__ */ _export_sfc(_sfc_main$1$1, [["render", _sfc_render$1$1], ["__scopeId", "data-v-e6236e50"]]);
register(t23, t50);
const _sfc_main$4 = {
  name: "NcAppNavigationItem",
  components: {
    NcActions,
    NcActionButton,
    NcAppNavigationIconCollapsible,
    NcInputConfirmCancel,
    NcLoadingIcon,
    NcVNodes: _sfc_main$5,
    Pencil,
    Undo
  },
  props: {
    /**
     * If you are not using vue-router you can use the property to set this item as the active navigation entry.
     * When using vue-router and the `to` property this is set automatically.
     */
    active: {
      type: Boolean,
      default: false
    },
    /**
     * The main text content of the entry.
     */
    name: {
      type: String,
      required: true
    },
    /**
     * The title attribute of the element.
     */
    title: {
      type: String,
      default: null
    },
    /**
     * id attribute of the list item element
     */
    id: {
      type: String,
      default: () => createElementId(),
      validator: (id) => id.trim() !== ""
    },
    /**
     * Refers to the icon on the left, this prop accepts a class
     * like 'icon-category-enabled'.
     */
    icon: {
      type: String,
      default: ""
    },
    /**
     * Displays a loading animated icon on the left of the element
     * instead of the icon.
     */
    loading: {
      type: Boolean,
      default: false
    },
    /**
     * Passing in a route will make the root element of this
     * component a `<router-link />` that points to that route.
     * By leaving this blank, the root element will be a `<li>`.
     */
    to: {
      type: [String, Object],
      default: null
    },
    /**
     * A direct link. This will be used as the `href` attribute.
     * This will ignore any `to` prop being defined.
     */
    href: {
      type: String,
      default: null
    },
    /**
     * Gives the possibility to collapse the children elements into the
     * parent element (true) or expands the children elements (false).
     */
    allowCollapse: {
      type: Boolean,
      default: false
    },
    /**
     * Makes the name of the item editable by providing an `ActionButton`
     * component that toggles a form
     */
    editable: {
      type: Boolean,
      default: false
    },
    /**
     * Only for 'editable' items, sets label for the edit action button.
     */
    editLabel: {
      type: String,
      default: ""
    },
    /**
     * Only for items in 'editable' mode, sets the placeholder text for the editing form.
     */
    editPlaceholder: {
      type: String,
      default: ""
    },
    /**
     * Pins the item to the bottom left area, above the settings. Do not
     * place 'non-pinned' `AppnavigationItem` components below `pinned`
     * ones.
     */
    pinned: {
      type: Boolean,
      default: false
    },
    /**
     * Puts the item in the 'undo' state.
     */
    undo: {
      type: Boolean,
      default: false
    },
    /**
     * The navigation collapsible state (synced)
     */
    open: {
      type: Boolean,
      default: false
    },
    /**
     * The actions menu open state (synced)
     */
    menuOpen: {
      type: Boolean,
      default: false
    },
    /**
     * Force the actions to display in a three dot menu
     */
    forceMenu: {
      type: Boolean,
      default: false
    },
    /**
     * The action's menu default icon
     */
    menuIcon: {
      type: String,
      default: void 0
    },
    /**
     * The action's menu direction
     */
    menuPlacement: {
      type: String,
      default: "bottom"
    },
    /**
     * Entry aria details
     */
    ariaDescription: {
      type: String,
      default: null
    },
    /**
     * To be used only when the elements in the actions menu are very important
     */
    forceDisplayActions: {
      type: Boolean,
      default: false
    },
    /**
     * Number of action items outside the menu
     */
    inlineActions: {
      type: Number,
      default: 0
    }
  },
  emits: [
    "update:menuOpen",
    "update:open",
    "update:name",
    "click",
    "undo"
  ],
  setup() {
    return {
      isMobile: useIsMobile()
    };
  },
  data() {
    return {
      actionsBoundariesElement: void 0,
      editingValue: "",
      opened: this.open,
      // Collapsible state
      editingActive: false,
      /**
       * Tracks the open state of the actions menu
       */
      menuOpenLocalValue: false,
      focused: false
    };
  },
  computed: {
    isRouterLink() {
      return this.to && !this.href;
    },
    // Checks if the component is already a children of another
    // instance of AppNavigationItem
    canHaveChildren() {
      if (this.$parent.$options._componentTag === "AppNavigationItem") {
        return false;
      } else {
        return true;
      }
    },
    editButtonAriaLabel() {
      return this.editLabel ? this.editLabel : t("Edit item");
    },
    undoButtonAriaLabel() {
      return t("Undo changes");
    }
  },
  watch: {
    open(newVal) {
      this.opened = newVal;
    }
  },
  mounted() {
    this.actionsBoundariesElement = document.querySelector("#content-vue") || void 0;
  },
  methods: {
    // sync opened menu state with prop
    onMenuToggle(state) {
      this.$emit("update:menuOpen", state);
      this.menuOpenLocalValue = state;
    },
    // toggle the collapsible state
    toggleCollapse() {
      this.opened = !this.opened;
      this.$emit("update:open", this.opened);
    },
    /**
     * Handle link click
     *
     * @param {PointerEvent} event - Native click event
     * @param {Function} [navigate] - VueRouter link's navigate if any
     * @param {string} [routerLinkHref] - VueRouter link's href
     */
    onClick(event, navigate, routerLinkHref) {
      this.$emit("click", event);
      if (event.metaKey || event.altKey || event.ctrlKey || event.shiftKey) {
        return;
      }
      if (routerLinkHref) {
        navigate?.(event);
        event.preventDefault();
      }
    },
    // Edition methods
    handleEdit() {
      this.editingValue = this.name;
      this.editingActive = true;
      this.onMenuToggle(false);
      this.$nextTick(() => {
        this.$refs.editingInput.focusInput();
      });
    },
    cancelEditing() {
      this.editingActive = false;
    },
    handleEditingDone() {
      this.$emit("update:name", this.editingValue);
      this.editingValue = "";
      this.editingActive = false;
    },
    // Undo methods
    handleUndo() {
      this.$emit("undo");
    },
    /**
     * Show actions upon focus
     */
    handleFocus() {
      this.focused = true;
    },
    handleBlur() {
      this.focused = false;
    },
    /**
     * This method checks if the root element of the component is focused and
     * if that's the case it focuses the actions button if available
     *
     * @param {Event} e the keydown event
     */
    handleTab(e) {
      if (!this.$refs.actions) {
        return;
      }
      if (this.focused) {
        e.preventDefault();
        this.$refs.actions.$refs.triggerButton.$el.focus();
        this.focused = false;
      } else {
        this.$refs.actions.$refs.triggerButton.$el.blur();
      }
    },
    /**
     * Is this an external link
     *
     * @param {string} href The link to check
     * @return {boolean} Whether it is external or not
     */
    isExternal(href) {
      return href && href.match(/[a-z]+:\/\//i);
    }
  }
};
const _hoisted_1$3 = ["id"];
const _hoisted_2$3 = ["aria-current", "aria-description", "aria-expanded", "href", "target", "title", "onClick"];
const _hoisted_3$3 = {
  key: 0,
  class: "editingContainer"
};
const _hoisted_4$3 = {
  key: 1,
  class: "app-navigation-entry__deleted"
};
const _hoisted_5 = { class: "app-navigation-entry__deleted-description" };
const _hoisted_6 = {
  key: 0,
  class: "app-navigation-entry__counter-wrapper"
};
const _hoisted_7 = {
  key: 0,
  class: "app-navigation-entry__children"
};
function _sfc_render$4(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcLoadingIcon = resolveComponent("NcLoadingIcon");
  const _component_NcInputConfirmCancel = resolveComponent("NcInputConfirmCancel");
  const _component_Pencil = resolveComponent("Pencil");
  const _component_NcActionButton = resolveComponent("NcActionButton");
  const _component_Undo = resolveComponent("Undo");
  const _component_NcActions = resolveComponent("NcActions");
  const _component_NcAppNavigationIconCollapsible = resolveComponent("NcAppNavigationIconCollapsible");
  return openBlock(), createElementBlock("li", {
    id: $props.id,
    class: normalizeClass([{
      "app-navigation-entry--opened": $data.opened,
      "app-navigation-entry--pinned": $props.pinned,
      "app-navigation-entry--collapsible": $props.allowCollapse && !!_ctx.$slots.default
    }, "app-navigation-entry-wrapper"])
  }, [
    (openBlock(), createBlock(resolveDynamicComponent($options.isRouterLink ? "router-link" : "NcVNodes"), normalizeProps(guardReactiveProps({ ...$options.isRouterLink && { custom: true, to: $props.to } })), {
      default: withCtx(({ href: routerLinkHref, navigate, isActive }) => [
        createBaseVNode("div", {
          class: normalizeClass(["app-navigation-entry", {
            "app-navigation-entry--editing": $data.editingActive,
            "app-navigation-entry--deleted": $props.undo,
            active: $props.to && isActive || $props.active
          }])
        }, [
          !$props.undo ? (openBlock(), createElementBlock("a", {
            key: 0,
            class: "app-navigation-entry-link",
            "aria-current": $props.active || $props.to && isActive ? "page" : void 0,
            "aria-description": $props.ariaDescription,
            "aria-expanded": !!_ctx.$slots.default ? $data.opened.toString() : void 0,
            href: $props.href || routerLinkHref || "#",
            target: $options.isExternal($props.href) ? "_blank" : void 0,
            title: $props.title || $props.name,
            onBlur: _cache[1] || (_cache[1] = (...args) => $options.handleBlur && $options.handleBlur(...args)),
            onClick: ($event) => $options.onClick($event, navigate, routerLinkHref),
            onFocus: _cache[2] || (_cache[2] = (...args) => $options.handleFocus && $options.handleFocus(...args)),
            onKeydown: _cache[3] || (_cache[3] = withKeys(withModifiers((...args) => $options.handleTab && $options.handleTab(...args), ["exact"]), ["tab"]))
          }, [
            createBaseVNode("div", {
              class: normalizeClass(["app-navigation-entry-icon", { [$props.icon]: $props.icon }])
            }, [
              $props.loading ? (openBlock(), createBlock(_component_NcLoadingIcon, { key: 0 })) : renderSlot(_ctx.$slots, "icon", {
                key: 1,
                active: $props.active || $props.to && isActive
              }, void 0, true)
            ], 2),
            createBaseVNode("span", {
              class: normalizeClass(["app-navigation-entry__name", { "hidden-visually": $data.editingActive }])
            }, toDisplayString($props.name), 3),
            $data.editingActive ? (openBlock(), createElementBlock("div", _hoisted_3$3, [
              createVNode(_component_NcInputConfirmCancel, {
                ref: "editingInput",
                modelValue: $data.editingValue,
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => $data.editingValue = $event),
                placeholder: $props.editPlaceholder !== "" ? $props.editPlaceholder : $props.name,
                primary: $props.to && isActive || $props.active,
                onCancel: $options.cancelEditing,
                onConfirm: $options.handleEditingDone
              }, null, 8, ["modelValue", "placeholder", "primary", "onCancel", "onConfirm"])
            ])) : createCommentVNode("", true)
          ], 40, _hoisted_2$3)) : createCommentVNode("", true),
          $props.undo ? (openBlock(), createElementBlock("div", _hoisted_4$3, [
            createBaseVNode("div", _hoisted_5, toDisplayString($props.name), 1)
          ])) : createCommentVNode("", true),
          (!!_ctx.$slots.actions || !!_ctx.$slots.counter || $props.editable || $props.undo) && !$data.editingActive ? (openBlock(), createElementBlock("div", {
            key: 2,
            class: normalizeClass(["app-navigation-entry__utils", { "app-navigation-entry__utils--display-actions": $props.forceDisplayActions || $data.menuOpenLocalValue || $props.menuOpen }])
          }, [
            !!_ctx.$slots.counter ? (openBlock(), createElementBlock("div", _hoisted_6, [
              renderSlot(_ctx.$slots, "counter", {}, void 0, true)
            ])) : createCommentVNode("", true),
            !!_ctx.$slots.actions || $props.editable && !$data.editingActive || $props.undo ? (openBlock(), createBlock(_component_NcActions, {
              key: 1,
              ref: "actions",
              class: "app-navigation-entry__actions",
              container: "#app-navigation-vue",
              boundariesElement: $data.actionsBoundariesElement,
              inline: $props.inlineActions,
              placement: $props.menuPlacement,
              open: $props.menuOpen,
              forceMenu: $props.forceMenu,
              defaultIcon: $props.menuIcon,
              variant: $props.to && isActive || $props.active ? "tertiary-on-primary" : "tertiary",
              "onUpdate:open": $options.onMenuToggle
            }, {
              icon: withCtx(() => [
                renderSlot(_ctx.$slots, "menu-icon", {}, void 0, true)
              ]),
              default: withCtx(() => [
                $props.editable && !$data.editingActive ? (openBlock(), createBlock(_component_NcActionButton, {
                  key: 0,
                  "aria-label": $options.editButtonAriaLabel,
                  onClick: $options.handleEdit
                }, {
                  icon: withCtx(() => [
                    createVNode(_component_Pencil, { size: 20 })
                  ]),
                  default: withCtx(() => [
                    createTextVNode(" " + toDisplayString($props.editLabel), 1)
                  ]),
                  _: 1
                }, 8, ["aria-label", "onClick"])) : createCommentVNode("", true),
                $props.undo ? (openBlock(), createBlock(_component_NcActionButton, {
                  key: 1,
                  "aria-label": $options.undoButtonAriaLabel,
                  onClick: $options.handleUndo
                }, {
                  icon: withCtx(() => [
                    createVNode(_component_Undo, { size: 20 })
                  ]),
                  _: 1
                }, 8, ["aria-label", "onClick"])) : createCommentVNode("", true),
                renderSlot(_ctx.$slots, "actions", {}, void 0, true)
              ]),
              _: 2
            }, 1032, ["boundariesElement", "inline", "placement", "open", "forceMenu", "defaultIcon", "variant", "onUpdate:open"])) : createCommentVNode("", true)
          ], 2)) : createCommentVNode("", true),
          $props.allowCollapse && !!_ctx.$slots.default ? (openBlock(), createBlock(_component_NcAppNavigationIconCollapsible, {
            key: 3,
            active: $props.to && isActive || $props.active,
            open: $data.opened,
            onClick: withModifiers($options.toggleCollapse, ["prevent", "stop"])
          }, null, 8, ["active", "open", "onClick"])) : createCommentVNode("", true),
          renderSlot(_ctx.$slots, "extra", {}, void 0, true)
        ], 2)
      ]),
      _: 3
    }, 16)),
    $options.canHaveChildren && !!_ctx.$slots.default ? (openBlock(), createElementBlock("ul", _hoisted_7, [
      renderSlot(_ctx.$slots, "default", {}, void 0, true)
    ])) : createCommentVNode("", true)
  ], 10, _hoisted_1$3);
}
const NcAppNavigationItem = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["render", _sfc_render$4], ["__scopeId", "data-v-f925f8d0"]]);
register(t46);
const _hoisted_1$2 = ["id"];
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "NcAppNavigationSettings",
  props: {
    excludeClickOutsideSelectors: { default: () => [] },
    name: { default: () => t("Settings") }
  },
  setup(__props) {
    const contentId = createElementId();
    const open = ref(false);
    const container2 = useTemplateRef("wrapperElement");
    const ignore = computed(() => Array.isArray(__props.excludeClickOutsideSelectors) ? __props.excludeClickOutsideSelectors : __props.excludeClickOutsideSelectors.split(" "));
    onClickOutside(container2, () => {
      open.value = false;
    }, { ignore });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", {
        ref: "wrapperElement",
        class: normalizeClass(_ctx.$style.container)
      }, [
        createBaseVNode("div", {
          class: normalizeClass(_ctx.$style.header)
        }, [
          createVNode(NcButton, {
            "aria-controls": unref(contentId),
            "aria-expanded": open.value ? "true" : "false",
            class: normalizeClass(_ctx.$style.button),
            alignment: "start",
            variant: "tertiary",
            wide: "",
            onClick: _cache[0] || (_cache[0] = ($event) => open.value = !open.value)
          }, {
            icon: withCtx(() => [
              createVNode(NcIconSvgWrapper, {
                path: unref(isLegacy) ? unref(mdiCog) : unref(mdiCogOutline)
              }, null, 8, ["path"])
            ]),
            default: withCtx(() => [
              createTextVNode(" " + toDisplayString(_ctx.name), 1)
            ]),
            _: 1
          }, 8, ["aria-controls", "aria-expanded", "class"])
        ], 2),
        createVNode(Transition, {
          enterActiveClass: _ctx.$style.animationActive,
          leaveActiveClass: _ctx.$style.animationActive,
          enterFromClass: _ctx.$style.animationStop,
          leaveToClass: _ctx.$style.animationStop
        }, {
          default: withCtx(() => [
            withDirectives(createBaseVNode("div", {
              id: unref(contentId),
              class: normalizeClass(_ctx.$style.content)
            }, [
              renderSlot(_ctx.$slots, "default")
            ], 10, _hoisted_1$2), [
              [vShow, open.value]
            ])
          ]),
          _: 3
        }, 8, ["enterActiveClass", "leaveActiveClass", "enterFromClass", "leaveToClass"])
      ], 2);
    };
  }
});
const container = "_container_RFk6U";
const header = "_header_2CtDS";
const button = "_button_tAyis";
const content = "_content_--KYD";
const animationActive = "_animationActive_DAG1p";
const animationStop = "_animationStop_fWYQj";
const style0 = {
  container,
  header,
  button,
  content,
  animationActive,
  animationStop
};
const cssModules = {
  "$style": style0
};
const NcAppNavigationSettings = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["__cssModules", cssModules]]);
const _sfc_main$1 = {
  name: "ContentCopyIcon",
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
const _hoisted_1$1 = ["aria-hidden", "aria-label"];
const _hoisted_2 = ["fill", "width", "height"];
const _hoisted_3 = { d: "M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z" };
const _hoisted_4 = { key: 0 };
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon content-copy-icon",
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
      createBaseVNode("path", _hoisted_3, [
        $props.title ? (openBlock(), createElementBlock(
          "title",
          _hoisted_4,
          toDisplayString($props.title),
          1
          /* TEXT */
        )) : createCommentVNode("v-if", true)
      ])
    ], 8, _hoisted_2))
  ], 16, _hoisted_1$1);
}
const IconContentCopy = /* @__PURE__ */ _export_sfc$1(_sfc_main$1, [["render", _sfc_render$1], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/node_modules/vue-material-design-icons/ContentCopy.vue"]]);
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ActivityAppNavigation",
  setup(__props, { expose: __expose }) {
    __expose();
    const {
      rssLink: initialRSSLink,
      personalSettingsLink
    } = loadState(appName, "settings");
    const navigationList = loadState(appName, "navigationList");
    const rssLink = ref(initialRSSLink);
    const hasRSSLink = computed(() => !!rssLink.value);
    async function toggleRSSLink() {
      try {
        const { data } = await cancelableClient.post(generateUrl("/apps/activity/settings/feed"), {
          enable: !hasRSSLink.value
        });
        rssLink.value = data.data.rsslink;
      } catch (e) {
        showError(translate("activity", "Could not enable RSS link"));
        logger$1.error(e);
      }
    }
    async function copyRSSLink() {
      try {
        window.navigator.clipboard.writeText(rssLink.value);
        showSuccess(translate("activity", "RSS link copied to clipboard"));
      } catch (e) {
        logger$1.debug(e);
        window.prompt(translate("activity", "Could not copy the RSS link, please copy manually:"), rssLink.value);
      }
    }
    const __returned__ = { initialRSSLink, personalSettingsLink, navigationList, rssLink, hasRSSLink, toggleRSSLink, copyRSSLink, get t() {
      return translate;
    }, get NcAppNavigation() {
      return NcAppNavigation;
    }, get NcAppNavigationItem() {
      return NcAppNavigationItem;
    }, get NcAppNavigationSettings() {
      return NcAppNavigationSettings;
    }, get NcButton() {
      return NcButton;
    }, get NcCheckboxRadioSwitch() {
      return NcCheckboxRadioSwitch;
    }, get NcInputField() {
      return NcInputField;
    }, IconContentCopy };
    Object.defineProperty(__returned__, "__isScriptSetup", { enumerable: false, value: true });
    return __returned__;
  }
});
const _hoisted_1 = ["src"];
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createBlock($setup["NcAppNavigation"], {
    "aria-label": $setup.t("activity", "Activity")
  }, {
    list: withCtx(() => [
      (openBlock(true), createElementBlock(
        Fragment,
        null,
        renderList($setup.navigationList, (navigationItem) => {
          return openBlock(), createBlock($setup["NcAppNavigationItem"], {
            key: navigationItem.id,
            "data-navigation": navigationItem.id,
            to: navigationItem.id,
            name: navigationItem.name
          }, createSlots({
            _: 2
            /* DYNAMIC */
          }, [
            navigationItem.icon ? {
              name: "icon",
              fn: withCtx(() => [
                createBaseVNode("img", {
                  alt: "",
                  src: navigationItem.icon,
                  class: "navigation-icon",
                  role: "presentation"
                }, null, 8, _hoisted_1)
              ]),
              key: "0"
            } : void 0
          ]), 1032, ["data-navigation", "to", "name"]);
        }),
        128
        /* KEYED_FRAGMENT */
      ))
    ]),
    footer: withCtx(() => [
      createVNode($setup["NcAppNavigationSettings"], {
        name: $setup.t("activity", "Activity settings")
      }, {
        default: withCtx(() => [
          createVNode($setup["NcCheckboxRadioSwitch"], {
            type: "switch",
            modelValue: $setup.hasRSSLink,
            "onUpdate:modelValue": [
              _cache[0] || (_cache[0] = ($event) => $setup.hasRSSLink = $event),
              $setup.toggleRSSLink
            ]
          }, {
            default: withCtx(() => [
              createTextVNode(
                toDisplayString($setup.t("activity", "Enable RSS feed")),
                1
                /* TEXT */
              )
            ]),
            _: 1
            /* STABLE */
          }, 8, ["modelValue"]),
          $setup.hasRSSLink ? (openBlock(), createBlock($setup["NcInputField"], {
            key: 0,
            modelValue: $setup.rssLink,
            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => $setup.rssLink = $event),
            label: $setup.t("activity", "RSS feed"),
            "show-trailing-button": true,
            "trailing-button-label": $setup.t("activity", "Copy RSS feed link"),
            readonly: "readonly",
            onTrailingButtonClick: $setup.copyRSSLink
          }, {
            "trailing-button-icon": withCtx(() => [
              createVNode($setup["IconContentCopy"], { size: 20 })
            ]),
            _: 1
            /* STABLE */
          }, 8, ["modelValue", "label", "trailing-button-label"])) : createCommentVNode("v-if", true),
          createVNode($setup["NcButton"], {
            class: "settings-link",
            href: $setup.personalSettingsLink,
            title: $setup.t("activity", "Personal notification settings"),
            variant: "tertiary"
          }, {
            default: withCtx(() => [
              createTextVNode(
                toDisplayString($setup.t("activity", "Personal notification settings")),
                1
                /* TEXT */
              )
            ]),
            _: 1
            /* STABLE */
          }, 8, ["href", "title"])
        ]),
        _: 1
        /* STABLE */
      }, 8, ["name"])
    ]),
    _: 1
    /* STABLE */
  }, 8, ["aria-label"]);
}
const ActivityAppNavigation = /* @__PURE__ */ _export_sfc$1(_sfc_main, [["render", _sfc_render], ["__file", "/home/anna/Desktop/Nextcloud/nextcloud-docker-dev/workspace/server/apps-extra/activity/src/views/ActivityAppNavigation.vue"]]);
const routes = [
  {
    path: "/",
    name: "root",
    redirect: { path: "/all" }
  },
  {
    path: "/:filter?",
    components: {
      default: ActivityAppFeed,
      navigation: ActivityAppNavigation
    },
    props: {
      default: true
    }
  }
];
const router = createRouter({
  history: createWebHistory(generateUrl("/apps/activity")),
  linkActiveClass: "active",
  routes
});
const app = createApp(ActivityApp);
app.use(router);
app.mount("#content");
//# sourceMappingURL=activity-app.mjs.map
