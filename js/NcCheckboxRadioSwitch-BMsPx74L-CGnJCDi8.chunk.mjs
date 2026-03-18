(function() {
  "use strict";
  try {
    if (typeof document != "undefined") {
      var elementStyle = document.createElement("style");
      elementStyle.appendChild(document.createTextNode('/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-06ad9b25] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.button-vue[data-v-06ad9b25] {\n  --button-size: var(--default-clickable-area);\n  --button-inner-size: calc(var(--button-size) - 4px);\n  --button-radius: var(--border-radius-element);\n  --button-padding-default: calc(var(--default-grid-baseline) + var(--button-radius));\n  --button-padding: var(--default-grid-baseline) var(--button-padding-default);\n  color: var(--color-primary-element-light-text);\n  background-color: var(--color-primary-element-light);\n  border: 1px solid var(--color-primary-element-light-hover);\n  border-bottom-width: 2px;\n  border-radius: var(--button-radius);\n  box-sizing: border-box;\n  position: relative;\n  width: fit-content;\n  overflow: hidden;\n  padding-block: 1px 0;\n  padding-inline: var(--button-padding);\n  min-height: var(--button-size);\n  min-width: var(--button-size);\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  transition-property: color, border-color, background-color;\n  transition-duration: 0.1s;\n  transition-timing-function: linear;\n  cursor: pointer;\n  font-size: var(--default-font-size);\n  font-weight: bold;\n}\n.button-vue--size-small[data-v-06ad9b25] {\n  --button-size: var(--clickable-area-small);\n}\n.button-vue--size-large[data-v-06ad9b25] {\n  --button-size: var(--clickable-area-large);\n}\n.button-vue[data-v-06ad9b25] * {\n  cursor: pointer;\n}\n.button-vue[data-v-06ad9b25]:focus {\n  outline: none;\n}\n.button-vue[data-v-06ad9b25]:disabled {\n  filter: saturate(0.7);\n  opacity: 0.5;\n  cursor: default;\n}\n.button-vue[data-v-06ad9b25]:disabled * {\n  cursor: default;\n}\n.button-vue[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: var(--color-primary-element-light-hover);\n}\n.button-vue[data-v-06ad9b25]:active {\n  background-color: var(--color-primary-element-light);\n}\n.button-vue__wrapper[data-v-06ad9b25] {\n  display: inline-flex;\n  align-items: center;\n  justify-content: center;\n  width: 100%;\n}\n.button-vue--end .button-vue__wrapper[data-v-06ad9b25] {\n  justify-content: end;\n}\n.button-vue--start .button-vue__wrapper[data-v-06ad9b25] {\n  justify-content: start;\n}\n.button-vue--reverse .button-vue__wrapper[data-v-06ad9b25] {\n  flex-direction: row-reverse;\n}\n.button-vue--reverse[data-v-06ad9b25] {\n  --button-padding: var(--button-padding-default) var(--default-grid-baseline);\n}\n.button-vue__icon[data-v-06ad9b25] {\n  --default-clickable-area: var(--button-inner-size);\n  height: var(--button-inner-size);\n  width: var(--button-inner-size);\n  min-height: var(--button-inner-size);\n  min-width: var(--button-inner-size);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n.button-vue__icon[data-v-06ad9b25]:empty {\n  display: none;\n}\n.button-vue--size-small .button-vue__icon[data-v-06ad9b25] > * {\n  max-height: 16px;\n  max-width: 16px;\n}\n.button-vue--size-small .button-vue__icon[data-v-06ad9b25] svg {\n  height: 16px;\n  width: 16px;\n}\n.button-vue__text[data-v-06ad9b25] {\n  font-weight: bold;\n  margin-bottom: 1px;\n  padding: 2px 0;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n  overflow: hidden;\n}\n.button-vue__text[data-v-06ad9b25]:empty {\n  display: none;\n}\n.button-vue[data-v-06ad9b25]:has(.button-vue__text:empty) {\n  --button-padding: var(--button-radius);\n  line-height: 1;\n  width: var(--button-size) !important;\n}\n.button-vue[data-v-06ad9b25]:has(.button-vue__icon:empty) {\n  --button-padding: var(--button-padding-default);\n}\n.button-vue:has(.button-vue__icon:empty) .button-vue__text[data-v-06ad9b25] {\n  padding-inline: var(--default-grid-baseline);\n}\n.button-vue--wide[data-v-06ad9b25] {\n  width: 100%;\n}\n.button-vue[data-v-06ad9b25]:focus-visible {\n  outline: 2px solid var(--color-main-text) !important;\n  box-shadow: 0 0 0 4px var(--color-main-background) !important;\n}\n.button-vue:focus-visible.button-vue--vue-tertiary-on-primary[data-v-06ad9b25] {\n  outline: 2px solid var(--color-primary-element-text);\n  border-radius: var(--border-radius-element);\n  background-color: transparent;\n}\n.button-vue--primary[data-v-06ad9b25] {\n  background-color: var(--color-primary-element);\n  border-color: var(--color-primary-element-hover);\n  color: var(--color-primary-element-text);\n}\n.button-vue--primary[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: var(--color-primary-element-hover);\n}\n.button-vue--primary[data-v-06ad9b25]:active {\n  background-color: var(--color-primary-element);\n}\n.button-vue--secondary[data-v-06ad9b25] {\n  background-color: var(--color-primary-element-light);\n  border-color: var(--color-primary-element-light-hover);\n  color: var(--color-primary-element-light-text);\n}\n.button-vue--secondary[data-v-06ad9b25]:hover:not(:disabled) {\n  color: var(--color-primary-element-light-text);\n  background-color: var(--color-primary-element-light-hover);\n}\n.button-vue--tertiary[data-v-06ad9b25] {\n  background-color: transparent;\n  border-color: transparent;\n  color: var(--color-main-text);\n}\n.button-vue--tertiary[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: var(--color-background-hover);\n}\n.button-vue--tertiary-no-background[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: transparent;\n}\n.button-vue--tertiary-on-primary[data-v-06ad9b25] {\n  color: var(--color-primary-element-text);\n}\n.button-vue--tertiary-on-primary[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: transparent;\n}\n.button-vue--success[data-v-06ad9b25] {\n  border-color: var(--color-success-hover);\n  background-color: var(--color-success);\n  color: var(--color-success-text);\n}\n.button-vue--success[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: var(--color-success-hover);\n}\n.button-vue--success[data-v-06ad9b25]:active {\n  background-color: var(--color-success);\n}\n.button-vue--warning[data-v-06ad9b25] {\n  border-color: var(--color-warning-hover);\n  background-color: var(--color-warning);\n  color: var(--color-warning-text);\n}\n.button-vue--warning[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: var(--color-warning-hover);\n}\n.button-vue--warning[data-v-06ad9b25]:active {\n  background-color: var(--color-warning);\n}\n.button-vue--error[data-v-06ad9b25] {\n  border-color: var(--color-error-hover);\n  background-color: var(--color-error);\n  color: var(--color-error-text);\n}\n.button-vue--error[data-v-06ad9b25]:hover:not(:disabled) {\n  background-color: var(--color-error-hover);\n}\n.button-vue--error[data-v-06ad9b25]:active {\n  background-color: var(--color-error);\n}\n.button-vue--legacy[data-v-06ad9b25] {\n  --button-inner-size: var(--button-size);\n  border: none;\n  padding-block: 0;\n}\n.button-vue--legacy.button-vue--error[data-v-06ad9b25], .button-vue--legacy.button-vue--success[data-v-06ad9b25], .button-vue--legacy.button-vue--warning[data-v-06ad9b25] {\n  color: white;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-aaedb1c3] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.icon-vue[data-v-aaedb1c3] {\n  display: flex;\n  justify-content: center;\n  align-items: center;\n  min-width: var(--default-clickable-area);\n  min-height: var(--default-clickable-area);\n  opacity: 1;\n}\n.icon-vue.icon-vue--inline[data-v-aaedb1c3] {\n  display: inline-flex !important;\n  min-width: fit-content;\n  min-height: fit-content;\n  vertical-align: text-bottom;\n}\n.icon-vue span[data-v-aaedb1c3] {\n  line-height: 0;\n}\n.icon-vue[data-v-aaedb1c3] svg {\n  fill: currentColor;\n  width: var(--fb515064);\n  height: var(--fb515064);\n  max-width: var(--fb515064);\n  max-height: var(--fb515064);\n}\n.icon-vue--directional[data-v-aaedb1c3] svg:dir(rtl) {\n  transform: scaleX(-1);\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-cf399190] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.loading-icon[data-v-cf399190] {\n  overflow: hidden;\n}\n.loading-icon svg[data-v-cf399190] {\n  animation: rotate var(--animation-duration, 0.8s) linear infinite;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-67fb20ba] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.modal-mask[data-v-67fb20ba] {\n  position: fixed;\n  z-index: 9998;\n  top: 0;\n  inset-inline-start: 0;\n  display: block;\n  width: 100%;\n  height: 100%;\n  --backdrop-color: 0, 0, 0;\n  background-color: rgba(var(--backdrop-color), 0.5);\n}\n.modal-mask[data-v-67fb20ba], .modal-mask[data-v-67fb20ba] * {\n  box-sizing: border-box;\n}\n.modal-mask--opaque[data-v-67fb20ba] {\n  background-color: rgba(var(--backdrop-color), 0.92);\n}\n.modal-mask--light[data-v-67fb20ba] {\n  --backdrop-color: 255, 255, 255;\n}\n.modal-header[data-v-67fb20ba] {\n  position: absolute;\n  z-index: 10001;\n  top: 0;\n  inset-inline: 0 0;\n  display: flex !important;\n  align-items: center;\n  justify-content: center;\n  width: 100%;\n  height: var(--header-height);\n  overflow: hidden;\n  transition: opacity 250ms, visibility 250ms;\n}\n.modal-header__name[data-v-67fb20ba] {\n  overflow-x: hidden;\n  width: 100%;\n  padding: 0 calc(var(--default-clickable-area) * 3) 0 12px;\n  transition: padding ease 100ms;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n  font-size: 16px;\n  margin-block: 0;\n}\n@media only screen and (min-width: 1024px) {\n.modal-header__name[data-v-67fb20ba] {\n    padding-inline-start: calc(var(--default-clickable-area) * 3);\n    text-align: center;\n}\n}\n.modal-header .icons-menu[data-v-67fb20ba] {\n  position: absolute;\n  inset-inline-end: 0;\n  display: flex;\n  align-items: center;\n  justify-content: flex-end;\n}\n.modal-header .icons-menu .header-close[data-v-67fb20ba] {\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  margin: calc((var(--header-height) - var(--default-clickable-area)) / 2);\n  padding: 0;\n}\n.modal-header .icons-menu .play-pause-icons[data-v-67fb20ba] {\n  position: relative;\n  width: var(--header-height);\n  height: var(--header-height);\n  margin: 0;\n  padding: 0;\n  cursor: pointer;\n  border: none;\n  background-color: transparent;\n}\n.modal-header .icons-menu .play-pause-icons:hover .play-pause-icons__icon[data-v-67fb20ba], .modal-header .icons-menu .play-pause-icons:focus .play-pause-icons__icon[data-v-67fb20ba] {\n  opacity: 1;\n  border-radius: calc(var(--default-clickable-area) / 2);\n  background-color: rgba(127, 127, 127, 0.25);\n}\n.modal-header .icons-menu .play-pause-icons__icon[data-v-67fb20ba] {\n  width: var(--default-clickable-area);\n  height: var(--default-clickable-area);\n  margin: calc((var(--header-height) - var(--default-clickable-area)) / 2);\n  cursor: pointer;\n  opacity: 0.7;\n}\n.modal-header .icons-menu[data-v-67fb20ba]  .action-item {\n  margin: calc((var(--header-height) - var(--default-clickable-area)) / 2);\n}\n.modal-header .icons-menu[data-v-67fb20ba]  .action-item--single {\n  width: var(--default-clickable-area);\n  height: var(--default-clickable-area);\n  cursor: pointer;\n  background-position: center;\n  background-size: 22px;\n}\n.modal-header .icons-menu .header-actions[data-v-67fb20ba] button:focus-visible {\n  box-shadow: none !important;\n  outline: 2px solid #fff !important;\n}\n.modal-wrapper[data-v-67fb20ba] {\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  width: 100%;\n  height: 100%;\n  /* Navigation buttons */\n}\n.modal-wrapper .prev[data-v-67fb20ba],\n.modal-wrapper .next[data-v-67fb20ba] {\n  z-index: 10000;\n  height: 35vh;\n  min-height: 300px;\n  position: absolute;\n  transition: opacity 250ms;\n  color: white;\n}\n.modal-wrapper .prev[data-v-67fb20ba]:focus-visible,\n.modal-wrapper .next[data-v-67fb20ba]:focus-visible {\n  box-shadow: 0 0 0 2px var(--color-primary-element-text);\n  background-color: var(--color-box-shadow);\n}\n.modal-wrapper .prev[data-v-67fb20ba] {\n  inset-inline-start: 2px;\n}\n.modal-wrapper .next[data-v-67fb20ba] {\n  inset-inline-end: 2px;\n}\n.modal-wrapper[data-v-67fb20ba] {\n  /* Content */\n}\n.modal-wrapper .modal-container[data-v-67fb20ba] {\n  position: relative;\n  display: flex;\n  padding: 0;\n  transition: transform 300ms ease;\n  border-radius: var(--border-radius-container);\n  background-color: var(--color-main-background);\n  color: var(--color-main-text);\n  box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);\n  overflow: auto;\n}\n.modal-wrapper .modal-container__close[data-v-67fb20ba] {\n  z-index: 1;\n  position: absolute;\n  top: 4px;\n  inset-inline-end: var(--default-grid-baseline);\n}\n.modal-wrapper .modal-container__content[data-v-67fb20ba] {\n  width: 100%;\n  min-height: 52px;\n  overflow: auto;\n}\n.modal-wrapper--small > .modal-container[data-v-67fb20ba] {\n  width: 400px;\n  max-width: 90%;\n  max-height: min(90%, 100% - 2 * var(--header-height) - 2 * var(--body-container-margin));\n}\n.modal-wrapper--normal > .modal-container[data-v-67fb20ba] {\n  max-width: 90%;\n  width: 600px;\n  max-height: min(90%, 100% - 2 * var(--header-height) - 2 * var(--body-container-margin));\n}\n.modal-wrapper--large > .modal-container[data-v-67fb20ba] {\n  max-width: 90%;\n  width: 900px;\n  max-height: min(90%, 100% - 2 * var(--header-height) - 2 * var(--body-container-margin));\n}\n.modal-wrapper--full > .modal-container[data-v-67fb20ba] {\n  width: 100%;\n  height: calc(100% - var(--header-height));\n  position: absolute;\n  top: var(--header-height);\n  border-radius: 0;\n}\n@media only screen and ((max-width: 512px) or (max-height: 400px)) {\n.modal-wrapper .modal-container[data-v-67fb20ba] {\n    max-width: initial;\n    width: 100%;\n    max-height: initial;\n    height: calc(100% - var(--header-height));\n    position: absolute;\n    top: var(--header-height);\n    border-radius: 0;\n}\n}\n\n/* TRANSITIONS */\n.fade-enter-active[data-v-67fb20ba],\n.fade-leave-active[data-v-67fb20ba] {\n  transition: opacity 250ms;\n}\n.fade-enter-from[data-v-67fb20ba],\n.fade-leave-to[data-v-67fb20ba] {\n  opacity: 0;\n}\n.fade-visibility-enter-from[data-v-67fb20ba],\n.fade-visibility-leave-to[data-v-67fb20ba] {\n  visibility: hidden;\n  opacity: 0;\n}\n.modal-in-enter-active[data-v-67fb20ba],\n.modal-in-leave-active[data-v-67fb20ba],\n.modal-out-enter-active[data-v-67fb20ba],\n.modal-out-leave-active[data-v-67fb20ba] {\n  transition: opacity 250ms;\n}\n.modal-in-enter-from[data-v-67fb20ba],\n.modal-in-leave-to[data-v-67fb20ba],\n.modal-out-enter-from[data-v-67fb20ba],\n.modal-out-leave-to[data-v-67fb20ba] {\n  opacity: 0;\n}\n.modal-in-enter .modal-container[data-v-67fb20ba],\n.modal-in-leave-to .modal-container[data-v-67fb20ba] {\n  transform: scale(0.9);\n}\n.modal-out-enter .modal-container[data-v-67fb20ba],\n.modal-out-leave-to .modal-container[data-v-67fb20ba] {\n  transform: scale(1.1);\n}\n.modal-mask .play-pause-icons .progress-ring[data-v-67fb20ba] {\n  position: absolute;\n  top: 0;\n  inset-inline-start: 0;\n  transform: rotate(-90deg);\n}\n.modal-mask .play-pause-icons .progress-ring .progress-ring__circle[data-v-67fb20ba] {\n  transition: 100ms stroke-dashoffset;\n  transform-origin: 50% 50%;\n  animation: progressring-67fb20ba linear var(--7f724f28) infinite;\n  stroke-linecap: round;\n  stroke-dashoffset: 94.2477796077;\n  stroke-dasharray: 94.2477796077;\n}\n.modal-mask .play-pause-icons--paused .play-pause-icons__icon[data-v-67fb20ba] {\n  animation: breath-67fb20ba 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;\n}\n.modal-mask .play-pause-icons--paused .progress-ring__circle[data-v-67fb20ba] {\n  animation-play-state: paused !important;\n}\n@keyframes progressring-67fb20ba {\nfrom {\n    stroke-dashoffset: 94.2477796077;\n}\nto {\n    stroke-dashoffset: 0;\n}\n}\n@keyframes breath-67fb20ba {\n0% {\n    opacity: 1;\n}\n50% {\n    opacity: 0;\n}\n100% {\n    opacity: 1;\n}\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-5f7eed6b] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.action-items[data-v-5f7eed6b] {\n  display: flex;\n  align-items: center;\n  gap: calc((var(--default-clickable-area) - 16px) / 2 / 2);\n}\n.action-item[data-v-5f7eed6b] {\n  --open-background-color: var(--color-background-hover, $action-background-hover);\n  position: relative;\n  display: inline-block;\n}\n.action-item.action-item--primary[data-v-5f7eed6b] {\n  --open-background-color: var(--color-primary-element-hover);\n}\n.action-item.action-item--secondary[data-v-5f7eed6b] {\n  --open-background-color: var(--color-primary-element-light-hover);\n}\n.action-item.action-item--error[data-v-5f7eed6b] {\n  --open-background-color: var(--color-error-hover);\n}\n.action-item.action-item--warning[data-v-5f7eed6b] {\n  --open-background-color: var(--color-warning-hover);\n}\n.action-item.action-item--success[data-v-5f7eed6b] {\n  --open-background-color: var(--color-success-hover);\n}\n.action-item.action-item--tertiary-no-background[data-v-5f7eed6b] {\n  --open-background-color: transparent;\n}\n.action-item.action-item--open .action-item__menutoggle[data-v-5f7eed6b] {\n  background-color: var(--open-background-color);\n}\n.action-item__menutoggle__icon[data-v-5f7eed6b] {\n  width: 20px;\n  height: 20px;\n  object-fit: contain;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.v-popper--theme-nc-popover-9.v-popper__popper.action-item__popper .v-popper__wrapper {\n  border-radius: var(--border-radius-element);\n}\n.v-popper--theme-nc-popover-9.v-popper__popper.action-item__popper .v-popper__wrapper .v-popper__inner {\n  border-radius: var(--border-radius-element);\n  padding: 4px;\n  max-height: calc(100vh - var(--header-height));\n  overflow: auto;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n._material-design-icon_FKPyJ {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9, ._ncPopover_HjJ88.v-popper--theme-nc-popover-9 * {\n  box-sizing: border-box;\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9 .resize-observer {\n  position: absolute;\n  top: 0;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  left: 0;\n  z-index: -1;\n  width: 100%;\n  height: 100%;\n  border: none;\n  background-color: transparent;\n  pointer-events: none;\n  display: block;\n  overflow: hidden;\n  opacity: 0;\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9 .resize-observer object {\n  display: block;\n  position: absolute;\n  top: 0;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  left: 0;\n  height: 100%;\n  width: 100%;\n  overflow: hidden;\n  pointer-events: none;\n  z-index: -1;\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper {\n  z-index: 100000;\n  top: 0;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  left: 0;\n  display: block !important;\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper .v-popper__wrapper {\n  /*\n   * In theory, "filter: drop-shadow" would look better here with arrow shadow.\n   * In fact, in results in a blurry popover in Chromium on scaling.\n   * The hypothesis is that "filter" creates a new composition layer,\n   * and with GPU acceleration requires the previous layers content to be rasterized.\n   * In combination with translate3d from floating-vue, it makes Chromium to first render and rasterize the popover\n   * and then apply scaling, which results in a blurry popover.\n   */\n  box-shadow: 0 1px 10px var(--color-box-shadow);\n  border-radius: var(--border-radius-element);\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper .v-popper__inner {\n  padding: 0;\n  color: var(--color-main-text);\n  border-radius: var(--border-radius-element);\n  overflow: hidden;\n  background: var(--color-main-background);\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper .v-popper__arrow-container {\n  position: absolute;\n  z-index: 1;\n  width: 0;\n  height: 0;\n  border-style: solid;\n  border-color: transparent;\n  border-width: 10px;\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper[data-popper-placement^=top] .v-popper__arrow-container {\n  bottom: -9px;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-bottom-width: 0;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-top-color: var(--color-main-background);\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper[data-popper-placement^=bottom] .v-popper__arrow-container {\n  top: -9px;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-top-width: 0;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-bottom-color: var(--color-main-background);\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper[data-popper-placement^=right] .v-popper__arrow-container {\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  left: -9px;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-left-width: 0;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-right-color: var(--color-main-background);\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper[data-popper-placement^=left] .v-popper__arrow-container {\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  right: -9px;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-right-width: 0;\n  /* stylelint-disable-next-line csstools/use-logical */ /* upstream logic */\n  border-left-color: var(--color-main-background);\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper[aria-hidden=true] {\n  visibility: hidden;\n  transition: opacity var(--animation-quick), visibility var(--animation-quick);\n  opacity: 0;\n}\n._ncPopover_HjJ88.v-popper--theme-nc-popover-9.v-popper__popper[aria-hidden=false] {\n  visibility: visible;\n  transition: opacity var(--animation-quick);\n  opacity: 1;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-a060196e] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.checkbox-content[data-v-a060196e] {\n  display: flex;\n  align-items: center;\n  flex-direction: row;\n  gap: var(--default-grid-baseline);\n  user-select: none;\n  min-height: var(--default-clickable-area);\n  border-radius: var(--checkbox-radio-switch--border-radius);\n  padding: var(--default-grid-baseline) calc((var(--default-clickable-area) - var(--icon-height)) / 2);\n  width: 100%;\n  max-width: fit-content;\n}\n.checkbox-content__wrapper[data-v-a060196e] {\n  flex: 1 0 0;\n  max-width: 100%;\n}\n.checkbox-content__text[data-v-a060196e]:empty {\n  display: none;\n}\n.checkbox-content-checkbox:not(.checkbox-content--button-variant) .checkbox-content__icon[data-v-a060196e], .checkbox-content-radio:not(.checkbox-content--button-variant) .checkbox-content__icon[data-v-a060196e], .checkbox-content-switch:not(.checkbox-content--button-variant) .checkbox-content__icon[data-v-a060196e] {\n  margin-block: calc((var(--default-clickable-area) - 2 * var(--default-grid-baseline) - var(--icon-height)) / 2) auto;\n  line-height: 0;\n}\n.checkbox-content-checkbox:not(.checkbox-content--button-variant) .checkbox-content__icon--has-description[data-v-a060196e], .checkbox-content-radio:not(.checkbox-content--button-variant) .checkbox-content__icon--has-description[data-v-a060196e], .checkbox-content-switch:not(.checkbox-content--button-variant) .checkbox-content__icon--has-description[data-v-a060196e] {\n  display: flex;\n  align-items: center;\n  margin-block-end: 0;\n  align-self: start;\n}\n.checkbox-content__icon[data-v-a060196e] > * {\n  width: var(--icon-size);\n  height: var(--icon-height);\n  color: var(--color-primary-element);\n}\n.checkbox-content__description[data-v-a060196e] {\n  display: block;\n  color: var(--color-text-maxcontrast);\n}\n.checkbox-content--button-variant .checkbox-content__icon[data-v-a060196e]:not(.checkbox-content__icon--checked) > * {\n  color: var(--color-primary-element);\n}\n.checkbox-content--button-variant .checkbox-content__icon--checked[data-v-a060196e] > * {\n  color: var(--color-primary-element-text);\n}\n.checkbox-content--has-text[data-v-a060196e] {\n  padding-inline-end: calc((var(--default-clickable-area) - 16px) / 2);\n}\n.checkbox-content[data-v-a060196e], .checkbox-content[data-v-a060196e] * {\n  cursor: pointer;\n  flex-shrink: 0;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n.material-design-icon[data-v-6808cde4] {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n.checkbox-radio-switch[data-v-6808cde4] {\n  --icon-size: var(--1d6eb36d);\n  --icon-height: var(--698a3993);\n  --checkbox-radio-switch--border-radius: var(--border-radius-element);\n  --checkbox-radio-switch--border-radius-outer: calc(var(--checkbox-radio-switch--border-radius) + 2px);\n  display: flex;\n  align-items: center;\n  color: var(--color-main-text);\n  background-color: transparent;\n  font-size: var(--default-font-size);\n  line-height: var(--default-line-height);\n  padding: 0;\n  position: relative;\n}\n.checkbox-radio-switch__input[data-v-6808cde4] {\n  position: absolute;\n  z-index: -1;\n  opacity: 0 !important;\n  width: var(--icon-size);\n  height: var(--icon-size);\n}\n.checkbox-radio-switch__input:focus-visible + .checkbox-radio-switch__content[data-v-6808cde4], .checkbox-radio-switch__input[data-v-6808cde4]:focus-visible {\n  outline: 2px solid var(--color-main-text);\n  border-color: var(--color-main-background);\n  outline-offset: -2px;\n}\n.checkbox-radio-switch--disabled .checkbox-radio-switch__content[data-v-6808cde4] {\n  opacity: 0.5;\n}\n.checkbox-radio-switch--disabled .checkbox-radio-switch__content[data-v-6808cde4] .checkbox-radio-switch__icon > * {\n  color: var(--color-main-text);\n}\n.checkbox-radio-switch--disabled .checkbox-radio-switch__content.checkbox-content[data-v-6808cde4], .checkbox-radio-switch--disabled .checkbox-radio-switch__content.checkbox-content[data-v-6808cde4] *:not(a) {\n  cursor: default !important;\n}\n.checkbox-radio-switch:not(.checkbox-radio-switch--disabled, .checkbox-radio-switch--checked):focus-within .checkbox-radio-switch__content[data-v-6808cde4], .checkbox-radio-switch:not(.checkbox-radio-switch--disabled, .checkbox-radio-switch--checked) .checkbox-radio-switch__content[data-v-6808cde4]:hover {\n  background-color: var(--color-background-hover);\n}\n.checkbox-radio-switch--checked:not(.checkbox-radio-switch--disabled):focus-within .checkbox-radio-switch__content[data-v-6808cde4], .checkbox-radio-switch--checked:not(.checkbox-radio-switch--disabled) .checkbox-radio-switch__content[data-v-6808cde4]:hover {\n  background-color: var(--color-primary-element-hover);\n}\n.checkbox-radio-switch--checked:not(.checkbox-radio-switch--button-variant):not(.checkbox-radio-switch--disabled):focus-within .checkbox-radio-switch__content[data-v-6808cde4], .checkbox-radio-switch--checked:not(.checkbox-radio-switch--button-variant):not(.checkbox-radio-switch--disabled) .checkbox-radio-switch__content[data-v-6808cde4]:hover {\n  background-color: var(--color-primary-element-light-hover);\n}\n.checkbox-radio-switch-switch[data-v-6808cde4]:not(.checkbox-radio-switch--checked) .checkbox-radio-switch__icon > * {\n  color: var(--color-text-maxcontrast);\n}\n.checkbox-radio-switch-switch.checkbox-radio-switch--disabled.checkbox-radio-switch--checked[data-v-6808cde4] .checkbox-radio-switch__icon > * {\n  color: var(--color-primary-element-light);\n}\n.checkbox-radio-switch--button-variant.checkbox-radio-switch[data-v-6808cde4] {\n  background-color: var(--color-main-background);\n  border: 2px solid var(--color-border-maxcontrast);\n  overflow: hidden;\n}\n.checkbox-radio-switch--button-variant.checkbox-radio-switch--checked[data-v-6808cde4] {\n  font-weight: bold;\n}\n.checkbox-radio-switch--button-variant.checkbox-radio-switch--checked .checkbox-radio-switch__content[data-v-6808cde4] {\n  background-color: var(--color-primary-element);\n  color: var(--color-primary-element-text);\n}\n.checkbox-radio-switch--button-variant[data-v-6808cde4] .checkbox-radio-switch__text {\n  overflow: hidden;\n  text-overflow: ellipsis;\n  white-space: nowrap;\n  width: 100%;\n}\n.checkbox-radio-switch--button-variant[data-v-6808cde4]:not(.checkbox-radio-switch--checked) .checkbox-radio-switch__icon > * {\n  color: var(--color-main-text);\n}\n.checkbox-radio-switch--button-variant[data-v-6808cde4] .checkbox-radio-switch__icon:empty {\n  display: none;\n}\n.checkbox-radio-switch--button-variant[data-v-6808cde4]:not(.checkbox-radio-switch--button-variant-v-grouped):not(.checkbox-radio-switch--button-variant-h-grouped), .checkbox-radio-switch--button-variant .checkbox-radio-switch__content[data-v-6808cde4] {\n  border-radius: var(--checkbox-radio-switch--border-radius);\n}\n.checkbox-radio-switch[data-v-6808cde4] {\n  /* Special rules for vertical button groups */\n}\n.checkbox-radio-switch--button-variant-v-grouped .checkbox-radio-switch__content[data-v-6808cde4] {\n  flex-basis: 100%;\n  max-width: unset;\n}\n.checkbox-radio-switch--button-variant-v-grouped[data-v-6808cde4]:first-of-type {\n  border-start-start-radius: var(--checkbox-radio-switch--border-radius-outer);\n  border-start-end-radius: var(--checkbox-radio-switch--border-radius-outer);\n}\n.checkbox-radio-switch--button-variant-v-grouped[data-v-6808cde4]:last-of-type {\n  border-end-start-radius: var(--checkbox-radio-switch--border-radius-outer);\n  border-end-end-radius: var(--checkbox-radio-switch--border-radius-outer);\n}\n.checkbox-radio-switch--button-variant-v-grouped[data-v-6808cde4]:not(:last-of-type) {\n  border-bottom: 0 !important;\n}\n.checkbox-radio-switch--button-variant-v-grouped:not(:last-of-type) .checkbox-radio-switch__content[data-v-6808cde4] {\n  margin-bottom: 2px;\n}\n.checkbox-radio-switch--button-variant-v-grouped[data-v-6808cde4]:not(:first-of-type) {\n  border-top: 0 !important;\n}\n.checkbox-radio-switch[data-v-6808cde4] {\n  /* Special rules for horizontal button groups */\n}\n.checkbox-radio-switch--button-variant-h-grouped[data-v-6808cde4]:first-of-type {\n  border-start-start-radius: var(--checkbox-radio-switch--border-radius-outer);\n  border-end-start-radius: var(--checkbox-radio-switch--border-radius-outer);\n}\n.checkbox-radio-switch--button-variant-h-grouped[data-v-6808cde4]:last-of-type {\n  border-start-end-radius: var(--checkbox-radio-switch--border-radius-outer);\n  border-end-end-radius: var(--checkbox-radio-switch--border-radius-outer);\n}\n.checkbox-radio-switch--button-variant-h-grouped[data-v-6808cde4]:not(:last-of-type) {\n  border-inline-end: 0 !important;\n}\n.checkbox-radio-switch--button-variant-h-grouped:not(:last-of-type) .checkbox-radio-switch__content[data-v-6808cde4] {\n  margin-inline-end: 2px;\n}\n.checkbox-radio-switch--button-variant-h-grouped[data-v-6808cde4]:not(:first-of-type) {\n  border-inline-start: 0 !important;\n}\n.checkbox-radio-switch--button-variant-h-grouped[data-v-6808cde4] .checkbox-radio-switch__text {\n  text-align: center;\n  display: flex;\n  align-items: center;\n}\n.checkbox-radio-switch--button-variant-h-grouped .checkbox-radio-switch__content[data-v-6808cde4] {\n  flex-direction: column;\n  justify-content: center;\n  width: 100%;\n  margin: 0;\n  gap: 0;\n}/**\n * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/**\n * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors\n * SPDX-License-Identifier: AGPL-3.0-or-later\n */\n/*\n* Ensure proper alignment of the vue material icons\n*/\n._material-design-icon_ZYrc5 {\n  display: flex;\n  align-self: center;\n  justify-self: center;\n  align-items: center;\n  justify-content: center;\n}\n._iconToggleSwitch_WgcOx {\n  color: var(--6bd152af);\n  transition: color var(--animation-quick) ease;\n}\n._iconToggleSwitch_WgcOx svg {\n  /* Unlike other icons, this icon is not a square */\n  height: auto !important;\n}\n._iconToggleSwitch_WgcOx circle {\n  cx: var(--16fd8ca9);\n  transition: cx var(--animation-quick) ease;\n}'));
      document.head.appendChild(elementStyle);
    }
  } catch (e) {
    console.error("vite-plugin-css-injected-by-js", e);
  }
})();
const appName = "activity";
const appVersion = "7.0.0-dev.0";
import { p as process$1, f as getRequestToken, o as onRequestTokenUpdate, a as getLoggerBuilder } from "./index-C6VBhB33.chunk.mjs";
import { A as inject, ac as global, Y as Buffer, s as watch, a6 as shallowRef, d as defineComponent, u as unref, ad as shallowReactive, a0 as reactive, D as h$1, c as computed, p as provide, r as ref, B as getCurrentInstance, l as nextTick, P as watchEffect, e as createBlock, b as openBlock, i as withCtx, g as createBaseVNode, f as renderSlot, G as mergeProps, z as resolveDynamicComponent, j as createTextVNode, t as toDisplayString, ae as useCssVars, a as createElementBlock, n as normalizeClass, af as purify, L as warn, a7 as readonly, ag as getLanguage, ah as getPlural, M as translate, ai as translatePlural, E as createCommentVNode, a1 as toValue, O as onUnmounted, aj as pushScopeId, ak as popScopeId, al as withScopeId, m as resolveComponent, h as createVNode, F as Fragment, C as normalizeStyle, Q as withKeys, S as normalizeProps, U as guardReactiveProps, am as isRTL, an as Comment, ao as Text, ap as getCanonicalLocale, ab as toHandlers, W as createSlots, x as onMounted } from "./translation-DoG5ZELJ-BRHGJcXa.chunk.mjs";
const generateOcsUrl = (url, params, options) => {
  const allOptions = Object.assign({
    ocsVersion: 2
  }, {});
  const version = allOptions.ocsVersion === 1 ? 1 : 2;
  const baseURL = getBaseUrl();
  return baseURL + "/ocs/v" + version + ".php" + _generateUrlPath(url, params);
};
const _generateUrlPath = (url, params, options) => {
  const allOptions = Object.assign({
    escape: true
  }, {});
  const _build = function(text, vars) {
    vars = vars || {};
    return text.replace(
      /{([^{}]*)}/g,
      function(a, b) {
        const r = vars[b];
        if (allOptions.escape) {
          return typeof r === "string" || typeof r === "number" ? encodeURIComponent(r.toString()) : encodeURIComponent(a);
        } else {
          return typeof r === "string" || typeof r === "number" ? r.toString() : a;
        }
      }
    );
  };
  if (url.charAt(0) !== "/") {
    url = "/" + url;
  }
  return _build(url, params || {});
};
const generateUrl = (url, params, options) => {
  const allOptions = Object.assign({
    noRewrite: false
  }, {});
  const baseOrRootURL = getRootUrl();
  if (window?.OC?.config?.modRewriteWorking === true && !allOptions.noRewrite) {
    return baseOrRootURL + _generateUrlPath(url, params);
  }
  return baseOrRootURL + "/index.php" + _generateUrlPath(url, params);
};
const imagePath = (app, file) => {
  if (!file.includes(".")) {
    return generateFilePath(app, "img", `${file}.svg`);
  }
  return generateFilePath(app, "img", file);
};
const generateFilePath = (app, type, file) => {
  const isCore = window?.OC?.coreApps?.includes(app) ?? false;
  const isPHP = file.slice(-3) === "php";
  let link = getRootUrl();
  if (isPHP && !isCore) {
    link += `/index.php/apps/${app}`;
    {
      link += `/${encodeURI(type)}`;
    }
    if (file !== "index.php") {
      link += `/${file}`;
    }
  } else if (!isPHP && !isCore) {
    link = getAppRootUrl(app);
    {
      link += `/${type}/`;
    }
    if (link.at(-1) !== "/") {
      link += "/";
    }
    link += file;
  } else {
    {
      link += `/${app}`;
    }
    {
      link += `/${type}`;
    }
    link += `/${file}`;
  }
  return link;
};
const getBaseUrl = () => window.location.protocol + "//" + window.location.host + getRootUrl();
function getRootUrl() {
  let webroot = window._oc_webroot;
  if (typeof webroot === "undefined") {
    webroot = location.pathname;
    const pos = webroot.indexOf("/index.php/");
    if (pos !== -1) {
      webroot = webroot.slice(0, pos);
    } else {
      const index = webroot.indexOf("/", 1);
      webroot = webroot.slice(0, index > 0 ? index : void 0);
    }
  }
  return webroot;
}
function getAppRootUrl(app) {
  const webroots = window._oc_appswebroots ?? {};
  return webroots[app] ?? "";
}
/*!
 * vue-router v5.0.3
 * (c) 2026 Eduardo San Martin Morote
 * @license MIT
 */
const isBrowser$1 = typeof document !== "undefined";
function isRouteComponent(component) {
  return typeof component === "object" || "displayName" in component || "props" in component || "__vccOpts" in component;
}
function isESModule(obj) {
  return obj.__esModule || obj[Symbol.toStringTag] === "Module" || obj.default && isRouteComponent(obj.default);
}
const assign = Object.assign;
function applyToParams(fn, params) {
  const newParams = {};
  for (const key in params) {
    const value = params[key];
    newParams[key] = isArray$3(value) ? value.map(fn) : fn(value);
  }
  return newParams;
}
const noop$1 = () => {
};
const isArray$3 = Array.isArray;
function mergeOptions(defaults2, partialOptions) {
  const options = {};
  for (const key in defaults2) options[key] = key in partialOptions ? partialOptions[key] : defaults2[key];
  return options;
}
let ErrorTypes = /* @__PURE__ */ (function(ErrorTypes2) {
  ErrorTypes2[ErrorTypes2["MATCHER_NOT_FOUND"] = 1] = "MATCHER_NOT_FOUND";
  ErrorTypes2[ErrorTypes2["NAVIGATION_GUARD_REDIRECT"] = 2] = "NAVIGATION_GUARD_REDIRECT";
  ErrorTypes2[ErrorTypes2["NAVIGATION_ABORTED"] = 4] = "NAVIGATION_ABORTED";
  ErrorTypes2[ErrorTypes2["NAVIGATION_CANCELLED"] = 8] = "NAVIGATION_CANCELLED";
  ErrorTypes2[ErrorTypes2["NAVIGATION_DUPLICATED"] = 16] = "NAVIGATION_DUPLICATED";
  return ErrorTypes2;
})({});
const NavigationFailureSymbol = /* @__PURE__ */ Symbol("navigation failure");
const ErrorTypeMessages = {
  [ErrorTypes.MATCHER_NOT_FOUND]({ location: location2, currentLocation }) {
    return `No match for
 ${JSON.stringify(location2)}${currentLocation ? "\nwhile being at\n" + JSON.stringify(currentLocation) : ""}`;
  },
  [ErrorTypes.NAVIGATION_GUARD_REDIRECT]({ from, to }) {
    return `Redirected from "${from.fullPath}" to "${stringifyRoute(to)}" via a navigation guard.`;
  },
  [ErrorTypes.NAVIGATION_ABORTED]({ from, to }) {
    return `Navigation aborted from "${from.fullPath}" to "${to.fullPath}" via a navigation guard.`;
  },
  [ErrorTypes.NAVIGATION_CANCELLED]({ from, to }) {
    return `Navigation cancelled from "${from.fullPath}" to "${to.fullPath}" with a new navigation.`;
  },
  [ErrorTypes.NAVIGATION_DUPLICATED]({ from, to }) {
    return `Avoided redundant navigation to current location: "${from.fullPath}".`;
  }
};
function createRouterError(type, params) {
  return assign(new Error(ErrorTypeMessages[type](params)), {
    type,
    [NavigationFailureSymbol]: true
  }, params);
}
function isNavigationFailure(error, type) {
  return error instanceof Error && NavigationFailureSymbol in error && (type == null || !!(error.type & type));
}
const propertiesToLog = [
  "params",
  "query",
  "hash"
];
function stringifyRoute(to) {
  if (typeof to === "string") return to;
  if (to.path != null) return to.path;
  const location2 = {};
  for (const key of propertiesToLog) if (key in to) location2[key] = to[key];
  return JSON.stringify(location2, null, 2);
}
const matchedRouteKey = /* @__PURE__ */ Symbol("router view location matched");
const viewDepthKey = /* @__PURE__ */ Symbol("router view depth");
const routerKey = /* @__PURE__ */ Symbol("router");
const routeLocationKey = /* @__PURE__ */ Symbol("route location");
const routerViewLocationKey = /* @__PURE__ */ Symbol("router view location");
function useRoute(_name) {
  return inject(routeLocationKey);
}
var __create$1 = Object.create;
var __defProp$1 = Object.defineProperty;
var __getOwnPropDesc$1 = Object.getOwnPropertyDescriptor;
var __getOwnPropNames$1 = Object.getOwnPropertyNames;
var __getProtoOf$1 = Object.getPrototypeOf;
var __hasOwnProp$1 = Object.prototype.hasOwnProperty;
var __commonJS$1 = (cb, mod) => function() {
  return mod || (0, cb[__getOwnPropNames$1(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
};
var __copyProps$1 = (to, from, except, desc) => {
  if (from && typeof from === "object" || typeof from === "function") for (var keys = __getOwnPropNames$1(from), i = 0, n2 = keys.length, key; i < n2; i++) {
    key = keys[i];
    if (!__hasOwnProp$1.call(to, key) && key !== except) __defProp$1(to, key, {
      get: ((k2) => from[k2]).bind(null, key),
      enumerable: !(desc = __getOwnPropDesc$1(from, key)) || desc.enumerable
    });
  }
  return to;
};
var __toESM$1 = (mod, isNodeMode, target$1) => (target$1 = mod != null ? __create$1(__getProtoOf$1(mod)) : {}, __copyProps$1(__defProp$1(target$1, "default", {
  value: mod,
  enumerable: true
}), mod));
const isBrowser = typeof navigator !== "undefined";
const target = typeof window !== "undefined" ? window : typeof globalThis !== "undefined" ? globalThis : typeof global !== "undefined" ? global : {};
typeof target.chrome !== "undefined" && !!target.chrome.devtools;
isBrowser && target.self !== target.top;
typeof navigator !== "undefined" && navigator.userAgent?.toLowerCase().includes("electron");
var require_rfdc = /* @__PURE__ */ __commonJS$1({ "../../node_modules/.pnpm/rfdc@1.4.1/node_modules/rfdc/index.js": ((exports, module) => {
  module.exports = rfdc$1;
  function copyBuffer(cur) {
    if (cur instanceof Buffer) return Buffer.from(cur);
    return new cur.constructor(cur.buffer.slice(), cur.byteOffset, cur.length);
  }
  function rfdc$1(opts) {
    opts = opts || {};
    if (opts.circles) return rfdcCircles(opts);
    const constructorHandlers = /* @__PURE__ */ new Map();
    constructorHandlers.set(Date, (o) => new Date(o));
    constructorHandlers.set(Map, (o, fn) => new Map(cloneArray(Array.from(o), fn)));
    constructorHandlers.set(Set, (o, fn) => new Set(cloneArray(Array.from(o), fn)));
    if (opts.constructorHandlers) for (const handler$1 of opts.constructorHandlers) constructorHandlers.set(handler$1[0], handler$1[1]);
    let handler = null;
    return opts.proto ? cloneProto : clone;
    function cloneArray(a, fn) {
      const keys = Object.keys(a);
      const a2 = new Array(keys.length);
      for (let i = 0; i < keys.length; i++) {
        const k2 = keys[i];
        const cur = a[k2];
        if (typeof cur !== "object" || cur === null) a2[k2] = cur;
        else if (cur.constructor !== Object && (handler = constructorHandlers.get(cur.constructor))) a2[k2] = handler(cur, fn);
        else if (ArrayBuffer.isView(cur)) a2[k2] = copyBuffer(cur);
        else a2[k2] = fn(cur);
      }
      return a2;
    }
    function clone(o) {
      if (typeof o !== "object" || o === null) return o;
      if (Array.isArray(o)) return cloneArray(o, clone);
      if (o.constructor !== Object && (handler = constructorHandlers.get(o.constructor))) return handler(o, clone);
      const o2 = {};
      for (const k2 in o) {
        if (Object.hasOwnProperty.call(o, k2) === false) continue;
        const cur = o[k2];
        if (typeof cur !== "object" || cur === null) o2[k2] = cur;
        else if (cur.constructor !== Object && (handler = constructorHandlers.get(cur.constructor))) o2[k2] = handler(cur, clone);
        else if (ArrayBuffer.isView(cur)) o2[k2] = copyBuffer(cur);
        else o2[k2] = clone(cur);
      }
      return o2;
    }
    function cloneProto(o) {
      if (typeof o !== "object" || o === null) return o;
      if (Array.isArray(o)) return cloneArray(o, cloneProto);
      if (o.constructor !== Object && (handler = constructorHandlers.get(o.constructor))) return handler(o, cloneProto);
      const o2 = {};
      for (const k2 in o) {
        const cur = o[k2];
        if (typeof cur !== "object" || cur === null) o2[k2] = cur;
        else if (cur.constructor !== Object && (handler = constructorHandlers.get(cur.constructor))) o2[k2] = handler(cur, cloneProto);
        else if (ArrayBuffer.isView(cur)) o2[k2] = copyBuffer(cur);
        else o2[k2] = cloneProto(cur);
      }
      return o2;
    }
  }
  function rfdcCircles(opts) {
    const refs = [];
    const refsNew = [];
    const constructorHandlers = /* @__PURE__ */ new Map();
    constructorHandlers.set(Date, (o) => new Date(o));
    constructorHandlers.set(Map, (o, fn) => new Map(cloneArray(Array.from(o), fn)));
    constructorHandlers.set(Set, (o, fn) => new Set(cloneArray(Array.from(o), fn)));
    if (opts.constructorHandlers) for (const handler$1 of opts.constructorHandlers) constructorHandlers.set(handler$1[0], handler$1[1]);
    let handler = null;
    return opts.proto ? cloneProto : clone;
    function cloneArray(a, fn) {
      const keys = Object.keys(a);
      const a2 = new Array(keys.length);
      for (let i = 0; i < keys.length; i++) {
        const k2 = keys[i];
        const cur = a[k2];
        if (typeof cur !== "object" || cur === null) a2[k2] = cur;
        else if (cur.constructor !== Object && (handler = constructorHandlers.get(cur.constructor))) a2[k2] = handler(cur, fn);
        else if (ArrayBuffer.isView(cur)) a2[k2] = copyBuffer(cur);
        else {
          const index = refs.indexOf(cur);
          if (index !== -1) a2[k2] = refsNew[index];
          else a2[k2] = fn(cur);
        }
      }
      return a2;
    }
    function clone(o) {
      if (typeof o !== "object" || o === null) return o;
      if (Array.isArray(o)) return cloneArray(o, clone);
      if (o.constructor !== Object && (handler = constructorHandlers.get(o.constructor))) return handler(o, clone);
      const o2 = {};
      refs.push(o);
      refsNew.push(o2);
      for (const k2 in o) {
        if (Object.hasOwnProperty.call(o, k2) === false) continue;
        const cur = o[k2];
        if (typeof cur !== "object" || cur === null) o2[k2] = cur;
        else if (cur.constructor !== Object && (handler = constructorHandlers.get(cur.constructor))) o2[k2] = handler(cur, clone);
        else if (ArrayBuffer.isView(cur)) o2[k2] = copyBuffer(cur);
        else {
          const i = refs.indexOf(cur);
          if (i !== -1) o2[k2] = refsNew[i];
          else o2[k2] = clone(cur);
        }
      }
      refs.pop();
      refsNew.pop();
      return o2;
    }
    function cloneProto(o) {
      if (typeof o !== "object" || o === null) return o;
      if (Array.isArray(o)) return cloneArray(o, cloneProto);
      if (o.constructor !== Object && (handler = constructorHandlers.get(o.constructor))) return handler(o, cloneProto);
      const o2 = {};
      refs.push(o);
      refsNew.push(o2);
      for (const k2 in o) {
        const cur = o[k2];
        if (typeof cur !== "object" || cur === null) o2[k2] = cur;
        else if (cur.constructor !== Object && (handler = constructorHandlers.get(cur.constructor))) o2[k2] = handler(cur, cloneProto);
        else if (ArrayBuffer.isView(cur)) o2[k2] = copyBuffer(cur);
        else {
          const i = refs.indexOf(cur);
          if (i !== -1) o2[k2] = refsNew[i];
          else o2[k2] = cloneProto(cur);
        }
      }
      refs.pop();
      refsNew.pop();
      return o2;
    }
  }
}) });
var import_rfdc = /* @__PURE__ */ __toESM$1(require_rfdc());
const classifyRE = /(?:^|[-_/])(\w)/g;
function toUpper(_2, c2) {
  return c2 ? c2.toUpperCase() : "";
}
function classify(str) {
  return str && `${str}`.replace(classifyRE, toUpper);
}
function basename(filename, ext) {
  let normalizedFilename = filename.replace(/^[a-z]:/i, "").replace(/\\/g, "/");
  if (normalizedFilename.endsWith(`index${ext}`)) normalizedFilename = normalizedFilename.replace(`/index${ext}`, ext);
  const lastSlashIndex = normalizedFilename.lastIndexOf("/");
  const baseNameWithExt = normalizedFilename.substring(lastSlashIndex + 1);
  {
    const extIndex = baseNameWithExt.lastIndexOf(ext);
    return baseNameWithExt.substring(0, extIndex);
  }
}
const deepClone = (0, import_rfdc.default)({ circles: true });
const DEBOUNCE_DEFAULTS = { trailing: true };
function debounce(fn, wait = 25, options = {}) {
  options = {
    ...DEBOUNCE_DEFAULTS,
    ...options
  };
  if (!Number.isFinite(wait)) throw new TypeError("Expected `wait` to be a finite number");
  let leadingValue;
  let timeout;
  let resolveList = [];
  let currentPromise;
  let trailingArgs;
  const applyFn = (_this, args) => {
    currentPromise = _applyPromised(fn, _this, args);
    currentPromise.finally(() => {
      currentPromise = null;
      if (options.trailing && trailingArgs && !timeout) {
        const promise = applyFn(_this, trailingArgs);
        trailingArgs = null;
        return promise;
      }
    });
    return currentPromise;
  };
  const debounced = function(...args) {
    if (options.trailing) trailingArgs = args;
    if (currentPromise) return currentPromise;
    return new Promise((resolve) => {
      const shouldCallNow = !timeout && options.leading;
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        timeout = null;
        const promise = options.leading ? leadingValue : applyFn(this, args);
        trailingArgs = null;
        for (const _resolve of resolveList) _resolve(promise);
        resolveList = [];
      }, wait);
      if (shouldCallNow) {
        leadingValue = applyFn(this, args);
        resolve(leadingValue);
      } else resolveList.push(resolve);
    });
  };
  const _clearTimeout = (timer) => {
    if (timer) {
      clearTimeout(timer);
      timeout = null;
    }
  };
  debounced.isPending = () => !!timeout;
  debounced.cancel = () => {
    _clearTimeout(timeout);
    resolveList = [];
    trailingArgs = null;
  };
  debounced.flush = () => {
    _clearTimeout(timeout);
    if (!trailingArgs || currentPromise) return;
    const args = trailingArgs;
    trailingArgs = null;
    return applyFn(this, args);
  };
  return debounced;
}
async function _applyPromised(fn, _this, args) {
  return await fn.apply(_this, args);
}
function flatHooks(configHooks, hooks2 = {}, parentName) {
  for (const key in configHooks) {
    const subHook = configHooks[key];
    const name = parentName ? `${parentName}:${key}` : key;
    if (typeof subHook === "object" && subHook !== null) {
      flatHooks(subHook, hooks2, name);
    } else if (typeof subHook === "function") {
      hooks2[name] = subHook;
    }
  }
  return hooks2;
}
const defaultTask = { run: (function_) => function_() };
const _createTask = () => defaultTask;
const createTask = typeof console.createTask !== "undefined" ? console.createTask : _createTask;
function serialTaskCaller(hooks2, args) {
  const name = args.shift();
  const task = createTask(name);
  return hooks2.reduce(
    (promise, hookFunction) => promise.then(() => task.run(() => hookFunction(...args))),
    Promise.resolve()
  );
}
function parallelTaskCaller(hooks2, args) {
  const name = args.shift();
  const task = createTask(name);
  return Promise.all(hooks2.map((hook2) => task.run(() => hook2(...args))));
}
function callEachWith(callbacks, arg0) {
  for (const callback of [...callbacks]) {
    callback(arg0);
  }
}
class Hookable {
  constructor() {
    this._hooks = {};
    this._before = void 0;
    this._after = void 0;
    this._deprecatedMessages = void 0;
    this._deprecatedHooks = {};
    this.hook = this.hook.bind(this);
    this.callHook = this.callHook.bind(this);
    this.callHookWith = this.callHookWith.bind(this);
  }
  hook(name, function_, options = {}) {
    if (!name || typeof function_ !== "function") {
      return () => {
      };
    }
    const originalName = name;
    let dep;
    while (this._deprecatedHooks[name]) {
      dep = this._deprecatedHooks[name];
      name = dep.to;
    }
    if (dep && !options.allowDeprecated) {
      let message = dep.message;
      if (!message) {
        message = `${originalName} hook has been deprecated` + (dep.to ? `, please use ${dep.to}` : "");
      }
      if (!this._deprecatedMessages) {
        this._deprecatedMessages = /* @__PURE__ */ new Set();
      }
      if (!this._deprecatedMessages.has(message)) {
        console.warn(message);
        this._deprecatedMessages.add(message);
      }
    }
    if (!function_.name) {
      try {
        Object.defineProperty(function_, "name", {
          get: () => "_" + name.replace(/\W+/g, "_") + "_hook_cb",
          configurable: true
        });
      } catch {
      }
    }
    this._hooks[name] = this._hooks[name] || [];
    this._hooks[name].push(function_);
    return () => {
      if (function_) {
        this.removeHook(name, function_);
        function_ = void 0;
      }
    };
  }
  hookOnce(name, function_) {
    let _unreg;
    let _function = (...arguments_) => {
      if (typeof _unreg === "function") {
        _unreg();
      }
      _unreg = void 0;
      _function = void 0;
      return function_(...arguments_);
    };
    _unreg = this.hook(name, _function);
    return _unreg;
  }
  removeHook(name, function_) {
    if (this._hooks[name]) {
      const index = this._hooks[name].indexOf(function_);
      if (index !== -1) {
        this._hooks[name].splice(index, 1);
      }
      if (this._hooks[name].length === 0) {
        delete this._hooks[name];
      }
    }
  }
  deprecateHook(name, deprecated) {
    this._deprecatedHooks[name] = typeof deprecated === "string" ? { to: deprecated } : deprecated;
    const _hooks = this._hooks[name] || [];
    delete this._hooks[name];
    for (const hook2 of _hooks) {
      this.hook(name, hook2);
    }
  }
  deprecateHooks(deprecatedHooks) {
    Object.assign(this._deprecatedHooks, deprecatedHooks);
    for (const name in deprecatedHooks) {
      this.deprecateHook(name, deprecatedHooks[name]);
    }
  }
  addHooks(configHooks) {
    const hooks2 = flatHooks(configHooks);
    const removeFns = Object.keys(hooks2).map(
      (key) => this.hook(key, hooks2[key])
    );
    return () => {
      for (const unreg of removeFns.splice(0, removeFns.length)) {
        unreg();
      }
    };
  }
  removeHooks(configHooks) {
    const hooks2 = flatHooks(configHooks);
    for (const key in hooks2) {
      this.removeHook(key, hooks2[key]);
    }
  }
  removeAllHooks() {
    for (const key in this._hooks) {
      delete this._hooks[key];
    }
  }
  callHook(name, ...arguments_) {
    arguments_.unshift(name);
    return this.callHookWith(serialTaskCaller, name, ...arguments_);
  }
  callHookParallel(name, ...arguments_) {
    arguments_.unshift(name);
    return this.callHookWith(parallelTaskCaller, name, ...arguments_);
  }
  callHookWith(caller, name, ...arguments_) {
    const event = this._before || this._after ? { name, args: arguments_, context: {} } : void 0;
    if (this._before) {
      callEachWith(this._before, event);
    }
    const result = caller(
      name in this._hooks ? [...this._hooks[name]] : [],
      arguments_
    );
    if (result instanceof Promise) {
      return result.finally(() => {
        if (this._after && event) {
          callEachWith(this._after, event);
        }
      });
    }
    if (this._after && event) {
      callEachWith(this._after, event);
    }
    return result;
  }
  beforeEach(function_) {
    this._before = this._before || [];
    this._before.push(function_);
    return () => {
      if (this._before !== void 0) {
        const index = this._before.indexOf(function_);
        if (index !== -1) {
          this._before.splice(index, 1);
        }
      }
    };
  }
  afterEach(function_) {
    this._after = this._after || [];
    this._after.push(function_);
    return () => {
      if (this._after !== void 0) {
        const index = this._after.indexOf(function_);
        if (index !== -1) {
          this._after.splice(index, 1);
        }
      }
    };
  }
}
function createHooks() {
  return new Hookable();
}
var __create = Object.create;
var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
var __getProtoOf = Object.getPrototypeOf;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __commonJS = (cb, mod) => function() {
  return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
};
var __copyProps = (to, from, except, desc) => {
  if (from && typeof from === "object" || typeof from === "function") for (var keys = __getOwnPropNames(from), i = 0, n2 = keys.length, key; i < n2; i++) {
    key = keys[i];
    if (!__hasOwnProp.call(to, key) && key !== except) __defProp(to, key, {
      get: ((k2) => from[k2]).bind(null, key),
      enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable
    });
  }
  return to;
};
var __toESM = (mod, isNodeMode, target$1) => (target$1 = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(__defProp(target$1, "default", {
  value: mod,
  enumerable: true
}), mod));
function getComponentTypeName(options) {
  const name = options.name || options._componentTag || options.__VUE_DEVTOOLS_COMPONENT_GUSSED_NAME__ || options.__name;
  if (name === "index" && options.__file?.endsWith("index.vue")) return "";
  return name;
}
function getComponentFileName(options) {
  const file = options.__file;
  if (file) return classify(basename(file, ".vue"));
}
function saveComponentGussedName(instance, name) {
  instance.type.__VUE_DEVTOOLS_COMPONENT_GUSSED_NAME__ = name;
  return name;
}
function getAppRecord(instance) {
  if (instance.__VUE_DEVTOOLS_NEXT_APP_RECORD__) return instance.__VUE_DEVTOOLS_NEXT_APP_RECORD__;
  else if (instance.root) return instance.appContext.app.__VUE_DEVTOOLS_NEXT_APP_RECORD__;
}
function isFragment(instance) {
  const subTreeType = instance.subTree?.type;
  const appRecord = getAppRecord(instance);
  if (appRecord) return appRecord?.types?.Fragment === subTreeType;
  return false;
}
function getInstanceName(instance) {
  const name = getComponentTypeName(instance?.type || {});
  if (name) return name;
  if (instance?.root === instance) return "Root";
  for (const key in instance.parent?.type?.components) if (instance.parent.type.components[key] === instance?.type) return saveComponentGussedName(instance, key);
  for (const key in instance.appContext?.components) if (instance.appContext.components[key] === instance?.type) return saveComponentGussedName(instance, key);
  const fileName = getComponentFileName(instance?.type || {});
  if (fileName) return fileName;
  return "Anonymous Component";
}
function getUniqueComponentId(instance) {
  return `${instance?.appContext?.app?.__VUE_DEVTOOLS_NEXT_APP_RECORD_ID__ ?? 0}:${instance === instance?.root ? "root" : instance.uid}`;
}
function getComponentInstance(appRecord, instanceId) {
  instanceId = instanceId || `${appRecord.id}:root`;
  return appRecord.instanceMap.get(instanceId) || appRecord.instanceMap.get(":root");
}
function createRect() {
  const rect = {
    top: 0,
    bottom: 0,
    left: 0,
    right: 0,
    get width() {
      return rect.right - rect.left;
    },
    get height() {
      return rect.bottom - rect.top;
    }
  };
  return rect;
}
let range;
function getTextRect(node) {
  if (!range) range = document.createRange();
  range.selectNode(node);
  return range.getBoundingClientRect();
}
function getFragmentRect(vnode) {
  const rect = createRect();
  if (!vnode.children) return rect;
  for (let i = 0, l = vnode.children.length; i < l; i++) {
    const childVnode = vnode.children[i];
    let childRect;
    if (childVnode.component) childRect = getComponentBoundingRect(childVnode.component);
    else if (childVnode.el) {
      const el = childVnode.el;
      if (el.nodeType === 1 || el.getBoundingClientRect) childRect = el.getBoundingClientRect();
      else if (el.nodeType === 3 && el.data.trim()) childRect = getTextRect(el);
    }
    if (childRect) mergeRects(rect, childRect);
  }
  return rect;
}
function mergeRects(a, b) {
  if (!a.top || b.top < a.top) a.top = b.top;
  if (!a.bottom || b.bottom > a.bottom) a.bottom = b.bottom;
  if (!a.left || b.left < a.left) a.left = b.left;
  if (!a.right || b.right > a.right) a.right = b.right;
  return a;
}
const DEFAULT_RECT = {
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  width: 0,
  height: 0
};
function getComponentBoundingRect(instance) {
  const el = instance.subTree.el;
  if (typeof window === "undefined") return DEFAULT_RECT;
  if (isFragment(instance)) return getFragmentRect(instance.subTree);
  else if (el?.nodeType === 1) return el?.getBoundingClientRect();
  else if (instance.subTree.component) return getComponentBoundingRect(instance.subTree.component);
  else return DEFAULT_RECT;
}
function getRootElementsFromComponentInstance(instance) {
  if (isFragment(instance)) return getFragmentRootElements(instance.subTree);
  if (!instance.subTree) return [];
  return [instance.subTree.el];
}
function getFragmentRootElements(vnode) {
  if (!vnode.children) return [];
  const list = [];
  vnode.children.forEach((childVnode) => {
    if (childVnode.component) list.push(...getRootElementsFromComponentInstance(childVnode.component));
    else if (childVnode?.el) list.push(childVnode.el);
  });
  return list;
}
const CONTAINER_ELEMENT_ID = "__vue-devtools-component-inspector__";
const CARD_ELEMENT_ID = "__vue-devtools-component-inspector__card__";
const COMPONENT_NAME_ELEMENT_ID = "__vue-devtools-component-inspector__name__";
const INDICATOR_ELEMENT_ID = "__vue-devtools-component-inspector__indicator__";
const containerStyles = {
  display: "block",
  zIndex: 2147483640,
  position: "fixed",
  backgroundColor: "#42b88325",
  border: "1px solid #42b88350",
  borderRadius: "5px",
  transition: "all 0.1s ease-in",
  pointerEvents: "none"
};
const cardStyles = {
  fontFamily: "Arial, Helvetica, sans-serif",
  padding: "5px 8px",
  borderRadius: "4px",
  textAlign: "left",
  position: "absolute",
  left: 0,
  color: "#e9e9e9",
  fontSize: "14px",
  fontWeight: 600,
  lineHeight: "24px",
  backgroundColor: "#42b883",
  boxShadow: "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1)"
};
const indicatorStyles = {
  display: "inline-block",
  fontWeight: 400,
  fontStyle: "normal",
  fontSize: "12px",
  opacity: 0.7
};
function getContainerElement() {
  return document.getElementById(CONTAINER_ELEMENT_ID);
}
function getCardElement() {
  return document.getElementById(CARD_ELEMENT_ID);
}
function getIndicatorElement() {
  return document.getElementById(INDICATOR_ELEMENT_ID);
}
function getNameElement() {
  return document.getElementById(COMPONENT_NAME_ELEMENT_ID);
}
function getStyles(bounds) {
  return {
    left: `${Math.round(bounds.left * 100) / 100}px`,
    top: `${Math.round(bounds.top * 100) / 100}px`,
    width: `${Math.round(bounds.width * 100) / 100}px`,
    height: `${Math.round(bounds.height * 100) / 100}px`
  };
}
function create(options) {
  const containerEl = document.createElement("div");
  containerEl.id = options.elementId ?? CONTAINER_ELEMENT_ID;
  Object.assign(containerEl.style, {
    ...containerStyles,
    ...getStyles(options.bounds),
    ...options.style
  });
  const cardEl = document.createElement("span");
  cardEl.id = CARD_ELEMENT_ID;
  Object.assign(cardEl.style, {
    ...cardStyles,
    top: options.bounds.top < 35 ? 0 : "-35px"
  });
  const nameEl = document.createElement("span");
  nameEl.id = COMPONENT_NAME_ELEMENT_ID;
  nameEl.innerHTML = `&lt;${options.name}&gt;&nbsp;&nbsp;`;
  const indicatorEl = document.createElement("i");
  indicatorEl.id = INDICATOR_ELEMENT_ID;
  indicatorEl.innerHTML = `${Math.round(options.bounds.width * 100) / 100} x ${Math.round(options.bounds.height * 100) / 100}`;
  Object.assign(indicatorEl.style, indicatorStyles);
  cardEl.appendChild(nameEl);
  cardEl.appendChild(indicatorEl);
  containerEl.appendChild(cardEl);
  document.body.appendChild(containerEl);
  return containerEl;
}
function update(options) {
  const containerEl = getContainerElement();
  const cardEl = getCardElement();
  const nameEl = getNameElement();
  const indicatorEl = getIndicatorElement();
  if (containerEl) {
    Object.assign(containerEl.style, {
      ...containerStyles,
      ...getStyles(options.bounds)
    });
    Object.assign(cardEl.style, { top: options.bounds.top < 35 ? 0 : "-35px" });
    nameEl.innerHTML = `&lt;${options.name}&gt;&nbsp;&nbsp;`;
    indicatorEl.innerHTML = `${Math.round(options.bounds.width * 100) / 100} x ${Math.round(options.bounds.height * 100) / 100}`;
  }
}
function highlight(instance) {
  const bounds = getComponentBoundingRect(instance);
  if (!bounds.width && !bounds.height) return;
  const name = getInstanceName(instance);
  getContainerElement() ? update({
    bounds,
    name
  }) : create({
    bounds,
    name
  });
}
function unhighlight() {
  const el = getContainerElement();
  if (el) el.style.display = "none";
}
let inspectInstance = null;
function inspectFn(e) {
  const target$1 = e.target;
  if (target$1) {
    const instance = target$1.__vueParentComponent;
    if (instance) {
      inspectInstance = instance;
      if (instance.vnode.el) {
        const bounds = getComponentBoundingRect(instance);
        const name = getInstanceName(instance);
        getContainerElement() ? update({
          bounds,
          name
        }) : create({
          bounds,
          name
        });
      }
    }
  }
}
function selectComponentFn(e, cb) {
  e.preventDefault();
  e.stopPropagation();
  if (inspectInstance) cb(getUniqueComponentId(inspectInstance));
}
let inspectComponentHighLighterSelectFn = null;
function cancelInspectComponentHighLighter() {
  unhighlight();
  window.removeEventListener("mouseover", inspectFn);
  window.removeEventListener("click", inspectComponentHighLighterSelectFn, true);
  inspectComponentHighLighterSelectFn = null;
}
function inspectComponentHighLighter() {
  window.addEventListener("mouseover", inspectFn);
  return new Promise((resolve) => {
    function onSelect(e) {
      e.preventDefault();
      e.stopPropagation();
      selectComponentFn(e, (id) => {
        window.removeEventListener("click", onSelect, true);
        inspectComponentHighLighterSelectFn = null;
        window.removeEventListener("mouseover", inspectFn);
        const el = getContainerElement();
        if (el) el.style.display = "none";
        resolve(JSON.stringify({ id }));
      });
    }
    inspectComponentHighLighterSelectFn = onSelect;
    window.addEventListener("click", onSelect, true);
  });
}
function scrollToComponent(options) {
  const instance = getComponentInstance(activeAppRecord.value, options.id);
  if (instance) {
    const [el] = getRootElementsFromComponentInstance(instance);
    if (typeof el.scrollIntoView === "function") el.scrollIntoView({ behavior: "smooth" });
    else {
      const bounds = getComponentBoundingRect(instance);
      const scrollTarget = document.createElement("div");
      const styles = {
        ...getStyles(bounds),
        position: "absolute"
      };
      Object.assign(scrollTarget.style, styles);
      document.body.appendChild(scrollTarget);
      scrollTarget.scrollIntoView({ behavior: "smooth" });
      setTimeout(() => {
        document.body.removeChild(scrollTarget);
      }, 2e3);
    }
    setTimeout(() => {
      const bounds = getComponentBoundingRect(instance);
      if (bounds.width || bounds.height) {
        const name = getInstanceName(instance);
        const el$1 = getContainerElement();
        el$1 ? update({
          ...options,
          name,
          bounds
        }) : create({
          ...options,
          name,
          bounds
        });
        setTimeout(() => {
          if (el$1) el$1.style.display = "none";
        }, 1500);
      }
    }, 1200);
  }
}
target.__VUE_DEVTOOLS_COMPONENT_INSPECTOR_ENABLED__ ??= true;
function waitForInspectorInit(cb) {
  let total = 0;
  const timer = setInterval(() => {
    if (target.__VUE_INSPECTOR__) {
      clearInterval(timer);
      total += 30;
      cb();
    }
    if (total >= 5e3) clearInterval(timer);
  }, 30);
}
function setupInspector() {
  const inspector = target.__VUE_INSPECTOR__;
  const _openInEditor = inspector.openInEditor;
  inspector.openInEditor = async (...params) => {
    inspector.disable();
    _openInEditor(...params);
  };
}
function getComponentInspector() {
  return new Promise((resolve) => {
    function setup() {
      setupInspector();
      resolve(target.__VUE_INSPECTOR__);
    }
    if (!target.__VUE_INSPECTOR__) waitForInspectorInit(() => {
      setup();
    });
    else setup();
  });
}
let ReactiveFlags = /* @__PURE__ */ (function(ReactiveFlags$1) {
  ReactiveFlags$1["SKIP"] = "__v_skip";
  ReactiveFlags$1["IS_REACTIVE"] = "__v_isReactive";
  ReactiveFlags$1["IS_READONLY"] = "__v_isReadonly";
  ReactiveFlags$1["IS_SHALLOW"] = "__v_isShallow";
  ReactiveFlags$1["RAW"] = "__v_raw";
  return ReactiveFlags$1;
})({});
function isReadonly(value) {
  return !!(value && value[ReactiveFlags.IS_READONLY]);
}
function isReactive$1(value) {
  if (isReadonly(value)) return isReactive$1(value[ReactiveFlags.RAW]);
  return !!(value && value[ReactiveFlags.IS_REACTIVE]);
}
function isRef$1(r) {
  return !!(r && r.__v_isRef === true);
}
function toRaw$1(observed) {
  const raw = observed && observed[ReactiveFlags.RAW];
  return raw ? toRaw$1(raw) : observed;
}
var StateEditor = class {
  constructor() {
    this.refEditor = new RefStateEditor();
  }
  set(object, path, value, cb) {
    const sections = Array.isArray(path) ? path : path.split(".");
    while (sections.length > 1) {
      const section = sections.shift();
      if (object instanceof Map) object = object.get(section);
      else if (object instanceof Set) object = Array.from(object.values())[section];
      else object = object[section];
      if (this.refEditor.isRef(object)) object = this.refEditor.get(object);
    }
    const field = sections[0];
    const item = this.refEditor.get(object)[field];
    if (cb) cb(object, field, value);
    else if (this.refEditor.isRef(item)) this.refEditor.set(item, value);
    else object[field] = value;
  }
  get(object, path) {
    const sections = Array.isArray(path) ? path : path.split(".");
    for (let i = 0; i < sections.length; i++) {
      if (object instanceof Map) object = object.get(sections[i]);
      else object = object[sections[i]];
      if (this.refEditor.isRef(object)) object = this.refEditor.get(object);
      if (!object) return void 0;
    }
    return object;
  }
  has(object, path, parent = false) {
    if (typeof object === "undefined") return false;
    const sections = Array.isArray(path) ? path.slice() : path.split(".");
    const size2 = !parent ? 1 : 2;
    while (object && sections.length > size2) {
      const section = sections.shift();
      object = object[section];
      if (this.refEditor.isRef(object)) object = this.refEditor.get(object);
    }
    return object != null && Object.prototype.hasOwnProperty.call(object, sections[0]);
  }
  createDefaultSetCallback(state) {
    return (object, field, value) => {
      if (state.remove || state.newKey) if (Array.isArray(object)) object.splice(field, 1);
      else if (toRaw$1(object) instanceof Map) object.delete(field);
      else if (toRaw$1(object) instanceof Set) object.delete(Array.from(object.values())[field]);
      else Reflect.deleteProperty(object, field);
      if (!state.remove) {
        const target$1 = object[state.newKey || field];
        if (this.refEditor.isRef(target$1)) this.refEditor.set(target$1, value);
        else if (toRaw$1(object) instanceof Map) object.set(state.newKey || field, value);
        else if (toRaw$1(object) instanceof Set) object.add(value);
        else object[state.newKey || field] = value;
      }
    };
  }
};
var RefStateEditor = class {
  set(ref2, value) {
    if (isRef$1(ref2)) ref2.value = value;
    else {
      if (ref2 instanceof Set && Array.isArray(value)) {
        ref2.clear();
        value.forEach((v) => ref2.add(v));
        return;
      }
      const currentKeys = Object.keys(value);
      if (ref2 instanceof Map) {
        const previousKeysSet$1 = new Set(ref2.keys());
        currentKeys.forEach((key) => {
          ref2.set(key, Reflect.get(value, key));
          previousKeysSet$1.delete(key);
        });
        previousKeysSet$1.forEach((key) => ref2.delete(key));
        return;
      }
      const previousKeysSet = new Set(Object.keys(ref2));
      currentKeys.forEach((key) => {
        Reflect.set(ref2, key, Reflect.get(value, key));
        previousKeysSet.delete(key);
      });
      previousKeysSet.forEach((key) => Reflect.deleteProperty(ref2, key));
    }
  }
  get(ref2) {
    return isRef$1(ref2) ? ref2.value : ref2;
  }
  isRef(ref2) {
    return isRef$1(ref2) || isReactive$1(ref2);
  }
};
const TIMELINE_LAYERS_STATE_STORAGE_ID = "__VUE_DEVTOOLS_KIT_TIMELINE_LAYERS_STATE__";
function getTimelineLayersStateFromStorage() {
  if (typeof window === "undefined" || !isBrowser || typeof localStorage === "undefined" || localStorage === null) return {
    recordingState: false,
    mouseEventEnabled: false,
    keyboardEventEnabled: false,
    componentEventEnabled: false,
    performanceEventEnabled: false,
    selected: ""
  };
  const state = typeof localStorage.getItem !== "undefined" ? localStorage.getItem(TIMELINE_LAYERS_STATE_STORAGE_ID) : null;
  return state ? JSON.parse(state) : {
    recordingState: false,
    mouseEventEnabled: false,
    keyboardEventEnabled: false,
    componentEventEnabled: false,
    performanceEventEnabled: false,
    selected: ""
  };
}
target.__VUE_DEVTOOLS_KIT_TIMELINE_LAYERS ??= [];
const devtoolsTimelineLayers = new Proxy(target.__VUE_DEVTOOLS_KIT_TIMELINE_LAYERS, { get(target$1, prop, receiver) {
  return Reflect.get(target$1, prop, receiver);
} });
function addTimelineLayer(options, descriptor) {
  devtoolsState.timelineLayersState[descriptor.id] = false;
  devtoolsTimelineLayers.push({
    ...options,
    descriptorId: descriptor.id,
    appRecord: getAppRecord(descriptor.app)
  });
}
target.__VUE_DEVTOOLS_KIT_INSPECTOR__ ??= [];
const devtoolsInspector = new Proxy(target.__VUE_DEVTOOLS_KIT_INSPECTOR__, { get(target$1, prop, receiver) {
  return Reflect.get(target$1, prop, receiver);
} });
const callInspectorUpdatedHook = debounce(() => {
  devtoolsContext.hooks.callHook(DevToolsMessagingHookKeys.SEND_INSPECTOR_TO_CLIENT, getActiveInspectors());
});
function addInspector(inspector, descriptor) {
  devtoolsInspector.push({
    options: inspector,
    descriptor,
    treeFilterPlaceholder: inspector.treeFilterPlaceholder ?? "Search tree...",
    stateFilterPlaceholder: inspector.stateFilterPlaceholder ?? "Search state...",
    treeFilter: "",
    selectedNodeId: "",
    appRecord: getAppRecord(descriptor.app)
  });
  callInspectorUpdatedHook();
}
function getActiveInspectors() {
  return devtoolsInspector.filter((inspector) => inspector.descriptor.app === activeAppRecord.value.app).filter((inspector) => inspector.descriptor.id !== "components").map((inspector) => {
    const descriptor = inspector.descriptor;
    const options = inspector.options;
    return {
      id: options.id,
      label: options.label,
      logo: descriptor.logo,
      icon: `custom-ic-baseline-${options?.icon?.replace(/_/g, "-")}`,
      packageName: descriptor.packageName,
      homepage: descriptor.homepage,
      pluginId: descriptor.id
    };
  });
}
function getInspector(id, app) {
  return devtoolsInspector.find((inspector) => inspector.options.id === id && (app ? inspector.descriptor.app === app : true));
}
let DevToolsV6PluginAPIHookKeys = /* @__PURE__ */ (function(DevToolsV6PluginAPIHookKeys$1) {
  DevToolsV6PluginAPIHookKeys$1["VISIT_COMPONENT_TREE"] = "visitComponentTree";
  DevToolsV6PluginAPIHookKeys$1["INSPECT_COMPONENT"] = "inspectComponent";
  DevToolsV6PluginAPIHookKeys$1["EDIT_COMPONENT_STATE"] = "editComponentState";
  DevToolsV6PluginAPIHookKeys$1["GET_INSPECTOR_TREE"] = "getInspectorTree";
  DevToolsV6PluginAPIHookKeys$1["GET_INSPECTOR_STATE"] = "getInspectorState";
  DevToolsV6PluginAPIHookKeys$1["EDIT_INSPECTOR_STATE"] = "editInspectorState";
  DevToolsV6PluginAPIHookKeys$1["INSPECT_TIMELINE_EVENT"] = "inspectTimelineEvent";
  DevToolsV6PluginAPIHookKeys$1["TIMELINE_CLEARED"] = "timelineCleared";
  DevToolsV6PluginAPIHookKeys$1["SET_PLUGIN_SETTINGS"] = "setPluginSettings";
  return DevToolsV6PluginAPIHookKeys$1;
})({});
let DevToolsContextHookKeys = /* @__PURE__ */ (function(DevToolsContextHookKeys$1) {
  DevToolsContextHookKeys$1["ADD_INSPECTOR"] = "addInspector";
  DevToolsContextHookKeys$1["SEND_INSPECTOR_TREE"] = "sendInspectorTree";
  DevToolsContextHookKeys$1["SEND_INSPECTOR_STATE"] = "sendInspectorState";
  DevToolsContextHookKeys$1["CUSTOM_INSPECTOR_SELECT_NODE"] = "customInspectorSelectNode";
  DevToolsContextHookKeys$1["TIMELINE_LAYER_ADDED"] = "timelineLayerAdded";
  DevToolsContextHookKeys$1["TIMELINE_EVENT_ADDED"] = "timelineEventAdded";
  DevToolsContextHookKeys$1["GET_COMPONENT_INSTANCES"] = "getComponentInstances";
  DevToolsContextHookKeys$1["GET_COMPONENT_BOUNDS"] = "getComponentBounds";
  DevToolsContextHookKeys$1["GET_COMPONENT_NAME"] = "getComponentName";
  DevToolsContextHookKeys$1["COMPONENT_HIGHLIGHT"] = "componentHighlight";
  DevToolsContextHookKeys$1["COMPONENT_UNHIGHLIGHT"] = "componentUnhighlight";
  return DevToolsContextHookKeys$1;
})({});
let DevToolsMessagingHookKeys = /* @__PURE__ */ (function(DevToolsMessagingHookKeys$1) {
  DevToolsMessagingHookKeys$1["SEND_INSPECTOR_TREE_TO_CLIENT"] = "sendInspectorTreeToClient";
  DevToolsMessagingHookKeys$1["SEND_INSPECTOR_STATE_TO_CLIENT"] = "sendInspectorStateToClient";
  DevToolsMessagingHookKeys$1["SEND_TIMELINE_EVENT_TO_CLIENT"] = "sendTimelineEventToClient";
  DevToolsMessagingHookKeys$1["SEND_INSPECTOR_TO_CLIENT"] = "sendInspectorToClient";
  DevToolsMessagingHookKeys$1["SEND_ACTIVE_APP_UNMOUNTED_TO_CLIENT"] = "sendActiveAppUpdatedToClient";
  DevToolsMessagingHookKeys$1["DEVTOOLS_STATE_UPDATED"] = "devtoolsStateUpdated";
  DevToolsMessagingHookKeys$1["DEVTOOLS_CONNECTED_UPDATED"] = "devtoolsConnectedUpdated";
  DevToolsMessagingHookKeys$1["ROUTER_INFO_UPDATED"] = "routerInfoUpdated";
  return DevToolsMessagingHookKeys$1;
})({});
function createDevToolsCtxHooks() {
  const hooks$1 = createHooks();
  hooks$1.hook(DevToolsContextHookKeys.ADD_INSPECTOR, ({ inspector, plugin }) => {
    addInspector(inspector, plugin.descriptor);
  });
  const debounceSendInspectorTree = debounce(async ({ inspectorId, plugin }) => {
    if (!inspectorId || !plugin?.descriptor?.app || devtoolsState.highPerfModeEnabled) return;
    const inspector = getInspector(inspectorId, plugin.descriptor.app);
    const _payload = {
      app: plugin.descriptor.app,
      inspectorId,
      filter: inspector?.treeFilter || "",
      rootNodes: []
    };
    await new Promise((resolve) => {
      hooks$1.callHookWith(async (callbacks) => {
        await Promise.all(callbacks.map((cb) => cb(_payload)));
        resolve();
      }, DevToolsV6PluginAPIHookKeys.GET_INSPECTOR_TREE);
    });
    hooks$1.callHookWith(async (callbacks) => {
      await Promise.all(callbacks.map((cb) => cb({
        inspectorId,
        rootNodes: _payload.rootNodes
      })));
    }, DevToolsMessagingHookKeys.SEND_INSPECTOR_TREE_TO_CLIENT);
  }, 120);
  hooks$1.hook(DevToolsContextHookKeys.SEND_INSPECTOR_TREE, debounceSendInspectorTree);
  const debounceSendInspectorState = debounce(async ({ inspectorId, plugin }) => {
    if (!inspectorId || !plugin?.descriptor?.app || devtoolsState.highPerfModeEnabled) return;
    const inspector = getInspector(inspectorId, plugin.descriptor.app);
    const _payload = {
      app: plugin.descriptor.app,
      inspectorId,
      nodeId: inspector?.selectedNodeId || "",
      state: null
    };
    const ctx = { currentTab: `custom-inspector:${inspectorId}` };
    if (_payload.nodeId) await new Promise((resolve) => {
      hooks$1.callHookWith(async (callbacks) => {
        await Promise.all(callbacks.map((cb) => cb(_payload, ctx)));
        resolve();
      }, DevToolsV6PluginAPIHookKeys.GET_INSPECTOR_STATE);
    });
    hooks$1.callHookWith(async (callbacks) => {
      await Promise.all(callbacks.map((cb) => cb({
        inspectorId,
        nodeId: _payload.nodeId,
        state: _payload.state
      })));
    }, DevToolsMessagingHookKeys.SEND_INSPECTOR_STATE_TO_CLIENT);
  }, 120);
  hooks$1.hook(DevToolsContextHookKeys.SEND_INSPECTOR_STATE, debounceSendInspectorState);
  hooks$1.hook(DevToolsContextHookKeys.CUSTOM_INSPECTOR_SELECT_NODE, ({ inspectorId, nodeId, plugin }) => {
    const inspector = getInspector(inspectorId, plugin.descriptor.app);
    if (!inspector) return;
    inspector.selectedNodeId = nodeId;
  });
  hooks$1.hook(DevToolsContextHookKeys.TIMELINE_LAYER_ADDED, ({ options, plugin }) => {
    addTimelineLayer(options, plugin.descriptor);
  });
  hooks$1.hook(DevToolsContextHookKeys.TIMELINE_EVENT_ADDED, ({ options, plugin }) => {
    if (devtoolsState.highPerfModeEnabled || !devtoolsState.timelineLayersState?.[plugin.descriptor.id] && ![
      "performance",
      "component-event",
      "keyboard",
      "mouse"
    ].includes(options.layerId)) return;
    hooks$1.callHookWith(async (callbacks) => {
      await Promise.all(callbacks.map((cb) => cb(options)));
    }, DevToolsMessagingHookKeys.SEND_TIMELINE_EVENT_TO_CLIENT);
  });
  hooks$1.hook(DevToolsContextHookKeys.GET_COMPONENT_INSTANCES, async ({ app }) => {
    const appRecord = app.__VUE_DEVTOOLS_NEXT_APP_RECORD__;
    if (!appRecord) return null;
    const appId = appRecord.id.toString();
    return [...appRecord.instanceMap].filter(([key]) => key.split(":")[0] === appId).map(([, instance]) => instance);
  });
  hooks$1.hook(DevToolsContextHookKeys.GET_COMPONENT_BOUNDS, async ({ instance }) => {
    return getComponentBoundingRect(instance);
  });
  hooks$1.hook(DevToolsContextHookKeys.GET_COMPONENT_NAME, ({ instance }) => {
    return getInstanceName(instance);
  });
  hooks$1.hook(DevToolsContextHookKeys.COMPONENT_HIGHLIGHT, ({ uid }) => {
    const instance = activeAppRecord.value.instanceMap.get(uid);
    if (instance) highlight(instance);
  });
  hooks$1.hook(DevToolsContextHookKeys.COMPONENT_UNHIGHLIGHT, () => {
    unhighlight();
  });
  return hooks$1;
}
target.__VUE_DEVTOOLS_KIT_APP_RECORDS__ ??= [];
target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD__ ??= {};
target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD_ID__ ??= "";
target.__VUE_DEVTOOLS_KIT_CUSTOM_TABS__ ??= [];
target.__VUE_DEVTOOLS_KIT_CUSTOM_COMMANDS__ ??= [];
const STATE_KEY = "__VUE_DEVTOOLS_KIT_GLOBAL_STATE__";
function initStateFactory() {
  return {
    connected: false,
    clientConnected: false,
    vitePluginDetected: true,
    appRecords: [],
    activeAppRecordId: "",
    tabs: [],
    commands: [],
    highPerfModeEnabled: true,
    devtoolsClientDetected: {},
    perfUniqueGroupId: 0,
    timelineLayersState: getTimelineLayersStateFromStorage()
  };
}
target[STATE_KEY] ??= initStateFactory();
const callStateUpdatedHook = debounce((state) => {
  devtoolsContext.hooks.callHook(DevToolsMessagingHookKeys.DEVTOOLS_STATE_UPDATED, { state });
});
debounce((state, oldState) => {
  devtoolsContext.hooks.callHook(DevToolsMessagingHookKeys.DEVTOOLS_CONNECTED_UPDATED, {
    state,
    oldState
  });
});
const devtoolsAppRecords = new Proxy(target.__VUE_DEVTOOLS_KIT_APP_RECORDS__, { get(_target, prop, receiver) {
  if (prop === "value") return target.__VUE_DEVTOOLS_KIT_APP_RECORDS__;
  return target.__VUE_DEVTOOLS_KIT_APP_RECORDS__[prop];
} });
const activeAppRecord = new Proxy(target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD__, { get(_target, prop, receiver) {
  if (prop === "value") return target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD__;
  else if (prop === "id") return target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD_ID__;
  return target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD__[prop];
} });
function updateAllStates() {
  callStateUpdatedHook({
    ...target[STATE_KEY],
    appRecords: devtoolsAppRecords.value,
    activeAppRecordId: activeAppRecord.id,
    tabs: target.__VUE_DEVTOOLS_KIT_CUSTOM_TABS__,
    commands: target.__VUE_DEVTOOLS_KIT_CUSTOM_COMMANDS__
  });
}
function setActiveAppRecord(app) {
  target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD__ = app;
  updateAllStates();
}
function setActiveAppRecordId(id) {
  target.__VUE_DEVTOOLS_KIT_ACTIVE_APP_RECORD_ID__ = id;
  updateAllStates();
}
const devtoolsState = new Proxy(target[STATE_KEY], {
  get(target$1, property) {
    if (property === "appRecords") return devtoolsAppRecords;
    else if (property === "activeAppRecordId") return activeAppRecord.id;
    else if (property === "tabs") return target.__VUE_DEVTOOLS_KIT_CUSTOM_TABS__;
    else if (property === "commands") return target.__VUE_DEVTOOLS_KIT_CUSTOM_COMMANDS__;
    return target[STATE_KEY][property];
  },
  deleteProperty(target$1, property) {
    delete target$1[property];
    return true;
  },
  set(target$1, property, value) {
    ({ ...target[STATE_KEY] });
    target$1[property] = value;
    target[STATE_KEY][property] = value;
    return true;
  }
});
function openInEditor(options = {}) {
  const { file, host, baseUrl = window.location.origin, line = 0, column = 0 } = options;
  if (file) {
    if (host === "chrome-extension") {
      const fileName = file.replace(/\\/g, "\\\\");
      const _baseUrl = window.VUE_DEVTOOLS_CONFIG?.openInEditorHost ?? "/";
      fetch(`${_baseUrl}__open-in-editor?file=${encodeURI(file)}`).then((response) => {
        if (!response.ok) {
          const msg = `Opening component ${fileName} failed`;
          console.log(`%c${msg}`, "color:red");
        }
      });
    } else if (devtoolsState.vitePluginDetected) {
      const _baseUrl = target.__VUE_DEVTOOLS_OPEN_IN_EDITOR_BASE_URL__ ?? baseUrl;
      target.__VUE_INSPECTOR__.openInEditor(_baseUrl, file, line, column);
    }
  }
}
target.__VUE_DEVTOOLS_KIT_PLUGIN_BUFFER__ ??= [];
const devtoolsPluginBuffer = new Proxy(target.__VUE_DEVTOOLS_KIT_PLUGIN_BUFFER__, { get(target$1, prop, receiver) {
  return Reflect.get(target$1, prop, receiver);
} });
function _getSettings(settings) {
  const _settings = {};
  Object.keys(settings).forEach((key) => {
    _settings[key] = settings[key].defaultValue;
  });
  return _settings;
}
function getPluginLocalKey(pluginId) {
  return `__VUE_DEVTOOLS_NEXT_PLUGIN_SETTINGS__${pluginId}__`;
}
function getPluginSettingsOptions(pluginId) {
  return (devtoolsPluginBuffer.find((item) => item[0].id === pluginId && !!item[0]?.settings)?.[0] ?? null)?.settings ?? null;
}
function getPluginSettings(pluginId, fallbackValue) {
  const localKey = getPluginLocalKey(pluginId);
  if (localKey) {
    const localSettings = localStorage.getItem(localKey);
    if (localSettings) return JSON.parse(localSettings);
  }
  if (pluginId) return _getSettings((devtoolsPluginBuffer.find((item) => item[0].id === pluginId)?.[0] ?? null)?.settings ?? {});
  return _getSettings(fallbackValue);
}
function initPluginSettings(pluginId, settings) {
  const localKey = getPluginLocalKey(pluginId);
  if (!localStorage.getItem(localKey)) localStorage.setItem(localKey, JSON.stringify(_getSettings(settings)));
}
function setPluginSettings(pluginId, key, value) {
  const localKey = getPluginLocalKey(pluginId);
  const localSettings = localStorage.getItem(localKey);
  const parsedLocalSettings = JSON.parse(localSettings || "{}");
  const updated = {
    ...parsedLocalSettings,
    [key]: value
  };
  localStorage.setItem(localKey, JSON.stringify(updated));
  devtoolsContext.hooks.callHookWith((callbacks) => {
    callbacks.forEach((cb) => cb({
      pluginId,
      key,
      oldValue: parsedLocalSettings[key],
      newValue: value,
      settings: updated
    }));
  }, DevToolsV6PluginAPIHookKeys.SET_PLUGIN_SETTINGS);
}
let DevToolsHooks = /* @__PURE__ */ (function(DevToolsHooks$1) {
  DevToolsHooks$1["APP_INIT"] = "app:init";
  DevToolsHooks$1["APP_UNMOUNT"] = "app:unmount";
  DevToolsHooks$1["COMPONENT_UPDATED"] = "component:updated";
  DevToolsHooks$1["COMPONENT_ADDED"] = "component:added";
  DevToolsHooks$1["COMPONENT_REMOVED"] = "component:removed";
  DevToolsHooks$1["COMPONENT_EMIT"] = "component:emit";
  DevToolsHooks$1["PERFORMANCE_START"] = "perf:start";
  DevToolsHooks$1["PERFORMANCE_END"] = "perf:end";
  DevToolsHooks$1["ADD_ROUTE"] = "router:add-route";
  DevToolsHooks$1["REMOVE_ROUTE"] = "router:remove-route";
  DevToolsHooks$1["RENDER_TRACKED"] = "render:tracked";
  DevToolsHooks$1["RENDER_TRIGGERED"] = "render:triggered";
  DevToolsHooks$1["APP_CONNECTED"] = "app:connected";
  DevToolsHooks$1["SETUP_DEVTOOLS_PLUGIN"] = "devtools-plugin:setup";
  return DevToolsHooks$1;
})({});
const devtoolsHooks = target.__VUE_DEVTOOLS_HOOK ??= createHooks();
const on = {
  vueAppInit(fn) {
    devtoolsHooks.hook(DevToolsHooks.APP_INIT, fn);
  },
  vueAppUnmount(fn) {
    devtoolsHooks.hook(DevToolsHooks.APP_UNMOUNT, fn);
  },
  vueAppConnected(fn) {
    devtoolsHooks.hook(DevToolsHooks.APP_CONNECTED, fn);
  },
  componentAdded(fn) {
    return devtoolsHooks.hook(DevToolsHooks.COMPONENT_ADDED, fn);
  },
  componentEmit(fn) {
    return devtoolsHooks.hook(DevToolsHooks.COMPONENT_EMIT, fn);
  },
  componentUpdated(fn) {
    return devtoolsHooks.hook(DevToolsHooks.COMPONENT_UPDATED, fn);
  },
  componentRemoved(fn) {
    return devtoolsHooks.hook(DevToolsHooks.COMPONENT_REMOVED, fn);
  },
  setupDevtoolsPlugin(fn) {
    devtoolsHooks.hook(DevToolsHooks.SETUP_DEVTOOLS_PLUGIN, fn);
  },
  perfStart(fn) {
    return devtoolsHooks.hook(DevToolsHooks.PERFORMANCE_START, fn);
  },
  perfEnd(fn) {
    return devtoolsHooks.hook(DevToolsHooks.PERFORMANCE_END, fn);
  }
};
const hook = {
  on,
  setupDevToolsPlugin(pluginDescriptor, setupFn) {
    return devtoolsHooks.callHook(DevToolsHooks.SETUP_DEVTOOLS_PLUGIN, pluginDescriptor, setupFn);
  }
};
var DevToolsV6PluginAPI = class {
  constructor({ plugin, ctx }) {
    this.hooks = ctx.hooks;
    this.plugin = plugin;
  }
  get on() {
    return {
      visitComponentTree: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.VISIT_COMPONENT_TREE, handler);
      },
      inspectComponent: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.INSPECT_COMPONENT, handler);
      },
      editComponentState: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.EDIT_COMPONENT_STATE, handler);
      },
      getInspectorTree: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.GET_INSPECTOR_TREE, handler);
      },
      getInspectorState: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.GET_INSPECTOR_STATE, handler);
      },
      editInspectorState: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.EDIT_INSPECTOR_STATE, handler);
      },
      inspectTimelineEvent: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.INSPECT_TIMELINE_EVENT, handler);
      },
      timelineCleared: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.TIMELINE_CLEARED, handler);
      },
      setPluginSettings: (handler) => {
        this.hooks.hook(DevToolsV6PluginAPIHookKeys.SET_PLUGIN_SETTINGS, handler);
      }
    };
  }
  notifyComponentUpdate(instance) {
    if (devtoolsState.highPerfModeEnabled) return;
    const inspector = getActiveInspectors().find((i) => i.packageName === this.plugin.descriptor.packageName);
    if (inspector?.id) {
      if (instance) {
        const args = [
          instance.appContext.app,
          instance.uid,
          instance.parent?.uid,
          instance
        ];
        devtoolsHooks.callHook(DevToolsHooks.COMPONENT_UPDATED, ...args);
      } else devtoolsHooks.callHook(DevToolsHooks.COMPONENT_UPDATED);
      this.hooks.callHook(DevToolsContextHookKeys.SEND_INSPECTOR_STATE, {
        inspectorId: inspector.id,
        plugin: this.plugin
      });
    }
  }
  addInspector(options) {
    this.hooks.callHook(DevToolsContextHookKeys.ADD_INSPECTOR, {
      inspector: options,
      plugin: this.plugin
    });
    if (this.plugin.descriptor.settings) initPluginSettings(options.id, this.plugin.descriptor.settings);
  }
  sendInspectorTree(inspectorId) {
    if (devtoolsState.highPerfModeEnabled) return;
    this.hooks.callHook(DevToolsContextHookKeys.SEND_INSPECTOR_TREE, {
      inspectorId,
      plugin: this.plugin
    });
  }
  sendInspectorState(inspectorId) {
    if (devtoolsState.highPerfModeEnabled) return;
    this.hooks.callHook(DevToolsContextHookKeys.SEND_INSPECTOR_STATE, {
      inspectorId,
      plugin: this.plugin
    });
  }
  selectInspectorNode(inspectorId, nodeId) {
    this.hooks.callHook(DevToolsContextHookKeys.CUSTOM_INSPECTOR_SELECT_NODE, {
      inspectorId,
      nodeId,
      plugin: this.plugin
    });
  }
  visitComponentTree(payload) {
    return this.hooks.callHook(DevToolsV6PluginAPIHookKeys.VISIT_COMPONENT_TREE, payload);
  }
  now() {
    if (devtoolsState.highPerfModeEnabled) return 0;
    return Date.now();
  }
  addTimelineLayer(options) {
    this.hooks.callHook(DevToolsContextHookKeys.TIMELINE_LAYER_ADDED, {
      options,
      plugin: this.plugin
    });
  }
  addTimelineEvent(options) {
    if (devtoolsState.highPerfModeEnabled) return;
    this.hooks.callHook(DevToolsContextHookKeys.TIMELINE_EVENT_ADDED, {
      options,
      plugin: this.plugin
    });
  }
  getSettings(pluginId) {
    return getPluginSettings(pluginId ?? this.plugin.descriptor.id, this.plugin.descriptor.settings);
  }
  getComponentInstances(app) {
    return this.hooks.callHook(DevToolsContextHookKeys.GET_COMPONENT_INSTANCES, { app });
  }
  getComponentBounds(instance) {
    return this.hooks.callHook(DevToolsContextHookKeys.GET_COMPONENT_BOUNDS, { instance });
  }
  getComponentName(instance) {
    return this.hooks.callHook(DevToolsContextHookKeys.GET_COMPONENT_NAME, { instance });
  }
  highlightElement(instance) {
    const uid = instance.__VUE_DEVTOOLS_NEXT_UID__;
    return this.hooks.callHook(DevToolsContextHookKeys.COMPONENT_HIGHLIGHT, { uid });
  }
  unhighlightElement() {
    return this.hooks.callHook(DevToolsContextHookKeys.COMPONENT_UNHIGHLIGHT);
  }
};
const DevToolsPluginAPI = DevToolsV6PluginAPI;
const UNDEFINED = "__vue_devtool_undefined__";
const INFINITY = "__vue_devtool_infinity__";
const NEGATIVE_INFINITY = "__vue_devtool_negative_infinity__";
const NAN = "__vue_devtool_nan__";
const tokenMap = {
  [UNDEFINED]: "undefined",
  [NAN]: "NaN",
  [INFINITY]: "Infinity",
  [NEGATIVE_INFINITY]: "-Infinity"
};
Object.entries(tokenMap).reduce((acc, [key, value]) => {
  acc[value] = key;
  return acc;
}, {});
target.__VUE_DEVTOOLS_KIT__REGISTERED_PLUGIN_APPS__ ??= /* @__PURE__ */ new Set();
function setupDevToolsPlugin(pluginDescriptor, setupFn) {
  return hook.setupDevToolsPlugin(pluginDescriptor, setupFn);
}
function callDevToolsPluginSetupFn(plugin, app) {
  const [pluginDescriptor, setupFn] = plugin;
  if (pluginDescriptor.app !== app) return;
  const api = new DevToolsPluginAPI({
    plugin: {
      setupFn,
      descriptor: pluginDescriptor
    },
    ctx: devtoolsContext
  });
  if (pluginDescriptor.packageName === "vuex") api.on.editInspectorState((payload) => {
    api.sendInspectorState(payload.inspectorId);
  });
  setupFn(api);
}
function registerDevToolsPlugin(app, options) {
  if (target.__VUE_DEVTOOLS_KIT__REGISTERED_PLUGIN_APPS__.has(app)) return;
  if (devtoolsState.highPerfModeEnabled && !options?.inspectingComponent) return;
  target.__VUE_DEVTOOLS_KIT__REGISTERED_PLUGIN_APPS__.add(app);
  devtoolsPluginBuffer.forEach((plugin) => {
    callDevToolsPluginSetupFn(plugin, app);
  });
}
const ROUTER_KEY = "__VUE_DEVTOOLS_ROUTER__";
const ROUTER_INFO_KEY = "__VUE_DEVTOOLS_ROUTER_INFO__";
target[ROUTER_INFO_KEY] ??= {
  currentRoute: null,
  routes: []
};
target[ROUTER_KEY] ??= {};
new Proxy(target[ROUTER_INFO_KEY], { get(target$1, property) {
  return target[ROUTER_INFO_KEY][property];
} });
new Proxy(target[ROUTER_KEY], { get(target$1, property) {
  if (property === "value") return target[ROUTER_KEY];
} });
function getRoutes(router) {
  const routesMap = /* @__PURE__ */ new Map();
  return (router?.getRoutes() || []).filter((i) => !routesMap.has(i.path) && routesMap.set(i.path, 1));
}
function filterRoutes(routes) {
  return routes.map((item) => {
    let { path, name, children, meta } = item;
    if (children?.length) children = filterRoutes(children);
    return {
      path,
      name,
      children,
      meta
    };
  });
}
function filterCurrentRoute(route) {
  if (route) {
    const { fullPath, hash, href, path, name, matched, params, query } = route;
    return {
      fullPath,
      hash,
      href,
      path,
      name,
      params,
      query,
      matched: filterRoutes(matched)
    };
  }
  return route;
}
function normalizeRouterInfo(appRecord, activeAppRecord$1) {
  function init() {
    const router = appRecord.app?.config.globalProperties.$router;
    const currentRoute = filterCurrentRoute(router?.currentRoute.value);
    const routes = filterRoutes(getRoutes(router));
    const c2 = console.warn;
    console.warn = () => {
    };
    target[ROUTER_INFO_KEY] = {
      currentRoute: currentRoute ? deepClone(currentRoute) : {},
      routes: deepClone(routes)
    };
    target[ROUTER_KEY] = router;
    console.warn = c2;
  }
  init();
  hook.on.componentUpdated(debounce(() => {
    if (activeAppRecord$1.value?.app !== appRecord.app) return;
    init();
    if (devtoolsState.highPerfModeEnabled) return;
    devtoolsContext.hooks.callHook(DevToolsMessagingHookKeys.ROUTER_INFO_UPDATED, { state: target[ROUTER_INFO_KEY] });
  }, 200));
}
function createDevToolsApi(hooks$1) {
  return {
    async getInspectorTree(payload) {
      const _payload = {
        ...payload,
        app: activeAppRecord.value.app,
        rootNodes: []
      };
      await new Promise((resolve) => {
        hooks$1.callHookWith(async (callbacks) => {
          await Promise.all(callbacks.map((cb) => cb(_payload)));
          resolve();
        }, DevToolsV6PluginAPIHookKeys.GET_INSPECTOR_TREE);
      });
      return _payload.rootNodes;
    },
    async getInspectorState(payload) {
      const _payload = {
        ...payload,
        app: activeAppRecord.value.app,
        state: null
      };
      const ctx = { currentTab: `custom-inspector:${payload.inspectorId}` };
      await new Promise((resolve) => {
        hooks$1.callHookWith(async (callbacks) => {
          await Promise.all(callbacks.map((cb) => cb(_payload, ctx)));
          resolve();
        }, DevToolsV6PluginAPIHookKeys.GET_INSPECTOR_STATE);
      });
      return _payload.state;
    },
    editInspectorState(payload) {
      const stateEditor$1 = new StateEditor();
      const _payload = {
        ...payload,
        app: activeAppRecord.value.app,
        set: (obj, path = payload.path, value = payload.state.value, cb) => {
          stateEditor$1.set(obj, path, value, cb || stateEditor$1.createDefaultSetCallback(payload.state));
        }
      };
      hooks$1.callHookWith((callbacks) => {
        callbacks.forEach((cb) => cb(_payload));
      }, DevToolsV6PluginAPIHookKeys.EDIT_INSPECTOR_STATE);
    },
    sendInspectorState(inspectorId) {
      const inspector = getInspector(inspectorId);
      hooks$1.callHook(DevToolsContextHookKeys.SEND_INSPECTOR_STATE, {
        inspectorId,
        plugin: {
          descriptor: inspector.descriptor,
          setupFn: () => ({})
        }
      });
    },
    inspectComponentInspector() {
      return inspectComponentHighLighter();
    },
    cancelInspectComponentInspector() {
      return cancelInspectComponentHighLighter();
    },
    getComponentRenderCode(id) {
      const instance = getComponentInstance(activeAppRecord.value, id);
      if (instance) return !(typeof instance?.type === "function") ? instance.render.toString() : instance.type.toString();
    },
    scrollToComponent(id) {
      return scrollToComponent({ id });
    },
    openInEditor,
    getVueInspector: getComponentInspector,
    toggleApp(id, options) {
      const appRecord = devtoolsAppRecords.value.find((record) => record.id === id);
      if (appRecord) {
        setActiveAppRecordId(id);
        setActiveAppRecord(appRecord);
        normalizeRouterInfo(appRecord, activeAppRecord);
        callInspectorUpdatedHook();
        registerDevToolsPlugin(appRecord.app, options);
      }
    },
    inspectDOM(instanceId) {
      const instance = getComponentInstance(activeAppRecord.value, instanceId);
      if (instance) {
        const [el] = getRootElementsFromComponentInstance(instance);
        if (el) target.__VUE_DEVTOOLS_INSPECT_DOM_TARGET__ = el;
      }
    },
    updatePluginSettings(pluginId, key, value) {
      setPluginSettings(pluginId, key, value);
    },
    getPluginSettings(pluginId) {
      return {
        options: getPluginSettingsOptions(pluginId),
        values: getPluginSettings(pluginId)
      };
    }
  };
}
target.__VUE_DEVTOOLS_ENV__ ??= { vitePluginDetected: false };
const hooks = createDevToolsCtxHooks();
target.__VUE_DEVTOOLS_KIT_CONTEXT__ ??= {
  hooks,
  get state() {
    return {
      ...devtoolsState,
      activeAppRecordId: activeAppRecord.id,
      activeAppRecord: activeAppRecord.value,
      appRecords: devtoolsAppRecords.value
    };
  },
  api: createDevToolsApi(hooks)
};
const devtoolsContext = target.__VUE_DEVTOOLS_KIT_CONTEXT__;
var require_speakingurl$1 = /* @__PURE__ */ __commonJS({ "../../node_modules/.pnpm/speakingurl@14.0.1/node_modules/speakingurl/lib/speakingurl.js": ((exports, module) => {
  (function(root) {
    var charMap = {
      "À": "A",
      "Á": "A",
      "Â": "A",
      "Ã": "A",
      "Ä": "Ae",
      "Å": "A",
      "Æ": "AE",
      "Ç": "C",
      "È": "E",
      "É": "E",
      "Ê": "E",
      "Ë": "E",
      "Ì": "I",
      "Í": "I",
      "Î": "I",
      "Ï": "I",
      "Ð": "D",
      "Ñ": "N",
      "Ò": "O",
      "Ó": "O",
      "Ô": "O",
      "Õ": "O",
      "Ö": "Oe",
      "Ő": "O",
      "Ø": "O",
      "Ù": "U",
      "Ú": "U",
      "Û": "U",
      "Ü": "Ue",
      "Ű": "U",
      "Ý": "Y",
      "Þ": "TH",
      "ß": "ss",
      "à": "a",
      "á": "a",
      "â": "a",
      "ã": "a",
      "ä": "ae",
      "å": "a",
      "æ": "ae",
      "ç": "c",
      "è": "e",
      "é": "e",
      "ê": "e",
      "ë": "e",
      "ì": "i",
      "í": "i",
      "î": "i",
      "ï": "i",
      "ð": "d",
      "ñ": "n",
      "ò": "o",
      "ó": "o",
      "ô": "o",
      "õ": "o",
      "ö": "oe",
      "ő": "o",
      "ø": "o",
      "ù": "u",
      "ú": "u",
      "û": "u",
      "ü": "ue",
      "ű": "u",
      "ý": "y",
      "þ": "th",
      "ÿ": "y",
      "ẞ": "SS",
      "ا": "a",
      "أ": "a",
      "إ": "i",
      "آ": "aa",
      "ؤ": "u",
      "ئ": "e",
      "ء": "a",
      "ب": "b",
      "ت": "t",
      "ث": "th",
      "ج": "j",
      "ح": "h",
      "خ": "kh",
      "د": "d",
      "ذ": "th",
      "ر": "r",
      "ز": "z",
      "س": "s",
      "ش": "sh",
      "ص": "s",
      "ض": "dh",
      "ط": "t",
      "ظ": "z",
      "ع": "a",
      "غ": "gh",
      "ف": "f",
      "ق": "q",
      "ك": "k",
      "ل": "l",
      "م": "m",
      "ن": "n",
      "ه": "h",
      "و": "w",
      "ي": "y",
      "ى": "a",
      "ة": "h",
      "ﻻ": "la",
      "ﻷ": "laa",
      "ﻹ": "lai",
      "ﻵ": "laa",
      "گ": "g",
      "چ": "ch",
      "پ": "p",
      "ژ": "zh",
      "ک": "k",
      "ی": "y",
      "َ": "a",
      "ً": "an",
      "ِ": "e",
      "ٍ": "en",
      "ُ": "u",
      "ٌ": "on",
      "ْ": "",
      "٠": "0",
      "١": "1",
      "٢": "2",
      "٣": "3",
      "٤": "4",
      "٥": "5",
      "٦": "6",
      "٧": "7",
      "٨": "8",
      "٩": "9",
      "۰": "0",
      "۱": "1",
      "۲": "2",
      "۳": "3",
      "۴": "4",
      "۵": "5",
      "۶": "6",
      "۷": "7",
      "۸": "8",
      "۹": "9",
      "က": "k",
      "ခ": "kh",
      "ဂ": "g",
      "ဃ": "ga",
      "င": "ng",
      "စ": "s",
      "ဆ": "sa",
      "ဇ": "z",
      "စျ": "za",
      "ည": "ny",
      "ဋ": "t",
      "ဌ": "ta",
      "ဍ": "d",
      "ဎ": "da",
      "ဏ": "na",
      "တ": "t",
      "ထ": "ta",
      "ဒ": "d",
      "ဓ": "da",
      "န": "n",
      "ပ": "p",
      "ဖ": "pa",
      "ဗ": "b",
      "ဘ": "ba",
      "မ": "m",
      "ယ": "y",
      "ရ": "ya",
      "လ": "l",
      "ဝ": "w",
      "သ": "th",
      "ဟ": "h",
      "ဠ": "la",
      "အ": "a",
      "ြ": "y",
      "ျ": "ya",
      "ွ": "w",
      "ြွ": "yw",
      "ျွ": "ywa",
      "ှ": "h",
      "ဧ": "e",
      "၏": "-e",
      "ဣ": "i",
      "ဤ": "-i",
      "ဉ": "u",
      "ဦ": "-u",
      "ဩ": "aw",
      "သြော": "aw",
      "ဪ": "aw",
      "၀": "0",
      "၁": "1",
      "၂": "2",
      "၃": "3",
      "၄": "4",
      "၅": "5",
      "၆": "6",
      "၇": "7",
      "၈": "8",
      "၉": "9",
      "္": "",
      "့": "",
      "း": "",
      "č": "c",
      "ď": "d",
      "ě": "e",
      "ň": "n",
      "ř": "r",
      "š": "s",
      "ť": "t",
      "ů": "u",
      "ž": "z",
      "Č": "C",
      "Ď": "D",
      "Ě": "E",
      "Ň": "N",
      "Ř": "R",
      "Š": "S",
      "Ť": "T",
      "Ů": "U",
      "Ž": "Z",
      "ހ": "h",
      "ށ": "sh",
      "ނ": "n",
      "ރ": "r",
      "ބ": "b",
      "ޅ": "lh",
      "ކ": "k",
      "އ": "a",
      "ވ": "v",
      "މ": "m",
      "ފ": "f",
      "ދ": "dh",
      "ތ": "th",
      "ލ": "l",
      "ގ": "g",
      "ޏ": "gn",
      "ސ": "s",
      "ޑ": "d",
      "ޒ": "z",
      "ޓ": "t",
      "ޔ": "y",
      "ޕ": "p",
      "ޖ": "j",
      "ޗ": "ch",
      "ޘ": "tt",
      "ޙ": "hh",
      "ޚ": "kh",
      "ޛ": "th",
      "ޜ": "z",
      "ޝ": "sh",
      "ޞ": "s",
      "ޟ": "d",
      "ޠ": "t",
      "ޡ": "z",
      "ޢ": "a",
      "ޣ": "gh",
      "ޤ": "q",
      "ޥ": "w",
      "ަ": "a",
      "ާ": "aa",
      "ި": "i",
      "ީ": "ee",
      "ު": "u",
      "ޫ": "oo",
      "ެ": "e",
      "ޭ": "ey",
      "ޮ": "o",
      "ޯ": "oa",
      "ް": "",
      "ა": "a",
      "ბ": "b",
      "გ": "g",
      "დ": "d",
      "ე": "e",
      "ვ": "v",
      "ზ": "z",
      "თ": "t",
      "ი": "i",
      "კ": "k",
      "ლ": "l",
      "მ": "m",
      "ნ": "n",
      "ო": "o",
      "პ": "p",
      "ჟ": "zh",
      "რ": "r",
      "ს": "s",
      "ტ": "t",
      "უ": "u",
      "ფ": "p",
      "ქ": "k",
      "ღ": "gh",
      "ყ": "q",
      "შ": "sh",
      "ჩ": "ch",
      "ც": "ts",
      "ძ": "dz",
      "წ": "ts",
      "ჭ": "ch",
      "ხ": "kh",
      "ჯ": "j",
      "ჰ": "h",
      "α": "a",
      "β": "v",
      "γ": "g",
      "δ": "d",
      "ε": "e",
      "ζ": "z",
      "η": "i",
      "θ": "th",
      "ι": "i",
      "κ": "k",
      "λ": "l",
      "μ": "m",
      "ν": "n",
      "ξ": "ks",
      "ο": "o",
      "π": "p",
      "ρ": "r",
      "σ": "s",
      "τ": "t",
      "υ": "y",
      "φ": "f",
      "χ": "x",
      "ψ": "ps",
      "ω": "o",
      "ά": "a",
      "έ": "e",
      "ί": "i",
      "ό": "o",
      "ύ": "y",
      "ή": "i",
      "ώ": "o",
      "ς": "s",
      "ϊ": "i",
      "ΰ": "y",
      "ϋ": "y",
      "ΐ": "i",
      "Α": "A",
      "Β": "B",
      "Γ": "G",
      "Δ": "D",
      "Ε": "E",
      "Ζ": "Z",
      "Η": "I",
      "Θ": "TH",
      "Ι": "I",
      "Κ": "K",
      "Λ": "L",
      "Μ": "M",
      "Ν": "N",
      "Ξ": "KS",
      "Ο": "O",
      "Π": "P",
      "Ρ": "R",
      "Σ": "S",
      "Τ": "T",
      "Υ": "Y",
      "Φ": "F",
      "Χ": "X",
      "Ψ": "PS",
      "Ω": "O",
      "Ά": "A",
      "Έ": "E",
      "Ί": "I",
      "Ό": "O",
      "Ύ": "Y",
      "Ή": "I",
      "Ώ": "O",
      "Ϊ": "I",
      "Ϋ": "Y",
      "ā": "a",
      "ē": "e",
      "ģ": "g",
      "ī": "i",
      "ķ": "k",
      "ļ": "l",
      "ņ": "n",
      "ū": "u",
      "Ā": "A",
      "Ē": "E",
      "Ģ": "G",
      "Ī": "I",
      "Ķ": "k",
      "Ļ": "L",
      "Ņ": "N",
      "Ū": "U",
      "Ќ": "Kj",
      "ќ": "kj",
      "Љ": "Lj",
      "љ": "lj",
      "Њ": "Nj",
      "њ": "nj",
      "Тс": "Ts",
      "тс": "ts",
      "ą": "a",
      "ć": "c",
      "ę": "e",
      "ł": "l",
      "ń": "n",
      "ś": "s",
      "ź": "z",
      "ż": "z",
      "Ą": "A",
      "Ć": "C",
      "Ę": "E",
      "Ł": "L",
      "Ń": "N",
      "Ś": "S",
      "Ź": "Z",
      "Ż": "Z",
      "Є": "Ye",
      "І": "I",
      "Ї": "Yi",
      "Ґ": "G",
      "є": "ye",
      "і": "i",
      "ї": "yi",
      "ґ": "g",
      "ă": "a",
      "Ă": "A",
      "ș": "s",
      "Ș": "S",
      "ț": "t",
      "Ț": "T",
      "ţ": "t",
      "Ţ": "T",
      "а": "a",
      "б": "b",
      "в": "v",
      "г": "g",
      "д": "d",
      "е": "e",
      "ё": "yo",
      "ж": "zh",
      "з": "z",
      "и": "i",
      "й": "i",
      "к": "k",
      "л": "l",
      "м": "m",
      "н": "n",
      "о": "o",
      "п": "p",
      "р": "r",
      "с": "s",
      "т": "t",
      "у": "u",
      "ф": "f",
      "х": "kh",
      "ц": "c",
      "ч": "ch",
      "ш": "sh",
      "щ": "sh",
      "ъ": "",
      "ы": "y",
      "ь": "",
      "э": "e",
      "ю": "yu",
      "я": "ya",
      "А": "A",
      "Б": "B",
      "В": "V",
      "Г": "G",
      "Д": "D",
      "Е": "E",
      "Ё": "Yo",
      "Ж": "Zh",
      "З": "Z",
      "И": "I",
      "Й": "I",
      "К": "K",
      "Л": "L",
      "М": "M",
      "Н": "N",
      "О": "O",
      "П": "P",
      "Р": "R",
      "С": "S",
      "Т": "T",
      "У": "U",
      "Ф": "F",
      "Х": "Kh",
      "Ц": "C",
      "Ч": "Ch",
      "Ш": "Sh",
      "Щ": "Sh",
      "Ъ": "",
      "Ы": "Y",
      "Ь": "",
      "Э": "E",
      "Ю": "Yu",
      "Я": "Ya",
      "ђ": "dj",
      "ј": "j",
      "ћ": "c",
      "џ": "dz",
      "Ђ": "Dj",
      "Ј": "j",
      "Ћ": "C",
      "Џ": "Dz",
      "ľ": "l",
      "ĺ": "l",
      "ŕ": "r",
      "Ľ": "L",
      "Ĺ": "L",
      "Ŕ": "R",
      "ş": "s",
      "Ş": "S",
      "ı": "i",
      "İ": "I",
      "ğ": "g",
      "Ğ": "G",
      "ả": "a",
      "Ả": "A",
      "ẳ": "a",
      "Ẳ": "A",
      "ẩ": "a",
      "Ẩ": "A",
      "đ": "d",
      "Đ": "D",
      "ẹ": "e",
      "Ẹ": "E",
      "ẽ": "e",
      "Ẽ": "E",
      "ẻ": "e",
      "Ẻ": "E",
      "ế": "e",
      "Ế": "E",
      "ề": "e",
      "Ề": "E",
      "ệ": "e",
      "Ệ": "E",
      "ễ": "e",
      "Ễ": "E",
      "ể": "e",
      "Ể": "E",
      "ỏ": "o",
      "ọ": "o",
      "Ọ": "o",
      "ố": "o",
      "Ố": "O",
      "ồ": "o",
      "Ồ": "O",
      "ổ": "o",
      "Ổ": "O",
      "ộ": "o",
      "Ộ": "O",
      "ỗ": "o",
      "Ỗ": "O",
      "ơ": "o",
      "Ơ": "O",
      "ớ": "o",
      "Ớ": "O",
      "ờ": "o",
      "Ờ": "O",
      "ợ": "o",
      "Ợ": "O",
      "ỡ": "o",
      "Ỡ": "O",
      "Ở": "o",
      "ở": "o",
      "ị": "i",
      "Ị": "I",
      "ĩ": "i",
      "Ĩ": "I",
      "ỉ": "i",
      "Ỉ": "i",
      "ủ": "u",
      "Ủ": "U",
      "ụ": "u",
      "Ụ": "U",
      "ũ": "u",
      "Ũ": "U",
      "ư": "u",
      "Ư": "U",
      "ứ": "u",
      "Ứ": "U",
      "ừ": "u",
      "Ừ": "U",
      "ự": "u",
      "Ự": "U",
      "ữ": "u",
      "Ữ": "U",
      "ử": "u",
      "Ử": "ư",
      "ỷ": "y",
      "Ỷ": "y",
      "ỳ": "y",
      "Ỳ": "Y",
      "ỵ": "y",
      "Ỵ": "Y",
      "ỹ": "y",
      "Ỹ": "Y",
      "ạ": "a",
      "Ạ": "A",
      "ấ": "a",
      "Ấ": "A",
      "ầ": "a",
      "Ầ": "A",
      "ậ": "a",
      "Ậ": "A",
      "ẫ": "a",
      "Ẫ": "A",
      "ắ": "a",
      "Ắ": "A",
      "ằ": "a",
      "Ằ": "A",
      "ặ": "a",
      "Ặ": "A",
      "ẵ": "a",
      "Ẵ": "A",
      "⓪": "0",
      "①": "1",
      "②": "2",
      "③": "3",
      "④": "4",
      "⑤": "5",
      "⑥": "6",
      "⑦": "7",
      "⑧": "8",
      "⑨": "9",
      "⑩": "10",
      "⑪": "11",
      "⑫": "12",
      "⑬": "13",
      "⑭": "14",
      "⑮": "15",
      "⑯": "16",
      "⑰": "17",
      "⑱": "18",
      "⑲": "18",
      "⑳": "18",
      "⓵": "1",
      "⓶": "2",
      "⓷": "3",
      "⓸": "4",
      "⓹": "5",
      "⓺": "6",
      "⓻": "7",
      "⓼": "8",
      "⓽": "9",
      "⓾": "10",
      "⓿": "0",
      "⓫": "11",
      "⓬": "12",
      "⓭": "13",
      "⓮": "14",
      "⓯": "15",
      "⓰": "16",
      "⓱": "17",
      "⓲": "18",
      "⓳": "19",
      "⓴": "20",
      "Ⓐ": "A",
      "Ⓑ": "B",
      "Ⓒ": "C",
      "Ⓓ": "D",
      "Ⓔ": "E",
      "Ⓕ": "F",
      "Ⓖ": "G",
      "Ⓗ": "H",
      "Ⓘ": "I",
      "Ⓙ": "J",
      "Ⓚ": "K",
      "Ⓛ": "L",
      "Ⓜ": "M",
      "Ⓝ": "N",
      "Ⓞ": "O",
      "Ⓟ": "P",
      "Ⓠ": "Q",
      "Ⓡ": "R",
      "Ⓢ": "S",
      "Ⓣ": "T",
      "Ⓤ": "U",
      "Ⓥ": "V",
      "Ⓦ": "W",
      "Ⓧ": "X",
      "Ⓨ": "Y",
      "Ⓩ": "Z",
      "ⓐ": "a",
      "ⓑ": "b",
      "ⓒ": "c",
      "ⓓ": "d",
      "ⓔ": "e",
      "ⓕ": "f",
      "ⓖ": "g",
      "ⓗ": "h",
      "ⓘ": "i",
      "ⓙ": "j",
      "ⓚ": "k",
      "ⓛ": "l",
      "ⓜ": "m",
      "ⓝ": "n",
      "ⓞ": "o",
      "ⓟ": "p",
      "ⓠ": "q",
      "ⓡ": "r",
      "ⓢ": "s",
      "ⓣ": "t",
      "ⓤ": "u",
      "ⓦ": "v",
      "ⓥ": "w",
      "ⓧ": "x",
      "ⓨ": "y",
      "ⓩ": "z",
      "“": '"',
      "”": '"',
      "‘": "'",
      "’": "'",
      "∂": "d",
      "ƒ": "f",
      "™": "(TM)",
      "©": "(C)",
      "œ": "oe",
      "Œ": "OE",
      "®": "(R)",
      "†": "+",
      "℠": "(SM)",
      "…": "...",
      "˚": "o",
      "º": "o",
      "ª": "a",
      "•": "*",
      "၊": ",",
      "။": ".",
      "$": "USD",
      "€": "EUR",
      "₢": "BRN",
      "₣": "FRF",
      "£": "GBP",
      "₤": "ITL",
      "₦": "NGN",
      "₧": "ESP",
      "₩": "KRW",
      "₪": "ILS",
      "₫": "VND",
      "₭": "LAK",
      "₮": "MNT",
      "₯": "GRD",
      "₱": "ARS",
      "₲": "PYG",
      "₳": "ARA",
      "₴": "UAH",
      "₵": "GHS",
      "¢": "cent",
      "¥": "CNY",
      "元": "CNY",
      "円": "YEN",
      "﷼": "IRR",
      "₠": "EWE",
      "฿": "THB",
      "₨": "INR",
      "₹": "INR",
      "₰": "PF",
      "₺": "TRY",
      "؋": "AFN",
      "₼": "AZN",
      "лв": "BGN",
      "៛": "KHR",
      "₡": "CRC",
      "₸": "KZT",
      "ден": "MKD",
      "zł": "PLN",
      "₽": "RUB",
      "₾": "GEL"
    };
    var lookAheadCharArray = ["်", "ް"];
    var diatricMap = {
      "ာ": "a",
      "ါ": "a",
      "ေ": "e",
      "ဲ": "e",
      "ိ": "i",
      "ီ": "i",
      "ို": "o",
      "ု": "u",
      "ူ": "u",
      "ေါင်": "aung",
      "ော": "aw",
      "ော်": "aw",
      "ေါ": "aw",
      "ေါ်": "aw",
      "်": "်",
      "က်": "et",
      "ိုက်": "aik",
      "ောက်": "auk",
      "င်": "in",
      "ိုင်": "aing",
      "ောင်": "aung",
      "စ်": "it",
      "ည်": "i",
      "တ်": "at",
      "ိတ်": "eik",
      "ုတ်": "ok",
      "ွတ်": "ut",
      "ေတ်": "it",
      "ဒ်": "d",
      "ိုဒ်": "ok",
      "ုဒ်": "ait",
      "န်": "an",
      "ာန်": "an",
      "ိန်": "ein",
      "ုန်": "on",
      "ွန်": "un",
      "ပ်": "at",
      "ိပ်": "eik",
      "ုပ်": "ok",
      "ွပ်": "ut",
      "န်ုပ်": "nub",
      "မ်": "an",
      "ိမ်": "ein",
      "ုမ်": "on",
      "ွမ်": "un",
      "ယ်": "e",
      "ိုလ်": "ol",
      "ဉ်": "in",
      "ံ": "an",
      "ိံ": "ein",
      "ုံ": "on",
      "ައް": "ah",
      "ަށް": "ah"
    };
    var langCharMap = {
      "en": {},
      "az": {
        "ç": "c",
        "ə": "e",
        "ğ": "g",
        "ı": "i",
        "ö": "o",
        "ş": "s",
        "ü": "u",
        "Ç": "C",
        "Ə": "E",
        "Ğ": "G",
        "İ": "I",
        "Ö": "O",
        "Ş": "S",
        "Ü": "U"
      },
      "cs": {
        "č": "c",
        "ď": "d",
        "ě": "e",
        "ň": "n",
        "ř": "r",
        "š": "s",
        "ť": "t",
        "ů": "u",
        "ž": "z",
        "Č": "C",
        "Ď": "D",
        "Ě": "E",
        "Ň": "N",
        "Ř": "R",
        "Š": "S",
        "Ť": "T",
        "Ů": "U",
        "Ž": "Z"
      },
      "fi": {
        "ä": "a",
        "Ä": "A",
        "ö": "o",
        "Ö": "O"
      },
      "hu": {
        "ä": "a",
        "Ä": "A",
        "ö": "o",
        "Ö": "O",
        "ü": "u",
        "Ü": "U",
        "ű": "u",
        "Ű": "U"
      },
      "lt": {
        "ą": "a",
        "č": "c",
        "ę": "e",
        "ė": "e",
        "į": "i",
        "š": "s",
        "ų": "u",
        "ū": "u",
        "ž": "z",
        "Ą": "A",
        "Č": "C",
        "Ę": "E",
        "Ė": "E",
        "Į": "I",
        "Š": "S",
        "Ų": "U",
        "Ū": "U"
      },
      "lv": {
        "ā": "a",
        "č": "c",
        "ē": "e",
        "ģ": "g",
        "ī": "i",
        "ķ": "k",
        "ļ": "l",
        "ņ": "n",
        "š": "s",
        "ū": "u",
        "ž": "z",
        "Ā": "A",
        "Č": "C",
        "Ē": "E",
        "Ģ": "G",
        "Ī": "i",
        "Ķ": "k",
        "Ļ": "L",
        "Ņ": "N",
        "Š": "S",
        "Ū": "u",
        "Ž": "Z"
      },
      "pl": {
        "ą": "a",
        "ć": "c",
        "ę": "e",
        "ł": "l",
        "ń": "n",
        "ó": "o",
        "ś": "s",
        "ź": "z",
        "ż": "z",
        "Ą": "A",
        "Ć": "C",
        "Ę": "e",
        "Ł": "L",
        "Ń": "N",
        "Ó": "O",
        "Ś": "S",
        "Ź": "Z",
        "Ż": "Z"
      },
      "sv": {
        "ä": "a",
        "Ä": "A",
        "ö": "o",
        "Ö": "O"
      },
      "sk": {
        "ä": "a",
        "Ä": "A"
      },
      "sr": {
        "љ": "lj",
        "њ": "nj",
        "Љ": "Lj",
        "Њ": "Nj",
        "đ": "dj",
        "Đ": "Dj"
      },
      "tr": {
        "Ü": "U",
        "Ö": "O",
        "ü": "u",
        "ö": "o"
      }
    };
    var symbolMap = {
      "ar": {
        "∆": "delta",
        "∞": "la-nihaya",
        "♥": "hob",
        "&": "wa",
        "|": "aw",
        "<": "aqal-men",
        ">": "akbar-men",
        "∑": "majmou",
        "¤": "omla"
      },
      "az": {},
      "ca": {
        "∆": "delta",
        "∞": "infinit",
        "♥": "amor",
        "&": "i",
        "|": "o",
        "<": "menys que",
        ">": "mes que",
        "∑": "suma dels",
        "¤": "moneda"
      },
      "cs": {
        "∆": "delta",
        "∞": "nekonecno",
        "♥": "laska",
        "&": "a",
        "|": "nebo",
        "<": "mensi nez",
        ">": "vetsi nez",
        "∑": "soucet",
        "¤": "mena"
      },
      "de": {
        "∆": "delta",
        "∞": "unendlich",
        "♥": "Liebe",
        "&": "und",
        "|": "oder",
        "<": "kleiner als",
        ">": "groesser als",
        "∑": "Summe von",
        "¤": "Waehrung"
      },
      "dv": {
        "∆": "delta",
        "∞": "kolunulaa",
        "♥": "loabi",
        "&": "aai",
        "|": "noonee",
        "<": "ah vure kuda",
        ">": "ah vure bodu",
        "∑": "jumula",
        "¤": "faisaa"
      },
      "en": {
        "∆": "delta",
        "∞": "infinity",
        "♥": "love",
        "&": "and",
        "|": "or",
        "<": "less than",
        ">": "greater than",
        "∑": "sum",
        "¤": "currency"
      },
      "es": {
        "∆": "delta",
        "∞": "infinito",
        "♥": "amor",
        "&": "y",
        "|": "u",
        "<": "menos que",
        ">": "mas que",
        "∑": "suma de los",
        "¤": "moneda"
      },
      "fa": {
        "∆": "delta",
        "∞": "bi-nahayat",
        "♥": "eshgh",
        "&": "va",
        "|": "ya",
        "<": "kamtar-az",
        ">": "bishtar-az",
        "∑": "majmooe",
        "¤": "vahed"
      },
      "fi": {
        "∆": "delta",
        "∞": "aarettomyys",
        "♥": "rakkaus",
        "&": "ja",
        "|": "tai",
        "<": "pienempi kuin",
        ">": "suurempi kuin",
        "∑": "summa",
        "¤": "valuutta"
      },
      "fr": {
        "∆": "delta",
        "∞": "infiniment",
        "♥": "Amour",
        "&": "et",
        "|": "ou",
        "<": "moins que",
        ">": "superieure a",
        "∑": "somme des",
        "¤": "monnaie"
      },
      "ge": {
        "∆": "delta",
        "∞": "usasruloba",
        "♥": "siqvaruli",
        "&": "da",
        "|": "an",
        "<": "naklebi",
        ">": "meti",
        "∑": "jami",
        "¤": "valuta"
      },
      "gr": {},
      "hu": {
        "∆": "delta",
        "∞": "vegtelen",
        "♥": "szerelem",
        "&": "es",
        "|": "vagy",
        "<": "kisebb mint",
        ">": "nagyobb mint",
        "∑": "szumma",
        "¤": "penznem"
      },
      "it": {
        "∆": "delta",
        "∞": "infinito",
        "♥": "amore",
        "&": "e",
        "|": "o",
        "<": "minore di",
        ">": "maggiore di",
        "∑": "somma",
        "¤": "moneta"
      },
      "lt": {
        "∆": "delta",
        "∞": "begalybe",
        "♥": "meile",
        "&": "ir",
        "|": "ar",
        "<": "maziau nei",
        ">": "daugiau nei",
        "∑": "suma",
        "¤": "valiuta"
      },
      "lv": {
        "∆": "delta",
        "∞": "bezgaliba",
        "♥": "milestiba",
        "&": "un",
        "|": "vai",
        "<": "mazak neka",
        ">": "lielaks neka",
        "∑": "summa",
        "¤": "valuta"
      },
      "my": {
        "∆": "kwahkhyaet",
        "∞": "asaonasme",
        "♥": "akhyait",
        "&": "nhin",
        "|": "tho",
        "<": "ngethaw",
        ">": "kyithaw",
        "∑": "paungld",
        "¤": "ngwekye"
      },
      "mk": {},
      "nl": {
        "∆": "delta",
        "∞": "oneindig",
        "♥": "liefde",
        "&": "en",
        "|": "of",
        "<": "kleiner dan",
        ">": "groter dan",
        "∑": "som",
        "¤": "valuta"
      },
      "pl": {
        "∆": "delta",
        "∞": "nieskonczonosc",
        "♥": "milosc",
        "&": "i",
        "|": "lub",
        "<": "mniejsze niz",
        ">": "wieksze niz",
        "∑": "suma",
        "¤": "waluta"
      },
      "pt": {
        "∆": "delta",
        "∞": "infinito",
        "♥": "amor",
        "&": "e",
        "|": "ou",
        "<": "menor que",
        ">": "maior que",
        "∑": "soma",
        "¤": "moeda"
      },
      "ro": {
        "∆": "delta",
        "∞": "infinit",
        "♥": "dragoste",
        "&": "si",
        "|": "sau",
        "<": "mai mic ca",
        ">": "mai mare ca",
        "∑": "suma",
        "¤": "valuta"
      },
      "ru": {
        "∆": "delta",
        "∞": "beskonechno",
        "♥": "lubov",
        "&": "i",
        "|": "ili",
        "<": "menshe",
        ">": "bolshe",
        "∑": "summa",
        "¤": "valjuta"
      },
      "sk": {
        "∆": "delta",
        "∞": "nekonecno",
        "♥": "laska",
        "&": "a",
        "|": "alebo",
        "<": "menej ako",
        ">": "viac ako",
        "∑": "sucet",
        "¤": "mena"
      },
      "sr": {},
      "tr": {
        "∆": "delta",
        "∞": "sonsuzluk",
        "♥": "ask",
        "&": "ve",
        "|": "veya",
        "<": "kucuktur",
        ">": "buyuktur",
        "∑": "toplam",
        "¤": "para birimi"
      },
      "uk": {
        "∆": "delta",
        "∞": "bezkinechnist",
        "♥": "lubov",
        "&": "i",
        "|": "abo",
        "<": "menshe",
        ">": "bilshe",
        "∑": "suma",
        "¤": "valjuta"
      },
      "vn": {
        "∆": "delta",
        "∞": "vo cuc",
        "♥": "yeu",
        "&": "va",
        "|": "hoac",
        "<": "nho hon",
        ">": "lon hon",
        "∑": "tong",
        "¤": "tien te"
      }
    };
    var uricChars = [
      ";",
      "?",
      ":",
      "@",
      "&",
      "=",
      "+",
      "$",
      ",",
      "/"
    ].join("");
    var uricNoSlashChars = [
      ";",
      "?",
      ":",
      "@",
      "&",
      "=",
      "+",
      "$",
      ","
    ].join("");
    var markChars = [
      ".",
      "!",
      "~",
      "*",
      "'",
      "(",
      ")"
    ].join("");
    var getSlug = function getSlug$1(input, opts) {
      var separator = "-";
      var result = "";
      var diatricString = "";
      var convertSymbols = true;
      var customReplacements = {};
      var maintainCase;
      var titleCase;
      var truncate;
      var uricFlag;
      var uricNoSlashFlag;
      var markFlag;
      var symbol;
      var langChar;
      var lucky;
      var i;
      var ch;
      var l;
      var lastCharWasSymbol;
      var lastCharWasDiatric;
      var allowedChars = "";
      if (typeof input !== "string") return "";
      if (typeof opts === "string") separator = opts;
      symbol = symbolMap.en;
      langChar = langCharMap.en;
      if (typeof opts === "object") {
        maintainCase = opts.maintainCase || false;
        customReplacements = opts.custom && typeof opts.custom === "object" ? opts.custom : customReplacements;
        truncate = +opts.truncate > 1 && opts.truncate || false;
        uricFlag = opts.uric || false;
        uricNoSlashFlag = opts.uricNoSlash || false;
        markFlag = opts.mark || false;
        convertSymbols = opts.symbols === false || opts.lang === false ? false : true;
        separator = opts.separator || separator;
        if (uricFlag) allowedChars += uricChars;
        if (uricNoSlashFlag) allowedChars += uricNoSlashChars;
        if (markFlag) allowedChars += markChars;
        symbol = opts.lang && symbolMap[opts.lang] && convertSymbols ? symbolMap[opts.lang] : convertSymbols ? symbolMap.en : {};
        langChar = opts.lang && langCharMap[opts.lang] ? langCharMap[opts.lang] : opts.lang === false || opts.lang === true ? {} : langCharMap.en;
        if (opts.titleCase && typeof opts.titleCase.length === "number" && Array.prototype.toString.call(opts.titleCase)) {
          opts.titleCase.forEach(function(v) {
            customReplacements[v + ""] = v + "";
          });
          titleCase = true;
        } else titleCase = !!opts.titleCase;
        if (opts.custom && typeof opts.custom.length === "number" && Array.prototype.toString.call(opts.custom)) opts.custom.forEach(function(v) {
          customReplacements[v + ""] = v + "";
        });
        Object.keys(customReplacements).forEach(function(v) {
          var r;
          if (v.length > 1) r = new RegExp("\\b" + escapeChars(v) + "\\b", "gi");
          else r = new RegExp(escapeChars(v), "gi");
          input = input.replace(r, customReplacements[v]);
        });
        for (ch in customReplacements) allowedChars += ch;
      }
      allowedChars += separator;
      allowedChars = escapeChars(allowedChars);
      input = input.replace(/(^\s+|\s+$)/g, "");
      lastCharWasSymbol = false;
      lastCharWasDiatric = false;
      for (i = 0, l = input.length; i < l; i++) {
        ch = input[i];
        if (isReplacedCustomChar(ch, customReplacements)) lastCharWasSymbol = false;
        else if (langChar[ch]) {
          ch = lastCharWasSymbol && langChar[ch].match(/[A-Za-z0-9]/) ? " " + langChar[ch] : langChar[ch];
          lastCharWasSymbol = false;
        } else if (ch in charMap) {
          if (i + 1 < l && lookAheadCharArray.indexOf(input[i + 1]) >= 0) {
            diatricString += ch;
            ch = "";
          } else if (lastCharWasDiatric === true) {
            ch = diatricMap[diatricString] + charMap[ch];
            diatricString = "";
          } else ch = lastCharWasSymbol && charMap[ch].match(/[A-Za-z0-9]/) ? " " + charMap[ch] : charMap[ch];
          lastCharWasSymbol = false;
          lastCharWasDiatric = false;
        } else if (ch in diatricMap) {
          diatricString += ch;
          ch = "";
          if (i === l - 1) ch = diatricMap[diatricString];
          lastCharWasDiatric = true;
        } else if (symbol[ch] && !(uricFlag && uricChars.indexOf(ch) !== -1) && !(uricNoSlashFlag && uricNoSlashChars.indexOf(ch) !== -1)) {
          ch = lastCharWasSymbol || result.substr(-1).match(/[A-Za-z0-9]/) ? separator + symbol[ch] : symbol[ch];
          ch += input[i + 1] !== void 0 && input[i + 1].match(/[A-Za-z0-9]/) ? separator : "";
          lastCharWasSymbol = true;
        } else {
          if (lastCharWasDiatric === true) {
            ch = diatricMap[diatricString] + ch;
            diatricString = "";
            lastCharWasDiatric = false;
          } else if (lastCharWasSymbol && (/[A-Za-z0-9]/.test(ch) || result.substr(-1).match(/A-Za-z0-9]/))) ch = " " + ch;
          lastCharWasSymbol = false;
        }
        result += ch.replace(new RegExp("[^\\w\\s" + allowedChars + "_-]", "g"), separator);
      }
      if (titleCase) result = result.replace(/(\w)(\S*)/g, function(_2, i$1, r) {
        var j = i$1.toUpperCase() + (r !== null ? r : "");
        return Object.keys(customReplacements).indexOf(j.toLowerCase()) < 0 ? j : j.toLowerCase();
      });
      result = result.replace(/\s+/g, separator).replace(new RegExp("\\" + separator + "+", "g"), separator).replace(new RegExp("(^\\" + separator + "+|\\" + separator + "+$)", "g"), "");
      if (truncate && result.length > truncate) {
        lucky = result.charAt(truncate) === separator;
        result = result.slice(0, truncate);
        if (!lucky) result = result.slice(0, result.lastIndexOf(separator));
      }
      if (!maintainCase && !titleCase) result = result.toLowerCase();
      return result;
    };
    var createSlug = function createSlug$1(opts) {
      return function getSlugWithConfig(input) {
        return getSlug(input, opts);
      };
    };
    var escapeChars = function escapeChars$1(input) {
      return input.replace(/[-\\^$*+?.()|[\]{}\/]/g, "\\$&");
    };
    var isReplacedCustomChar = function(ch, customReplacements) {
      for (var c2 in customReplacements) if (customReplacements[c2] === ch) return true;
    };
    if (typeof module !== "undefined" && module.exports) {
      module.exports = getSlug;
      module.exports.createSlug = createSlug;
    } else if (typeof define !== "undefined" && define.amd) define([], function() {
      return getSlug;
    });
    else try {
      if (root.getSlug || root.createSlug) throw "speakingurl: globals exists /(getSlug|createSlug)/";
      else {
        root.getSlug = getSlug;
        root.createSlug = createSlug;
      }
    } catch (e) {
    }
  })(exports);
}) });
var require_speakingurl = /* @__PURE__ */ __commonJS({ "../../node_modules/.pnpm/speakingurl@14.0.1/node_modules/speakingurl/index.js": ((exports, module) => {
  module.exports = require_speakingurl$1();
}) });
/* @__PURE__ */ __toESM(require_speakingurl());
target.__VUE_DEVTOOLS_NEXT_APP_RECORD_INFO__ ??= {
  id: 0,
  appIds: /* @__PURE__ */ new Set()
};
function toggleHighPerfMode(state) {
  devtoolsState.highPerfModeEnabled = state ?? !devtoolsState.highPerfModeEnabled;
  if (!state && activeAppRecord.value) registerDevToolsPlugin(activeAppRecord.value.app);
}
function updateDevToolsClientDetected(params) {
  devtoolsState.devtoolsClientDetected = {
    ...devtoolsState.devtoolsClientDetected,
    ...params
  };
  toggleHighPerfMode(!Object.values(devtoolsState.devtoolsClientDetected).some(Boolean));
}
target.__VUE_DEVTOOLS_UPDATE_CLIENT_DETECTED__ ??= updateDevToolsClientDetected;
var DoubleIndexedKV = class {
  constructor() {
    this.keyToValue = /* @__PURE__ */ new Map();
    this.valueToKey = /* @__PURE__ */ new Map();
  }
  set(key, value) {
    this.keyToValue.set(key, value);
    this.valueToKey.set(value, key);
  }
  getByKey(key) {
    return this.keyToValue.get(key);
  }
  getByValue(value) {
    return this.valueToKey.get(value);
  }
  clear() {
    this.keyToValue.clear();
    this.valueToKey.clear();
  }
};
var Registry = class {
  constructor(generateIdentifier) {
    this.generateIdentifier = generateIdentifier;
    this.kv = new DoubleIndexedKV();
  }
  register(value, identifier) {
    if (this.kv.getByValue(value)) return;
    if (!identifier) identifier = this.generateIdentifier(value);
    this.kv.set(identifier, value);
  }
  clear() {
    this.kv.clear();
  }
  getIdentifier(value) {
    return this.kv.getByValue(value);
  }
  getValue(identifier) {
    return this.kv.getByKey(identifier);
  }
};
var ClassRegistry = class extends Registry {
  constructor() {
    super((c2) => c2.name);
    this.classToAllowedProps = /* @__PURE__ */ new Map();
  }
  register(value, options) {
    if (typeof options === "object") {
      if (options.allowProps) this.classToAllowedProps.set(value, options.allowProps);
      super.register(value, options.identifier);
    } else super.register(value, options);
  }
  getAllowedProps(value) {
    return this.classToAllowedProps.get(value);
  }
};
function valuesOfObj(record) {
  if ("values" in Object) return Object.values(record);
  const values = [];
  for (const key in record) if (record.hasOwnProperty(key)) values.push(record[key]);
  return values;
}
function find(record, predicate) {
  const values = valuesOfObj(record);
  if ("find" in values) return values.find(predicate);
  const valuesNotNever = values;
  for (let i = 0; i < valuesNotNever.length; i++) {
    const value = valuesNotNever[i];
    if (predicate(value)) return value;
  }
}
function forEach$1(record, run) {
  Object.entries(record).forEach(([key, value]) => run(value, key));
}
function includes(arr, value) {
  return arr.indexOf(value) !== -1;
}
function findArr(record, predicate) {
  for (let i = 0; i < record.length; i++) {
    const value = record[i];
    if (predicate(value)) return value;
  }
}
var CustomTransformerRegistry = class {
  constructor() {
    this.transfomers = {};
  }
  register(transformer) {
    this.transfomers[transformer.name] = transformer;
  }
  findApplicable(v) {
    return find(this.transfomers, (transformer) => transformer.isApplicable(v));
  }
  findByName(name) {
    return this.transfomers[name];
  }
};
const getType$1 = (payload) => Object.prototype.toString.call(payload).slice(8, -1);
const isUndefined$1 = (payload) => typeof payload === "undefined";
const isNull$1 = (payload) => payload === null;
const isPlainObject$2 = (payload) => {
  if (typeof payload !== "object" || payload === null) return false;
  if (payload === Object.prototype) return false;
  if (Object.getPrototypeOf(payload) === null) return true;
  return Object.getPrototypeOf(payload) === Object.prototype;
};
const isEmptyObject$1 = (payload) => isPlainObject$2(payload) && Object.keys(payload).length === 0;
const isArray$2 = (payload) => Array.isArray(payload);
const isString$1 = (payload) => typeof payload === "string";
const isNumber$1 = (payload) => typeof payload === "number" && !isNaN(payload);
const isBoolean$1 = (payload) => typeof payload === "boolean";
const isRegExp$1 = (payload) => payload instanceof RegExp;
const isMap = (payload) => payload instanceof Map;
const isSet = (payload) => payload instanceof Set;
const isSymbol = (payload) => getType$1(payload) === "Symbol";
const isDate$1 = (payload) => payload instanceof Date && !isNaN(payload.valueOf());
const isError = (payload) => payload instanceof Error;
const isNaNValue = (payload) => typeof payload === "number" && isNaN(payload);
const isPrimitive = (payload) => isBoolean$1(payload) || isNull$1(payload) || isUndefined$1(payload) || isNumber$1(payload) || isString$1(payload) || isSymbol(payload);
const isBigint = (payload) => typeof payload === "bigint";
const isInfinite = (payload) => payload === Infinity || payload === -Infinity;
const isTypedArray$1 = (payload) => ArrayBuffer.isView(payload) && !(payload instanceof DataView);
const isURL = (payload) => payload instanceof URL;
const escapeKey = (key) => key.replace(/\./g, "\\.");
const stringifyPath = (path) => path.map(String).map(escapeKey).join(".");
const parsePath = (string) => {
  const result = [];
  let segment = "";
  for (let i = 0; i < string.length; i++) {
    let char = string.charAt(i);
    if (char === "\\" && string.charAt(i + 1) === ".") {
      segment += ".";
      i++;
      continue;
    }
    if (char === ".") {
      result.push(segment);
      segment = "";
      continue;
    }
    segment += char;
  }
  const lastSegment = segment;
  result.push(lastSegment);
  return result;
};
function simpleTransformation(isApplicable, annotation, transform, untransform) {
  return {
    isApplicable,
    annotation,
    transform,
    untransform
  };
}
const simpleRules = [
  simpleTransformation(isUndefined$1, "undefined", () => null, () => void 0),
  simpleTransformation(isBigint, "bigint", (v) => v.toString(), (v) => {
    if (typeof BigInt !== "undefined") return BigInt(v);
    console.error("Please add a BigInt polyfill.");
    return v;
  }),
  simpleTransformation(isDate$1, "Date", (v) => v.toISOString(), (v) => new Date(v)),
  simpleTransformation(isError, "Error", (v, superJson) => {
    const baseError = {
      name: v.name,
      message: v.message
    };
    superJson.allowedErrorProps.forEach((prop) => {
      baseError[prop] = v[prop];
    });
    return baseError;
  }, (v, superJson) => {
    const e = new Error(v.message);
    e.name = v.name;
    e.stack = v.stack;
    superJson.allowedErrorProps.forEach((prop) => {
      e[prop] = v[prop];
    });
    return e;
  }),
  simpleTransformation(isRegExp$1, "regexp", (v) => "" + v, (regex) => {
    const body = regex.slice(1, regex.lastIndexOf("/"));
    const flags = regex.slice(regex.lastIndexOf("/") + 1);
    return new RegExp(body, flags);
  }),
  simpleTransformation(isSet, "set", (v) => [...v.values()], (v) => new Set(v)),
  simpleTransformation(isMap, "map", (v) => [...v.entries()], (v) => new Map(v)),
  simpleTransformation((v) => isNaNValue(v) || isInfinite(v), "number", (v) => {
    if (isNaNValue(v)) return "NaN";
    if (v > 0) return "Infinity";
    else return "-Infinity";
  }, Number),
  simpleTransformation((v) => v === 0 && 1 / v === -Infinity, "number", () => {
    return "-0";
  }, Number),
  simpleTransformation(isURL, "URL", (v) => v.toString(), (v) => new URL(v))
];
function compositeTransformation(isApplicable, annotation, transform, untransform) {
  return {
    isApplicable,
    annotation,
    transform,
    untransform
  };
}
const symbolRule = compositeTransformation((s, superJson) => {
  if (isSymbol(s)) return !!superJson.symbolRegistry.getIdentifier(s);
  return false;
}, (s, superJson) => {
  return ["symbol", superJson.symbolRegistry.getIdentifier(s)];
}, (v) => v.description, (_2, a, superJson) => {
  const value = superJson.symbolRegistry.getValue(a[1]);
  if (!value) throw new Error("Trying to deserialize unknown symbol");
  return value;
});
const constructorToName = [
  Int8Array,
  Uint8Array,
  Int16Array,
  Uint16Array,
  Int32Array,
  Uint32Array,
  Float32Array,
  Float64Array,
  Uint8ClampedArray
].reduce((obj, ctor) => {
  obj[ctor.name] = ctor;
  return obj;
}, {});
const typedArrayRule = compositeTransformation(isTypedArray$1, (v) => ["typed-array", v.constructor.name], (v) => [...v], (v, a) => {
  const ctor = constructorToName[a[1]];
  if (!ctor) throw new Error("Trying to deserialize unknown typed array");
  return new ctor(v);
});
function isInstanceOfRegisteredClass(potentialClass, superJson) {
  if (potentialClass?.constructor) return !!superJson.classRegistry.getIdentifier(potentialClass.constructor);
  return false;
}
const classRule = compositeTransformation(isInstanceOfRegisteredClass, (clazz, superJson) => {
  return ["class", superJson.classRegistry.getIdentifier(clazz.constructor)];
}, (clazz, superJson) => {
  const allowedProps = superJson.classRegistry.getAllowedProps(clazz.constructor);
  if (!allowedProps) return { ...clazz };
  const result = {};
  allowedProps.forEach((prop) => {
    result[prop] = clazz[prop];
  });
  return result;
}, (v, a, superJson) => {
  const clazz = superJson.classRegistry.getValue(a[1]);
  if (!clazz) throw new Error(`Trying to deserialize unknown class '${a[1]}' - check https://github.com/blitz-js/superjson/issues/116#issuecomment-773996564`);
  return Object.assign(Object.create(clazz.prototype), v);
});
const customRule = compositeTransformation((value, superJson) => {
  return !!superJson.customTransformerRegistry.findApplicable(value);
}, (value, superJson) => {
  return ["custom", superJson.customTransformerRegistry.findApplicable(value).name];
}, (value, superJson) => {
  return superJson.customTransformerRegistry.findApplicable(value).serialize(value);
}, (v, a, superJson) => {
  const transformer = superJson.customTransformerRegistry.findByName(a[1]);
  if (!transformer) throw new Error("Trying to deserialize unknown custom value");
  return transformer.deserialize(v);
});
const compositeRules = [
  classRule,
  symbolRule,
  customRule,
  typedArrayRule
];
const transformValue = (value, superJson) => {
  const applicableCompositeRule = findArr(compositeRules, (rule) => rule.isApplicable(value, superJson));
  if (applicableCompositeRule) return {
    value: applicableCompositeRule.transform(value, superJson),
    type: applicableCompositeRule.annotation(value, superJson)
  };
  const applicableSimpleRule = findArr(simpleRules, (rule) => rule.isApplicable(value, superJson));
  if (applicableSimpleRule) return {
    value: applicableSimpleRule.transform(value, superJson),
    type: applicableSimpleRule.annotation
  };
};
const simpleRulesByAnnotation = {};
simpleRules.forEach((rule) => {
  simpleRulesByAnnotation[rule.annotation] = rule;
});
const untransformValue = (json, type, superJson) => {
  if (isArray$2(type)) switch (type[0]) {
    case "symbol":
      return symbolRule.untransform(json, type, superJson);
    case "class":
      return classRule.untransform(json, type, superJson);
    case "custom":
      return customRule.untransform(json, type, superJson);
    case "typed-array":
      return typedArrayRule.untransform(json, type, superJson);
    default:
      throw new Error("Unknown transformation: " + type);
  }
  else {
    const transformation = simpleRulesByAnnotation[type];
    if (!transformation) throw new Error("Unknown transformation: " + type);
    return transformation.untransform(json, superJson);
  }
};
const getNthKey = (value, n2) => {
  if (n2 > value.size) throw new Error("index out of bounds");
  const keys = value.keys();
  while (n2 > 0) {
    keys.next();
    n2--;
  }
  return keys.next().value;
};
function validatePath(path) {
  if (includes(path, "__proto__")) throw new Error("__proto__ is not allowed as a property");
  if (includes(path, "prototype")) throw new Error("prototype is not allowed as a property");
  if (includes(path, "constructor")) throw new Error("constructor is not allowed as a property");
}
const getDeep = (object, path) => {
  validatePath(path);
  for (let i = 0; i < path.length; i++) {
    const key = path[i];
    if (isSet(object)) object = getNthKey(object, +key);
    else if (isMap(object)) {
      const row = +key;
      const type = +path[++i] === 0 ? "key" : "value";
      const keyOfRow = getNthKey(object, row);
      switch (type) {
        case "key":
          object = keyOfRow;
          break;
        case "value":
          object = object.get(keyOfRow);
          break;
      }
    } else object = object[key];
  }
  return object;
};
const setDeep = (object, path, mapper) => {
  validatePath(path);
  if (path.length === 0) return mapper(object);
  let parent = object;
  for (let i = 0; i < path.length - 1; i++) {
    const key = path[i];
    if (isArray$2(parent)) {
      const index = +key;
      parent = parent[index];
    } else if (isPlainObject$2(parent)) parent = parent[key];
    else if (isSet(parent)) {
      const row = +key;
      parent = getNthKey(parent, row);
    } else if (isMap(parent)) {
      if (i === path.length - 2) break;
      const row = +key;
      const type = +path[++i] === 0 ? "key" : "value";
      const keyOfRow = getNthKey(parent, row);
      switch (type) {
        case "key":
          parent = keyOfRow;
          break;
        case "value":
          parent = parent.get(keyOfRow);
          break;
      }
    }
  }
  const lastKey = path[path.length - 1];
  if (isArray$2(parent)) parent[+lastKey] = mapper(parent[+lastKey]);
  else if (isPlainObject$2(parent)) parent[lastKey] = mapper(parent[lastKey]);
  if (isSet(parent)) {
    const oldValue = getNthKey(parent, +lastKey);
    const newValue = mapper(oldValue);
    if (oldValue !== newValue) {
      parent.delete(oldValue);
      parent.add(newValue);
    }
  }
  if (isMap(parent)) {
    const row = +path[path.length - 2];
    const keyToRow = getNthKey(parent, row);
    switch (+lastKey === 0 ? "key" : "value") {
      case "key": {
        const newKey = mapper(keyToRow);
        parent.set(newKey, parent.get(keyToRow));
        if (newKey !== keyToRow) parent.delete(keyToRow);
        break;
      }
      case "value":
        parent.set(keyToRow, mapper(parent.get(keyToRow)));
        break;
    }
  }
  return object;
};
function traverse(tree, walker$1, origin2 = []) {
  if (!tree) return;
  if (!isArray$2(tree)) {
    forEach$1(tree, (subtree, key) => traverse(subtree, walker$1, [...origin2, ...parsePath(key)]));
    return;
  }
  const [nodeValue, children] = tree;
  if (children) forEach$1(children, (child, key) => {
    traverse(child, walker$1, [...origin2, ...parsePath(key)]);
  });
  walker$1(nodeValue, origin2);
}
function applyValueAnnotations(plain, annotations, superJson) {
  traverse(annotations, (type, path) => {
    plain = setDeep(plain, path, (v) => untransformValue(v, type, superJson));
  });
  return plain;
}
function applyReferentialEqualityAnnotations(plain, annotations) {
  function apply(identicalPaths, path) {
    const object = getDeep(plain, parsePath(path));
    identicalPaths.map(parsePath).forEach((identicalObjectPath) => {
      plain = setDeep(plain, identicalObjectPath, () => object);
    });
  }
  if (isArray$2(annotations)) {
    const [root, other] = annotations;
    root.forEach((identicalPath) => {
      plain = setDeep(plain, parsePath(identicalPath), () => plain);
    });
    if (other) forEach$1(other, apply);
  } else forEach$1(annotations, apply);
  return plain;
}
const isDeep = (object, superJson) => isPlainObject$2(object) || isArray$2(object) || isMap(object) || isSet(object) || isInstanceOfRegisteredClass(object, superJson);
function addIdentity(object, path, identities) {
  const existingSet = identities.get(object);
  if (existingSet) existingSet.push(path);
  else identities.set(object, [path]);
}
function generateReferentialEqualityAnnotations(identitites, dedupe) {
  const result = {};
  let rootEqualityPaths = void 0;
  identitites.forEach((paths) => {
    if (paths.length <= 1) return;
    if (!dedupe) paths = paths.map((path) => path.map(String)).sort((a, b) => a.length - b.length);
    const [representativePath, ...identicalPaths] = paths;
    if (representativePath.length === 0) rootEqualityPaths = identicalPaths.map(stringifyPath);
    else result[stringifyPath(representativePath)] = identicalPaths.map(stringifyPath);
  });
  if (rootEqualityPaths) if (isEmptyObject$1(result)) return [rootEqualityPaths];
  else return [rootEqualityPaths, result];
  else return isEmptyObject$1(result) ? void 0 : result;
}
const walker = (object, identities, superJson, dedupe, path = [], objectsInThisPath = [], seenObjects = /* @__PURE__ */ new Map()) => {
  const primitive = isPrimitive(object);
  if (!primitive) {
    addIdentity(object, path, identities);
    const seen = seenObjects.get(object);
    if (seen) return dedupe ? { transformedValue: null } : seen;
  }
  if (!isDeep(object, superJson)) {
    const transformed$1 = transformValue(object, superJson);
    const result$1 = transformed$1 ? {
      transformedValue: transformed$1.value,
      annotations: [transformed$1.type]
    } : { transformedValue: object };
    if (!primitive) seenObjects.set(object, result$1);
    return result$1;
  }
  if (includes(objectsInThisPath, object)) return { transformedValue: null };
  const transformationResult = transformValue(object, superJson);
  const transformed = transformationResult?.value ?? object;
  const transformedValue = isArray$2(transformed) ? [] : {};
  const innerAnnotations = {};
  forEach$1(transformed, (value, index) => {
    if (index === "__proto__" || index === "constructor" || index === "prototype") throw new Error(`Detected property ${index}. This is a prototype pollution risk, please remove it from your object.`);
    const recursiveResult = walker(value, identities, superJson, dedupe, [...path, index], [...objectsInThisPath, object], seenObjects);
    transformedValue[index] = recursiveResult.transformedValue;
    if (isArray$2(recursiveResult.annotations)) innerAnnotations[index] = recursiveResult.annotations;
    else if (isPlainObject$2(recursiveResult.annotations)) forEach$1(recursiveResult.annotations, (tree, key) => {
      innerAnnotations[escapeKey(index) + "." + key] = tree;
    });
  });
  const result = isEmptyObject$1(innerAnnotations) ? {
    transformedValue,
    annotations: !!transformationResult ? [transformationResult.type] : void 0
  } : {
    transformedValue,
    annotations: !!transformationResult ? [transformationResult.type, innerAnnotations] : innerAnnotations
  };
  if (!primitive) seenObjects.set(object, result);
  return result;
};
function getType(payload) {
  return Object.prototype.toString.call(payload).slice(8, -1);
}
function isArray$1(payload) {
  return getType(payload) === "Array";
}
function isPlainObject$1(payload) {
  if (getType(payload) !== "Object") return false;
  const prototype2 = Object.getPrototypeOf(payload);
  return !!prototype2 && prototype2.constructor === Object && prototype2 === Object.prototype;
}
function assignProp(carry, key, newVal, originalObject, includeNonenumerable) {
  const propType = {}.propertyIsEnumerable.call(originalObject, key) ? "enumerable" : "nonenumerable";
  if (propType === "enumerable") carry[key] = newVal;
  if (includeNonenumerable && propType === "nonenumerable") Object.defineProperty(carry, key, {
    value: newVal,
    enumerable: false,
    writable: true,
    configurable: true
  });
}
function copy(target$1, options = {}) {
  if (isArray$1(target$1)) return target$1.map((item) => copy(item, options));
  if (!isPlainObject$1(target$1)) return target$1;
  const props = Object.getOwnPropertyNames(target$1);
  const symbols = Object.getOwnPropertySymbols(target$1);
  return [...props, ...symbols].reduce((carry, key) => {
    if (isArray$1(options.props) && !options.props.includes(key)) return carry;
    const val = target$1[key];
    assignProp(carry, key, copy(val, options), target$1, options.nonenumerable);
    return carry;
  }, {});
}
var SuperJSON = class {
  /**
  * @param dedupeReferentialEqualities  If true, SuperJSON will make sure only one instance of referentially equal objects are serialized and the rest are replaced with `null`.
  */
  constructor({ dedupe = false } = {}) {
    this.classRegistry = new ClassRegistry();
    this.symbolRegistry = new Registry((s) => s.description ?? "");
    this.customTransformerRegistry = new CustomTransformerRegistry();
    this.allowedErrorProps = [];
    this.dedupe = dedupe;
  }
  serialize(object) {
    const identities = /* @__PURE__ */ new Map();
    const output = walker(object, identities, this, this.dedupe);
    const res = { json: output.transformedValue };
    if (output.annotations) res.meta = {
      ...res.meta,
      values: output.annotations
    };
    const equalityAnnotations = generateReferentialEqualityAnnotations(identities, this.dedupe);
    if (equalityAnnotations) res.meta = {
      ...res.meta,
      referentialEqualities: equalityAnnotations
    };
    return res;
  }
  deserialize(payload) {
    const { json, meta } = payload;
    let result = copy(json);
    if (meta?.values) result = applyValueAnnotations(result, meta.values, this);
    if (meta?.referentialEqualities) result = applyReferentialEqualityAnnotations(result, meta.referentialEqualities);
    return result;
  }
  stringify(object) {
    return JSON.stringify(this.serialize(object));
  }
  parse(string) {
    return this.deserialize(JSON.parse(string));
  }
  registerClass(v, options) {
    this.classRegistry.register(v, options);
  }
  registerSymbol(v, identifier) {
    this.symbolRegistry.register(v, identifier);
  }
  registerCustom(transformer, name) {
    this.customTransformerRegistry.register({
      name,
      ...transformer
    });
  }
  allowErrorProps(...props) {
    this.allowedErrorProps.push(...props);
  }
};
SuperJSON.defaultInstance = new SuperJSON();
SuperJSON.serialize = SuperJSON.defaultInstance.serialize.bind(SuperJSON.defaultInstance);
SuperJSON.deserialize = SuperJSON.defaultInstance.deserialize.bind(SuperJSON.defaultInstance);
SuperJSON.stringify = SuperJSON.defaultInstance.stringify.bind(SuperJSON.defaultInstance);
SuperJSON.parse = SuperJSON.defaultInstance.parse.bind(SuperJSON.defaultInstance);
SuperJSON.registerClass = SuperJSON.defaultInstance.registerClass.bind(SuperJSON.defaultInstance);
SuperJSON.registerSymbol = SuperJSON.defaultInstance.registerSymbol.bind(SuperJSON.defaultInstance);
SuperJSON.registerCustom = SuperJSON.defaultInstance.registerCustom.bind(SuperJSON.defaultInstance);
SuperJSON.allowErrorProps = SuperJSON.defaultInstance.allowErrorProps.bind(SuperJSON.defaultInstance);
target.__VUE_DEVTOOLS_KIT_MESSAGE_CHANNELS__ ??= [];
target.__VUE_DEVTOOLS_KIT_RPC_CLIENT__ ??= null;
target.__VUE_DEVTOOLS_KIT_RPC_SERVER__ ??= null;
target.__VUE_DEVTOOLS_KIT_VITE_RPC_CLIENT__ ??= null;
target.__VUE_DEVTOOLS_KIT_VITE_RPC_SERVER__ ??= null;
target.__VUE_DEVTOOLS_KIT_BROADCAST_RPC_SERVER__ ??= null;
/*!
 * vue-router v5.0.3
 * (c) 2026 Eduardo San Martin Morote
 * @license MIT
 */
function warn$1(msg) {
  const args = Array.from(arguments).slice(1);
  console.warn.apply(console, ["[Vue Router warn]: " + msg].concat(args));
}
const HASH_RE = /#/g;
const AMPERSAND_RE = /&/g;
const SLASH_RE = /\//g;
const EQUAL_RE = /=/g;
const IM_RE = /\?/g;
const PLUS_RE = /\+/g;
const ENC_BRACKET_OPEN_RE = /%5B/g;
const ENC_BRACKET_CLOSE_RE = /%5D/g;
const ENC_CARET_RE = /%5E/g;
const ENC_BACKTICK_RE = /%60/g;
const ENC_CURLY_OPEN_RE = /%7B/g;
const ENC_PIPE_RE = /%7C/g;
const ENC_CURLY_CLOSE_RE = /%7D/g;
const ENC_SPACE_RE = /%20/g;
function commonEncode(text) {
  return text == null ? "" : encodeURI("" + text).replace(ENC_PIPE_RE, "|").replace(ENC_BRACKET_OPEN_RE, "[").replace(ENC_BRACKET_CLOSE_RE, "]");
}
function encodeHash(text) {
  return commonEncode(text).replace(ENC_CURLY_OPEN_RE, "{").replace(ENC_CURLY_CLOSE_RE, "}").replace(ENC_CARET_RE, "^");
}
function encodeQueryValue(text) {
  return commonEncode(text).replace(PLUS_RE, "%2B").replace(ENC_SPACE_RE, "+").replace(HASH_RE, "%23").replace(AMPERSAND_RE, "%26").replace(ENC_BACKTICK_RE, "`").replace(ENC_CURLY_OPEN_RE, "{").replace(ENC_CURLY_CLOSE_RE, "}").replace(ENC_CARET_RE, "^");
}
function encodeQueryKey(text) {
  return encodeQueryValue(text).replace(EQUAL_RE, "%3D");
}
function encodePath(text) {
  return commonEncode(text).replace(HASH_RE, "%23").replace(IM_RE, "%3F");
}
function encodeParam(text) {
  return encodePath(text).replace(SLASH_RE, "%2F");
}
function decode(text) {
  if (text == null) return null;
  try {
    return decodeURIComponent("" + text);
  } catch (err) {
    warn$1(`Error decoding "${text}". Using original value`);
  }
  return "" + text;
}
const TRAILING_SLASH_RE = /\/$/;
const removeTrailingSlash = (path) => path.replace(TRAILING_SLASH_RE, "");
function parseURL(parseQuery2, location2, currentLocation = "/") {
  let path, query = {}, searchString = "", hash = "";
  const hashPos = location2.indexOf("#");
  let searchPos = location2.indexOf("?");
  searchPos = hashPos >= 0 && searchPos > hashPos ? -1 : searchPos;
  if (searchPos >= 0) {
    path = location2.slice(0, searchPos);
    searchString = location2.slice(searchPos, hashPos > 0 ? hashPos : location2.length);
    query = parseQuery2(searchString.slice(1));
  }
  if (hashPos >= 0) {
    path = path || location2.slice(0, hashPos);
    hash = location2.slice(hashPos, location2.length);
  }
  path = resolveRelativePath(path != null ? path : location2, currentLocation);
  return {
    fullPath: path + searchString + hash,
    path,
    query,
    hash: decode(hash)
  };
}
function stringifyURL(stringifyQuery2, location2) {
  const query = location2.query ? stringifyQuery2(location2.query) : "";
  return location2.path + (query && "?") + query + (location2.hash || "");
}
function stripBase(pathname, base) {
  if (!base || !pathname.toLowerCase().startsWith(base.toLowerCase())) return pathname;
  return pathname.slice(base.length) || "/";
}
function isSameRouteLocation(stringifyQuery2, a, b) {
  const aLastIndex = a.matched.length - 1;
  const bLastIndex = b.matched.length - 1;
  return aLastIndex > -1 && aLastIndex === bLastIndex && isSameRouteRecord(a.matched[aLastIndex], b.matched[bLastIndex]) && isSameRouteLocationParams(a.params, b.params) && stringifyQuery2(a.query) === stringifyQuery2(b.query) && a.hash === b.hash;
}
function isSameRouteRecord(a, b) {
  return (a.aliasOf || a) === (b.aliasOf || b);
}
function isSameRouteLocationParams(a, b) {
  if (Object.keys(a).length !== Object.keys(b).length) return false;
  for (var key in a) if (!isSameRouteLocationParamsValue(a[key], b[key])) return false;
  return true;
}
function isSameRouteLocationParamsValue(a, b) {
  return isArray$3(a) ? isEquivalentArray(a, b) : isArray$3(b) ? isEquivalentArray(b, a) : (a && a.valueOf()) === (b && b.valueOf());
}
function isEquivalentArray(a, b) {
  return isArray$3(b) ? a.length === b.length && a.every((value, i) => value === b[i]) : a.length === 1 && a[0] === b;
}
function resolveRelativePath(to, from) {
  if (to.startsWith("/")) return to;
  if (!from.startsWith("/")) {
    warn$1(`Cannot resolve a relative location without an absolute path. Trying to resolve "${to}" from "${from}". It should look like "/${from}".`);
    return to;
  }
  if (!to) return from;
  const fromSegments = from.split("/");
  const toSegments = to.split("/");
  const lastToSegment = toSegments[toSegments.length - 1];
  if (lastToSegment === ".." || lastToSegment === ".") toSegments.push("");
  let position = fromSegments.length - 1;
  let toPosition;
  let segment;
  for (toPosition = 0; toPosition < toSegments.length; toPosition++) {
    segment = toSegments[toPosition];
    if (segment === ".") continue;
    if (segment === "..") {
      if (position > 1) position--;
    } else break;
  }
  return fromSegments.slice(0, position).join("/") + "/" + toSegments.slice(toPosition).join("/");
}
const START_LOCATION_NORMALIZED = {
  path: "/",
  name: void 0,
  params: {},
  query: {},
  hash: "",
  fullPath: "/",
  matched: [],
  meta: {},
  redirectedFrom: void 0
};
let NavigationType = /* @__PURE__ */ (function(NavigationType2) {
  NavigationType2["pop"] = "pop";
  NavigationType2["push"] = "push";
  return NavigationType2;
})({});
let NavigationDirection = /* @__PURE__ */ (function(NavigationDirection2) {
  NavigationDirection2["back"] = "back";
  NavigationDirection2["forward"] = "forward";
  NavigationDirection2["unknown"] = "";
  return NavigationDirection2;
})({});
function normalizeBase(base) {
  if (!base) if (isBrowser$1) {
    const baseEl = document.querySelector("base");
    base = baseEl && baseEl.getAttribute("href") || "/";
    base = base.replace(/^\w+:\/\/[^\/]+/, "");
  } else base = "/";
  if (base[0] !== "/" && base[0] !== "#") base = "/" + base;
  return removeTrailingSlash(base);
}
const BEFORE_HASH_RE = /^[^#]+#/;
function createHref(base, location2) {
  return base.replace(BEFORE_HASH_RE, "#") + location2;
}
function getElementPosition(el, offset2) {
  const docRect = document.documentElement.getBoundingClientRect();
  const elRect = el.getBoundingClientRect();
  return {
    behavior: offset2.behavior,
    left: elRect.left - docRect.left - (offset2.left || 0),
    top: elRect.top - docRect.top - (offset2.top || 0)
  };
}
const computeScrollPosition = () => ({
  left: window.scrollX,
  top: window.scrollY
});
function scrollToPosition(position) {
  let scrollToOptions;
  if ("el" in position) {
    const positionEl = position.el;
    const isIdSelector = typeof positionEl === "string" && positionEl.startsWith("#");
    if (typeof position.el === "string") {
      if (!isIdSelector || !document.getElementById(position.el.slice(1))) try {
        const foundEl = document.querySelector(position.el);
        if (isIdSelector && foundEl) {
          warn$1(`The selector "${position.el}" should be passed as "el: document.querySelector('${position.el}')" because it starts with "#".`);
          return;
        }
      } catch (err) {
        warn$1(`The selector "${position.el}" is invalid. If you are using an id selector, make sure to escape it. You can find more information about escaping characters in selectors at https://mathiasbynens.be/notes/css-escapes or use CSS.escape (https://developer.mozilla.org/en-US/docs/Web/API/CSS/escape).`);
        return;
      }
    }
    const el = typeof positionEl === "string" ? isIdSelector ? document.getElementById(positionEl.slice(1)) : document.querySelector(positionEl) : positionEl;
    if (!el) {
      warn$1(`Couldn't find element using selector "${position.el}" returned by scrollBehavior.`);
      return;
    }
    scrollToOptions = getElementPosition(el, position);
  } else scrollToOptions = position;
  if ("scrollBehavior" in document.documentElement.style) window.scrollTo(scrollToOptions);
  else window.scrollTo(scrollToOptions.left != null ? scrollToOptions.left : window.scrollX, scrollToOptions.top != null ? scrollToOptions.top : window.scrollY);
}
function getScrollKey(path, delta) {
  return (history.state ? history.state.position - delta : -1) + path;
}
const scrollPositions = /* @__PURE__ */ new Map();
function saveScrollPosition(key, scrollPosition) {
  scrollPositions.set(key, scrollPosition);
}
function getSavedScrollPosition(key) {
  const scroll = scrollPositions.get(key);
  scrollPositions.delete(key);
  return scroll;
}
function isRouteLocation(route) {
  return typeof route === "string" || route && typeof route === "object";
}
function isRouteName(name) {
  return typeof name === "string" || typeof name === "symbol";
}
function parseQuery(search) {
  const query = {};
  if (search === "" || search === "?") return query;
  const searchParams = (search[0] === "?" ? search.slice(1) : search).split("&");
  for (let i = 0; i < searchParams.length; ++i) {
    const searchParam = searchParams[i].replace(PLUS_RE, " ");
    const eqPos = searchParam.indexOf("=");
    const key = decode(eqPos < 0 ? searchParam : searchParam.slice(0, eqPos));
    const value = eqPos < 0 ? null : decode(searchParam.slice(eqPos + 1));
    if (key in query) {
      let currentValue = query[key];
      if (!isArray$3(currentValue)) currentValue = query[key] = [currentValue];
      currentValue.push(value);
    } else query[key] = value;
  }
  return query;
}
function stringifyQuery(query) {
  let search = "";
  for (let key in query) {
    const value = query[key];
    key = encodeQueryKey(key);
    if (value == null) {
      if (value !== void 0) search += (search.length ? "&" : "") + key;
      continue;
    }
    (isArray$3(value) ? value.map((v) => v && encodeQueryValue(v)) : [value && encodeQueryValue(value)]).forEach((value2) => {
      if (value2 !== void 0) {
        search += (search.length ? "&" : "") + key;
        if (value2 != null) search += "=" + value2;
      }
    });
  }
  return search;
}
function normalizeQuery(query) {
  const normalizedQuery = {};
  for (const key in query) {
    const value = query[key];
    if (value !== void 0) normalizedQuery[key] = isArray$3(value) ? value.map((v) => v == null ? null : "" + v) : value == null ? value : "" + value;
  }
  return normalizedQuery;
}
function useCallbacks() {
  let handlers = [];
  function add(handler) {
    handlers.push(handler);
    return () => {
      const i = handlers.indexOf(handler);
      if (i > -1) handlers.splice(i, 1);
    };
  }
  function reset() {
    handlers = [];
  }
  return {
    add,
    list: () => handlers.slice(),
    reset
  };
}
function guardToPromiseFn(guard, to, from, record, name, runWithContext = (fn) => fn()) {
  const enterCallbackArray = record && (record.enterCallbacks[name] = record.enterCallbacks[name] || []);
  return () => new Promise((resolve, reject) => {
    const next = (valid) => {
      if (valid === false) reject(createRouterError(ErrorTypes.NAVIGATION_ABORTED, {
        from,
        to
      }));
      else if (valid instanceof Error) reject(valid);
      else if (isRouteLocation(valid)) reject(createRouterError(ErrorTypes.NAVIGATION_GUARD_REDIRECT, {
        from: to,
        to: valid
      }));
      else {
        if (enterCallbackArray && record.enterCallbacks[name] === enterCallbackArray && typeof valid === "function") enterCallbackArray.push(valid);
        resolve();
      }
    };
    const guardReturn = runWithContext(() => guard.call(record && record.instances[name], to, from, withDeprecationWarning(canOnlyBeCalledOnce(next, to, from))));
    let guardCall = Promise.resolve(guardReturn);
    if (guard.length < 3) guardCall = guardCall.then(next);
    if (guard.length > 2) {
      const message = `The "next" callback was never called inside of ${guard.name ? '"' + guard.name + '"' : ""}:
${guard.toString()}
. If you are returning a value instead of calling "next", make sure to remove the "next" parameter from your function.`;
      if (typeof guardReturn === "object" && "then" in guardReturn) guardCall = guardCall.then((resolvedValue) => {
        if (!next._called) {
          warn$1(message);
          return Promise.reject(/* @__PURE__ */ new Error("Invalid navigation guard"));
        }
        return resolvedValue;
      });
      else if (guardReturn !== void 0) {
        if (!next._called) {
          warn$1(message);
          reject(/* @__PURE__ */ new Error("Invalid navigation guard"));
          return;
        }
      }
    }
    guardCall.catch((err) => reject(err));
  });
}
function withDeprecationWarning(next) {
  let warned = false;
  return function() {
    if (!warned) {
      warned = true;
      warn$1("The `next()` callback in navigation guards is deprecated. Return the value instead of calling `next(value)`.");
    }
    return next.apply(this, arguments);
  };
}
function canOnlyBeCalledOnce(next, to, from) {
  let called = 0;
  return function() {
    if (called++ === 1) warn$1(`The "next" callback was called more than once in one navigation guard when going from "${from.fullPath}" to "${to.fullPath}". It should be called exactly one time in each navigation guard. This will fail in production.`);
    next._called = true;
    if (called === 1) next.apply(null, arguments);
  };
}
function extractComponentsGuards(matched, guardType, to, from, runWithContext = (fn) => fn()) {
  const guards = [];
  for (const record of matched) {
    if (!record.components && record.children && !record.children.length) warn$1(`Record with path "${record.path}" is either missing a "component(s)" or "children" property.`);
    for (const name in record.components) {
      let rawComponent = record.components[name];
      {
        if (!rawComponent || typeof rawComponent !== "object" && typeof rawComponent !== "function") {
          warn$1(`Component "${name}" in record with path "${record.path}" is not a valid component. Received "${String(rawComponent)}".`);
          throw new Error("Invalid route component");
        } else if ("then" in rawComponent) {
          warn$1(`Component "${name}" in record with path "${record.path}" is a Promise instead of a function that returns a Promise. Did you write "import('./MyPage.vue')" instead of "() => import('./MyPage.vue')" ? This will break in production if not fixed.`);
          const promise = rawComponent;
          rawComponent = () => promise;
        } else if (rawComponent.__asyncLoader && !rawComponent.__warnedDefineAsync) {
          rawComponent.__warnedDefineAsync = true;
          warn$1(`Component "${name}" in record with path "${record.path}" is defined using "defineAsyncComponent()". Write "() => import('./MyPage.vue')" instead of "defineAsyncComponent(() => import('./MyPage.vue'))".`);
        }
      }
      if (guardType !== "beforeRouteEnter" && !record.instances[name]) continue;
      if (isRouteComponent(rawComponent)) {
        const guard = (rawComponent.__vccOpts || rawComponent)[guardType];
        guard && guards.push(guardToPromiseFn(guard, to, from, record, name, runWithContext));
      } else {
        let componentPromise = rawComponent();
        if (!("catch" in componentPromise)) {
          warn$1(`Component "${name}" in record with path "${record.path}" is a function that does not return a Promise. If you were passing a functional component, make sure to add a "displayName" to the component. This will break in production if not fixed.`);
          componentPromise = Promise.resolve(componentPromise);
        }
        guards.push(() => componentPromise.then((resolved) => {
          if (!resolved) throw new Error(`Couldn't resolve component "${name}" at "${record.path}"`);
          const resolvedComponent = isESModule(resolved) ? resolved.default : resolved;
          record.mods[name] = resolved;
          record.components[name] = resolvedComponent;
          const guard = (resolvedComponent.__vccOpts || resolvedComponent)[guardType];
          return guard && guardToPromiseFn(guard, to, from, record, name, runWithContext)();
        }));
      }
    }
  }
  return guards;
}
function extractChangingRecords(to, from) {
  const leavingRecords = [];
  const updatingRecords = [];
  const enteringRecords = [];
  const len = Math.max(from.matched.length, to.matched.length);
  for (let i = 0; i < len; i++) {
    const recordFrom = from.matched[i];
    if (recordFrom) if (to.matched.find((record) => isSameRouteRecord(record, recordFrom))) updatingRecords.push(recordFrom);
    else leavingRecords.push(recordFrom);
    const recordTo = to.matched[i];
    if (recordTo) {
      if (!from.matched.find((record) => isSameRouteRecord(record, recordTo))) enteringRecords.push(recordTo);
    }
  }
  return [
    leavingRecords,
    updatingRecords,
    enteringRecords
  ];
}
function formatRouteLocation(routeLocation, tooltip) {
  const copy2 = assign({}, routeLocation, { matched: routeLocation.matched.map((matched) => omit(matched, [
    "instances",
    "children",
    "aliasOf"
  ])) });
  return { _custom: {
    type: null,
    readOnly: true,
    display: routeLocation.fullPath,
    tooltip,
    value: copy2
  } };
}
function formatDisplay(display) {
  return { _custom: { display } };
}
let routerId = 0;
function addDevtools(app, router, matcher) {
  if (router.__hasDevtools) return;
  router.__hasDevtools = true;
  const id = routerId++;
  setupDevToolsPlugin({
    id: "org.vuejs.router" + (id ? "." + id : ""),
    label: "Vue Router",
    packageName: "vue-router",
    homepage: "https://router.vuejs.org",
    logo: "https://router.vuejs.org/logo.png",
    componentStateTypes: ["Routing"],
    app
  }, (api) => {
    api.on.inspectComponent((payload) => {
      if (payload.instanceData) payload.instanceData.state.push({
        type: "Routing",
        key: "$route",
        editable: false,
        value: formatRouteLocation(router.currentRoute.value, "Current Route")
      });
    });
    api.on.visitComponentTree(({ treeNode: node, componentInstance }) => {
      if (componentInstance.__vrv_devtools) {
        const info = componentInstance.__vrv_devtools;
        node.tags.push({
          label: (info.name ? `${info.name.toString()}: ` : "") + info.path,
          textColor: 0,
          tooltip: "This component is rendered by &lt;router-view&gt;",
          backgroundColor: PINK_500
        });
      }
      if (isArray$3(componentInstance.__vrl_devtools)) {
        componentInstance.__devtoolsApi = api;
        componentInstance.__vrl_devtools.forEach((devtoolsData) => {
          let label = devtoolsData.route.path;
          let backgroundColor = ORANGE_400;
          let tooltip = "";
          let textColor = 0;
          if (devtoolsData.error) {
            label = devtoolsData.error;
            backgroundColor = RED_100;
            textColor = RED_700;
          } else if (devtoolsData.isExactActive) {
            backgroundColor = LIME_500;
            tooltip = "This is exactly active";
          } else if (devtoolsData.isActive) {
            backgroundColor = BLUE_600;
            tooltip = "This link is active";
          }
          node.tags.push({
            label,
            textColor,
            tooltip,
            backgroundColor
          });
        });
      }
    });
    watch(router.currentRoute, () => {
      refreshRoutesView();
      api.notifyComponentUpdate();
      api.sendInspectorTree(routerInspectorId);
      api.sendInspectorState(routerInspectorId);
    });
    const navigationsLayerId = "router:navigations:" + id;
    api.addTimelineLayer({
      id: navigationsLayerId,
      label: `Router${id ? " " + id : ""} Navigations`,
      color: 4237508
    });
    router.onError((error, to) => {
      api.addTimelineEvent({
        layerId: navigationsLayerId,
        event: {
          title: "Error during Navigation",
          subtitle: to.fullPath,
          logType: "error",
          time: api.now(),
          data: { error },
          groupId: to.meta.__navigationId
        }
      });
    });
    let navigationId = 0;
    router.beforeEach((to, from) => {
      const data = {
        guard: formatDisplay("beforeEach"),
        from: formatRouteLocation(from, "Current Location during this navigation"),
        to: formatRouteLocation(to, "Target location")
      };
      Object.defineProperty(to.meta, "__navigationId", { value: navigationId++ });
      api.addTimelineEvent({
        layerId: navigationsLayerId,
        event: {
          time: api.now(),
          title: "Start of navigation",
          subtitle: to.fullPath,
          data,
          groupId: to.meta.__navigationId
        }
      });
    });
    router.afterEach((to, from, failure) => {
      const data = { guard: formatDisplay("afterEach") };
      if (failure) {
        data.failure = { _custom: {
          type: Error,
          readOnly: true,
          display: failure ? failure.message : "",
          tooltip: "Navigation Failure",
          value: failure
        } };
        data.status = formatDisplay("❌");
      } else data.status = formatDisplay("✅");
      data.from = formatRouteLocation(from, "Current Location during this navigation");
      data.to = formatRouteLocation(to, "Target location");
      api.addTimelineEvent({
        layerId: navigationsLayerId,
        event: {
          title: "End of navigation",
          subtitle: to.fullPath,
          time: api.now(),
          data,
          logType: failure ? "warning" : "default",
          groupId: to.meta.__navigationId
        }
      });
    });
    const routerInspectorId = "router-inspector:" + id;
    api.addInspector({
      id: routerInspectorId,
      label: "Routes" + (id ? " " + id : ""),
      icon: "book",
      treeFilterPlaceholder: "Search routes"
    });
    function refreshRoutesView() {
      if (!activeRoutesPayload) return;
      const payload = activeRoutesPayload;
      let routes = matcher.getRoutes().filter((route) => !route.parent || !route.parent.record.components);
      routes.forEach(resetMatchStateOnRouteRecord);
      if (payload.filter) routes = routes.filter((route) => isRouteMatching(route, payload.filter.toLowerCase()));
      routes.forEach((route) => markRouteRecordActive(route, router.currentRoute.value));
      payload.rootNodes = routes.map(formatRouteRecordForInspector);
    }
    let activeRoutesPayload;
    api.on.getInspectorTree((payload) => {
      activeRoutesPayload = payload;
      if (payload.app === app && payload.inspectorId === routerInspectorId) refreshRoutesView();
    });
    api.on.getInspectorState((payload) => {
      if (payload.app === app && payload.inspectorId === routerInspectorId) {
        const route = matcher.getRoutes().find((route2) => route2.record.__vd_id === payload.nodeId);
        if (route) payload.state = { options: formatRouteRecordMatcherForStateInspector(route) };
      }
    });
    api.sendInspectorTree(routerInspectorId);
    api.sendInspectorState(routerInspectorId);
  });
}
function modifierForKey(key) {
  if (key.optional) return key.repeatable ? "*" : "?";
  else return key.repeatable ? "+" : "";
}
function formatRouteRecordMatcherForStateInspector(route) {
  const { record } = route;
  const fields = [{
    editable: false,
    key: "path",
    value: record.path
  }];
  if (record.name != null) fields.push({
    editable: false,
    key: "name",
    value: record.name
  });
  fields.push({
    editable: false,
    key: "regexp",
    value: route.re
  });
  if (route.keys.length) fields.push({
    editable: false,
    key: "keys",
    value: { _custom: {
      type: null,
      readOnly: true,
      display: route.keys.map((key) => `${key.name}${modifierForKey(key)}`).join(" "),
      tooltip: "Param keys",
      value: route.keys
    } }
  });
  if (record.redirect != null) fields.push({
    editable: false,
    key: "redirect",
    value: record.redirect
  });
  if (route.alias.length) fields.push({
    editable: false,
    key: "aliases",
    value: route.alias.map((alias) => alias.record.path)
  });
  if (Object.keys(route.record.meta).length) fields.push({
    editable: false,
    key: "meta",
    value: route.record.meta
  });
  fields.push({
    key: "score",
    editable: false,
    value: { _custom: {
      type: null,
      readOnly: true,
      display: route.score.map((score) => score.join(", ")).join(" | "),
      tooltip: "Score used to sort routes",
      value: route.score
    } }
  });
  return fields;
}
const PINK_500 = 15485081;
const BLUE_600 = 2450411;
const LIME_500 = 8702998;
const CYAN_400 = 2282478;
const ORANGE_400 = 16486972;
const DARK = 6710886;
const RED_100 = 16704226;
const RED_700 = 12131356;
function formatRouteRecordForInspector(route) {
  const tags = [];
  const { record } = route;
  if (record.name != null) tags.push({
    label: String(record.name),
    textColor: 0,
    backgroundColor: CYAN_400
  });
  if (record.aliasOf) tags.push({
    label: "alias",
    textColor: 0,
    backgroundColor: ORANGE_400
  });
  if (route.__vd_match) tags.push({
    label: "matches",
    textColor: 0,
    backgroundColor: PINK_500
  });
  if (route.__vd_exactActive) tags.push({
    label: "exact",
    textColor: 0,
    backgroundColor: LIME_500
  });
  if (route.__vd_active) tags.push({
    label: "active",
    textColor: 0,
    backgroundColor: BLUE_600
  });
  if (record.redirect) tags.push({
    label: typeof record.redirect === "string" ? `redirect: ${record.redirect}` : "redirects",
    textColor: 16777215,
    backgroundColor: DARK
  });
  let id = record.__vd_id;
  if (id == null) {
    id = String(routeRecordId++);
    record.__vd_id = id;
  }
  return {
    id,
    label: record.path,
    tags,
    children: route.children.map(formatRouteRecordForInspector)
  };
}
let routeRecordId = 0;
const EXTRACT_REGEXP_RE = /^\/(.*)\/([a-z]*)$/;
function markRouteRecordActive(route, currentRoute) {
  const isExactActive = currentRoute.matched.length && isSameRouteRecord(currentRoute.matched[currentRoute.matched.length - 1], route.record);
  route.__vd_exactActive = route.__vd_active = isExactActive;
  if (!isExactActive) route.__vd_active = currentRoute.matched.some((match) => isSameRouteRecord(match, route.record));
  route.children.forEach((childRoute) => markRouteRecordActive(childRoute, currentRoute));
}
function resetMatchStateOnRouteRecord(route) {
  route.__vd_match = false;
  route.children.forEach(resetMatchStateOnRouteRecord);
}
function isRouteMatching(route, filter2) {
  const found = String(route.re).match(EXTRACT_REGEXP_RE);
  route.__vd_match = false;
  if (!found || found.length < 3) return false;
  if (new RegExp(found[1].replace(/\$$/, ""), found[2]).test(filter2)) {
    route.children.forEach((child) => isRouteMatching(child, filter2));
    if (route.record.path !== "/" || filter2 === "/") {
      route.__vd_match = route.re.test(filter2);
      return true;
    }
    return false;
  }
  const path = route.record.path.toLowerCase();
  const decodedPath = decode(path);
  if (!filter2.startsWith("/") && (decodedPath.includes(filter2) || path.includes(filter2))) return true;
  if (decodedPath.startsWith(filter2) || path.startsWith(filter2)) return true;
  if (route.record.name && String(route.record.name).includes(filter2)) return true;
  return route.children.some((child) => isRouteMatching(child, filter2));
}
function omit(obj, keys) {
  const ret = {};
  for (const key in obj) if (!keys.includes(key)) ret[key] = obj[key];
  return ret;
}
/*!
 * vue-router v5.0.3
 * (c) 2026 Eduardo San Martin Morote
 * @license MIT
 */
let createBaseLocation = () => location.protocol + "//" + location.host;
function createCurrentLocation(base, location2) {
  const { pathname, search, hash } = location2;
  const hashPos = base.indexOf("#");
  if (hashPos > -1) {
    let slicePos = hash.includes(base.slice(hashPos)) ? base.slice(hashPos).length : 1;
    let pathFromHash = hash.slice(slicePos);
    if (pathFromHash[0] !== "/") pathFromHash = "/" + pathFromHash;
    return stripBase(pathFromHash, "");
  }
  return stripBase(pathname, base) + search + hash;
}
function useHistoryListeners(base, historyState, currentLocation, replace) {
  let listeners = [];
  let teardowns = [];
  let pauseState = null;
  const popStateHandler = ({ state }) => {
    const to = createCurrentLocation(base, location);
    const from = currentLocation.value;
    const fromState = historyState.value;
    let delta = 0;
    if (state) {
      currentLocation.value = to;
      historyState.value = state;
      if (pauseState && pauseState === from) {
        pauseState = null;
        return;
      }
      delta = fromState ? state.position - fromState.position : 0;
    } else replace(to);
    listeners.forEach((listener) => {
      listener(currentLocation.value, from, {
        delta,
        type: NavigationType.pop,
        direction: delta ? delta > 0 ? NavigationDirection.forward : NavigationDirection.back : NavigationDirection.unknown
      });
    });
  };
  function pauseListeners() {
    pauseState = currentLocation.value;
  }
  function listen(callback) {
    listeners.push(callback);
    const teardown = () => {
      const index = listeners.indexOf(callback);
      if (index > -1) listeners.splice(index, 1);
    };
    teardowns.push(teardown);
    return teardown;
  }
  function beforeUnloadListener() {
    if (document.visibilityState === "hidden") {
      const { history: history2 } = window;
      if (!history2.state) return;
      history2.replaceState(assign({}, history2.state, { scroll: computeScrollPosition() }), "");
    }
  }
  function destroy() {
    for (const teardown of teardowns) teardown();
    teardowns = [];
    window.removeEventListener("popstate", popStateHandler);
    window.removeEventListener("pagehide", beforeUnloadListener);
    document.removeEventListener("visibilitychange", beforeUnloadListener);
  }
  window.addEventListener("popstate", popStateHandler);
  window.addEventListener("pagehide", beforeUnloadListener);
  document.addEventListener("visibilitychange", beforeUnloadListener);
  return {
    pauseListeners,
    listen,
    destroy
  };
}
function buildState(back, current, forward, replaced = false, computeScroll = false) {
  return {
    back,
    current,
    forward,
    replaced,
    position: window.history.length,
    scroll: computeScroll ? computeScrollPosition() : null
  };
}
function useHistoryStateNavigation(base) {
  const { history: history2, location: location2 } = window;
  const currentLocation = { value: createCurrentLocation(base, location2) };
  const historyState = { value: history2.state };
  if (!historyState.value) changeLocation(currentLocation.value, {
    back: null,
    current: currentLocation.value,
    forward: null,
    position: history2.length - 1,
    replaced: true,
    scroll: null
  }, true);
  function changeLocation(to, state, replace2) {
    const hashIndex = base.indexOf("#");
    const url = hashIndex > -1 ? (location2.host && document.querySelector("base") ? base : base.slice(hashIndex)) + to : createBaseLocation() + base + to;
    try {
      history2[replace2 ? "replaceState" : "pushState"](state, "", url);
      historyState.value = state;
    } catch (err) {
      warn$1("Error with push/replace State", err);
      location2[replace2 ? "replace" : "assign"](url);
    }
  }
  function replace(to, data) {
    changeLocation(to, assign({}, history2.state, buildState(historyState.value.back, to, historyState.value.forward, true), data, { position: historyState.value.position }), true);
    currentLocation.value = to;
  }
  function push(to, data) {
    const currentState = assign({}, historyState.value, history2.state, {
      forward: to,
      scroll: computeScrollPosition()
    });
    if (!history2.state) warn$1("history.state seems to have been manually replaced without preserving the necessary values. Make sure to preserve existing history state if you are manually calling history.replaceState:\n\nhistory.replaceState(history.state, '', url)\n\nYou can find more information at https://router.vuejs.org/guide/migration/#Usage-of-history-state");
    changeLocation(currentState.current, currentState, true);
    changeLocation(to, assign({}, buildState(currentLocation.value, to, null), { position: currentState.position + 1 }, data), false);
    currentLocation.value = to;
  }
  return {
    location: currentLocation,
    state: historyState,
    push,
    replace
  };
}
function createWebHistory(base) {
  base = normalizeBase(base);
  const historyNavigation = useHistoryStateNavigation(base);
  const historyListeners = useHistoryListeners(base, historyNavigation.state, historyNavigation.location, historyNavigation.replace);
  function go(delta, triggerListeners = true) {
    if (!triggerListeners) historyListeners.pauseListeners();
    history.go(delta);
  }
  const routerHistory = assign({
    location: "",
    base,
    go,
    createHref: createHref.bind(null, base)
  }, historyNavigation, historyListeners);
  Object.defineProperty(routerHistory, "location", {
    enumerable: true,
    get: () => historyNavigation.location.value
  });
  Object.defineProperty(routerHistory, "state", {
    enumerable: true,
    get: () => historyNavigation.state.value
  });
  return routerHistory;
}
let TokenType = /* @__PURE__ */ (function(TokenType2) {
  TokenType2[TokenType2["Static"] = 0] = "Static";
  TokenType2[TokenType2["Param"] = 1] = "Param";
  TokenType2[TokenType2["Group"] = 2] = "Group";
  return TokenType2;
})({});
var TokenizerState = /* @__PURE__ */ (function(TokenizerState2) {
  TokenizerState2[TokenizerState2["Static"] = 0] = "Static";
  TokenizerState2[TokenizerState2["Param"] = 1] = "Param";
  TokenizerState2[TokenizerState2["ParamRegExp"] = 2] = "ParamRegExp";
  TokenizerState2[TokenizerState2["ParamRegExpEnd"] = 3] = "ParamRegExpEnd";
  TokenizerState2[TokenizerState2["EscapeNext"] = 4] = "EscapeNext";
  return TokenizerState2;
})(TokenizerState || {});
const ROOT_TOKEN = {
  type: TokenType.Static,
  value: ""
};
const VALID_PARAM_RE = /[a-zA-Z0-9_]/;
function tokenizePath(path) {
  if (!path) return [[]];
  if (path === "/") return [[ROOT_TOKEN]];
  if (!path.startsWith("/")) throw new Error(`Route paths should start with a "/": "${path}" should be "/${path}".`);
  function crash(message) {
    throw new Error(`ERR (${state})/"${buffer}": ${message}`);
  }
  let state = TokenizerState.Static;
  let previousState = state;
  const tokens = [];
  let segment;
  function finalizeSegment() {
    if (segment) tokens.push(segment);
    segment = [];
  }
  let i = 0;
  let char;
  let buffer = "";
  let customRe = "";
  function consumeBuffer() {
    if (!buffer) return;
    if (state === TokenizerState.Static) segment.push({
      type: TokenType.Static,
      value: buffer
    });
    else if (state === TokenizerState.Param || state === TokenizerState.ParamRegExp || state === TokenizerState.ParamRegExpEnd) {
      if (segment.length > 1 && (char === "*" || char === "+")) crash(`A repeatable param (${buffer}) must be alone in its segment. eg: '/:ids+.`);
      segment.push({
        type: TokenType.Param,
        value: buffer,
        regexp: customRe,
        repeatable: char === "*" || char === "+",
        optional: char === "*" || char === "?"
      });
    } else crash("Invalid state to consume buffer");
    buffer = "";
  }
  function addCharToBuffer() {
    buffer += char;
  }
  while (i < path.length) {
    char = path[i++];
    if (char === "\\" && state !== TokenizerState.ParamRegExp) {
      previousState = state;
      state = TokenizerState.EscapeNext;
      continue;
    }
    switch (state) {
      case TokenizerState.Static:
        if (char === "/") {
          if (buffer) consumeBuffer();
          finalizeSegment();
        } else if (char === ":") {
          consumeBuffer();
          state = TokenizerState.Param;
        } else addCharToBuffer();
        break;
      case TokenizerState.EscapeNext:
        addCharToBuffer();
        state = previousState;
        break;
      case TokenizerState.Param:
        if (char === "(") state = TokenizerState.ParamRegExp;
        else if (VALID_PARAM_RE.test(char)) addCharToBuffer();
        else {
          consumeBuffer();
          state = TokenizerState.Static;
          if (char !== "*" && char !== "?" && char !== "+") i--;
        }
        break;
      case TokenizerState.ParamRegExp:
        if (char === ")") if (customRe[customRe.length - 1] == "\\") customRe = customRe.slice(0, -1) + char;
        else state = TokenizerState.ParamRegExpEnd;
        else customRe += char;
        break;
      case TokenizerState.ParamRegExpEnd:
        consumeBuffer();
        state = TokenizerState.Static;
        if (char !== "*" && char !== "?" && char !== "+") i--;
        customRe = "";
        break;
      default:
        crash("Unknown state");
        break;
    }
  }
  if (state === TokenizerState.ParamRegExp) crash(`Unfinished custom RegExp for param "${buffer}"`);
  consumeBuffer();
  finalizeSegment();
  return tokens;
}
const BASE_PARAM_PATTERN = "[^/]+?";
const BASE_PATH_PARSER_OPTIONS = {
  sensitive: false,
  strict: false,
  start: true,
  end: true
};
var PathScore = /* @__PURE__ */ (function(PathScore2) {
  PathScore2[PathScore2["_multiplier"] = 10] = "_multiplier";
  PathScore2[PathScore2["Root"] = 90] = "Root";
  PathScore2[PathScore2["Segment"] = 40] = "Segment";
  PathScore2[PathScore2["SubSegment"] = 30] = "SubSegment";
  PathScore2[PathScore2["Static"] = 40] = "Static";
  PathScore2[PathScore2["Dynamic"] = 20] = "Dynamic";
  PathScore2[PathScore2["BonusCustomRegExp"] = 10] = "BonusCustomRegExp";
  PathScore2[PathScore2["BonusWildcard"] = -50] = "BonusWildcard";
  PathScore2[PathScore2["BonusRepeatable"] = -20] = "BonusRepeatable";
  PathScore2[PathScore2["BonusOptional"] = -8] = "BonusOptional";
  PathScore2[PathScore2["BonusStrict"] = 0.7000000000000001] = "BonusStrict";
  PathScore2[PathScore2["BonusCaseSensitive"] = 0.25] = "BonusCaseSensitive";
  return PathScore2;
})(PathScore || {});
const REGEX_CHARS_RE = /[.+*?^${}()[\]/\\]/g;
function tokensToParser(segments, extraOptions) {
  const options = assign({}, BASE_PATH_PARSER_OPTIONS, extraOptions);
  const score = [];
  let pattern = options.start ? "^" : "";
  const keys = [];
  for (const segment of segments) {
    const segmentScores = segment.length ? [] : [PathScore.Root];
    if (options.strict && !segment.length) pattern += "/";
    for (let tokenIndex = 0; tokenIndex < segment.length; tokenIndex++) {
      const token = segment[tokenIndex];
      let subSegmentScore = PathScore.Segment + (options.sensitive ? PathScore.BonusCaseSensitive : 0);
      if (token.type === TokenType.Static) {
        if (!tokenIndex) pattern += "/";
        pattern += token.value.replace(REGEX_CHARS_RE, "\\$&");
        subSegmentScore += PathScore.Static;
      } else if (token.type === TokenType.Param) {
        const { value, repeatable, optional, regexp } = token;
        keys.push({
          name: value,
          repeatable,
          optional
        });
        const re22 = regexp ? regexp : BASE_PARAM_PATTERN;
        if (re22 !== BASE_PARAM_PATTERN) {
          subSegmentScore += PathScore.BonusCustomRegExp;
          try {
            new RegExp(`(${re22})`);
          } catch (err) {
            throw new Error(`Invalid custom RegExp for param "${value}" (${re22}): ` + err.message);
          }
        }
        let subPattern = repeatable ? `((?:${re22})(?:/(?:${re22}))*)` : `(${re22})`;
        if (!tokenIndex) subPattern = optional && segment.length < 2 ? `(?:/${subPattern})` : "/" + subPattern;
        if (optional) subPattern += "?";
        pattern += subPattern;
        subSegmentScore += PathScore.Dynamic;
        if (optional) subSegmentScore += PathScore.BonusOptional;
        if (repeatable) subSegmentScore += PathScore.BonusRepeatable;
        if (re22 === ".*") subSegmentScore += PathScore.BonusWildcard;
      }
      segmentScores.push(subSegmentScore);
    }
    score.push(segmentScores);
  }
  if (options.strict && options.end) {
    const i = score.length - 1;
    score[i][score[i].length - 1] += PathScore.BonusStrict;
  }
  if (!options.strict) pattern += "/?";
  if (options.end) pattern += "$";
  else if (options.strict && !pattern.endsWith("/")) pattern += "(?:/|$)";
  const re2 = new RegExp(pattern, options.sensitive ? "" : "i");
  function parse(path) {
    const match = path.match(re2);
    const params = {};
    if (!match) return null;
    for (let i = 1; i < match.length; i++) {
      const value = match[i] || "";
      const key = keys[i - 1];
      params[key.name] = value && key.repeatable ? value.split("/") : value;
    }
    return params;
  }
  function stringify(params) {
    let path = "";
    let avoidDuplicatedSlash = false;
    for (const segment of segments) {
      if (!avoidDuplicatedSlash || !path.endsWith("/")) path += "/";
      avoidDuplicatedSlash = false;
      for (const token of segment) if (token.type === TokenType.Static) path += token.value;
      else if (token.type === TokenType.Param) {
        const { value, repeatable, optional } = token;
        const param = value in params ? params[value] : "";
        if (isArray$3(param) && !repeatable) throw new Error(`Provided param "${value}" is an array but it is not repeatable (* or + modifiers)`);
        const text = isArray$3(param) ? param.join("/") : param;
        if (!text) if (optional) {
          if (segment.length < 2) if (path.endsWith("/")) path = path.slice(0, -1);
          else avoidDuplicatedSlash = true;
        } else throw new Error(`Missing required param "${value}"`);
        path += text;
      }
    }
    return path || "/";
  }
  return {
    re: re2,
    score,
    keys,
    parse,
    stringify
  };
}
function compareScoreArray(a, b) {
  let i = 0;
  while (i < a.length && i < b.length) {
    const diff = b[i] - a[i];
    if (diff) return diff;
    i++;
  }
  if (a.length < b.length) return a.length === 1 && a[0] === PathScore.Static + PathScore.Segment ? -1 : 1;
  else if (a.length > b.length) return b.length === 1 && b[0] === PathScore.Static + PathScore.Segment ? 1 : -1;
  return 0;
}
function comparePathParserScore(a, b) {
  let i = 0;
  const aScore = a.score;
  const bScore = b.score;
  while (i < aScore.length && i < bScore.length) {
    const comp = compareScoreArray(aScore[i], bScore[i]);
    if (comp) return comp;
    i++;
  }
  if (Math.abs(bScore.length - aScore.length) === 1) {
    if (isLastScoreNegative(aScore)) return 1;
    if (isLastScoreNegative(bScore)) return -1;
  }
  return bScore.length - aScore.length;
}
function isLastScoreNegative(score) {
  const last = score[score.length - 1];
  return score.length > 0 && last[last.length - 1] < 0;
}
const PATH_PARSER_OPTIONS_DEFAULTS = {
  strict: false,
  end: true,
  sensitive: false
};
function createRouteRecordMatcher(record, parent, options) {
  const parser = tokensToParser(tokenizePath(record.path), options);
  {
    const existingKeys = /* @__PURE__ */ new Set();
    for (const key of parser.keys) {
      if (existingKeys.has(key.name)) warn$1(`Found duplicated params with name "${key.name}" for path "${record.path}". Only the last one will be available on "$route.params".`);
      existingKeys.add(key.name);
    }
  }
  const matcher = assign(parser, {
    record,
    parent,
    children: [],
    alias: []
  });
  if (parent) {
    if (!matcher.record.aliasOf === !parent.record.aliasOf) parent.children.push(matcher);
  }
  return matcher;
}
function createRouterMatcher(routes, globalOptions) {
  const matchers = [];
  const matcherMap = /* @__PURE__ */ new Map();
  globalOptions = mergeOptions(PATH_PARSER_OPTIONS_DEFAULTS, globalOptions);
  function getRecordMatcher(name) {
    return matcherMap.get(name);
  }
  function addRoute(record, parent, originalRecord) {
    const isRootAdd = !originalRecord;
    const mainNormalizedRecord = normalizeRouteRecord(record);
    checkChildMissingNameWithEmptyPath(mainNormalizedRecord, parent);
    mainNormalizedRecord.aliasOf = originalRecord && originalRecord.record;
    const options = mergeOptions(globalOptions, record);
    const normalizedRecords = [mainNormalizedRecord];
    if ("alias" in record) {
      const aliases = typeof record.alias === "string" ? [record.alias] : record.alias;
      for (const alias of aliases) normalizedRecords.push(normalizeRouteRecord(assign({}, mainNormalizedRecord, {
        components: originalRecord ? originalRecord.record.components : mainNormalizedRecord.components,
        path: alias,
        aliasOf: originalRecord ? originalRecord.record : mainNormalizedRecord
      })));
    }
    let matcher;
    let originalMatcher;
    for (const normalizedRecord of normalizedRecords) {
      const { path } = normalizedRecord;
      if (parent && path[0] !== "/") {
        const parentPath = parent.record.path;
        const connectingSlash = parentPath[parentPath.length - 1] === "/" ? "" : "/";
        normalizedRecord.path = parent.record.path + (path && connectingSlash + path);
      }
      if (normalizedRecord.path === "*") throw new Error('Catch all routes ("*") must now be defined using a param with a custom regexp.\nSee more at https://router.vuejs.org/guide/migration/#Removed-star-or-catch-all-routes.');
      matcher = createRouteRecordMatcher(normalizedRecord, parent, options);
      if (parent && path[0] === "/") checkMissingParamsInAbsolutePath(matcher, parent);
      if (originalRecord) {
        originalRecord.alias.push(matcher);
        checkSameParams(originalRecord, matcher);
      } else {
        originalMatcher = originalMatcher || matcher;
        if (originalMatcher !== matcher) originalMatcher.alias.push(matcher);
        if (isRootAdd && record.name && !isAliasRecord(matcher)) {
          checkSameNameAsAncestor(record, parent);
          removeRoute(record.name);
        }
      }
      if (isMatchable(matcher)) insertMatcher(matcher);
      if (mainNormalizedRecord.children) {
        const children = mainNormalizedRecord.children;
        for (let i = 0; i < children.length; i++) addRoute(children[i], matcher, originalRecord && originalRecord.children[i]);
      }
      originalRecord = originalRecord || matcher;
    }
    return originalMatcher ? () => {
      removeRoute(originalMatcher);
    } : noop$1;
  }
  function removeRoute(matcherRef) {
    if (isRouteName(matcherRef)) {
      const matcher = matcherMap.get(matcherRef);
      if (matcher) {
        matcherMap.delete(matcherRef);
        matchers.splice(matchers.indexOf(matcher), 1);
        matcher.children.forEach(removeRoute);
        matcher.alias.forEach(removeRoute);
      }
    } else {
      const index = matchers.indexOf(matcherRef);
      if (index > -1) {
        matchers.splice(index, 1);
        if (matcherRef.record.name) matcherMap.delete(matcherRef.record.name);
        matcherRef.children.forEach(removeRoute);
        matcherRef.alias.forEach(removeRoute);
      }
    }
  }
  function getRoutes2() {
    return matchers;
  }
  function insertMatcher(matcher) {
    const index = findInsertionIndex(matcher, matchers);
    matchers.splice(index, 0, matcher);
    if (matcher.record.name && !isAliasRecord(matcher)) matcherMap.set(matcher.record.name, matcher);
  }
  function resolve(location2, currentLocation) {
    let matcher;
    let params = {};
    let path;
    let name;
    if ("name" in location2 && location2.name) {
      matcher = matcherMap.get(location2.name);
      if (!matcher) throw createRouterError(ErrorTypes.MATCHER_NOT_FOUND, { location: location2 });
      {
        const invalidParams = Object.keys(location2.params || {}).filter((paramName) => !matcher.keys.find((k2) => k2.name === paramName));
        if (invalidParams.length) warn$1(`Discarded invalid param(s) "${invalidParams.join('", "')}" when navigating. See https://github.com/vuejs/router/blob/main/packages/router/CHANGELOG.md#414-2022-08-22 for more details.`);
      }
      name = matcher.record.name;
      params = assign(pickParams(currentLocation.params, matcher.keys.filter((k2) => !k2.optional).concat(matcher.parent ? matcher.parent.keys.filter((k2) => k2.optional) : []).map((k2) => k2.name)), location2.params && pickParams(location2.params, matcher.keys.map((k2) => k2.name)));
      path = matcher.stringify(params);
    } else if (location2.path != null) {
      path = location2.path;
      if (!path.startsWith("/")) warn$1(`The Matcher cannot resolve relative paths but received "${path}". Unless you directly called \`matcher.resolve("${path}")\`, this is probably a bug in vue-router. Please open an issue at https://github.com/vuejs/router/issues/new/choose.`);
      matcher = matchers.find((m2) => m2.re.test(path));
      if (matcher) {
        params = matcher.parse(path);
        name = matcher.record.name;
      }
    } else {
      matcher = currentLocation.name ? matcherMap.get(currentLocation.name) : matchers.find((m2) => m2.re.test(currentLocation.path));
      if (!matcher) throw createRouterError(ErrorTypes.MATCHER_NOT_FOUND, {
        location: location2,
        currentLocation
      });
      name = matcher.record.name;
      params = assign({}, currentLocation.params, location2.params);
      path = matcher.stringify(params);
    }
    const matched = [];
    let parentMatcher = matcher;
    while (parentMatcher) {
      matched.unshift(parentMatcher.record);
      parentMatcher = parentMatcher.parent;
    }
    return {
      name,
      path,
      params,
      matched,
      meta: mergeMetaFields(matched)
    };
  }
  routes.forEach((route) => addRoute(route));
  function clearRoutes() {
    matchers.length = 0;
    matcherMap.clear();
  }
  return {
    addRoute,
    resolve,
    removeRoute,
    clearRoutes,
    getRoutes: getRoutes2,
    getRecordMatcher
  };
}
function pickParams(params, keys) {
  const newParams = {};
  for (const key of keys) if (key in params) newParams[key] = params[key];
  return newParams;
}
function normalizeRouteRecord(record) {
  const normalized = {
    path: record.path,
    redirect: record.redirect,
    name: record.name,
    meta: record.meta || {},
    aliasOf: record.aliasOf,
    beforeEnter: record.beforeEnter,
    props: normalizeRecordProps(record),
    children: record.children || [],
    instances: {},
    leaveGuards: /* @__PURE__ */ new Set(),
    updateGuards: /* @__PURE__ */ new Set(),
    enterCallbacks: {},
    components: "components" in record ? record.components || null : record.component && { default: record.component }
  };
  Object.defineProperty(normalized, "mods", { value: {} });
  return normalized;
}
function normalizeRecordProps(record) {
  const propsObject = {};
  const props = record.props || false;
  if ("component" in record) propsObject.default = props;
  else for (const name in record.components) propsObject[name] = typeof props === "object" ? props[name] : props;
  return propsObject;
}
function isAliasRecord(record) {
  while (record) {
    if (record.record.aliasOf) return true;
    record = record.parent;
  }
  return false;
}
function mergeMetaFields(matched) {
  return matched.reduce((meta, record) => assign(meta, record.meta), {});
}
function isSameParam(a, b) {
  return a.name === b.name && a.optional === b.optional && a.repeatable === b.repeatable;
}
function checkSameParams(a, b) {
  for (const key of a.keys) if (!key.optional && !b.keys.find(isSameParam.bind(null, key))) return warn$1(`Alias "${b.record.path}" and the original record: "${a.record.path}" must have the exact same param named "${key.name}"`);
  for (const key of b.keys) if (!key.optional && !a.keys.find(isSameParam.bind(null, key))) return warn$1(`Alias "${b.record.path}" and the original record: "${a.record.path}" must have the exact same param named "${key.name}"`);
}
function checkChildMissingNameWithEmptyPath(mainNormalizedRecord, parent) {
  if (parent && parent.record.name && !mainNormalizedRecord.name && !mainNormalizedRecord.path && mainNormalizedRecord.children.length === 0) warn$1(`The route named "${String(parent.record.name)}" has a child without a name, an empty path, and no children. This is probably a mistake: using that name won't render the empty path child so you probably want to move the name to the child instead. If this is intentional, add a name to the child route to silence the warning.`);
}
function checkSameNameAsAncestor(record, parent) {
  for (let ancestor = parent; ancestor; ancestor = ancestor.parent) if (ancestor.record.name === record.name) throw new Error(`A route named "${String(record.name)}" has been added as a ${parent === ancestor ? "child" : "descendant"} of a route with the same name. Route names must be unique and a nested route cannot use the same name as an ancestor.`);
}
function checkMissingParamsInAbsolutePath(record, parent) {
  for (const key of parent.keys) if (!record.keys.find(isSameParam.bind(null, key))) return warn$1(`Absolute path "${record.record.path}" must have the exact same param named "${key.name}" as its parent "${parent.record.path}".`);
}
function findInsertionIndex(matcher, matchers) {
  let lower = 0;
  let upper = matchers.length;
  while (lower !== upper) {
    const mid = lower + upper >> 1;
    if (comparePathParserScore(matcher, matchers[mid]) < 0) upper = mid;
    else lower = mid + 1;
  }
  const insertionAncestor = getInsertionAncestor(matcher);
  if (insertionAncestor) {
    upper = matchers.lastIndexOf(insertionAncestor, upper - 1);
    if (upper < 0) warn$1(`Finding ancestor route "${insertionAncestor.record.path}" failed for "${matcher.record.path}"`);
  }
  return upper;
}
function getInsertionAncestor(matcher) {
  let ancestor = matcher;
  while (ancestor = ancestor.parent) if (isMatchable(ancestor) && comparePathParserScore(matcher, ancestor) === 0) return ancestor;
}
function isMatchable({ record }) {
  return !!(record.name || record.components && Object.keys(record.components).length || record.redirect);
}
function useLink(props) {
  const router = inject(routerKey);
  const currentRoute = inject(routeLocationKey);
  let hasPrevious = false;
  let previousTo = null;
  const route = computed(() => {
    const to = unref(props.to);
    if (!hasPrevious || to !== previousTo) {
      if (!isRouteLocation(to)) if (hasPrevious) warn$1(`Invalid value for prop "to" in useLink()
- to:`, to, `
- previous to:`, previousTo, `
- props:`, props);
      else warn$1(`Invalid value for prop "to" in useLink()
- to:`, to, `
- props:`, props);
      previousTo = to;
      hasPrevious = true;
    }
    return router.resolve(to);
  });
  const activeRecordIndex = computed(() => {
    const { matched } = route.value;
    const { length } = matched;
    const routeMatched = matched[length - 1];
    const currentMatched = currentRoute.matched;
    if (!routeMatched || !currentMatched.length) return -1;
    const index = currentMatched.findIndex(isSameRouteRecord.bind(null, routeMatched));
    if (index > -1) return index;
    const parentRecordPath = getOriginalPath(matched[length - 2]);
    return length > 1 && getOriginalPath(routeMatched) === parentRecordPath && currentMatched[currentMatched.length - 1].path !== parentRecordPath ? currentMatched.findIndex(isSameRouteRecord.bind(null, matched[length - 2])) : index;
  });
  const isActive = computed(() => activeRecordIndex.value > -1 && includesParams(currentRoute.params, route.value.params));
  const isExactActive = computed(() => activeRecordIndex.value > -1 && activeRecordIndex.value === currentRoute.matched.length - 1 && isSameRouteLocationParams(currentRoute.params, route.value.params));
  function navigate(e = {}) {
    if (guardEvent(e)) {
      const p = router[unref(props.replace) ? "replace" : "push"](unref(props.to)).catch(noop$1);
      if (props.viewTransition && typeof document !== "undefined" && "startViewTransition" in document) document.startViewTransition(() => p);
      return p;
    }
    return Promise.resolve();
  }
  if (isBrowser$1) {
    const instance = getCurrentInstance();
    if (instance) {
      const linkContextDevtools = {
        route: route.value,
        isActive: isActive.value,
        isExactActive: isExactActive.value,
        error: null
      };
      instance.__vrl_devtools = instance.__vrl_devtools || [];
      instance.__vrl_devtools.push(linkContextDevtools);
      watchEffect(() => {
        linkContextDevtools.route = route.value;
        linkContextDevtools.isActive = isActive.value;
        linkContextDevtools.isExactActive = isExactActive.value;
        linkContextDevtools.error = isRouteLocation(unref(props.to)) ? null : 'Invalid "to" value';
      }, { flush: "post" });
    }
  }
  return {
    route,
    href: computed(() => route.value.href),
    isActive,
    isExactActive,
    navigate
  };
}
function preferSingleVNode(vnodes) {
  return vnodes.length === 1 ? vnodes[0] : vnodes;
}
const RouterLinkImpl = /* @__PURE__ */ defineComponent({
  name: "RouterLink",
  compatConfig: { MODE: 3 },
  props: {
    to: {
      type: [String, Object],
      required: true
    },
    replace: Boolean,
    activeClass: String,
    exactActiveClass: String,
    custom: Boolean,
    ariaCurrentValue: {
      type: String,
      default: "page"
    },
    viewTransition: Boolean
  },
  useLink,
  setup(props, { slots }) {
    const link = reactive(useLink(props));
    const { options } = inject(routerKey);
    const elClass = computed(() => ({
      [getLinkClass(props.activeClass, options.linkActiveClass, "router-link-active")]: link.isActive,
      [getLinkClass(props.exactActiveClass, options.linkExactActiveClass, "router-link-exact-active")]: link.isExactActive
    }));
    return () => {
      const children = slots.default && preferSingleVNode(slots.default(link));
      return props.custom ? children : h$1("a", {
        "aria-current": link.isExactActive ? props.ariaCurrentValue : null,
        href: link.href,
        onClick: link.navigate,
        class: elClass.value
      }, children);
    };
  }
});
const RouterLink = RouterLinkImpl;
function guardEvent(e) {
  if (e.metaKey || e.altKey || e.ctrlKey || e.shiftKey) return;
  if (e.defaultPrevented) return;
  if (e.button !== void 0 && e.button !== 0) return;
  if (e.currentTarget && e.currentTarget.getAttribute) {
    const target2 = e.currentTarget.getAttribute("target");
    if (/\b_blank\b/i.test(target2)) return;
  }
  if (e.preventDefault) e.preventDefault();
  return true;
}
function includesParams(outer, inner) {
  for (const key in inner) {
    const innerValue = inner[key];
    const outerValue = outer[key];
    if (typeof innerValue === "string") {
      if (innerValue !== outerValue) return false;
    } else if (!isArray$3(outerValue) || outerValue.length !== innerValue.length || innerValue.some((value, i) => value.valueOf() !== outerValue[i].valueOf())) return false;
  }
  return true;
}
function getOriginalPath(record) {
  return record ? record.aliasOf ? record.aliasOf.path : record.path : "";
}
const getLinkClass = (propClass, globalClass, defaultClass) => propClass != null ? propClass : globalClass != null ? globalClass : defaultClass;
const RouterViewImpl = /* @__PURE__ */ defineComponent({
  name: "RouterView",
  inheritAttrs: false,
  props: {
    name: {
      type: String,
      default: "default"
    },
    route: Object
  },
  compatConfig: { MODE: 3 },
  setup(props, { attrs, slots }) {
    warnDeprecatedUsage();
    const injectedRoute = inject(routerViewLocationKey);
    const routeToDisplay = computed(() => props.route || injectedRoute.value);
    const injectedDepth = inject(viewDepthKey, 0);
    const depth = computed(() => {
      let initialDepth = unref(injectedDepth);
      const { matched } = routeToDisplay.value;
      let matchedRoute;
      while ((matchedRoute = matched[initialDepth]) && !matchedRoute.components) initialDepth++;
      return initialDepth;
    });
    const matchedRouteRef = computed(() => routeToDisplay.value.matched[depth.value]);
    provide(viewDepthKey, computed(() => depth.value + 1));
    provide(matchedRouteKey, matchedRouteRef);
    provide(routerViewLocationKey, routeToDisplay);
    const viewRef = ref();
    watch(() => [
      viewRef.value,
      matchedRouteRef.value,
      props.name
    ], ([instance, to, name], [oldInstance, from, oldName]) => {
      if (to) {
        to.instances[name] = instance;
        if (from && from !== to && instance && instance === oldInstance) {
          if (!to.leaveGuards.size) to.leaveGuards = from.leaveGuards;
          if (!to.updateGuards.size) to.updateGuards = from.updateGuards;
        }
      }
      if (instance && to && (!from || !isSameRouteRecord(to, from) || !oldInstance)) (to.enterCallbacks[name] || []).forEach((callback) => callback(instance));
    }, { flush: "post" });
    return () => {
      const route = routeToDisplay.value;
      const currentName = props.name;
      const matchedRoute = matchedRouteRef.value;
      const ViewComponent = matchedRoute && matchedRoute.components[currentName];
      if (!ViewComponent) return normalizeSlot(slots.default, {
        Component: ViewComponent,
        route
      });
      const routePropsOption = matchedRoute.props[currentName];
      const routeProps = routePropsOption ? routePropsOption === true ? route.params : typeof routePropsOption === "function" ? routePropsOption(route) : routePropsOption : null;
      const onVnodeUnmounted = (vnode) => {
        if (vnode.component.isUnmounted) matchedRoute.instances[currentName] = null;
      };
      const component = h$1(ViewComponent, assign({}, routeProps, attrs, {
        onVnodeUnmounted,
        ref: viewRef
      }));
      if (isBrowser$1 && component.ref) {
        const info = {
          depth: depth.value,
          name: matchedRoute.name,
          path: matchedRoute.path,
          meta: matchedRoute.meta
        };
        (isArray$3(component.ref) ? component.ref.map((r) => r.i) : [component.ref.i]).forEach((instance) => {
          instance.__vrv_devtools = info;
        });
      }
      return normalizeSlot(slots.default, {
        Component: component,
        route
      }) || component;
    };
  }
});
function normalizeSlot(slot, data) {
  if (!slot) return null;
  const slotContent = slot(data);
  return slotContent.length === 1 ? slotContent[0] : slotContent;
}
const RouterView = RouterViewImpl;
function warnDeprecatedUsage() {
  const instance = getCurrentInstance();
  const parentName = instance.parent && instance.parent.type.name;
  const parentSubTreeType = instance.parent && instance.parent.subTree && instance.parent.subTree.type;
  if (parentName && (parentName === "KeepAlive" || parentName.includes("Transition")) && typeof parentSubTreeType === "object" && parentSubTreeType.name === "RouterView") {
    const comp = parentName === "KeepAlive" ? "keep-alive" : "transition";
    warn$1(`<router-view> can no longer be used directly inside <transition> or <keep-alive>.
Use slot props instead:

<router-view v-slot="{ Component }">
  <${comp}>
    <component :is="Component" />
  </${comp}>
</router-view>`);
  }
}
function createRouter(options) {
  const matcher = createRouterMatcher(options.routes, options);
  const parseQuery$1 = options.parseQuery || parseQuery;
  const stringifyQuery$1 = options.stringifyQuery || stringifyQuery;
  const routerHistory = options.history;
  if (!routerHistory) throw new Error('Provide the "history" option when calling "createRouter()": https://router.vuejs.org/api/interfaces/RouterOptions.html#history');
  const beforeGuards = useCallbacks();
  const beforeResolveGuards = useCallbacks();
  const afterGuards = useCallbacks();
  const currentRoute = shallowRef(START_LOCATION_NORMALIZED);
  let pendingLocation = START_LOCATION_NORMALIZED;
  if (isBrowser$1 && options.scrollBehavior && "scrollRestoration" in history) history.scrollRestoration = "manual";
  const normalizeParams = applyToParams.bind(null, (paramValue) => "" + paramValue);
  const encodeParams = applyToParams.bind(null, encodeParam);
  const decodeParams = applyToParams.bind(null, decode);
  function addRoute(parentOrRoute, route) {
    let parent;
    let record;
    if (isRouteName(parentOrRoute)) {
      parent = matcher.getRecordMatcher(parentOrRoute);
      if (!parent) warn$1(`Parent route "${String(parentOrRoute)}" not found when adding child route`, route);
      record = route;
    } else record = parentOrRoute;
    return matcher.addRoute(record, parent);
  }
  function removeRoute(name) {
    const recordMatcher = matcher.getRecordMatcher(name);
    if (recordMatcher) matcher.removeRoute(recordMatcher);
    else warn$1(`Cannot remove non-existent route "${String(name)}"`);
  }
  function getRoutes2() {
    return matcher.getRoutes().map((routeMatcher) => routeMatcher.record);
  }
  function hasRoute(name) {
    return !!matcher.getRecordMatcher(name);
  }
  function resolve(rawLocation, currentLocation) {
    currentLocation = assign({}, currentLocation || currentRoute.value);
    if (typeof rawLocation === "string") {
      const locationNormalized = parseURL(parseQuery$1, rawLocation, currentLocation.path);
      const matchedRoute2 = matcher.resolve({ path: locationNormalized.path }, currentLocation);
      const href2 = routerHistory.createHref(locationNormalized.fullPath);
      {
        if (href2.startsWith("//")) warn$1(`Location "${rawLocation}" resolved to "${href2}". A resolved location cannot start with multiple slashes.`);
        else if (!matchedRoute2.matched.length) warn$1(`No match found for location with path "${rawLocation}"`);
      }
      return assign(locationNormalized, matchedRoute2, {
        params: decodeParams(matchedRoute2.params),
        hash: decode(locationNormalized.hash),
        redirectedFrom: void 0,
        href: href2
      });
    }
    if (!isRouteLocation(rawLocation)) {
      warn$1(`router.resolve() was passed an invalid location. This will fail in production.
- Location:`, rawLocation);
      return resolve({});
    }
    let matcherLocation;
    if (rawLocation.path != null) {
      if ("params" in rawLocation && !("name" in rawLocation) && Object.keys(rawLocation.params).length) warn$1(`Path "${rawLocation.path}" was passed with params but they will be ignored. Use a named route alongside params instead.`);
      matcherLocation = assign({}, rawLocation, { path: parseURL(parseQuery$1, rawLocation.path, currentLocation.path).path });
    } else {
      const targetParams = assign({}, rawLocation.params);
      for (const key in targetParams) if (targetParams[key] == null) delete targetParams[key];
      matcherLocation = assign({}, rawLocation, { params: encodeParams(targetParams) });
      currentLocation.params = encodeParams(currentLocation.params);
    }
    const matchedRoute = matcher.resolve(matcherLocation, currentLocation);
    const hash = rawLocation.hash || "";
    if (hash && !hash.startsWith("#")) warn$1(`A \`hash\` should always start with the character "#". Replace "${hash}" with "#${hash}".`);
    matchedRoute.params = normalizeParams(decodeParams(matchedRoute.params));
    const fullPath = stringifyURL(stringifyQuery$1, assign({}, rawLocation, {
      hash: encodeHash(hash),
      path: matchedRoute.path
    }));
    const href = routerHistory.createHref(fullPath);
    {
      if (href.startsWith("//")) warn$1(`Location "${rawLocation}" resolved to "${href}". A resolved location cannot start with multiple slashes.`);
      else if (!matchedRoute.matched.length) warn$1(`No match found for location with path "${rawLocation.path != null ? rawLocation.path : rawLocation}"`);
    }
    return assign({
      fullPath,
      hash,
      query: stringifyQuery$1 === stringifyQuery ? normalizeQuery(rawLocation.query) : rawLocation.query || {}
    }, matchedRoute, {
      redirectedFrom: void 0,
      href
    });
  }
  function locationAsObject(to) {
    return typeof to === "string" ? parseURL(parseQuery$1, to, currentRoute.value.path) : assign({}, to);
  }
  function checkCanceledNavigation(to, from) {
    if (pendingLocation !== to) return createRouterError(ErrorTypes.NAVIGATION_CANCELLED, {
      from,
      to
    });
  }
  function push(to) {
    return pushWithRedirect(to);
  }
  function replace(to) {
    return push(assign(locationAsObject(to), { replace: true }));
  }
  function handleRedirectRecord(to, from) {
    const lastMatched = to.matched[to.matched.length - 1];
    if (lastMatched && lastMatched.redirect) {
      const { redirect } = lastMatched;
      let newTargetLocation = typeof redirect === "function" ? redirect(to, from) : redirect;
      if (typeof newTargetLocation === "string") {
        newTargetLocation = newTargetLocation.includes("?") || newTargetLocation.includes("#") ? newTargetLocation = locationAsObject(newTargetLocation) : { path: newTargetLocation };
        newTargetLocation.params = {};
      }
      if (newTargetLocation.path == null && !("name" in newTargetLocation)) {
        warn$1(`Invalid redirect found:
${JSON.stringify(newTargetLocation, null, 2)}
 when navigating to "${to.fullPath}". A redirect must contain a name or path. This will break in production.`);
        throw new Error("Invalid redirect");
      }
      return assign({
        query: to.query,
        hash: to.hash,
        params: newTargetLocation.path != null ? {} : to.params
      }, newTargetLocation);
    }
  }
  function pushWithRedirect(to, redirectedFrom) {
    const targetLocation = pendingLocation = resolve(to);
    const from = currentRoute.value;
    const data = to.state;
    const force = to.force;
    const replace2 = to.replace === true;
    const shouldRedirect = handleRedirectRecord(targetLocation, from);
    if (shouldRedirect) return pushWithRedirect(assign(locationAsObject(shouldRedirect), {
      state: typeof shouldRedirect === "object" ? assign({}, data, shouldRedirect.state) : data,
      force,
      replace: replace2
    }), redirectedFrom || targetLocation);
    const toLocation = targetLocation;
    toLocation.redirectedFrom = redirectedFrom;
    let failure;
    if (!force && isSameRouteLocation(stringifyQuery$1, from, targetLocation)) {
      failure = createRouterError(ErrorTypes.NAVIGATION_DUPLICATED, {
        to: toLocation,
        from
      });
      handleScroll(from, from, true, false);
    }
    return (failure ? Promise.resolve(failure) : navigate(toLocation, from)).catch((error) => isNavigationFailure(error) ? isNavigationFailure(error, ErrorTypes.NAVIGATION_GUARD_REDIRECT) ? error : markAsReady(error) : triggerError(error, toLocation, from)).then((failure2) => {
      if (failure2) {
        if (isNavigationFailure(failure2, ErrorTypes.NAVIGATION_GUARD_REDIRECT)) {
          if (isSameRouteLocation(stringifyQuery$1, resolve(failure2.to), toLocation) && redirectedFrom && (redirectedFrom._count = redirectedFrom._count ? redirectedFrom._count + 1 : 1) > 30) {
            warn$1(`Detected a possibly infinite redirection in a navigation guard when going from "${from.fullPath}" to "${toLocation.fullPath}". Aborting to avoid a Stack Overflow.
 Are you always returning a new location within a navigation guard? That would lead to this error. Only return when redirecting or aborting, that should fix this. This might break in production if not fixed.`);
            return Promise.reject(/* @__PURE__ */ new Error("Infinite redirect in navigation guard"));
          }
          return pushWithRedirect(assign({ replace: replace2 }, locationAsObject(failure2.to), {
            state: typeof failure2.to === "object" ? assign({}, data, failure2.to.state) : data,
            force
          }), redirectedFrom || toLocation);
        }
      } else failure2 = finalizeNavigation(toLocation, from, true, replace2, data);
      triggerAfterEach(toLocation, from, failure2);
      return failure2;
    });
  }
  function checkCanceledNavigationAndReject(to, from) {
    const error = checkCanceledNavigation(to, from);
    return error ? Promise.reject(error) : Promise.resolve();
  }
  function runWithContext(fn) {
    const app = installedApps.values().next().value;
    return app && typeof app.runWithContext === "function" ? app.runWithContext(fn) : fn();
  }
  function navigate(to, from) {
    let guards;
    const [leavingRecords, updatingRecords, enteringRecords] = extractChangingRecords(to, from);
    guards = extractComponentsGuards(leavingRecords.reverse(), "beforeRouteLeave", to, from);
    for (const record of leavingRecords) record.leaveGuards.forEach((guard) => {
      guards.push(guardToPromiseFn(guard, to, from));
    });
    const canceledNavigationCheck = checkCanceledNavigationAndReject.bind(null, to, from);
    guards.push(canceledNavigationCheck);
    return runGuardQueue(guards).then(() => {
      guards = [];
      for (const guard of beforeGuards.list()) guards.push(guardToPromiseFn(guard, to, from));
      guards.push(canceledNavigationCheck);
      return runGuardQueue(guards);
    }).then(() => {
      guards = extractComponentsGuards(updatingRecords, "beforeRouteUpdate", to, from);
      for (const record of updatingRecords) record.updateGuards.forEach((guard) => {
        guards.push(guardToPromiseFn(guard, to, from));
      });
      guards.push(canceledNavigationCheck);
      return runGuardQueue(guards);
    }).then(() => {
      guards = [];
      for (const record of enteringRecords) if (record.beforeEnter) if (isArray$3(record.beforeEnter)) for (const beforeEnter of record.beforeEnter) guards.push(guardToPromiseFn(beforeEnter, to, from));
      else guards.push(guardToPromiseFn(record.beforeEnter, to, from));
      guards.push(canceledNavigationCheck);
      return runGuardQueue(guards);
    }).then(() => {
      to.matched.forEach((record) => record.enterCallbacks = {});
      guards = extractComponentsGuards(enteringRecords, "beforeRouteEnter", to, from, runWithContext);
      guards.push(canceledNavigationCheck);
      return runGuardQueue(guards);
    }).then(() => {
      guards = [];
      for (const guard of beforeResolveGuards.list()) guards.push(guardToPromiseFn(guard, to, from));
      guards.push(canceledNavigationCheck);
      return runGuardQueue(guards);
    }).catch((err) => isNavigationFailure(err, ErrorTypes.NAVIGATION_CANCELLED) ? err : Promise.reject(err));
  }
  function triggerAfterEach(to, from, failure) {
    afterGuards.list().forEach((guard) => runWithContext(() => guard(to, from, failure)));
  }
  function finalizeNavigation(toLocation, from, isPush, replace2, data) {
    const error = checkCanceledNavigation(toLocation, from);
    if (error) return error;
    const isFirstNavigation = from === START_LOCATION_NORMALIZED;
    const state = !isBrowser$1 ? {} : history.state;
    if (isPush) if (replace2 || isFirstNavigation) routerHistory.replace(toLocation.fullPath, assign({ scroll: isFirstNavigation && state && state.scroll }, data));
    else routerHistory.push(toLocation.fullPath, data);
    currentRoute.value = toLocation;
    handleScroll(toLocation, from, isPush, isFirstNavigation);
    markAsReady();
  }
  let removeHistoryListener;
  function setupListeners() {
    if (removeHistoryListener) return;
    removeHistoryListener = routerHistory.listen((to, _from, info) => {
      if (!router.listening) return;
      const toLocation = resolve(to);
      const shouldRedirect = handleRedirectRecord(toLocation, router.currentRoute.value);
      if (shouldRedirect) {
        pushWithRedirect(assign(shouldRedirect, {
          replace: true,
          force: true
        }), toLocation).catch(noop$1);
        return;
      }
      pendingLocation = toLocation;
      const from = currentRoute.value;
      if (isBrowser$1) saveScrollPosition(getScrollKey(from.fullPath, info.delta), computeScrollPosition());
      navigate(toLocation, from).catch((error) => {
        if (isNavigationFailure(error, ErrorTypes.NAVIGATION_ABORTED | ErrorTypes.NAVIGATION_CANCELLED)) return error;
        if (isNavigationFailure(error, ErrorTypes.NAVIGATION_GUARD_REDIRECT)) {
          pushWithRedirect(assign(locationAsObject(error.to), { force: true }), toLocation).then((failure) => {
            if (isNavigationFailure(failure, ErrorTypes.NAVIGATION_ABORTED | ErrorTypes.NAVIGATION_DUPLICATED) && !info.delta && info.type === NavigationType.pop) routerHistory.go(-1, false);
          }).catch(noop$1);
          return Promise.reject();
        }
        if (info.delta) routerHistory.go(-info.delta, false);
        return triggerError(error, toLocation, from);
      }).then((failure) => {
        failure = failure || finalizeNavigation(toLocation, from, false);
        if (failure) {
          if (info.delta && !isNavigationFailure(failure, ErrorTypes.NAVIGATION_CANCELLED)) routerHistory.go(-info.delta, false);
          else if (info.type === NavigationType.pop && isNavigationFailure(failure, ErrorTypes.NAVIGATION_ABORTED | ErrorTypes.NAVIGATION_DUPLICATED)) routerHistory.go(-1, false);
        }
        triggerAfterEach(toLocation, from, failure);
      }).catch(noop$1);
    });
  }
  let readyHandlers = useCallbacks();
  let errorListeners = useCallbacks();
  let ready;
  function triggerError(error, to, from) {
    markAsReady(error);
    const list = errorListeners.list();
    if (list.length) list.forEach((handler) => handler(error, to, from));
    else {
      warn$1("uncaught error during route navigation:");
      console.error(error);
    }
    return Promise.reject(error);
  }
  function isReady() {
    if (ready && currentRoute.value !== START_LOCATION_NORMALIZED) return Promise.resolve();
    return new Promise((resolve2, reject) => {
      readyHandlers.add([resolve2, reject]);
    });
  }
  function markAsReady(err) {
    if (!ready) {
      ready = !err;
      setupListeners();
      readyHandlers.list().forEach(([resolve2, reject]) => err ? reject(err) : resolve2());
      readyHandlers.reset();
    }
    return err;
  }
  function handleScroll(to, from, isPush, isFirstNavigation) {
    const { scrollBehavior } = options;
    if (!isBrowser$1 || !scrollBehavior) return Promise.resolve();
    const scrollPosition = !isPush && getSavedScrollPosition(getScrollKey(to.fullPath, 0)) || (isFirstNavigation || !isPush) && history.state && history.state.scroll || null;
    return nextTick().then(() => scrollBehavior(to, from, scrollPosition)).then((position) => position && scrollToPosition(position)).catch((err) => triggerError(err, to, from));
  }
  const go = (delta) => routerHistory.go(delta);
  let started;
  const installedApps = /* @__PURE__ */ new Set();
  const router = {
    currentRoute,
    listening: true,
    addRoute,
    removeRoute,
    clearRoutes: matcher.clearRoutes,
    hasRoute,
    getRoutes: getRoutes2,
    resolve,
    options,
    push,
    replace,
    go,
    back: () => go(-1),
    forward: () => go(1),
    beforeEach: beforeGuards.add,
    beforeResolve: beforeResolveGuards.add,
    afterEach: afterGuards.add,
    onError: errorListeners.add,
    isReady,
    install(app) {
      app.component("RouterLink", RouterLink);
      app.component("RouterView", RouterView);
      app.config.globalProperties.$router = router;
      Object.defineProperty(app.config.globalProperties, "$route", {
        enumerable: true,
        get: () => unref(currentRoute)
      });
      if (isBrowser$1 && !started && currentRoute.value === START_LOCATION_NORMALIZED) {
        started = true;
        push(routerHistory.location).catch((err) => {
          warn$1("Unexpected error when starting the router:", err);
        });
      }
      const reactiveRoute = {};
      for (const key in START_LOCATION_NORMALIZED) Object.defineProperty(reactiveRoute, key, {
        get: () => currentRoute.value[key],
        enumerable: true
      });
      app.provide(routerKey, router);
      app.provide(routeLocationKey, shallowReactive(reactiveRoute));
      app.provide(routerViewLocationKey, currentRoute);
      const unmountApp = app.unmount;
      installedApps.add(app);
      app.unmount = function() {
        installedApps.delete(app);
        if (installedApps.size < 1) {
          pendingLocation = START_LOCATION_NORMALIZED;
          removeHistoryListener && removeHistoryListener();
          removeHistoryListener = null;
          currentRoute.value = START_LOCATION_NORMALIZED;
          started = false;
          ready = false;
        }
        unmountApp();
      };
      if (isBrowser$1 && true) addDevtools(app, router, matcher);
    }
  };
  function runGuardQueue(guards) {
    return guards.reduce((promise, guard) => promise.then(() => runWithContext(guard)), Promise.resolve());
  }
  return router;
}
const [majorVersion] = window.OC?.config?.version?.split(".") ?? [];
const isLegacy = Number.parseInt(majorVersion ?? "32") < 32;
const NC_FORM_BOX_CONTEXT_KEY = /* @__PURE__ */ Symbol.for("NcFormBox:context");
function useNcFormBox() {
  return inject(NC_FORM_BOX_CONTEXT_KEY, {
    isInFormBox: false,
    formBoxItemClass: void 0
  });
}
const _export_sfc$1 = (sfc, props) => {
  const target2 = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target2[key] = val;
  }
  return target2;
};
const _hoisted_1$a = { class: "button-vue__wrapper" };
const _hoisted_2$9 = { class: "button-vue__icon" };
const _hoisted_3$9 = { class: "button-vue__text" };
const _sfc_main$c = /* @__PURE__ */ defineComponent({
  __name: "NcButton",
  props: {
    alignment: { default: "center" },
    ariaLabel: { default: void 0 },
    disabled: { type: Boolean },
    download: { type: [String, Boolean], default: void 0 },
    href: { default: void 0 },
    pressed: { type: Boolean, default: void 0 },
    size: { default: "normal" },
    target: { default: "_self" },
    text: { default: void 0 },
    to: { default: void 0 },
    type: { default: "button" },
    variant: { default: "secondary" },
    wide: { type: Boolean }
  },
  emits: ["click", "update:pressed"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const { formBoxItemClass } = useNcFormBox();
    const hasVueRouterContext = inject(routerKey, null) !== null;
    const tag = computed(() => {
      if (hasVueRouterContext && props.to) {
        return "RouterLink";
      } else if (props.href) {
        return "a";
      } else {
        return "button";
      }
    });
    const hasPressedState = computed(() => tag.value === "button" && typeof props.pressed === "boolean");
    const variantWithPressed = computed(() => {
      if (props.pressed) {
        return "primary";
      }
      if (props.pressed === false && props.variant === "primary") {
        return "secondary";
      }
      return props.variant;
    });
    const isTertiaryVariant = computed(() => variantWithPressed.value.startsWith("tertiary"));
    const flexAlignment = computed(() => props.alignment.split("-")[0]);
    const isReverseAligned = computed(() => props.alignment.includes("-"));
    const getNcPopoverTriggerAttrs = inject("NcPopover:trigger:attrs", () => ({}), false);
    const ncPopoverTriggerAttrs = computed(() => getNcPopoverTriggerAttrs());
    const attrs = computed(() => {
      if (tag.value === "RouterLink") {
        return {
          to: props.to,
          activeClass: "active"
        };
      } else if (tag.value === "a") {
        return {
          href: props.href || "#",
          target: props.target,
          rel: "nofollow noreferrer noopener",
          download: props.download || void 0
        };
      } else if (tag.value === "button") {
        return {
          ...ncPopoverTriggerAttrs.value,
          "aria-pressed": props.pressed,
          type: props.type,
          disabled: props.disabled
        };
      }
      return void 0;
    });
    function onClick(event) {
      if (hasPressedState.value) {
        emit("update:pressed", !props.pressed);
      }
      emit("click", event);
    }
    return (_ctx, _cache) => {
      return openBlock(), createBlock(resolveDynamicComponent(tag.value), mergeProps({
        class: ["button-vue", [
          `button-vue--size-${_ctx.size}`,
          {
            [`button-vue--${variantWithPressed.value}`]: variantWithPressed.value,
            "button-vue--tertiary": isTertiaryVariant.value,
            "button-vue--wide": _ctx.wide,
            [`button-vue--${flexAlignment.value}`]: flexAlignment.value !== "center",
            "button-vue--reverse": isReverseAligned.value,
            "button-vue--legacy": unref(isLegacy)
          },
          unref(formBoxItemClass)
        ]],
        "aria-label": _ctx.ariaLabel
      }, attrs.value, { onClick }), {
        default: withCtx(() => [
          createBaseVNode("span", _hoisted_1$a, [
            createBaseVNode("span", _hoisted_2$9, [
              renderSlot(_ctx.$slots, "icon", {}, void 0, true)
            ]),
            createBaseVNode("span", _hoisted_3$9, [
              renderSlot(_ctx.$slots, "default", {}, () => [
                createTextVNode(toDisplayString(_ctx.text), 1)
              ], true)
            ])
          ])
        ]),
        _: 3
      }, 16, ["class", "aria-label"]);
    };
  }
});
const NcButton = /* @__PURE__ */ _export_sfc$1(_sfc_main$c, [["__scopeId", "data-v-06ad9b25"]]);
const _hoisted_1$9 = ["aria-hidden", "aria-label"];
const _hoisted_2$8 = {
  key: 0,
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
};
const _hoisted_3$8 = ["d"];
const _hoisted_4$7 = ["innerHTML"];
const _sfc_main$b = /* @__PURE__ */ defineComponent({
  __name: "NcIconSvgWrapper",
  props: {
    directional: { type: Boolean },
    inline: { type: Boolean },
    svg: { default: "" },
    name: { default: void 0 },
    path: { default: "" },
    size: { default: 20 }
  },
  setup(__props) {
    useCssVars((_ctx) => ({
      "fb515064": iconSize.value
    }));
    const props = __props;
    const iconSize = computed(() => typeof props.size === "number" ? `${props.size}px` : props.size);
    const cleanSvg = computed(() => {
      if (!props.svg || props.path) {
        return;
      }
      const svg2 = purify.sanitize(props.svg);
      const svgDocument = new DOMParser().parseFromString(svg2, "image/svg+xml");
      if (svgDocument.querySelector("parsererror")) {
        warn("SVG is not valid");
        return "";
      }
      if (svgDocument.documentElement.id) {
        svgDocument.documentElement.removeAttribute("id");
      }
      return svgDocument.documentElement.outerHTML;
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("span", {
        "aria-hidden": _ctx.name ? void 0 : "true",
        "aria-label": _ctx.name || void 0,
        class: normalizeClass(["icon-vue", {
          "icon-vue--directional": _ctx.directional,
          "icon-vue--inline": _ctx.inline
        }]),
        role: "img"
      }, [
        !cleanSvg.value ? (openBlock(), createElementBlock("svg", _hoisted_2$8, [
          createBaseVNode("path", { d: _ctx.path }, null, 8, _hoisted_3$8)
        ])) : (openBlock(), createElementBlock("span", {
          key: 1,
          innerHTML: cleanSvg.value
        }, null, 8, _hoisted_4$7))
      ], 10, _hoisted_1$9);
    };
  }
});
const NcIconSvgWrapper = /* @__PURE__ */ _export_sfc$1(_sfc_main$b, [["__scopeId", "data-v-aaedb1c3"]]);
const MOBILE_BREAKPOINT = 1024;
const MOBILE_SMALL_BREAKPOINT = MOBILE_BREAKPOINT / 2;
const isLessThanBreakpoint = (breakpoint) => document.documentElement.clientWidth < breakpoint;
const isMobile = ref(isLessThanBreakpoint(MOBILE_BREAKPOINT));
const isSmallMobile = ref(isLessThanBreakpoint(MOBILE_SMALL_BREAKPOINT));
window.addEventListener("resize", () => {
  isMobile.value = isLessThanBreakpoint(MOBILE_BREAKPOINT);
  isSmallMobile.value = isLessThanBreakpoint(MOBILE_SMALL_BREAKPOINT);
}, { passive: true });
function useIsMobile() {
  return readonly(isMobile);
}
/*!
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: GPL-3.0-or-later
 */
function formatRelativeTime(timestamp = Date.now(), opts = {}) {
  const options = {
    ignoreSeconds: false,
    language: getLanguage(),
    relativeTime: "long",
    ...opts
  };
  const date = new Date(timestamp);
  const formatter = new Intl.RelativeTimeFormat([options.language, getLanguage()], { numeric: "auto", style: options.relativeTime });
  const diff = date.getTime() - Date.now();
  const seconds = diff / 1e3;
  if (Math.abs(seconds) < 59.5) {
    return options.ignoreSeconds || formatter.format(Math.round(seconds), "second");
  }
  const minutes = seconds / 60;
  if (Math.abs(minutes) <= 59) {
    return formatter.format(Math.round(minutes), "minute");
  }
  const hours = minutes / 60;
  if (Math.abs(hours) < 23.5) {
    return formatter.format(Math.round(hours), "hour");
  }
  const days = hours / 24;
  if (Math.abs(days) < 6.5) {
    return formatter.format(Math.round(days), "day");
  }
  if (Math.abs(days) < 27.5) {
    const weeks = days / 7;
    return formatter.format(Math.round(weeks), "week");
  }
  const months = days / 30;
  const format = Math.abs(months) < 11 ? { month: options.relativeTime, day: "numeric" } : { year: options.relativeTime === "narrow" ? "2-digit" : "numeric", month: options.relativeTime };
  const dateTimeFormatter = new Intl.DateTimeFormat([options.language, getLanguage()], format);
  return dateTimeFormatter.format(date);
}
/*!
 * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: GPL-3.0-or-later
 */
class GettextWrapper {
  bundle;
  constructor(pluralFunction) {
    this.bundle = {
      pluralFunction,
      translations: {}
    };
  }
  /**
   * Append new translations to the wrapper.
   *
   * This is useful if translations should be added on demand,
   * e.g. depending on component usage.
   *
   * @param bundle - The new translation bundle to append
   */
  addTranslations(bundle) {
    const dict = Object.values(bundle.translations[""] ?? {}).map(({ msgid, msgid_plural: msgidPlural, msgstr }) => {
      if (msgidPlural !== void 0) {
        return [`_${msgid}_::_${msgidPlural}_`, msgstr];
      }
      return [msgid, msgstr[0]];
    });
    this.bundle.translations = {
      ...this.bundle.translations,
      ...Object.fromEntries(dict)
    };
  }
  /**
   * Get translated string (singular form), optionally with placeholders
   *
   * @param original original string to translate
   * @param placeholders map of placeholder key to value
   */
  gettext(original, placeholders = {}) {
    return translate("", original, placeholders, void 0, { bundle: this.bundle });
  }
  /**
   * Get translated string with plural forms
   *
   * @param singular Singular text form
   * @param plural Plural text form to be used if `count` requires it
   * @param count The number to insert into the text
   * @param placeholders optional map of placeholder key to value
   */
  ngettext(singular, plural, count, placeholders = {}) {
    return translatePlural("", singular, plural, count, placeholders, { bundle: this.bundle });
  }
}
class GettextBuilder {
  debug = false;
  language = "en";
  translations = {};
  setLanguage(language) {
    this.language = language;
    return this;
  }
  /**
   * Try to detect locale from context with `en` as fallback value
   * This only works within a Nextcloud page context.
   *
   * @deprecated use `detectLanguage` instead.
   */
  detectLocale() {
    return this.detectLanguage();
  }
  /**
   * Try to detect locale from context with `en` as fallback value.
   * This only works within a Nextcloud page context.
   */
  detectLanguage() {
    return this.setLanguage(getLanguage().replace("-", "_"));
  }
  /**
   * Register a new translation bundle for a specified language.
   *
   * Please note that existing translations for that language will be overwritten.
   *
   * @param language - Language this is the translation for
   * @param data - The translation bundle
   */
  addTranslation(language, data) {
    this.translations[language] = data;
    return this;
  }
  enableDebugMode() {
    this.debug = true;
    return this;
  }
  build() {
    if (this.debug) {
      console.debug(`Creating gettext instance for language ${this.language}`);
    }
    const wrapper = new GettextWrapper((n2) => getPlural(n2, this.language));
    if (this.language in this.translations) {
      wrapper.addTranslations(this.translations[this.language]);
    }
    return wrapper;
  }
}
function getGettextBuilder() {
  return new GettextBuilder();
}
/*!
 * SPDX-FileCopyrightText: Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
const gettext = getGettextBuilder().detectLanguage().build();
const n$1 = (...args) => gettext.ngettext(...args);
const t = (...args) => gettext.gettext(...args);
function register(...chunks) {
  for (const chunk of chunks) {
    if (chunk.registered) {
      continue;
    }
    for (const { l: language, t: translations } of chunk) {
      if (language !== getLanguage() || !translations) {
        continue;
      }
      const decompressed = Object.fromEntries(Object.entries(translations).map(([id, value]) => [
        id,
        {
          msgid: id,
          msgid_plural: value.p,
          msgstr: value.v
        }
      ]));
      gettext.addTranslations({
        translations: {
          "": decompressed
        }
      });
    }
    chunk.registered = true;
  }
}
const t2 = [{ "l": "ar", "t": { "a few seconds ago": { "v": ["منذ عدة ثوانٍ"] }, "sec. ago": { "v": ["ثانية مضت"] }, "seconds ago": { "v": ["ثوانٍ مضت"] } } }, { "l": "ast", "t": { "a few seconds ago": { "v": ["hai unos segundos"] }, "sec. ago": { "v": ["hai segs"] }, "seconds ago": { "v": ["hai segundos"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "a few seconds ago": { "v": ["před několika sekundami"] }, "sec. ago": { "v": ["sek. před"] }, "seconds ago": { "v": ["sekund předtím"] } } }, { "l": "cs-CZ", "t": { "a few seconds ago": { "v": ["před několika sekundami"] }, "sec. ago": { "v": ["sek. před"] }, "seconds ago": { "v": ["sekund předtím"] } } }, { "l": "da", "t": { "a few seconds ago": { "v": ["et par sekunder siden"] }, "sec. ago": { "v": ["sek. siden"] }, "seconds ago": { "v": ["sekunder siden"] } } }, { "l": "de", "t": { "a few seconds ago": { "v": ["vor ein paar Sekunden"] }, "sec. ago": { "v": ["Sek. zuvor"] }, "seconds ago": { "v": ["Sekunden zuvor"] } } }, { "l": "de-DE", "t": { "a few seconds ago": { "v": ["vor ein paar Sekunden"] }, "sec. ago": { "v": ["Sek. zuvor"] }, "seconds ago": { "v": ["Sekunden zuvor"] } } }, { "l": "el", "t": { "a few seconds ago": { "v": ["πριν λίγα δευτερόλεπτα"] }, "sec. ago": { "v": ["δευτ. πριν"] }, "seconds ago": { "v": ["δευτερόλεπτα πριν"] } } }, { "l": "en-GB", "t": { "a few seconds ago": { "v": ["a few seconds ago"] }, "sec. ago": { "v": ["sec. ago"] }, "seconds ago": { "v": ["seconds ago"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "a few seconds ago": { "v": ["hace unos pocos segundos"] }, "sec. ago": { "v": ["hace segundos"] }, "seconds ago": { "v": ["segundos atrás"] } } }, { "l": "es-AR", "t": { "a few seconds ago": { "v": ["hace unos segundos"] }, "sec. ago": { "v": ["seg. atrás"] }, "seconds ago": { "v": ["segundos atrás"] } } }, { "l": "es-EC", "t": { "a few seconds ago": { "v": ["hace unos segundos"] }, "sec. ago": { "v": ["hace segundos"] }, "seconds ago": { "v": ["Segundos atrás"] } } }, { "l": "es-MX", "t": { "a few seconds ago": { "v": ["hace unos segundos"] }, "sec. ago": { "v": ["seg. atrás"] }, "seconds ago": { "v": ["segundos atrás"] } } }, { "l": "et-EE", "t": { "a few seconds ago": { "v": ["mõni sekund tagasi"] }, "sec. ago": { "v": ["sek. tagasi"] }, "seconds ago": { "v": ["sekundit tagasi"] } } }, { "l": "eu", "t": { "a few seconds ago": { "v": ["duela segundo batzuk"] }, "sec. ago": { "v": ["duela seg."] }, "seconds ago": { "v": ["duela segundo"] } } }, { "l": "fa", "t": { "a few seconds ago": { "v": ["چند ثانیه پیش"] }, "sec. ago": { "v": ["چند ثانیه پیش"] }, "seconds ago": { "v": ["چند ثانیه پیش"] } } }, { "l": "fi", "t": { "a few seconds ago": { "v": ["muutamia sekunteja sitten"] }, "sec. ago": { "v": ["sek. sitten"] }, "seconds ago": { "v": ["sekunteja sitten"] } } }, { "l": "fr", "t": { "a few seconds ago": { "v": ["il y a quelques instants"] }, "sec. ago": { "v": ["il y a qq. sec."] }, "seconds ago": { "v": ["il y a quelques secondes"] } } }, { "l": "ga", "t": { "a few seconds ago": { "v": ["cúpla soicind ó shin"] }, "sec. ago": { "v": ["soic. ó shin"] }, "seconds ago": { "v": ["soicind ó shin"] } } }, { "l": "gl", "t": { "a few seconds ago": { "v": ["hai uns segundos"] }, "sec. ago": { "v": ["segs. atrás"] }, "seconds ago": { "v": ["segundos atrás"] } } }, { "l": "he", "t": { "a few seconds ago": { "v": ["לפני מספר שניות"] }, "sec. ago": { "v": ["לפני מספר שניות"] }, "seconds ago": { "v": ["לפני מס׳ שניות"] } } }, { "l": "hr", "t": { "a few seconds ago": { "v": ["prije nekoliko sekundi"] }, "sec. ago": { "v": ["prije nek. sek."] }, "seconds ago": { "v": ["prije nek. sek."] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "a few seconds ago": { "v": ["beberapa detik yang lalu"] }, "sec. ago": { "v": ["dtk. yang lalu"] }, "seconds ago": { "v": ["beberapa detik lalu"] } } }, { "l": "is", "t": { "a few seconds ago": { "v": ["fyrir örfáum sekúndum síðan"] }, "sec. ago": { "v": ["sek. síðan"] }, "seconds ago": { "v": ["sekúndum síðan"] } } }, { "l": "it", "t": { "a few seconds ago": { "v": ["pochi secondi fa"] }, "sec. ago": { "v": ["sec. fa"] }, "seconds ago": { "v": ["secondi fa"] } } }, { "l": "ja", "t": { "a few seconds ago": { "v": ["数秒前"] }, "sec. ago": { "v": ["秒前"] }, "seconds ago": { "v": ["数秒前"] } } }, { "l": "ja-JP", "t": { "a few seconds ago": { "v": ["数秒前"] }, "sec. ago": { "v": ["秒前"] }, "seconds ago": { "v": ["数秒前"] } } }, { "l": "ko", "t": { "a few seconds ago": { "v": ["방금 전"] }, "sec. ago": { "v": ["몇 초 전"] }, "seconds ago": { "v": ["초 전"] } } }, { "l": "lo", "t": { "a few seconds ago": { "v": ["ສອງສາມວິນາທີກ່ອນ"] }, "sec. ago": { "v": ["ວິ. ກ່ອນ"] }, "seconds ago": { "v": ["ວິນາທີກ່ອນ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "a few seconds ago": { "v": ["пред неколку секунди"] }, "sec. ago": { "v": ["секунда"] }, "seconds ago": { "v": ["секунди"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "a few seconds ago": { "v": ["noen få sekunder siden"] }, "sec. ago": { "v": ["sek. siden"] }, "seconds ago": { "v": ["sekunder siden"] } } }, { "l": "nl", "t": { "a few seconds ago": { "v": ["enkele seconden geleden"] }, "sec. ago": { "v": ["sec. geleden"] }, "seconds ago": { "v": ["seconden geleden"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "a few seconds ago": { "v": ["kilka sekund temu"] }, "sec. ago": { "v": ["sek. temu"] }, "seconds ago": { "v": ["sekund temu"] } } }, { "l": "pt-BR", "t": { "a few seconds ago": { "v": ["há alguns segundos"] }, "sec. ago": { "v": ["seg. atrás"] }, "seconds ago": { "v": ["segundos atrás"] } } }, { "l": "pt-PT", "t": { "a few seconds ago": { "v": ["há alguns segundos"] }, "sec. ago": { "v": ["seg. atrás"] }, "seconds ago": { "v": ["segundos atrás"] } } }, { "l": "ro", "t": { "a few seconds ago": { "v": ["acum câteva secunde"] }, "sec. ago": { "v": ["sec. în urmă"] }, "seconds ago": { "v": ["secunde în urmă"] } } }, { "l": "ru", "t": { "a few seconds ago": { "v": ["несколько секунд назад"] }, "sec. ago": { "v": ["сек. назад"] }, "seconds ago": { "v": ["секунд назад"] } } }, { "l": "sk", "t": { "a few seconds ago": { "v": ["pred chvíľou"] }, "sec. ago": { "v": ["pred pár sekundami"] }, "seconds ago": { "v": ["pred sekundami"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "a few seconds ago": { "v": ["пре неколико секунди"] }, "sec. ago": { "v": ["сек. раније"] }, "seconds ago": { "v": ["секунди раније"] } } }, { "l": "sv", "t": { "a few seconds ago": { "v": ["några sekunder sedan"] }, "sec. ago": { "v": ["sek. sedan"] }, "seconds ago": { "v": ["sekunder sedan"] } } }, { "l": "tr", "t": { "a few seconds ago": { "v": ["birkaç saniye önce"] }, "sec. ago": { "v": ["sn. önce"] }, "seconds ago": { "v": ["saniye önce"] } } }, { "l": "uk", "t": { "a few seconds ago": { "v": ["декілька секунд тому"] }, "sec. ago": { "v": ["с тому"] }, "seconds ago": { "v": ["с тому"] } } }, { "l": "uz", "t": { "a few seconds ago": { "v": ["bir necha soniya oldin"] }, "sec. ago": { "v": ["sek. oldin"] }, "seconds ago": { "v": ["soniyalar oldin"] } } }, { "l": "zh-CN", "t": { "a few seconds ago": { "v": ["几秒前"] }, "sec. ago": { "v": ["几秒前"] }, "seconds ago": { "v": ["几秒前"] } } }, { "l": "zh-HK", "t": { "a few seconds ago": { "v": ["幾秒前"] }, "sec. ago": { "v": ["秒前"] }, "seconds ago": { "v": ["秒前"] } } }, { "l": "zh-TW", "t": { "a few seconds ago": { "v": ["幾秒前"] }, "sec. ago": { "v": ["秒前"] }, "seconds ago": { "v": ["秒前"] } } }];
const t3 = [{ "l": "ar", "t": { "Acapulco": { "v": ["بازلائي مطفي"] }, "Blue Violet": { "v": ["بنفسجي مشعشع"] }, "Boston Blue": { "v": ["سماوي مطفي"] }, "Deluge": { "v": ["بنفسجي مطفي"] }, "Feldspar": { "v": ["وردي صخري"] }, "Gold": { "v": ["ذهبي"] }, "Mariner": { "v": ["أزرق بحري"] }, "Nextcloud blue": { "v": ["أزرق نكست كلاود"] }, "Olivine": { "v": ["زيتي"] }, "Purple": { "v": ["بنفسجي"] }, "Rosy brown": { "v": ["بُنِّي زهري"] }, "Whiskey": { "v": ["نبيذي"] } } }, { "l": "ast", "t": { "Acapulco": { "v": ["Acapulcu"] }, "Blue Violet": { "v": ["Viola azulao"] }, "Boston Blue": { "v": ["Azul Boston"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Oru"] }, "Mariner": { "v": ["Marineru"] }, "Nextcloud blue": { "v": ["Nextcloud azul"] }, "Olivine": { "v": ["Olivina"] }, "Purple": { "v": ["Moráu"] }, "Rosy brown": { "v": ["Marrón arrosao"] }, "Whiskey": { "v": ["Whiskey"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Acapulco": { "v": ["Akapulko"] }, "Black": { "v": ["Černá"] }, "Blue Violet": { "v": ["Modrofialová"] }, "Boston Blue": { "v": ["Bostonská modrá"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Živicová"] }, "Gold": { "v": ["Zlatá"] }, "Mariner": { "v": ["Námořnická"] }, "Nextcloud blue": { "v": ["Nextcloud modrá"] }, "Olivine": { "v": ["Olivínová"] }, "Purple": { "v": ["Fialová"] }, "Rosy brown": { "v": ["Růžovohnědá"] }, "Whiskey": { "v": ["Whisky"] }, "White": { "v": ["Bílá"] } } }, { "l": "cs-CZ", "t": { "Acapulco": { "v": ["Akapulko"] }, "Blue Violet": { "v": ["Modrofialová"] }, "Boston Blue": { "v": ["Bostonská modrá"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Živicová"] }, "Gold": { "v": ["Zlatá"] }, "Mariner": { "v": ["Námořnická"] }, "Nextcloud blue": { "v": ["Nextcloud modrá"] }, "Olivine": { "v": ["Olivínová"] }, "Purple": { "v": ["Fialová"] }, "Rosy brown": { "v": ["Růžovohnědá"] }, "Whiskey": { "v": ["Whisky"] } } }, { "l": "da", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Sort"] }, "Blue Violet": { "v": ["Blue Violet"] }, "Boston Blue": { "v": ["Boston Blue"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Guld"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["Nextcloud blue"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Lilla"] }, "Rosy brown": { "v": ["Rosy brown"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Hvid"] } } }, { "l": "de", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Schwarz"] }, "Blue Violet": { "v": ["Blau Violett"] }, "Boston Blue": { "v": ["Boston-Blau"] }, "Deluge": { "v": ["Sintflut"] }, "Feldspar": { "v": ["Feldspat"] }, "Gold": { "v": ["Gold"] }, "Mariner": { "v": ["Seemann"] }, "Nextcloud blue": { "v": ["Nextcloud Blau"] }, "Olivine": { "v": ["Olivin"] }, "Purple": { "v": ["Lila"] }, "Rosy brown": { "v": ["Rosiges Braun"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Weiß"] } } }, { "l": "de-DE", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Schwarz"] }, "Blue Violet": { "v": ["Blau Violett"] }, "Boston Blue": { "v": ["Boston-Blau"] }, "Deluge": { "v": ["Sintflut"] }, "Feldspar": { "v": ["Feldspat"] }, "Gold": { "v": ["Gold"] }, "Mariner": { "v": ["Seemann"] }, "Nextcloud blue": { "v": ["Nextcloud Blau"] }, "Olivine": { "v": ["Olivin"] }, "Purple": { "v": ["Lila"] }, "Rosy brown": { "v": ["Rosiges Braun"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Weiß"] } } }, { "l": "el", "t": { "Acapulco": { "v": ["Ακαπούλκο"] }, "Black": { "v": ["Μαύρο"] }, "Blue Violet": { "v": ["Μπλε Βιολέτ"] }, "Boston Blue": { "v": ["Μπλε Βοστώνης"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Χρυσό"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["Μπλε Nextcloud"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Μωβ"] }, "Rosy brown": { "v": ["Ροζ καφέ"] }, "Whiskey": { "v": ["Ουίσκι"] }, "White": { "v": ["Λευκό"] } } }, { "l": "en-GB", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Black"] }, "Blue Violet": { "v": ["Blue Violet"] }, "Boston Blue": { "v": ["Boston Blue"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Gold"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["Nextcloud blue"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Purple"] }, "Rosy brown": { "v": ["Rosy brown"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["White"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Violeta Azul"] }, "Boston Blue": { "v": ["Azul Boston"] }, "Deluge": { "v": ["Diluvio"] }, "Feldspar": { "v": ["Feldespato"] }, "Gold": { "v": ["Oro"] }, "Mariner": { "v": ["Marinero"] }, "Nextcloud blue": { "v": ["Azul Nextcloud"] }, "Olivine": { "v": ["Olivino"] }, "Purple": { "v": ["Púrpura"] }, "Rosy brown": { "v": ["Marrón rosáceo"] }, "Whiskey": { "v": ["Whiskey"] } } }, { "l": "es-AR", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Violeta Azul"] }, "Boston Blue": { "v": ["Azul Boston"] }, "Deluge": { "v": ["Diluvio"] }, "Feldspar": { "v": ["Feldespato"] }, "Gold": { "v": ["Oro"] }, "Mariner": { "v": ["Marinero"] }, "Nextcloud blue": { "v": ["Azul Nextcloud"] }, "Olivine": { "v": ["Olivino"] }, "Purple": { "v": ["Púrpura"] }, "Rosy brown": { "v": ["Marrón rosáceo"] }, "Whiskey": { "v": ["Whiskey"] } } }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Violeta Azul"] }, "Boston Blue": { "v": ["Azul Boston"] }, "Deluge": { "v": ["Diluvio"] }, "Feldspar": { "v": ["Feldespato"] }, "Gold": { "v": ["Oro"] }, "Mariner": { "v": ["Marinero"] }, "Nextcloud blue": { "v": ["Azul Nextcloud"] }, "Olivine": { "v": ["Olivino"] }, "Purple": { "v": ["Púrpura"] }, "Rosy brown": { "v": ["Marrón rosáceo"] }, "Whiskey": { "v": ["Whiskey"] } } }, { "l": "et-EE", "t": { "Acapulco": { "v": ["Acapulco meresinine"] }, "Black": { "v": ["Must"] }, "Blue Violet": { "v": ["Sinakasvioletne"] }, "Boston Blue": { "v": ["Bostoni rohekassinine"] }, "Deluge": { "v": ["Tulvavee lilla"] }, "Feldspar": { "v": ["Põlevkivipruun"] }, "Gold": { "v": ["Kuldne"] }, "Mariner": { "v": ["Meresinine"] }, "Nextcloud blue": { "v": ["Nextcloudi sinine"] }, "Olivine": { "v": ["Oliiviroheline"] }, "Purple": { "v": ["Purpurpunane"] }, "Rosy brown": { "v": ["Roosikarva pruun"] }, "Whiskey": { "v": ["Viskikarva kollakaspruun"] }, "White": { "v": ["Valge"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": { "Acapulco": { "v": ["آکاپولکو"] }, "Blue Violet": { "v": ["بنفش آبی"] }, "Boston Blue": { "v": ["آبی بوستونی"] }, "Deluge": { "v": ["سیل"] }, "Feldspar": { "v": ["فلدسپات"] }, "Gold": { "v": ["طلا"] }, "Mariner": { "v": ["مارینر"] }, "Nextcloud blue": { "v": ["نکس کلود آبی"] }, "Olivine": { "v": ["الیوین"] }, "Purple": { "v": ["بنفش"] }, "Rosy brown": { "v": ["قهوه‌ای رز"] }, "Whiskey": { "v": ["ویسکی"] } } }, { "l": "fi", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Sinivioletti"] }, "Boston Blue": { "v": ["Bostoninsininen"] }, "Deluge": { "v": ["Tulva"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Kulta"] }, "Mariner": { "v": ["Merenkulkija"] }, "Nextcloud blue": { "v": ["Nextcloudin sininen"] }, "Olivine": { "v": ["Oliviini"] }, "Purple": { "v": ["Purppura"] }, "Rosy brown": { "v": ["Ruusunruskea"] }, "Whiskey": { "v": ["Viski"] } } }, { "l": "fr", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Noir"] }, "Blue Violet": { "v": ["Bleu violet"] }, "Boston Blue": { "v": ["Bleu de Boston"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Doré"] }, "Mariner": { "v": ["Marin"] }, "Nextcloud blue": { "v": ["Bleu Nextcloud"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Violet"] }, "Rosy brown": { "v": ["Brun rosé"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Blanc"] } } }, { "l": "ga", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Dubh"] }, "Blue Violet": { "v": ["Gorm Violet"] }, "Boston Blue": { "v": ["Bostún Gorm"] }, "Deluge": { "v": ["Díle"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Óir"] }, "Mariner": { "v": ["Mairnéalach"] }, "Nextcloud blue": { "v": ["Nextcloud gorm"] }, "Olivine": { "v": ["Olaivín"] }, "Purple": { "v": ["Corcra"] }, "Rosy brown": { "v": ["Rosach donn"] }, "Whiskey": { "v": ["Fuisce"] }, "White": { "v": ["Bán"] } } }, { "l": "gl", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Negro"] }, "Blue Violet": { "v": ["Azul violeta"] }, "Boston Blue": { "v": ["Azul Boston"] }, "Deluge": { "v": ["Dioivo"] }, "Feldspar": { "v": ["Feldespato"] }, "Gold": { "v": ["Ouro"] }, "Mariner": { "v": ["Marino"] }, "Nextcloud blue": { "v": ["Azul Nextcloud"] }, "Olivine": { "v": ["Olivina"] }, "Purple": { "v": ["Púrpura"] }, "Rosy brown": { "v": ["Pardo rosado"] }, "Whiskey": { "v": ["Whisky"] }, "White": { "v": ["Branco"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Crna"] }, "Blue Violet": { "v": ["Plavoljubičasta"] }, "Boston Blue": { "v": ["Bostonsko plava"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Zlatna"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["Nextcloud plava"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Ljubičasta"] }, "Rosy brown": { "v": ["Ružičastosmeđa"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Bijela"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Hitam"] }, "Blue Violet": { "v": ["Ungu kebiruan"] }, "Boston Blue": { "v": ["Biru Boston"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Emas"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["Biru Nextcloud"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Ungu"] }, "Rosy brown": { "v": ["Cokelat kemerahan"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Putih"] } } }, { "l": "is", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Bláklukka"] }, "Boston Blue": { "v": ["Bostonblátt"] }, "Deluge": { "v": ["Fjólublátt"] }, "Feldspar": { "v": ["Feldspat"] }, "Gold": { "v": ["Gull"] }, "Mariner": { "v": ["Sjóarablátt"] }, "Nextcloud blue": { "v": ["Nextcloud blátt"] }, "Olivine": { "v": ["Ólivín"] }, "Purple": { "v": ["Purpurablátt"] }, "Rosy brown": { "v": ["Rósabrúnt"] }, "Whiskey": { "v": ["Viský"] } } }, { "l": "it", "t": { "Gold": { "v": ["Oro"] }, "Nextcloud blue": { "v": ["Nextcloud blue"] }, "Purple": { "v": ["Viola"] } } }, { "l": "ja", "t": { "Acapulco": { "v": ["アカプルコ"] }, "Black": { "v": ["黒"] }, "Blue Violet": { "v": ["ブルーバイオレット"] }, "Boston Blue": { "v": ["ボストンブルー"] }, "Deluge": { "v": ["豪雨"] }, "Feldspar": { "v": ["長石"] }, "Gold": { "v": ["黄金"] }, "Mariner": { "v": ["船乗り"] }, "Nextcloud blue": { "v": ["ネクストクラウド・ブルー"] }, "Olivine": { "v": ["カンラン石"] }, "Purple": { "v": ["紫色"] }, "Rosy brown": { "v": ["バラ色"] }, "Whiskey": { "v": ["ウイスキー"] }, "White": { "v": ["白"] } } }, { "l": "ja-JP", "t": { "Acapulco": { "v": ["アカプルコ"] }, "Blue Violet": { "v": ["ブルーバイオレット"] }, "Boston Blue": { "v": ["ボストンブルー"] }, "Deluge": { "v": ["豪雨"] }, "Feldspar": { "v": ["長石"] }, "Gold": { "v": ["黄金"] }, "Mariner": { "v": ["船乗り"] }, "Nextcloud blue": { "v": ["ネクストクラウド・ブルー"] }, "Olivine": { "v": ["カンラン石"] }, "Purple": { "v": ["紫色"] }, "Rosy brown": { "v": ["バラ色"] }, "Whiskey": { "v": ["ウイスキー"] } } }, { "l": "ko", "t": { "Acapulco": { "v": ["아카풀코"] }, "Black": { "v": ["검정"] }, "Blue Violet": { "v": ["푸른 보라"] }, "Boston Blue": { "v": ["보스턴 블루"] }, "Deluge": { "v": ["폭우"] }, "Feldspar": { "v": ["장석"] }, "Gold": { "v": ["금"] }, "Mariner": { "v": ["뱃사람"] }, "Nextcloud blue": { "v": ["Nextcloud 파랑"] }, "Olivine": { "v": ["감람석"] }, "Purple": { "v": ["보라"] }, "Rosy brown": { "v": ["로지 브라운"] }, "Whiskey": { "v": ["위스키"] }, "White": { "v": ["하양"] } } }, { "l": "lo", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["ສີດຳ"] }, "Blue Violet": { "v": ["Blue Violet"] }, "Boston Blue": { "v": ["Boston Blue"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["ສີຄຳ"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["ສີຟ້າ Nextcloud"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["ສີມ່ວງ"] }, "Rosy brown": { "v": ["Rosy brown"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["ສີຂາວ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Acapulco": { "v": ["Акапулко"] }, "Black": { "v": ["Црно"] }, "Blue Violet": { "v": ["Сино Виолетова"] }, "Boston Blue": { "v": ["Бостон Сина"] }, "Deluge": { "v": ["Делуџ"] }, "Feldspar": { "v": ["Фелдспар"] }, "Gold": { "v": ["Златна"] }, "Mariner": { "v": ["Маринер"] }, "Nextcloud blue": { "v": ["Nextcloud сина"] }, "Olivine": { "v": ["Оливин"] }, "Purple": { "v": ["Виолетова"] }, "Rosy brown": { "v": ["Розево-кафеава"] }, "Whiskey": { "v": ["Виски"] }, "White": { "v": ["Бела"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Blå fiolett"] }, "Boston Blue": { "v": ["Boston blå"] }, "Deluge": { "v": ["Syndflod"] }, "Feldspar": { "v": ["Feltspat"] }, "Gold": { "v": ["Gull"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["Nextcloud-blå"] }, "Olivine": { "v": ["Olivin"] }, "Purple": { "v": ["Lilla"] }, "Rosy brown": { "v": ["Rosenrød brun"] }, "Whiskey": { "v": ["Whiskey"] } } }, { "l": "nl", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Zwart"] }, "Blue Violet": { "v": ["Blauw Paars"] }, "Boston Blue": { "v": ["Boston Blauw"] }, "Deluge": { "v": ["Overlopen"] }, "Feldspar": { "v": ["Veldspaat"] }, "Gold": { "v": ["Goud"] }, "Mariner": { "v": ["Marineblauw"] }, "Nextcloud blue": { "v": ["Nextcloud blauw"] }, "Olivine": { "v": ["Olivijn"] }, "Purple": { "v": ["Paars"] }, "Rosy brown": { "v": ["Rozig bruin"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Wit"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Niebieski fiolet"] }, "Boston Blue": { "v": ["Błękit Bostonu"] }, "Deluge": { "v": ["Potop"] }, "Feldspar": { "v": ["Skaleń"] }, "Gold": { "v": ["Złote"] }, "Mariner": { "v": ["Marynarz"] }, "Nextcloud blue": { "v": ["Niebieskie Nextcloud"] }, "Olivine": { "v": ["Oliwin"] }, "Purple": { "v": ["Fioletowy"] }, "Rosy brown": { "v": ["Różowy brąz"] }, "Whiskey": { "v": ["Whisky"] } } }, { "l": "pt-BR", "t": { "Acapulco": { "v": ["Acapulco"] }, "Black": { "v": ["Preto"] }, "Blue Violet": { "v": ["Violeta Azul"] }, "Boston Blue": { "v": ["Azul Boston"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspato"] }, "Gold": { "v": ["Ouro"] }, "Mariner": { "v": ["Marinheiro"] }, "Nextcloud blue": { "v": ["Azul Nextcloud"] }, "Olivine": { "v": ["Olivina"] }, "Purple": { "v": ["Roxo"] }, "Rosy brown": { "v": ["Castanho rosado"] }, "Whiskey": { "v": ["Uísque"] }, "White": { "v": ["Branco"] } } }, { "l": "pt-PT", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Azul violeta"] }, "Boston Blue": { "v": ["Azul Boston"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Ouro"] }, "Mariner": { "v": ["Mariner"] }, "Nextcloud blue": { "v": ["Nextcloud azul"] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Púrpura"] }, "Rosy brown": { "v": ["Castanho rosado"] }, "Whiskey": { "v": ["Whiskey"] } } }, { "l": "ro", "t": { "Gold": { "v": ["Aur"] }, "Nextcloud blue": { "v": ["Nextcloud albastru"] }, "Purple": { "v": ["Purpuriu"] } } }, { "l": "ru", "t": { "Acapulco": { "v": ["Акапулько"] }, "Black": { "v": ["Черный"] }, "Blue Violet": { "v": ["Синий фиолет"] }, "Boston Blue": { "v": ["Синий Бостон"] }, "Deluge": { "v": ["Перламутрово-фиолетовый"] }, "Feldspar": { "v": ["Античная латунь"] }, "Gold": { "v": ["Золотой"] }, "Mariner": { "v": ["Морской"] }, "Nextcloud blue": { "v": ["Nextcloud голубой"] }, "Olivine": { "v": [" Оливковый"] }, "Purple": { "v": ["Фиолетовый"] }, "Rosy brown": { "v": ["Розово-коричневый"] }, "Whiskey": { "v": ["Виски"] }, "White": { "v": ["Белый"] } } }, { "l": "sk", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Modro fialová"] }, "Boston Blue": { "v": ["Bostonská modrá"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["Živec"] }, "Gold": { "v": ["Zlatá"] }, "Mariner": { "v": ["Námorník"] }, "Nextcloud blue": { "v": ["Nextcloud modrá"] }, "Olivine": { "v": ["Olivová"] }, "Purple": { "v": ["Fialová"] }, "Rosy brown": { "v": ["Ružovo hnedá"] }, "Whiskey": { "v": ["Whisky"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Acapulco": { "v": ["Акапулко"] }, "Black": { "v": ["Црно"] }, "Blue Violet": { "v": ["Плаво љубичаста"] }, "Boston Blue": { "v": ["Бостон плава"] }, "Deluge": { "v": ["Поплава"] }, "Feldspar": { "v": ["Фелдспар"] }, "Gold": { "v": ["Злато"] }, "Mariner": { "v": ["Морнар"] }, "Nextcloud blue": { "v": ["Nextcloud плава"] }, "Olivine": { "v": ["Маслинаста"] }, "Purple": { "v": ["Пурпурна"] }, "Rosy brown": { "v": ["Роси браон"] }, "Whiskey": { "v": ["Виски"] }, "White": { "v": ["Бело"] } } }, { "l": "sv", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["Blåviolett"] }, "Boston Blue": { "v": ["Bostonblå"] }, "Deluge": { "v": ["Skyfallsblå"] }, "Feldspar": { "v": ["Feldspat"] }, "Gold": { "v": ["Guld"] }, "Mariner": { "v": ["Marinblå"] }, "Nextcloud blue": { "v": ["Nextcloud-blå"] }, "Olivine": { "v": ["Olivin"] }, "Purple": { "v": ["Lila"] }, "Rosy brown": { "v": ["Rosabrun"] }, "Whiskey": { "v": ["Whisky"] } } }, { "l": "tr", "t": { "Acapulco": { "v": ["Akapulko"] }, "Black": { "v": ["Siyah"] }, "Blue Violet": { "v": ["Mavi mor"] }, "Boston Blue": { "v": ["Boston mavisi"] }, "Deluge": { "v": ["Sel"] }, "Feldspar": { "v": ["Feldispat"] }, "Gold": { "v": ["Altın"] }, "Mariner": { "v": ["Denizci"] }, "Nextcloud blue": { "v": ["Nextcloud mavi"] }, "Olivine": { "v": ["Zeytinlik"] }, "Purple": { "v": ["Mor"] }, "Rosy brown": { "v": ["Kırmızımsı kahverengi"] }, "Whiskey": { "v": ["Viski"] }, "White": { "v": ["Beyaz"] } } }, { "l": "uk", "t": { "Acapulco": { "v": ["Акапулько"] }, "Blue Violet": { "v": ["Блакитна фіалка"] }, "Boston Blue": { "v": ["Бостонський синій"] }, "Deluge": { "v": ["Злива"] }, "Feldspar": { "v": ["Польові шпати"] }, "Gold": { "v": ["Золотий"] }, "Mariner": { "v": ["Морський"] }, "Nextcloud blue": { "v": ["Блакитний Nextcloud"] }, "Olivine": { "v": ["Олива"] }, "Purple": { "v": ["Фіолетовий"] }, "Rosy brown": { "v": ["Темно-рожевий"] }, "Whiskey": { "v": ["Кола"] } } }, { "l": "uz", "t": { "Acapulco": { "v": ["Akapulko"] }, "Black": { "v": ["Qora"] }, "Blue Violet": { "v": ["Moviy binafsha"] }, "Boston Blue": { "v": ["Boston ko'k"] }, "Deluge": { "v": ["To'fon"] }, "Feldspar": { "v": ["Feldspar"] }, "Gold": { "v": ["Oltin"] }, "Mariner": { "v": ["Dengizchi"] }, "Nextcloud blue": { "v": ["Ko'k Nextcloud "] }, "Olivine": { "v": ["Olivine"] }, "Purple": { "v": ["Binafsha"] }, "Rosy brown": { "v": ["Qizil jigarrang"] }, "Whiskey": { "v": ["Whiskey"] }, "White": { "v": ["Oq"] } } }, { "l": "zh-CN", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["瓦罗兰特蓝"] }, "Boston Blue": { "v": ["波士顿蓝"] }, "Deluge": { "v": ["洪水色"] }, "Feldspar": { "v": ["长石"] }, "Gold": { "v": ["金色"] }, "Mariner": { "v": ["水手"] }, "Nextcloud blue": { "v": ["Nextcloud 蓝"] }, "Olivine": { "v": ["橄榄石色"] }, "Purple": { "v": ["紫色"] }, "Rosy brown": { "v": ["玫瑰棕色"] }, "Whiskey": { "v": ["威士忌"] } } }, { "l": "zh-HK", "t": { "Acapulco": { "v": ["阿卡普爾科"] }, "Black": { "v": ["黑色"] }, "Blue Violet": { "v": ["藍紫色"] }, "Boston Blue": { "v": ["波士頓藍"] }, "Deluge": { "v": ["大洪水"] }, "Feldspar": { "v": ["長石"] }, "Gold": { "v": ["Gold"] }, "Mariner": { "v": ["海軍藍"] }, "Nextcloud blue": { "v": ["Nextcloud 藍色"] }, "Olivine": { "v": ["橄欖石色"] }, "Purple": { "v": ["紫色"] }, "Rosy brown": { "v": ["玫瑰棕色"] }, "Whiskey": { "v": ["威士忌"] }, "White": { "v": ["白色"] } } }, { "l": "zh-TW", "t": { "Acapulco": { "v": ["Acapulco"] }, "Blue Violet": { "v": ["藍紫色"] }, "Boston Blue": { "v": ["波士頓藍"] }, "Deluge": { "v": ["Deluge"] }, "Feldspar": { "v": ["長石"] }, "Gold": { "v": ["金色"] }, "Mariner": { "v": ["海軍藍"] }, "Nextcloud blue": { "v": ["Nextcloud 藍色"] }, "Olivine": { "v": ["橄欖石色"] }, "Purple": { "v": ["紫色"] }, "Rosy brown": { "v": ["玫瑰棕色"] }, "Whiskey": { "v": ["威士忌"] } } }];
const t4 = [{ "l": "ar", "t": { "Actions": { "v": ["إجراءات"] } } }, { "l": "ast", "t": { "Actions": { "v": ["Aiciones"] } } }, { "l": "br", "t": { "Actions": { "v": ["Oberioù"] } } }, { "l": "ca", "t": { "Actions": { "v": ["Accions"] } } }, { "l": "cs", "t": { "Actions": { "v": ["Akce"] } } }, { "l": "cs-CZ", "t": { "Actions": { "v": ["Akce"] } } }, { "l": "da", "t": { "Actions": { "v": ["Handlinger"] } } }, { "l": "de", "t": { "Actions": { "v": ["Aktionen"] } } }, { "l": "de-DE", "t": { "Actions": { "v": ["Aktionen"] } } }, { "l": "el", "t": { "Actions": { "v": ["Ενέργειες"] } } }, { "l": "en-GB", "t": { "Actions": { "v": ["Actions"] } } }, { "l": "eo", "t": { "Actions": { "v": ["Agoj"] } } }, { "l": "es", "t": { "Actions": { "v": ["Acciones"] } } }, { "l": "es-AR", "t": { "Actions": { "v": ["Acciones"] } } }, { "l": "es-EC", "t": { "Actions": { "v": ["Acciones"] } } }, { "l": "es-MX", "t": { "Actions": { "v": ["Acciones"] } } }, { "l": "et-EE", "t": { "Actions": { "v": ["Tegevus"] } } }, { "l": "eu", "t": { "Actions": { "v": ["Ekintzak"] } } }, { "l": "fa", "t": { "Actions": { "v": ["کنش‌ها"] } } }, { "l": "fi", "t": { "Actions": { "v": ["Toiminnot"] } } }, { "l": "fr", "t": { "Actions": { "v": ["Actions"] } } }, { "l": "ga", "t": { "Actions": { "v": ["Gníomhartha"] } } }, { "l": "gl", "t": { "Actions": { "v": ["Accións"] } } }, { "l": "he", "t": { "Actions": { "v": ["פעולות"] } } }, { "l": "hr", "t": { "Actions": { "v": ["Radnje"] } } }, { "l": "hu", "t": { "Actions": { "v": ["Műveletek"] } } }, { "l": "id", "t": { "Actions": { "v": ["Tindakan"] } } }, { "l": "is", "t": { "Actions": { "v": ["Aðgerðir"] } } }, { "l": "it", "t": { "Actions": { "v": ["Azioni"] } } }, { "l": "ja", "t": { "Actions": { "v": ["操作"] } } }, { "l": "ja-JP", "t": { "Actions": { "v": ["操作"] } } }, { "l": "ko", "t": { "Actions": { "v": ["동작"] } } }, { "l": "lo", "t": { "Actions": { "v": ["ການກະທຳ"] } } }, { "l": "lt-LT", "t": { "Actions": { "v": ["Veiksmai"] } } }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Actions": { "v": ["Акции"] } } }, { "l": "my", "t": { "Actions": { "v": ["လုပ်ဆောင်ချက်များ"] } } }, { "l": "nb", "t": { "Actions": { "v": ["Handlinger"] } } }, { "l": "nl", "t": { "Actions": { "v": ["Acties"] } } }, { "l": "oc", "t": { "Actions": { "v": ["Accions"] } } }, { "l": "pl", "t": { "Actions": { "v": ["Działania"] } } }, { "l": "pt-BR", "t": { "Actions": { "v": ["Ações"] } } }, { "l": "pt-PT", "t": { "Actions": { "v": ["Ações"] } } }, { "l": "ro", "t": { "Actions": { "v": ["Acțiuni"] } } }, { "l": "ru", "t": { "Actions": { "v": ["Действия "] } } }, { "l": "sk", "t": { "Actions": { "v": ["Akcie"] } } }, { "l": "sl", "t": { "Actions": { "v": ["Dejanja"] } } }, { "l": "sr", "t": { "Actions": { "v": ["Радње"] } } }, { "l": "sv", "t": { "Actions": { "v": ["Åtgärder"] } } }, { "l": "tr", "t": { "Actions": { "v": ["İşlemler"] } } }, { "l": "uk", "t": { "Actions": { "v": ["Дії"] } } }, { "l": "uz", "t": { "Actions": { "v": ["Harakatlar"] } } }, { "l": "zh-CN", "t": { "Actions": { "v": ["行为"] } } }, { "l": "zh-HK", "t": { "Actions": { "v": ["動作"] } } }, { "l": "zh-TW", "t": { "Actions": { "v": ["動作"] } } }];
const t8 = [{ "l": "ar", "t": { "Any link": { "v": ["أيَّ رابط"] } } }, { "l": "ast", "t": { "Any link": { "v": ["Cualesquier enllaz"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Any link": { "v": ["Jakýkoli odkaz"] } } }, { "l": "cs-CZ", "t": { "Any link": { "v": ["Jakýkoli odkaz"] } } }, { "l": "da", "t": { "Any link": { "v": ["Ethvert link"] } } }, { "l": "de", "t": { "Any link": { "v": ["Irgendein Link"] } } }, { "l": "de-DE", "t": { "Any link": { "v": ["Irgendein Link"] } } }, { "l": "el", "t": { "Any link": { "v": ["Οποιοσδήποτε σύνδεσμος"] } } }, { "l": "en-GB", "t": { "Any link": { "v": ["Any link"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Any link": { "v": ["Cualquier enlace"] } } }, { "l": "es-AR", "t": { "Any link": { "v": ["Cualquier enlace"] } } }, { "l": "es-EC", "t": { "Any link": { "v": ["Cualquier enlace"] } } }, { "l": "es-MX", "t": { "Any link": { "v": ["Cualquier enlace"] } } }, { "l": "et-EE", "t": { "Any link": { "v": ["Mistahes link"] } } }, { "l": "eu", "t": { "Any link": { "v": ["Edozein esteka"] } } }, { "l": "fa", "t": { "Any link": { "v": ["هر پیوندی"] } } }, { "l": "fi", "t": { "Any link": { "v": ["Mikä tahansa linkki"] } } }, { "l": "fr", "t": { "Any link": { "v": ["N'importe quel lien"] } } }, { "l": "ga", "t": { "Any link": { "v": ["Aon nasc"] } } }, { "l": "gl", "t": { "Any link": { "v": ["Calquera ligazón"] } } }, { "l": "he", "t": { "Any link": { "v": ["קישור כלשהו"] } } }, { "l": "hr", "t": { "Any link": { "v": ["Bilo koja poveznica"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Any link": { "v": ["Semua tautan"] } } }, { "l": "is", "t": { "Any link": { "v": ["Einhver tengill"] } } }, { "l": "it", "t": { "Any link": { "v": ["Qualsiasi link"] } } }, { "l": "ja", "t": { "Any link": { "v": ["任意のリンク"] } } }, { "l": "ja-JP", "t": { "Any link": { "v": ["任意のリンク"] } } }, { "l": "ko", "t": { "Any link": { "v": ["아무 링크"] } } }, { "l": "lo", "t": { "Any link": { "v": ["ລິງໃດກໍໄດ້"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Any link": { "v": ["Секој линк"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Any link": { "v": ["Enhver lenke"] } } }, { "l": "nl", "t": { "Any link": { "v": ["Elke link"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Any link": { "v": ["Dowolny link"] } } }, { "l": "pt-BR", "t": { "Any link": { "v": ["Qualquer link"] } } }, { "l": "pt-PT", "t": { "Any link": { "v": ["Qualquer hiperligação"] } } }, { "l": "ro", "t": { "Any link": { "v": ["Orice link"] } } }, { "l": "ru", "t": { "Any link": { "v": ["Любая ссылка"] } } }, { "l": "sk", "t": { "Any link": { "v": ["Akýkoľvek odkaz"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Any link": { "v": ["Било који линк"] } } }, { "l": "sv", "t": { "Any link": { "v": ["Vilken länk som helst"] } } }, { "l": "tr", "t": { "Any link": { "v": ["Herhangi bir bağlantı"] } } }, { "l": "uk", "t": { "Any link": { "v": ["Будь-яке посилання"] } } }, { "l": "uz", "t": { "Any link": { "v": ["Har qanday havola"] } } }, { "l": "zh-CN", "t": { "Any link": { "v": ["任何链接"] } } }, { "l": "zh-HK", "t": { "Any link": { "v": ["任何連結"] } } }, { "l": "zh-TW", "t": { "Any link": { "v": ["任何連結"] } } }];
const t10 = [{ "l": "ar", "t": { "Avatar of {displayName}": { "v": ["صورة الملف الشخصي الرمزية لــ {displayName}  "] }, "Avatar of {displayName}, {status}": { "v": ["صورة الملف الشخصي الرمزية لــ {displayName}، {status}"] } } }, { "l": "ast", "t": { "Avatar of {displayName}": { "v": ["Avatar de: {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de: {displayName}, {status}"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "cs", "t": { "Avatar of {displayName}": { "v": ["Zástupný obrázek uživatele {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Zástupný obrázek uživatele {displayName}, {status}"] } } }, { "l": "cs-CZ", "t": { "Avatar of {displayName}": { "v": ["Zástupný obrázek uživatele {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Zástupný obrázek uživatele {displayName}, {status}"] } } }, { "l": "da", "t": { "Avatar of {displayName}": { "v": ["Avatar af {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar af {displayName}, {status}"] } } }, { "l": "de", "t": { "Avatar of {displayName}": { "v": ["Avatar von {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar von {displayName}, {status}"] } } }, { "l": "de-DE", "t": { "Avatar of {displayName}": { "v": ["Avatar von {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar von {displayName}, {status}"] } } }, { "l": "el", "t": { "Avatar of {displayName}": { "v": ["Άβαταρ του {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Άβαταρ του {displayName}, {status}"] } } }, { "l": "en-GB", "t": { "Avatar of {displayName}": { "v": ["Avatar of {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar of {displayName}, {status}"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "es-AR", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "es-EC", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "es-MX", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "et-EE", "t": { "Avatar of {displayName}": { "v": ["Avatar {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar {displayName}, {status}"] } } }, { "l": "eu", "t": { "Avatar of {displayName}": { "v": ["{displayName}-(e)n irudia"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName} -(e)n irudia, {status}"] } } }, { "l": "fa", "t": { "Avatar of {displayName}": { "v": ["آواتار {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["آواتار {displayName} ، {status}"] } } }, { "l": "fi", "t": { "Avatar of {displayName}": { "v": ["{displayName}n avatar"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}n avatar, {status}"] } } }, { "l": "fr", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "ga", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "gl", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "he", "t": { "Avatar of {displayName}": { "v": ["תמונה ייצוגית של {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["תמונה ייצוגית של {displayName}, {status}"] } } }, { "l": "hr", "t": { "Avatar of {displayName}": { "v": ["Avatar od {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar od {displayName}, {status}"] } } }, { "l": "hu", "t": { "Avatar of {displayName}": { "v": ["{displayName} profilképe"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName} profilképe, {status}"] } } }, { "l": "id", "t": { "Avatar of {displayName}": { "v": ["Avatar {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar {displayName}, {status}"] } } }, { "l": "is", "t": { "Avatar of {displayName}": { "v": ["Auðkennismynd fyrir {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Auðkennismynd fyrir {displayName}, {status}"] } } }, { "l": "it", "t": { "Avatar of {displayName}": { "v": ["Avatar di {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar di {displayName}, {status}"] } } }, { "l": "ja", "t": { "Avatar of {displayName}": { "v": ["{displayName} のアバター"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}, {status} のアバター"] } } }, { "l": "ja-JP", "t": { "Avatar of {displayName}": { "v": ["{displayName} のアバター"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}, {status} のアバター"] } } }, { "l": "ko", "t": { "Avatar of {displayName}": { "v": ["{displayName}님의 아바타"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}, {status}님의 아바타"] } } }, { "l": "lo", "t": { "Avatar of {displayName}": { "v": ["ຮູບແທນຕົວຂອງ {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["ຮູບແທນຕົວຂອງ {displayName}, {status}"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Avatar of {displayName}": { "v": ["Аватар на {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Аватар на {displayName}, {status}"] } } }, { "l": "my", "t": { "Avatar of {displayName}": { "v": ["{displayName} ၏ ကိုယ်ပွား"] } } }, { "l": "nb", "t": { "Avatar of {displayName}": { "v": ["Avataren til {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}'s avatar, {status}"] } } }, { "l": "nl", "t": { "Avatar of {displayName}": { "v": ["Avatar van {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar van {displayName}, {status}"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Avatar of {displayName}": { "v": ["Awatar {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Awatar {displayName}, {status}"] } } }, { "l": "pt-BR", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "pt-PT", "t": { "Avatar of {displayName}": { "v": ["Avatar de {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar de {displayName}, {status}"] } } }, { "l": "ro", "t": { "Avatar of {displayName}": { "v": ["Avatarul lui {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatarul lui {displayName}, {status}"] } } }, { "l": "ru", "t": { "Avatar of {displayName}": { "v": ["Аватар {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Фотография {displayName}, {status}"] } } }, { "l": "sk", "t": { "Avatar of {displayName}": { "v": ["Avatar {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar {displayName}, {status}"] } } }, { "l": "sl", "t": { "Avatar of {displayName}": { "v": ["Podoba {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Prikazna slika {displayName}, {status}"] } } }, { "l": "sr", "t": { "Avatar of {displayName}": { "v": ["Аватар за {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Avatar za {displayName}, {status}"] } } }, { "l": "sv", "t": { "Avatar of {displayName}": { "v": ["{displayName}s avatar"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}s avatar, {status}"] } } }, { "l": "tr", "t": { "Avatar of {displayName}": { "v": ["{displayName} avatarı"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}, {status} avatarı"] } } }, { "l": "uk", "t": { "Avatar of {displayName}": { "v": ["Аватар {displayName}"] }, "Avatar of {displayName}, {status}": { "v": ["Аватар {displayName}, {status}"] } } }, { "l": "uz", "t": { "Avatar of {displayName}": { "v": [" {displayName}Avatari"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}, {status} Avatari"] } } }, { "l": "zh-CN", "t": { "Avatar of {displayName}": { "v": ["{displayName}的头像"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}的头像，{status}"] } } }, { "l": "zh-HK", "t": { "Avatar of {displayName}": { "v": ["{displayName} 的頭像"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName} 的頭像，{status}"] } } }, { "l": "zh-TW", "t": { "Avatar of {displayName}": { "v": ["{displayName} 的大頭照"] }, "Avatar of {displayName}, {status}": { "v": ["{displayName}, {status} 的大頭照"] } } }];
const t11 = [{ "l": "ar", "t": { "away": { "v": ["غير موجود"] }, "busy": { "v": ["مشغول"] }, "do not disturb": { "v": ["يُرجى عدم الإزعاج"] }, "invisible": { "v": ["غير مرئي"] }, "offline": { "v": ["غير متصل"] }, "online": { "v": ["متصل"] } } }, { "l": "ast", "t": { "away": { "v": ["ausente"] }, "busy": { "v": ["ocupáu"] }, "do not disturb": { "v": ["nun molestar"] }, "invisible": { "v": ["invisible"] }, "offline": { "v": ["desconectáu"] }, "online": { "v": ["en llinia"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "away": { "v": ["pryč"] }, "busy": { "v": ["zaneprádněn(a)"] }, "do not disturb": { "v": ["nerušit"] }, "invisible": { "v": ["neviditelné"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "cs-CZ", "t": { "away": { "v": ["pryč"] }, "busy": { "v": ["zaneprádněn(a)"] }, "do not disturb": { "v": ["nerušit"] }, "invisible": { "v": ["neviditelné"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "da", "t": { "away": { "v": ["væk"] }, "busy": { "v": ["optaget"] }, "do not disturb": { "v": ["forstyr ikke"] }, "invisible": { "v": ["usynlig"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "de", "t": { "away": { "v": ["Abwesend"] }, "busy": { "v": ["Beschäftigt"] }, "do not disturb": { "v": ["Bitte nicht stören"] }, "invisible": { "v": ["Unsichtbar"] }, "offline": { "v": ["Offline"] }, "online": { "v": ["Online"] } } }, { "l": "de-DE", "t": { "away": { "v": ["Abwesend"] }, "busy": { "v": ["Beschäftigt"] }, "do not disturb": { "v": ["Bitte nicht stören"] }, "invisible": { "v": ["Unsichtbar"] }, "offline": { "v": ["Offline"] }, "online": { "v": ["Online"] } } }, { "l": "el", "t": { "away": { "v": ["μακριά"] }, "busy": { "v": ["απασχολημένος"] }, "do not disturb": { "v": ["μην ενοχλείτε"] }, "invisible": { "v": ["αόρατο"] }, "offline": { "v": ["εκτός σύνδεσης"] }, "online": { "v": ["συνδεδεμένος"] } } }, { "l": "en-GB", "t": { "away": { "v": ["away"] }, "busy": { "v": ["busy"] }, "do not disturb": { "v": ["do not disturb"] }, "invisible": { "v": ["invisible"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "away": { "v": ["ausente"] }, "busy": { "v": ["ocupado"] }, "do not disturb": { "v": ["no molestar"] }, "invisible": { "v": ["invisible"] }, "offline": { "v": ["fuera de línea"] }, "online": { "v": ["en línea"] } } }, { "l": "es-AR", "t": { "away": { "v": ["ausente"] }, "busy": { "v": ["ocupado"] }, "do not disturb": { "v": ["no molestar"] }, "invisible": { "v": ["invisible"] }, "offline": { "v": ["desconectado"] }, "online": { "v": ["en línea"] } } }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": { "away": { "v": ["ausente"] }, "busy": { "v": ["ocupado"] }, "do not disturb": { "v": ["no molestar"] }, "invisible": { "v": ["invisible"] }, "offline": { "v": ["fuera de línea"] }, "online": { "v": ["en línea"] } } }, { "l": "et-EE", "t": { "away": { "v": ["eemal"] }, "busy": { "v": ["hõivatud"] }, "do not disturb": { "v": ["ära sega"] }, "invisible": { "v": ["nähtamatu"] }, "offline": { "v": ["pole võrgus"] }, "online": { "v": ["võrgus"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": { "away": { "v": ["دور از دستگاه"] }, "busy": { "v": ["مشغول"] }, "do not disturb": { "v": ["مزاحم نشوید"] }, "invisible": { "v": ["مخفی"] }, "offline": { "v": ["برون‌خط"] }, "online": { "v": ["برخط"] } } }, { "l": "fi", "t": { "away": { "v": ["poissa"] }, "busy": { "v": ["varattu"] }, "do not disturb": { "v": ["älä häiritse"] }, "invisible": { "v": ["näkymätön"] }, "offline": { "v": ["ei linjalla"] }, "online": { "v": ["linjalla"] } } }, { "l": "fr", "t": { "away": { "v": ["absent"] }, "busy": { "v": ["occupé"] }, "do not disturb": { "v": ["ne pas déranger"] }, "invisible": { "v": ["invisible"] }, "offline": { "v": ["hors ligne"] }, "online": { "v": ["en ligne"] } } }, { "l": "ga", "t": { "away": { "v": ["ar shiúl"] }, "busy": { "v": ["gnóthach"] }, "do not disturb": { "v": ["ná cur as"] }, "invisible": { "v": ["dofheicthe"] }, "offline": { "v": ["as líne"] }, "online": { "v": ["ar líne"] } } }, { "l": "gl", "t": { "away": { "v": ["ausente"] }, "busy": { "v": ["ocupado"] }, "do not disturb": { "v": ["non molestar"] }, "invisible": { "v": ["invisíbel"] }, "offline": { "v": ["desconectado"] }, "online": { "v": ["conectado"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "away": { "v": ["odsutan"] }, "busy": { "v": ["zauzet"] }, "do not disturb": { "v": ["ne smetaj"] }, "invisible": { "v": ["nevidljiv"] }, "offline": { "v": ["izvan mreže"] }, "online": { "v": ["na mreži"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "away": { "v": ["tidak tersedia"] }, "busy": { "v": ["sibuk"] }, "do not disturb": { "v": ["jangan ganggu"] }, "invisible": { "v": ["tidak terlihat"] }, "offline": { "v": ["luring"] }, "online": { "v": ["daring"] } } }, { "l": "is", "t": { "away": { "v": ["í burtu"] }, "busy": { "v": ["upptekin/n"] }, "do not disturb": { "v": ["ekki ónáða"] }, "invisible": { "v": ["ósýnilegt"] }, "offline": { "v": ["ónettengt"] }, "online": { "v": ["nettengt"] } } }, { "l": "it", "t": { "away": { "v": ["via"] }, "do not disturb": { "v": ["non disturbare"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "ja", "t": { "away": { "v": ["離れる"] }, "busy": { "v": ["ビジー"] }, "do not disturb": { "v": ["邪魔をしないでください"] }, "invisible": { "v": ["不可視"] }, "offline": { "v": ["オフライン"] }, "online": { "v": ["オンライン"] } } }, { "l": "ja-JP", "t": { "away": { "v": ["離れる"] }, "busy": { "v": ["ビジー"] }, "do not disturb": { "v": ["邪魔をしないでください"] }, "invisible": { "v": ["不可視"] }, "offline": { "v": ["オフライン"] }, "online": { "v": ["オンライン"] } } }, { "l": "ko", "t": { "away": { "v": ["자리 비움"] }, "busy": { "v": ["바쁨"] }, "do not disturb": { "v": ["방해 금지"] }, "invisible": { "v": ["보이지 않음"] }, "offline": { "v": ["오프라인"] }, "online": { "v": ["온라인"] } } }, { "l": "lo", "t": { "away": { "v": ["ບໍ່ຢູ່"] }, "busy": { "v": ["ບໍ່ວ່າງ"] }, "do not disturb": { "v": ["ຫ້າມລົບກວນ"] }, "invisible": { "v": ["ບໍ່ສະແດງ"] }, "offline": { "v": ["ອອບໄລນ໌"] }, "online": { "v": ["ອອນໄລນ໌"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "away": { "v": ["оддалечен"] }, "busy": { "v": ["зафатен"] }, "do not disturb": { "v": ["не вознемирувај"] }, "invisible": { "v": ["невидливо"] }, "offline": { "v": ["офлајн"] }, "online": { "v": ["онлајн"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "away": { "v": ["borte"] }, "busy": { "v": ["opptatt"] }, "do not disturb": { "v": ["ikke forstyrr"] }, "invisible": { "v": ["usynlig"] }, "offline": { "v": ["frakoblet"] }, "online": { "v": ["tilkoblet"] } } }, { "l": "nl", "t": { "away": { "v": ["weg"] }, "busy": { "v": ["bezig"] }, "do not disturb": { "v": ["niet storen"] }, "invisible": { "v": ["Onzichtbaar"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "away": { "v": ["stąd"] }, "busy": { "v": ["zajęty"] }, "do not disturb": { "v": ["nie przeszkadzać"] }, "invisible": { "v": ["niewidzialny"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "pt-BR", "t": { "away": { "v": ["ausente"] }, "busy": { "v": ["ocupado"] }, "do not disturb": { "v": ["não perturbe"] }, "invisible": { "v": ["invisível"] }, "offline": { "v": ["off-line"] }, "online": { "v": ["on-line"] } } }, { "l": "pt-PT", "t": { "away": { "v": ["longe"] }, "busy": { "v": ["ocupado"] }, "do not disturb": { "v": ["não incomodar"] }, "invisible": { "v": ["invisível"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "ro", "t": { "away": { "v": ["plecat"] }, "do not disturb": { "v": ["nu deranjați"] }, "offline": { "v": ["deconectat"] }, "online": { "v": ["online"] } } }, { "l": "ru", "t": { "away": { "v": ["отсутствие"] }, "busy": { "v": ["занятый"] }, "do not disturb": { "v": ["не беспокоить"] }, "invisible": { "v": ["невидимый"] }, "offline": { "v": ["офлайн"] }, "online": { "v": ["онлайн"] } } }, { "l": "sk", "t": { "away": { "v": ["neprítomný"] }, "busy": { "v": ["zaneprázdnený"] }, "do not disturb": { "v": ["nerušiť"] }, "invisible": { "v": ["neviditeľný"] }, "offline": { "v": ["Odpojený - offline"] }, "online": { "v": ["Pripojený - online"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "away": { "v": ["одсутан"] }, "busy": { "v": ["заузет"] }, "do not disturb": { "v": ["не узнемиравај"] }, "invisible": { "v": ["невидљиво"] }, "offline": { "v": ["ван мреже"] }, "online": { "v": ["на мрежи"] } } }, { "l": "sv", "t": { "away": { "v": ["borta"] }, "busy": { "v": ["upptagen"] }, "do not disturb": { "v": ["stör ej"] }, "invisible": { "v": ["osynlig"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "tr", "t": { "away": { "v": ["Uzakta"] }, "busy": { "v": ["Meşgul"] }, "do not disturb": { "v": ["Rahatsız etmeyin"] }, "invisible": { "v": ["görünmez"] }, "offline": { "v": ["Çevrim dışı"] }, "online": { "v": ["Çevrim içi"] } } }, { "l": "uk", "t": { "away": { "v": ["відсутній"] }, "busy": { "v": ["зайнято"] }, "do not disturb": { "v": ["не турбувати"] }, "invisible": { "v": ["Невидимий"] }, "offline": { "v": ["не в мережі"] }, "online": { "v": ["в мережі"] } } }, { "l": "uz", "t": { "away": { "v": ["uzoqda"] }, "busy": { "v": ["band"] }, "do not disturb": { "v": ["bezovta qilmang"] }, "invisible": { "v": ["ko'rinmas"] }, "offline": { "v": ["offline"] }, "online": { "v": ["online"] } } }, { "l": "zh-CN", "t": { "away": { "v": ["离开"] }, "busy": { "v": ["繁忙"] }, "do not disturb": { "v": ["请勿打扰"] }, "invisible": { "v": ["隐藏的"] }, "offline": { "v": ["离线"] }, "online": { "v": ["在线"] } } }, { "l": "zh-HK", "t": { "away": { "v": ["離開"] }, "busy": { "v": ["忙碌"] }, "do not disturb": { "v": ["請勿打擾"] }, "invisible": { "v": ["隐藏的"] }, "offline": { "v": ["離線"] }, "online": { "v": ["在線"] } } }, { "l": "zh-TW", "t": { "away": { "v": ["離開"] }, "busy": { "v": ["忙碌"] }, "do not disturb": { "v": ["請勿打擾"] }, "invisible": { "v": ["不可見"] }, "offline": { "v": ["離線"] }, "online": { "v": ["線上"] } } }];
const t12 = [{ "l": "ar", "t": { "Back to provider selection": { "v": ["عودة إلى اختيار المزوّد"] }, "Close Smart Picker": { "v": ["إغلاق المحدد الذكي"] }, "Smart Picker": { "v": ["اللاقط الذكي smart picker"] } } }, { "l": "ast", "t": { "Back to provider selection": { "v": ["Volver a la seleición de fornidores"] }, "Close Smart Picker": { "v": ["Zarrar la seleición intelixente"] }, "Smart Picker": { "v": ["Selector intelixente"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Back to provider selection": { "v": ["Zpět na výběr poskytovatele"] }, "Close Smart Picker": { "v": ["Zavřít inteligentní výběr"] }, "Smart Picker": { "v": ["Inteligentní výběr"] } } }, { "l": "cs-CZ", "t": { "Back to provider selection": { "v": ["Zpět na výběr poskytovatele"] }, "Close Smart Picker": { "v": ["Zavřít inteligentní výběr"] }, "Smart Picker": { "v": ["Inteligentní výběr"] } } }, { "l": "da", "t": { "Back to provider selection": { "v": ["Tilbage til udbydervalg"] }, "Close Smart Picker": { "v": ["Luk Smart Vælger"] }, "Smart Picker": { "v": ["Smart Vælger"] } } }, { "l": "de", "t": { "Back to provider selection": { "v": ["Zurück zur Anbieterauswahl"] }, "Close Smart Picker": { "v": ["Smart Picker schließen"] }, "Smart Picker": { "v": ["Smart Picker"] } } }, { "l": "de-DE", "t": { "Back to provider selection": { "v": ["Zurück zur Anbieterauswahl"] }, "Close Smart Picker": { "v": ["Smart Picker schließen"] }, "Smart Picker": { "v": ["Smart Picker"] } } }, { "l": "el", "t": { "Back to provider selection": { "v": ["Επιστροφή στην επιλογή παρόχου"] }, "Close Smart Picker": { "v": ["Κλείσιμο Έξυπνης Επιλογής"] }, "Smart Picker": { "v": ["Έξυπνη Επιλογή"] } } }, { "l": "en-GB", "t": { "Back to provider selection": { "v": ["Back to provider selection"] }, "Close Smart Picker": { "v": ["Close Smart Picker"] }, "Smart Picker": { "v": ["Smart Picker"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Back to provider selection": { "v": ["Volver a la selección de proveedor"] }, "Close Smart Picker": { "v": ["Cerrar selector inteligente"] }, "Smart Picker": { "v": ["Selector inteligente"] } } }, { "l": "es-AR", "t": { "Back to provider selection": { "v": ["Volver a la selección de proveedor"] }, "Close Smart Picker": { "v": ["Cerrar selector inteligente"] }, "Smart Picker": { "v": ["Selector inteligente"] } } }, { "l": "es-EC", "t": { "Back to provider selection": { "v": ["Volver a la selección de proveedor"] }, "Close Smart Picker": { "v": ["Cerrar selector inteligente"] }, "Smart Picker": { "v": ["Selector inteligente"] } } }, { "l": "es-MX", "t": { "Back to provider selection": { "v": ["Volver a la selección de proveedor"] }, "Close Smart Picker": { "v": ["Cerrar selector inteligente"] }, "Smart Picker": { "v": ["Selector inteligente"] } } }, { "l": "et-EE", "t": { "Back to provider selection": { "v": ["Tagasi teenusepakkuja valiku juurde"] }, "Close Smart Picker": { "v": ["Sulge nutikas valija"] }, "Smart Picker": { "v": ["Nutikas valija"] } } }, { "l": "eu", "t": { "Back to provider selection": { "v": ["Itzuli hornitzaileen hautapenera"] }, "Close Smart Picker": { "v": ["Itxi hautatzaile adimenduna"] }, "Smart Picker": { "v": ["Hautatzaile adimenduna"] } } }, { "l": "fa", "t": { "Back to provider selection": { "v": ["بازگشت به انتخاب ارائه دهنده"] }, "Close Smart Picker": { "v": ["بستن انتخاب‌گر هوشمند"] }, "Smart Picker": { "v": ["انتخابگر هوشمند"] } } }, { "l": "fi", "t": { "Back to provider selection": { "v": ["Takaisin toimittajavalintaan"] }, "Close Smart Picker": { "v": ["Sulje älykas valitsin"] }, "Smart Picker": { "v": ["Älykäs valitsin"] } } }, { "l": "fr", "t": { "Back to provider selection": { "v": ["Revenir à la sélection du fournisseur"] }, "Close Smart Picker": { "v": ["Fermer le sélecteur intelligent"] }, "Smart Picker": { "v": ["Sélecteur intelligent"] } } }, { "l": "ga", "t": { "Back to provider selection": { "v": ["Ar ais go roghnú soláthróra"] }, "Close Smart Picker": { "v": ["Dún Piocálaí Cliste"] }, "Smart Picker": { "v": ["Roghnóir Cliste"] } } }, { "l": "gl", "t": { "Back to provider selection": { "v": ["Volver á selección do provedor"] }, "Close Smart Picker": { "v": ["Pechar o Selector intelixente"] }, "Smart Picker": { "v": ["Selector intelixente"] } } }, { "l": "he", "t": { "Back to provider selection": { "v": ["חזרה לבחירת ספק"] }, "Close Smart Picker": { "v": ["סגירת הבורר החכם"] }, "Smart Picker": { "v": ["בורר חכם"] } } }, { "l": "hr", "t": { "Back to provider selection": { "v": ["Natrag na odabir pružatelja"] }, "Close Smart Picker": { "v": ["Zatvori pametni odabir"] }, "Smart Picker": { "v": ["Pametni odabir"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Back to provider selection": { "v": ["Kembali ke pemilihan penyedia"] }, "Close Smart Picker": { "v": ["Tutup Pemilih Cerdas"] }, "Smart Picker": { "v": ["Pemilih Cerdas"] } } }, { "l": "is", "t": { "Back to provider selection": { "v": ["Til baka í val á þjónustuveitu"] }, "Close Smart Picker": { "v": ["Loka snjall-veljara"] }, "Smart Picker": { "v": ["Snjall-veljari"] } } }, { "l": "it", "t": { "Back to provider selection": { "v": ["Torna alla selezione del provider"] }, "Close Smart Picker": { "v": ["Chiudere lo Smart Picker"] }, "Smart Picker": { "v": ["Picker intelligente"] } } }, { "l": "ja", "t": { "Back to provider selection": { "v": ["プロバイダーの選択に戻る"] }, "Close Smart Picker": { "v": ["スマートピッカーを閉じる"] }, "Smart Picker": { "v": ["スマートピッカー"] } } }, { "l": "ja-JP", "t": { "Back to provider selection": { "v": ["プロバイダーの選択に戻る"] }, "Close Smart Picker": { "v": ["スマートピッカーを閉じる"] }, "Smart Picker": { "v": ["スマートピッカー"] } } }, { "l": "ko", "t": { "Back to provider selection": { "v": ["제공자 선택으로 돌아가기"] }, "Close Smart Picker": { "v": ["스마트 선택기 닫기"] }, "Smart Picker": { "v": ["스마트 선택기"] } } }, { "l": "lo", "t": { "Back to provider selection": { "v": ["ກັບໄປທີ່ການເລືອກຜູ້ໃຫ້ບໍລິການ"] }, "Close Smart Picker": { "v": ["ປິດໂຕເລືອກອັດສະລິຍະ"] }, "Smart Picker": { "v": ["ໂຕເລືອກອັດສະລິຍະ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Back to provider selection": { "v": ["Назад до избор на провајдер"] }, "Close Smart Picker": { "v": ["Затвори паметен избирач"] }, "Smart Picker": { "v": ["Паметен избирач"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Back to provider selection": { "v": ["Tilbake til leverandørvalg"] }, "Close Smart Picker": { "v": ["Lukk Smart Velger"] }, "Smart Picker": { "v": ["Smart Velger"] } } }, { "l": "nl", "t": { "Back to provider selection": { "v": ["Terug naar provider selectie"] }, "Close Smart Picker": { "v": ["Slimme Kiezer sluiten"] }, "Smart Picker": { "v": ["Slimme Kiezer"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Back to provider selection": { "v": ["Powrót do wyboru dostawcy"] }, "Close Smart Picker": { "v": ["Zamknij inteligentny selektor"] }, "Smart Picker": { "v": ["Inteligentne wybieranie"] } } }, { "l": "pt-BR", "t": { "Back to provider selection": { "v": ["Voltar para seleção de provedor"] }, "Close Smart Picker": { "v": ["Fechar Seletor Inteligente"] }, "Smart Picker": { "v": ["Seletor Inteligente"] } } }, { "l": "pt-PT", "t": { "Back to provider selection": { "v": ["Voltar à seleção de fornecedor"] }, "Close Smart Picker": { "v": ['Fechar "Smart Picker"'] }, "Smart Picker": { "v": ["Smart Picker"] } } }, { "l": "ro", "t": { "Back to provider selection": { "v": ["Înapoi la selecția providerului"] }, "Close Smart Picker": { "v": ["Închide Smart Picker"] }, "Smart Picker": { "v": ["Smart Picker"] } } }, { "l": "ru", "t": { "Back to provider selection": { "v": ["Вернуться к выбору провайдера"] }, "Close Smart Picker": { "v": ["Закрыть интеллектуальный выбор"] }, "Smart Picker": { "v": ["Умный выбор"] } } }, { "l": "sk", "t": { "Back to provider selection": { "v": ["Späť na výber poskytovateľa"] }, "Close Smart Picker": { "v": ["Zavrieť inteligentný výber"] }, "Smart Picker": { "v": ["Inteligentný výber"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Back to provider selection": { "v": ["Назад на избор пружаоца"] }, "Close Smart Picker": { "v": ["Затвори паметни бирач"] }, "Smart Picker": { "v": ["Паметни бирач"] } } }, { "l": "sv", "t": { "Back to provider selection": { "v": ["Tillbaka till leverantörsval"] }, "Close Smart Picker": { "v": ["Stäng Smart Picker"] }, "Smart Picker": { "v": ["Smart Picker"] } } }, { "l": "tr", "t": { "Back to provider selection": { "v": ["Hizmet sağlayıcı seçimine dön"] }, "Close Smart Picker": { "v": ["Akıllı seçimi kapat"] }, "Smart Picker": { "v": ["Akıllı seçim"] } } }, { "l": "uk", "t": { "Back to provider selection": { "v": ["Назад до вибору постачальника"] }, "Close Smart Picker": { "v": ["Закрити асистент вибору"] }, "Smart Picker": { "v": ["Асистент вибору"] } } }, { "l": "uz", "t": { "Back to provider selection": { "v": ["Provayder tanloviga qaytish"] }, "Close Smart Picker": { "v": ["Smart Picker-ni yoping"] }, "Smart Picker": { "v": ["Aqlli tanlovchi"] } } }, { "l": "zh-CN", "t": { "Back to provider selection": { "v": ["返回至提供者选择列表"] }, "Close Smart Picker": { "v": ["关闭智能拾取器"] }, "Smart Picker": { "v": ["智能拾取器"] } } }, { "l": "zh-HK", "t": { "Back to provider selection": { "v": ["回到提供者選擇"] }, "Close Smart Picker": { "v": ["關閉 Smart Picker"] }, "Smart Picker": { "v": ["Smart Picker"] } } }, { "l": "zh-TW", "t": { "Back to provider selection": { "v": ["回到提供者選擇"] }, "Close Smart Picker": { "v": ["關閉智慧型挑選器"] }, "Smart Picker": { "v": ["智慧型挑選器"] } } }];
const t14 = [{ "l": "ar", "t": { "Cancel changes": { "v": ["إلغاء التغييرات"] }, "Confirm changes": { "v": ["تأكيد التغييرات"] } } }, { "l": "ast", "t": { "Cancel changes": { "v": ["Encaboxar los cambeos"] }, "Confirm changes": { "v": ["Confirmar los cambeos"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": { "Cancel changes": { "v": ["Cancel·la els canvis"] }, "Confirm changes": { "v": ["Confirmeu els canvis"] } } }, { "l": "cs", "t": { "Cancel changes": { "v": ["Zrušit změny"] }, "Confirm changes": { "v": ["Potvrdit změny"] } } }, { "l": "cs-CZ", "t": { "Cancel changes": { "v": ["Zrušit změny"] }, "Confirm changes": { "v": ["Potvrdit změny"] } } }, { "l": "da", "t": { "Cancel changes": { "v": ["Annuller ændringer"] }, "Confirm changes": { "v": ["Bekræft ændringer"] } } }, { "l": "de", "t": { "Cancel changes": { "v": ["Änderungen verwerfen"] }, "Confirm changes": { "v": ["Änderungen bestätigen"] } } }, { "l": "de-DE", "t": { "Cancel changes": { "v": ["Änderungen verwerfen"] }, "Confirm changes": { "v": ["Änderungen bestätigen"] } } }, { "l": "el", "t": { "Cancel changes": { "v": ["Ακύρωση αλλαγών"] }, "Confirm changes": { "v": ["Επιβεβαίωση αλλαγών"] } } }, { "l": "en-GB", "t": { "Cancel changes": { "v": ["Cancel changes"] }, "Confirm changes": { "v": ["Confirm changes"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Cancel changes": { "v": ["Cancelar cambios"] }, "Confirm changes": { "v": ["Confirmar cambios"] } } }, { "l": "es-AR", "t": { "Cancel changes": { "v": ["Cancelar cambios"] }, "Confirm changes": { "v": ["Confirmar cambios"] } } }, { "l": "es-EC", "t": { "Cancel changes": { "v": ["Cancelar cambios"] }, "Confirm changes": { "v": ["Confirmar cambios"] } } }, { "l": "es-MX", "t": { "Cancel changes": { "v": ["Cancelar cambios"] }, "Confirm changes": { "v": ["Confirmar cambios"] } } }, { "l": "et-EE", "t": { "Cancel changes": { "v": ["Tühista muudatused"] }, "Confirm changes": { "v": ["Kinnitage muudatused"] } } }, { "l": "eu", "t": { "Cancel changes": { "v": ["Ezeztatu aldaketak"] }, "Confirm changes": { "v": ["Baieztatu aldaketak"] } } }, { "l": "fa", "t": { "Cancel changes": { "v": ["لغو تغییرات"] }, "Confirm changes": { "v": ["تایید تغییرات"] } } }, { "l": "fi", "t": { "Cancel changes": { "v": ["Peruuta muutokset"] }, "Confirm changes": { "v": ["Vahvista muutokset"] } } }, { "l": "fr", "t": { "Cancel changes": { "v": ["Annuler les modifications"] }, "Confirm changes": { "v": ["Confirmer les modifications"] } } }, { "l": "ga", "t": { "Cancel changes": { "v": ["Cealaigh athruithe"] }, "Confirm changes": { "v": ["Deimhnigh na hathruithe"] } } }, { "l": "gl", "t": { "Cancel changes": { "v": ["Cancelar os cambios"] }, "Confirm changes": { "v": ["Confirma os cambios"] } } }, { "l": "he", "t": { "Cancel changes": { "v": ["ביטול שינויים"] }, "Confirm changes": { "v": ["אישור השינויים"] } } }, { "l": "hr", "t": { "Cancel changes": { "v": ["Otkaži promjene"] }, "Confirm changes": { "v": ["Potvrdi promjene"] } } }, { "l": "hu", "t": { "Cancel changes": { "v": ["Változtatások elvetése"] }, "Confirm changes": { "v": ["Változtatások megerősítése"] } } }, { "l": "id", "t": { "Cancel changes": { "v": ["Batalkan perubahan"] }, "Confirm changes": { "v": ["Konfirmasikan perubahan"] } } }, { "l": "is", "t": { "Cancel changes": { "v": ["Hætta við breytingar"] }, "Confirm changes": { "v": ["Staðfesta breytingar"] } } }, { "l": "it", "t": { "Cancel changes": { "v": ["Annulla modifiche"] }, "Confirm changes": { "v": ["Conferma modifiche"] } } }, { "l": "ja", "t": { "Cancel changes": { "v": ["変更をキャンセル"] }, "Confirm changes": { "v": ["変更を承認"] } } }, { "l": "ja-JP", "t": { "Cancel changes": { "v": ["変更をキャンセル"] }, "Confirm changes": { "v": ["変更を承認"] } } }, { "l": "ko", "t": { "Cancel changes": { "v": ["변경 취소"] }, "Confirm changes": { "v": ["변경 사항 확인"] } } }, { "l": "lo", "t": { "Cancel changes": { "v": ["ຍົກເລີກການປ່ຽນແປງ"] }, "Confirm changes": { "v": ["ຢືນຢັນການປ່ຽນແປງ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Cancel changes": { "v": ["Откажи ги промените"] }, "Confirm changes": { "v": ["Потврди ги промените"] } } }, { "l": "my", "t": { "Cancel changes": { "v": ["ပြောင်းလဲမှုများ ပယ်ဖျက်ရန်"] }, "Confirm changes": { "v": ["ပြောင်းလဲမှုများ အတည်ပြုရန်"] } } }, { "l": "nb", "t": { "Cancel changes": { "v": ["Avbryt endringer"] }, "Confirm changes": { "v": ["Bekreft endringer"] } } }, { "l": "nl", "t": { "Cancel changes": { "v": ["Wijzigingen annuleren"] }, "Confirm changes": { "v": ["Wijzigingen bevestigen"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Cancel changes": { "v": ["Anuluj zmiany"] }, "Confirm changes": { "v": ["Potwierdź zmiany"] } } }, { "l": "pt-BR", "t": { "Cancel changes": { "v": ["Cancelar alterações"] }, "Confirm changes": { "v": ["Confirmar alterações"] } } }, { "l": "pt-PT", "t": { "Cancel changes": { "v": ["Cancelar alterações"] }, "Confirm changes": { "v": ["Confirmar alterações"] } } }, { "l": "ro", "t": { "Cancel changes": { "v": ["Anulează modificările"] }, "Confirm changes": { "v": ["Confirmați modificările"] } } }, { "l": "ru", "t": { "Cancel changes": { "v": ["Отменить изменения"] }, "Confirm changes": { "v": ["Подтвердить изменения"] } } }, { "l": "sk", "t": { "Cancel changes": { "v": ["Zrušiť zmeny"] }, "Confirm changes": { "v": ["Potvrdiť zmeny"] } } }, { "l": "sl", "t": { "Cancel changes": { "v": ["Prekliči spremembe"] }, "Confirm changes": { "v": ["Potrdi spremembe"] } } }, { "l": "sr", "t": { "Cancel changes": { "v": ["Откажи измене"] }, "Confirm changes": { "v": ["Потврдите измене"] } } }, { "l": "sv", "t": { "Cancel changes": { "v": ["Avbryt ändringar"] }, "Confirm changes": { "v": ["Bekräfta ändringar"] } } }, { "l": "tr", "t": { "Cancel changes": { "v": ["Değişiklikleri iptal et"] }, "Confirm changes": { "v": ["Değişiklikleri onayla"] } } }, { "l": "uk", "t": { "Cancel changes": { "v": ["Скасувати зміни"] }, "Confirm changes": { "v": ["Підтвердити зміни"] } } }, { "l": "uz", "t": { "Cancel changes": { "v": ["O'zgarishlarni bekor qilish"] }, "Confirm changes": { "v": ["O'zgarishlarni tasdiqlang"] } } }, { "l": "zh-CN", "t": { "Cancel changes": { "v": ["取消更改"] }, "Confirm changes": { "v": ["确认更改"] } } }, { "l": "zh-HK", "t": { "Cancel changes": { "v": ["取消更改"] }, "Confirm changes": { "v": ["確認更改"] } } }, { "l": "zh-TW", "t": { "Cancel changes": { "v": ["取消變更"] }, "Confirm changes": { "v": ["確認變更"] } } }];
const t17 = [{ "l": "ar", "t": { "Clear selected": { "v": ["محو المحدّد"] }, "Deselect {option}": { "v": ["إلغاء تحديد {option}"] }, "No results": { "v": ["ليس هناك أية نتيجة"] }, "Options": { "v": ["خيارات"] } } }, { "l": "ast", "t": { "Clear selected": { "v": ["Borrar lo seleicionao"] }, "Deselect {option}": { "v": ["Deseleicionar «{option}»"] }, "No results": { "v": ["Nun hai nengún resultáu"] }, "Options": { "v": ["Opciones"] } } }, { "l": "br", "t": { "No results": { "v": ["Disoc'h ebet"] } } }, { "l": "ca", "t": { "No results": { "v": ["Sense resultats"] } } }, { "l": "cs", "t": { "Clear selected": { "v": ["Vyčistit vybrané"] }, "Deselect {option}": { "v": ["Zrušit výběr {option}"] }, "No results": { "v": ["Nic nenalezeno"] }, "Options": { "v": ["Možnosti"] } } }, { "l": "cs-CZ", "t": { "Clear selected": { "v": ["Vyčistit vybrané"] }, "Deselect {option}": { "v": ["Zrušit výběr {option}"] }, "No results": { "v": ["Nic nenalezeno"] }, "Options": { "v": ["Možnosti"] } } }, { "l": "da", "t": { "Clear selected": { "v": ["Ryd valgt"] }, "Deselect {option}": { "v": ["Fravælg {option}"] }, "No results": { "v": ["Ingen resultater"] }, "Options": { "v": ["Indstillinger"] } } }, { "l": "de", "t": { "Clear selected": { "v": ["Auswahl leeren"] }, "Deselect {option}": { "v": ["{option} abwählen"] }, "No results": { "v": ["Keine Ergebnisse"] }, "Options": { "v": ["Optionen"] } } }, { "l": "de-DE", "t": { "Clear selected": { "v": ["Auswahl leeren"] }, "Deselect {option}": { "v": ["{option} abwählen"] }, "No results": { "v": ["Keine Ergebnisse"] }, "Options": { "v": ["Optionen"] } } }, { "l": "el", "t": { "Clear selected": { "v": ["Εκκαθάριση επιλογής"] }, "Deselect {option}": { "v": ["Αποεπιλογή {option}"] }, "No results": { "v": ["Κανένα αποτέλεσμα"] }, "Options": { "v": ["Επιλογές"] } } }, { "l": "en-GB", "t": { "Clear selected": { "v": ["Clear selected"] }, "Deselect {option}": { "v": ["Deselect {option}"] }, "No results": { "v": ["No results"] }, "Options": { "v": ["Options"] } } }, { "l": "eo", "t": { "No results": { "v": ["La rezulto forestas"] } } }, { "l": "es", "t": { "Clear selected": { "v": ["Limpiar selección"] }, "Deselect {option}": { "v": ["Deseleccionar {option}"] }, "No results": { "v": [" Ningún resultado"] }, "Options": { "v": ["Opciones"] } } }, { "l": "es-AR", "t": { "Clear selected": { "v": ["Limpiar selección"] }, "Deselect {option}": { "v": ["Deseleccionar {option}"] }, "No results": { "v": ["Sin resultados"] }, "Options": { "v": ["Opciones"] } } }, { "l": "es-EC", "t": { "No results": { "v": ["Sin resultados"] } } }, { "l": "es-MX", "t": { "Clear selected": { "v": ["Limpiar selección"] }, "Deselect {option}": { "v": ["Deseleccionar {option}"] }, "No results": { "v": ["Sin resultados"] }, "Options": { "v": ["Opciones"] } } }, { "l": "et-EE", "t": { "Clear selected": { "v": ["Tühjenad valik"] }, "Deselect {option}": { "v": ["Eemalda {option} valik"] }, "No results": { "v": ["Tulemusi pole"] }, "Options": { "v": ["Valikud"] } } }, { "l": "eu", "t": { "No results": { "v": ["Emaitzarik ez"] } } }, { "l": "fa", "t": { "Clear selected": { "v": ["پاک کردن مورد انتخاب شده"] }, "Deselect {option}": { "v": ["لغو انتخاب {option}"] }, "No results": { "v": ["بدون هیچ نتیجه‌ای"] }, "Options": { "v": ["گزینه‌ها"] } } }, { "l": "fi", "t": { "Clear selected": { "v": ["Tyhjennä valitut"] }, "Deselect {option}": { "v": ["Poista valinta {option}"] }, "No results": { "v": ["Ei tuloksia"] }, "Options": { "v": ["Valinnat"] } } }, { "l": "fr", "t": { "Clear selected": { "v": ["Vider la sélection"] }, "Deselect {option}": { "v": ["Désélectionner {option}"] }, "No results": { "v": ["Aucun résultat"] }, "Options": { "v": ["Options"] } } }, { "l": "ga", "t": { "Clear selected": { "v": ["Glan roghnaithe"] }, "Deselect {option}": { "v": ["Díroghnaigh {option}"] }, "No results": { "v": ["Gan torthaí"] }, "Options": { "v": ["Roghanna"] } } }, { "l": "gl", "t": { "Clear selected": { "v": ["Limpar o seleccionado"] }, "Deselect {option}": { "v": ["Desmarcar {option}"] }, "No results": { "v": ["Sen resultados"] }, "Options": { "v": ["Opcións"] } } }, { "l": "he", "t": { "No results": { "v": ["אין תוצאות"] } } }, { "l": "hr", "t": { "Clear selected": { "v": ["Očisti odabir"] }, "Deselect {option}": { "v": ["Odznači {option}"] }, "No results": { "v": ["Nema rezultata"] }, "Options": { "v": ["Mogućnosti"] } } }, { "l": "hu", "t": { "No results": { "v": ["Nincs találat"] } } }, { "l": "id", "t": { "Clear selected": { "v": ["Hapus terpilih"] }, "Deselect {option}": { "v": ["Batalkan pemilihan {option}"] }, "No results": { "v": ["Tidak ada hasil"] }, "Options": { "v": ["Opsi"] } } }, { "l": "is", "t": { "Clear selected": { "v": ["Hreinsa valið"] }, "Deselect {option}": { "v": ["Afvelja {option}"] }, "No results": { "v": ["Engar niðurstöður"] }, "Options": { "v": ["Valkostir"] } } }, { "l": "it", "t": { "Clear selected": { "v": ["Cancella selezionati"] }, "Deselect {option}": { "v": ["Deselezionare {option}"] }, "No results": { "v": ["Nessun risultato"] } } }, { "l": "ja", "t": { "Clear selected": { "v": ["選択を解除"] }, "Deselect {option}": { "v": ["{option} の選択を解除"] }, "No results": { "v": ["結果無し"] }, "Options": { "v": ["オプション"] } } }, { "l": "ja-JP", "t": { "Clear selected": { "v": ["選択を解除"] }, "Deselect {option}": { "v": ["{option} の選択を解除"] }, "No results": { "v": ["結果無し"] }, "Options": { "v": ["オプション"] } } }, { "l": "ko", "t": { "Clear selected": { "v": ["선택 항목 지우기"] }, "Deselect {option}": { "v": ["{option} 선택 해제"] }, "No results": { "v": ["결과 없음"] }, "Options": { "v": ["옵션"] } } }, { "l": "lo", "t": { "Clear selected": { "v": ["ລຶບສິ່ງທີ່ເລືອກ"] }, "Deselect {option}": { "v": ["ຍົກເລີກການເລືອກ {option}"] }, "No results": { "v": ["ບໍ່ມີຜົນລັບ"] }, "Options": { "v": ["ຕົວເລືອກ"] } } }, { "l": "lt-LT", "t": { "No results": { "v": ["Nėra rezultatų"] } } }, { "l": "lv", "t": { "No results": { "v": ["Nav rezultātu"] } } }, { "l": "mk", "t": { "Clear selected": { "v": ["Исчисти означени"] }, "Deselect {option}": { "v": ["Откажи избор на {option}"] }, "No results": { "v": ["Нема резултати"] }, "Options": { "v": ["Опции"] } } }, { "l": "my", "t": { "No results": { "v": ["ရလဒ်မရှိပါ"] } } }, { "l": "nb", "t": { "Clear selected": { "v": ["Tøm merket"] }, "Deselect {option}": { "v": ["Opphev valg {option}"] }, "No results": { "v": ["Ingen resultater"] }, "Options": { "v": ["Alternativer"] } } }, { "l": "nl", "t": { "Clear selected": { "v": ["Selectie wissen"] }, "Deselect {option}": { "v": ["Selectie {option} opheffen"] }, "No results": { "v": ["Geen resultaten"] }, "Options": { "v": ["Opties"] } } }, { "l": "oc", "t": { "No results": { "v": ["Cap de resultat"] } } }, { "l": "pl", "t": { "Clear selected": { "v": ["Wyczyść wybrane"] }, "Deselect {option}": { "v": ["Odznacz {option}"] }, "No results": { "v": ["Brak wyników"] }, "Options": { "v": ["Opcje"] } } }, { "l": "pt-BR", "t": { "Clear selected": { "v": ["Limpar selecionado"] }, "Deselect {option}": { "v": ["Desselecionar {option}"] }, "No results": { "v": ["Sem resultados"] }, "Options": { "v": ["Opções"] } } }, { "l": "pt-PT", "t": { "Clear selected": { "v": ["Limpeza selecionada"] }, "Deselect {option}": { "v": ["Desmarcar {option}"] }, "No results": { "v": ["Sem resultados"] }, "Options": { "v": ["Opções"] } } }, { "l": "ro", "t": { "Clear selected": { "v": ["Șterge selecția"] }, "Deselect {option}": { "v": ["Deselctează {option}"] }, "No results": { "v": ["Nu există rezultate"] } } }, { "l": "ru", "t": { "Clear selected": { "v": ["Очистить выбранный"] }, "Deselect {option}": { "v": ["Отменить выбор {option}"] }, "No results": { "v": ["Результаты отсуствуют"] }, "Options": { "v": ["Варианты"] } } }, { "l": "sk", "t": { "Clear selected": { "v": ["Vymazať vybraté"] }, "Deselect {option}": { "v": ["Zrušiť výber {option}"] }, "No results": { "v": ["Žiadne výsledky"] }, "Options": { "v": ["možnosti"] } } }, { "l": "sl", "t": { "No results": { "v": ["Ni zadetkov"] } } }, { "l": "sr", "t": { "Clear selected": { "v": ["Обриши изабрано"] }, "Deselect {option}": { "v": ["Уклони избор {option}"] }, "No results": { "v": ["Нема резултата"] }, "Options": { "v": ["Опције"] } } }, { "l": "sv", "t": { "Clear selected": { "v": ["Rensa val"] }, "Deselect {option}": { "v": ["Avmarkera {option}"] }, "No results": { "v": ["Inga resultat"] }, "Options": { "v": ["Alternativ"] } } }, { "l": "tr", "t": { "Clear selected": { "v": ["Seçilmişleri temizle"] }, "Deselect {option}": { "v": ["{option} bırak"] }, "No results": { "v": ["Herhangi bir sonuç bulunamadı"] }, "Options": { "v": ["Seçenekler"] } } }, { "l": "uk", "t": { "Clear selected": { "v": ["Очистити вибране"] }, "Deselect {option}": { "v": ["Зняти вибір {option}"] }, "No results": { "v": ["Відсутні результати"] }, "Options": { "v": ["Параметри"] } } }, { "l": "uz", "t": { "Clear selected": { "v": ["Tanlanganni tozalash"] }, "Deselect {option}": { "v": ["{option}tanlovni bekor qiling"] }, "No results": { "v": ["Natija yoʻq"] }, "Options": { "v": ["Variantlar"] } } }, { "l": "zh-CN", "t": { "Clear selected": { "v": ["清除所选"] }, "Deselect {option}": { "v": ["取消选择 {option}"] }, "No results": { "v": ["无结果"] }, "Options": { "v": ["选项"] } } }, { "l": "zh-HK", "t": { "Clear selected": { "v": ["清除所選項目"] }, "Deselect {option}": { "v": ["取消選擇 {option}"] }, "No results": { "v": ["無結果"] }, "Options": { "v": ["選項"] } } }, { "l": "zh-TW", "t": { "Clear selected": { "v": ["清除選定項目"] }, "Deselect {option}": { "v": ["取消選取 {option}"] }, "No results": { "v": ["無結果"] }, "Options": { "v": ["選項"] } } }];
const t18 = [{ "l": "ar", "t": { "Clear text": { "v": ["محو النص"] }, "Save changes": { "v": ["حفظ التغييرات"] } } }, { "l": "ast", "t": { "Clear text": { "v": ["Borrar el testu"] }, "Save changes": { "v": ["Guardar los cambeos"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": { "Clear text": { "v": ["Netejar text"] } } }, { "l": "cs", "t": { "Clear text": { "v": ["Čitelný text"] }, "Save changes": { "v": ["Uložit změny"] } } }, { "l": "cs-CZ", "t": { "Clear text": { "v": ["Čitelný text"] }, "Save changes": { "v": ["Uložit změny"] } } }, { "l": "da", "t": { "Clear text": { "v": ["Ryd tekst"] }, "Save changes": { "v": ["Gem ændringer"] } } }, { "l": "de", "t": { "Clear text": { "v": ["Klartext"] }, "Save changes": { "v": ["Änderungen speichern"] } } }, { "l": "de-DE", "t": { "Clear text": { "v": ["Klartext"] }, "Save changes": { "v": ["Änderungen speichern"] } } }, { "l": "el", "t": { "Clear text": { "v": ["Εκκαθάριση κειμένου"] }, "Save changes": { "v": ["Αποθήκευση αλλαγών"] } } }, { "l": "en-GB", "t": { "Clear text": { "v": ["Clear text"] }, "Save changes": { "v": ["Save changes"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Clear text": { "v": ["Limpiar texto"] }, "Save changes": { "v": ["Guardar cambios"] } } }, { "l": "es-AR", "t": { "Clear text": { "v": ["Limpiar texto"] }, "Save changes": { "v": ["Guardar cambios"] } } }, { "l": "es-EC", "t": { "Clear text": { "v": ["Limpiar texto"] } } }, { "l": "es-MX", "t": { "Clear text": { "v": ["Limpiar texto"] }, "Save changes": { "v": ["Guardar cambios"] } } }, { "l": "et-EE", "t": { "Clear text": { "v": ["Kustuta tekst"] }, "Save changes": { "v": ["Salvesta muudatused"] } } }, { "l": "eu", "t": { "Clear text": { "v": ["Garbitu testua"] } } }, { "l": "fa", "t": { "Clear text": { "v": ["پاک کردن متن"] }, "Save changes": { "v": ["ذخیرهٔ تغییرات"] } } }, { "l": "fi", "t": { "Clear text": { "v": ["Tyhjennä teksti"] }, "Save changes": { "v": ["Tallenna muutokset"] } } }, { "l": "fr", "t": { "Clear text": { "v": ["Effacer le texte"] }, "Save changes": { "v": ["Sauvegarder les changements"] } } }, { "l": "ga", "t": { "Clear text": { "v": ["Glan téacs"] }, "Save changes": { "v": ["Sabháil na hathruithe"] } } }, { "l": "gl", "t": { "Clear text": { "v": ["Limpar o texto"] }, "Save changes": { "v": ["Gardar os cambios"] } } }, { "l": "he", "t": { "Clear text": { "v": ["פינוי טקסט"] } } }, { "l": "hr", "t": { "Clear text": { "v": ["Očisti tekst"] }, "Save changes": { "v": ["Spremi promjene"] } } }, { "l": "hu", "t": { "Clear text": { "v": ["Szöveg törlése"] } } }, { "l": "id", "t": { "Clear text": { "v": ["Bersihkan teks"] }, "Save changes": { "v": ["Simpan perubahan"] } } }, { "l": "is", "t": { "Clear text": { "v": ["Hreinsa texta"] }, "Save changes": { "v": ["Vista breytingar"] } } }, { "l": "it", "t": { "Clear text": { "v": ["Cancella il testo"] }, "Save changes": { "v": ["Salva le modifiche"] } } }, { "l": "ja", "t": { "Clear text": { "v": ["テキストをクリア"] }, "Save changes": { "v": ["変更を保存"] } } }, { "l": "ja-JP", "t": { "Clear text": { "v": ["テキストをクリア"] }, "Save changes": { "v": ["変更を保存"] } } }, { "l": "ko", "t": { "Clear text": { "v": ["텍스트 지우기"] }, "Save changes": { "v": ["변경 사항 저장"] } } }, { "l": "lo", "t": { "Clear text": { "v": ["ລຶບຂໍ້ຄວາມ"] }, "Save changes": { "v": ["ບັນທຶກການປ່ຽນແປງ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Clear text": { "v": ["Исчисти текст"] }, "Save changes": { "v": ["Зачувај промени"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Clear text": { "v": ["Fjern tekst"] }, "Save changes": { "v": ["Lagre endringer"] } } }, { "l": "nl", "t": { "Clear text": { "v": ["Tekst wissen"] }, "Save changes": { "v": ["Wijzigingen opslaan"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Clear text": { "v": ["Wyczyść tekst"] }, "Save changes": { "v": ["Zapisz zmiany"] } } }, { "l": "pt-BR", "t": { "Clear text": { "v": ["Limpar texto"] }, "Save changes": { "v": ["Salvar alterações"] } } }, { "l": "pt-PT", "t": { "Clear text": { "v": ["Limpar texto"] }, "Save changes": { "v": ["Gravar alterações"] } } }, { "l": "ro", "t": { "Clear text": { "v": ["Șterge textul"] }, "Save changes": { "v": ["Salvează modificările"] } } }, { "l": "ru", "t": { "Clear text": { "v": ["Очистить текст"] }, "Save changes": { "v": ["Сохранить изменения"] } } }, { "l": "sk", "t": { "Clear text": { "v": ["Vamazať text"] }, "Save changes": { "v": ["Uložiť zmeny"] } } }, { "l": "sl", "t": { "Clear text": { "v": ["Počisti besedilo"] } } }, { "l": "sr", "t": { "Clear text": { "v": ["Обриши текст"] }, "Save changes": { "v": ["Сачувај измене"] } } }, { "l": "sv", "t": { "Clear text": { "v": ["Ta bort text"] }, "Save changes": { "v": ["Spara ändringar"] } } }, { "l": "tr", "t": { "Clear text": { "v": ["Metni temizle"] }, "Save changes": { "v": ["Değişiklikleri kaydet"] } } }, { "l": "uk", "t": { "Clear text": { "v": ["Очистити текст"] }, "Save changes": { "v": ["Зберегти зміни"] } } }, { "l": "uz", "t": { "Clear text": { "v": ["Matnni tozalash"] }, "Save changes": { "v": ["O'zgarishlarni saqlang"] } } }, { "l": "zh-CN", "t": { "Clear text": { "v": ["清除文本"] }, "Save changes": { "v": ["保存修改"] } } }, { "l": "zh-HK", "t": { "Clear text": { "v": ["清除文本"] }, "Save changes": { "v": ["保存更改"] } } }, { "l": "zh-TW", "t": { "Clear text": { "v": ["清除文字"] }, "Save changes": { "v": ["儲存變更"] } } }];
const t19 = [{ "l": "ar", "t": { "Close": { "v": ["إغلاق"] } } }, { "l": "ast", "t": { "Close": { "v": ["Zarrar"] } } }, { "l": "br", "t": { "Close": { "v": ["Serriñ"] } } }, { "l": "ca", "t": { "Close": { "v": ["Tanca"] } } }, { "l": "cs", "t": { "Close": { "v": ["Zavřít"] } } }, { "l": "cs-CZ", "t": { "Close": { "v": ["Zavřít"] } } }, { "l": "da", "t": { "Close": { "v": ["Luk"] } } }, { "l": "de", "t": { "Close": { "v": ["Schließen"] } } }, { "l": "de-DE", "t": { "Close": { "v": ["Schließen"] } } }, { "l": "el", "t": { "Close": { "v": ["Κλείσιμο"] } } }, { "l": "en-GB", "t": { "Close": { "v": ["Close"] } } }, { "l": "eo", "t": { "Close": { "v": ["Fermu"] } } }, { "l": "es", "t": { "Close": { "v": ["Cerrar"] } } }, { "l": "es-AR", "t": { "Close": { "v": ["Cerrar"] } } }, { "l": "es-EC", "t": { "Close": { "v": ["Cerrar"] } } }, { "l": "es-MX", "t": { "Close": { "v": ["Cerrar"] } } }, { "l": "et-EE", "t": { "Close": { "v": ["Sulge"] } } }, { "l": "eu", "t": { "Close": { "v": ["Itxi"] } } }, { "l": "fa", "t": { "Close": { "v": ["بستن"] } } }, { "l": "fi", "t": { "Close": { "v": ["Sulje"] } } }, { "l": "fr", "t": { "Close": { "v": ["Fermer"] } } }, { "l": "ga", "t": { "Close": { "v": ["Dún"] } } }, { "l": "gl", "t": { "Close": { "v": ["Pechar"] } } }, { "l": "he", "t": { "Close": { "v": ["סגירה"] } } }, { "l": "hr", "t": { "Close": { "v": ["Zatvori"] } } }, { "l": "hu", "t": { "Close": { "v": ["Bezárás"] } } }, { "l": "id", "t": { "Close": { "v": ["Tutup"] } } }, { "l": "is", "t": { "Close": { "v": ["Loka"] } } }, { "l": "it", "t": { "Close": { "v": ["Chiudi"] } } }, { "l": "ja", "t": { "Close": { "v": ["閉じる"] } } }, { "l": "ja-JP", "t": { "Close": { "v": ["閉じる"] } } }, { "l": "ko", "t": { "Close": { "v": ["닫기"] } } }, { "l": "lo", "t": { "Close": { "v": ["ປິດ"] } } }, { "l": "lt-LT", "t": { "Close": { "v": ["Užverti"] } } }, { "l": "lv", "t": { "Close": { "v": ["Aizvērt"] } } }, { "l": "mk", "t": { "Close": { "v": ["Затвори"] } } }, { "l": "my", "t": { "Close": { "v": ["ပိတ်ရန်"] } } }, { "l": "nb", "t": { "Close": { "v": ["Lukk"] } } }, { "l": "nl", "t": { "Close": { "v": ["Sluiten"] } } }, { "l": "oc", "t": { "Close": { "v": ["Tampar"] } } }, { "l": "pl", "t": { "Close": { "v": ["Zamknij"] } } }, { "l": "pt-BR", "t": { "Close": { "v": ["Fechar"] } } }, { "l": "pt-PT", "t": { "Close": { "v": ["Fechar"] } } }, { "l": "ro", "t": { "Close": { "v": ["Închideți"] } } }, { "l": "ru", "t": { "Close": { "v": ["Закрыть"] } } }, { "l": "sk", "t": { "Close": { "v": ["Zavrieť"] } } }, { "l": "sl", "t": { "Close": { "v": ["Zapri"] } } }, { "l": "sr", "t": { "Close": { "v": ["Затвори"] } } }, { "l": "sv", "t": { "Close": { "v": ["Stäng"] } } }, { "l": "tr", "t": { "Close": { "v": ["Kapat"] } } }, { "l": "uk", "t": { "Close": { "v": ["Закрити"] } } }, { "l": "uz", "t": { "Close": { "v": ["Yopish"] } } }, { "l": "zh-CN", "t": { "Close": { "v": ["关闭"] } } }, { "l": "zh-HK", "t": { "Close": { "v": ["關閉"] } } }, { "l": "zh-TW", "t": { "Close": { "v": ["關閉"] } } }];
const t20 = [{ "l": "ar", "t": { "Close navigation": { "v": ["إغلاق التصفح"] }, "Open navigation": { "v": ["فتح التنقُّل"] } } }, { "l": "ast", "t": { "Close navigation": { "v": ["Zarrar la navegación"] }, "Open navigation": { "v": ["Abrir la navegación"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": { "Close navigation": { "v": ["Tanca la navegació"] }, "Open navigation": { "v": ["Obre la navegació"] } } }, { "l": "cs", "t": { "Close navigation": { "v": ["Zavřít navigaci"] }, "Open navigation": { "v": ["Otevřít navigaci"] } } }, { "l": "cs-CZ", "t": { "Close navigation": { "v": ["Zavřít navigaci"] }, "Open navigation": { "v": ["Otevřít navigaci"] } } }, { "l": "da", "t": { "Close navigation": { "v": ["Luk navigation"] }, "Open navigation": { "v": ["Åben navigation"] } } }, { "l": "de", "t": { "Close navigation": { "v": ["Navigation schließen"] }, "Open navigation": { "v": ["Navigation öffnen"] } } }, { "l": "de-DE", "t": { "Close navigation": { "v": ["Navigation schließen"] }, "Open navigation": { "v": ["Navigation öffnen"] } } }, { "l": "el", "t": { "Close navigation": { "v": ["Κλείσιμο πλοήγησης"] }, "Open navigation": { "v": ["Άνοιγμα πλοήγησης"] } } }, { "l": "en-GB", "t": { "Close navigation": { "v": ["Close navigation"] }, "Open navigation": { "v": ["Open navigation"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Close navigation": { "v": ["Cerrar navegación"] }, "Open navigation": { "v": ["Abrir navegación"] } } }, { "l": "es-AR", "t": { "Close navigation": { "v": ["Cerrar navegación"] }, "Open navigation": { "v": ["Abrir navegación"] } } }, { "l": "es-EC", "t": { "Close navigation": { "v": ["Cerrar navegación"] }, "Open navigation": { "v": ["Abrir navegación"] } } }, { "l": "es-MX", "t": { "Close navigation": { "v": ["Cerrar navegación"] }, "Open navigation": { "v": ["Abrir navegación"] } } }, { "l": "et-EE", "t": { "Close navigation": { "v": ["Sulge navigatsioon"] }, "Open navigation": { "v": ["Ava liikumisvaade"] } } }, { "l": "eu", "t": { "Close navigation": { "v": ["Itxi nabigazioa"] }, "Open navigation": { "v": ["Ireki nabigazioa"] } } }, { "l": "fa", "t": { "Close navigation": { "v": ["بستن بخش ناوبری"] }, "Open navigation": { "v": ["باز کردن بخش ناوبری"] } } }, { "l": "fi", "t": { "Close navigation": { "v": ["Sulje navigaatio"] } } }, { "l": "fr", "t": { "Close navigation": { "v": ["Fermer la navigation"] }, "Open navigation": { "v": ["Ouvrir la navigation"] } } }, { "l": "ga", "t": { "Close navigation": { "v": ["Dún nascleanúint"] }, "Open navigation": { "v": ["Oscail nascleanúint"] } } }, { "l": "gl", "t": { "Close navigation": { "v": ["Pechar a navegación"] }, "Open navigation": { "v": ["Abrir a navegación"] } } }, { "l": "he", "t": { "Close navigation": { "v": ["סגירת הניווט"] }, "Open navigation": { "v": ["פתיחת ניווט"] } } }, { "l": "hr", "t": { "Close navigation": { "v": ["Zatvori navigaciju"] }, "Open navigation": { "v": ["Otvori navigaciju"] } } }, { "l": "hu", "t": { "Close navigation": { "v": ["Navigáció bezárása"] }, "Open navigation": { "v": ["Navigáció megnyitása"] } } }, { "l": "id", "t": { "Close navigation": { "v": ["Tutup navigasi"] }, "Open navigation": { "v": ["Buka navigasi"] } } }, { "l": "is", "t": { "Close navigation": { "v": ["Loka leiðsagnarsleða"] } } }, { "l": "it", "t": { "Close navigation": { "v": ["Chiudi la navigazione"] }, "Open navigation": { "v": ["Apri la navigazione"] } } }, { "l": "ja", "t": { "Close navigation": { "v": ["ナビゲーションを閉じる"] }, "Open navigation": { "v": ["ナビゲーションを開く"] } } }, { "l": "ja-JP", "t": { "Close navigation": { "v": ["ナビゲーションを閉じる"] }, "Open navigation": { "v": ["ナビゲーションを開く"] } } }, { "l": "ko", "t": { "Close navigation": { "v": ["탐색 닫기"] }, "Open navigation": { "v": ["탐색 열기"] } } }, { "l": "lo", "t": { "Close navigation": { "v": ["ປິດການນຳທາງ"] }, "Open navigation": { "v": ["ເປີດການນຳທາງ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Close navigation": { "v": ["Затвори навигација"] }, "Open navigation": { "v": ["Отвори навигација"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Close navigation": { "v": ["Lukk navigasjon"] }, "Open navigation": { "v": ["Åpne navigasjon"] } } }, { "l": "nl", "t": { "Close navigation": { "v": ["Navigatie sluiten"] }, "Open navigation": { "v": ["Navigatie openen"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Close navigation": { "v": ["Zamknij nawigację"] } } }, { "l": "pt-BR", "t": { "Close navigation": { "v": ["Fechar navegação"] }, "Open navigation": { "v": ["Abrir navegação"] } } }, { "l": "pt-PT", "t": { "Close navigation": { "v": ["Fechar navegação"] }, "Open navigation": { "v": ["Abrir navegação"] } } }, { "l": "ro", "t": { "Close navigation": { "v": ["Închideți navigarea"] }, "Open navigation": { "v": ["Deschideți navigația"] } } }, { "l": "ru", "t": { "Close navigation": { "v": ["Закрыть навигацию"] }, "Open navigation": { "v": ["Открыть навигацию"] } } }, { "l": "sk", "t": { "Close navigation": { "v": ["Zavrieť navigáciu"] } } }, { "l": "sl", "t": { "Close navigation": { "v": ["Zapri krmarjenje"] }, "Open navigation": { "v": ["Odpri krmarjenje"] } } }, { "l": "sr", "t": { "Close navigation": { "v": ["Затвори навигацију"] }, "Open navigation": { "v": ["Отвори навигацију"] } } }, { "l": "sv", "t": { "Close navigation": { "v": ["Stäng navigering"] } } }, { "l": "tr", "t": { "Close navigation": { "v": ["Gezinmeyi kapat"] }, "Open navigation": { "v": ["Gezinmeyi aç"] } } }, { "l": "uk", "t": { "Close navigation": { "v": ["Закрити навігацію"] }, "Open navigation": { "v": ["Перейти до навігації"] } } }, { "l": "uz", "t": { "Close navigation": { "v": ["Navigatsiyani yopish"] }, "Open navigation": { "v": ["Navigatsiyani oching"] } } }, { "l": "zh-CN", "t": { "Close navigation": { "v": ["关闭导航"] } } }, { "l": "zh-HK", "t": { "Close navigation": { "v": ["關閉導航"] }, "Open navigation": { "v": ["開啟導航"] } } }, { "l": "zh-TW", "t": { "Close navigation": { "v": ["關閉導航"] }, "Open navigation": { "v": ["開啟導航"] } } }];
const t21 = [{ "l": "ar", "t": { "Collapse menu": { "v": ["طي القائمة"] }, "Open menu": { "v": ["إفتَح القائمة"] } } }, { "l": "ast", "t": { "Collapse menu": { "v": ["Recoyer el menú"] }, "Open menu": { "v": ["Abrir le menú"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Collapse menu": { "v": ["Sbalit nabídku"] }, "Open menu": { "v": ["Otevřít nabídku"] } } }, { "l": "cs-CZ", "t": { "Collapse menu": { "v": ["Sbalit nabídku"] }, "Open menu": { "v": ["Otevřít nabídku"] } } }, { "l": "da", "t": { "Collapse menu": { "v": ["Skjul menuen"] }, "Open menu": { "v": ["Åben menu"] } } }, { "l": "de", "t": { "Collapse menu": { "v": ["Menü einklappen"] }, "Open menu": { "v": ["Menü öffnen"] } } }, { "l": "de-DE", "t": { "Collapse menu": { "v": ["Menü einklappen"] }, "Open menu": { "v": ["Menü öffnen"] } } }, { "l": "el", "t": { "Collapse menu": { "v": ["Σύμπτυξη μενού"] }, "Open menu": { "v": ["Άνοιγμα μενού"] } } }, { "l": "en-GB", "t": { "Collapse menu": { "v": ["Collapse menu"] }, "Open menu": { "v": ["Open menu"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Collapse menu": { "v": ["Ocultar menú"] }, "Open menu": { "v": ["Abrir menú"] } } }, { "l": "es-AR", "t": { "Collapse menu": { "v": ["Ocultar menú"] }, "Open menu": { "v": ["Abrir menú"] } } }, { "l": "es-EC", "t": { "Collapse menu": { "v": ["Ocultar menú"] }, "Open menu": { "v": ["Abrir menú"] } } }, { "l": "es-MX", "t": { "Collapse menu": { "v": ["Ocultar menú"] }, "Open menu": { "v": ["Abrir menú"] } } }, { "l": "et-EE", "t": { "Collapse menu": { "v": ["Menüü kokkuklappimine"] }, "Open menu": { "v": ["Ava menüü"] } } }, { "l": "eu", "t": { "Collapse menu": { "v": ["Tolestu menua"] }, "Open menu": { "v": ["Ireki menua"] } } }, { "l": "fa", "t": { "Collapse menu": { "v": ["بستن فهرست"] }, "Open menu": { "v": ["باز کردن فهرست"] } } }, { "l": "fi", "t": { "Collapse menu": { "v": ["Supista valikko"] }, "Open menu": { "v": ["Avaa valikko"] } } }, { "l": "fr", "t": { "Collapse menu": { "v": ["Réduire le menu"] }, "Open menu": { "v": ["Ouvrir le menu"] } } }, { "l": "ga", "t": { "Collapse menu": { "v": ["Roghchlár Laghdaigh"] }, "Open menu": { "v": ["Roghchlár a oscailt"] } } }, { "l": "gl", "t": { "Collapse menu": { "v": ["Contraer o menú"] }, "Open menu": { "v": ["Abrir o menú"] } } }, { "l": "he", "t": { "Collapse menu": { "v": ["צמצום התפריט"] }, "Open menu": { "v": ["פתיחת תפריט"] } } }, { "l": "hr", "t": { "Collapse menu": { "v": ["Sakrij izbornik"] }, "Open menu": { "v": ["Otvori izbornik"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Collapse menu": { "v": ["Ciutkan menu"] }, "Open menu": { "v": ["Buka menu"] } } }, { "l": "is", "t": { "Collapse menu": { "v": ["Fella valmynd saman"] }, "Open menu": { "v": ["Opna valmynd"] } } }, { "l": "it", "t": { "Collapse menu": { "v": ["Chiudi Menu"] }, "Open menu": { "v": ["Apri il menu"] } } }, { "l": "ja", "t": { "Collapse menu": { "v": ["メニューの折りたたみ"] }, "Open menu": { "v": ["メニューを開く"] } } }, { "l": "ja-JP", "t": { "Collapse menu": { "v": ["メニューの折りたたみ"] }, "Open menu": { "v": ["メニューを開く"] } } }, { "l": "ko", "t": { "Collapse menu": { "v": ["메뉴 접기"] }, "Open menu": { "v": ["메뉴 열기"] } } }, { "l": "lo", "t": { "Collapse menu": { "v": ["ຫຍໍ້ເມນູ"] }, "Open menu": { "v": ["ເປີດເມນູ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Collapse menu": { "v": ["Скриј мени"] }, "Open menu": { "v": ["Отвори мени"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Collapse menu": { "v": ["Skjul meny"] }, "Open menu": { "v": ["Åpne meny"] } } }, { "l": "nl", "t": { "Collapse menu": { "v": ["Menu inklappen"] }, "Open menu": { "v": ["Menu openen"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Collapse menu": { "v": ["Zwiń menu"] }, "Open menu": { "v": ["Otwórz menu"] } } }, { "l": "pt-BR", "t": { "Collapse menu": { "v": ["Recolher menu"] }, "Open menu": { "v": ["Abrir menu"] } } }, { "l": "pt-PT", "t": { "Collapse menu": { "v": ["Ocultar menu"] }, "Open menu": { "v": ["Abrir menu"] } } }, { "l": "ro", "t": { "Collapse menu": { "v": ["Restrânge meniul"] }, "Open menu": { "v": ["Deschide meniul"] } } }, { "l": "ru", "t": { "Collapse menu": { "v": ["Свернуть меню"] }, "Open menu": { "v": ["Открыть меню"] } } }, { "l": "sk", "t": { "Collapse menu": { "v": ["Zbaliť menu"] }, "Open menu": { "v": ["Otvoriť menu"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Collapse menu": { "v": ["Сажми мени"] }, "Open menu": { "v": ["Отвори мени"] } } }, { "l": "sv", "t": { "Collapse menu": { "v": ["Dölj menyn"] }, "Open menu": { "v": ["Öppna menyn"] } } }, { "l": "tr", "t": { "Collapse menu": { "v": ["Menüyü daralt"] }, "Open menu": { "v": ["Menüyü aç"] } } }, { "l": "uk", "t": { "Collapse menu": { "v": ["Згорнути меню"] }, "Open menu": { "v": ["Відкрити меню"] } } }, { "l": "uz", "t": { "Collapse menu": { "v": ["Menyuni yig‘ish"] }, "Open menu": { "v": ["Menyuni oching"] } } }, { "l": "zh-CN", "t": { "Collapse menu": { "v": ["收起菜单"] }, "Open menu": { "v": ["打开菜单"] } } }, { "l": "zh-HK", "t": { "Collapse menu": { "v": ["折疊選單"] }, "Open menu": { "v": ["開啟選單"] } } }, { "l": "zh-TW", "t": { "Collapse menu": { "v": ["折疊選單"] }, "Open menu": { "v": ["開啟選單"] } } }];
const t22 = [{ "l": "ar", "t": {} }, { "l": "ast", "t": {} }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Copied": { "v": ["Zkopírováno"] }, "Copy to clipboard": { "v": ["Zkopírovat do schránky"] } } }, { "l": "cs-CZ", "t": {} }, { "l": "da", "t": { "Copied": { "v": ["Kopieret"] }, "Copy to clipboard": { "v": ["Kopiér til udklipsholderen"] } } }, { "l": "de", "t": { "Copied": { "v": ["Kopiert"] }, "Copy to clipboard": { "v": ["In die Zwischenablage kopieren"] } } }, { "l": "de-DE", "t": { "Copied": { "v": ["Kopiert"] }, "Copy to clipboard": { "v": ["In die Zwischenablage kopieren"] } } }, { "l": "el", "t": {} }, { "l": "en-GB", "t": { "Copied": { "v": ["Copied"] }, "Copy to clipboard": { "v": ["Copy to clipboard"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": {} }, { "l": "es-AR", "t": {} }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": {} }, { "l": "et-EE", "t": { "Copied": { "v": ["Kopeeritud"] }, "Copy to clipboard": { "v": ["Kopeeri lõikelauale"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": {} }, { "l": "fi", "t": {} }, { "l": "fr", "t": { "Copied": { "v": ["Copié"] }, "Copy to clipboard": { "v": ["Copier dans le presse-papiers"] } } }, { "l": "ga", "t": { "Copied": { "v": ["Cóipeáilte"] }, "Copy to clipboard": { "v": ["Cóipeáil chuig an ghearrthaisce"] } } }, { "l": "gl", "t": { "Copied": { "v": ["Copiado"] }, "Copy to clipboard": { "v": ["Copiar ao portapapeis"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "Copied": { "v": ["Kopirano"] }, "Copy to clipboard": { "v": ["Kopiraj u međuspremnik"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Copied": { "v": ["Disalin"] }, "Copy to clipboard": { "v": ["Salin ke clipboard"] } } }, { "l": "is", "t": {} }, { "l": "it", "t": {} }, { "l": "ja", "t": { "Copied": { "v": ["コピーされました"] }, "Copy to clipboard": { "v": ["クリップボードにコピー"] } } }, { "l": "ja-JP", "t": {} }, { "l": "ko", "t": { "Copied": { "v": ["복사됨"] }, "Copy to clipboard": { "v": ["클립보드로 복사"] } } }, { "l": "lo", "t": { "Copied": { "v": ["ສຳເນົາແລ້ວ"] }, "Copy to clipboard": { "v": ["ສຳເນົາໃສ່ຄລິບບອດ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": {} }, { "l": "my", "t": {} }, { "l": "nb", "t": {} }, { "l": "nl", "t": { "Copied": { "v": ["Gekopieerd"] }, "Copy to clipboard": { "v": ["Kopieer naar klembord"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": {} }, { "l": "pt-BR", "t": { "Copied": { "v": ["Copiado"] }, "Copy to clipboard": { "v": ["Copiar para a área de transferência"] } } }, { "l": "pt-PT", "t": {} }, { "l": "ro", "t": {} }, { "l": "ru", "t": { "Copied": { "v": ["Скопировано"] }, "Copy to clipboard": { "v": ["Скопировать в буфер"] } } }, { "l": "sk", "t": {} }, { "l": "sl", "t": {} }, { "l": "sr", "t": {} }, { "l": "sv", "t": {} }, { "l": "tr", "t": { "Copied": { "v": ["Kopyalandı"] }, "Copy to clipboard": { "v": ["Panoya kopyalandı"] } } }, { "l": "uk", "t": {} }, { "l": "uz", "t": {} }, { "l": "zh-CN", "t": {} }, { "l": "zh-HK", "t": { "Copied": { "v": ["已被複製"] }, "Copy to clipboard": { "v": ["複製到剪貼簿"] } } }, { "l": "zh-TW", "t": {} }];
const t23 = [{ "l": "ar", "t": { "Edit item": { "v": ["تعديل عنصر"] } } }, { "l": "ast", "t": { "Edit item": { "v": ["Editar l'elementu"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": { "Edit item": { "v": ["Edita l'element"] } } }, { "l": "cs", "t": { "Edit item": { "v": ["Upravit položku"] } } }, { "l": "cs-CZ", "t": { "Edit item": { "v": ["Upravit položku"] } } }, { "l": "da", "t": { "Edit item": { "v": ["Rediger emne"] } } }, { "l": "de", "t": { "Edit item": { "v": ["Element bearbeiten"] } } }, { "l": "de-DE", "t": { "Edit item": { "v": ["Element bearbeiten"] } } }, { "l": "el", "t": { "Edit item": { "v": ["Επεξεργασία αντικειμένου"] } } }, { "l": "en-GB", "t": { "Edit item": { "v": ["Edit item"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Edit item": { "v": ["Editar elemento"] } } }, { "l": "es-AR", "t": { "Edit item": { "v": ["Editar elemento"] } } }, { "l": "es-EC", "t": { "Edit item": { "v": ["Editar elemento"] } } }, { "l": "es-MX", "t": { "Edit item": { "v": ["Editar elemento"] } } }, { "l": "et-EE", "t": { "Edit item": { "v": ["Muuda objekti"] } } }, { "l": "eu", "t": { "Edit item": { "v": ["Editatu elementua"] } } }, { "l": "fa", "t": { "Edit item": { "v": ["ویرایش مورد"] } } }, { "l": "fi", "t": { "Edit item": { "v": ["Muokkaa kohdetta"] } } }, { "l": "fr", "t": { "Edit item": { "v": ["Éditer l'élément"] } } }, { "l": "ga", "t": { "Edit item": { "v": ["Cuir mír in eagar"] } } }, { "l": "gl", "t": { "Edit item": { "v": ["Editar o elemento"] } } }, { "l": "he", "t": { "Edit item": { "v": ["עריכת פריט"] } } }, { "l": "hr", "t": { "Edit item": { "v": ["Uredi stavku"] } } }, { "l": "hu", "t": { "Edit item": { "v": ["Elem szerkesztése"] } } }, { "l": "id", "t": { "Edit item": { "v": ["Edit item"] } } }, { "l": "is", "t": { "Edit item": { "v": ["Breyta atriði"] } } }, { "l": "it", "t": { "Edit item": { "v": ["Modifica l'elemento"] } } }, { "l": "ja", "t": { "Edit item": { "v": ["編集"] } } }, { "l": "ja-JP", "t": { "Edit item": { "v": ["編集"] } } }, { "l": "ko", "t": { "Edit item": { "v": ["항목 수정"] } } }, { "l": "lo", "t": { "Edit item": { "v": ["ແກ້ໄຂລາຍການ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Edit item": { "v": ["Уреди"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Edit item": { "v": ["Rediger"] } } }, { "l": "nl", "t": { "Edit item": { "v": ["Item bewerken"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Edit item": { "v": ["Edytuj element"] } } }, { "l": "pt-BR", "t": { "Edit item": { "v": ["Editar item"] } } }, { "l": "pt-PT", "t": { "Edit item": { "v": ["Editar item"] } } }, { "l": "ro", "t": { "Edit item": { "v": ["Editați elementul"] } } }, { "l": "ru", "t": { "Edit item": { "v": ["Изменить элемент"] } } }, { "l": "sk", "t": { "Edit item": { "v": ["Upraviť položku"] } } }, { "l": "sl", "t": { "Edit item": { "v": ["Uredi predmet"] } } }, { "l": "sr", "t": { "Edit item": { "v": ["Уреди ставку"] } } }, { "l": "sv", "t": { "Edit item": { "v": ["Redigera objekt"] } } }, { "l": "tr", "t": { "Edit item": { "v": ["Ögeyi düzenle"] } } }, { "l": "uk", "t": { "Edit item": { "v": ["Редагувати елемент"] } } }, { "l": "uz", "t": { "Edit item": { "v": ["Elementni tahrirlash"] } } }, { "l": "zh-CN", "t": { "Edit item": { "v": ["编辑项目"] } } }, { "l": "zh-HK", "t": { "Edit item": { "v": ["編輯項目"] } } }, { "l": "zh-TW", "t": { "Edit item": { "v": ["編輯項目"] } } }];
const t24 = [{ "l": "ar", "t": { "Enable interactive view": { "v": ["تمكين المنظور التفاعلي"] } } }, { "l": "ast", "t": { "Enable interactive view": { "v": ["Activar la vista interactiva"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Enable interactive view": { "v": ["Zapnout interaktivní zobrazení"] } } }, { "l": "cs-CZ", "t": {} }, { "l": "da", "t": { "Enable interactive view": { "v": ["Aktiver interaktiv visning"] } } }, { "l": "de", "t": { "Enable interactive view": { "v": ["Die interaktive Ansicht aktivieren"] } } }, { "l": "de-DE", "t": { "Enable interactive view": { "v": ["Die interaktive Ansicht aktivieren"] } } }, { "l": "el", "t": { "Enable interactive view": { "v": ["Ενεργοποίηση διαδραστικής προβολής"] } } }, { "l": "en-GB", "t": { "Enable interactive view": { "v": ["Enable interactive view"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Enable interactive view": { "v": ["Habilitar vista interactiva"] } } }, { "l": "es-AR", "t": { "Enable interactive view": { "v": ["Habilitar vista interactiva"] } } }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": { "Enable interactive view": { "v": ["Habilitar vista interactiva"] } } }, { "l": "et-EE", "t": { "Enable interactive view": { "v": ["Lülita interaktiivne vaade sisse"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": { "Enable interactive view": { "v": ["فعال‌سازی نمای تعاملی"] } } }, { "l": "fi", "t": { "Enable interactive view": { "v": ["Näytä vuorovaikutteinen näkymä"] } } }, { "l": "fr", "t": { "Enable interactive view": { "v": ["Activer la vue interactive"] } } }, { "l": "ga", "t": { "Enable interactive view": { "v": ["Cumasaigh amharc idirghníomhach"] } } }, { "l": "gl", "t": { "Enable interactive view": { "v": ["Activar a vista interactiva"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "Enable interactive view": { "v": ["Omogući interaktivni prikaz"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Enable interactive view": { "v": ["Aktifkan tampilan interaktif"] } } }, { "l": "is", "t": { "Enable interactive view": { "v": ["Virkja gagnvirka sýn"] } } }, { "l": "it", "t": {} }, { "l": "ja", "t": { "Enable interactive view": { "v": ["インタラクティブ・ビューを有効にする"] } } }, { "l": "ja-JP", "t": { "Enable interactive view": { "v": ["インタラクティブ・ビューを有効にする"] } } }, { "l": "ko", "t": { "Enable interactive view": { "v": ["대화형 보기 활성화"] } } }, { "l": "lo", "t": { "Enable interactive view": { "v": ["ເປີດໃຊ້ງານມຸມມອງໂຕ້ຕອບ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Enable interactive view": { "v": ["Овозможи интерактивен приказ"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Enable interactive view": { "v": ["Aktiver interaktiv visning"] } } }, { "l": "nl", "t": { "Enable interactive view": { "v": ["Interactieve weergave inschakelen"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Enable interactive view": { "v": ["Włącz widok interaktywny"] } } }, { "l": "pt-BR", "t": { "Enable interactive view": { "v": ["Ativar visualização interativa"] } } }, { "l": "pt-PT", "t": { "Enable interactive view": { "v": ["Ativar vista interativa"] } } }, { "l": "ro", "t": {} }, { "l": "ru", "t": { "Enable interactive view": { "v": ["Включить интерактивный просмотр"] } } }, { "l": "sk", "t": { "Enable interactive view": { "v": ["Povoliť interaktívny pohľad"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Enable interactive view": { "v": ["Укључи интерактивни приказ"] } } }, { "l": "sv", "t": { "Enable interactive view": { "v": ["Aktivera interaktiv vy"] } } }, { "l": "tr", "t": { "Enable interactive view": { "v": ["Etkileşimli görünümü aç"] } } }, { "l": "uk", "t": { "Enable interactive view": { "v": ["Увімкнути інтерактивний перегляд"] } } }, { "l": "uz", "t": { "Enable interactive view": { "v": ["Interaktiv ko'rinishni yoqing"] } } }, { "l": "zh-CN", "t": { "Enable interactive view": { "v": ["启用交互视窗"] } } }, { "l": "zh-HK", "t": { "Enable interactive view": { "v": ["啟用互動視圖"] } } }, { "l": "zh-TW", "t": { "Enable interactive view": { "v": ["啟用互動檢視"] } } }];
const t25 = [{ "l": "ar", "t": { "Enter link": { "v": ["أدخِل الرابط"] } } }, { "l": "ast", "t": { "Enter link": { "v": ["Introducir l'enllaz"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Enter link": { "v": ["Zadat odkaz"] } } }, { "l": "cs-CZ", "t": { "Enter link": { "v": ["Zadat odkaz"] } } }, { "l": "da", "t": { "Enter link": { "v": ["Indtast link"] } } }, { "l": "de", "t": { "Enter link": { "v": ["Link eingeben"] } } }, { "l": "de-DE", "t": { "Enter link": { "v": ["Link eingeben"] } } }, { "l": "el", "t": { "Enter link": { "v": ["Εισάγετε σύνδεσμο"] } } }, { "l": "en-GB", "t": { "Enter link": { "v": ["Enter link"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Enter link": { "v": ["Ingrese enlace"] } } }, { "l": "es-AR", "t": { "Enter link": { "v": ["Ingresar enlace"] } } }, { "l": "es-EC", "t": { "Enter link": { "v": ["Ingresar enlace"] } } }, { "l": "es-MX", "t": { "Enter link": { "v": ["Ingresar enlace"] } } }, { "l": "et-EE", "t": { "Enter link": { "v": ["Sisesta link"] } } }, { "l": "eu", "t": { "Enter link": { "v": ["Sartu esteka"] } } }, { "l": "fa", "t": { "Enter link": { "v": ["لینک را وارد کنید"] } } }, { "l": "fi", "t": { "Enter link": { "v": ["Kirjoita linkki"] } } }, { "l": "fr", "t": { "Enter link": { "v": ["Saisissez le lien"] } } }, { "l": "ga", "t": { "Enter link": { "v": ["Cuir isteach nasc"] } } }, { "l": "gl", "t": { "Enter link": { "v": ["Introducir a ligazón"] } } }, { "l": "he", "t": { "Enter link": { "v": ["מילוי קישור"] } } }, { "l": "hr", "t": { "Enter link": { "v": ["Unesi poveznicu"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Enter link": { "v": ["Masukkan tautan"] } } }, { "l": "is", "t": { "Enter link": { "v": ["Settu inn tengil"] } } }, { "l": "it", "t": { "Enter link": { "v": ["Inserire il link"] } } }, { "l": "ja", "t": { "Enter link": { "v": ["リンクを入力する"] } } }, { "l": "ja-JP", "t": { "Enter link": { "v": ["リンクを入力する"] } } }, { "l": "ko", "t": { "Enter link": { "v": ["링크 입력"] } } }, { "l": "lo", "t": { "Enter link": { "v": ["ປ້ອນລິງ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Enter link": { "v": ["Внеси линк"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Enter link": { "v": ["Skriv inn lenken"] } } }, { "l": "nl", "t": { "Enter link": { "v": ["Link invoeren"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Enter link": { "v": ["Wprowadź link"] } } }, { "l": "pt-BR", "t": { "Enter link": { "v": ["Insira o link"] } } }, { "l": "pt-PT", "t": { "Enter link": { "v": ["Inserir hiperligação"] } } }, { "l": "ro", "t": { "Enter link": { "v": ["Introduceți link-ul"] } } }, { "l": "ru", "t": { "Enter link": { "v": ["Введите ссылку"] } } }, { "l": "sk", "t": { "Enter link": { "v": ["Vložiť link"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Enter link": { "v": ["Унесите линк"] } } }, { "l": "sv", "t": { "Enter link": { "v": ["Ange länk"] } } }, { "l": "tr", "t": { "Enter link": { "v": ["Bağlantıyı yazın"] } } }, { "l": "uk", "t": { "Enter link": { "v": ["Зазначте посилання"] } } }, { "l": "uz", "t": { "Enter link": { "v": ["Havolani kiriting"] } } }, { "l": "zh-CN", "t": { "Enter link": { "v": ["输入链接"] } } }, { "l": "zh-HK", "t": { "Enter link": { "v": ["輸入連結"] } } }, { "l": "zh-TW", "t": { "Enter link": { "v": ["輸入連結"] } } }];
const t26 = [{ "l": "ar", "t": {} }, { "l": "ast", "t": {} }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "External documentation": { "v": ["Externí dokumentace"] } } }, { "l": "cs-CZ", "t": {} }, { "l": "da", "t": { "External documentation": { "v": ["Ekstern dokumentation"] } } }, { "l": "de", "t": { "External documentation": { "v": ["Externe Dokumentation"] } } }, { "l": "de-DE", "t": { "External documentation": { "v": ["Externe Dokumentation"] } } }, { "l": "el", "t": { "External documentation": { "v": ["Εξωτερική τεκμηρίωση"] } } }, { "l": "en-GB", "t": { "External documentation": { "v": ["External documentation"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": {} }, { "l": "es-AR", "t": {} }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": {} }, { "l": "et-EE", "t": { "External documentation": { "v": ["Dokumentatsioon välises allikas"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": {} }, { "l": "fi", "t": {} }, { "l": "fr", "t": { "External documentation": { "v": ["Documentation externe"] } } }, { "l": "ga", "t": { "External documentation": { "v": ["Doiciméadú seachtrach"] } } }, { "l": "gl", "t": { "External documentation": { "v": ["Documentación externa"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "External documentation": { "v": ["Vanjska dokumentacija"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "External documentation": { "v": ["Dokumentasi eksternal"] } } }, { "l": "is", "t": {} }, { "l": "it", "t": {} }, { "l": "ja", "t": { "External documentation": { "v": ["外部ドキュメント"] } } }, { "l": "ja-JP", "t": {} }, { "l": "ko", "t": { "External documentation": { "v": ["외부 문서"] } } }, { "l": "lo", "t": { "External documentation": { "v": ["ເອກະສານພາຍນອກ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "External documentation": { "v": ["Надворешна документација"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": {} }, { "l": "nl", "t": { "External documentation": { "v": ["Externe documentatie"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": {} }, { "l": "pt-BR", "t": { "External documentation": { "v": ["Documentação externa"] } } }, { "l": "pt-PT", "t": {} }, { "l": "ro", "t": {} }, { "l": "ru", "t": { "External documentation": { "v": ["Внешняя документация"] } } }, { "l": "sk", "t": {} }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "External documentation": { "v": ["Спољна документација"] } } }, { "l": "sv", "t": {} }, { "l": "tr", "t": { "External documentation": { "v": ["Dış belgeler"] } } }, { "l": "uk", "t": { "External documentation": { "v": ["Зовнішня документація"] } } }, { "l": "uz", "t": { "External documentation": { "v": ["Tashqi hujjatlar"] } } }, { "l": "zh-CN", "t": {} }, { "l": "zh-HK", "t": { "External documentation": { "v": ["外部文件"] } } }, { "l": "zh-TW", "t": {} }];
const t27 = [{ "l": "ar", "t": { "Go back to the list": { "v": ["عودة إلى القائمة"] } } }, { "l": "ast", "t": { "Go back to the list": { "v": ["Volver a la llista"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": { "Go back to the list": { "v": ["Torna a la llista"] } } }, { "l": "cs", "t": { "Go back to the list": { "v": ["Jít zpět na seznam"] } } }, { "l": "cs-CZ", "t": { "Go back to the list": { "v": ["Jít zpět na seznam"] } } }, { "l": "da", "t": { "Go back to the list": { "v": ["Tilbage til listen"] } } }, { "l": "de", "t": { "Go back to the list": { "v": ["Zurück zur Liste"] } } }, { "l": "de-DE", "t": { "Go back to the list": { "v": ["Zurück zur Liste"] } } }, { "l": "el", "t": { "Go back to the list": { "v": ["Επιστροφή στην αρχική λίστα"] } } }, { "l": "en-GB", "t": { "Go back to the list": { "v": ["Go back to the list"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Go back to the list": { "v": ["Volver a la lista"] } } }, { "l": "es-AR", "t": { "Go back to the list": { "v": ["Volver a la lista"] } } }, { "l": "es-EC", "t": { "Go back to the list": { "v": ["Volver a la lista"] } } }, { "l": "es-MX", "t": { "Go back to the list": { "v": ["Regresar a la lista"] } } }, { "l": "et-EE", "t": { "Go back to the list": { "v": ["Tagasi nimekirja juurde"] } } }, { "l": "eu", "t": { "Go back to the list": { "v": ["Bueltatu zerrendara"] } } }, { "l": "fa", "t": { "Go back to the list": { "v": ["برگشت به لیست"] } } }, { "l": "fi", "t": { "Go back to the list": { "v": ["Takaisin listaan"] } } }, { "l": "fr", "t": { "Go back to the list": { "v": ["Retourner à la liste"] } } }, { "l": "ga", "t": { "Go back to the list": { "v": ["Téigh ar ais go dtí an liosta"] } } }, { "l": "gl", "t": { "Go back to the list": { "v": ["Volver á lista"] } } }, { "l": "he", "t": { "Go back to the list": { "v": ["חזרה לרשימה"] } } }, { "l": "hr", "t": { "Go back to the list": { "v": ["Vrati se na popis"] } } }, { "l": "hu", "t": { "Go back to the list": { "v": ["Ugrás vissza a listához"] } } }, { "l": "id", "t": { "Go back to the list": { "v": ["Kembali ke daftar"] } } }, { "l": "is", "t": { "Go back to the list": { "v": ["Fara til baka í listann"] } } }, { "l": "it", "t": { "Go back to the list": { "v": ["Torna all'elenco"] } } }, { "l": "ja", "t": { "Go back to the list": { "v": ["リストに戻る"] } } }, { "l": "ja-JP", "t": { "Go back to the list": { "v": ["リストに戻る"] } } }, { "l": "ko", "t": { "Go back to the list": { "v": ["목록으로 돌아가기"] } } }, { "l": "lo", "t": { "Go back to the list": { "v": ["ກັບໄປທີ່ລາຍການ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Go back to the list": { "v": ["Врати се на листата"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Go back to the list": { "v": ["Gå tilbake til listen"] } } }, { "l": "nl", "t": { "Go back to the list": { "v": ["Ga terug naar de lijst"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Go back to the list": { "v": ["Powrót do listy"] } } }, { "l": "pt-BR", "t": { "Go back to the list": { "v": ["Voltar para a lista"] } } }, { "l": "pt-PT", "t": { "Go back to the list": { "v": ["Voltar para a lista"] } } }, { "l": "ro", "t": { "Go back to the list": { "v": ["Întoarceți-vă la listă"] } } }, { "l": "ru", "t": { "Go back to the list": { "v": ["Вернуться к списку"] } } }, { "l": "sk", "t": { "Go back to the list": { "v": ["Späť na zoznam"] } } }, { "l": "sl", "t": { "Go back to the list": { "v": ["Vrni se na seznam"] } } }, { "l": "sr", "t": { "Go back to the list": { "v": ["Назад на листу"] } } }, { "l": "sv", "t": { "Go back to the list": { "v": ["Gå tillbaka till listan"] } } }, { "l": "tr", "t": { "Go back to the list": { "v": ["Listeye dön"] } } }, { "l": "uk", "t": { "Go back to the list": { "v": ["Повернутися до списку"] } } }, { "l": "uz", "t": { "Go back to the list": { "v": ["Ro'yxatga qayting"] } } }, { "l": "zh-CN", "t": { "Go back to the list": { "v": ["返回至列表"] } } }, { "l": "zh-HK", "t": { "Go back to the list": { "v": ["返回清單"] } } }, { "l": "zh-TW", "t": { "Go back to the list": { "v": ["回到清單"] } } }];
const t30 = [{ "l": "ar", "t": { "Keyboard navigation help": { "v": ["مساعدة في التنقل باستعمال لوحة المفاتيح"] }, "Skip to app navigation": { "v": ["تجاوَز إلى التنقل في التطبيق"] }, "Skip to main content": { "v": ["تجاوَز إلى المحتوى الرئيسي"] } } }, { "l": "ast", "t": { "Keyboard navigation help": { "v": ["Ayuda de la navegación pente'l tecláu"] }, "Skip to app navigation": { "v": ["Dir a la navegación d'aplicaciones"] }, "Skip to main content": { "v": ["Dir al conteníu principal"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Keyboard navigation help": { "v": ["Nápověda pro pohyb pomocí klávesnice"] }, "Skip to app navigation": { "v": ["Přeskočit na navigaci aplikace"] }, "Skip to main content": { "v": ["Přeskočit na hlavní obsah"] } } }, { "l": "cs-CZ", "t": { "Keyboard navigation help": { "v": ["Nápověda pro pohyb pomocí klávesnice"] }, "Skip to app navigation": { "v": ["Přeskočit na navigaci aplikace"] }, "Skip to main content": { "v": ["Přeskočit na hlavní obsah"] } } }, { "l": "da", "t": { "Keyboard navigation help": { "v": ["Hjælp til tastaturnavigation"] }, "Skip to app navigation": { "v": ["Spring til app navigation"] }, "Skip to main content": { "v": ["Spring til hovedindhold"] } } }, { "l": "de", "t": { "Keyboard navigation help": { "v": ["Tastatur-Navigationshilfe"] }, "Skip to app navigation": { "v": ["Zur App-Navigation springen"] }, "Skip to main content": { "v": ["Zum Hauptinhalt springen"] } } }, { "l": "de-DE", "t": { "Keyboard navigation help": { "v": ["Tastatur-Navigationshilfe"] }, "Skip to app navigation": { "v": ["Zur App-Navigation springen"] }, "Skip to main content": { "v": ["Zum Hauptinhalt springen"] } } }, { "l": "el", "t": { "Keyboard navigation help": { "v": ["Βοήθεια πλοήγησης με πληκτρολόγιο"] }, "Skip to app navigation": { "v": ["Μετάβαση στην πλοήγηση της εφαρμογής"] }, "Skip to main content": { "v": ["Μετάβαση στο κύριο περιεχόμενο"] } } }, { "l": "en-GB", "t": { "Keyboard navigation help": { "v": ["Keyboard navigation help"] }, "Skip to app navigation": { "v": ["Skip to app navigation"] }, "Skip to main content": { "v": ["Skip to main content"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Keyboard navigation help": { "v": ["Ayuda de navegación del teclado"] }, "Skip to app navigation": { "v": ["Saltar a la navegación de apps"] }, "Skip to main content": { "v": ["Saltar al contenido principal"] } } }, { "l": "es-AR", "t": { "Keyboard navigation help": { "v": ["Ayuda de navegación del teclado"] }, "Skip to app navigation": { "v": ["Saltar a la navegación de app"] }, "Skip to main content": { "v": ["Saltar al contenido principal"] } } }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": { "Keyboard navigation help": { "v": ["Ayuda de navegación del teclado"] }, "Skip to app navigation": { "v": ["Saltar a la navegación de app"] }, "Skip to main content": { "v": ["Saltar al contenido principal"] } } }, { "l": "et-EE", "t": { "Keyboard navigation help": { "v": ["Klahvistiku kasutuse abiteave"] }, "Skip to app navigation": { "v": ["Suundu rakenduses liikumise valikute juurde"] }, "Skip to main content": { "v": ["Suundu põhisisu juurde"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": { "Keyboard navigation help": { "v": ["راهنمای ناوبری صفحه کلید"] }, "Skip to app navigation": { "v": ["رفتن به پیمایش برنامه"] }, "Skip to main content": { "v": ["رفتن به محتوای اصلی"] } } }, { "l": "fi", "t": { "Keyboard navigation help": { "v": ["Näppäimistönavigoinnin ohje"] }, "Skip to app navigation": { "v": ["Siirry sovelluksen navigaatioon"] }, "Skip to main content": { "v": ["Siirry pääsisältöön"] } } }, { "l": "fr", "t": { "Keyboard navigation help": { "v": ["Aide à la navigation du clavier"] }, "Skip to app navigation": { "v": ["Passer à l'app navigation"] }, "Skip to main content": { "v": ["Passer au contenu principal"] } } }, { "l": "ga", "t": { "Keyboard navigation help": { "v": ["Cabhair le nascleanúint méarchláir"] }, "Skip to app navigation": { "v": ["Téigh ar aghaidh chuig nascleanúint aip"] }, "Skip to main content": { "v": ["Téigh ar aghaidh chuig an bpríomhábhar"] } } }, { "l": "gl", "t": { "Keyboard navigation help": { "v": ["Axuda á navegación co teclado"] }, "Skip to app navigation": { "v": ["Ir á navegación da aplicación"] }, "Skip to main content": { "v": ["Ir ao contido principal"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "Keyboard navigation help": { "v": ["Pomoć za navigaciju tipkovnicom"] }, "Skip to app navigation": { "v": ["Preskoči na navigaciju aplikacije"] }, "Skip to main content": { "v": ["Preskoči na glavni sadržaj"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Keyboard navigation help": { "v": ["Bantuan navigasi keyboard"] }, "Skip to app navigation": { "v": ["Lewati ke navigasi aplikasi"] }, "Skip to main content": { "v": ["Lewati ke konten utama"] } } }, { "l": "is", "t": { "Keyboard navigation help": { "v": ["Aðstoð við rötun á lyklaborði"] }, "Skip to app navigation": { "v": ["Sleppa og fara í flakk innan forrits"] }, "Skip to main content": { "v": ["Sleppa og fara í meginefni"] } } }, { "l": "it", "t": {} }, { "l": "ja", "t": { "Keyboard navigation help": { "v": ["キーボード・ナビゲーション・ヘルプ"] }, "Skip to app navigation": { "v": ["アプリのナビゲーションへ移動"] }, "Skip to main content": { "v": ["メインコンテンツへ移動"] } } }, { "l": "ja-JP", "t": { "Keyboard navigation help": { "v": ["キーボード・ナビゲーション・ヘルプ"] }, "Skip to app navigation": { "v": ["アプリのナビゲーションへ移動"] }, "Skip to main content": { "v": ["メインコンテンツへ移動"] } } }, { "l": "ko", "t": { "Keyboard navigation help": { "v": ["키보드 탐색 도움말"] }, "Skip to app navigation": { "v": ["앱 탐색으로 건너뛰기"] }, "Skip to main content": { "v": ["본 내용으로 건너뛰기"] } } }, { "l": "lo", "t": { "Keyboard navigation help": { "v": ["ການຊ່ວຍເຫຼືອການນຳທາງດ້ວຍຄີບອດ"] }, "Skip to app navigation": { "v": ["ຂ້າມໄປທີ່ການນຳທາງຂອງແອັບ"] }, "Skip to main content": { "v": ["ຂ້າມໄປທີ່ເນື້ອຫາຫຼັກ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Keyboard navigation help": { "v": ["Навигација со тастатура"] }, "Skip to app navigation": { "v": ["Прескокни на навигација на апликацијата"] }, "Skip to main content": { "v": ["Прескокни на главна содржина"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Keyboard navigation help": { "v": ["Hjelp for tastaturnavigering"] }, "Skip to app navigation": { "v": ["Hopp til appnavigering"] }, "Skip to main content": { "v": ["Hopp til hovedinnhold"] } } }, { "l": "nl", "t": { "Keyboard navigation help": { "v": ["Hulp voor toetsenbordnavigatie"] }, "Skip to app navigation": { "v": ["Doorgaan naar app-navigatie"] }, "Skip to main content": { "v": ["Naar hoofdinhoud gaan"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Keyboard navigation help": { "v": ["Pomoc w nawigacji za pomocą klawiatury"] }, "Skip to app navigation": { "v": ["Przewiń do nawigacji"] }, "Skip to main content": { "v": ["Przewiń do głównych treści"] } } }, { "l": "pt-BR", "t": { "Keyboard navigation help": { "v": ["Ajuda para navegação pelo teclado"] }, "Skip to app navigation": { "v": ["Ir para navegação de aplicativo"] }, "Skip to main content": { "v": ["Ir para conteúdo principal"] } } }, { "l": "pt-PT", "t": { "Keyboard navigation help": { "v": ["Ajuda à navegação no teclado"] }, "Skip to app navigation": { "v": ["Saltar para navegação da app"] }, "Skip to main content": { "v": ["Saltar para conteúdo principal"] } } }, { "l": "ro", "t": {} }, { "l": "ru", "t": { "Keyboard navigation help": { "v": ["Справка по навигации с помощью клавиатуры"] }, "Skip to app navigation": { "v": ["Перейти к навигации по приложению"] }, "Skip to main content": { "v": ["Перейти к основному содержанию"] } } }, { "l": "sk", "t": { "Keyboard navigation help": { "v": ["Pomoc pri navigácii pomocou klávesnice"] }, "Skip to app navigation": { "v": ["Preskočiť na navigáciu v aplikácii"] }, "Skip to main content": { "v": ["Preskočiť na hlavný obsah"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Keyboard navigation help": { "v": ["Помоћ за навигацију тастатуром"] }, "Skip to app navigation": { "v": ["Прескочи на навигацију апликацијом"] }, "Skip to main content": { "v": ["Прескочи на главни садржај"] } } }, { "l": "sv", "t": { "Keyboard navigation help": { "v": ["Hjälp med tangentbordsnavigering"] }, "Skip to app navigation": { "v": ["Hoppa till appnavigering"] }, "Skip to main content": { "v": ["Hoppa till huvudinnehåll"] } } }, { "l": "tr", "t": { "Keyboard navigation help": { "v": ["Klavye ile gezinme yardımı"] }, "Skip to app navigation": { "v": ["Uygulama gezinmesine git"] }, "Skip to main content": { "v": ["Ana içeriğe git"] } } }, { "l": "uk", "t": { "Keyboard navigation help": { "v": ["Допомога з навігацією клавішами"] }, "Skip to app navigation": { "v": ["Пропустити навігацію по застосунках"] }, "Skip to main content": { "v": ["Перейти одразу до головного вмісту"] } } }, { "l": "uz", "t": { "Keyboard navigation help": { "v": ["Klaviatura navigatsiyasi yordami"] }, "Skip to app navigation": { "v": ["Ilova navigatsiyasiga oʻtish"] }, "Skip to main content": { "v": ["Asosiy tarkibga o'tish"] } } }, { "l": "zh-CN", "t": { "Keyboard navigation help": { "v": ["键盘导航栏帮助"] }, "Skip to app navigation": { "v": ["跳转至应用程序导航页"] }, "Skip to main content": { "v": ["跳转至主要内容"] } } }, { "l": "zh-HK", "t": { "Keyboard navigation help": { "v": ["鍵盤導航幫助"] }, "Skip to app navigation": { "v": ["跳至應用程式導航"] }, "Skip to main content": { "v": ["跳至主要內容"] } } }, { "l": "zh-TW", "t": { "Keyboard navigation help": { "v": ["鍵盤導航說明"] }, "Skip to app navigation": { "v": ["略過應用程式導覽"] }, "Skip to main content": { "v": ["跳至主要內容"] } } }];
const t32 = [{ "l": "ar", "t": { 'Load more "{options}"': { "v": ['تحميل المزيد من "{options}" '] }, "Raw link {options}": { "v": [" الرابط الخام raw link ـ {options}"] }, "Start typing to search": { "v": ["إبدإ كتابة مفردات البحث"] } } }, { "l": "ast", "t": { 'Load more "{options}"': { "v": ["Cargar más «{options}»"] }, "Raw link {options}": { "v": ["Enllaz en bruto {optiones}"] }, "Start typing to search": { "v": ["Comienza a escribir pa buscar"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { 'Load more "{options}"': { "v": ["Načíst další „{options}“"] }, "Raw link {options}": { "v": ["Holý odkaz {options}"] }, "Start typing to search": { "v": ["Vyhledávejte psaním"] } } }, { "l": "cs-CZ", "t": { 'Load more "{options}"': { "v": ["Načíst další „{options}“"] }, "Raw link {options}": { "v": ["Holý odkaz {options}"] }, "Start typing to search": { "v": ["Vyhledávejte psaním"] } } }, { "l": "da", "t": { 'Load more "{options}"': { "v": ['Indlæs flere "{options}"'] }, "Raw link {options}": { "v": ["Rå link {options}"] }, "Start typing to search": { "v": ["Begynd at skrive for at søge"] } } }, { "l": "de", "t": { 'Load more "{options}"': { "v": ['Weitere "{options}" laden'] }, "Raw link {options}": { "v": ["Unverarbeiteter Link {options}"] }, "Start typing to search": { "v": ["Mit der Eingabe beginnen, um zu suchen"] } } }, { "l": "de-DE", "t": { 'Load more "{options}"': { "v": ['Weitere "{options}" laden'] }, "Raw link {options}": { "v": ["Unverarbeiteter Link {options}"] }, "Start typing to search": { "v": ["Mit der Eingabe beginnen, um zu suchen"] } } }, { "l": "el", "t": { 'Load more "{options}"': { "v": ['Φόρτωση περισσότερων "{options}"'] }, "Raw link {options}": { "v": ["Ακατέργαστος σύνδεσμος {options}"] }, "Start typing to search": { "v": ["Ξεκινήστε να πληκτρολογείτε για αναζήτηση"] } } }, { "l": "en-GB", "t": { 'Load more "{options}"': { "v": ['Load more "{options}"'] }, "Raw link {options}": { "v": ["Raw link {options}"] }, "Start typing to search": { "v": ["Start typing to search"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { 'Load more "{options}"': { "v": ['Cargar más "{options}"'] }, "Raw link {options}": { "v": ["Enlace directo {options}"] }, "Start typing to search": { "v": ["Comience a escribir para buscar"] } } }, { "l": "es-AR", "t": { 'Load more "{options}"': { "v": ['Cargar más "{options}"'] }, "Raw link {options}": { "v": ["Enlace directo {options}"] }, "Start typing to search": { "v": ["Comience a escribir para buscar"] } } }, { "l": "es-EC", "t": { "Raw link {options}": { "v": ["Enlace directo {options}"] }, "Start typing to search": { "v": ["Comienza a escribir para buscar"] } } }, { "l": "es-MX", "t": { 'Load more "{options}"': { "v": ['Cargar más "{options}"'] }, "Raw link {options}": { "v": ["Enlace directo {options}"] }, "Start typing to search": { "v": ["Comience a escribir para buscar"] } } }, { "l": "et-EE", "t": { 'Load more "{options}"': { "v": ["Laadi veel „{options}“"] }, "Raw link {options}": { "v": ["Töötlemata link: {options}"] }, "Start typing to search": { "v": ["Alusta otsinguks sisestamist"] } } }, { "l": "eu", "t": { 'Load more "{options}"': { "v": ['Kargatu "{options}" gehiago'] }, "Raw link {options}": { "v": ["Formaturik gabeko esteka {aukerak}"] }, "Start typing to search": { "v": ["Hasi idazten bilatzeko"] } } }, { "l": "fa", "t": { 'Load more "{options}"': { "v": ['بارگذاری بیشتر "{options}"'] }, "Raw link {options}": { "v": ["پیوند خام {options}"] }, "Start typing to search": { "v": ["برای جستجو تایپ کنید"] } } }, { "l": "fi", "t": { 'Load more "{options}"': { "v": ['Lataa lisää "{options}"'] }, "Raw link {options}": { "v": ["Raaka linkki {options}"] }, "Start typing to search": { "v": ["Aloita kirjoittaminen hakeaksesi"] } } }, { "l": "fr", "t": { 'Load more "{options}"': { "v": [`Charger d'avantage "{options}"`] }, "Raw link {options}": { "v": ["Lien brut {options}"] }, "Start typing to search": { "v": ["Commencez à écrire pour rechercher"] } } }, { "l": "ga", "t": { 'Load more "{options}"': { "v": ['Luchtaigh tuilleadh "{options}"'] }, "Raw link {options}": { "v": ["Nasc amh {roghanna}"] }, "Start typing to search": { "v": ["Tosaigh ag clóscríobh chun cuardach a dhéanamh"] } } }, { "l": "gl", "t": { 'Load more "{options}"': { "v": ["Cargar máis «{options}»"] }, "Raw link {options}": { "v": ["Ligazón sen procesar {options}"] }, "Start typing to search": { "v": ["Comece a escribir para buscar"] } } }, { "l": "he", "t": { "Raw link {options}": { "v": ["קישור גולמי {options}"] }, "Start typing to search": { "v": ["התחלת הקלדה מחפשת"] } } }, { "l": "hr", "t": { 'Load more "{options}"': { "v": ["Učitaj još „{options}”"] }, "Raw link {options}": { "v": ["Izravna poveznica {options}"] }, "Start typing to search": { "v": ["Počnite tipkati za pretraživanje"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { 'Load more "{options}"': { "v": ['Muat "{options}" lainnya'] }, "Raw link {options}": { "v": ["Tautan mentah {options}"] }, "Start typing to search": { "v": ["Ketik untuk mulai mencari"] } } }, { "l": "is", "t": { 'Load more "{options}"': { "v": ['Hlaða inn fleiri "{options}"'] }, "Raw link {options}": { "v": ["Hrár tengill {options}"] }, "Start typing to search": { "v": ["Byrjaðu að skrifa til að leita"] } } }, { "l": "it", "t": { 'Load more "{options}"': { "v": ['Carica più "{options}"'] }, "Raw link {options}": { "v": ["Raw link {options}"] }, "Start typing to search": { "v": ["Iniziare a digitare per effettuare la ricerca"] } } }, { "l": "ja", "t": { 'Load more "{options}"': { "v": ['"{options}" をもっと読み込む'] }, "Raw link {options}": { "v": ["未加工のリンク {options}"] }, "Start typing to search": { "v": ["入力を開始して検索します"] } } }, { "l": "ja-JP", "t": { 'Load more "{options}"': { "v": ['"{options}" をもっと読み込む'] }, "Raw link {options}": { "v": ["未加工のリンク {options}"] }, "Start typing to search": { "v": ["入力を開始して検索します"] } } }, { "l": "ko", "t": { 'Load more "{options}"': { "v": ['"{options}" 더 불러오기'] }, "Raw link {options}": { "v": ["{options} 원본 링크"] }, "Start typing to search": { "v": ["입력하여 검색"] } } }, { "l": "lo", "t": { 'Load more "{options}"': { "v": ["ໂຫຼດ “{options}” ເພີ່ມເຕີມ"] }, "Raw link {options}": { "v": ["ລິງດິບ {options}"] }, "Start typing to search": { "v": ["ເລີ່ມພິມເພື່ອຄົ້ນຫາ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { 'Load more "{options}"': { "v": ['Вчитај повеќе "{options}"'] }, "Raw link {options}": { "v": ["Суров линк {options}"] }, "Start typing to search": { "v": ["Почни да пишуваш за пребарување"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { 'Load more "{options}"': { "v": ['Last inn flere "{options}"'] }, "Raw link {options}": { "v": ["Rå lenke {options}"] }, "Start typing to search": { "v": ["Start å skrive for å søke"] } } }, { "l": "nl", "t": { 'Load more "{options}"': { "v": ['Meer "{options}" laden'] }, "Raw link {options}": { "v": ["Ruwe link {options}"] }, "Start typing to search": { "v": ["Start met typen om te zoeken"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { 'Load more "{options}"': { "v": ['Załaduj więcej "{options}"'] }, "Raw link {options}": { "v": ["Surowy odnośnik {options}"] }, "Start typing to search": { "v": ["Zacznij pisać, aby wyszukać"] } } }, { "l": "pt-BR", "t": { 'Load more "{options}"': { "v": ['Carregar mais "{options}"'] }, "Raw link {options}": { "v": ["Link bruto {options}"] }, "Start typing to search": { "v": ["Comece a digitar para pesquisar"] } } }, { "l": "pt-PT", "t": { 'Load more "{options}"': { "v": ['Carregar mais "{options}"'] }, "Raw link {options}": { "v": ["Link inicial {options}"] }, "Start typing to search": { "v": ["Comece a digitar para pesquisar"] } } }, { "l": "ro", "t": { 'Load more "{options}"': { "v": ['Încarcă mai multe "{options}"'] }, "Raw link {options}": { "v": ["Link brut {options}"] }, "Start typing to search": { "v": ["Tastați pentru căutare"] } } }, { "l": "ru", "t": { 'Load more "{options}"': { "v": ['Загрузить больше "{options}""'] }, "Raw link {options}": { "v": ["Необработанная ссылка {options}"] }, "Start typing to search": { "v": ["Начните вводить текст для поиска"] } } }, { "l": "sk", "t": { 'Load more "{options}"': { "v": ['Načítať viac "{options}"'] }, "Raw link {options}": { "v": ["Raw odkaz {options}"] }, "Start typing to search": { "v": ["Začnite písať pre vyhľadávanie"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { 'Load more "{options}"': { "v": ["Учитај још „{options}”"] }, "Raw link {options}": { "v": ["Сирови линк {options}"] }, "Start typing to search": { "v": ["Покрените претрагу куцањем"] } } }, { "l": "sv", "t": { 'Load more "{options}"': { "v": ['Ladda fler "{options}"'] }, "Raw link {options}": { "v": ["Oformaterad länk {options}"] }, "Start typing to search": { "v": ["Börja skriva för att söka"] } } }, { "l": "tr", "t": { 'Load more "{options}"': { "v": ['Diğer "{options}"'] }, "Raw link {options}": { "v": ["Ham bağlantı {options}"] }, "Start typing to search": { "v": ["Aramak için yazmaya başlayın"] } } }, { "l": "uk", "t": { 'Load more "{options}"': { "v": ['Завантажити більше "{options}"'] }, "Raw link {options}": { "v": ["Пряме посилання {options}"] }, "Start typing to search": { "v": ["Почніть вводити для пошуку"] } } }, { "l": "uz", "t": { 'Load more "{options}"': { "v": [`Ko'proq yuklash "{options}"`] }, "Raw link {options}": { "v": [" {options}satr havolasi"] }, "Start typing to search": { "v": ["Qidirish uchun yozishni boshlang"] } } }, { "l": "zh-CN", "t": { 'Load more "{options}"': { "v": ["加载更多 “{options}”"] }, "Raw link {options}": { "v": ["原始链接 {options}"] }, "Start typing to search": { "v": ["开始输入以进行搜索"] } } }, { "l": "zh-HK", "t": { 'Load more "{options}"': { "v": ['載入更多 "{options}"'] }, "Raw link {options}": { "v": ["原始連結 {options}"] }, "Start typing to search": { "v": ["開始輸入以進行搜尋"] } } }, { "l": "zh-TW", "t": { 'Load more "{options}"': { "v": ["載入更多「{options}」"] }, "Raw link {options}": { "v": ["原始連結 {options}"] }, "Start typing to search": { "v": ["開始輸入以進行搜尋"] } } }];
const t33 = [{ "l": "ar", "t": { "Loading …": { "v": ["التحميل جارٍ ..."] } } }, { "l": "ast", "t": {} }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Loading …": { "v": ["Načítání …"] } } }, { "l": "cs-CZ", "t": {} }, { "l": "da", "t": { "Loading …": { "v": ["Indlæser ..."] } } }, { "l": "de", "t": { "Loading …": { "v": ["Wird geladen …"] } } }, { "l": "de-DE", "t": { "Loading …": { "v": ["Wird geladen …"] } } }, { "l": "el", "t": { "Loading …": { "v": ["Φόρτωση  …"] } } }, { "l": "en-GB", "t": { "Loading …": { "v": ["Loading …"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": {} }, { "l": "es-AR", "t": {} }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": {} }, { "l": "et-EE", "t": { "Loading …": { "v": ["Laadin …"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": { "Loading …": { "v": ["در حال بارگذاری ..."] } } }, { "l": "fi", "t": { "Loading …": { "v": ["Ladataan ..."] } } }, { "l": "fr", "t": { "Loading …": { "v": ["Chargement..."] } } }, { "l": "ga", "t": { "Loading …": { "v": ["Ag lódáil …"] } } }, { "l": "gl", "t": { "Loading …": { "v": ["Cargando…"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "Loading …": { "v": ["Učitavanje …"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Loading …": { "v": ["Memuat …"] } } }, { "l": "is", "t": { "Loading …": { "v": ["Hleð inn …"] } } }, { "l": "it", "t": {} }, { "l": "ja", "t": { "Loading …": { "v": ["読み込み中 …"] } } }, { "l": "ja-JP", "t": {} }, { "l": "ko", "t": { "Loading …": { "v": ["로딩 중 ..."] } } }, { "l": "lo", "t": { "Loading …": { "v": ["ກຳລັງໂຫຼດ…"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Loading …": { "v": ["Вчитување …"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Loading …": { "v": ["Laster inn..."] } } }, { "l": "nl", "t": { "Loading …": { "v": ["Laden …"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Loading …": { "v": ["Wczytywanie…"] } } }, { "l": "pt-BR", "t": { "Loading …": { "v": ["Carregando …"] } } }, { "l": "pt-PT", "t": { "Loading …": { "v": ["A carregar..."] } } }, { "l": "ro", "t": {} }, { "l": "ru", "t": { "Loading …": { "v": ["Загрузка …"] } } }, { "l": "sk", "t": { "Loading …": { "v": ["Nahrávam ..."] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Loading …": { "v": ["Учитава се…"] } } }, { "l": "sv", "t": { "Loading …": { "v": ["Laddar ..."] } } }, { "l": "tr", "t": { "Loading …": { "v": ["Yükleniyor…"] } } }, { "l": "uk", "t": { "Loading …": { "v": ["Завантаження …"] } } }, { "l": "uz", "t": { "Loading …": { "v": ["Yuklanmoqda..."] } } }, { "l": "zh-CN", "t": { "Loading …": { "v": ["加载中..."] } } }, { "l": "zh-HK", "t": { "Loading …": { "v": ["加載中 …"] } } }, { "l": "zh-TW", "t": { "Loading …": { "v": ["正在載入……"] } } }];
const t36 = [{ "l": "ar", "t": { "Next": { "v": ["التالي"] }, "Pause slideshow": { "v": ["تجميد عرض الشرائح"] }, "Previous": { "v": ["السابق"] }, "Start slideshow": { "v": ["إبدإ العرض"] } } }, { "l": "ast", "t": { "Next": { "v": ["Siguiente"] }, "Pause slideshow": { "v": ["Posar la presentación de diapositives"] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Aniciar la presentación de diapositives"] } } }, { "l": "br", "t": { "Next": { "v": ["Da heul"] }, "Pause slideshow": { "v": ["Arsav an diaporama"] }, "Previous": { "v": ["A-raok"] }, "Start slideshow": { "v": ["Kregiñ an diaporama"] } } }, { "l": "ca", "t": { "Next": { "v": ["Següent"] }, "Pause slideshow": { "v": ["Atura la presentació"] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Inicia la presentació"] } } }, { "l": "cs", "t": { "Next": { "v": ["Následující"] }, "Pause slideshow": { "v": ["Pozastavit prezentaci"] }, "Previous": { "v": ["Předchozí"] }, "Start slideshow": { "v": ["Spustit prezentaci"] } } }, { "l": "cs-CZ", "t": { "Next": { "v": ["Následující"] }, "Pause slideshow": { "v": ["Pozastavit prezentaci"] }, "Previous": { "v": ["Předchozí"] }, "Start slideshow": { "v": ["Spustit prezentaci"] } } }, { "l": "da", "t": { "Next": { "v": ["Videre"] }, "Pause slideshow": { "v": ["Suspender fremvisning"] }, "Previous": { "v": ["Forrige"] }, "Start slideshow": { "v": ["Start fremvisning"] } } }, { "l": "de", "t": { "Next": { "v": ["Weiter"] }, "Pause slideshow": { "v": ["Diashow pausieren"] }, "Previous": { "v": ["Vorherige"] }, "Start slideshow": { "v": ["Diashow starten"] } } }, { "l": "de-DE", "t": { "Next": { "v": ["Weiter"] }, "Pause slideshow": { "v": ["Diashow pausieren"] }, "Previous": { "v": ["Vorherige"] }, "Start slideshow": { "v": ["Diashow starten"] } } }, { "l": "el", "t": { "Next": { "v": ["Επόμενο"] }, "Pause slideshow": { "v": ["Παύση προβολής διαφανειών"] }, "Previous": { "v": ["Προηγούμενο"] }, "Start slideshow": { "v": ["Έναρξη προβολής διαφανειών"] } } }, { "l": "en-GB", "t": { "Next": { "v": ["Next"] }, "Pause slideshow": { "v": ["Pause slideshow"] }, "Previous": { "v": ["Previous"] }, "Start slideshow": { "v": ["Start slideshow"] } } }, { "l": "eo", "t": { "Next": { "v": ["Sekva"] }, "Pause slideshow": { "v": ["Payzi bildprezenton"] }, "Previous": { "v": ["Antaŭa"] }, "Start slideshow": { "v": ["Komenci bildprezenton"] } } }, { "l": "es", "t": { "Next": { "v": ["Siguiente"] }, "Pause slideshow": { "v": ["Pausar la presentación "] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Iniciar la presentación"] } } }, { "l": "es-AR", "t": { "Next": { "v": ["Siguiente"] }, "Pause slideshow": { "v": ["Pausar la presentación "] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Iniciar la presentación"] } } }, { "l": "es-EC", "t": { "Next": { "v": ["Siguiente"] }, "Pause slideshow": { "v": ["Pausar presentación de diapositivas"] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Iniciar presentación de diapositivas"] } } }, { "l": "es-MX", "t": { "Next": { "v": ["Siguiente"] }, "Pause slideshow": { "v": ["Pausar presentación de diapositivas"] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Iniciar presentación de diapositivas"] } } }, { "l": "et-EE", "t": { "Next": { "v": ["Edasi"] }, "Pause slideshow": { "v": ["Slaidiesitluse paus"] }, "Previous": { "v": ["Eelmine"] }, "Start slideshow": { "v": ["Alusta slaidiesitust"] } } }, { "l": "eu", "t": { "Next": { "v": ["Hurrengoa"] }, "Pause slideshow": { "v": ["Pausatu diaporama"] }, "Previous": { "v": ["Aurrekoa"] }, "Start slideshow": { "v": ["Hasi diaporama"] } } }, { "l": "fa", "t": { "Next": { "v": ["بعدی"] }, "Pause slideshow": { "v": ["توقف نمایش اسلاید"] }, "Previous": { "v": ["قبلی"] }, "Start slideshow": { "v": ["شروع نمایش اسلاید"] } } }, { "l": "fi", "t": { "Next": { "v": ["Seuraava"] }, "Pause slideshow": { "v": ["Keskeytä diaesitys"] }, "Previous": { "v": ["Edellinen"] }, "Start slideshow": { "v": ["Aloita diaesitys"] } } }, { "l": "fr", "t": { "Next": { "v": ["Suivant"] }, "Pause slideshow": { "v": ["Mettre le diaporama en pause"] }, "Previous": { "v": ["Précédent"] }, "Start slideshow": { "v": ["Démarrer le diaporama"] } } }, { "l": "ga", "t": { "Next": { "v": ["Ar aghaidh"] }, "Pause slideshow": { "v": ["Cuir taispeántas sleamhnán ar sos"] }, "Previous": { "v": ["Roimhe Seo"] }, "Start slideshow": { "v": ["Tosaigh taispeántas sleamhnán"] } } }, { "l": "gl", "t": { "Next": { "v": ["Seguinte"] }, "Pause slideshow": { "v": ["Pausar o diaporama"] }, "Previous": { "v": ["Anterir"] }, "Start slideshow": { "v": ["Iniciar o diaporama"] } } }, { "l": "he", "t": { "Next": { "v": ["הבא"] }, "Pause slideshow": { "v": ["השהיית מצגת"] }, "Previous": { "v": ["הקודם"] }, "Start slideshow": { "v": ["התחלת המצגת"] } } }, { "l": "hr", "t": { "Next": { "v": ["Sljedeće"] }, "Pause slideshow": { "v": ["Pauziraj dijaprojekciju"] }, "Previous": { "v": ["Prethodno"] }, "Start slideshow": { "v": ["Pokreni dijaprojekciju"] } } }, { "l": "hu", "t": { "Next": { "v": ["Következő"] }, "Pause slideshow": { "v": ["Diavetítés szüneteltetése"] }, "Previous": { "v": ["Előző"] }, "Start slideshow": { "v": ["Diavetítés indítása"] } } }, { "l": "id", "t": { "Next": { "v": ["Selanjutnya"] }, "Pause slideshow": { "v": ["Jeda tayangan slide"] }, "Previous": { "v": ["Sebelumnya"] }, "Start slideshow": { "v": ["Mulai salindia"] } } }, { "l": "is", "t": { "Next": { "v": ["Næsta"] }, "Pause slideshow": { "v": ["Gera hlé á skyggnusýningu"] }, "Previous": { "v": ["Fyrri"] }, "Start slideshow": { "v": ["Byrja skyggnusýningu"] } } }, { "l": "it", "t": { "Next": { "v": ["Successivo"] }, "Pause slideshow": { "v": ["Presentazione in pausa"] }, "Previous": { "v": ["Precedente"] }, "Start slideshow": { "v": ["Avvia presentazione"] } } }, { "l": "ja", "t": { "Next": { "v": ["次"] }, "Pause slideshow": { "v": ["スライドショーを一時停止"] }, "Previous": { "v": ["前"] }, "Start slideshow": { "v": ["スライドショーを開始"] } } }, { "l": "ja-JP", "t": { "Next": { "v": ["次"] }, "Pause slideshow": { "v": ["スライドショーを一時停止"] }, "Previous": { "v": ["前"] }, "Start slideshow": { "v": ["スライドショーを開始"] } } }, { "l": "ko", "t": { "Next": { "v": ["다음"] }, "Pause slideshow": { "v": ["슬라이드쇼 일시정지"] }, "Previous": { "v": ["이전"] }, "Start slideshow": { "v": ["슬라이드쇼 시작"] } } }, { "l": "lo", "t": { "Next": { "v": ["ຕໍ່ໄປ"] }, "Pause slideshow": { "v": ["ຢຸດສະໄລ້ໂຊຊົ່ວຄາວ"] }, "Previous": { "v": ["ກ່ອນໜ້າ"] }, "Start slideshow": { "v": ["ເລີ່ມສະໄລ້ໂຊ"] } } }, { "l": "lt-LT", "t": { "Next": { "v": ["Kitas"] }, "Pause slideshow": { "v": ["Pristabdyti skaidrių rodymą"] }, "Previous": { "v": ["Ankstesnis"] }, "Start slideshow": { "v": ["Pradėti skaidrių rodymą"] } } }, { "l": "lv", "t": { "Next": { "v": ["Nākamais"] }, "Pause slideshow": { "v": ["Pauzēt slaidrādi"] }, "Previous": { "v": ["Iepriekšējais"] }, "Start slideshow": { "v": ["Sākt slaidrādi"] } } }, { "l": "mk", "t": { "Next": { "v": ["Следно"] }, "Pause slideshow": { "v": ["Пузирај слајдшоу"] }, "Previous": { "v": ["Предходно"] }, "Start slideshow": { "v": ["Стартувај слајдшоу"] } } }, { "l": "my", "t": { "Next": { "v": ["နောက်သို့ဆက်ရန်"] }, "Pause slideshow": { "v": ["စလိုက်ရှိုး ခေတ္တရပ်ရန်"] }, "Previous": { "v": ["ယခင်"] }, "Start slideshow": { "v": ["စလိုက်ရှိုးအား စတင်ရန်"] } } }, { "l": "nb", "t": { "Next": { "v": ["Neste"] }, "Pause slideshow": { "v": ["Pause lysbildefremvisning"] }, "Previous": { "v": ["Forrige"] }, "Start slideshow": { "v": ["Start lysbildefremvisning"] } } }, { "l": "nl", "t": { "Next": { "v": ["Volgende"] }, "Pause slideshow": { "v": ["Diavoorstelling pauzeren"] }, "Previous": { "v": ["Vorige"] }, "Start slideshow": { "v": ["Diavoorstelling starten"] } } }, { "l": "oc", "t": { "Next": { "v": ["Seguent"] }, "Pause slideshow": { "v": ["Metre en pausa lo diaporama"] }, "Previous": { "v": ["Precedent"] }, "Start slideshow": { "v": ["Lançar lo diaporama"] } } }, { "l": "pl", "t": { "Next": { "v": ["Następny"] }, "Pause slideshow": { "v": ["Wstrzymaj pokaz slajdów"] }, "Previous": { "v": ["Poprzedni"] }, "Start slideshow": { "v": ["Rozpocznij pokaz slajdów"] } } }, { "l": "pt-BR", "t": { "Next": { "v": ["Próximo"] }, "Pause slideshow": { "v": ["Pausar apresentação de slides"] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Iniciar apresentação de slides"] } } }, { "l": "pt-PT", "t": { "Next": { "v": ["Seguinte"] }, "Pause slideshow": { "v": ["Pausar diaporama"] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Iniciar diaporama"] } } }, { "l": "ro", "t": { "Next": { "v": ["Următorul"] }, "Pause slideshow": { "v": ["Pauză prezentare de diapozitive"] }, "Previous": { "v": ["Anterior"] }, "Start slideshow": { "v": ["Începeți prezentarea de diapozitive"] } } }, { "l": "ru", "t": { "Next": { "v": ["Следующее"] }, "Pause slideshow": { "v": ["Приостановить показ слйдов"] }, "Previous": { "v": ["Предыдущее"] }, "Start slideshow": { "v": ["Начать показ слайдов"] } } }, { "l": "sk", "t": { "Next": { "v": ["Ďalej"] }, "Pause slideshow": { "v": ["Pozastaviť prezentáciu"] }, "Previous": { "v": ["Predchádzajúce"] }, "Start slideshow": { "v": ["Začať prezentáciu"] } } }, { "l": "sl", "t": { "Next": { "v": ["Naslednji"] }, "Pause slideshow": { "v": ["Ustavi predstavitev"] }, "Previous": { "v": ["Predhodni"] }, "Start slideshow": { "v": ["Začni predstavitev"] } } }, { "l": "sr", "t": { "Next": { "v": ["Следеће"] }, "Pause slideshow": { "v": ["Паузирај слајд шоу"] }, "Previous": { "v": ["Претходно"] }, "Start slideshow": { "v": ["Покрени слајд шоу"] } } }, { "l": "sv", "t": { "Next": { "v": ["Nästa"] }, "Pause slideshow": { "v": ["Pausa bildspelet"] }, "Previous": { "v": ["Föregående"] }, "Start slideshow": { "v": ["Starta bildspelet"] } } }, { "l": "tr", "t": { "Next": { "v": ["Sonraki"] }, "Pause slideshow": { "v": ["Slayt sunumunu duraklat"] }, "Previous": { "v": ["Önceki"] }, "Start slideshow": { "v": ["Slayt sunumunu başlat"] } } }, { "l": "uk", "t": { "Next": { "v": ["Вперед"] }, "Pause slideshow": { "v": ["Пауза у показі слайдів"] }, "Previous": { "v": ["Назад"] }, "Start slideshow": { "v": ["Почати показ слайдів"] } } }, { "l": "uz", "t": { "Next": { "v": ["Keyingi"] }, "Pause slideshow": { "v": ["Slayd-shouni to'xtatib turish"] }, "Previous": { "v": ["Oldingi"] }, "Start slideshow": { "v": ["Slayd-shouni boshlash"] } } }, { "l": "zh-CN", "t": { "Next": { "v": ["下一个"] }, "Pause slideshow": { "v": ["暂停幻灯片"] }, "Previous": { "v": ["上一个"] }, "Start slideshow": { "v": ["开始幻灯片"] } } }, { "l": "zh-HK", "t": { "Next": { "v": ["下一個"] }, "Pause slideshow": { "v": ["暫停幻燈片"] }, "Previous": { "v": ["上一個"] }, "Start slideshow": { "v": ["開始幻燈片"] } } }, { "l": "zh-TW", "t": { "Next": { "v": ["下一個"] }, "Pause slideshow": { "v": ["暫停幻燈片"] }, "Previous": { "v": ["上一個"] }, "Start slideshow": { "v": ["開始幻燈片"] } } }];
const t40 = [{ "l": "ar", "t": { "Provider icon": { "v": ["أيقونة المزوّد"] } } }, { "l": "ast", "t": { "Provider icon": { "v": ["Iconu del fornidor"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Provider icon": { "v": ["Ikona poskytovatele"] } } }, { "l": "cs-CZ", "t": { "Provider icon": { "v": ["Ikona poskytovatele"] } } }, { "l": "da", "t": { "Provider icon": { "v": ["Udbyder ikon"] } } }, { "l": "de", "t": { "Provider icon": { "v": ["Anbietersymbol"] } } }, { "l": "de-DE", "t": { "Provider icon": { "v": ["Anbietersymbol"] } } }, { "l": "el", "t": { "Provider icon": { "v": ["Εικονίδιο παρόχου"] } } }, { "l": "en-GB", "t": { "Provider icon": { "v": ["Provider icon"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Provider icon": { "v": ["Ícono del proveedor"] } } }, { "l": "es-AR", "t": { "Provider icon": { "v": ["Ícono del proveedor"] } } }, { "l": "es-EC", "t": { "Provider icon": { "v": ["Ícono del proveedor"] } } }, { "l": "es-MX", "t": { "Provider icon": { "v": ["Ícono del proveedor"] } } }, { "l": "et-EE", "t": { "Provider icon": { "v": ["Teenusepakkuja ikoon"] } } }, { "l": "eu", "t": { "Provider icon": { "v": ["Hornitzailearen ikonoa"] } } }, { "l": "fa", "t": { "Provider icon": { "v": ["آیکون ارائه دهنده"] } } }, { "l": "fi", "t": { "Provider icon": { "v": ["Palveluntarjoajan kuvake"] } } }, { "l": "fr", "t": { "Provider icon": { "v": ["Icône du fournisseur"] } } }, { "l": "ga", "t": { "Provider icon": { "v": ["Deilbhín soláthraí"] } } }, { "l": "gl", "t": { "Provider icon": { "v": ["Icona do provedor"] } } }, { "l": "he", "t": { "Provider icon": { "v": ["סמל ספק"] } } }, { "l": "hr", "t": { "Provider icon": { "v": ["Ikona pružatelja"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Provider icon": { "v": ["Ikon penyedia"] } } }, { "l": "is", "t": { "Provider icon": { "v": ["Táknmynd þjónustuveitu"] } } }, { "l": "it", "t": { "Provider icon": { "v": ["Icona del provider"] } } }, { "l": "ja", "t": { "Provider icon": { "v": ["プロバイダーのアイコン"] } } }, { "l": "ja-JP", "t": { "Provider icon": { "v": ["プロバイダーのアイコン"] } } }, { "l": "ko", "t": { "Provider icon": { "v": ["제공자 아이콘"] } } }, { "l": "lo", "t": { "Provider icon": { "v": ["ໄອຄອນຜູ້ໃຫ້ບໍລິການ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Provider icon": { "v": ["Икона на давател"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Provider icon": { "v": ["Leverandørikon"] } } }, { "l": "nl", "t": { "Provider icon": { "v": ["Provider-pictogram"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Provider icon": { "v": ["Dostawca ikony"] } } }, { "l": "pt-BR", "t": { "Provider icon": { "v": ["Ícone do provedor"] } } }, { "l": "pt-PT", "t": { "Provider icon": { "v": ["Ícone do fornecedor"] } } }, { "l": "ro", "t": { "Provider icon": { "v": ["Provider pentru icon"] } } }, { "l": "ru", "t": { "Provider icon": { "v": ["Значок поставщика"] } } }, { "l": "sk", "t": { "Provider icon": { "v": ["Ikonka poskytovateľa"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Provider icon": { "v": ["Икона пружаоца"] } } }, { "l": "sv", "t": { "Provider icon": { "v": ["Leverantörsikon"] } } }, { "l": "tr", "t": { "Provider icon": { "v": ["Hizmet sağlayıcı simgesi"] } } }, { "l": "uk", "t": { "Provider icon": { "v": ["Піктограма постачальника"] } } }, { "l": "uz", "t": { "Provider icon": { "v": ["Provayder belgisi"] } } }, { "l": "zh-CN", "t": { "Provider icon": { "v": ["提供者图标"] } } }, { "l": "zh-HK", "t": { "Provider icon": { "v": ["提供者圖示"] } } }, { "l": "zh-TW", "t": { "Provider icon": { "v": ["提供者圖示"] } } }];
const t42 = [{ "l": "ar", "t": { "Search": { "v": ["بحث"] } } }, { "l": "ast", "t": { "Search": { "v": ["Buscar"] } } }, { "l": "br", "t": { "Search": { "v": ["Klask"] } } }, { "l": "ca", "t": { "Search": { "v": ["Cerca"] } } }, { "l": "cs", "t": { "Search": { "v": ["Hledat"] } } }, { "l": "cs-CZ", "t": { "Search": { "v": ["Hledat"] } } }, { "l": "da", "t": { "Search": { "v": ["Søg"] } } }, { "l": "de", "t": { "Search": { "v": ["Suche"] } } }, { "l": "de-DE", "t": { "Search": { "v": ["Suche"] } } }, { "l": "el", "t": { "Search": { "v": ["Αναζήτηση"] } } }, { "l": "en-GB", "t": { "Search": { "v": ["Search"] } } }, { "l": "eo", "t": { "Search": { "v": ["Serĉi"] } } }, { "l": "es", "t": { "Search": { "v": ["Buscar"] } } }, { "l": "es-AR", "t": { "Search": { "v": ["Buscar"] } } }, { "l": "es-EC", "t": { "Search": { "v": ["Buscar"] } } }, { "l": "es-MX", "t": { "Search": { "v": ["Buscar"] } } }, { "l": "et-EE", "t": { "Search": { "v": ["Otsing"] } } }, { "l": "eu", "t": { "Search": { "v": ["Bilatu"] } } }, { "l": "fa", "t": { "Search": { "v": ["جستجو"] } } }, { "l": "fi", "t": { "Search": { "v": ["Etsi"] } } }, { "l": "fr", "t": { "Search": { "v": ["Rechercher"] } } }, { "l": "ga", "t": { "Search": { "v": ["Cuardach"] } } }, { "l": "gl", "t": { "Search": { "v": ["Buscar"] } } }, { "l": "he", "t": { "Search": { "v": ["חיפוש"] } } }, { "l": "hr", "t": { "Search": { "v": ["Traži"] } } }, { "l": "hu", "t": { "Search": { "v": ["Keresés"] } } }, { "l": "id", "t": { "Search": { "v": ["Cari"] } } }, { "l": "is", "t": { "Search": { "v": ["Leita"] } } }, { "l": "it", "t": { "Search": { "v": ["Cerca"] } } }, { "l": "ja", "t": { "Search": { "v": ["検索"] } } }, { "l": "ja-JP", "t": { "Search": { "v": ["検索"] } } }, { "l": "ko", "t": { "Search": { "v": ["검색"] } } }, { "l": "lo", "t": { "Search": { "v": ["ຄົ້ນຫາ"] } } }, { "l": "lt-LT", "t": { "Search": { "v": ["Ieškoti"] } } }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Search": { "v": ["Барај"] } } }, { "l": "my", "t": { "Search": { "v": ["ရှာဖွေရန်"] } } }, { "l": "nb", "t": { "Search": { "v": ["Søk"] } } }, { "l": "nl", "t": { "Search": { "v": ["Zoeken"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Search": { "v": ["Szukaj"] } } }, { "l": "pt-BR", "t": { "Search": { "v": ["Pesquisar"] } } }, { "l": "pt-PT", "t": { "Search": { "v": ["Pesquisar"] } } }, { "l": "ro", "t": { "Search": { "v": ["Căutare"] } } }, { "l": "ru", "t": { "Search": { "v": ["Поиск"] } } }, { "l": "sk", "t": { "Search": { "v": ["Hľadať"] } } }, { "l": "sl", "t": { "Search": { "v": ["Iskanje"] } } }, { "l": "sr", "t": { "Search": { "v": ["Претражи"] } } }, { "l": "sv", "t": { "Search": { "v": ["Sök"] } } }, { "l": "tr", "t": { "Search": { "v": ["Ara"] } } }, { "l": "uk", "t": { "Search": { "v": ["Пошук"] } } }, { "l": "uz", "t": { "Search": { "v": ["Qidiruv"] } } }, { "l": "zh-CN", "t": { "Search": { "v": ["搜索"] } } }, { "l": "zh-HK", "t": { "Search": { "v": ["搜尋"] } } }, { "l": "zh-TW", "t": { "Search": { "v": ["搜尋"] } } }];
const t45 = [{ "l": "ar", "t": { "Select provider": { "v": ["اختر مزود"] } } }, { "l": "ast", "t": { "Select provider": { "v": ["Seleicionar el fornidor"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "Select provider": { "v": ["Vybrat poskytovatele"] } } }, { "l": "cs-CZ", "t": { "Select provider": { "v": ["Vybrat poskytovatele"] } } }, { "l": "da", "t": { "Select provider": { "v": ["Vælg udbyder"] } } }, { "l": "de", "t": { "Select provider": { "v": ["Anbieter auswählen"] } } }, { "l": "de-DE", "t": { "Select provider": { "v": ["Anbieter auswählen"] } } }, { "l": "el", "t": { "Select provider": { "v": ["Επιλογή παρόχου"] } } }, { "l": "en-GB", "t": { "Select provider": { "v": ["Select provider"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Select provider": { "v": ["Seleccione proveedor"] } } }, { "l": "es-AR", "t": { "Select provider": { "v": ["Elija proveedor"] } } }, { "l": "es-EC", "t": { "Select provider": { "v": ["Seleccionar proveedor"] } } }, { "l": "es-MX", "t": { "Select provider": { "v": ["Seleccionar proveedor"] } } }, { "l": "et-EE", "t": { "Select provider": { "v": ["Vali teenuspakkuja"] } } }, { "l": "eu", "t": { "Select provider": { "v": ["Hautatu hornitzailea"] } } }, { "l": "fa", "t": { "Select provider": { "v": ["ارائه دهنده را انتخاب کنید"] } } }, { "l": "fi", "t": { "Select provider": { "v": ["Valitse tarjoaja"] } } }, { "l": "fr", "t": { "Select provider": { "v": ["Sélectionner un fournisseur"] } } }, { "l": "ga", "t": { "Select provider": { "v": ["Roghnaigh soláthraí"] } } }, { "l": "gl", "t": { "Select provider": { "v": ["Seleccione o provedor"] } } }, { "l": "he", "t": { "Select provider": { "v": ["בחירת ספק"] } } }, { "l": "hr", "t": { "Select provider": { "v": ["Odaberi pružatelja"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "Select provider": { "v": ["Pilih penyedia"] } } }, { "l": "is", "t": { "Select provider": { "v": ["Veldu þjónustuveitu"] } } }, { "l": "it", "t": { "Select provider": { "v": ["Selezionare il provider"] } } }, { "l": "ja", "t": { "Select provider": { "v": ["プロバイダーを選択"] } } }, { "l": "ja-JP", "t": { "Select provider": { "v": ["プロバイダーを選択"] } } }, { "l": "ko", "t": { "Select provider": { "v": ["제공자 선택"] } } }, { "l": "lo", "t": { "Select provider": { "v": ["ເລືອກຜູ້ໃຫ້ບໍລິການ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Select provider": { "v": ["Избери провајдер"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Select provider": { "v": ["Velg leverandør"] } } }, { "l": "nl", "t": { "Select provider": { "v": ["Selecteer provider"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Select provider": { "v": ["Wybierz dostawcę"] } } }, { "l": "pt-BR", "t": { "Select provider": { "v": ["Selecione provedor"] } } }, { "l": "pt-PT", "t": { "Select provider": { "v": ["Selecionar fornecedor"] } } }, { "l": "ro", "t": { "Select provider": { "v": ["Selectați providerul"] } } }, { "l": "ru", "t": { "Select provider": { "v": ["Выбрать поставщика"] } } }, { "l": "sk", "t": { "Select provider": { "v": ["Vybrať poskytovateľa"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "Select provider": { "v": ["Изаберите пружаоца"] } } }, { "l": "sv", "t": { "Select provider": { "v": ["Välj leverantör"] } } }, { "l": "tr", "t": { "Select provider": { "v": ["Hizmet sağlayıcı seçin"] } } }, { "l": "uk", "t": { "Select provider": { "v": ["Виберіть постачальника"] } } }, { "l": "uz", "t": { "Select provider": { "v": ["Provayderni tanlang"] } } }, { "l": "zh-CN", "t": { "Select provider": { "v": ["选择提供者"] } } }, { "l": "zh-HK", "t": { "Select provider": { "v": ["選擇提供者"] } } }, { "l": "zh-TW", "t": { "Select provider": { "v": ["選取提供者"] } } }];
const t46 = [{ "l": "ar", "t": { "Settings": { "v": ["الإعدادات"] } } }, { "l": "ast", "t": { "Settings": { "v": ["Configuración"] } } }, { "l": "br", "t": { "Settings": { "v": ["Arventennoù"] } } }, { "l": "ca", "t": { "Settings": { "v": ["Paràmetres"] } } }, { "l": "cs", "t": { "Settings": { "v": ["Nastavení"] } } }, { "l": "cs-CZ", "t": { "Settings": { "v": ["Nastavení"] } } }, { "l": "da", "t": { "Settings": { "v": ["Indstillinger"] } } }, { "l": "de", "t": { "Settings": { "v": ["Einstellungen"] } } }, { "l": "de-DE", "t": { "Settings": { "v": ["Einstellungen"] } } }, { "l": "el", "t": { "Settings": { "v": ["Ρυθμίσεις"] } } }, { "l": "en-GB", "t": { "Settings": { "v": ["Settings"] } } }, { "l": "eo", "t": { "Settings": { "v": ["Agordo"] } } }, { "l": "es", "t": { "Settings": { "v": ["Ajustes"] } } }, { "l": "es-AR", "t": { "Settings": { "v": ["Configuraciones"] } } }, { "l": "es-EC", "t": { "Settings": { "v": ["Configuraciones"] } } }, { "l": "es-MX", "t": { "Settings": { "v": ["Configuración"] } } }, { "l": "et-EE", "t": { "Settings": { "v": ["Seadistused"] } } }, { "l": "eu", "t": { "Settings": { "v": ["Ezarpenak"] } } }, { "l": "fa", "t": { "Settings": { "v": ["تنظیمات"] } } }, { "l": "fi", "t": { "Settings": { "v": ["Asetukset"] } } }, { "l": "fr", "t": { "Settings": { "v": ["Paramètres"] } } }, { "l": "ga", "t": { "Settings": { "v": ["Socruithe"] } } }, { "l": "gl", "t": { "Settings": { "v": ["Axustes"] } } }, { "l": "he", "t": { "Settings": { "v": ["הגדרות"] } } }, { "l": "hr", "t": { "Settings": { "v": ["Postavke"] } } }, { "l": "hu", "t": { "Settings": { "v": ["Beállítások"] } } }, { "l": "id", "t": { "Settings": { "v": ["Pengaturan"] } } }, { "l": "is", "t": { "Settings": { "v": ["Stillingar"] } } }, { "l": "it", "t": { "Settings": { "v": ["Impostazioni"] } } }, { "l": "ja", "t": { "Settings": { "v": ["設定"] } } }, { "l": "ja-JP", "t": { "Settings": { "v": ["設定"] } } }, { "l": "ko", "t": { "Settings": { "v": ["선택"] } } }, { "l": "lo", "t": { "Settings": { "v": ["ການຕັ້ງຄ່າ"] } } }, { "l": "lt-LT", "t": { "Settings": { "v": ["Nustatymai"] } } }, { "l": "lv", "t": { "Settings": { "v": ["Iestatījumi"] } } }, { "l": "mk", "t": { "Settings": { "v": ["Параметри"] } } }, { "l": "my", "t": { "Settings": { "v": ["ချိန်ညှိချက်များ"] } } }, { "l": "nb", "t": { "Settings": { "v": ["Innstillinger"] } } }, { "l": "nl", "t": { "Settings": { "v": ["Instellingen"] } } }, { "l": "oc", "t": { "Settings": { "v": ["Paramètres"] } } }, { "l": "pl", "t": { "Settings": { "v": ["Ustawienia"] } } }, { "l": "pt-BR", "t": { "Settings": { "v": ["Configurações"] } } }, { "l": "pt-PT", "t": { "Settings": { "v": ["Definições"] } } }, { "l": "ro", "t": { "Settings": { "v": ["Setări"] } } }, { "l": "ru", "t": { "Settings": { "v": ["Параметры"] } } }, { "l": "sk", "t": { "Settings": { "v": ["Nastavenia"] } } }, { "l": "sl", "t": { "Settings": { "v": ["Nastavitve"] } } }, { "l": "sr", "t": { "Settings": { "v": ["Поставке"] } } }, { "l": "sv", "t": { "Settings": { "v": ["Inställningar"] } } }, { "l": "tr", "t": { "Settings": { "v": ["Ayarlar"] } } }, { "l": "uk", "t": { "Settings": { "v": ["Налаштування"] } } }, { "l": "uz", "t": { "Settings": { "v": ["Sozlamalar"] } } }, { "l": "zh-CN", "t": { "Settings": { "v": ["设置"] } } }, { "l": "zh-HK", "t": { "Settings": { "v": ["設定"] } } }, { "l": "zh-TW", "t": { "Settings": { "v": ["設定"] } } }];
const t50 = [{ "l": "ar", "t": { "Undo changes": { "v": ["تراجَع عن التغييرات"] } } }, { "l": "ast", "t": { "Undo changes": { "v": ["Desfacer los cambeos"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": { "Undo changes": { "v": ["Desfés els canvis"] } } }, { "l": "cs", "t": { "Undo changes": { "v": ["Vzít změny zpět"] } } }, { "l": "cs-CZ", "t": { "Undo changes": { "v": ["Vzít změny zpět"] } } }, { "l": "da", "t": { "Undo changes": { "v": ["Fortryd ændringer"] } } }, { "l": "de", "t": { "Undo changes": { "v": ["Änderungen rückgängig machen"] } } }, { "l": "de-DE", "t": { "Undo changes": { "v": ["Änderungen rückgängig machen"] } } }, { "l": "el", "t": { "Undo changes": { "v": ["Αναίρεση Αλλαγών"] } } }, { "l": "en-GB", "t": { "Undo changes": { "v": ["Undo changes"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "Undo changes": { "v": ["Deshacer cambios"] } } }, { "l": "es-AR", "t": { "Undo changes": { "v": ["Deshacer cambios"] } } }, { "l": "es-EC", "t": { "Undo changes": { "v": ["Deshacer cambios"] } } }, { "l": "es-MX", "t": { "Undo changes": { "v": ["Deshacer cambios"] } } }, { "l": "et-EE", "t": { "Undo changes": { "v": ["Pööra muudatused tagasi"] } } }, { "l": "eu", "t": { "Undo changes": { "v": ["Aldaketak desegin"] } } }, { "l": "fa", "t": { "Undo changes": { "v": ["لغو تغییرات"] } } }, { "l": "fi", "t": { "Undo changes": { "v": ["Kumoa muutokset"] } } }, { "l": "fr", "t": { "Undo changes": { "v": ["Annuler les changements"] } } }, { "l": "ga", "t": { "Undo changes": { "v": ["Cealaigh athruithe"] } } }, { "l": "gl", "t": { "Undo changes": { "v": ["Desfacer os cambios"] } } }, { "l": "he", "t": { "Undo changes": { "v": ["ביטול שינויים"] } } }, { "l": "hr", "t": { "Undo changes": { "v": ["Poništi promjene"] } } }, { "l": "hu", "t": { "Undo changes": { "v": ["Változtatások visszavonása"] } } }, { "l": "id", "t": { "Undo changes": { "v": ["Urungkan perubahan"] } } }, { "l": "is", "t": { "Undo changes": { "v": ["Afturkalla breytingar"] } } }, { "l": "it", "t": { "Undo changes": { "v": ["Cancella i cambiamenti"] } } }, { "l": "ja", "t": { "Undo changes": { "v": ["変更を取り消し"] } } }, { "l": "ja-JP", "t": { "Undo changes": { "v": ["変更を取り消し"] } } }, { "l": "ko", "t": { "Undo changes": { "v": ["변경 되돌리기"] } } }, { "l": "lo", "t": { "Undo changes": { "v": ["ຍ້ອນຄືນການປ່ຽນແປງ"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "Undo changes": { "v": ["Врати ги промените"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "Undo changes": { "v": ["Tilbakestill endringer"] } } }, { "l": "nl", "t": { "Undo changes": { "v": ["Wijzigingen ongedaan maken"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "Undo changes": { "v": ["Cofnij zmiany"] } } }, { "l": "pt-BR", "t": { "Undo changes": { "v": ["Desfazer modificações"] } } }, { "l": "pt-PT", "t": { "Undo changes": { "v": ["Anular alterações"] } } }, { "l": "ro", "t": { "Undo changes": { "v": ["Anularea modificărilor"] } } }, { "l": "ru", "t": { "Undo changes": { "v": ["Отменить изменения"] } } }, { "l": "sk", "t": { "Undo changes": { "v": ["Vrátiť zmeny"] } } }, { "l": "sl", "t": { "Undo changes": { "v": ["Razveljavi spremembe"] } } }, { "l": "sr", "t": { "Undo changes": { "v": ["Поништи измене"] } } }, { "l": "sv", "t": { "Undo changes": { "v": ["Ångra ändringar"] } } }, { "l": "tr", "t": { "Undo changes": { "v": ["Değişiklikleri geri al"] } } }, { "l": "uk", "t": { "Undo changes": { "v": ["Скасувати зміни"] } } }, { "l": "uz", "t": { "Undo changes": { "v": ["O'zgarishlarni bekor qilish"] } } }, { "l": "zh-CN", "t": { "Undo changes": { "v": ["撤销更改"] } } }, { "l": "zh-HK", "t": { "Undo changes": { "v": ["取消更改"] } } }, { "l": "zh-TW", "t": { "Undo changes": { "v": ["還原變更"] } } }];
const t51 = [{ "l": "ar", "t": { "User status: {status}": { "v": ["حالة المستخدِم: {status}"] } } }, { "l": "ast", "t": { "User status: {status}": { "v": ["Estáu del usuariu: {status}"] } } }, { "l": "br", "t": {} }, { "l": "ca", "t": {} }, { "l": "cs", "t": { "User status: {status}": { "v": ["Stav uživatele: {status}"] } } }, { "l": "cs-CZ", "t": { "User status: {status}": { "v": ["Stav uživatele: {status}"] } } }, { "l": "da", "t": { "User status: {status}": { "v": ["Brugerstatus: {status}"] } } }, { "l": "de", "t": { "User status: {status}": { "v": ["Benutzerstatus: {status}"] } } }, { "l": "de-DE", "t": { "User status: {status}": { "v": ["Benutzerstatus: {status}"] } } }, { "l": "el", "t": { "User status: {status}": { "v": ["Κατάσταση χρήστη: {status}"] } } }, { "l": "en-GB", "t": { "User status: {status}": { "v": ["User status: {status}"] } } }, { "l": "eo", "t": {} }, { "l": "es", "t": { "User status: {status}": { "v": ["Estatus del usuario: {status}"] } } }, { "l": "es-AR", "t": { "User status: {status}": { "v": ["Estado del usuario: {status}"] } } }, { "l": "es-EC", "t": {} }, { "l": "es-MX", "t": { "User status: {status}": { "v": ["Estado del usuario: {status}"] } } }, { "l": "et-EE", "t": { "User status: {status}": { "v": ["Kasutaja olek: {status}"] } } }, { "l": "eu", "t": {} }, { "l": "fa", "t": { "User status: {status}": { "v": ["وضعیت کاربر: {status}"] } } }, { "l": "fi", "t": { "User status: {status}": { "v": ["Käyttäjän tila: {status}"] } } }, { "l": "fr", "t": { "User status: {status}": { "v": ["Statut de l'utilisateur : {status}"] } } }, { "l": "ga", "t": { "User status: {status}": { "v": ["Stádas úsáideora: {status}"] } } }, { "l": "gl", "t": { "User status: {status}": { "v": ["Estado do usuario: {status}"] } } }, { "l": "he", "t": {} }, { "l": "hr", "t": { "User status: {status}": { "v": ["Status korisnika: {status}"] } } }, { "l": "hu", "t": {} }, { "l": "id", "t": { "User status: {status}": { "v": ["Status pengguna: {status}"] } } }, { "l": "is", "t": { "User status: {status}": { "v": ["Staða notanda: {status}"] } } }, { "l": "it", "t": { "User status: {status}": { "v": ["Stato dell'utente: {status}"] } } }, { "l": "ja", "t": { "User status: {status}": { "v": ["ユーザのステータス: {status}"] } } }, { "l": "ja-JP", "t": { "User status: {status}": { "v": ["ユーザのステータス: {status}"] } } }, { "l": "ko", "t": { "User status: {status}": { "v": ["사용자 상태: {status}"] } } }, { "l": "lo", "t": { "User status: {status}": { "v": ["ສະຖານະຜູ້ໃຊ້: {status}"] } } }, { "l": "lt-LT", "t": {} }, { "l": "lv", "t": {} }, { "l": "mk", "t": { "User status: {status}": { "v": ["Статус: {status}"] } } }, { "l": "my", "t": {} }, { "l": "nb", "t": { "User status: {status}": { "v": ["Brukerstatus: {status}"] } } }, { "l": "nl", "t": { "User status: {status}": { "v": ["Gebruikersstatus: {status}"] } } }, { "l": "oc", "t": {} }, { "l": "pl", "t": { "User status: {status}": { "v": ["Status użytkownika: {status}"] } } }, { "l": "pt-BR", "t": { "User status: {status}": { "v": ["Status do usuário: {status}"] } } }, { "l": "pt-PT", "t": { "User status: {status}": { "v": ["Estado do utilizador: {status}"] } } }, { "l": "ro", "t": { "User status: {status}": { "v": ["Status utilizator: {status}"] } } }, { "l": "ru", "t": { "User status: {status}": { "v": ["Статус пользователя: {status}"] } } }, { "l": "sk", "t": { "User status: {status}": { "v": ["Stav užívateľa: {status}"] } } }, { "l": "sl", "t": {} }, { "l": "sr", "t": { "User status: {status}": { "v": ["Статус корисника: {status}"] } } }, { "l": "sv", "t": { "User status: {status}": { "v": ["Användarstatus: {status}"] } } }, { "l": "tr", "t": { "User status: {status}": { "v": ["Kullanıcı durumu: {status}"] } } }, { "l": "uk", "t": { "User status: {status}": { "v": ["Статус користувача: {status}"] } } }, { "l": "uz", "t": { "User status: {status}": { "v": ["Foydalanuvchi holati: {status}"] } } }, { "l": "zh-CN", "t": { "User status: {status}": { "v": ["用户状态：{status}"] } } }, { "l": "zh-HK", "t": { "User status: {status}": { "v": ["用戶狀態：{status}"] } } }, { "l": "zh-TW", "t": { "User status: {status}": { "v": ["使用者狀態：{status}"] } } }];
const _export_sfc = (sfc, props) => {
  const target2 = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target2[key] = val;
  }
  return target2;
};
function bind(fn, thisArg) {
  return function wrap() {
    return fn.apply(thisArg, arguments);
  };
}
const { toString } = Object.prototype;
const { getPrototypeOf } = Object;
const { iterator, toStringTag } = Symbol;
const kindOf = /* @__PURE__ */ ((cache) => (thing) => {
  const str = toString.call(thing);
  return cache[str] || (cache[str] = str.slice(8, -1).toLowerCase());
})(/* @__PURE__ */ Object.create(null));
const kindOfTest = (type) => {
  type = type.toLowerCase();
  return (thing) => kindOf(thing) === type;
};
const typeOfTest = (type) => (thing) => typeof thing === type;
const { isArray } = Array;
const isUndefined = typeOfTest("undefined");
function isBuffer(val) {
  return val !== null && !isUndefined(val) && val.constructor !== null && !isUndefined(val.constructor) && isFunction$1(val.constructor.isBuffer) && val.constructor.isBuffer(val);
}
const isArrayBuffer = kindOfTest("ArrayBuffer");
function isArrayBufferView(val) {
  let result;
  if (typeof ArrayBuffer !== "undefined" && ArrayBuffer.isView) {
    result = ArrayBuffer.isView(val);
  } else {
    result = val && val.buffer && isArrayBuffer(val.buffer);
  }
  return result;
}
const isString = typeOfTest("string");
const isFunction$1 = typeOfTest("function");
const isNumber = typeOfTest("number");
const isObject = (thing) => thing !== null && typeof thing === "object";
const isBoolean = (thing) => thing === true || thing === false;
const isPlainObject = (val) => {
  if (kindOf(val) !== "object") {
    return false;
  }
  const prototype2 = getPrototypeOf(val);
  return (prototype2 === null || prototype2 === Object.prototype || Object.getPrototypeOf(prototype2) === null) && !(toStringTag in val) && !(iterator in val);
};
const isEmptyObject = (val) => {
  if (!isObject(val) || isBuffer(val)) {
    return false;
  }
  try {
    return Object.keys(val).length === 0 && Object.getPrototypeOf(val) === Object.prototype;
  } catch (e) {
    return false;
  }
};
const isDate = kindOfTest("Date");
const isFile = kindOfTest("File");
const isBlob = kindOfTest("Blob");
const isFileList = kindOfTest("FileList");
const isStream = (val) => isObject(val) && isFunction$1(val.pipe);
const isFormData = (thing) => {
  let kind;
  return thing && (typeof FormData === "function" && thing instanceof FormData || isFunction$1(thing.append) && ((kind = kindOf(thing)) === "formdata" || // detect form-data instance
  kind === "object" && isFunction$1(thing.toString) && thing.toString() === "[object FormData]"));
};
const isURLSearchParams = kindOfTest("URLSearchParams");
const [isReadableStream, isRequest, isResponse, isHeaders] = [
  "ReadableStream",
  "Request",
  "Response",
  "Headers"
].map(kindOfTest);
const trim = (str) => str.trim ? str.trim() : str.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "");
function forEach(obj, fn, { allOwnKeys = false } = {}) {
  if (obj === null || typeof obj === "undefined") {
    return;
  }
  let i;
  let l;
  if (typeof obj !== "object") {
    obj = [obj];
  }
  if (isArray(obj)) {
    for (i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    if (isBuffer(obj)) {
      return;
    }
    const keys = allOwnKeys ? Object.getOwnPropertyNames(obj) : Object.keys(obj);
    const len = keys.length;
    let key;
    for (i = 0; i < len; i++) {
      key = keys[i];
      fn.call(null, obj[key], key, obj);
    }
  }
}
function findKey(obj, key) {
  if (isBuffer(obj)) {
    return null;
  }
  key = key.toLowerCase();
  const keys = Object.keys(obj);
  let i = keys.length;
  let _key;
  while (i-- > 0) {
    _key = keys[i];
    if (key === _key.toLowerCase()) {
      return _key;
    }
  }
  return null;
}
const _global = (() => {
  if (typeof globalThis !== "undefined") return globalThis;
  return typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : global;
})();
const isContextDefined = (context) => !isUndefined(context) && context !== _global;
function merge() {
  const { caseless, skipUndefined } = isContextDefined(this) && this || {};
  const result = {};
  const assignValue = (val, key) => {
    if (key === "__proto__" || key === "constructor" || key === "prototype") {
      return;
    }
    const targetKey = caseless && findKey(result, key) || key;
    if (isPlainObject(result[targetKey]) && isPlainObject(val)) {
      result[targetKey] = merge(result[targetKey], val);
    } else if (isPlainObject(val)) {
      result[targetKey] = merge({}, val);
    } else if (isArray(val)) {
      result[targetKey] = val.slice();
    } else if (!skipUndefined || !isUndefined(val)) {
      result[targetKey] = val;
    }
  };
  for (let i = 0, l = arguments.length; i < l; i++) {
    arguments[i] && forEach(arguments[i], assignValue);
  }
  return result;
}
const extend = (a, b, thisArg, { allOwnKeys } = {}) => {
  forEach(
    b,
    (val, key) => {
      if (thisArg && isFunction$1(val)) {
        Object.defineProperty(a, key, {
          value: bind(val, thisArg),
          writable: true,
          enumerable: true,
          configurable: true
        });
      } else {
        Object.defineProperty(a, key, {
          value: val,
          writable: true,
          enumerable: true,
          configurable: true
        });
      }
    },
    { allOwnKeys }
  );
  return a;
};
const stripBOM = (content) => {
  if (content.charCodeAt(0) === 65279) {
    content = content.slice(1);
  }
  return content;
};
const inherits = (constructor, superConstructor, props, descriptors) => {
  constructor.prototype = Object.create(
    superConstructor.prototype,
    descriptors
  );
  Object.defineProperty(constructor.prototype, "constructor", {
    value: constructor,
    writable: true,
    enumerable: false,
    configurable: true
  });
  Object.defineProperty(constructor, "super", {
    value: superConstructor.prototype
  });
  props && Object.assign(constructor.prototype, props);
};
const toFlatObject = (sourceObj, destObj, filter2, propFilter) => {
  let props;
  let i;
  let prop;
  const merged = {};
  destObj = destObj || {};
  if (sourceObj == null) return destObj;
  do {
    props = Object.getOwnPropertyNames(sourceObj);
    i = props.length;
    while (i-- > 0) {
      prop = props[i];
      if ((!propFilter || propFilter(prop, sourceObj, destObj)) && !merged[prop]) {
        destObj[prop] = sourceObj[prop];
        merged[prop] = true;
      }
    }
    sourceObj = filter2 !== false && getPrototypeOf(sourceObj);
  } while (sourceObj && (!filter2 || filter2(sourceObj, destObj)) && sourceObj !== Object.prototype);
  return destObj;
};
const endsWith = (str, searchString, position) => {
  str = String(str);
  if (position === void 0 || position > str.length) {
    position = str.length;
  }
  position -= searchString.length;
  const lastIndex = str.indexOf(searchString, position);
  return lastIndex !== -1 && lastIndex === position;
};
const toArray = (thing) => {
  if (!thing) return null;
  if (isArray(thing)) return thing;
  let i = thing.length;
  if (!isNumber(i)) return null;
  const arr = new Array(i);
  while (i-- > 0) {
    arr[i] = thing[i];
  }
  return arr;
};
const isTypedArray = /* @__PURE__ */ ((TypedArray) => {
  return (thing) => {
    return TypedArray && thing instanceof TypedArray;
  };
})(typeof Uint8Array !== "undefined" && getPrototypeOf(Uint8Array));
const forEachEntry = (obj, fn) => {
  const generator = obj && obj[iterator];
  const _iterator = generator.call(obj);
  let result;
  while ((result = _iterator.next()) && !result.done) {
    const pair = result.value;
    fn.call(obj, pair[0], pair[1]);
  }
};
const matchAll = (regExp, str) => {
  let matches2;
  const arr = [];
  while ((matches2 = regExp.exec(str)) !== null) {
    arr.push(matches2);
  }
  return arr;
};
const isHTMLForm = kindOfTest("HTMLFormElement");
const toCamelCase = (str) => {
  return str.toLowerCase().replace(/[-_\s]([a-z\d])(\w*)/g, function replacer(m2, p1, p2) {
    return p1.toUpperCase() + p2;
  });
};
const hasOwnProperty = (({ hasOwnProperty: hasOwnProperty2 }) => (obj, prop) => hasOwnProperty2.call(obj, prop))(Object.prototype);
const isRegExp = kindOfTest("RegExp");
const reduceDescriptors = (obj, reducer) => {
  const descriptors = Object.getOwnPropertyDescriptors(obj);
  const reducedDescriptors = {};
  forEach(descriptors, (descriptor, name) => {
    let ret;
    if ((ret = reducer(descriptor, name, obj)) !== false) {
      reducedDescriptors[name] = ret || descriptor;
    }
  });
  Object.defineProperties(obj, reducedDescriptors);
};
const freezeMethods = (obj) => {
  reduceDescriptors(obj, (descriptor, name) => {
    if (isFunction$1(obj) && ["arguments", "caller", "callee"].indexOf(name) !== -1) {
      return false;
    }
    const value = obj[name];
    if (!isFunction$1(value)) return;
    descriptor.enumerable = false;
    if ("writable" in descriptor) {
      descriptor.writable = false;
      return;
    }
    if (!descriptor.set) {
      descriptor.set = () => {
        throw Error("Can not rewrite read-only method '" + name + "'");
      };
    }
  });
};
const toObjectSet = (arrayOrString, delimiter) => {
  const obj = {};
  const define2 = (arr) => {
    arr.forEach((value) => {
      obj[value] = true;
    });
  };
  isArray(arrayOrString) ? define2(arrayOrString) : define2(String(arrayOrString).split(delimiter));
  return obj;
};
const noop = () => {
};
const toFiniteNumber = (value, defaultValue) => {
  return value != null && Number.isFinite(value = +value) ? value : defaultValue;
};
function isSpecCompliantForm(thing) {
  return !!(thing && isFunction$1(thing.append) && thing[toStringTag] === "FormData" && thing[iterator]);
}
const toJSONObject = (obj) => {
  const stack = new Array(10);
  const visit = (source, i) => {
    if (isObject(source)) {
      if (stack.indexOf(source) >= 0) {
        return;
      }
      if (isBuffer(source)) {
        return source;
      }
      if (!("toJSON" in source)) {
        stack[i] = source;
        const target2 = isArray(source) ? [] : {};
        forEach(source, (value, key) => {
          const reducedValue = visit(value, i + 1);
          !isUndefined(reducedValue) && (target2[key] = reducedValue);
        });
        stack[i] = void 0;
        return target2;
      }
    }
    return source;
  };
  return visit(obj, 0);
};
const isAsyncFn = kindOfTest("AsyncFunction");
const isThenable = (thing) => thing && (isObject(thing) || isFunction$1(thing)) && isFunction$1(thing.then) && isFunction$1(thing.catch);
const _setImmediate = ((setImmediateSupported, postMessageSupported) => {
  if (setImmediateSupported) {
    return setImmediate;
  }
  return postMessageSupported ? ((token, callbacks) => {
    _global.addEventListener(
      "message",
      ({ source, data }) => {
        if (source === _global && data === token) {
          callbacks.length && callbacks.shift()();
        }
      },
      false
    );
    return (cb) => {
      callbacks.push(cb);
      _global.postMessage(token, "*");
    };
  })(`axios@${Math.random()}`, []) : (cb) => setTimeout(cb);
})(typeof setImmediate === "function", isFunction$1(_global.postMessage));
const asap = typeof queueMicrotask !== "undefined" ? queueMicrotask.bind(_global) : typeof process$1 !== "undefined" && process$1.nextTick || _setImmediate;
const isIterable = (thing) => thing != null && isFunction$1(thing[iterator]);
const utils$1 = {
  isArray,
  isArrayBuffer,
  isBuffer,
  isFormData,
  isArrayBufferView,
  isString,
  isNumber,
  isBoolean,
  isObject,
  isPlainObject,
  isEmptyObject,
  isReadableStream,
  isRequest,
  isResponse,
  isHeaders,
  isUndefined,
  isDate,
  isFile,
  isBlob,
  isRegExp,
  isFunction: isFunction$1,
  isStream,
  isURLSearchParams,
  isTypedArray,
  isFileList,
  forEach,
  merge,
  extend,
  trim,
  stripBOM,
  inherits,
  toFlatObject,
  kindOf,
  kindOfTest,
  endsWith,
  toArray,
  forEachEntry,
  matchAll,
  isHTMLForm,
  hasOwnProperty,
  hasOwnProp: hasOwnProperty,
  // an alias to avoid ESLint no-prototype-builtins detection
  reduceDescriptors,
  freezeMethods,
  toObjectSet,
  toCamelCase,
  noop,
  toFiniteNumber,
  findKey,
  global: _global,
  isContextDefined,
  isSpecCompliantForm,
  toJSONObject,
  isAsyncFn,
  isThenable,
  setImmediate: _setImmediate,
  asap,
  isIterable
};
let AxiosError$1 = class AxiosError extends Error {
  static from(error, code, config, request, response, customProps) {
    const axiosError = new AxiosError(error.message, code || error.code, config, request, response);
    axiosError.cause = error;
    axiosError.name = error.name;
    customProps && Object.assign(axiosError, customProps);
    return axiosError;
  }
  /**
   * Create an Error with the specified message, config, error code, request and response.
   *
   * @param {string} message The error message.
   * @param {string} [code] The error code (for example, 'ECONNABORTED').
   * @param {Object} [config] The config.
   * @param {Object} [request] The request.
   * @param {Object} [response] The response.
   *
   * @returns {Error} The created error.
   */
  constructor(message, code, config, request, response) {
    super(message);
    this.name = "AxiosError";
    this.isAxiosError = true;
    code && (this.code = code);
    config && (this.config = config);
    request && (this.request = request);
    if (response) {
      this.response = response;
      this.status = response.status;
    }
  }
  toJSON() {
    return {
      // Standard
      message: this.message,
      name: this.name,
      // Microsoft
      description: this.description,
      number: this.number,
      // Mozilla
      fileName: this.fileName,
      lineNumber: this.lineNumber,
      columnNumber: this.columnNumber,
      stack: this.stack,
      // Axios
      config: utils$1.toJSONObject(this.config),
      code: this.code,
      status: this.status
    };
  }
};
AxiosError$1.ERR_BAD_OPTION_VALUE = "ERR_BAD_OPTION_VALUE";
AxiosError$1.ERR_BAD_OPTION = "ERR_BAD_OPTION";
AxiosError$1.ECONNABORTED = "ECONNABORTED";
AxiosError$1.ETIMEDOUT = "ETIMEDOUT";
AxiosError$1.ERR_NETWORK = "ERR_NETWORK";
AxiosError$1.ERR_FR_TOO_MANY_REDIRECTS = "ERR_FR_TOO_MANY_REDIRECTS";
AxiosError$1.ERR_DEPRECATED = "ERR_DEPRECATED";
AxiosError$1.ERR_BAD_RESPONSE = "ERR_BAD_RESPONSE";
AxiosError$1.ERR_BAD_REQUEST = "ERR_BAD_REQUEST";
AxiosError$1.ERR_CANCELED = "ERR_CANCELED";
AxiosError$1.ERR_NOT_SUPPORT = "ERR_NOT_SUPPORT";
AxiosError$1.ERR_INVALID_URL = "ERR_INVALID_URL";
const httpAdapter = null;
function isVisitable(thing) {
  return utils$1.isPlainObject(thing) || utils$1.isArray(thing);
}
function removeBrackets(key) {
  return utils$1.endsWith(key, "[]") ? key.slice(0, -2) : key;
}
function renderKey(path, key, dots) {
  if (!path) return key;
  return path.concat(key).map(function each(token, i) {
    token = removeBrackets(token);
    return !dots && i ? "[" + token + "]" : token;
  }).join(dots ? "." : "");
}
function isFlatArray(arr) {
  return utils$1.isArray(arr) && !arr.some(isVisitable);
}
const predicates = utils$1.toFlatObject(utils$1, {}, null, function filter(prop) {
  return /^is[A-Z]/.test(prop);
});
function toFormData$1(obj, formData, options) {
  if (!utils$1.isObject(obj)) {
    throw new TypeError("target must be an object");
  }
  formData = formData || new FormData();
  options = utils$1.toFlatObject(options, {
    metaTokens: true,
    dots: false,
    indexes: false
  }, false, function defined(option, source) {
    return !utils$1.isUndefined(source[option]);
  });
  const metaTokens = options.metaTokens;
  const visitor = options.visitor || defaultVisitor;
  const dots = options.dots;
  const indexes = options.indexes;
  const _Blob = options.Blob || typeof Blob !== "undefined" && Blob;
  const useBlob = _Blob && utils$1.isSpecCompliantForm(formData);
  if (!utils$1.isFunction(visitor)) {
    throw new TypeError("visitor must be a function");
  }
  function convertValue(value) {
    if (value === null) return "";
    if (utils$1.isDate(value)) {
      return value.toISOString();
    }
    if (utils$1.isBoolean(value)) {
      return value.toString();
    }
    if (!useBlob && utils$1.isBlob(value)) {
      throw new AxiosError$1("Blob is not supported. Use a Buffer instead.");
    }
    if (utils$1.isArrayBuffer(value) || utils$1.isTypedArray(value)) {
      return useBlob && typeof Blob === "function" ? new Blob([value]) : Buffer.from(value);
    }
    return value;
  }
  function defaultVisitor(value, key, path) {
    let arr = value;
    if (value && !path && typeof value === "object") {
      if (utils$1.endsWith(key, "{}")) {
        key = metaTokens ? key : key.slice(0, -2);
        value = JSON.stringify(value);
      } else if (utils$1.isArray(value) && isFlatArray(value) || (utils$1.isFileList(value) || utils$1.endsWith(key, "[]")) && (arr = utils$1.toArray(value))) {
        key = removeBrackets(key);
        arr.forEach(function each(el, index) {
          !(utils$1.isUndefined(el) || el === null) && formData.append(
            // eslint-disable-next-line no-nested-ternary
            indexes === true ? renderKey([key], index, dots) : indexes === null ? key : key + "[]",
            convertValue(el)
          );
        });
        return false;
      }
    }
    if (isVisitable(value)) {
      return true;
    }
    formData.append(renderKey(path, key, dots), convertValue(value));
    return false;
  }
  const stack = [];
  const exposedHelpers = Object.assign(predicates, {
    defaultVisitor,
    convertValue,
    isVisitable
  });
  function build(value, path) {
    if (utils$1.isUndefined(value)) return;
    if (stack.indexOf(value) !== -1) {
      throw Error("Circular reference detected in " + path.join("."));
    }
    stack.push(value);
    utils$1.forEach(value, function each(el, key) {
      const result = !(utils$1.isUndefined(el) || el === null) && visitor.call(
        formData,
        el,
        utils$1.isString(key) ? key.trim() : key,
        path,
        exposedHelpers
      );
      if (result === true) {
        build(el, path ? path.concat(key) : [key]);
      }
    });
    stack.pop();
  }
  if (!utils$1.isObject(obj)) {
    throw new TypeError("data must be an object");
  }
  build(obj);
  return formData;
}
function encode$1(str) {
  const charMap = {
    "!": "%21",
    "'": "%27",
    "(": "%28",
    ")": "%29",
    "~": "%7E",
    "%20": "+",
    "%00": "\0"
  };
  return encodeURIComponent(str).replace(/[!'()~]|%20|%00/g, function replacer(match) {
    return charMap[match];
  });
}
function AxiosURLSearchParams(params, options) {
  this._pairs = [];
  params && toFormData$1(params, this, options);
}
const prototype = AxiosURLSearchParams.prototype;
prototype.append = function append(name, value) {
  this._pairs.push([name, value]);
};
prototype.toString = function toString2(encoder) {
  const _encode = encoder ? function(value) {
    return encoder.call(this, value, encode$1);
  } : encode$1;
  return this._pairs.map(function each(pair) {
    return _encode(pair[0]) + "=" + _encode(pair[1]);
  }, "").join("&");
};
function encode(val) {
  return encodeURIComponent(val).replace(/%3A/gi, ":").replace(/%24/g, "$").replace(/%2C/gi, ",").replace(/%20/g, "+");
}
function buildURL(url, params, options) {
  if (!params) {
    return url;
  }
  const _encode = options && options.encode || encode;
  const _options = utils$1.isFunction(options) ? {
    serialize: options
  } : options;
  const serializeFn = _options && _options.serialize;
  let serializedParams;
  if (serializeFn) {
    serializedParams = serializeFn(params, _options);
  } else {
    serializedParams = utils$1.isURLSearchParams(params) ? params.toString() : new AxiosURLSearchParams(params, _options).toString(_encode);
  }
  if (serializedParams) {
    const hashmarkIndex = url.indexOf("#");
    if (hashmarkIndex !== -1) {
      url = url.slice(0, hashmarkIndex);
    }
    url += (url.indexOf("?") === -1 ? "?" : "&") + serializedParams;
  }
  return url;
}
class InterceptorManager {
  constructor() {
    this.handlers = [];
  }
  /**
   * Add a new interceptor to the stack
   *
   * @param {Function} fulfilled The function to handle `then` for a `Promise`
   * @param {Function} rejected The function to handle `reject` for a `Promise`
   * @param {Object} options The options for the interceptor, synchronous and runWhen
   *
   * @return {Number} An ID used to remove interceptor later
   */
  use(fulfilled, rejected, options) {
    this.handlers.push({
      fulfilled,
      rejected,
      synchronous: options ? options.synchronous : false,
      runWhen: options ? options.runWhen : null
    });
    return this.handlers.length - 1;
  }
  /**
   * Remove an interceptor from the stack
   *
   * @param {Number} id The ID that was returned by `use`
   *
   * @returns {void}
   */
  eject(id) {
    if (this.handlers[id]) {
      this.handlers[id] = null;
    }
  }
  /**
   * Clear all interceptors from the stack
   *
   * @returns {void}
   */
  clear() {
    if (this.handlers) {
      this.handlers = [];
    }
  }
  /**
   * Iterate over all the registered interceptors
   *
   * This method is particularly useful for skipping over any
   * interceptors that may have become `null` calling `eject`.
   *
   * @param {Function} fn The function to call for each interceptor
   *
   * @returns {void}
   */
  forEach(fn) {
    utils$1.forEach(this.handlers, function forEachHandler(h2) {
      if (h2 !== null) {
        fn(h2);
      }
    });
  }
}
const transitionalDefaults = {
  silentJSONParsing: true,
  forcedJSONParsing: true,
  clarifyTimeoutError: false,
  legacyInterceptorReqResOrdering: true
};
const URLSearchParams$1 = typeof URLSearchParams !== "undefined" ? URLSearchParams : AxiosURLSearchParams;
const FormData$1 = typeof FormData !== "undefined" ? FormData : null;
const Blob$1 = typeof Blob !== "undefined" ? Blob : null;
const platform$2 = {
  isBrowser: true,
  classes: {
    URLSearchParams: URLSearchParams$1,
    FormData: FormData$1,
    Blob: Blob$1
  },
  protocols: ["http", "https", "file", "blob", "url", "data"]
};
const hasBrowserEnv = typeof window !== "undefined" && typeof document !== "undefined";
const _navigator = typeof navigator === "object" && navigator || void 0;
const hasStandardBrowserEnv = hasBrowserEnv && (!_navigator || ["ReactNative", "NativeScript", "NS"].indexOf(_navigator.product) < 0);
const hasStandardBrowserWebWorkerEnv = (() => {
  return typeof WorkerGlobalScope !== "undefined" && // eslint-disable-next-line no-undef
  self instanceof WorkerGlobalScope && typeof self.importScripts === "function";
})();
const origin = hasBrowserEnv && window.location.href || "http://localhost";
const utils = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  hasBrowserEnv,
  hasStandardBrowserEnv,
  hasStandardBrowserWebWorkerEnv,
  navigator: _navigator,
  origin
}, Symbol.toStringTag, { value: "Module" }));
const platform$1 = {
  ...utils,
  ...platform$2
};
function toURLEncodedForm(data, options) {
  return toFormData$1(data, new platform$1.classes.URLSearchParams(), {
    visitor: function(value, key, path, helpers) {
      if (platform$1.isNode && utils$1.isBuffer(value)) {
        this.append(key, value.toString("base64"));
        return false;
      }
      return helpers.defaultVisitor.apply(this, arguments);
    },
    ...options
  });
}
function parsePropPath(name) {
  return utils$1.matchAll(/\w+|\[(\w*)]/g, name).map((match) => {
    return match[0] === "[]" ? "" : match[1] || match[0];
  });
}
function arrayToObject(arr) {
  const obj = {};
  const keys = Object.keys(arr);
  let i;
  const len = keys.length;
  let key;
  for (i = 0; i < len; i++) {
    key = keys[i];
    obj[key] = arr[key];
  }
  return obj;
}
function formDataToJSON(formData) {
  function buildPath(path, value, target2, index) {
    let name = path[index++];
    if (name === "__proto__") return true;
    const isNumericKey = Number.isFinite(+name);
    const isLast = index >= path.length;
    name = !name && utils$1.isArray(target2) ? target2.length : name;
    if (isLast) {
      if (utils$1.hasOwnProp(target2, name)) {
        target2[name] = [target2[name], value];
      } else {
        target2[name] = value;
      }
      return !isNumericKey;
    }
    if (!target2[name] || !utils$1.isObject(target2[name])) {
      target2[name] = [];
    }
    const result = buildPath(path, value, target2[name], index);
    if (result && utils$1.isArray(target2[name])) {
      target2[name] = arrayToObject(target2[name]);
    }
    return !isNumericKey;
  }
  if (utils$1.isFormData(formData) && utils$1.isFunction(formData.entries)) {
    const obj = {};
    utils$1.forEachEntry(formData, (name, value) => {
      buildPath(parsePropPath(name), value, obj, 0);
    });
    return obj;
  }
  return null;
}
function stringifySafely(rawValue, parser, encoder) {
  if (utils$1.isString(rawValue)) {
    try {
      (parser || JSON.parse)(rawValue);
      return utils$1.trim(rawValue);
    } catch (e) {
      if (e.name !== "SyntaxError") {
        throw e;
      }
    }
  }
  return (encoder || JSON.stringify)(rawValue);
}
const defaults = {
  transitional: transitionalDefaults,
  adapter: ["xhr", "http", "fetch"],
  transformRequest: [function transformRequest(data, headers) {
    const contentType = headers.getContentType() || "";
    const hasJSONContentType = contentType.indexOf("application/json") > -1;
    const isObjectPayload = utils$1.isObject(data);
    if (isObjectPayload && utils$1.isHTMLForm(data)) {
      data = new FormData(data);
    }
    const isFormData2 = utils$1.isFormData(data);
    if (isFormData2) {
      return hasJSONContentType ? JSON.stringify(formDataToJSON(data)) : data;
    }
    if (utils$1.isArrayBuffer(data) || utils$1.isBuffer(data) || utils$1.isStream(data) || utils$1.isFile(data) || utils$1.isBlob(data) || utils$1.isReadableStream(data)) {
      return data;
    }
    if (utils$1.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils$1.isURLSearchParams(data)) {
      headers.setContentType("application/x-www-form-urlencoded;charset=utf-8", false);
      return data.toString();
    }
    let isFileList2;
    if (isObjectPayload) {
      if (contentType.indexOf("application/x-www-form-urlencoded") > -1) {
        return toURLEncodedForm(data, this.formSerializer).toString();
      }
      if ((isFileList2 = utils$1.isFileList(data)) || contentType.indexOf("multipart/form-data") > -1) {
        const _FormData = this.env && this.env.FormData;
        return toFormData$1(
          isFileList2 ? { "files[]": data } : data,
          _FormData && new _FormData(),
          this.formSerializer
        );
      }
    }
    if (isObjectPayload || hasJSONContentType) {
      headers.setContentType("application/json", false);
      return stringifySafely(data);
    }
    return data;
  }],
  transformResponse: [function transformResponse(data) {
    const transitional2 = this.transitional || defaults.transitional;
    const forcedJSONParsing = transitional2 && transitional2.forcedJSONParsing;
    const JSONRequested = this.responseType === "json";
    if (utils$1.isResponse(data) || utils$1.isReadableStream(data)) {
      return data;
    }
    if (data && utils$1.isString(data) && (forcedJSONParsing && !this.responseType || JSONRequested)) {
      const silentJSONParsing = transitional2 && transitional2.silentJSONParsing;
      const strictJSONParsing = !silentJSONParsing && JSONRequested;
      try {
        return JSON.parse(data, this.parseReviver);
      } catch (e) {
        if (strictJSONParsing) {
          if (e.name === "SyntaxError") {
            throw AxiosError$1.from(e, AxiosError$1.ERR_BAD_RESPONSE, this, null, this.response);
          }
          throw e;
        }
      }
    }
    return data;
  }],
  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,
  xsrfCookieName: "XSRF-TOKEN",
  xsrfHeaderName: "X-XSRF-TOKEN",
  maxContentLength: -1,
  maxBodyLength: -1,
  env: {
    FormData: platform$1.classes.FormData,
    Blob: platform$1.classes.Blob
  },
  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  },
  headers: {
    common: {
      "Accept": "application/json, text/plain, */*",
      "Content-Type": void 0
    }
  }
};
utils$1.forEach(["delete", "get", "head", "post", "put", "patch"], (method) => {
  defaults.headers[method] = {};
});
const ignoreDuplicateOf = utils$1.toObjectSet([
  "age",
  "authorization",
  "content-length",
  "content-type",
  "etag",
  "expires",
  "from",
  "host",
  "if-modified-since",
  "if-unmodified-since",
  "last-modified",
  "location",
  "max-forwards",
  "proxy-authorization",
  "referer",
  "retry-after",
  "user-agent"
]);
const parseHeaders = (rawHeaders) => {
  const parsed = {};
  let key;
  let val;
  let i;
  rawHeaders && rawHeaders.split("\n").forEach(function parser(line) {
    i = line.indexOf(":");
    key = line.substring(0, i).trim().toLowerCase();
    val = line.substring(i + 1).trim();
    if (!key || parsed[key] && ignoreDuplicateOf[key]) {
      return;
    }
    if (key === "set-cookie") {
      if (parsed[key]) {
        parsed[key].push(val);
      } else {
        parsed[key] = [val];
      }
    } else {
      parsed[key] = parsed[key] ? parsed[key] + ", " + val : val;
    }
  });
  return parsed;
};
const $internals = /* @__PURE__ */ Symbol("internals");
function normalizeHeader(header) {
  return header && String(header).trim().toLowerCase();
}
function normalizeValue(value) {
  if (value === false || value == null) {
    return value;
  }
  return utils$1.isArray(value) ? value.map(normalizeValue) : String(value);
}
function parseTokens(str) {
  const tokens = /* @__PURE__ */ Object.create(null);
  const tokensRE = /([^\s,;=]+)\s*(?:=\s*([^,;]+))?/g;
  let match;
  while (match = tokensRE.exec(str)) {
    tokens[match[1]] = match[2];
  }
  return tokens;
}
const isValidHeaderName = (str) => /^[-_a-zA-Z0-9^`|~,!#$%&'*+.]+$/.test(str.trim());
function matchHeaderValue(context, value, header, filter2, isHeaderNameFilter) {
  if (utils$1.isFunction(filter2)) {
    return filter2.call(this, value, header);
  }
  if (isHeaderNameFilter) {
    value = header;
  }
  if (!utils$1.isString(value)) return;
  if (utils$1.isString(filter2)) {
    return value.indexOf(filter2) !== -1;
  }
  if (utils$1.isRegExp(filter2)) {
    return filter2.test(value);
  }
}
function formatHeader(header) {
  return header.trim().toLowerCase().replace(/([a-z\d])(\w*)/g, (w, char, str) => {
    return char.toUpperCase() + str;
  });
}
function buildAccessors(obj, header) {
  const accessorName = utils$1.toCamelCase(" " + header);
  ["get", "set", "has"].forEach((methodName) => {
    Object.defineProperty(obj, methodName + accessorName, {
      value: function(arg1, arg2, arg3) {
        return this[methodName].call(this, header, arg1, arg2, arg3);
      },
      configurable: true
    });
  });
}
let AxiosHeaders$1 = class AxiosHeaders {
  constructor(headers) {
    headers && this.set(headers);
  }
  set(header, valueOrRewrite, rewrite) {
    const self2 = this;
    function setHeader(_value, _header, _rewrite) {
      const lHeader = normalizeHeader(_header);
      if (!lHeader) {
        throw new Error("header name must be a non-empty string");
      }
      const key = utils$1.findKey(self2, lHeader);
      if (!key || self2[key] === void 0 || _rewrite === true || _rewrite === void 0 && self2[key] !== false) {
        self2[key || _header] = normalizeValue(_value);
      }
    }
    const setHeaders = (headers, _rewrite) => utils$1.forEach(headers, (_value, _header) => setHeader(_value, _header, _rewrite));
    if (utils$1.isPlainObject(header) || header instanceof this.constructor) {
      setHeaders(header, valueOrRewrite);
    } else if (utils$1.isString(header) && (header = header.trim()) && !isValidHeaderName(header)) {
      setHeaders(parseHeaders(header), valueOrRewrite);
    } else if (utils$1.isObject(header) && utils$1.isIterable(header)) {
      let obj = {}, dest, key;
      for (const entry of header) {
        if (!utils$1.isArray(entry)) {
          throw TypeError("Object iterator must return a key-value pair");
        }
        obj[key = entry[0]] = (dest = obj[key]) ? utils$1.isArray(dest) ? [...dest, entry[1]] : [dest, entry[1]] : entry[1];
      }
      setHeaders(obj, valueOrRewrite);
    } else {
      header != null && setHeader(valueOrRewrite, header, rewrite);
    }
    return this;
  }
  get(header, parser) {
    header = normalizeHeader(header);
    if (header) {
      const key = utils$1.findKey(this, header);
      if (key) {
        const value = this[key];
        if (!parser) {
          return value;
        }
        if (parser === true) {
          return parseTokens(value);
        }
        if (utils$1.isFunction(parser)) {
          return parser.call(this, value, key);
        }
        if (utils$1.isRegExp(parser)) {
          return parser.exec(value);
        }
        throw new TypeError("parser must be boolean|regexp|function");
      }
    }
  }
  has(header, matcher) {
    header = normalizeHeader(header);
    if (header) {
      const key = utils$1.findKey(this, header);
      return !!(key && this[key] !== void 0 && (!matcher || matchHeaderValue(this, this[key], key, matcher)));
    }
    return false;
  }
  delete(header, matcher) {
    const self2 = this;
    let deleted = false;
    function deleteHeader(_header) {
      _header = normalizeHeader(_header);
      if (_header) {
        const key = utils$1.findKey(self2, _header);
        if (key && (!matcher || matchHeaderValue(self2, self2[key], key, matcher))) {
          delete self2[key];
          deleted = true;
        }
      }
    }
    if (utils$1.isArray(header)) {
      header.forEach(deleteHeader);
    } else {
      deleteHeader(header);
    }
    return deleted;
  }
  clear(matcher) {
    const keys = Object.keys(this);
    let i = keys.length;
    let deleted = false;
    while (i--) {
      const key = keys[i];
      if (!matcher || matchHeaderValue(this, this[key], key, matcher, true)) {
        delete this[key];
        deleted = true;
      }
    }
    return deleted;
  }
  normalize(format) {
    const self2 = this;
    const headers = {};
    utils$1.forEach(this, (value, header) => {
      const key = utils$1.findKey(headers, header);
      if (key) {
        self2[key] = normalizeValue(value);
        delete self2[header];
        return;
      }
      const normalized = format ? formatHeader(header) : String(header).trim();
      if (normalized !== header) {
        delete self2[header];
      }
      self2[normalized] = normalizeValue(value);
      headers[normalized] = true;
    });
    return this;
  }
  concat(...targets) {
    return this.constructor.concat(this, ...targets);
  }
  toJSON(asStrings) {
    const obj = /* @__PURE__ */ Object.create(null);
    utils$1.forEach(this, (value, header) => {
      value != null && value !== false && (obj[header] = asStrings && utils$1.isArray(value) ? value.join(", ") : value);
    });
    return obj;
  }
  [Symbol.iterator]() {
    return Object.entries(this.toJSON())[Symbol.iterator]();
  }
  toString() {
    return Object.entries(this.toJSON()).map(([header, value]) => header + ": " + value).join("\n");
  }
  getSetCookie() {
    return this.get("set-cookie") || [];
  }
  get [Symbol.toStringTag]() {
    return "AxiosHeaders";
  }
  static from(thing) {
    return thing instanceof this ? thing : new this(thing);
  }
  static concat(first, ...targets) {
    const computed2 = new this(first);
    targets.forEach((target2) => computed2.set(target2));
    return computed2;
  }
  static accessor(header) {
    const internals = this[$internals] = this[$internals] = {
      accessors: {}
    };
    const accessors = internals.accessors;
    const prototype2 = this.prototype;
    function defineAccessor(_header) {
      const lHeader = normalizeHeader(_header);
      if (!accessors[lHeader]) {
        buildAccessors(prototype2, _header);
        accessors[lHeader] = true;
      }
    }
    utils$1.isArray(header) ? header.forEach(defineAccessor) : defineAccessor(header);
    return this;
  }
};
AxiosHeaders$1.accessor(["Content-Type", "Content-Length", "Accept", "Accept-Encoding", "User-Agent", "Authorization"]);
utils$1.reduceDescriptors(AxiosHeaders$1.prototype, ({ value }, key) => {
  let mapped = key[0].toUpperCase() + key.slice(1);
  return {
    get: () => value,
    set(headerValue) {
      this[mapped] = headerValue;
    }
  };
});
utils$1.freezeMethods(AxiosHeaders$1);
function transformData(fns, response) {
  const config = this || defaults;
  const context = response || config;
  const headers = AxiosHeaders$1.from(context.headers);
  let data = context.data;
  utils$1.forEach(fns, function transform(fn) {
    data = fn.call(config, data, headers.normalize(), response ? response.status : void 0);
  });
  headers.normalize();
  return data;
}
function isCancel$1(value) {
  return !!(value && value.__CANCEL__);
}
let CanceledError$1 = class CanceledError extends AxiosError$1 {
  /**
   * A `CanceledError` is an object that is thrown when an operation is canceled.
   *
   * @param {string=} message The message.
   * @param {Object=} config The config.
   * @param {Object=} request The request.
   *
   * @returns {CanceledError} The created error.
   */
  constructor(message, config, request) {
    super(message == null ? "canceled" : message, AxiosError$1.ERR_CANCELED, config, request);
    this.name = "CanceledError";
    this.__CANCEL__ = true;
  }
};
function settle(resolve, reject, response) {
  const validateStatus2 = response.config.validateStatus;
  if (!response.status || !validateStatus2 || validateStatus2(response.status)) {
    resolve(response);
  } else {
    reject(new AxiosError$1(
      "Request failed with status code " + response.status,
      [AxiosError$1.ERR_BAD_REQUEST, AxiosError$1.ERR_BAD_RESPONSE][Math.floor(response.status / 100) - 4],
      response.config,
      response.request,
      response
    ));
  }
}
function parseProtocol(url) {
  const match = /^([-+\w]{1,25})(:?\/\/|:)/.exec(url);
  return match && match[1] || "";
}
function speedometer(samplesCount, min2) {
  samplesCount = samplesCount || 10;
  const bytes = new Array(samplesCount);
  const timestamps = new Array(samplesCount);
  let head = 0;
  let tail = 0;
  let firstSampleTS;
  min2 = min2 !== void 0 ? min2 : 1e3;
  return function push(chunkLength) {
    const now = Date.now();
    const startedAt = timestamps[tail];
    if (!firstSampleTS) {
      firstSampleTS = now;
    }
    bytes[head] = chunkLength;
    timestamps[head] = now;
    let i = tail;
    let bytesCount = 0;
    while (i !== head) {
      bytesCount += bytes[i++];
      i = i % samplesCount;
    }
    head = (head + 1) % samplesCount;
    if (head === tail) {
      tail = (tail + 1) % samplesCount;
    }
    if (now - firstSampleTS < min2) {
      return;
    }
    const passed = startedAt && now - startedAt;
    return passed ? Math.round(bytesCount * 1e3 / passed) : void 0;
  };
}
function throttle(fn, freq) {
  let timestamp = 0;
  let threshold = 1e3 / freq;
  let lastArgs;
  let timer;
  const invoke = (args, now = Date.now()) => {
    timestamp = now;
    lastArgs = null;
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
    fn(...args);
  };
  const throttled = (...args) => {
    const now = Date.now();
    const passed = now - timestamp;
    if (passed >= threshold) {
      invoke(args, now);
    } else {
      lastArgs = args;
      if (!timer) {
        timer = setTimeout(() => {
          timer = null;
          invoke(lastArgs);
        }, threshold - passed);
      }
    }
  };
  const flush = () => lastArgs && invoke(lastArgs);
  return [throttled, flush];
}
const progressEventReducer = (listener, isDownloadStream, freq = 3) => {
  let bytesNotified = 0;
  const _speedometer = speedometer(50, 250);
  return throttle((e) => {
    const loaded = e.loaded;
    const total = e.lengthComputable ? e.total : void 0;
    const progressBytes = loaded - bytesNotified;
    const rate = _speedometer(progressBytes);
    const inRange = loaded <= total;
    bytesNotified = loaded;
    const data = {
      loaded,
      total,
      progress: total ? loaded / total : void 0,
      bytes: progressBytes,
      rate: rate ? rate : void 0,
      estimated: rate && total && inRange ? (total - loaded) / rate : void 0,
      event: e,
      lengthComputable: total != null,
      [isDownloadStream ? "download" : "upload"]: true
    };
    listener(data);
  }, freq);
};
const progressEventDecorator = (total, throttled) => {
  const lengthComputable = total != null;
  return [(loaded) => throttled[0]({
    lengthComputable,
    total,
    loaded
  }), throttled[1]];
};
const asyncDecorator = (fn) => (...args) => utils$1.asap(() => fn(...args));
const isURLSameOrigin = platform$1.hasStandardBrowserEnv ? /* @__PURE__ */ ((origin2, isMSIE) => (url) => {
  url = new URL(url, platform$1.origin);
  return origin2.protocol === url.protocol && origin2.host === url.host && (isMSIE || origin2.port === url.port);
})(
  new URL(platform$1.origin),
  platform$1.navigator && /(msie|trident)/i.test(platform$1.navigator.userAgent)
) : () => true;
const cookies = platform$1.hasStandardBrowserEnv ? (
  // Standard browser envs support document.cookie
  {
    write(name, value, expires, path, domain, secure, sameSite) {
      if (typeof document === "undefined") return;
      const cookie = [`${name}=${encodeURIComponent(value)}`];
      if (utils$1.isNumber(expires)) {
        cookie.push(`expires=${new Date(expires).toUTCString()}`);
      }
      if (utils$1.isString(path)) {
        cookie.push(`path=${path}`);
      }
      if (utils$1.isString(domain)) {
        cookie.push(`domain=${domain}`);
      }
      if (secure === true) {
        cookie.push("secure");
      }
      if (utils$1.isString(sameSite)) {
        cookie.push(`SameSite=${sameSite}`);
      }
      document.cookie = cookie.join("; ");
    },
    read(name) {
      if (typeof document === "undefined") return null;
      const match = document.cookie.match(new RegExp("(?:^|; )" + name + "=([^;]*)"));
      return match ? decodeURIComponent(match[1]) : null;
    },
    remove(name) {
      this.write(name, "", Date.now() - 864e5, "/");
    }
  }
) : (
  // Non-standard browser env (web workers, react-native) lack needed support.
  {
    write() {
    },
    read() {
      return null;
    },
    remove() {
    }
  }
);
function isAbsoluteURL(url) {
  if (typeof url !== "string") {
    return false;
  }
  return /^([a-z][a-z\d+\-.]*:)?\/\//i.test(url);
}
function combineURLs(baseURL, relativeURL) {
  return relativeURL ? baseURL.replace(/\/?\/$/, "") + "/" + relativeURL.replace(/^\/+/, "") : baseURL;
}
function buildFullPath(baseURL, requestedURL, allowAbsoluteUrls) {
  let isRelativeUrl = !isAbsoluteURL(requestedURL);
  if (baseURL && (isRelativeUrl || allowAbsoluteUrls == false)) {
    return combineURLs(baseURL, requestedURL);
  }
  return requestedURL;
}
const headersToObject = (thing) => thing instanceof AxiosHeaders$1 ? { ...thing } : thing;
function mergeConfig$1(config1, config2) {
  config2 = config2 || {};
  const config = {};
  function getMergedValue(target2, source, prop, caseless) {
    if (utils$1.isPlainObject(target2) && utils$1.isPlainObject(source)) {
      return utils$1.merge.call({ caseless }, target2, source);
    } else if (utils$1.isPlainObject(source)) {
      return utils$1.merge({}, source);
    } else if (utils$1.isArray(source)) {
      return source.slice();
    }
    return source;
  }
  function mergeDeepProperties(a, b, prop, caseless) {
    if (!utils$1.isUndefined(b)) {
      return getMergedValue(a, b, prop, caseless);
    } else if (!utils$1.isUndefined(a)) {
      return getMergedValue(void 0, a, prop, caseless);
    }
  }
  function valueFromConfig2(a, b) {
    if (!utils$1.isUndefined(b)) {
      return getMergedValue(void 0, b);
    }
  }
  function defaultToConfig2(a, b) {
    if (!utils$1.isUndefined(b)) {
      return getMergedValue(void 0, b);
    } else if (!utils$1.isUndefined(a)) {
      return getMergedValue(void 0, a);
    }
  }
  function mergeDirectKeys(a, b, prop) {
    if (prop in config2) {
      return getMergedValue(a, b);
    } else if (prop in config1) {
      return getMergedValue(void 0, a);
    }
  }
  const mergeMap = {
    url: valueFromConfig2,
    method: valueFromConfig2,
    data: valueFromConfig2,
    baseURL: defaultToConfig2,
    transformRequest: defaultToConfig2,
    transformResponse: defaultToConfig2,
    paramsSerializer: defaultToConfig2,
    timeout: defaultToConfig2,
    timeoutMessage: defaultToConfig2,
    withCredentials: defaultToConfig2,
    withXSRFToken: defaultToConfig2,
    adapter: defaultToConfig2,
    responseType: defaultToConfig2,
    xsrfCookieName: defaultToConfig2,
    xsrfHeaderName: defaultToConfig2,
    onUploadProgress: defaultToConfig2,
    onDownloadProgress: defaultToConfig2,
    decompress: defaultToConfig2,
    maxContentLength: defaultToConfig2,
    maxBodyLength: defaultToConfig2,
    beforeRedirect: defaultToConfig2,
    transport: defaultToConfig2,
    httpAgent: defaultToConfig2,
    httpsAgent: defaultToConfig2,
    cancelToken: defaultToConfig2,
    socketPath: defaultToConfig2,
    responseEncoding: defaultToConfig2,
    validateStatus: mergeDirectKeys,
    headers: (a, b, prop) => mergeDeepProperties(headersToObject(a), headersToObject(b), prop, true)
  };
  utils$1.forEach(
    Object.keys({ ...config1, ...config2 }),
    function computeConfigValue(prop) {
      if (prop === "__proto__" || prop === "constructor" || prop === "prototype")
        return;
      const merge2 = utils$1.hasOwnProp(mergeMap, prop) ? mergeMap[prop] : mergeDeepProperties;
      const configValue = merge2(config1[prop], config2[prop], prop);
      utils$1.isUndefined(configValue) && merge2 !== mergeDirectKeys || (config[prop] = configValue);
    }
  );
  return config;
}
const resolveConfig = (config) => {
  const newConfig = mergeConfig$1({}, config);
  let { data, withXSRFToken, xsrfHeaderName, xsrfCookieName, headers, auth } = newConfig;
  newConfig.headers = headers = AxiosHeaders$1.from(headers);
  newConfig.url = buildURL(buildFullPath(newConfig.baseURL, newConfig.url, newConfig.allowAbsoluteUrls), config.params, config.paramsSerializer);
  if (auth) {
    headers.set(
      "Authorization",
      "Basic " + btoa((auth.username || "") + ":" + (auth.password ? unescape(encodeURIComponent(auth.password)) : ""))
    );
  }
  if (utils$1.isFormData(data)) {
    if (platform$1.hasStandardBrowserEnv || platform$1.hasStandardBrowserWebWorkerEnv) {
      headers.setContentType(void 0);
    } else if (utils$1.isFunction(data.getHeaders)) {
      const formHeaders = data.getHeaders();
      const allowedHeaders = ["content-type", "content-length"];
      Object.entries(formHeaders).forEach(([key, val]) => {
        if (allowedHeaders.includes(key.toLowerCase())) {
          headers.set(key, val);
        }
      });
    }
  }
  if (platform$1.hasStandardBrowserEnv) {
    withXSRFToken && utils$1.isFunction(withXSRFToken) && (withXSRFToken = withXSRFToken(newConfig));
    if (withXSRFToken || withXSRFToken !== false && isURLSameOrigin(newConfig.url)) {
      const xsrfValue = xsrfHeaderName && xsrfCookieName && cookies.read(xsrfCookieName);
      if (xsrfValue) {
        headers.set(xsrfHeaderName, xsrfValue);
      }
    }
  }
  return newConfig;
};
const isXHRAdapterSupported = typeof XMLHttpRequest !== "undefined";
const xhrAdapter = isXHRAdapterSupported && function(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    const _config = resolveConfig(config);
    let requestData = _config.data;
    const requestHeaders = AxiosHeaders$1.from(_config.headers).normalize();
    let { responseType, onUploadProgress, onDownloadProgress } = _config;
    let onCanceled;
    let uploadThrottled, downloadThrottled;
    let flushUpload, flushDownload;
    function done() {
      flushUpload && flushUpload();
      flushDownload && flushDownload();
      _config.cancelToken && _config.cancelToken.unsubscribe(onCanceled);
      _config.signal && _config.signal.removeEventListener("abort", onCanceled);
    }
    let request = new XMLHttpRequest();
    request.open(_config.method.toUpperCase(), _config.url, true);
    request.timeout = _config.timeout;
    function onloadend() {
      if (!request) {
        return;
      }
      const responseHeaders = AxiosHeaders$1.from(
        "getAllResponseHeaders" in request && request.getAllResponseHeaders()
      );
      const responseData = !responseType || responseType === "text" || responseType === "json" ? request.responseText : request.response;
      const response = {
        data: responseData,
        status: request.status,
        statusText: request.statusText,
        headers: responseHeaders,
        config,
        request
      };
      settle(function _resolve(value) {
        resolve(value);
        done();
      }, function _reject(err) {
        reject(err);
        done();
      }, response);
      request = null;
    }
    if ("onloadend" in request) {
      request.onloadend = onloadend;
    } else {
      request.onreadystatechange = function handleLoad() {
        if (!request || request.readyState !== 4) {
          return;
        }
        if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf("file:") === 0)) {
          return;
        }
        setTimeout(onloadend);
      };
    }
    request.onabort = function handleAbort() {
      if (!request) {
        return;
      }
      reject(new AxiosError$1("Request aborted", AxiosError$1.ECONNABORTED, config, request));
      request = null;
    };
    request.onerror = function handleError(event) {
      const msg = event && event.message ? event.message : "Network Error";
      const err = new AxiosError$1(msg, AxiosError$1.ERR_NETWORK, config, request);
      err.event = event || null;
      reject(err);
      request = null;
    };
    request.ontimeout = function handleTimeout() {
      let timeoutErrorMessage = _config.timeout ? "timeout of " + _config.timeout + "ms exceeded" : "timeout exceeded";
      const transitional2 = _config.transitional || transitionalDefaults;
      if (_config.timeoutErrorMessage) {
        timeoutErrorMessage = _config.timeoutErrorMessage;
      }
      reject(new AxiosError$1(
        timeoutErrorMessage,
        transitional2.clarifyTimeoutError ? AxiosError$1.ETIMEDOUT : AxiosError$1.ECONNABORTED,
        config,
        request
      ));
      request = null;
    };
    requestData === void 0 && requestHeaders.setContentType(null);
    if ("setRequestHeader" in request) {
      utils$1.forEach(requestHeaders.toJSON(), function setRequestHeader(val, key) {
        request.setRequestHeader(key, val);
      });
    }
    if (!utils$1.isUndefined(_config.withCredentials)) {
      request.withCredentials = !!_config.withCredentials;
    }
    if (responseType && responseType !== "json") {
      request.responseType = _config.responseType;
    }
    if (onDownloadProgress) {
      [downloadThrottled, flushDownload] = progressEventReducer(onDownloadProgress, true);
      request.addEventListener("progress", downloadThrottled);
    }
    if (onUploadProgress && request.upload) {
      [uploadThrottled, flushUpload] = progressEventReducer(onUploadProgress);
      request.upload.addEventListener("progress", uploadThrottled);
      request.upload.addEventListener("loadend", flushUpload);
    }
    if (_config.cancelToken || _config.signal) {
      onCanceled = (cancel) => {
        if (!request) {
          return;
        }
        reject(!cancel || cancel.type ? new CanceledError$1(null, config, request) : cancel);
        request.abort();
        request = null;
      };
      _config.cancelToken && _config.cancelToken.subscribe(onCanceled);
      if (_config.signal) {
        _config.signal.aborted ? onCanceled() : _config.signal.addEventListener("abort", onCanceled);
      }
    }
    const protocol = parseProtocol(_config.url);
    if (protocol && platform$1.protocols.indexOf(protocol) === -1) {
      reject(new AxiosError$1("Unsupported protocol " + protocol + ":", AxiosError$1.ERR_BAD_REQUEST, config));
      return;
    }
    request.send(requestData || null);
  });
};
const composeSignals = (signals, timeout) => {
  const { length } = signals = signals ? signals.filter(Boolean) : [];
  if (timeout || length) {
    let controller = new AbortController();
    let aborted;
    const onabort = function(reason) {
      if (!aborted) {
        aborted = true;
        unsubscribe();
        const err = reason instanceof Error ? reason : this.reason;
        controller.abort(err instanceof AxiosError$1 ? err : new CanceledError$1(err instanceof Error ? err.message : err));
      }
    };
    let timer = timeout && setTimeout(() => {
      timer = null;
      onabort(new AxiosError$1(`timeout of ${timeout}ms exceeded`, AxiosError$1.ETIMEDOUT));
    }, timeout);
    const unsubscribe = () => {
      if (signals) {
        timer && clearTimeout(timer);
        timer = null;
        signals.forEach((signal2) => {
          signal2.unsubscribe ? signal2.unsubscribe(onabort) : signal2.removeEventListener("abort", onabort);
        });
        signals = null;
      }
    };
    signals.forEach((signal2) => signal2.addEventListener("abort", onabort));
    const { signal } = controller;
    signal.unsubscribe = () => utils$1.asap(unsubscribe);
    return signal;
  }
};
const streamChunk = function* (chunk, chunkSize) {
  let len = chunk.byteLength;
  if (len < chunkSize) {
    yield chunk;
    return;
  }
  let pos = 0;
  let end;
  while (pos < len) {
    end = pos + chunkSize;
    yield chunk.slice(pos, end);
    pos = end;
  }
};
const readBytes = async function* (iterable, chunkSize) {
  for await (const chunk of readStream(iterable)) {
    yield* streamChunk(chunk, chunkSize);
  }
};
const readStream = async function* (stream) {
  if (stream[Symbol.asyncIterator]) {
    yield* stream;
    return;
  }
  const reader = stream.getReader();
  try {
    for (; ; ) {
      const { done, value } = await reader.read();
      if (done) {
        break;
      }
      yield value;
    }
  } finally {
    await reader.cancel();
  }
};
const trackStream = (stream, chunkSize, onProgress, onFinish) => {
  const iterator2 = readBytes(stream, chunkSize);
  let bytes = 0;
  let done;
  let _onFinish = (e) => {
    if (!done) {
      done = true;
      onFinish && onFinish(e);
    }
  };
  return new ReadableStream({
    async pull(controller) {
      try {
        const { done: done2, value } = await iterator2.next();
        if (done2) {
          _onFinish();
          controller.close();
          return;
        }
        let len = value.byteLength;
        if (onProgress) {
          let loadedBytes = bytes += len;
          onProgress(loadedBytes);
        }
        controller.enqueue(new Uint8Array(value));
      } catch (err) {
        _onFinish(err);
        throw err;
      }
    },
    cancel(reason) {
      _onFinish(reason);
      return iterator2.return();
    }
  }, {
    highWaterMark: 2
  });
};
const DEFAULT_CHUNK_SIZE = 64 * 1024;
const { isFunction } = utils$1;
const globalFetchAPI = (({ Request, Response }) => ({
  Request,
  Response
}))(utils$1.global);
const {
  ReadableStream: ReadableStream$1,
  TextEncoder
} = utils$1.global;
const test = (fn, ...args) => {
  try {
    return !!fn(...args);
  } catch (e) {
    return false;
  }
};
const factory = (env) => {
  env = utils$1.merge.call({
    skipUndefined: true
  }, globalFetchAPI, env);
  const { fetch: envFetch, Request, Response } = env;
  const isFetchSupported = envFetch ? isFunction(envFetch) : typeof fetch === "function";
  const isRequestSupported = isFunction(Request);
  const isResponseSupported = isFunction(Response);
  if (!isFetchSupported) {
    return false;
  }
  const isReadableStreamSupported = isFetchSupported && isFunction(ReadableStream$1);
  const encodeText = isFetchSupported && (typeof TextEncoder === "function" ? /* @__PURE__ */ ((encoder) => (str) => encoder.encode(str))(new TextEncoder()) : async (str) => new Uint8Array(await new Request(str).arrayBuffer()));
  const supportsRequestStream = isRequestSupported && isReadableStreamSupported && test(() => {
    let duplexAccessed = false;
    const hasContentType = new Request(platform$1.origin, {
      body: new ReadableStream$1(),
      method: "POST",
      get duplex() {
        duplexAccessed = true;
        return "half";
      }
    }).headers.has("Content-Type");
    return duplexAccessed && !hasContentType;
  });
  const supportsResponseStream = isResponseSupported && isReadableStreamSupported && test(() => utils$1.isReadableStream(new Response("").body));
  const resolvers = {
    stream: supportsResponseStream && ((res) => res.body)
  };
  isFetchSupported && (() => {
    ["text", "arrayBuffer", "blob", "formData", "stream"].forEach((type) => {
      !resolvers[type] && (resolvers[type] = (res, config) => {
        let method = res && res[type];
        if (method) {
          return method.call(res);
        }
        throw new AxiosError$1(`Response type '${type}' is not supported`, AxiosError$1.ERR_NOT_SUPPORT, config);
      });
    });
  })();
  const getBodyLength = async (body) => {
    if (body == null) {
      return 0;
    }
    if (utils$1.isBlob(body)) {
      return body.size;
    }
    if (utils$1.isSpecCompliantForm(body)) {
      const _request = new Request(platform$1.origin, {
        method: "POST",
        body
      });
      return (await _request.arrayBuffer()).byteLength;
    }
    if (utils$1.isArrayBufferView(body) || utils$1.isArrayBuffer(body)) {
      return body.byteLength;
    }
    if (utils$1.isURLSearchParams(body)) {
      body = body + "";
    }
    if (utils$1.isString(body)) {
      return (await encodeText(body)).byteLength;
    }
  };
  const resolveBodyLength = async (headers, body) => {
    const length = utils$1.toFiniteNumber(headers.getContentLength());
    return length == null ? getBodyLength(body) : length;
  };
  return async (config) => {
    let {
      url,
      method,
      data,
      signal,
      cancelToken,
      timeout,
      onDownloadProgress,
      onUploadProgress,
      responseType,
      headers,
      withCredentials = "same-origin",
      fetchOptions
    } = resolveConfig(config);
    let _fetch = envFetch || fetch;
    responseType = responseType ? (responseType + "").toLowerCase() : "text";
    let composedSignal = composeSignals([signal, cancelToken && cancelToken.toAbortSignal()], timeout);
    let request = null;
    const unsubscribe = composedSignal && composedSignal.unsubscribe && (() => {
      composedSignal.unsubscribe();
    });
    let requestContentLength;
    try {
      if (onUploadProgress && supportsRequestStream && method !== "get" && method !== "head" && (requestContentLength = await resolveBodyLength(headers, data)) !== 0) {
        let _request = new Request(url, {
          method: "POST",
          body: data,
          duplex: "half"
        });
        let contentTypeHeader;
        if (utils$1.isFormData(data) && (contentTypeHeader = _request.headers.get("content-type"))) {
          headers.setContentType(contentTypeHeader);
        }
        if (_request.body) {
          const [onProgress, flush] = progressEventDecorator(
            requestContentLength,
            progressEventReducer(asyncDecorator(onUploadProgress))
          );
          data = trackStream(_request.body, DEFAULT_CHUNK_SIZE, onProgress, flush);
        }
      }
      if (!utils$1.isString(withCredentials)) {
        withCredentials = withCredentials ? "include" : "omit";
      }
      const isCredentialsSupported = isRequestSupported && "credentials" in Request.prototype;
      const resolvedOptions = {
        ...fetchOptions,
        signal: composedSignal,
        method: method.toUpperCase(),
        headers: headers.normalize().toJSON(),
        body: data,
        duplex: "half",
        credentials: isCredentialsSupported ? withCredentials : void 0
      };
      request = isRequestSupported && new Request(url, resolvedOptions);
      let response = await (isRequestSupported ? _fetch(request, fetchOptions) : _fetch(url, resolvedOptions));
      const isStreamResponse = supportsResponseStream && (responseType === "stream" || responseType === "response");
      if (supportsResponseStream && (onDownloadProgress || isStreamResponse && unsubscribe)) {
        const options = {};
        ["status", "statusText", "headers"].forEach((prop) => {
          options[prop] = response[prop];
        });
        const responseContentLength = utils$1.toFiniteNumber(response.headers.get("content-length"));
        const [onProgress, flush] = onDownloadProgress && progressEventDecorator(
          responseContentLength,
          progressEventReducer(asyncDecorator(onDownloadProgress), true)
        ) || [];
        response = new Response(
          trackStream(response.body, DEFAULT_CHUNK_SIZE, onProgress, () => {
            flush && flush();
            unsubscribe && unsubscribe();
          }),
          options
        );
      }
      responseType = responseType || "text";
      let responseData = await resolvers[utils$1.findKey(resolvers, responseType) || "text"](response, config);
      !isStreamResponse && unsubscribe && unsubscribe();
      return await new Promise((resolve, reject) => {
        settle(resolve, reject, {
          data: responseData,
          headers: AxiosHeaders$1.from(response.headers),
          status: response.status,
          statusText: response.statusText,
          config,
          request
        });
      });
    } catch (err) {
      unsubscribe && unsubscribe();
      if (err && err.name === "TypeError" && /Load failed|fetch/i.test(err.message)) {
        throw Object.assign(
          new AxiosError$1("Network Error", AxiosError$1.ERR_NETWORK, config, request, err && err.response),
          {
            cause: err.cause || err
          }
        );
      }
      throw AxiosError$1.from(err, err && err.code, config, request, err && err.response);
    }
  };
};
const seedCache = /* @__PURE__ */ new Map();
const getFetch = (config) => {
  let env = config && config.env || {};
  const { fetch: fetch2, Request, Response } = env;
  const seeds = [
    Request,
    Response,
    fetch2
  ];
  let len = seeds.length, i = len, seed, target2, map = seedCache;
  while (i--) {
    seed = seeds[i];
    target2 = map.get(seed);
    target2 === void 0 && map.set(seed, target2 = i ? /* @__PURE__ */ new Map() : factory(env));
    map = target2;
  }
  return target2;
};
getFetch();
const knownAdapters = {
  http: httpAdapter,
  xhr: xhrAdapter,
  fetch: {
    get: getFetch
  }
};
utils$1.forEach(knownAdapters, (fn, value) => {
  if (fn) {
    try {
      Object.defineProperty(fn, "name", { value });
    } catch (e) {
    }
    Object.defineProperty(fn, "adapterName", { value });
  }
});
const renderReason = (reason) => `- ${reason}`;
const isResolvedHandle = (adapter) => utils$1.isFunction(adapter) || adapter === null || adapter === false;
function getAdapter$1(adapters2, config) {
  adapters2 = utils$1.isArray(adapters2) ? adapters2 : [adapters2];
  const { length } = adapters2;
  let nameOrAdapter;
  let adapter;
  const rejectedReasons = {};
  for (let i = 0; i < length; i++) {
    nameOrAdapter = adapters2[i];
    let id;
    adapter = nameOrAdapter;
    if (!isResolvedHandle(nameOrAdapter)) {
      adapter = knownAdapters[(id = String(nameOrAdapter)).toLowerCase()];
      if (adapter === void 0) {
        throw new AxiosError$1(`Unknown adapter '${id}'`);
      }
    }
    if (adapter && (utils$1.isFunction(adapter) || (adapter = adapter.get(config)))) {
      break;
    }
    rejectedReasons[id || "#" + i] = adapter;
  }
  if (!adapter) {
    const reasons = Object.entries(rejectedReasons).map(
      ([id, state]) => `adapter ${id} ` + (state === false ? "is not supported by the environment" : "is not available in the build")
    );
    let s = length ? reasons.length > 1 ? "since :\n" + reasons.map(renderReason).join("\n") : " " + renderReason(reasons[0]) : "as no adapter specified";
    throw new AxiosError$1(
      `There is no suitable adapter to dispatch the request ` + s,
      "ERR_NOT_SUPPORT"
    );
  }
  return adapter;
}
const adapters = {
  /**
   * Resolve an adapter from a list of adapter names or functions.
   * @type {Function}
   */
  getAdapter: getAdapter$1,
  /**
   * Exposes all known adapters
   * @type {Object<string, Function|Object>}
   */
  adapters: knownAdapters
};
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
  if (config.signal && config.signal.aborted) {
    throw new CanceledError$1(null, config);
  }
}
function dispatchRequest(config) {
  throwIfCancellationRequested(config);
  config.headers = AxiosHeaders$1.from(config.headers);
  config.data = transformData.call(
    config,
    config.transformRequest
  );
  if (["post", "put", "patch"].indexOf(config.method) !== -1) {
    config.headers.setContentType("application/x-www-form-urlencoded", false);
  }
  const adapter = adapters.getAdapter(config.adapter || defaults.adapter, config);
  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);
    response.data = transformData.call(
      config,
      config.transformResponse,
      response
    );
    response.headers = AxiosHeaders$1.from(response.headers);
    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel$1(reason)) {
      throwIfCancellationRequested(config);
      if (reason && reason.response) {
        reason.response.data = transformData.call(
          config,
          config.transformResponse,
          reason.response
        );
        reason.response.headers = AxiosHeaders$1.from(reason.response.headers);
      }
    }
    return Promise.reject(reason);
  });
}
const VERSION$1 = "1.13.5";
const validators$1 = {};
["object", "boolean", "number", "function", "string", "symbol"].forEach((type, i) => {
  validators$1[type] = function validator2(thing) {
    return typeof thing === type || "a" + (i < 1 ? "n " : " ") + type;
  };
});
const deprecatedWarnings = {};
validators$1.transitional = function transitional(validator2, version, message) {
  function formatMessage(opt, desc) {
    return "[Axios v" + VERSION$1 + "] Transitional option '" + opt + "'" + desc + (message ? ". " + message : "");
  }
  return (value, opt, opts) => {
    if (validator2 === false) {
      throw new AxiosError$1(
        formatMessage(opt, " has been removed" + (version ? " in " + version : "")),
        AxiosError$1.ERR_DEPRECATED
      );
    }
    if (version && !deprecatedWarnings[opt]) {
      deprecatedWarnings[opt] = true;
      console.warn(
        formatMessage(
          opt,
          " has been deprecated since v" + version + " and will be removed in the near future"
        )
      );
    }
    return validator2 ? validator2(value, opt, opts) : true;
  };
};
validators$1.spelling = function spelling(correctSpelling) {
  return (value, opt) => {
    console.warn(`${opt} is likely a misspelling of ${correctSpelling}`);
    return true;
  };
};
function assertOptions(options, schema, allowUnknown) {
  if (typeof options !== "object") {
    throw new AxiosError$1("options must be an object", AxiosError$1.ERR_BAD_OPTION_VALUE);
  }
  const keys = Object.keys(options);
  let i = keys.length;
  while (i-- > 0) {
    const opt = keys[i];
    const validator2 = schema[opt];
    if (validator2) {
      const value = options[opt];
      const result = value === void 0 || validator2(value, opt, options);
      if (result !== true) {
        throw new AxiosError$1("option " + opt + " must be " + result, AxiosError$1.ERR_BAD_OPTION_VALUE);
      }
      continue;
    }
    if (allowUnknown !== true) {
      throw new AxiosError$1("Unknown option " + opt, AxiosError$1.ERR_BAD_OPTION);
    }
  }
}
const validator = {
  assertOptions,
  validators: validators$1
};
const validators = validator.validators;
let Axios$1 = class Axios {
  constructor(instanceConfig) {
    this.defaults = instanceConfig || {};
    this.interceptors = {
      request: new InterceptorManager(),
      response: new InterceptorManager()
    };
  }
  /**
   * Dispatch a request
   *
   * @param {String|Object} configOrUrl The config specific for this request (merged with this.defaults)
   * @param {?Object} config
   *
   * @returns {Promise} The Promise to be fulfilled
   */
  async request(configOrUrl, config) {
    try {
      return await this._request(configOrUrl, config);
    } catch (err) {
      if (err instanceof Error) {
        let dummy = {};
        Error.captureStackTrace ? Error.captureStackTrace(dummy) : dummy = new Error();
        const stack = dummy.stack ? dummy.stack.replace(/^.+\n/, "") : "";
        try {
          if (!err.stack) {
            err.stack = stack;
          } else if (stack && !String(err.stack).endsWith(stack.replace(/^.+\n.+\n/, ""))) {
            err.stack += "\n" + stack;
          }
        } catch (e) {
        }
      }
      throw err;
    }
  }
  _request(configOrUrl, config) {
    if (typeof configOrUrl === "string") {
      config = config || {};
      config.url = configOrUrl;
    } else {
      config = configOrUrl || {};
    }
    config = mergeConfig$1(this.defaults, config);
    const { transitional: transitional2, paramsSerializer, headers } = config;
    if (transitional2 !== void 0) {
      validator.assertOptions(transitional2, {
        silentJSONParsing: validators.transitional(validators.boolean),
        forcedJSONParsing: validators.transitional(validators.boolean),
        clarifyTimeoutError: validators.transitional(validators.boolean),
        legacyInterceptorReqResOrdering: validators.transitional(validators.boolean)
      }, false);
    }
    if (paramsSerializer != null) {
      if (utils$1.isFunction(paramsSerializer)) {
        config.paramsSerializer = {
          serialize: paramsSerializer
        };
      } else {
        validator.assertOptions(paramsSerializer, {
          encode: validators.function,
          serialize: validators.function
        }, true);
      }
    }
    if (config.allowAbsoluteUrls !== void 0) ;
    else if (this.defaults.allowAbsoluteUrls !== void 0) {
      config.allowAbsoluteUrls = this.defaults.allowAbsoluteUrls;
    } else {
      config.allowAbsoluteUrls = true;
    }
    validator.assertOptions(config, {
      baseUrl: validators.spelling("baseURL"),
      withXsrfToken: validators.spelling("withXSRFToken")
    }, true);
    config.method = (config.method || this.defaults.method || "get").toLowerCase();
    let contextHeaders = headers && utils$1.merge(
      headers.common,
      headers[config.method]
    );
    headers && utils$1.forEach(
      ["delete", "get", "head", "post", "put", "patch", "common"],
      (method) => {
        delete headers[method];
      }
    );
    config.headers = AxiosHeaders$1.concat(contextHeaders, headers);
    const requestInterceptorChain = [];
    let synchronousRequestInterceptors = true;
    this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
      if (typeof interceptor.runWhen === "function" && interceptor.runWhen(config) === false) {
        return;
      }
      synchronousRequestInterceptors = synchronousRequestInterceptors && interceptor.synchronous;
      const transitional3 = config.transitional || transitionalDefaults;
      const legacyInterceptorReqResOrdering = transitional3 && transitional3.legacyInterceptorReqResOrdering;
      if (legacyInterceptorReqResOrdering) {
        requestInterceptorChain.unshift(interceptor.fulfilled, interceptor.rejected);
      } else {
        requestInterceptorChain.push(interceptor.fulfilled, interceptor.rejected);
      }
    });
    const responseInterceptorChain = [];
    this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
      responseInterceptorChain.push(interceptor.fulfilled, interceptor.rejected);
    });
    let promise;
    let i = 0;
    let len;
    if (!synchronousRequestInterceptors) {
      const chain = [dispatchRequest.bind(this), void 0];
      chain.unshift(...requestInterceptorChain);
      chain.push(...responseInterceptorChain);
      len = chain.length;
      promise = Promise.resolve(config);
      while (i < len) {
        promise = promise.then(chain[i++], chain[i++]);
      }
      return promise;
    }
    len = requestInterceptorChain.length;
    let newConfig = config;
    while (i < len) {
      const onFulfilled = requestInterceptorChain[i++];
      const onRejected = requestInterceptorChain[i++];
      try {
        newConfig = onFulfilled(newConfig);
      } catch (error) {
        onRejected.call(this, error);
        break;
      }
    }
    try {
      promise = dispatchRequest.call(this, newConfig);
    } catch (error) {
      return Promise.reject(error);
    }
    i = 0;
    len = responseInterceptorChain.length;
    while (i < len) {
      promise = promise.then(responseInterceptorChain[i++], responseInterceptorChain[i++]);
    }
    return promise;
  }
  getUri(config) {
    config = mergeConfig$1(this.defaults, config);
    const fullPath = buildFullPath(config.baseURL, config.url, config.allowAbsoluteUrls);
    return buildURL(fullPath, config.params, config.paramsSerializer);
  }
};
utils$1.forEach(["delete", "get", "head", "options"], function forEachMethodNoData(method) {
  Axios$1.prototype[method] = function(url, config) {
    return this.request(mergeConfig$1(config || {}, {
      method,
      url,
      data: (config || {}).data
    }));
  };
});
utils$1.forEach(["post", "put", "patch"], function forEachMethodWithData(method) {
  function generateHTTPMethod(isForm) {
    return function httpMethod(url, data, config) {
      return this.request(mergeConfig$1(config || {}, {
        method,
        headers: isForm ? {
          "Content-Type": "multipart/form-data"
        } : {},
        url,
        data
      }));
    };
  }
  Axios$1.prototype[method] = generateHTTPMethod();
  Axios$1.prototype[method + "Form"] = generateHTTPMethod(true);
});
let CancelToken$1 = class CancelToken {
  constructor(executor) {
    if (typeof executor !== "function") {
      throw new TypeError("executor must be a function.");
    }
    let resolvePromise;
    this.promise = new Promise(function promiseExecutor(resolve) {
      resolvePromise = resolve;
    });
    const token = this;
    this.promise.then((cancel) => {
      if (!token._listeners) return;
      let i = token._listeners.length;
      while (i-- > 0) {
        token._listeners[i](cancel);
      }
      token._listeners = null;
    });
    this.promise.then = (onfulfilled) => {
      let _resolve;
      const promise = new Promise((resolve) => {
        token.subscribe(resolve);
        _resolve = resolve;
      }).then(onfulfilled);
      promise.cancel = function reject() {
        token.unsubscribe(_resolve);
      };
      return promise;
    };
    executor(function cancel(message, config, request) {
      if (token.reason) {
        return;
      }
      token.reason = new CanceledError$1(message, config, request);
      resolvePromise(token.reason);
    });
  }
  /**
   * Throws a `CanceledError` if cancellation has been requested.
   */
  throwIfRequested() {
    if (this.reason) {
      throw this.reason;
    }
  }
  /**
   * Subscribe to the cancel signal
   */
  subscribe(listener) {
    if (this.reason) {
      listener(this.reason);
      return;
    }
    if (this._listeners) {
      this._listeners.push(listener);
    } else {
      this._listeners = [listener];
    }
  }
  /**
   * Unsubscribe from the cancel signal
   */
  unsubscribe(listener) {
    if (!this._listeners) {
      return;
    }
    const index = this._listeners.indexOf(listener);
    if (index !== -1) {
      this._listeners.splice(index, 1);
    }
  }
  toAbortSignal() {
    const controller = new AbortController();
    const abort = (err) => {
      controller.abort(err);
    };
    this.subscribe(abort);
    controller.signal.unsubscribe = () => this.unsubscribe(abort);
    return controller.signal;
  }
  /**
   * Returns an object that contains a new `CancelToken` and a function that, when called,
   * cancels the `CancelToken`.
   */
  static source() {
    let cancel;
    const token = new CancelToken(function executor(c2) {
      cancel = c2;
    });
    return {
      token,
      cancel
    };
  }
};
function spread$1(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
}
function isAxiosError$1(payload) {
  return utils$1.isObject(payload) && payload.isAxiosError === true;
}
const HttpStatusCode$1 = {
  Continue: 100,
  SwitchingProtocols: 101,
  Processing: 102,
  EarlyHints: 103,
  Ok: 200,
  Created: 201,
  Accepted: 202,
  NonAuthoritativeInformation: 203,
  NoContent: 204,
  ResetContent: 205,
  PartialContent: 206,
  MultiStatus: 207,
  AlreadyReported: 208,
  ImUsed: 226,
  MultipleChoices: 300,
  MovedPermanently: 301,
  Found: 302,
  SeeOther: 303,
  NotModified: 304,
  UseProxy: 305,
  Unused: 306,
  TemporaryRedirect: 307,
  PermanentRedirect: 308,
  BadRequest: 400,
  Unauthorized: 401,
  PaymentRequired: 402,
  Forbidden: 403,
  NotFound: 404,
  MethodNotAllowed: 405,
  NotAcceptable: 406,
  ProxyAuthenticationRequired: 407,
  RequestTimeout: 408,
  Conflict: 409,
  Gone: 410,
  LengthRequired: 411,
  PreconditionFailed: 412,
  PayloadTooLarge: 413,
  UriTooLong: 414,
  UnsupportedMediaType: 415,
  RangeNotSatisfiable: 416,
  ExpectationFailed: 417,
  ImATeapot: 418,
  MisdirectedRequest: 421,
  UnprocessableEntity: 422,
  Locked: 423,
  FailedDependency: 424,
  TooEarly: 425,
  UpgradeRequired: 426,
  PreconditionRequired: 428,
  TooManyRequests: 429,
  RequestHeaderFieldsTooLarge: 431,
  UnavailableForLegalReasons: 451,
  InternalServerError: 500,
  NotImplemented: 501,
  BadGateway: 502,
  ServiceUnavailable: 503,
  GatewayTimeout: 504,
  HttpVersionNotSupported: 505,
  VariantAlsoNegotiates: 506,
  InsufficientStorage: 507,
  LoopDetected: 508,
  NotExtended: 510,
  NetworkAuthenticationRequired: 511,
  WebServerIsDown: 521,
  ConnectionTimedOut: 522,
  OriginIsUnreachable: 523,
  TimeoutOccurred: 524,
  SslHandshakeFailed: 525,
  InvalidSslCertificate: 526
};
Object.entries(HttpStatusCode$1).forEach(([key, value]) => {
  HttpStatusCode$1[value] = key;
});
function createInstance(defaultConfig) {
  const context = new Axios$1(defaultConfig);
  const instance = bind(Axios$1.prototype.request, context);
  utils$1.extend(instance, Axios$1.prototype, context, { allOwnKeys: true });
  utils$1.extend(instance, context, null, { allOwnKeys: true });
  instance.create = function create2(instanceConfig) {
    return createInstance(mergeConfig$1(defaultConfig, instanceConfig));
  };
  return instance;
}
const axios = createInstance(defaults);
axios.Axios = Axios$1;
axios.CanceledError = CanceledError$1;
axios.CancelToken = CancelToken$1;
axios.isCancel = isCancel$1;
axios.VERSION = VERSION$1;
axios.toFormData = toFormData$1;
axios.AxiosError = AxiosError$1;
axios.Cancel = axios.CanceledError;
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = spread$1;
axios.isAxiosError = isAxiosError$1;
axios.mergeConfig = mergeConfig$1;
axios.AxiosHeaders = AxiosHeaders$1;
axios.formToJSON = (thing) => formDataToJSON(utils$1.isHTMLForm(thing) ? new FormData(thing) : thing);
axios.getAdapter = adapters.getAdapter;
axios.HttpStatusCode = HttpStatusCode$1;
axios.default = axios;
const {
  Axios: Axios2,
  AxiosError: AxiosError2,
  CanceledError: CanceledError2,
  isCancel,
  CancelToken: CancelToken2,
  VERSION,
  all: all2,
  Cancel,
  isAxiosError,
  spread,
  toFormData,
  AxiosHeaders: AxiosHeaders2,
  HttpStatusCode,
  formToJSON,
  getAdapter,
  mergeConfig
} = axios;
/*!
 * SPDX-License-Identifier: GPL-3.0-or-later
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 */
const client = axios.create({
  headers: {
    requesttoken: getRequestToken() ?? "",
    "X-Requested-With": "XMLHttpRequest"
  }
});
onRequestTokenUpdate((token) => {
  client.defaults.headers.requesttoken = token;
});
const cancelableClient = Object.assign(client, {
  CancelToken: axios.CancelToken,
  isCancel: axios.isCancel
});
const RETRY_KEY = /* @__PURE__ */ Symbol("csrf-retry");
function onCsrfTokenError(axios2) {
  return async (error) => {
    if (!isAxiosError(error)) {
      throw error;
    }
    const { config, response, request } = error;
    const responseURL = request?.responseURL;
    if (config && !config[RETRY_KEY] && response?.status === 412 && response?.data?.message === "CSRF check failed") {
      console.warn(`Request to ${responseURL} failed because of a CSRF mismatch. Fetching a new token`);
      const { data: { token } } = await axios2.get(generateUrl("/csrftoken"));
      console.debug(`New request token ${token} fetched`);
      axios2.defaults.headers.requesttoken = token;
      return axios2({
        ...config,
        headers: {
          ...config.headers,
          requesttoken: token
        },
        [RETRY_KEY]: true
      });
    }
    throw error;
  };
}
const RETRY_DELAY_KEY = /* @__PURE__ */ Symbol("retryDelay");
function onMaintenanceModeError(axios2) {
  return async (error) => {
    if (!isAxiosError(error)) {
      throw error;
    }
    const { config, response, request } = error;
    const responseURL = request?.responseURL;
    const status = response?.status;
    const headers = response?.headers;
    let retryDelay = typeof config?.[RETRY_DELAY_KEY] === "number" ? config?.[RETRY_DELAY_KEY] : 1;
    if (status === 503 && headers?.["x-nextcloud-maintenance-mode"] === "1" && config?.retryIfMaintenanceMode) {
      retryDelay *= 2;
      if (retryDelay > 32) {
        console.error("Retry delay exceeded one minute, giving up.", { responseURL });
        throw error;
      }
      console.warn(`Request to ${responseURL} failed because of maintenance mode. Retrying in ${retryDelay}s`);
      await new Promise((resolve) => {
        setTimeout(resolve, retryDelay * 1e3);
      });
      return axios2({
        ...config,
        [RETRY_DELAY_KEY]: retryDelay
      });
    }
    throw error;
  };
}
async function onNotLoggedInError(error) {
  if (isAxiosError(error)) {
    const { config, response, request } = error;
    const responseURL = request?.responseURL;
    const status = response?.status;
    if (status === 401 && response?.data?.message === "Current user is not logged in" && config?.reloadExpiredSession && window?.location) {
      console.error(`Request to ${responseURL} failed because the user session expired. Reloading the page …`);
      window.location.reload();
    }
  }
  throw error;
}
cancelableClient.interceptors.response.use((r) => r, onCsrfTokenError(cancelableClient));
cancelableClient.interceptors.response.use((r) => r, onMaintenanceModeError(cancelableClient));
cancelableClient.interceptors.response.use((r) => r, onNotLoggedInError);
window._nc_vue_element_id = window._nc_vue_element_id ?? 0;
function createElementId() {
  return `nc-vue-${window._nc_vue_element_id++}`;
}
const _hoisted_1$8 = ["aria-label"];
const _hoisted_2$7 = ["width", "height"];
const _hoisted_3$7 = ["fill"];
const _hoisted_4$6 = ["fill"];
const _hoisted_5 = { key: 0 };
const _sfc_main$a = /* @__PURE__ */ defineComponent({
  __name: "NcLoadingIcon",
  props: {
    appearance: { default: "auto" },
    name: { default: "" },
    size: { default: 20 }
  },
  setup(__props) {
    const props = __props;
    const colors = computed(() => {
      const colors2 = ["#777", "#CCC"];
      if (props.appearance === "light") {
        return colors2;
      } else if (props.appearance === "dark") {
        return colors2.reverse();
      }
      return ["var(--color-loading-light)", "var(--color-loading-dark)"];
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("span", {
        "aria-label": _ctx.name,
        role: "img",
        class: "material-design-icon loading-icon"
      }, [
        (openBlock(), createElementBlock("svg", {
          width: _ctx.size,
          height: _ctx.size,
          viewBox: "0 0 24 24"
        }, [
          createBaseVNode("path", {
            fill: colors.value[0],
            d: "M12,4V2A10,10 0 1,0 22,12H20A8,8 0 1,1 12,4Z"
          }, null, 8, _hoisted_3$7),
          createBaseVNode("path", {
            fill: colors.value[1],
            d: "M12,4V2A10,10 0 0,1 22,12H20A8,8 0 0,0 12,4Z"
          }, [
            _ctx.name ? (openBlock(), createElementBlock("title", _hoisted_5, toDisplayString(_ctx.name), 1)) : createCommentVNode("", true)
          ], 8, _hoisted_4$6)
        ], 8, _hoisted_2$7))
      ], 8, _hoisted_1$8);
    };
  }
});
const NcLoadingIcon = /* @__PURE__ */ _export_sfc$1(_sfc_main$a, [["__scopeId", "data-v-cf399190"]]);
/*!
* tabbable 6.4.0
* @license MIT, https://github.com/focus-trap/tabbable/blob/master/LICENSE
*/
var candidateSelectors = ["input:not([inert]):not([inert] *)", "select:not([inert]):not([inert] *)", "textarea:not([inert]):not([inert] *)", "a[href]:not([inert]):not([inert] *)", "button:not([inert]):not([inert] *)", "[tabindex]:not(slot):not([inert]):not([inert] *)", "audio[controls]:not([inert]):not([inert] *)", "video[controls]:not([inert]):not([inert] *)", '[contenteditable]:not([contenteditable="false"]):not([inert]):not([inert] *)', "details>summary:first-of-type:not([inert]):not([inert] *)", "details:not([inert]):not([inert] *)"];
var candidateSelector = /* @__PURE__ */ candidateSelectors.join(",");
var NoElement = typeof Element === "undefined";
var matches = NoElement ? function() {
} : Element.prototype.matches || Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
var getRootNode = !NoElement && Element.prototype.getRootNode ? function(element) {
  var _element$getRootNode;
  return element === null || element === void 0 ? void 0 : (_element$getRootNode = element.getRootNode) === null || _element$getRootNode === void 0 ? void 0 : _element$getRootNode.call(element);
} : function(element) {
  return element === null || element === void 0 ? void 0 : element.ownerDocument;
};
var _isInert = function isInert(node, lookUp) {
  var _node$getAttribute;
  if (lookUp === void 0) {
    lookUp = true;
  }
  var inertAtt = node === null || node === void 0 ? void 0 : (_node$getAttribute = node.getAttribute) === null || _node$getAttribute === void 0 ? void 0 : _node$getAttribute.call(node, "inert");
  var inert = inertAtt === "" || inertAtt === "true";
  var result = inert || lookUp && node && // closest does not exist on shadow roots, so we fall back to a manual
  // lookup upward, in case it is not defined.
  (typeof node.closest === "function" ? node.closest("[inert]") : _isInert(node.parentNode));
  return result;
};
var isContentEditable = function isContentEditable2(node) {
  var _node$getAttribute2;
  var attValue = node === null || node === void 0 ? void 0 : (_node$getAttribute2 = node.getAttribute) === null || _node$getAttribute2 === void 0 ? void 0 : _node$getAttribute2.call(node, "contenteditable");
  return attValue === "" || attValue === "true";
};
var getCandidates = function getCandidates2(el, includeContainer, filter2) {
  if (_isInert(el)) {
    return [];
  }
  var candidates = Array.prototype.slice.apply(el.querySelectorAll(candidateSelector));
  if (includeContainer && matches.call(el, candidateSelector)) {
    candidates.unshift(el);
  }
  candidates = candidates.filter(filter2);
  return candidates;
};
var _getCandidatesIteratively = function getCandidatesIteratively(elements, includeContainer, options) {
  var candidates = [];
  var elementsToCheck = Array.from(elements);
  while (elementsToCheck.length) {
    var element = elementsToCheck.shift();
    if (_isInert(element, false)) {
      continue;
    }
    if (element.tagName === "SLOT") {
      var assigned = element.assignedElements();
      var content = assigned.length ? assigned : element.children;
      var nestedCandidates = _getCandidatesIteratively(content, true, options);
      if (options.flatten) {
        candidates.push.apply(candidates, nestedCandidates);
      } else {
        candidates.push({
          scopeParent: element,
          candidates: nestedCandidates
        });
      }
    } else {
      var validCandidate = matches.call(element, candidateSelector);
      if (validCandidate && options.filter(element) && (includeContainer || !elements.includes(element))) {
        candidates.push(element);
      }
      var shadowRoot = element.shadowRoot || // check for an undisclosed shadow
      typeof options.getShadowRoot === "function" && options.getShadowRoot(element);
      var validShadowRoot = !_isInert(shadowRoot, false) && (!options.shadowRootFilter || options.shadowRootFilter(element));
      if (shadowRoot && validShadowRoot) {
        var _nestedCandidates = _getCandidatesIteratively(shadowRoot === true ? element.children : shadowRoot.children, true, options);
        if (options.flatten) {
          candidates.push.apply(candidates, _nestedCandidates);
        } else {
          candidates.push({
            scopeParent: element,
            candidates: _nestedCandidates
          });
        }
      } else {
        elementsToCheck.unshift.apply(elementsToCheck, element.children);
      }
    }
  }
  return candidates;
};
var hasTabIndex = function hasTabIndex2(node) {
  return !isNaN(parseInt(node.getAttribute("tabindex"), 10));
};
var getTabIndex = function getTabIndex2(node) {
  if (!node) {
    throw new Error("No node provided");
  }
  if (node.tabIndex < 0) {
    if ((/^(AUDIO|VIDEO|DETAILS)$/.test(node.tagName) || isContentEditable(node)) && !hasTabIndex(node)) {
      return 0;
    }
  }
  return node.tabIndex;
};
var getSortOrderTabIndex = function getSortOrderTabIndex2(node, isScope) {
  var tabIndex = getTabIndex(node);
  if (tabIndex < 0 && isScope && !hasTabIndex(node)) {
    return 0;
  }
  return tabIndex;
};
var sortOrderedTabbables = function sortOrderedTabbables2(a, b) {
  return a.tabIndex === b.tabIndex ? a.documentOrder - b.documentOrder : a.tabIndex - b.tabIndex;
};
var isInput = function isInput2(node) {
  return node.tagName === "INPUT";
};
var isHiddenInput = function isHiddenInput2(node) {
  return isInput(node) && node.type === "hidden";
};
var isDetailsWithSummary = function isDetailsWithSummary2(node) {
  var r = node.tagName === "DETAILS" && Array.prototype.slice.apply(node.children).some(function(child) {
    return child.tagName === "SUMMARY";
  });
  return r;
};
var getCheckedRadio = function getCheckedRadio2(nodes, form) {
  for (var i = 0; i < nodes.length; i++) {
    if (nodes[i].checked && nodes[i].form === form) {
      return nodes[i];
    }
  }
};
var isTabbableRadio = function isTabbableRadio2(node) {
  if (!node.name) {
    return true;
  }
  var radioScope = node.form || getRootNode(node);
  var queryRadios = function queryRadios2(name) {
    return radioScope.querySelectorAll('input[type="radio"][name="' + name + '"]');
  };
  var radioSet;
  if (typeof window !== "undefined" && typeof window.CSS !== "undefined" && typeof window.CSS.escape === "function") {
    radioSet = queryRadios(window.CSS.escape(node.name));
  } else {
    try {
      radioSet = queryRadios(node.name);
    } catch (err) {
      console.error("Looks like you have a radio button with a name attribute containing invalid CSS selector characters and need the CSS.escape polyfill: %s", err.message);
      return false;
    }
  }
  var checked = getCheckedRadio(radioSet, node.form);
  return !checked || checked === node;
};
var isRadio = function isRadio2(node) {
  return isInput(node) && node.type === "radio";
};
var isNonTabbableRadio = function isNonTabbableRadio2(node) {
  return isRadio(node) && !isTabbableRadio(node);
};
var isNodeAttached = function isNodeAttached2(node) {
  var _nodeRoot;
  var nodeRoot = node && getRootNode(node);
  var nodeRootHost = (_nodeRoot = nodeRoot) === null || _nodeRoot === void 0 ? void 0 : _nodeRoot.host;
  var attached = false;
  if (nodeRoot && nodeRoot !== node) {
    var _nodeRootHost, _nodeRootHost$ownerDo, _node$ownerDocument;
    attached = !!((_nodeRootHost = nodeRootHost) !== null && _nodeRootHost !== void 0 && (_nodeRootHost$ownerDo = _nodeRootHost.ownerDocument) !== null && _nodeRootHost$ownerDo !== void 0 && _nodeRootHost$ownerDo.contains(nodeRootHost) || node !== null && node !== void 0 && (_node$ownerDocument = node.ownerDocument) !== null && _node$ownerDocument !== void 0 && _node$ownerDocument.contains(node));
    while (!attached && nodeRootHost) {
      var _nodeRoot2, _nodeRootHost2, _nodeRootHost2$ownerD;
      nodeRoot = getRootNode(nodeRootHost);
      nodeRootHost = (_nodeRoot2 = nodeRoot) === null || _nodeRoot2 === void 0 ? void 0 : _nodeRoot2.host;
      attached = !!((_nodeRootHost2 = nodeRootHost) !== null && _nodeRootHost2 !== void 0 && (_nodeRootHost2$ownerD = _nodeRootHost2.ownerDocument) !== null && _nodeRootHost2$ownerD !== void 0 && _nodeRootHost2$ownerD.contains(nodeRootHost));
    }
  }
  return attached;
};
var isZeroArea = function isZeroArea2(node) {
  var _node$getBoundingClie = node.getBoundingClientRect(), width = _node$getBoundingClie.width, height = _node$getBoundingClie.height;
  return width === 0 && height === 0;
};
var isHidden = function isHidden2(node, _ref) {
  var displayCheck = _ref.displayCheck, getShadowRoot = _ref.getShadowRoot;
  if (displayCheck === "full-native") {
    if ("checkVisibility" in node) {
      var visible = node.checkVisibility({
        // Checking opacity might be desirable for some use cases, but natively,
        // opacity zero elements _are_ focusable and tabbable.
        checkOpacity: false,
        opacityProperty: false,
        contentVisibilityAuto: true,
        visibilityProperty: true,
        // This is an alias for `visibilityProperty`. Contemporary browsers
        // support both. However, this alias has wider browser support (Chrome
        // >= 105 and Firefox >= 106, vs. Chrome >= 121 and Firefox >= 122), so
        // we include it anyway.
        checkVisibilityCSS: true
      });
      return !visible;
    }
  }
  if (getComputedStyle(node).visibility === "hidden") {
    return true;
  }
  var isDirectSummary = matches.call(node, "details>summary:first-of-type");
  var nodeUnderDetails = isDirectSummary ? node.parentElement : node;
  if (matches.call(nodeUnderDetails, "details:not([open]) *")) {
    return true;
  }
  if (!displayCheck || displayCheck === "full" || // full-native can run this branch when it falls through in case
  // Element#checkVisibility is unsupported
  displayCheck === "full-native" || displayCheck === "legacy-full") {
    if (typeof getShadowRoot === "function") {
      var originalNode = node;
      while (node) {
        var parentElement = node.parentElement;
        var rootNode = getRootNode(node);
        if (parentElement && !parentElement.shadowRoot && getShadowRoot(parentElement) === true) {
          return isZeroArea(node);
        } else if (node.assignedSlot) {
          node = node.assignedSlot;
        } else if (!parentElement && rootNode !== node.ownerDocument) {
          node = rootNode.host;
        } else {
          node = parentElement;
        }
      }
      node = originalNode;
    }
    if (isNodeAttached(node)) {
      return !node.getClientRects().length;
    }
    if (displayCheck !== "legacy-full") {
      return true;
    }
  } else if (displayCheck === "non-zero-area") {
    return isZeroArea(node);
  }
  return false;
};
var isDisabledFromFieldset = function isDisabledFromFieldset2(node) {
  if (/^(INPUT|BUTTON|SELECT|TEXTAREA)$/.test(node.tagName)) {
    var parentNode = node.parentElement;
    while (parentNode) {
      if (parentNode.tagName === "FIELDSET" && parentNode.disabled) {
        for (var i = 0; i < parentNode.children.length; i++) {
          var child = parentNode.children.item(i);
          if (child.tagName === "LEGEND") {
            return matches.call(parentNode, "fieldset[disabled] *") ? true : !child.contains(node);
          }
        }
        return true;
      }
      parentNode = parentNode.parentElement;
    }
  }
  return false;
};
var isNodeMatchingSelectorFocusable = function isNodeMatchingSelectorFocusable2(options, node) {
  if (node.disabled || isHiddenInput(node) || isHidden(node, options) || // For a details element with a summary, the summary element gets the focus
  isDetailsWithSummary(node) || isDisabledFromFieldset(node)) {
    return false;
  }
  return true;
};
var isNodeMatchingSelectorTabbable = function isNodeMatchingSelectorTabbable2(options, node) {
  if (isNonTabbableRadio(node) || getTabIndex(node) < 0 || !isNodeMatchingSelectorFocusable(options, node)) {
    return false;
  }
  return true;
};
var isShadowRootTabbable = function isShadowRootTabbable2(shadowHostNode) {
  var tabIndex = parseInt(shadowHostNode.getAttribute("tabindex"), 10);
  if (isNaN(tabIndex) || tabIndex >= 0) {
    return true;
  }
  return false;
};
var _sortByOrder = function sortByOrder(candidates) {
  var regularTabbables = [];
  var orderedTabbables = [];
  candidates.forEach(function(item, i) {
    var isScope = !!item.scopeParent;
    var element = isScope ? item.scopeParent : item;
    var candidateTabindex = getSortOrderTabIndex(element, isScope);
    var elements = isScope ? _sortByOrder(item.candidates) : element;
    if (candidateTabindex === 0) {
      isScope ? regularTabbables.push.apply(regularTabbables, elements) : regularTabbables.push(element);
    } else {
      orderedTabbables.push({
        documentOrder: i,
        tabIndex: candidateTabindex,
        item,
        isScope,
        content: elements
      });
    }
  });
  return orderedTabbables.sort(sortOrderedTabbables).reduce(function(acc, sortable) {
    sortable.isScope ? acc.push.apply(acc, sortable.content) : acc.push(sortable.content);
    return acc;
  }, []).concat(regularTabbables);
};
var tabbable = function tabbable2(container, options) {
  options = options || {};
  var candidates;
  if (options.getShadowRoot) {
    candidates = _getCandidatesIteratively([container], options.includeContainer, {
      filter: isNodeMatchingSelectorTabbable.bind(null, options),
      flatten: false,
      getShadowRoot: options.getShadowRoot,
      shadowRootFilter: isShadowRootTabbable
    });
  } else {
    candidates = getCandidates(container, options.includeContainer, isNodeMatchingSelectorTabbable.bind(null, options));
  }
  return _sortByOrder(candidates);
};
var focusable = function focusable2(container, options) {
  options = options || {};
  var candidates;
  if (options.getShadowRoot) {
    candidates = _getCandidatesIteratively([container], options.includeContainer, {
      filter: isNodeMatchingSelectorFocusable.bind(null, options),
      flatten: true,
      getShadowRoot: options.getShadowRoot
    });
  } else {
    candidates = getCandidates(container, options.includeContainer, isNodeMatchingSelectorFocusable.bind(null, options));
  }
  return candidates;
};
var isTabbable = function isTabbable2(node, options) {
  options = options || {};
  if (!node) {
    throw new Error("No node provided");
  }
  if (matches.call(node, candidateSelector) === false) {
    return false;
  }
  return isNodeMatchingSelectorTabbable(options, node);
};
var focusableCandidateSelector = /* @__PURE__ */ candidateSelectors.concat("iframe:not([inert]):not([inert] *)").join(",");
var isFocusable = function isFocusable2(node, options) {
  options = options || {};
  if (!node) {
    throw new Error("No node provided");
  }
  if (matches.call(node, focusableCandidateSelector) === false) {
    return false;
  }
  return isNodeMatchingSelectorFocusable(options, node);
};
/*!
* focus-trap 8.0.0
* @license MIT, https://github.com/focus-trap/focus-trap/blob/master/LICENSE
*/
function _arrayLikeToArray(r, a) {
  (null == a || a > r.length) && (a = r.length);
  for (var e = 0, n2 = Array(a); e < a; e++) n2[e] = r[e];
  return n2;
}
function _arrayWithoutHoles(r) {
  if (Array.isArray(r)) return _arrayLikeToArray(r);
}
function asyncGeneratorStep(n2, t5, e, r, o, a, c2) {
  try {
    var i = n2[a](c2), u = i.value;
  } catch (n3) {
    return void e(n3);
  }
  i.done ? t5(u) : Promise.resolve(u).then(r, o);
}
function _asyncToGenerator(n2) {
  return function() {
    var t5 = this, e = arguments;
    return new Promise(function(r, o) {
      var a = n2.apply(t5, e);
      function _next(n3) {
        asyncGeneratorStep(a, r, o, _next, _throw, "next", n3);
      }
      function _throw(n3) {
        asyncGeneratorStep(a, r, o, _next, _throw, "throw", n3);
      }
      _next(void 0);
    });
  };
}
function _createForOfIteratorHelper(r, e) {
  var t5 = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
  if (!t5) {
    if (Array.isArray(r) || (t5 = _unsupportedIterableToArray(r)) || e) {
      t5 && (r = t5);
      var n2 = 0, F = function() {
      };
      return {
        s: F,
        n: function() {
          return n2 >= r.length ? {
            done: true
          } : {
            done: false,
            value: r[n2++]
          };
        },
        e: function(r2) {
          throw r2;
        },
        f: F
      };
    }
    throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }
  var o, a = true, u = false;
  return {
    s: function() {
      t5 = t5.call(r);
    },
    n: function() {
      var r2 = t5.next();
      return a = r2.done, r2;
    },
    e: function(r2) {
      u = true, o = r2;
    },
    f: function() {
      try {
        a || null == t5.return || t5.return();
      } finally {
        if (u) throw o;
      }
    }
  };
}
function _defineProperty(e, r, t5) {
  return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, {
    value: t5,
    enumerable: true,
    configurable: true,
    writable: true
  }) : e[r] = t5, e;
}
function _iterableToArray(r) {
  if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r);
}
function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
function ownKeys(e, r) {
  var t5 = Object.keys(e);
  if (Object.getOwnPropertySymbols) {
    var o = Object.getOwnPropertySymbols(e);
    r && (o = o.filter(function(r2) {
      return Object.getOwnPropertyDescriptor(e, r2).enumerable;
    })), t5.push.apply(t5, o);
  }
  return t5;
}
function _objectSpread2(e) {
  for (var r = 1; r < arguments.length; r++) {
    var t5 = null != arguments[r] ? arguments[r] : {};
    r % 2 ? ownKeys(Object(t5), true).forEach(function(r2) {
      _defineProperty(e, r2, t5[r2]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t5)) : ownKeys(Object(t5)).forEach(function(r2) {
      Object.defineProperty(e, r2, Object.getOwnPropertyDescriptor(t5, r2));
    });
  }
  return e;
}
function _regenerator() {
  /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */
  var e, t5, r = "function" == typeof Symbol ? Symbol : {}, n2 = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag";
  function i(r2, n3, o2, i2) {
    var c3 = n3 && n3.prototype instanceof Generator ? n3 : Generator, u2 = Object.create(c3.prototype);
    return _regeneratorDefine(u2, "_invoke", (function(r3, n4, o3) {
      var i3, c4, u3, f2 = 0, p = o3 || [], y2 = false, G2 = {
        p: 0,
        n: 0,
        v: e,
        a: d2,
        f: d2.bind(e, 4),
        d: function(t6, r4) {
          return i3 = t6, c4 = 0, u3 = e, G2.n = r4, a;
        }
      };
      function d2(r4, n5) {
        for (c4 = r4, u3 = n5, t5 = 0; !y2 && f2 && !o4 && t5 < p.length; t5++) {
          var o4, i4 = p[t5], d3 = G2.p, l = i4[2];
          r4 > 3 ? (o4 = l === n5) && (u3 = i4[(c4 = i4[4]) ? 5 : (c4 = 3, 3)], i4[4] = i4[5] = e) : i4[0] <= d3 && ((o4 = r4 < 2 && d3 < i4[1]) ? (c4 = 0, G2.v = n5, G2.n = i4[1]) : d3 < l && (o4 = r4 < 3 || i4[0] > n5 || n5 > l) && (i4[4] = r4, i4[5] = n5, G2.n = l, c4 = 0));
        }
        if (o4 || r4 > 1) return a;
        throw y2 = true, n5;
      }
      return function(o4, p2, l) {
        if (f2 > 1) throw TypeError("Generator is already running");
        for (y2 && 1 === p2 && d2(p2, l), c4 = p2, u3 = l; (t5 = c4 < 2 ? e : u3) || !y2; ) {
          i3 || (c4 ? c4 < 3 ? (c4 > 1 && (G2.n = -1), d2(c4, u3)) : G2.n = u3 : G2.v = u3);
          try {
            if (f2 = 2, i3) {
              if (c4 || (o4 = "next"), t5 = i3[o4]) {
                if (!(t5 = t5.call(i3, u3))) throw TypeError("iterator result is not an object");
                if (!t5.done) return t5;
                u3 = t5.value, c4 < 2 && (c4 = 0);
              } else 1 === c4 && (t5 = i3.return) && t5.call(i3), c4 < 2 && (u3 = TypeError("The iterator does not provide a '" + o4 + "' method"), c4 = 1);
              i3 = e;
            } else if ((t5 = (y2 = G2.n < 0) ? u3 : r3.call(n4, G2)) !== a) break;
          } catch (t6) {
            i3 = e, c4 = 1, u3 = t6;
          } finally {
            f2 = 1;
          }
        }
        return {
          value: t5,
          done: y2
        };
      };
    })(r2, o2, i2), true), u2;
  }
  var a = {};
  function Generator() {
  }
  function GeneratorFunction() {
  }
  function GeneratorFunctionPrototype() {
  }
  t5 = Object.getPrototypeOf;
  var c2 = [][n2] ? t5(t5([][n2]())) : (_regeneratorDefine(t5 = {}, n2, function() {
    return this;
  }), t5), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c2);
  function f(e2) {
    return Object.setPrototypeOf ? Object.setPrototypeOf(e2, GeneratorFunctionPrototype) : (e2.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine(e2, o, "GeneratorFunction")), e2.prototype = Object.create(u), e2;
  }
  return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine(u), _regeneratorDefine(u, o, "Generator"), _regeneratorDefine(u, n2, function() {
    return this;
  }), _regeneratorDefine(u, "toString", function() {
    return "[object Generator]";
  }), (_regenerator = function() {
    return {
      w: i,
      m: f
    };
  })();
}
function _regeneratorDefine(e, r, n2, t5) {
  var i = Object.defineProperty;
  try {
    i({}, "", {});
  } catch (e2) {
    i = 0;
  }
  _regeneratorDefine = function(e2, r2, n3, t6) {
    function o(r3, n4) {
      _regeneratorDefine(e2, r3, function(e3) {
        return this._invoke(r3, n4, e3);
      });
    }
    r2 ? i ? i(e2, r2, {
      value: n3,
      enumerable: !t6,
      configurable: !t6,
      writable: !t6
    }) : e2[r2] = n3 : (o("next", 0), o("throw", 1), o("return", 2));
  }, _regeneratorDefine(e, r, n2, t5);
}
function _toConsumableArray(r) {
  return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread();
}
function _toPrimitive(t5, r) {
  if ("object" != typeof t5 || !t5) return t5;
  var e = t5[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t5, r);
    if ("object" != typeof i) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t5);
}
function _toPropertyKey(t5) {
  var i = _toPrimitive(t5, "string");
  return "symbol" == typeof i ? i : i + "";
}
function _unsupportedIterableToArray(r, a) {
  if (r) {
    if ("string" == typeof r) return _arrayLikeToArray(r, a);
    var t5 = {}.toString.call(r).slice(8, -1);
    return "Object" === t5 && r.constructor && (t5 = r.constructor.name), "Map" === t5 || "Set" === t5 ? Array.from(r) : "Arguments" === t5 || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t5) ? _arrayLikeToArray(r, a) : void 0;
  }
}
var activeFocusTraps = {
  // Returns the trap from the top of the stack.
  getActiveTrap: function getActiveTrap(trapStack) {
    if ((trapStack === null || trapStack === void 0 ? void 0 : trapStack.length) > 0) {
      return trapStack[trapStack.length - 1];
    }
    return null;
  },
  // Pauses the currently active trap, then adds a new trap to the stack.
  activateTrap: function activateTrap(trapStack, trap) {
    var activeTrap = activeFocusTraps.getActiveTrap(trapStack);
    if (trap !== activeTrap) {
      activeFocusTraps.pauseTrap(trapStack);
    }
    var trapIndex = trapStack.indexOf(trap);
    if (trapIndex === -1) {
      trapStack.push(trap);
    } else {
      trapStack.splice(trapIndex, 1);
      trapStack.push(trap);
    }
  },
  // Removes the trap from the top of the stack, then unpauses the next trap down.
  deactivateTrap: function deactivateTrap(trapStack, trap) {
    var trapIndex = trapStack.indexOf(trap);
    if (trapIndex !== -1) {
      trapStack.splice(trapIndex, 1);
    }
    activeFocusTraps.unpauseTrap(trapStack);
  },
  // Pauses the trap at the top of the stack.
  pauseTrap: function pauseTrap(trapStack) {
    var activeTrap = activeFocusTraps.getActiveTrap(trapStack);
    activeTrap === null || activeTrap === void 0 || activeTrap._setPausedState(true);
  },
  // Unpauses the trap at the top of the stack.
  unpauseTrap: function unpauseTrap(trapStack) {
    var activeTrap = activeFocusTraps.getActiveTrap(trapStack);
    if (activeTrap && !activeTrap._isManuallyPaused()) {
      activeTrap._setPausedState(false);
    }
  }
};
var isSelectableInput = function isSelectableInput2(node) {
  return node.tagName && node.tagName.toLowerCase() === "input" && typeof node.select === "function";
};
var isEscapeEvent = function isEscapeEvent2(e) {
  return (e === null || e === void 0 ? void 0 : e.key) === "Escape" || (e === null || e === void 0 ? void 0 : e.key) === "Esc" || (e === null || e === void 0 ? void 0 : e.keyCode) === 27;
};
var isTabEvent = function isTabEvent2(e) {
  return (e === null || e === void 0 ? void 0 : e.key) === "Tab" || (e === null || e === void 0 ? void 0 : e.keyCode) === 9;
};
var isKeyForward = function isKeyForward2(e) {
  return isTabEvent(e) && !e.shiftKey;
};
var isKeyBackward = function isKeyBackward2(e) {
  return isTabEvent(e) && e.shiftKey;
};
var delay = function delay2(fn) {
  return setTimeout(fn, 0);
};
var valueOrHandler = function valueOrHandler2(value) {
  for (var _len = arguments.length, params = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
    params[_key - 1] = arguments[_key];
  }
  return typeof value === "function" ? value.apply(void 0, params) : value;
};
var getActualTarget = function getActualTarget2(event) {
  return event.target.shadowRoot && typeof event.composedPath === "function" ? event.composedPath()[0] : event.target;
};
var internalTrapStack = [];
var createFocusTrap = function createFocusTrap2(elements, userOptions) {
  var doc = (userOptions === null || userOptions === void 0 ? void 0 : userOptions.document) || document;
  var trapStack = (userOptions === null || userOptions === void 0 ? void 0 : userOptions.trapStack) || internalTrapStack;
  var config = _objectSpread2({
    returnFocusOnDeactivate: true,
    escapeDeactivates: true,
    delayInitialFocus: true,
    isolateSubtrees: false,
    isKeyForward,
    isKeyBackward
  }, userOptions);
  var state = {
    // containers given to createFocusTrap()
    /** @type {Array<HTMLElement>} */
    containers: [],
    // list of objects identifying tabbable nodes in `containers` in the trap
    // NOTE: it's possible that a group has no tabbable nodes if nodes get removed while the trap
    //  is active, but the trap should never get to a state where there isn't at least one group
    //  with at least one tabbable node in it (that would lead to an error condition that would
    //  result in an error being thrown)
    /** @type {Array<{
     *    container: HTMLElement,
     *    tabbableNodes: Array<HTMLElement>, // empty if none
     *    focusableNodes: Array<HTMLElement>, // empty if none
     *    posTabIndexesFound: boolean,
     *    firstTabbableNode: HTMLElement|undefined,
     *    lastTabbableNode: HTMLElement|undefined,
     *    firstDomTabbableNode: HTMLElement|undefined,
     *    lastDomTabbableNode: HTMLElement|undefined,
     *    nextTabbableNode: (node: HTMLElement, forward: boolean) => HTMLElement|undefined
     *  }>}
     */
    containerGroups: [],
    // same order/length as `containers` list
    // references to objects in `containerGroups`, but only those that actually have
    //  tabbable nodes in them
    // NOTE: same order as `containers` and `containerGroups`, but __not necessarily__
    //  the same length
    tabbableGroups: [],
    // references to nodes that are siblings to the ancestors of this trap's containers.
    /** @type {Set<HTMLElement>} */
    adjacentElements: /* @__PURE__ */ new Set(),
    // references to nodes that were inert or aria-hidden before the trap was activated.
    /** @type {Set<HTMLElement>} */
    alreadySilent: /* @__PURE__ */ new Set(),
    nodeFocusedBeforeActivation: null,
    mostRecentlyFocusedNode: null,
    active: false,
    paused: false,
    manuallyPaused: false,
    // timer ID for when delayInitialFocus is true and initial focus in this trap
    //  has been delayed during activation
    delayInitialFocusTimer: void 0,
    // the most recent KeyboardEvent for the configured nav key (typically [SHIFT+]TAB), if any
    recentNavEvent: void 0
  };
  var trap;
  var getOption = function getOption2(configOverrideOptions, optionName, configOptionName) {
    return configOverrideOptions && configOverrideOptions[optionName] !== void 0 ? configOverrideOptions[optionName] : config[configOptionName || optionName];
  };
  var findContainerIndex = function findContainerIndex2(element, event) {
    var composedPath = typeof (event === null || event === void 0 ? void 0 : event.composedPath) === "function" ? event.composedPath() : void 0;
    return state.containerGroups.findIndex(function(_ref) {
      var container = _ref.container, tabbableNodes = _ref.tabbableNodes;
      return container.contains(element) || // fall back to explicit tabbable search which will take into consideration any
      //  web components if the `tabbableOptions.getShadowRoot` option was used for
      //  the trap, enabling shadow DOM support in tabbable (`Node.contains()` doesn't
      //  look inside web components even if open)
      (composedPath === null || composedPath === void 0 ? void 0 : composedPath.includes(container)) || tabbableNodes.find(function(node) {
        return node === element;
      });
    });
  };
  var getNodeForOption = function getNodeForOption2(optionName) {
    var _ref2 = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : {}, _ref2$hasFallback = _ref2.hasFallback, hasFallback = _ref2$hasFallback === void 0 ? false : _ref2$hasFallback, _ref2$params = _ref2.params, params = _ref2$params === void 0 ? [] : _ref2$params;
    var optionValue = config[optionName];
    if (typeof optionValue === "function") {
      optionValue = optionValue.apply(void 0, _toConsumableArray(params));
    }
    if (optionValue === true) {
      optionValue = void 0;
    }
    if (!optionValue) {
      if (optionValue === void 0 || optionValue === false) {
        return optionValue;
      }
      throw new Error("`".concat(optionName, "` was specified but was not a node, or did not return a node"));
    }
    var node = optionValue;
    if (typeof optionValue === "string") {
      try {
        node = doc.querySelector(optionValue);
      } catch (err) {
        throw new Error("`".concat(optionName, '` appears to be an invalid selector; error="').concat(err.message, '"'));
      }
      if (!node) {
        if (!hasFallback) {
          throw new Error("`".concat(optionName, "` as selector refers to no known node"));
        }
      }
    }
    return node;
  };
  var getInitialFocusNode = function getInitialFocusNode2() {
    var node = getNodeForOption("initialFocus", {
      hasFallback: true
    });
    if (node === false) {
      return false;
    }
    if (node === void 0 || node && !isFocusable(node, config.tabbableOptions)) {
      if (findContainerIndex(doc.activeElement) >= 0) {
        node = doc.activeElement;
      } else {
        var firstTabbableGroup = state.tabbableGroups[0];
        var firstTabbableNode = firstTabbableGroup && firstTabbableGroup.firstTabbableNode;
        node = firstTabbableNode || getNodeForOption("fallbackFocus");
      }
    } else if (node === null) {
      node = getNodeForOption("fallbackFocus");
    }
    if (!node) {
      throw new Error("Your focus-trap needs to have at least one focusable element");
    }
    return node;
  };
  var updateTabbableNodes = function updateTabbableNodes2() {
    state.containerGroups = state.containers.map(function(container) {
      var tabbableNodes = tabbable(container, config.tabbableOptions);
      var focusableNodes = focusable(container, config.tabbableOptions);
      var firstTabbableNode = tabbableNodes.length > 0 ? tabbableNodes[0] : void 0;
      var lastTabbableNode = tabbableNodes.length > 0 ? tabbableNodes[tabbableNodes.length - 1] : void 0;
      var firstDomTabbableNode = focusableNodes.find(function(node) {
        return isTabbable(node);
      });
      var lastDomTabbableNode = focusableNodes.slice().reverse().find(function(node) {
        return isTabbable(node);
      });
      var posTabIndexesFound = !!tabbableNodes.find(function(node) {
        return getTabIndex(node) > 0;
      });
      return {
        container,
        tabbableNodes,
        focusableNodes,
        /** True if at least one node with positive `tabindex` was found in this container. */
        posTabIndexesFound,
        /** First tabbable node in container, __tabindex__ order; `undefined` if none. */
        firstTabbableNode,
        /** Last tabbable node in container, __tabindex__ order; `undefined` if none. */
        lastTabbableNode,
        // NOTE: DOM order is NOT NECESSARILY "document position" order, but figuring that out
        //  would require more than just https://developer.mozilla.org/en-US/docs/Web/API/Node/compareDocumentPosition
        //  because that API doesn't work with Shadow DOM as well as it should (@see
        //  https://github.com/whatwg/dom/issues/320) and since this first/last is only needed, so far,
        //  to address an edge case related to positive tabindex support, this seems like a much easier,
        //  "close enough most of the time" alternative for positive tabindexes which should generally
        //  be avoided anyway...
        /** First tabbable node in container, __DOM__ order; `undefined` if none. */
        firstDomTabbableNode,
        /** Last tabbable node in container, __DOM__ order; `undefined` if none. */
        lastDomTabbableNode,
        /**
         * Finds the __tabbable__ node that follows the given node in the specified direction,
         *  in this container, if any.
         * @param {HTMLElement} node
         * @param {boolean} [forward] True if going in forward tab order; false if going
         *  in reverse.
         * @returns {HTMLElement|undefined} The next tabbable node, if any.
         */
        nextTabbableNode: function nextTabbableNode(node) {
          var forward = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : true;
          var nodeIdx = tabbableNodes.indexOf(node);
          if (nodeIdx < 0) {
            if (forward) {
              return focusableNodes.slice(focusableNodes.indexOf(node) + 1).find(function(el) {
                return isTabbable(el);
              });
            }
            return focusableNodes.slice(0, focusableNodes.indexOf(node)).reverse().find(function(el) {
              return isTabbable(el);
            });
          }
          return tabbableNodes[nodeIdx + (forward ? 1 : -1)];
        }
      };
    });
    state.tabbableGroups = state.containerGroups.filter(function(group) {
      return group.tabbableNodes.length > 0;
    });
    if (state.tabbableGroups.length <= 0 && !getNodeForOption("fallbackFocus")) {
      throw new Error("Your focus-trap must have at least one container with at least one tabbable node in it at all times");
    }
    if (state.containerGroups.find(function(g2) {
      return g2.posTabIndexesFound;
    }) && state.containerGroups.length > 1) {
      throw new Error("At least one node with a positive tabindex was found in one of your focus-trap's multiple containers. Positive tabindexes are only supported in single-container focus-traps.");
    }
  };
  var _getActiveElement = function getActiveElement(el) {
    var activeElement = el.activeElement;
    if (!activeElement) {
      return;
    }
    if (activeElement.shadowRoot && activeElement.shadowRoot.activeElement !== null) {
      return _getActiveElement(activeElement.shadowRoot);
    }
    return activeElement;
  };
  var _tryFocus = function tryFocus(node) {
    if (node === false) {
      return;
    }
    if (node === _getActiveElement(document)) {
      return;
    }
    if (!node || !node.focus) {
      _tryFocus(getInitialFocusNode());
      return;
    }
    node.focus({
      preventScroll: !!config.preventScroll
    });
    state.mostRecentlyFocusedNode = node;
    if (isSelectableInput(node)) {
      node.select();
    }
  };
  var getReturnFocusNode = function getReturnFocusNode2(previousActiveElement) {
    var node = getNodeForOption("setReturnFocus", {
      params: [previousActiveElement]
    });
    return node ? node : node === false ? false : previousActiveElement;
  };
  var findNextNavNode = function findNextNavNode2(_ref3) {
    var target2 = _ref3.target, event = _ref3.event, _ref3$isBackward = _ref3.isBackward, isBackward = _ref3$isBackward === void 0 ? false : _ref3$isBackward;
    target2 = target2 || getActualTarget(event);
    updateTabbableNodes();
    var destinationNode = null;
    if (state.tabbableGroups.length > 0) {
      var containerIndex = findContainerIndex(target2, event);
      var containerGroup = containerIndex >= 0 ? state.containerGroups[containerIndex] : void 0;
      if (containerIndex < 0) {
        if (isBackward) {
          destinationNode = state.tabbableGroups[state.tabbableGroups.length - 1].lastTabbableNode;
        } else {
          destinationNode = state.tabbableGroups[0].firstTabbableNode;
        }
      } else if (isBackward) {
        var startOfGroupIndex = state.tabbableGroups.findIndex(function(_ref4) {
          var firstTabbableNode = _ref4.firstTabbableNode;
          return target2 === firstTabbableNode;
        });
        if (startOfGroupIndex < 0 && (containerGroup.container === target2 || isFocusable(target2, config.tabbableOptions) && !isTabbable(target2, config.tabbableOptions) && !containerGroup.nextTabbableNode(target2, false))) {
          startOfGroupIndex = containerIndex;
        }
        if (startOfGroupIndex >= 0) {
          var destinationGroupIndex = startOfGroupIndex === 0 ? state.tabbableGroups.length - 1 : startOfGroupIndex - 1;
          var destinationGroup = state.tabbableGroups[destinationGroupIndex];
          destinationNode = getTabIndex(target2) >= 0 ? destinationGroup.lastTabbableNode : destinationGroup.lastDomTabbableNode;
        } else if (!isTabEvent(event)) {
          destinationNode = containerGroup.nextTabbableNode(target2, false);
        }
      } else {
        var lastOfGroupIndex = state.tabbableGroups.findIndex(function(_ref5) {
          var lastTabbableNode = _ref5.lastTabbableNode;
          return target2 === lastTabbableNode;
        });
        if (lastOfGroupIndex < 0 && (containerGroup.container === target2 || isFocusable(target2, config.tabbableOptions) && !isTabbable(target2, config.tabbableOptions) && !containerGroup.nextTabbableNode(target2))) {
          lastOfGroupIndex = containerIndex;
        }
        if (lastOfGroupIndex >= 0) {
          var _destinationGroupIndex = lastOfGroupIndex === state.tabbableGroups.length - 1 ? 0 : lastOfGroupIndex + 1;
          var _destinationGroup = state.tabbableGroups[_destinationGroupIndex];
          destinationNode = getTabIndex(target2) >= 0 ? _destinationGroup.firstTabbableNode : _destinationGroup.firstDomTabbableNode;
        } else if (!isTabEvent(event)) {
          destinationNode = containerGroup.nextTabbableNode(target2);
        }
      }
    } else {
      destinationNode = getNodeForOption("fallbackFocus");
    }
    return destinationNode;
  };
  var checkPointerDown = function checkPointerDown2(e) {
    var target2 = getActualTarget(e);
    if (findContainerIndex(target2, e) >= 0) {
      return;
    }
    if (valueOrHandler(config.clickOutsideDeactivates, e)) {
      trap.deactivate({
        // NOTE: by setting `returnFocus: false`, deactivate() will do nothing,
        //  which will result in the outside click setting focus to the node
        //  that was clicked (and if not focusable, to "nothing"); by setting
        //  `returnFocus: true`, we'll attempt to re-focus the node originally-focused
        //  on activation (or the configured `setReturnFocus` node), whether the
        //  outside click was on a focusable node or not
        returnFocus: config.returnFocusOnDeactivate
      });
      return;
    }
    if (valueOrHandler(config.allowOutsideClick, e)) {
      return;
    }
    e.preventDefault();
  };
  var checkFocusIn = function checkFocusIn2(event) {
    var target2 = getActualTarget(event);
    var targetContained = findContainerIndex(target2, event) >= 0;
    if (targetContained || target2 instanceof Document) {
      if (targetContained) {
        state.mostRecentlyFocusedNode = target2;
      }
    } else {
      event.stopImmediatePropagation();
      var nextNode;
      var navAcrossContainers = true;
      if (state.mostRecentlyFocusedNode) {
        if (getTabIndex(state.mostRecentlyFocusedNode) > 0) {
          var mruContainerIdx = findContainerIndex(state.mostRecentlyFocusedNode);
          var tabbableNodes = state.containerGroups[mruContainerIdx].tabbableNodes;
          if (tabbableNodes.length > 0) {
            var mruTabIdx = tabbableNodes.findIndex(function(node) {
              return node === state.mostRecentlyFocusedNode;
            });
            if (mruTabIdx >= 0) {
              if (config.isKeyForward(state.recentNavEvent)) {
                if (mruTabIdx + 1 < tabbableNodes.length) {
                  nextNode = tabbableNodes[mruTabIdx + 1];
                  navAcrossContainers = false;
                }
              } else {
                if (mruTabIdx - 1 >= 0) {
                  nextNode = tabbableNodes[mruTabIdx - 1];
                  navAcrossContainers = false;
                }
              }
            }
          }
        } else {
          if (!state.containerGroups.some(function(g2) {
            return g2.tabbableNodes.some(function(n2) {
              return getTabIndex(n2) > 0;
            });
          })) {
            navAcrossContainers = false;
          }
        }
      } else {
        navAcrossContainers = false;
      }
      if (navAcrossContainers) {
        nextNode = findNextNavNode({
          // move FROM the MRU node, not event-related node (which will be the node that is
          //  outside the trap causing the focus escape we're trying to fix)
          target: state.mostRecentlyFocusedNode,
          isBackward: config.isKeyBackward(state.recentNavEvent)
        });
      }
      if (nextNode) {
        _tryFocus(nextNode);
      } else {
        _tryFocus(state.mostRecentlyFocusedNode || getInitialFocusNode());
      }
    }
    state.recentNavEvent = void 0;
  };
  var checkKeyNav = function checkKeyNav2(event) {
    var isBackward = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : false;
    state.recentNavEvent = event;
    var destinationNode = findNextNavNode({
      event,
      isBackward
    });
    if (destinationNode) {
      if (isTabEvent(event)) {
        event.preventDefault();
      }
      _tryFocus(destinationNode);
    }
  };
  var checkTabKey = function checkTabKey2(event) {
    if (config.isKeyForward(event) || config.isKeyBackward(event)) {
      checkKeyNav(event, config.isKeyBackward(event));
    }
  };
  var checkEscapeKey = function checkEscapeKey2(event) {
    if (isEscapeEvent(event) && valueOrHandler(config.escapeDeactivates, event) !== false) {
      event.preventDefault();
      trap.deactivate();
    }
  };
  var checkClick = function checkClick2(e) {
    var target2 = getActualTarget(e);
    if (findContainerIndex(target2, e) >= 0) {
      return;
    }
    if (valueOrHandler(config.clickOutsideDeactivates, e)) {
      return;
    }
    if (valueOrHandler(config.allowOutsideClick, e)) {
      return;
    }
    e.preventDefault();
    e.stopImmediatePropagation();
  };
  var addListeners = function addListeners2() {
    if (!state.active) {
      return Promise.resolve();
    }
    activeFocusTraps.activateTrap(trapStack, trap);
    var promise;
    if (config.delayInitialFocus) {
      promise = new Promise(function(resolve) {
        state.delayInitialFocusTimer = delay(function() {
          _tryFocus(getInitialFocusNode());
          resolve();
        });
      });
    } else {
      promise = Promise.resolve();
      _tryFocus(getInitialFocusNode());
    }
    doc.addEventListener("focusin", checkFocusIn, true);
    doc.addEventListener("mousedown", checkPointerDown, {
      capture: true,
      passive: false
    });
    doc.addEventListener("touchstart", checkPointerDown, {
      capture: true,
      passive: false
    });
    doc.addEventListener("click", checkClick, {
      capture: true,
      passive: false
    });
    doc.addEventListener("keydown", checkTabKey, {
      capture: true,
      passive: false
    });
    doc.addEventListener("keydown", checkEscapeKey);
    return promise;
  };
  var collectAdjacentElements = function collectAdjacentElements2(containers) {
    if (state.active && !state.paused) {
      trap._setSubtreeIsolation(false);
    }
    state.adjacentElements.clear();
    state.alreadySilent.clear();
    var containerAncestors = /* @__PURE__ */ new Set();
    var adjacentElements = /* @__PURE__ */ new Set();
    var _iterator = _createForOfIteratorHelper(containers), _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done; ) {
        var container = _step.value;
        containerAncestors.add(container);
        var insideShadowRoot = typeof ShadowRoot !== "undefined" && container.getRootNode() instanceof ShadowRoot;
        var current = container;
        while (current) {
          containerAncestors.add(current);
          var parent = current.parentElement;
          var siblings = [];
          if (parent) {
            siblings = parent.children;
          } else if (!parent && insideShadowRoot) {
            siblings = current.getRootNode().children;
            parent = current.getRootNode().host;
            insideShadowRoot = typeof ShadowRoot !== "undefined" && parent.getRootNode() instanceof ShadowRoot;
          }
          var _iterator2 = _createForOfIteratorHelper(siblings), _step2;
          try {
            for (_iterator2.s(); !(_step2 = _iterator2.n()).done; ) {
              var child = _step2.value;
              adjacentElements.add(child);
            }
          } catch (err) {
            _iterator2.e(err);
          } finally {
            _iterator2.f();
          }
          current = parent;
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    containerAncestors.forEach(function(el) {
      adjacentElements["delete"](el);
    });
    state.adjacentElements = adjacentElements;
  };
  var removeListeners = function removeListeners2() {
    if (!state.active) {
      return;
    }
    doc.removeEventListener("focusin", checkFocusIn, true);
    doc.removeEventListener("mousedown", checkPointerDown, true);
    doc.removeEventListener("touchstart", checkPointerDown, true);
    doc.removeEventListener("click", checkClick, true);
    doc.removeEventListener("keydown", checkTabKey, true);
    doc.removeEventListener("keydown", checkEscapeKey);
    return trap;
  };
  var checkDomRemoval = function checkDomRemoval2(mutations) {
    var isFocusedNodeRemoved = mutations.some(function(mutation) {
      var removedNodes = Array.from(mutation.removedNodes);
      return removedNodes.some(function(node) {
        return node === state.mostRecentlyFocusedNode;
      });
    });
    if (isFocusedNodeRemoved) {
      _tryFocus(getInitialFocusNode());
    }
  };
  var mutationObserver = typeof window !== "undefined" && "MutationObserver" in window ? new MutationObserver(checkDomRemoval) : void 0;
  var updateObservedNodes = function updateObservedNodes2() {
    if (!mutationObserver) {
      return;
    }
    mutationObserver.disconnect();
    if (state.active && !state.paused) {
      state.containers.map(function(container) {
        mutationObserver.observe(container, {
          subtree: true,
          childList: true
        });
      });
    }
  };
  trap = {
    get active() {
      return state.active;
    },
    get paused() {
      return state.paused;
    },
    activate: function activate(activateOptions) {
      if (state.active) {
        return this;
      }
      var onActivate = getOption(activateOptions, "onActivate");
      var onPostActivate = getOption(activateOptions, "onPostActivate");
      var checkCanFocusTrap = getOption(activateOptions, "checkCanFocusTrap");
      var preexistingTrap = activeFocusTraps.getActiveTrap(trapStack);
      var revertState = false;
      if (preexistingTrap && !preexistingTrap.paused) {
        var _preexistingTrap$_set;
        (_preexistingTrap$_set = preexistingTrap._setSubtreeIsolation) === null || _preexistingTrap$_set === void 0 || _preexistingTrap$_set.call(preexistingTrap, false);
        revertState = true;
      }
      try {
        if (!checkCanFocusTrap) {
          updateTabbableNodes();
        }
        state.active = true;
        state.paused = false;
        state.nodeFocusedBeforeActivation = _getActiveElement(doc);
        onActivate === null || onActivate === void 0 || onActivate();
        var finishActivation = /* @__PURE__ */ (function() {
          var _ref6 = _asyncToGenerator(/* @__PURE__ */ _regenerator().m(function _callee() {
            return _regenerator().w(function(_context) {
              while (1) switch (_context.n) {
                case 0:
                  if (checkCanFocusTrap) {
                    updateTabbableNodes();
                  }
                  _context.n = 1;
                  return addListeners();
                case 1:
                  trap._setSubtreeIsolation(true);
                  updateObservedNodes();
                  onPostActivate === null || onPostActivate === void 0 || onPostActivate();
                case 2:
                  return _context.a(2);
              }
            }, _callee);
          }));
          return function finishActivation2() {
            return _ref6.apply(this, arguments);
          };
        })();
        if (checkCanFocusTrap) {
          checkCanFocusTrap(state.containers.concat()).then(finishActivation, finishActivation);
          return this;
        }
        finishActivation();
      } catch (error) {
        if (preexistingTrap === activeFocusTraps.getActiveTrap(trapStack) && revertState) {
          var _preexistingTrap$_set2;
          (_preexistingTrap$_set2 = preexistingTrap._setSubtreeIsolation) === null || _preexistingTrap$_set2 === void 0 || _preexistingTrap$_set2.call(preexistingTrap, true);
        }
        throw error;
      }
      return this;
    },
    deactivate: function deactivate(deactivateOptions) {
      if (!state.active) {
        return this;
      }
      var options = _objectSpread2({
        onDeactivate: config.onDeactivate,
        onPostDeactivate: config.onPostDeactivate,
        checkCanReturnFocus: config.checkCanReturnFocus
      }, deactivateOptions);
      clearTimeout(state.delayInitialFocusTimer);
      state.delayInitialFocusTimer = void 0;
      if (!state.paused) {
        trap._setSubtreeIsolation(false);
      }
      state.alreadySilent.clear();
      removeListeners();
      state.active = false;
      state.paused = false;
      updateObservedNodes();
      activeFocusTraps.deactivateTrap(trapStack, trap);
      var onDeactivate = getOption(options, "onDeactivate");
      var onPostDeactivate = getOption(options, "onPostDeactivate");
      var checkCanReturnFocus = getOption(options, "checkCanReturnFocus");
      var returnFocus = getOption(options, "returnFocus", "returnFocusOnDeactivate");
      onDeactivate === null || onDeactivate === void 0 || onDeactivate();
      var finishDeactivation = function finishDeactivation2() {
        delay(function() {
          if (returnFocus) {
            _tryFocus(getReturnFocusNode(state.nodeFocusedBeforeActivation));
          }
          onPostDeactivate === null || onPostDeactivate === void 0 || onPostDeactivate();
        });
      };
      if (returnFocus && checkCanReturnFocus) {
        checkCanReturnFocus(getReturnFocusNode(state.nodeFocusedBeforeActivation)).then(finishDeactivation, finishDeactivation);
        return this;
      }
      finishDeactivation();
      return this;
    },
    pause: function pause(pauseOptions) {
      if (!state.active) {
        return this;
      }
      state.manuallyPaused = true;
      return this._setPausedState(true, pauseOptions);
    },
    unpause: function unpause(unpauseOptions) {
      if (!state.active) {
        return this;
      }
      state.manuallyPaused = false;
      if (trapStack[trapStack.length - 1] !== this) {
        return this;
      }
      return this._setPausedState(false, unpauseOptions);
    },
    updateContainerElements: function updateContainerElements(containerElements) {
      var elementsAsArray = [].concat(containerElements).filter(Boolean);
      state.containers = elementsAsArray.map(function(element) {
        return typeof element === "string" ? doc.querySelector(element) : element;
      });
      if (config.isolateSubtrees) {
        collectAdjacentElements(state.containers);
      }
      if (state.active) {
        updateTabbableNodes();
        if (!state.paused) {
          trap._setSubtreeIsolation(true);
        }
      }
      updateObservedNodes();
      return this;
    }
  };
  Object.defineProperties(trap, {
    _isManuallyPaused: {
      value: function value() {
        return state.manuallyPaused;
      }
    },
    _setPausedState: {
      value: function value(paused, options) {
        if (state.paused === paused) {
          return this;
        }
        state.paused = paused;
        if (paused) {
          var onPause = getOption(options, "onPause");
          var onPostPause = getOption(options, "onPostPause");
          onPause === null || onPause === void 0 || onPause();
          removeListeners();
          trap._setSubtreeIsolation(false);
          updateObservedNodes();
          onPostPause === null || onPostPause === void 0 || onPostPause();
        } else {
          var onUnpause = getOption(options, "onUnpause");
          var onPostUnpause = getOption(options, "onPostUnpause");
          onUnpause === null || onUnpause === void 0 || onUnpause();
          var finishUnpause = /* @__PURE__ */ (function() {
            var _ref7 = _asyncToGenerator(/* @__PURE__ */ _regenerator().m(function _callee2() {
              return _regenerator().w(function(_context2) {
                while (1) switch (_context2.n) {
                  case 0:
                    updateTabbableNodes();
                    _context2.n = 1;
                    return addListeners();
                  case 1:
                    trap._setSubtreeIsolation(true);
                    updateObservedNodes();
                    onPostUnpause === null || onPostUnpause === void 0 || onPostUnpause();
                  case 2:
                    return _context2.a(2);
                }
              }, _callee2);
            }));
            return function finishUnpause2() {
              return _ref7.apply(this, arguments);
            };
          })();
          finishUnpause();
        }
        return this;
      }
    },
    _setSubtreeIsolation: {
      value: function value(isEnabled) {
        if (config.isolateSubtrees) {
          state.adjacentElements.forEach(function(el) {
            var _el$getAttribute;
            if (isEnabled) {
              switch (config.isolateSubtrees) {
                case "aria-hidden":
                  if (el.ariaHidden === "true" || ((_el$getAttribute = el.getAttribute("aria-hidden")) === null || _el$getAttribute === void 0 ? void 0 : _el$getAttribute.toLowerCase()) === "true") {
                    state.alreadySilent.add(el);
                  }
                  el.setAttribute("aria-hidden", "true");
                  break;
                default:
                  if (el.inert || el.hasAttribute("inert")) {
                    state.alreadySilent.add(el);
                  }
                  el.setAttribute("inert", true);
                  break;
              }
            } else {
              if (state.alreadySilent.has(el)) ;
              else {
                switch (config.isolateSubtrees) {
                  case "aria-hidden":
                    el.removeAttribute("aria-hidden");
                    break;
                  default:
                    el.removeAttribute("inert");
                    break;
                }
              }
            }
          });
        }
      }
    }
  });
  trap.updateContainerElements(elements);
  return trap;
};
function getTrapStack() {
  window._nc_focus_trap ??= [];
  return window._nc_focus_trap;
}
function createTrapStackController() {
  let pausedStack = [];
  return {
    /**
     * Pause the current focus-trap stack
     */
    pause() {
      pausedStack = [...getTrapStack()];
      for (const trap of pausedStack) {
        trap.pause();
      }
    },
    /**
     * Unpause the paused focus trap stack
     * If the actual stack is different from the paused one, ignore unpause.
     */
    unpause() {
      if (pausedStack.length === getTrapStack().length) {
        for (const trap of pausedStack) {
          trap.unpause();
        }
      }
      pausedStack = [];
    }
  };
}
function useTrapStackControl(shouldPause, options = {}) {
  const trapStackController = createTrapStackController();
  watch(shouldPause, () => {
    if (toValue(options.disabled)) {
      return;
    }
    if (toValue(shouldPause)) {
      trapStackController.pause();
    } else {
      trapStackController.unpause();
    }
  });
  onUnmounted(() => {
    trapStackController.unpause();
  });
}
const sides = ["top", "right", "bottom", "left"];
const alignments = ["start", "end"];
const placements = /* @__PURE__ */ sides.reduce((acc, side) => acc.concat(side, side + "-" + alignments[0], side + "-" + alignments[1]), []);
const min$1 = Math.min;
const max$1 = Math.max;
const oppositeSideMap = {
  left: "right",
  right: "left",
  bottom: "top",
  top: "bottom"
};
const oppositeAlignmentMap = {
  start: "end",
  end: "start"
};
function clamp(start, value, end) {
  return max$1(start, min$1(value, end));
}
function evaluate(value, param) {
  return typeof value === "function" ? value(param) : value;
}
function getSide(placement) {
  return placement.split("-")[0];
}
function getAlignment(placement) {
  return placement.split("-")[1];
}
function getOppositeAxis(axis) {
  return axis === "x" ? "y" : "x";
}
function getAxisLength(axis) {
  return axis === "y" ? "height" : "width";
}
const yAxisSides = /* @__PURE__ */ new Set(["top", "bottom"]);
function getSideAxis(placement) {
  return yAxisSides.has(getSide(placement)) ? "y" : "x";
}
function getAlignmentAxis(placement) {
  return getOppositeAxis(getSideAxis(placement));
}
function getAlignmentSides(placement, rects, rtl) {
  if (rtl === void 0) {
    rtl = false;
  }
  const alignment = getAlignment(placement);
  const alignmentAxis = getAlignmentAxis(placement);
  const length = getAxisLength(alignmentAxis);
  let mainAlignmentSide = alignmentAxis === "x" ? alignment === (rtl ? "end" : "start") ? "right" : "left" : alignment === "start" ? "bottom" : "top";
  if (rects.reference[length] > rects.floating[length]) {
    mainAlignmentSide = getOppositePlacement(mainAlignmentSide);
  }
  return [mainAlignmentSide, getOppositePlacement(mainAlignmentSide)];
}
function getExpandedPlacements(placement) {
  const oppositePlacement = getOppositePlacement(placement);
  return [getOppositeAlignmentPlacement(placement), oppositePlacement, getOppositeAlignmentPlacement(oppositePlacement)];
}
function getOppositeAlignmentPlacement(placement) {
  return placement.replace(/start|end/g, (alignment) => oppositeAlignmentMap[alignment]);
}
const lrPlacement = ["left", "right"];
const rlPlacement = ["right", "left"];
const tbPlacement = ["top", "bottom"];
const btPlacement = ["bottom", "top"];
function getSideList(side, isStart, rtl) {
  switch (side) {
    case "top":
    case "bottom":
      if (rtl) return isStart ? rlPlacement : lrPlacement;
      return isStart ? lrPlacement : rlPlacement;
    case "left":
    case "right":
      return isStart ? tbPlacement : btPlacement;
    default:
      return [];
  }
}
function getOppositeAxisPlacements(placement, flipAlignment, direction, rtl) {
  const alignment = getAlignment(placement);
  let list = getSideList(getSide(placement), direction === "start", rtl);
  if (alignment) {
    list = list.map((side) => side + "-" + alignment);
    if (flipAlignment) {
      list = list.concat(list.map(getOppositeAlignmentPlacement));
    }
  }
  return list;
}
function getOppositePlacement(placement) {
  return placement.replace(/left|right|bottom|top/g, (side) => oppositeSideMap[side]);
}
function expandPaddingObject(padding) {
  return {
    top: 0,
    right: 0,
    bottom: 0,
    left: 0,
    ...padding
  };
}
function getPaddingObject(padding) {
  return typeof padding !== "number" ? expandPaddingObject(padding) : {
    top: padding,
    right: padding,
    bottom: padding,
    left: padding
  };
}
function rectToClientRect(rect) {
  const {
    x,
    y: y2,
    width,
    height
  } = rect;
  return {
    width,
    height,
    top: y2,
    left: x,
    right: x + width,
    bottom: y2 + height,
    x,
    y: y2
  };
}
function computeCoordsFromPlacement(_ref, placement, rtl) {
  let {
    reference,
    floating
  } = _ref;
  const sideAxis = getSideAxis(placement);
  const alignmentAxis = getAlignmentAxis(placement);
  const alignLength = getAxisLength(alignmentAxis);
  const side = getSide(placement);
  const isVertical = sideAxis === "y";
  const commonX = reference.x + reference.width / 2 - floating.width / 2;
  const commonY = reference.y + reference.height / 2 - floating.height / 2;
  const commonAlign = reference[alignLength] / 2 - floating[alignLength] / 2;
  let coords;
  switch (side) {
    case "top":
      coords = {
        x: commonX,
        y: reference.y - floating.height
      };
      break;
    case "bottom":
      coords = {
        x: commonX,
        y: reference.y + reference.height
      };
      break;
    case "right":
      coords = {
        x: reference.x + reference.width,
        y: commonY
      };
      break;
    case "left":
      coords = {
        x: reference.x - floating.width,
        y: commonY
      };
      break;
    default:
      coords = {
        x: reference.x,
        y: reference.y
      };
  }
  switch (getAlignment(placement)) {
    case "start":
      coords[alignmentAxis] -= commonAlign * (rtl && isVertical ? -1 : 1);
      break;
    case "end":
      coords[alignmentAxis] += commonAlign * (rtl && isVertical ? -1 : 1);
      break;
  }
  return coords;
}
async function detectOverflow(state, options) {
  var _await$platform$isEle;
  if (options === void 0) {
    options = {};
  }
  const {
    x,
    y: y2,
    platform: platform2,
    rects,
    elements,
    strategy
  } = state;
  const {
    boundary = "clippingAncestors",
    rootBoundary = "viewport",
    elementContext = "floating",
    altBoundary = false,
    padding = 0
  } = evaluate(options, state);
  const paddingObject = getPaddingObject(padding);
  const altContext = elementContext === "floating" ? "reference" : "floating";
  const element = elements[altBoundary ? altContext : elementContext];
  const clippingClientRect = rectToClientRect(await platform2.getClippingRect({
    element: ((_await$platform$isEle = await (platform2.isElement == null ? void 0 : platform2.isElement(element))) != null ? _await$platform$isEle : true) ? element : element.contextElement || await (platform2.getDocumentElement == null ? void 0 : platform2.getDocumentElement(elements.floating)),
    boundary,
    rootBoundary,
    strategy
  }));
  const rect = elementContext === "floating" ? {
    x,
    y: y2,
    width: rects.floating.width,
    height: rects.floating.height
  } : rects.reference;
  const offsetParent = await (platform2.getOffsetParent == null ? void 0 : platform2.getOffsetParent(elements.floating));
  const offsetScale = await (platform2.isElement == null ? void 0 : platform2.isElement(offsetParent)) ? await (platform2.getScale == null ? void 0 : platform2.getScale(offsetParent)) || {
    x: 1,
    y: 1
  } : {
    x: 1,
    y: 1
  };
  const elementClientRect = rectToClientRect(platform2.convertOffsetParentRelativeRectToViewportRelativeRect ? await platform2.convertOffsetParentRelativeRectToViewportRelativeRect({
    elements,
    rect,
    offsetParent,
    strategy
  }) : rect);
  return {
    top: (clippingClientRect.top - elementClientRect.top + paddingObject.top) / offsetScale.y,
    bottom: (elementClientRect.bottom - clippingClientRect.bottom + paddingObject.bottom) / offsetScale.y,
    left: (clippingClientRect.left - elementClientRect.left + paddingObject.left) / offsetScale.x,
    right: (elementClientRect.right - clippingClientRect.right + paddingObject.right) / offsetScale.x
  };
}
const computePosition$1 = async (reference, floating, config) => {
  const {
    placement = "bottom",
    strategy = "absolute",
    middleware = [],
    platform: platform2
  } = config;
  const validMiddleware = middleware.filter(Boolean);
  const rtl = await (platform2.isRTL == null ? void 0 : platform2.isRTL(floating));
  let rects = await platform2.getElementRects({
    reference,
    floating,
    strategy
  });
  let {
    x,
    y: y2
  } = computeCoordsFromPlacement(rects, placement, rtl);
  let statefulPlacement = placement;
  let middlewareData = {};
  let resetCount = 0;
  for (let i = 0; i < validMiddleware.length; i++) {
    var _platform$detectOverf;
    const {
      name,
      fn
    } = validMiddleware[i];
    const {
      x: nextX,
      y: nextY,
      data,
      reset
    } = await fn({
      x,
      y: y2,
      initialPlacement: placement,
      placement: statefulPlacement,
      strategy,
      middlewareData,
      rects,
      platform: {
        ...platform2,
        detectOverflow: (_platform$detectOverf = platform2.detectOverflow) != null ? _platform$detectOverf : detectOverflow
      },
      elements: {
        reference,
        floating
      }
    });
    x = nextX != null ? nextX : x;
    y2 = nextY != null ? nextY : y2;
    middlewareData = {
      ...middlewareData,
      [name]: {
        ...middlewareData[name],
        ...data
      }
    };
    if (reset && resetCount <= 50) {
      resetCount++;
      if (typeof reset === "object") {
        if (reset.placement) {
          statefulPlacement = reset.placement;
        }
        if (reset.rects) {
          rects = reset.rects === true ? await platform2.getElementRects({
            reference,
            floating,
            strategy
          }) : reset.rects;
        }
        ({
          x,
          y: y2
        } = computeCoordsFromPlacement(rects, statefulPlacement, rtl));
      }
      i = -1;
    }
  }
  return {
    x,
    y: y2,
    placement: statefulPlacement,
    strategy,
    middlewareData
  };
};
const arrow = (options) => ({
  name: "arrow",
  options,
  async fn(state) {
    const {
      x,
      y: y2,
      placement,
      rects,
      platform: platform2,
      elements,
      middlewareData
    } = state;
    const {
      element,
      padding = 0
    } = evaluate(options, state) || {};
    if (element == null) {
      return {};
    }
    const paddingObject = getPaddingObject(padding);
    const coords = {
      x,
      y: y2
    };
    const axis = getAlignmentAxis(placement);
    const length = getAxisLength(axis);
    const arrowDimensions = await platform2.getDimensions(element);
    const isYAxis = axis === "y";
    const minProp = isYAxis ? "top" : "left";
    const maxProp = isYAxis ? "bottom" : "right";
    const clientProp = isYAxis ? "clientHeight" : "clientWidth";
    const endDiff = rects.reference[length] + rects.reference[axis] - coords[axis] - rects.floating[length];
    const startDiff = coords[axis] - rects.reference[axis];
    const arrowOffsetParent = await (platform2.getOffsetParent == null ? void 0 : platform2.getOffsetParent(element));
    let clientSize = arrowOffsetParent ? arrowOffsetParent[clientProp] : 0;
    if (!clientSize || !await (platform2.isElement == null ? void 0 : platform2.isElement(arrowOffsetParent))) {
      clientSize = elements.floating[clientProp] || rects.floating[length];
    }
    const centerToReference = endDiff / 2 - startDiff / 2;
    const largestPossiblePadding = clientSize / 2 - arrowDimensions[length] / 2 - 1;
    const minPadding = min$1(paddingObject[minProp], largestPossiblePadding);
    const maxPadding = min$1(paddingObject[maxProp], largestPossiblePadding);
    const min$1$1 = minPadding;
    const max2 = clientSize - arrowDimensions[length] - maxPadding;
    const center = clientSize / 2 - arrowDimensions[length] / 2 + centerToReference;
    const offset2 = clamp(min$1$1, center, max2);
    const shouldAddOffset = !middlewareData.arrow && getAlignment(placement) != null && center !== offset2 && rects.reference[length] / 2 - (center < min$1$1 ? minPadding : maxPadding) - arrowDimensions[length] / 2 < 0;
    const alignmentOffset = shouldAddOffset ? center < min$1$1 ? center - min$1$1 : center - max2 : 0;
    return {
      [axis]: coords[axis] + alignmentOffset,
      data: {
        [axis]: offset2,
        centerOffset: center - offset2 - alignmentOffset,
        ...shouldAddOffset && {
          alignmentOffset
        }
      },
      reset: shouldAddOffset
    };
  }
});
function getPlacementList(alignment, autoAlignment, allowedPlacements) {
  const allowedPlacementsSortedByAlignment = alignment ? [...allowedPlacements.filter((placement) => getAlignment(placement) === alignment), ...allowedPlacements.filter((placement) => getAlignment(placement) !== alignment)] : allowedPlacements.filter((placement) => getSide(placement) === placement);
  return allowedPlacementsSortedByAlignment.filter((placement) => {
    if (alignment) {
      return getAlignment(placement) === alignment || (autoAlignment ? getOppositeAlignmentPlacement(placement) !== placement : false);
    }
    return true;
  });
}
const autoPlacement = function(options) {
  if (options === void 0) {
    options = {};
  }
  return {
    name: "autoPlacement",
    options,
    async fn(state) {
      var _middlewareData$autoP, _middlewareData$autoP2, _placementsThatFitOnE;
      const {
        rects,
        middlewareData,
        placement,
        platform: platform2,
        elements
      } = state;
      const {
        crossAxis = false,
        alignment,
        allowedPlacements = placements,
        autoAlignment = true,
        ...detectOverflowOptions
      } = evaluate(options, state);
      const placements$1 = alignment !== void 0 || allowedPlacements === placements ? getPlacementList(alignment || null, autoAlignment, allowedPlacements) : allowedPlacements;
      const overflow = await platform2.detectOverflow(state, detectOverflowOptions);
      const currentIndex = ((_middlewareData$autoP = middlewareData.autoPlacement) == null ? void 0 : _middlewareData$autoP.index) || 0;
      const currentPlacement = placements$1[currentIndex];
      if (currentPlacement == null) {
        return {};
      }
      const alignmentSides = getAlignmentSides(currentPlacement, rects, await (platform2.isRTL == null ? void 0 : platform2.isRTL(elements.floating)));
      if (placement !== currentPlacement) {
        return {
          reset: {
            placement: placements$1[0]
          }
        };
      }
      const currentOverflows = [overflow[getSide(currentPlacement)], overflow[alignmentSides[0]], overflow[alignmentSides[1]]];
      const allOverflows = [...((_middlewareData$autoP2 = middlewareData.autoPlacement) == null ? void 0 : _middlewareData$autoP2.overflows) || [], {
        placement: currentPlacement,
        overflows: currentOverflows
      }];
      const nextPlacement = placements$1[currentIndex + 1];
      if (nextPlacement) {
        return {
          data: {
            index: currentIndex + 1,
            overflows: allOverflows
          },
          reset: {
            placement: nextPlacement
          }
        };
      }
      const placementsSortedByMostSpace = allOverflows.map((d2) => {
        const alignment2 = getAlignment(d2.placement);
        return [d2.placement, alignment2 && crossAxis ? (
          // Check along the mainAxis and main crossAxis side.
          d2.overflows.slice(0, 2).reduce((acc, v) => acc + v, 0)
        ) : (
          // Check only the mainAxis.
          d2.overflows[0]
        ), d2.overflows];
      }).sort((a, b) => a[1] - b[1]);
      const placementsThatFitOnEachSide = placementsSortedByMostSpace.filter((d2) => d2[2].slice(
        0,
        // Aligned placements should not check their opposite crossAxis
        // side.
        getAlignment(d2[0]) ? 2 : 3
      ).every((v) => v <= 0));
      const resetPlacement = ((_placementsThatFitOnE = placementsThatFitOnEachSide[0]) == null ? void 0 : _placementsThatFitOnE[0]) || placementsSortedByMostSpace[0][0];
      if (resetPlacement !== placement) {
        return {
          data: {
            index: currentIndex + 1,
            overflows: allOverflows
          },
          reset: {
            placement: resetPlacement
          }
        };
      }
      return {};
    }
  };
};
const flip = function(options) {
  if (options === void 0) {
    options = {};
  }
  return {
    name: "flip",
    options,
    async fn(state) {
      var _middlewareData$arrow, _middlewareData$flip;
      const {
        placement,
        middlewareData,
        rects,
        initialPlacement,
        platform: platform2,
        elements
      } = state;
      const {
        mainAxis: checkMainAxis = true,
        crossAxis: checkCrossAxis = true,
        fallbackPlacements: specifiedFallbackPlacements,
        fallbackStrategy = "bestFit",
        fallbackAxisSideDirection = "none",
        flipAlignment = true,
        ...detectOverflowOptions
      } = evaluate(options, state);
      if ((_middlewareData$arrow = middlewareData.arrow) != null && _middlewareData$arrow.alignmentOffset) {
        return {};
      }
      const side = getSide(placement);
      const initialSideAxis = getSideAxis(initialPlacement);
      const isBasePlacement = getSide(initialPlacement) === initialPlacement;
      const rtl = await (platform2.isRTL == null ? void 0 : platform2.isRTL(elements.floating));
      const fallbackPlacements = specifiedFallbackPlacements || (isBasePlacement || !flipAlignment ? [getOppositePlacement(initialPlacement)] : getExpandedPlacements(initialPlacement));
      const hasFallbackAxisSideDirection = fallbackAxisSideDirection !== "none";
      if (!specifiedFallbackPlacements && hasFallbackAxisSideDirection) {
        fallbackPlacements.push(...getOppositeAxisPlacements(initialPlacement, flipAlignment, fallbackAxisSideDirection, rtl));
      }
      const placements2 = [initialPlacement, ...fallbackPlacements];
      const overflow = await platform2.detectOverflow(state, detectOverflowOptions);
      const overflows = [];
      let overflowsData = ((_middlewareData$flip = middlewareData.flip) == null ? void 0 : _middlewareData$flip.overflows) || [];
      if (checkMainAxis) {
        overflows.push(overflow[side]);
      }
      if (checkCrossAxis) {
        const sides2 = getAlignmentSides(placement, rects, rtl);
        overflows.push(overflow[sides2[0]], overflow[sides2[1]]);
      }
      overflowsData = [...overflowsData, {
        placement,
        overflows
      }];
      if (!overflows.every((side2) => side2 <= 0)) {
        var _middlewareData$flip2, _overflowsData$filter;
        const nextIndex = (((_middlewareData$flip2 = middlewareData.flip) == null ? void 0 : _middlewareData$flip2.index) || 0) + 1;
        const nextPlacement = placements2[nextIndex];
        if (nextPlacement) {
          const ignoreCrossAxisOverflow = checkCrossAxis === "alignment" ? initialSideAxis !== getSideAxis(nextPlacement) : false;
          if (!ignoreCrossAxisOverflow || // We leave the current main axis only if every placement on that axis
          // overflows the main axis.
          overflowsData.every((d2) => getSideAxis(d2.placement) === initialSideAxis ? d2.overflows[0] > 0 : true)) {
            return {
              data: {
                index: nextIndex,
                overflows: overflowsData
              },
              reset: {
                placement: nextPlacement
              }
            };
          }
        }
        let resetPlacement = (_overflowsData$filter = overflowsData.filter((d2) => d2.overflows[0] <= 0).sort((a, b) => a.overflows[1] - b.overflows[1])[0]) == null ? void 0 : _overflowsData$filter.placement;
        if (!resetPlacement) {
          switch (fallbackStrategy) {
            case "bestFit": {
              var _overflowsData$filter2;
              const placement2 = (_overflowsData$filter2 = overflowsData.filter((d2) => {
                if (hasFallbackAxisSideDirection) {
                  const currentSideAxis = getSideAxis(d2.placement);
                  return currentSideAxis === initialSideAxis || // Create a bias to the `y` side axis due to horizontal
                  // reading directions favoring greater width.
                  currentSideAxis === "y";
                }
                return true;
              }).map((d2) => [d2.placement, d2.overflows.filter((overflow2) => overflow2 > 0).reduce((acc, overflow2) => acc + overflow2, 0)]).sort((a, b) => a[1] - b[1])[0]) == null ? void 0 : _overflowsData$filter2[0];
              if (placement2) {
                resetPlacement = placement2;
              }
              break;
            }
            case "initialPlacement":
              resetPlacement = initialPlacement;
              break;
          }
        }
        if (placement !== resetPlacement) {
          return {
            reset: {
              placement: resetPlacement
            }
          };
        }
      }
      return {};
    }
  };
};
const originSides = /* @__PURE__ */ new Set(["left", "top"]);
async function convertValueToCoords(state, options) {
  const {
    placement,
    platform: platform2,
    elements
  } = state;
  const rtl = await (platform2.isRTL == null ? void 0 : platform2.isRTL(elements.floating));
  const side = getSide(placement);
  const alignment = getAlignment(placement);
  const isVertical = getSideAxis(placement) === "y";
  const mainAxisMulti = originSides.has(side) ? -1 : 1;
  const crossAxisMulti = rtl && isVertical ? -1 : 1;
  const rawValue = evaluate(options, state);
  let {
    mainAxis,
    crossAxis,
    alignmentAxis
  } = typeof rawValue === "number" ? {
    mainAxis: rawValue,
    crossAxis: 0,
    alignmentAxis: null
  } : {
    mainAxis: rawValue.mainAxis || 0,
    crossAxis: rawValue.crossAxis || 0,
    alignmentAxis: rawValue.alignmentAxis
  };
  if (alignment && typeof alignmentAxis === "number") {
    crossAxis = alignment === "end" ? alignmentAxis * -1 : alignmentAxis;
  }
  return isVertical ? {
    x: crossAxis * crossAxisMulti,
    y: mainAxis * mainAxisMulti
  } : {
    x: mainAxis * mainAxisMulti,
    y: crossAxis * crossAxisMulti
  };
}
const offset = function(options) {
  if (options === void 0) {
    options = 0;
  }
  return {
    name: "offset",
    options,
    async fn(state) {
      var _middlewareData$offse, _middlewareData$arrow;
      const {
        x,
        y: y2,
        placement,
        middlewareData
      } = state;
      const diffCoords = await convertValueToCoords(state, options);
      if (placement === ((_middlewareData$offse = middlewareData.offset) == null ? void 0 : _middlewareData$offse.placement) && (_middlewareData$arrow = middlewareData.arrow) != null && _middlewareData$arrow.alignmentOffset) {
        return {};
      }
      return {
        x: x + diffCoords.x,
        y: y2 + diffCoords.y,
        data: {
          ...diffCoords,
          placement
        }
      };
    }
  };
};
const shift = function(options) {
  if (options === void 0) {
    options = {};
  }
  return {
    name: "shift",
    options,
    async fn(state) {
      const {
        x,
        y: y2,
        placement,
        platform: platform2
      } = state;
      const {
        mainAxis: checkMainAxis = true,
        crossAxis: checkCrossAxis = false,
        limiter = {
          fn: (_ref) => {
            let {
              x: x2,
              y: y3
            } = _ref;
            return {
              x: x2,
              y: y3
            };
          }
        },
        ...detectOverflowOptions
      } = evaluate(options, state);
      const coords = {
        x,
        y: y2
      };
      const overflow = await platform2.detectOverflow(state, detectOverflowOptions);
      const crossAxis = getSideAxis(getSide(placement));
      const mainAxis = getOppositeAxis(crossAxis);
      let mainAxisCoord = coords[mainAxis];
      let crossAxisCoord = coords[crossAxis];
      if (checkMainAxis) {
        const minSide = mainAxis === "y" ? "top" : "left";
        const maxSide = mainAxis === "y" ? "bottom" : "right";
        const min2 = mainAxisCoord + overflow[minSide];
        const max2 = mainAxisCoord - overflow[maxSide];
        mainAxisCoord = clamp(min2, mainAxisCoord, max2);
      }
      if (checkCrossAxis) {
        const minSide = crossAxis === "y" ? "top" : "left";
        const maxSide = crossAxis === "y" ? "bottom" : "right";
        const min2 = crossAxisCoord + overflow[minSide];
        const max2 = crossAxisCoord - overflow[maxSide];
        crossAxisCoord = clamp(min2, crossAxisCoord, max2);
      }
      const limitedCoords = limiter.fn({
        ...state,
        [mainAxis]: mainAxisCoord,
        [crossAxis]: crossAxisCoord
      });
      return {
        ...limitedCoords,
        data: {
          x: limitedCoords.x - x,
          y: limitedCoords.y - y2,
          enabled: {
            [mainAxis]: checkMainAxis,
            [crossAxis]: checkCrossAxis
          }
        }
      };
    }
  };
};
const size = function(options) {
  if (options === void 0) {
    options = {};
  }
  return {
    name: "size",
    options,
    async fn(state) {
      var _state$middlewareData, _state$middlewareData2;
      const {
        placement,
        rects,
        platform: platform2,
        elements
      } = state;
      const {
        apply = () => {
        },
        ...detectOverflowOptions
      } = evaluate(options, state);
      const overflow = await platform2.detectOverflow(state, detectOverflowOptions);
      const side = getSide(placement);
      const alignment = getAlignment(placement);
      const isYAxis = getSideAxis(placement) === "y";
      const {
        width,
        height
      } = rects.floating;
      let heightSide;
      let widthSide;
      if (side === "top" || side === "bottom") {
        heightSide = side;
        widthSide = alignment === (await (platform2.isRTL == null ? void 0 : platform2.isRTL(elements.floating)) ? "start" : "end") ? "left" : "right";
      } else {
        widthSide = side;
        heightSide = alignment === "end" ? "top" : "bottom";
      }
      const maximumClippingHeight = height - overflow.top - overflow.bottom;
      const maximumClippingWidth = width - overflow.left - overflow.right;
      const overflowAvailableHeight = min$1(height - overflow[heightSide], maximumClippingHeight);
      const overflowAvailableWidth = min$1(width - overflow[widthSide], maximumClippingWidth);
      const noShift = !state.middlewareData.shift;
      let availableHeight = overflowAvailableHeight;
      let availableWidth = overflowAvailableWidth;
      if ((_state$middlewareData = state.middlewareData.shift) != null && _state$middlewareData.enabled.x) {
        availableWidth = maximumClippingWidth;
      }
      if ((_state$middlewareData2 = state.middlewareData.shift) != null && _state$middlewareData2.enabled.y) {
        availableHeight = maximumClippingHeight;
      }
      if (noShift && !alignment) {
        const xMin = max$1(overflow.left, 0);
        const xMax = max$1(overflow.right, 0);
        const yMin = max$1(overflow.top, 0);
        const yMax = max$1(overflow.bottom, 0);
        if (isYAxis) {
          availableWidth = width - 2 * (xMin !== 0 || xMax !== 0 ? xMin + xMax : max$1(overflow.left, overflow.right));
        } else {
          availableHeight = height - 2 * (yMin !== 0 || yMax !== 0 ? yMin + yMax : max$1(overflow.top, overflow.bottom));
        }
      }
      await apply({
        ...state,
        availableWidth,
        availableHeight
      });
      const nextDimensions = await platform2.getDimensions(elements.floating);
      if (width !== nextDimensions.width || height !== nextDimensions.height) {
        return {
          reset: {
            rects: true
          }
        };
      }
      return {};
    }
  };
};
function getWindow(node) {
  var _node$ownerDocument;
  return ((_node$ownerDocument = node.ownerDocument) == null ? void 0 : _node$ownerDocument.defaultView) || window;
}
function getComputedStyle$1(element) {
  return getWindow(element).getComputedStyle(element);
}
const min = Math.min;
const max = Math.max;
const round = Math.round;
function getCssDimensions(element) {
  const css = getComputedStyle$1(element);
  let width = parseFloat(css.width);
  let height = parseFloat(css.height);
  const offsetWidth = element.offsetWidth;
  const offsetHeight = element.offsetHeight;
  const shouldFallback = round(width) !== offsetWidth || round(height) !== offsetHeight;
  if (shouldFallback) {
    width = offsetWidth;
    height = offsetHeight;
  }
  return {
    width,
    height,
    fallback: shouldFallback
  };
}
function getNodeName(node) {
  return isNode(node) ? (node.nodeName || "").toLowerCase() : "";
}
let uaString;
function getUAString() {
  if (uaString) {
    return uaString;
  }
  const uaData = navigator.userAgentData;
  if (uaData && Array.isArray(uaData.brands)) {
    uaString = uaData.brands.map((item) => item.brand + "/" + item.version).join(" ");
    return uaString;
  }
  return navigator.userAgent;
}
function isHTMLElement(value) {
  return value instanceof getWindow(value).HTMLElement;
}
function isElement(value) {
  return value instanceof getWindow(value).Element;
}
function isNode(value) {
  return value instanceof getWindow(value).Node;
}
function isShadowRoot(node) {
  if (typeof ShadowRoot === "undefined") {
    return false;
  }
  const OwnElement = getWindow(node).ShadowRoot;
  return node instanceof OwnElement || node instanceof ShadowRoot;
}
function isOverflowElement(element) {
  const {
    overflow,
    overflowX,
    overflowY,
    display
  } = getComputedStyle$1(element);
  return /auto|scroll|overlay|hidden|clip/.test(overflow + overflowY + overflowX) && !["inline", "contents"].includes(display);
}
function isTableElement(element) {
  return ["table", "td", "th"].includes(getNodeName(element));
}
function isContainingBlock(element) {
  const isFirefox = /firefox/i.test(getUAString());
  const css = getComputedStyle$1(element);
  const backdropFilter = css.backdropFilter || css.WebkitBackdropFilter;
  return css.transform !== "none" || css.perspective !== "none" || (backdropFilter ? backdropFilter !== "none" : false) || isFirefox && css.willChange === "filter" || isFirefox && (css.filter ? css.filter !== "none" : false) || ["transform", "perspective"].some((value) => css.willChange.includes(value)) || ["paint", "layout", "strict", "content"].some((value) => {
    const contain = css.contain;
    return contain != null ? contain.includes(value) : false;
  });
}
function isLayoutViewport() {
  return !/^((?!chrome|android).)*safari/i.test(getUAString());
}
function isLastTraversableNode(node) {
  return ["html", "body", "#document"].includes(getNodeName(node));
}
function unwrapElement(element) {
  return !isElement(element) ? element.contextElement : element;
}
const FALLBACK_SCALE = {
  x: 1,
  y: 1
};
function getScale(element) {
  const domElement = unwrapElement(element);
  if (!isHTMLElement(domElement)) {
    return FALLBACK_SCALE;
  }
  const rect = domElement.getBoundingClientRect();
  const {
    width,
    height,
    fallback
  } = getCssDimensions(domElement);
  let x = (fallback ? round(rect.width) : rect.width) / width;
  let y2 = (fallback ? round(rect.height) : rect.height) / height;
  if (!x || !Number.isFinite(x)) {
    x = 1;
  }
  if (!y2 || !Number.isFinite(y2)) {
    y2 = 1;
  }
  return {
    x,
    y: y2
  };
}
function getBoundingClientRect(element, includeScale, isFixedStrategy, offsetParent) {
  var _win$visualViewport, _win$visualViewport2;
  if (includeScale === void 0) {
    includeScale = false;
  }
  if (isFixedStrategy === void 0) {
    isFixedStrategy = false;
  }
  const clientRect = element.getBoundingClientRect();
  const domElement = unwrapElement(element);
  let scale = FALLBACK_SCALE;
  if (includeScale) {
    if (offsetParent) {
      if (isElement(offsetParent)) {
        scale = getScale(offsetParent);
      }
    } else {
      scale = getScale(element);
    }
  }
  const win = domElement ? getWindow(domElement) : window;
  const addVisualOffsets = !isLayoutViewport() && isFixedStrategy;
  let x = (clientRect.left + (addVisualOffsets ? ((_win$visualViewport = win.visualViewport) == null ? void 0 : _win$visualViewport.offsetLeft) || 0 : 0)) / scale.x;
  let y2 = (clientRect.top + (addVisualOffsets ? ((_win$visualViewport2 = win.visualViewport) == null ? void 0 : _win$visualViewport2.offsetTop) || 0 : 0)) / scale.y;
  let width = clientRect.width / scale.x;
  let height = clientRect.height / scale.y;
  if (domElement) {
    const win2 = getWindow(domElement);
    const offsetWin = offsetParent && isElement(offsetParent) ? getWindow(offsetParent) : offsetParent;
    let currentIFrame = win2.frameElement;
    while (currentIFrame && offsetParent && offsetWin !== win2) {
      const iframeScale = getScale(currentIFrame);
      const iframeRect = currentIFrame.getBoundingClientRect();
      const css = getComputedStyle(currentIFrame);
      iframeRect.x += (currentIFrame.clientLeft + parseFloat(css.paddingLeft)) * iframeScale.x;
      iframeRect.y += (currentIFrame.clientTop + parseFloat(css.paddingTop)) * iframeScale.y;
      x *= iframeScale.x;
      y2 *= iframeScale.y;
      width *= iframeScale.x;
      height *= iframeScale.y;
      x += iframeRect.x;
      y2 += iframeRect.y;
      currentIFrame = getWindow(currentIFrame).frameElement;
    }
  }
  return {
    width,
    height,
    top: y2,
    right: x + width,
    bottom: y2 + height,
    left: x,
    x,
    y: y2
  };
}
function getDocumentElement(node) {
  return ((isNode(node) ? node.ownerDocument : node.document) || window.document).documentElement;
}
function getNodeScroll(element) {
  if (isElement(element)) {
    return {
      scrollLeft: element.scrollLeft,
      scrollTop: element.scrollTop
    };
  }
  return {
    scrollLeft: element.pageXOffset,
    scrollTop: element.pageYOffset
  };
}
function convertOffsetParentRelativeRectToViewportRelativeRect(_ref) {
  let {
    rect,
    offsetParent,
    strategy
  } = _ref;
  const isOffsetParentAnElement = isHTMLElement(offsetParent);
  const documentElement = getDocumentElement(offsetParent);
  if (offsetParent === documentElement) {
    return rect;
  }
  let scroll = {
    scrollLeft: 0,
    scrollTop: 0
  };
  let scale = {
    x: 1,
    y: 1
  };
  const offsets = {
    x: 0,
    y: 0
  };
  if (isOffsetParentAnElement || !isOffsetParentAnElement && strategy !== "fixed") {
    if (getNodeName(offsetParent) !== "body" || isOverflowElement(documentElement)) {
      scroll = getNodeScroll(offsetParent);
    }
    if (isHTMLElement(offsetParent)) {
      const offsetRect = getBoundingClientRect(offsetParent);
      scale = getScale(offsetParent);
      offsets.x = offsetRect.x + offsetParent.clientLeft;
      offsets.y = offsetRect.y + offsetParent.clientTop;
    }
  }
  return {
    width: rect.width * scale.x,
    height: rect.height * scale.y,
    x: rect.x * scale.x - scroll.scrollLeft * scale.x + offsets.x,
    y: rect.y * scale.y - scroll.scrollTop * scale.y + offsets.y
  };
}
function getWindowScrollBarX(element) {
  return getBoundingClientRect(getDocumentElement(element)).left + getNodeScroll(element).scrollLeft;
}
function getDocumentRect(element) {
  const html = getDocumentElement(element);
  const scroll = getNodeScroll(element);
  const body = element.ownerDocument.body;
  const width = max(html.scrollWidth, html.clientWidth, body.scrollWidth, body.clientWidth);
  const height = max(html.scrollHeight, html.clientHeight, body.scrollHeight, body.clientHeight);
  let x = -scroll.scrollLeft + getWindowScrollBarX(element);
  const y2 = -scroll.scrollTop;
  if (getComputedStyle$1(body).direction === "rtl") {
    x += max(html.clientWidth, body.clientWidth) - width;
  }
  return {
    width,
    height,
    x,
    y: y2
  };
}
function getParentNode(node) {
  if (getNodeName(node) === "html") {
    return node;
  }
  const result = (
    // Step into the shadow DOM of the parent of a slotted node.
    node.assignedSlot || // DOM Element detected.
    node.parentNode || // ShadowRoot detected.
    isShadowRoot(node) && node.host || // Fallback.
    getDocumentElement(node)
  );
  return isShadowRoot(result) ? result.host : result;
}
function getNearestOverflowAncestor(node) {
  const parentNode = getParentNode(node);
  if (isLastTraversableNode(parentNode)) {
    return parentNode.ownerDocument.body;
  }
  if (isHTMLElement(parentNode) && isOverflowElement(parentNode)) {
    return parentNode;
  }
  return getNearestOverflowAncestor(parentNode);
}
function getOverflowAncestors(node, list) {
  var _node$ownerDocument;
  if (list === void 0) {
    list = [];
  }
  const scrollableAncestor = getNearestOverflowAncestor(node);
  const isBody = scrollableAncestor === ((_node$ownerDocument = node.ownerDocument) == null ? void 0 : _node$ownerDocument.body);
  const win = getWindow(scrollableAncestor);
  if (isBody) {
    return list.concat(win, win.visualViewport || [], isOverflowElement(scrollableAncestor) ? scrollableAncestor : []);
  }
  return list.concat(scrollableAncestor, getOverflowAncestors(scrollableAncestor));
}
function getViewportRect(element, strategy) {
  const win = getWindow(element);
  const html = getDocumentElement(element);
  const visualViewport = win.visualViewport;
  let width = html.clientWidth;
  let height = html.clientHeight;
  let x = 0;
  let y2 = 0;
  if (visualViewport) {
    width = visualViewport.width;
    height = visualViewport.height;
    const layoutViewport = isLayoutViewport();
    if (layoutViewport || !layoutViewport && strategy === "fixed") {
      x = visualViewport.offsetLeft;
      y2 = visualViewport.offsetTop;
    }
  }
  return {
    width,
    height,
    x,
    y: y2
  };
}
function getInnerBoundingClientRect(element, strategy) {
  const clientRect = getBoundingClientRect(element, true, strategy === "fixed");
  const top = clientRect.top + element.clientTop;
  const left = clientRect.left + element.clientLeft;
  const scale = isHTMLElement(element) ? getScale(element) : {
    x: 1,
    y: 1
  };
  const width = element.clientWidth * scale.x;
  const height = element.clientHeight * scale.y;
  const x = left * scale.x;
  const y2 = top * scale.y;
  return {
    width,
    height,
    x,
    y: y2
  };
}
function getClientRectFromClippingAncestor(element, clippingAncestor, strategy) {
  if (clippingAncestor === "viewport") {
    return rectToClientRect(getViewportRect(element, strategy));
  }
  if (isElement(clippingAncestor)) {
    return rectToClientRect(getInnerBoundingClientRect(clippingAncestor, strategy));
  }
  return rectToClientRect(getDocumentRect(getDocumentElement(element)));
}
function getClippingElementAncestors(element, cache) {
  const cachedResult = cache.get(element);
  if (cachedResult) {
    return cachedResult;
  }
  let result = getOverflowAncestors(element).filter((el) => isElement(el) && getNodeName(el) !== "body");
  let currentContainingBlockComputedStyle = null;
  const elementIsFixed = getComputedStyle$1(element).position === "fixed";
  let currentNode = elementIsFixed ? getParentNode(element) : element;
  while (isElement(currentNode) && !isLastTraversableNode(currentNode)) {
    const computedStyle = getComputedStyle$1(currentNode);
    const containingBlock = isContainingBlock(currentNode);
    const shouldDropCurrentNode = elementIsFixed ? !containingBlock && !currentContainingBlockComputedStyle : !containingBlock && computedStyle.position === "static" && !!currentContainingBlockComputedStyle && ["absolute", "fixed"].includes(currentContainingBlockComputedStyle.position);
    if (shouldDropCurrentNode) {
      result = result.filter((ancestor) => ancestor !== currentNode);
    } else {
      currentContainingBlockComputedStyle = computedStyle;
    }
    currentNode = getParentNode(currentNode);
  }
  cache.set(element, result);
  return result;
}
function getClippingRect(_ref) {
  let {
    element,
    boundary,
    rootBoundary,
    strategy
  } = _ref;
  const elementClippingAncestors = boundary === "clippingAncestors" ? getClippingElementAncestors(element, this._c) : [].concat(boundary);
  const clippingAncestors = [...elementClippingAncestors, rootBoundary];
  const firstClippingAncestor = clippingAncestors[0];
  const clippingRect = clippingAncestors.reduce((accRect, clippingAncestor) => {
    const rect = getClientRectFromClippingAncestor(element, clippingAncestor, strategy);
    accRect.top = max(rect.top, accRect.top);
    accRect.right = min(rect.right, accRect.right);
    accRect.bottom = min(rect.bottom, accRect.bottom);
    accRect.left = max(rect.left, accRect.left);
    return accRect;
  }, getClientRectFromClippingAncestor(element, firstClippingAncestor, strategy));
  return {
    width: clippingRect.right - clippingRect.left,
    height: clippingRect.bottom - clippingRect.top,
    x: clippingRect.left,
    y: clippingRect.top
  };
}
function getDimensions(element) {
  if (isHTMLElement(element)) {
    return getCssDimensions(element);
  }
  return element.getBoundingClientRect();
}
function getTrueOffsetParent(element) {
  if (!isHTMLElement(element) || getComputedStyle$1(element).position === "fixed") {
    return null;
  }
  return element.offsetParent;
}
function getContainingBlock(element) {
  let currentNode = getParentNode(element);
  while (isHTMLElement(currentNode) && !isLastTraversableNode(currentNode)) {
    if (isContainingBlock(currentNode)) {
      return currentNode;
    } else {
      currentNode = getParentNode(currentNode);
    }
  }
  return null;
}
function getOffsetParent(element) {
  const window2 = getWindow(element);
  let offsetParent = getTrueOffsetParent(element);
  while (offsetParent && isTableElement(offsetParent) && getComputedStyle$1(offsetParent).position === "static") {
    offsetParent = getTrueOffsetParent(offsetParent);
  }
  if (offsetParent && (getNodeName(offsetParent) === "html" || getNodeName(offsetParent) === "body" && getComputedStyle$1(offsetParent).position === "static" && !isContainingBlock(offsetParent))) {
    return window2;
  }
  return offsetParent || getContainingBlock(element) || window2;
}
function getRectRelativeToOffsetParent(element, offsetParent, strategy) {
  const isOffsetParentAnElement = isHTMLElement(offsetParent);
  const documentElement = getDocumentElement(offsetParent);
  const rect = getBoundingClientRect(element, true, strategy === "fixed", offsetParent);
  let scroll = {
    scrollLeft: 0,
    scrollTop: 0
  };
  const offsets = {
    x: 0,
    y: 0
  };
  if (isOffsetParentAnElement || !isOffsetParentAnElement && strategy !== "fixed") {
    if (getNodeName(offsetParent) !== "body" || isOverflowElement(documentElement)) {
      scroll = getNodeScroll(offsetParent);
    }
    if (isHTMLElement(offsetParent)) {
      const offsetRect = getBoundingClientRect(offsetParent, true);
      offsets.x = offsetRect.x + offsetParent.clientLeft;
      offsets.y = offsetRect.y + offsetParent.clientTop;
    } else if (documentElement) {
      offsets.x = getWindowScrollBarX(documentElement);
    }
  }
  return {
    x: rect.left + scroll.scrollLeft - offsets.x,
    y: rect.top + scroll.scrollTop - offsets.y,
    width: rect.width,
    height: rect.height
  };
}
const platform = {
  getClippingRect,
  convertOffsetParentRelativeRectToViewportRelativeRect,
  isElement,
  getDimensions,
  getOffsetParent,
  getDocumentElement,
  getScale,
  async getElementRects(_ref) {
    let {
      reference,
      floating,
      strategy
    } = _ref;
    const getOffsetParentFn = this.getOffsetParent || getOffsetParent;
    const getDimensionsFn = this.getDimensions;
    return {
      reference: getRectRelativeToOffsetParent(reference, await getOffsetParentFn(floating), strategy),
      floating: {
        x: 0,
        y: 0,
        ...await getDimensionsFn(floating)
      }
    };
  },
  getClientRects: (element) => Array.from(element.getClientRects()),
  isRTL: (element) => getComputedStyle$1(element).direction === "rtl"
};
const computePosition = (reference, floating, options) => {
  const cache = /* @__PURE__ */ new Map();
  const mergedOptions = {
    platform,
    ...options
  };
  const platformWithCache = {
    ...mergedOptions.platform,
    _c: cache
  };
  return computePosition$1(reference, floating, {
    ...mergedOptions,
    platform: platformWithCache
  });
};
const h = {
  // Disable popper components
  disabled: false,
  // Default position offset along main axis (px)
  distance: 5,
  // Default position offset along cross axis (px)
  skidding: 0,
  // Default container where the tooltip will be appended
  container: "body",
  // Element used to compute position and size boundaries
  boundary: void 0,
  // Skip delay & CSS transitions when another popper is shown, so that the popper appear to instanly move to the new position.
  instantMove: false,
  // Auto destroy tooltip DOM nodes (ms)
  disposeTimeout: 150,
  // Triggers on the popper itself
  popperTriggers: [],
  // Positioning strategy
  strategy: "absolute",
  // Prevent overflow
  preventOverflow: true,
  // Flip to the opposite placement if needed
  flip: true,
  // Shift on the cross axis to prevent the popper from overflowing
  shift: true,
  // Overflow padding (px)
  overflowPadding: 0,
  // Arrow padding (px)
  arrowPadding: 0,
  // Compute arrow overflow (useful to hide it)
  arrowOverflow: true,
  /**
   * By default, compute autohide on 'click'.
   */
  autoHideOnMousedown: false,
  // Themes
  themes: {
    tooltip: {
      // Default tooltip placement relative to target element
      placement: "top",
      // Default events that trigger the tooltip
      triggers: ["hover", "focus", "touch"],
      // Close tooltip on click on tooltip target
      hideTriggers: (e) => [...e, "click"],
      // Delay (ms)
      delay: {
        show: 200,
        hide: 0
      },
      // Update popper on content resize
      handleResize: false,
      // Enable HTML content in directive
      html: false,
      // Displayed when tooltip content is loading
      loadingContent: "..."
    },
    dropdown: {
      // Default dropdown placement relative to target element
      placement: "bottom",
      // Default events that trigger the dropdown
      triggers: ["click"],
      // Delay (ms)
      delay: 0,
      // Update popper on content resize
      handleResize: true,
      // Hide on clock outside
      autoHide: true
    },
    menu: {
      $extend: "dropdown",
      triggers: ["hover", "focus"],
      popperTriggers: ["hover"],
      delay: {
        show: 0,
        hide: 400
      }
    }
  }
};
function S(e, t5) {
  let o = h.themes[e] || {}, i;
  do
    i = o[t5], typeof i > "u" ? o.$extend ? o = h.themes[o.$extend] || {} : (o = null, i = h[t5]) : o = null;
  while (o);
  return i;
}
function Ze(e) {
  const t5 = [e];
  let o = h.themes[e] || {};
  do
    o.$extend && !o.$resetCss ? (t5.push(o.$extend), o = h.themes[o.$extend] || {}) : o = null;
  while (o);
  return t5.map((i) => `v-popper--theme-${i}`);
}
function re(e) {
  const t5 = [e];
  let o = h.themes[e] || {};
  do
    o.$extend ? (t5.push(o.$extend), o = h.themes[o.$extend] || {}) : o = null;
  while (o);
  return t5;
}
let $ = false;
if (typeof window < "u") {
  $ = false;
  try {
    const e = Object.defineProperty({}, "passive", {
      get() {
        $ = true;
      }
    });
    window.addEventListener("test", null, e);
  } catch {
  }
}
let _e = false;
typeof window < "u" && typeof navigator < "u" && (_e = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream);
const Te = ["auto", "top", "bottom", "left", "right"].reduce((e, t5) => e.concat([
  t5,
  `${t5}-start`,
  `${t5}-end`
]), []), pe = {
  hover: "mouseenter",
  focus: "focus",
  click: "click",
  touch: "touchstart",
  pointer: "pointerdown"
}, ae = {
  hover: "mouseleave",
  focus: "blur",
  click: "click",
  touch: "touchend",
  pointer: "pointerup"
};
function de(e, t5) {
  const o = e.indexOf(t5);
  o !== -1 && e.splice(o, 1);
}
function G() {
  return new Promise((e) => requestAnimationFrame(() => {
    requestAnimationFrame(e);
  }));
}
const d = [];
let g = null;
const le = {};
function he(e) {
  let t5 = le[e];
  return t5 || (t5 = le[e] = []), t5;
}
let Y = function() {
};
typeof window < "u" && (Y = window.Element);
function n(e) {
  return function(t5) {
    return S(t5.theme, e);
  };
}
const q = "__floating-vue__popper", Q = () => defineComponent({
  name: "VPopper",
  provide() {
    return {
      [q]: {
        parentPopper: this
      }
    };
  },
  inject: {
    [q]: { default: null }
  },
  props: {
    theme: {
      type: String,
      required: true
    },
    targetNodes: {
      type: Function,
      required: true
    },
    referenceNode: {
      type: Function,
      default: null
    },
    popperNode: {
      type: Function,
      required: true
    },
    shown: {
      type: Boolean,
      default: false
    },
    showGroup: {
      type: String,
      default: null
    },
    // eslint-disable-next-line vue/require-prop-types
    ariaId: {
      default: null
    },
    disabled: {
      type: Boolean,
      default: n("disabled")
    },
    positioningDisabled: {
      type: Boolean,
      default: n("positioningDisabled")
    },
    placement: {
      type: String,
      default: n("placement"),
      validator: (e) => Te.includes(e)
    },
    delay: {
      type: [String, Number, Object],
      default: n("delay")
    },
    distance: {
      type: [Number, String],
      default: n("distance")
    },
    skidding: {
      type: [Number, String],
      default: n("skidding")
    },
    triggers: {
      type: Array,
      default: n("triggers")
    },
    showTriggers: {
      type: [Array, Function],
      default: n("showTriggers")
    },
    hideTriggers: {
      type: [Array, Function],
      default: n("hideTriggers")
    },
    popperTriggers: {
      type: Array,
      default: n("popperTriggers")
    },
    popperShowTriggers: {
      type: [Array, Function],
      default: n("popperShowTriggers")
    },
    popperHideTriggers: {
      type: [Array, Function],
      default: n("popperHideTriggers")
    },
    container: {
      type: [String, Object, Y, Boolean],
      default: n("container")
    },
    boundary: {
      type: [String, Y],
      default: n("boundary")
    },
    strategy: {
      type: String,
      validator: (e) => ["absolute", "fixed"].includes(e),
      default: n("strategy")
    },
    autoHide: {
      type: [Boolean, Function],
      default: n("autoHide")
    },
    handleResize: {
      type: Boolean,
      default: n("handleResize")
    },
    instantMove: {
      type: Boolean,
      default: n("instantMove")
    },
    eagerMount: {
      type: Boolean,
      default: n("eagerMount")
    },
    popperClass: {
      type: [String, Array, Object],
      default: n("popperClass")
    },
    computeTransformOrigin: {
      type: Boolean,
      default: n("computeTransformOrigin")
    },
    /**
     * @deprecated
     */
    autoMinSize: {
      type: Boolean,
      default: n("autoMinSize")
    },
    autoSize: {
      type: [Boolean, String],
      default: n("autoSize")
    },
    /**
     * @deprecated
     */
    autoMaxSize: {
      type: Boolean,
      default: n("autoMaxSize")
    },
    autoBoundaryMaxSize: {
      type: Boolean,
      default: n("autoBoundaryMaxSize")
    },
    preventOverflow: {
      type: Boolean,
      default: n("preventOverflow")
    },
    overflowPadding: {
      type: [Number, String],
      default: n("overflowPadding")
    },
    arrowPadding: {
      type: [Number, String],
      default: n("arrowPadding")
    },
    arrowOverflow: {
      type: Boolean,
      default: n("arrowOverflow")
    },
    flip: {
      type: Boolean,
      default: n("flip")
    },
    shift: {
      type: Boolean,
      default: n("shift")
    },
    shiftCrossAxis: {
      type: Boolean,
      default: n("shiftCrossAxis")
    },
    noAutoFocus: {
      type: Boolean,
      default: n("noAutoFocus")
    },
    disposeTimeout: {
      type: Number,
      default: n("disposeTimeout")
    }
  },
  emits: {
    show: () => true,
    hide: () => true,
    "update:shown": (e) => true,
    "apply-show": () => true,
    "apply-hide": () => true,
    "close-group": () => true,
    "close-directive": () => true,
    "auto-hide": () => true,
    resize: () => true
  },
  data() {
    return {
      isShown: false,
      isMounted: false,
      skipTransition: false,
      classes: {
        showFrom: false,
        showTo: false,
        hideFrom: false,
        hideTo: true
      },
      result: {
        x: 0,
        y: 0,
        placement: "",
        strategy: this.strategy,
        arrow: {
          x: 0,
          y: 0,
          centerOffset: 0
        },
        transformOrigin: null
      },
      randomId: `popper_${[Math.random(), Date.now()].map((e) => e.toString(36).substring(2, 10)).join("_")}`,
      shownChildren: /* @__PURE__ */ new Set(),
      lastAutoHide: true,
      pendingHide: false,
      containsGlobalTarget: false,
      isDisposed: true,
      mouseDownContains: false
    };
  },
  computed: {
    popperId() {
      return this.ariaId != null ? this.ariaId : this.randomId;
    },
    shouldMountContent() {
      return this.eagerMount || this.isMounted;
    },
    slotData() {
      return {
        popperId: this.popperId,
        isShown: this.isShown,
        shouldMountContent: this.shouldMountContent,
        skipTransition: this.skipTransition,
        autoHide: typeof this.autoHide == "function" ? this.lastAutoHide : this.autoHide,
        show: this.show,
        hide: this.hide,
        handleResize: this.handleResize,
        onResize: this.onResize,
        classes: {
          ...this.classes,
          popperClass: this.popperClass
        },
        result: this.positioningDisabled ? null : this.result,
        attrs: this.$attrs
      };
    },
    parentPopper() {
      var e;
      return (e = this[q]) == null ? void 0 : e.parentPopper;
    },
    hasPopperShowTriggerHover() {
      var e, t5;
      return ((e = this.popperTriggers) == null ? void 0 : e.includes("hover")) || ((t5 = this.popperShowTriggers) == null ? void 0 : t5.includes("hover"));
    }
  },
  watch: {
    shown: "$_autoShowHide",
    disabled(e) {
      e ? this.dispose() : this.init();
    },
    async container() {
      this.isShown && (this.$_ensureTeleport(), await this.$_computePosition());
    },
    triggers: {
      handler: "$_refreshListeners",
      deep: true
    },
    positioningDisabled: "$_refreshListeners",
    ...[
      "placement",
      "distance",
      "skidding",
      "boundary",
      "strategy",
      "overflowPadding",
      "arrowPadding",
      "preventOverflow",
      "shift",
      "shiftCrossAxis",
      "flip"
    ].reduce((e, t5) => (e[t5] = "$_computePosition", e), {})
  },
  created() {
    this.autoMinSize && console.warn('[floating-vue] `autoMinSize` option is deprecated. Use `autoSize="min"` instead.'), this.autoMaxSize && console.warn("[floating-vue] `autoMaxSize` option is deprecated. Use `autoBoundaryMaxSize` instead.");
  },
  mounted() {
    this.init(), this.$_detachPopperNode();
  },
  activated() {
    this.$_autoShowHide();
  },
  deactivated() {
    this.hide();
  },
  beforeUnmount() {
    this.dispose();
  },
  methods: {
    show({ event: e = null, skipDelay: t5 = false, force: o = false } = {}) {
      var i, s;
      (i = this.parentPopper) != null && i.lockedChild && this.parentPopper.lockedChild !== this || (this.pendingHide = false, (o || !this.disabled) && (((s = this.parentPopper) == null ? void 0 : s.lockedChild) === this && (this.parentPopper.lockedChild = null), this.$_scheduleShow(e, t5), this.$emit("show"), this.$_showFrameLocked = true, requestAnimationFrame(() => {
        this.$_showFrameLocked = false;
      })), this.$emit("update:shown", true));
    },
    hide({ event: e = null, skipDelay: t5 = false } = {}) {
      var o;
      if (!this.$_hideInProgress) {
        if (this.shownChildren.size > 0) {
          this.pendingHide = true;
          return;
        }
        if (this.hasPopperShowTriggerHover && this.$_isAimingPopper()) {
          this.parentPopper && (this.parentPopper.lockedChild = this, clearTimeout(this.parentPopper.lockedChildTimer), this.parentPopper.lockedChildTimer = setTimeout(() => {
            this.parentPopper.lockedChild === this && (this.parentPopper.lockedChild.hide({ skipDelay: t5 }), this.parentPopper.lockedChild = null);
          }, 1e3));
          return;
        }
        ((o = this.parentPopper) == null ? void 0 : o.lockedChild) === this && (this.parentPopper.lockedChild = null), this.pendingHide = false, this.$_scheduleHide(e, t5), this.$emit("hide"), this.$emit("update:shown", false);
      }
    },
    init() {
      var e;
      this.isDisposed && (this.isDisposed = false, this.isMounted = false, this.$_events = [], this.$_preventShow = false, this.$_referenceNode = ((e = this.referenceNode) == null ? void 0 : e.call(this)) ?? this.$el, this.$_targetNodes = this.targetNodes().filter((t5) => t5.nodeType === t5.ELEMENT_NODE), this.$_popperNode = this.popperNode(), this.$_innerNode = this.$_popperNode.querySelector(".v-popper__inner"), this.$_arrowNode = this.$_popperNode.querySelector(".v-popper__arrow-container"), this.$_swapTargetAttrs("title", "data-original-title"), this.$_detachPopperNode(), this.triggers.length && this.$_addEventListeners(), this.shown && this.show());
    },
    dispose() {
      this.isDisposed || (this.isDisposed = true, this.$_removeEventListeners(), this.hide({ skipDelay: true }), this.$_detachPopperNode(), this.isMounted = false, this.isShown = false, this.$_updateParentShownChildren(false), this.$_swapTargetAttrs("data-original-title", "title"));
    },
    async onResize() {
      this.isShown && (await this.$_computePosition(), this.$emit("resize"));
    },
    async $_computePosition() {
      if (this.isDisposed || this.positioningDisabled)
        return;
      const e = {
        strategy: this.strategy,
        middleware: []
      };
      (this.distance || this.skidding) && e.middleware.push(offset({
        mainAxis: this.distance,
        crossAxis: this.skidding
      }));
      const t5 = this.placement.startsWith("auto");
      if (t5 ? e.middleware.push(autoPlacement({
        alignment: this.placement.split("-")[1] ?? ""
      })) : e.placement = this.placement, this.preventOverflow && (this.shift && e.middleware.push(shift({
        padding: this.overflowPadding,
        boundary: this.boundary,
        crossAxis: this.shiftCrossAxis
      })), !t5 && this.flip && e.middleware.push(flip({
        padding: this.overflowPadding,
        boundary: this.boundary
      }))), e.middleware.push(arrow({
        element: this.$_arrowNode,
        padding: this.arrowPadding
      })), this.arrowOverflow && e.middleware.push({
        name: "arrowOverflow",
        fn: ({ placement: i, rects: s, middlewareData: r }) => {
          let p;
          const { centerOffset: a } = r.arrow;
          return i.startsWith("top") || i.startsWith("bottom") ? p = Math.abs(a) > s.reference.width / 2 : p = Math.abs(a) > s.reference.height / 2, {
            data: {
              overflow: p
            }
          };
        }
      }), this.autoMinSize || this.autoSize) {
        const i = this.autoSize ? this.autoSize : this.autoMinSize ? "min" : null;
        e.middleware.push({
          name: "autoSize",
          fn: ({ rects: s, placement: r, middlewareData: p }) => {
            var u;
            if ((u = p.autoSize) != null && u.skip)
              return {};
            let a, l;
            return r.startsWith("top") || r.startsWith("bottom") ? a = s.reference.width : l = s.reference.height, this.$_innerNode.style[i === "min" ? "minWidth" : i === "max" ? "maxWidth" : "width"] = a != null ? `${a}px` : null, this.$_innerNode.style[i === "min" ? "minHeight" : i === "max" ? "maxHeight" : "height"] = l != null ? `${l}px` : null, {
              data: {
                skip: true
              },
              reset: {
                rects: true
              }
            };
          }
        });
      }
      (this.autoMaxSize || this.autoBoundaryMaxSize) && (this.$_innerNode.style.maxWidth = null, this.$_innerNode.style.maxHeight = null, e.middleware.push(size({
        boundary: this.boundary,
        padding: this.overflowPadding,
        apply: ({ availableWidth: i, availableHeight: s }) => {
          this.$_innerNode.style.maxWidth = i != null ? `${i}px` : null, this.$_innerNode.style.maxHeight = s != null ? `${s}px` : null;
        }
      })));
      const o = await computePosition(this.$_referenceNode, this.$_popperNode, e);
      Object.assign(this.result, {
        x: o.x,
        y: o.y,
        placement: o.placement,
        strategy: o.strategy,
        arrow: {
          ...o.middlewareData.arrow,
          ...o.middlewareData.arrowOverflow
        }
      });
    },
    $_scheduleShow(e, t5 = false) {
      if (this.$_updateParentShownChildren(true), this.$_hideInProgress = false, clearTimeout(this.$_scheduleTimer), g && this.instantMove && g.instantMove && g !== this.parentPopper) {
        g.$_applyHide(true), this.$_applyShow(true);
        return;
      }
      t5 ? this.$_applyShow() : this.$_scheduleTimer = setTimeout(this.$_applyShow.bind(this), this.$_computeDelay("show"));
    },
    $_scheduleHide(e, t5 = false) {
      if (this.shownChildren.size > 0) {
        this.pendingHide = true;
        return;
      }
      this.$_updateParentShownChildren(false), this.$_hideInProgress = true, clearTimeout(this.$_scheduleTimer), this.isShown && (g = this), t5 ? this.$_applyHide() : this.$_scheduleTimer = setTimeout(this.$_applyHide.bind(this), this.$_computeDelay("hide"));
    },
    $_computeDelay(e) {
      const t5 = this.delay;
      return parseInt(t5 && t5[e] || t5 || 0);
    },
    async $_applyShow(e = false) {
      clearTimeout(this.$_disposeTimer), clearTimeout(this.$_scheduleTimer), this.skipTransition = e, !this.isShown && (this.$_ensureTeleport(), await G(), await this.$_computePosition(), await this.$_applyShowEffect(), this.positioningDisabled || this.$_registerEventListeners([
        ...getOverflowAncestors(this.$_referenceNode),
        ...getOverflowAncestors(this.$_popperNode)
      ], "scroll", () => {
        this.$_computePosition();
      }));
    },
    async $_applyShowEffect() {
      if (this.$_hideInProgress)
        return;
      if (this.computeTransformOrigin) {
        const t5 = this.$_referenceNode.getBoundingClientRect(), o = this.$_popperNode.querySelector(".v-popper__wrapper"), i = o.parentNode.getBoundingClientRect(), s = t5.x + t5.width / 2 - (i.left + o.offsetLeft), r = t5.y + t5.height / 2 - (i.top + o.offsetTop);
        this.result.transformOrigin = `${s}px ${r}px`;
      }
      this.isShown = true, this.$_applyAttrsToTarget({
        "aria-describedby": this.popperId,
        "data-popper-shown": ""
      });
      const e = this.showGroup;
      if (e) {
        let t5;
        for (let o = 0; o < d.length; o++)
          t5 = d[o], t5.showGroup !== e && (t5.hide(), t5.$emit("close-group"));
      }
      d.push(this), document.body.classList.add("v-popper--some-open");
      for (const t5 of re(this.theme))
        he(t5).push(this), document.body.classList.add(`v-popper--some-open--${t5}`);
      this.$emit("apply-show"), this.classes.showFrom = true, this.classes.showTo = false, this.classes.hideFrom = false, this.classes.hideTo = false, await G(), this.classes.showFrom = false, this.classes.showTo = true, this.noAutoFocus || this.$_popperNode.focus();
    },
    async $_applyHide(e = false) {
      if (this.shownChildren.size > 0) {
        this.pendingHide = true, this.$_hideInProgress = false;
        return;
      }
      if (clearTimeout(this.$_scheduleTimer), !this.isShown)
        return;
      this.skipTransition = e, de(d, this), d.length === 0 && document.body.classList.remove("v-popper--some-open");
      for (const o of re(this.theme)) {
        const i = he(o);
        de(i, this), i.length === 0 && document.body.classList.remove(`v-popper--some-open--${o}`);
      }
      g === this && (g = null), this.isShown = false, this.$_applyAttrsToTarget({
        "aria-describedby": void 0,
        "data-popper-shown": void 0
      }), clearTimeout(this.$_disposeTimer);
      const t5 = this.disposeTimeout;
      t5 !== null && (this.$_disposeTimer = setTimeout(() => {
        this.$_popperNode && (this.$_detachPopperNode(), this.isMounted = false);
      }, t5)), this.$_removeEventListeners("scroll"), this.$emit("apply-hide"), this.classes.showFrom = false, this.classes.showTo = false, this.classes.hideFrom = true, this.classes.hideTo = false, await G(), this.classes.hideFrom = false, this.classes.hideTo = true;
    },
    $_autoShowHide() {
      this.shown ? this.show() : this.hide();
    },
    $_ensureTeleport() {
      if (this.isDisposed)
        return;
      let e = this.container;
      if (typeof e == "string" ? e = window.document.querySelector(e) : e === false && (e = this.$_targetNodes[0].parentNode), !e)
        throw new Error("No container for popover: " + this.container);
      e.appendChild(this.$_popperNode), this.isMounted = true;
    },
    $_addEventListeners() {
      const e = (o) => {
        this.isShown && !this.$_hideInProgress || (o.usedByTooltip = true, !this.$_preventShow && this.show({ event: o }));
      };
      this.$_registerTriggerListeners(this.$_targetNodes, pe, this.triggers, this.showTriggers, e), this.$_registerTriggerListeners([this.$_popperNode], pe, this.popperTriggers, this.popperShowTriggers, e);
      const t5 = (o) => {
        o.usedByTooltip || this.hide({ event: o });
      };
      this.$_registerTriggerListeners(this.$_targetNodes, ae, this.triggers, this.hideTriggers, t5), this.$_registerTriggerListeners([this.$_popperNode], ae, this.popperTriggers, this.popperHideTriggers, t5);
    },
    $_registerEventListeners(e, t5, o) {
      this.$_events.push({ targetNodes: e, eventType: t5, handler: o }), e.forEach((i) => i.addEventListener(t5, o, $ ? {
        passive: true
      } : void 0));
    },
    $_registerTriggerListeners(e, t5, o, i, s) {
      let r = o;
      i != null && (r = typeof i == "function" ? i(r) : i), r.forEach((p) => {
        const a = t5[p];
        a && this.$_registerEventListeners(e, a, s);
      });
    },
    $_removeEventListeners(e) {
      const t5 = [];
      this.$_events.forEach((o) => {
        const { targetNodes: i, eventType: s, handler: r } = o;
        !e || e === s ? i.forEach((p) => p.removeEventListener(s, r)) : t5.push(o);
      }), this.$_events = t5;
    },
    $_refreshListeners() {
      this.isDisposed || (this.$_removeEventListeners(), this.$_addEventListeners());
    },
    $_handleGlobalClose(e, t5 = false) {
      this.$_showFrameLocked || (this.hide({ event: e }), e.closePopover ? this.$emit("close-directive") : this.$emit("auto-hide"), t5 && (this.$_preventShow = true, setTimeout(() => {
        this.$_preventShow = false;
      }, 300)));
    },
    $_detachPopperNode() {
      this.$_popperNode.parentNode && this.$_popperNode.parentNode.removeChild(this.$_popperNode);
    },
    $_swapTargetAttrs(e, t5) {
      for (const o of this.$_targetNodes) {
        const i = o.getAttribute(e);
        i && (o.removeAttribute(e), o.setAttribute(t5, i));
      }
    },
    $_applyAttrsToTarget(e) {
      for (const t5 of this.$_targetNodes)
        for (const o in e) {
          const i = e[o];
          i == null ? t5.removeAttribute(o) : t5.setAttribute(o, i);
        }
    },
    $_updateParentShownChildren(e) {
      let t5 = this.parentPopper;
      for (; t5; )
        e ? t5.shownChildren.add(this.randomId) : (t5.shownChildren.delete(this.randomId), t5.pendingHide && t5.hide()), t5 = t5.parentPopper;
    },
    $_isAimingPopper() {
      const e = this.$_referenceNode.getBoundingClientRect();
      if (y >= e.left && y <= e.right && _ >= e.top && _ <= e.bottom) {
        const t5 = this.$_popperNode.getBoundingClientRect(), o = y - c, i = _ - m, r = t5.left + t5.width / 2 - c + (t5.top + t5.height / 2) - m + t5.width + t5.height, p = c + o * r, a = m + i * r;
        return C(c, m, p, a, t5.left, t5.top, t5.left, t5.bottom) || // Left edge
        C(c, m, p, a, t5.left, t5.top, t5.right, t5.top) || // Top edge
        C(c, m, p, a, t5.right, t5.top, t5.right, t5.bottom) || // Right edge
        C(c, m, p, a, t5.left, t5.bottom, t5.right, t5.bottom);
      }
      return false;
    }
  },
  render() {
    return this.$slots.default(this.slotData);
  }
});
if (typeof document < "u" && typeof window < "u") {
  if (_e) {
    const e = $ ? {
      passive: true,
      capture: true
    } : true;
    document.addEventListener("touchstart", (t5) => ue(t5), e), document.addEventListener("touchend", (t5) => fe(t5, true), e);
  } else
    window.addEventListener("mousedown", (e) => ue(e), true), window.addEventListener("click", (e) => fe(e, false), true);
  window.addEventListener("resize", tt);
}
function ue(e, t5) {
  for (let o = 0; o < d.length; o++) {
    const i = d[o];
    try {
      i.mouseDownContains = i.popperNode().contains(e.target);
    } catch {
    }
  }
}
function fe(e, t5) {
  Pe(e, t5);
}
function Pe(e, t5) {
  const o = {};
  for (let i = d.length - 1; i >= 0; i--) {
    const s = d[i];
    try {
      const r = s.containsGlobalTarget = s.mouseDownContains || s.popperNode().contains(e.target);
      s.pendingHide = false, requestAnimationFrame(() => {
        if (s.pendingHide = false, !o[s.randomId] && ce(s, r, e)) {
          if (s.$_handleGlobalClose(e, t5), !e.closeAllPopover && e.closePopover && r) {
            let a = s.parentPopper;
            for (; a; )
              o[a.randomId] = true, a = a.parentPopper;
            return;
          }
          let p = s.parentPopper;
          for (; p && ce(p, p.containsGlobalTarget, e); ) {
            p.$_handleGlobalClose(e, t5);
            p = p.parentPopper;
          }
        }
      });
    } catch {
    }
  }
}
function ce(e, t5, o) {
  return o.closeAllPopover || o.closePopover && t5 || et(e, o) && !t5;
}
function et(e, t5) {
  if (typeof e.autoHide == "function") {
    const o = e.autoHide(t5);
    return e.lastAutoHide = o, o;
  }
  return e.autoHide;
}
function tt() {
  for (let e = 0; e < d.length; e++)
    d[e].$_computePosition();
}
let c = 0, m = 0, y = 0, _ = 0;
typeof window < "u" && window.addEventListener("mousemove", (e) => {
  c = y, m = _, y = e.clientX, _ = e.clientY;
}, $ ? {
  passive: true
} : void 0);
function C(e, t5, o, i, s, r, p, a) {
  const l = ((p - s) * (t5 - r) - (a - r) * (e - s)) / ((a - r) * (o - e) - (p - s) * (i - t5)), u = ((o - e) * (t5 - r) - (i - t5) * (e - s)) / ((a - r) * (o - e) - (p - s) * (i - t5));
  return l >= 0 && l <= 1 && u >= 0 && u <= 1;
}
const ot = {
  extends: Q()
}, B = (e, t5) => {
  const o = e.__vccOpts || e;
  for (const [i, s] of t5)
    o[i] = s;
  return o;
};
function it(e, t5, o, i, s, r) {
  return openBlock(), createElementBlock("div", {
    ref: "reference",
    class: normalizeClass(["v-popper", {
      "v-popper--shown": e.slotData.isShown
    }])
  }, [
    renderSlot(e.$slots, "default", normalizeProps(guardReactiveProps(e.slotData)))
  ], 2);
}
const st = /* @__PURE__ */ B(ot, [["render", it]]);
function nt() {
  var e = window.navigator.userAgent, t5 = e.indexOf("MSIE ");
  if (t5 > 0)
    return parseInt(e.substring(t5 + 5, e.indexOf(".", t5)), 10);
  var o = e.indexOf("Trident/");
  if (o > 0) {
    var i = e.indexOf("rv:");
    return parseInt(e.substring(i + 3, e.indexOf(".", i)), 10);
  }
  var s = e.indexOf("Edge/");
  return s > 0 ? parseInt(e.substring(s + 5, e.indexOf(".", s)), 10) : -1;
}
let z;
function X() {
  X.init || (X.init = true, z = nt() !== -1);
}
var E = {
  name: "ResizeObserver",
  props: {
    emitOnMount: {
      type: Boolean,
      default: false
    },
    ignoreWidth: {
      type: Boolean,
      default: false
    },
    ignoreHeight: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "notify"
  ],
  mounted() {
    X(), nextTick(() => {
      this._w = this.$el.offsetWidth, this._h = this.$el.offsetHeight, this.emitOnMount && this.emitSize();
    });
    const e = document.createElement("object");
    this._resizeObject = e, e.setAttribute("aria-hidden", "true"), e.setAttribute("tabindex", -1), e.onload = this.addResizeHandlers, e.type = "text/html", z && this.$el.appendChild(e), e.data = "about:blank", z || this.$el.appendChild(e);
  },
  beforeUnmount() {
    this.removeResizeHandlers();
  },
  methods: {
    compareAndNotify() {
      (!this.ignoreWidth && this._w !== this.$el.offsetWidth || !this.ignoreHeight && this._h !== this.$el.offsetHeight) && (this._w = this.$el.offsetWidth, this._h = this.$el.offsetHeight, this.emitSize());
    },
    emitSize() {
      this.$emit("notify", {
        width: this._w,
        height: this._h
      });
    },
    addResizeHandlers() {
      this._resizeObject.contentDocument.defaultView.addEventListener("resize", this.compareAndNotify), this.compareAndNotify();
    },
    removeResizeHandlers() {
      this._resizeObject && this._resizeObject.onload && (!z && this._resizeObject.contentDocument && this._resizeObject.contentDocument.defaultView.removeEventListener("resize", this.compareAndNotify), this.$el.removeChild(this._resizeObject), this._resizeObject.onload = null, this._resizeObject = null);
    }
  }
};
const rt = /* @__PURE__ */ withScopeId();
pushScopeId("data-v-b329ee4c");
const pt = {
  class: "resize-observer",
  tabindex: "-1"
};
popScopeId();
const at = /* @__PURE__ */ rt((e, t5, o, i, s, r) => (openBlock(), createBlock("div", pt)));
E.render = at;
E.__scopeId = "data-v-b329ee4c";
E.__file = "src/components/ResizeObserver.vue";
const Z = (e = "theme") => ({
  computed: {
    themeClass() {
      return Ze(this[e]);
    }
  }
}), dt = defineComponent({
  name: "VPopperContent",
  components: {
    ResizeObserver: E
  },
  mixins: [
    Z()
  ],
  props: {
    popperId: String,
    theme: String,
    shown: Boolean,
    mounted: Boolean,
    skipTransition: Boolean,
    autoHide: Boolean,
    handleResize: Boolean,
    classes: Object,
    result: Object
  },
  emits: [
    "hide",
    "resize"
  ],
  methods: {
    toPx(e) {
      return e != null && !isNaN(e) ? `${e}px` : null;
    }
  }
}), lt = ["id", "aria-hidden", "tabindex", "data-popper-placement"], ht = {
  ref: "inner",
  class: "v-popper__inner"
}, ut = /* @__PURE__ */ createBaseVNode("div", { class: "v-popper__arrow-outer" }, null, -1), ft = /* @__PURE__ */ createBaseVNode("div", { class: "v-popper__arrow-inner" }, null, -1), ct = [
  ut,
  ft
];
function mt(e, t5, o, i, s, r) {
  const p = resolveComponent("ResizeObserver");
  return openBlock(), createElementBlock("div", {
    id: e.popperId,
    ref: "popover",
    class: normalizeClass(["v-popper__popper", [
      e.themeClass,
      e.classes.popperClass,
      {
        "v-popper__popper--shown": e.shown,
        "v-popper__popper--hidden": !e.shown,
        "v-popper__popper--show-from": e.classes.showFrom,
        "v-popper__popper--show-to": e.classes.showTo,
        "v-popper__popper--hide-from": e.classes.hideFrom,
        "v-popper__popper--hide-to": e.classes.hideTo,
        "v-popper__popper--skip-transition": e.skipTransition,
        "v-popper__popper--arrow-overflow": e.result && e.result.arrow.overflow,
        "v-popper__popper--no-positioning": !e.result
      }
    ]]),
    style: normalizeStyle(e.result ? {
      position: e.result.strategy,
      transform: `translate3d(${Math.round(e.result.x)}px,${Math.round(e.result.y)}px,0)`
    } : void 0),
    "aria-hidden": e.shown ? "false" : "true",
    tabindex: e.autoHide ? 0 : void 0,
    "data-popper-placement": e.result ? e.result.placement : void 0,
    onKeyup: t5[2] || (t5[2] = withKeys((a) => e.autoHide && e.$emit("hide"), ["esc"]))
  }, [
    createBaseVNode("div", {
      class: "v-popper__backdrop",
      onClick: t5[0] || (t5[0] = (a) => e.autoHide && e.$emit("hide"))
    }),
    createBaseVNode("div", {
      class: "v-popper__wrapper",
      style: normalizeStyle(e.result ? {
        transformOrigin: e.result.transformOrigin
      } : void 0)
    }, [
      createBaseVNode("div", ht, [
        e.mounted ? (openBlock(), createElementBlock(Fragment, { key: 0 }, [
          createBaseVNode("div", null, [
            renderSlot(e.$slots, "default")
          ]),
          e.handleResize ? (openBlock(), createBlock(p, {
            key: 0,
            onNotify: t5[1] || (t5[1] = (a) => e.$emit("resize", a))
          })) : createCommentVNode("", true)
        ], 64)) : createCommentVNode("", true)
      ], 512),
      createBaseVNode("div", {
        ref: "arrow",
        class: "v-popper__arrow-container",
        style: normalizeStyle(e.result ? {
          left: e.toPx(e.result.arrow.x),
          top: e.toPx(e.result.arrow.y)
        } : void 0)
      }, ct, 4)
    ], 4)
  ], 46, lt);
}
const ee = /* @__PURE__ */ B(dt, [["render", mt]]), te = {
  methods: {
    show(...e) {
      return this.$refs.popper.show(...e);
    },
    hide(...e) {
      return this.$refs.popper.hide(...e);
    },
    dispose(...e) {
      return this.$refs.popper.dispose(...e);
    },
    onResize(...e) {
      return this.$refs.popper.onResize(...e);
    }
  }
};
let K = function() {
};
typeof window < "u" && (K = window.Element);
const gt = defineComponent({
  name: "VPopperWrapper",
  components: {
    Popper: st,
    PopperContent: ee
  },
  mixins: [
    te,
    Z("finalTheme")
  ],
  props: {
    theme: {
      type: String,
      default: null
    },
    referenceNode: {
      type: Function,
      default: null
    },
    shown: {
      type: Boolean,
      default: false
    },
    showGroup: {
      type: String,
      default: null
    },
    // eslint-disable-next-line vue/require-prop-types
    ariaId: {
      default: null
    },
    disabled: {
      type: Boolean,
      default: void 0
    },
    positioningDisabled: {
      type: Boolean,
      default: void 0
    },
    placement: {
      type: String,
      default: void 0
    },
    delay: {
      type: [String, Number, Object],
      default: void 0
    },
    distance: {
      type: [Number, String],
      default: void 0
    },
    skidding: {
      type: [Number, String],
      default: void 0
    },
    triggers: {
      type: Array,
      default: void 0
    },
    showTriggers: {
      type: [Array, Function],
      default: void 0
    },
    hideTriggers: {
      type: [Array, Function],
      default: void 0
    },
    popperTriggers: {
      type: Array,
      default: void 0
    },
    popperShowTriggers: {
      type: [Array, Function],
      default: void 0
    },
    popperHideTriggers: {
      type: [Array, Function],
      default: void 0
    },
    container: {
      type: [String, Object, K, Boolean],
      default: void 0
    },
    boundary: {
      type: [String, K],
      default: void 0
    },
    strategy: {
      type: String,
      default: void 0
    },
    autoHide: {
      type: [Boolean, Function],
      default: void 0
    },
    handleResize: {
      type: Boolean,
      default: void 0
    },
    instantMove: {
      type: Boolean,
      default: void 0
    },
    eagerMount: {
      type: Boolean,
      default: void 0
    },
    popperClass: {
      type: [String, Array, Object],
      default: void 0
    },
    computeTransformOrigin: {
      type: Boolean,
      default: void 0
    },
    /**
     * @deprecated
     */
    autoMinSize: {
      type: Boolean,
      default: void 0
    },
    autoSize: {
      type: [Boolean, String],
      default: void 0
    },
    /**
     * @deprecated
     */
    autoMaxSize: {
      type: Boolean,
      default: void 0
    },
    autoBoundaryMaxSize: {
      type: Boolean,
      default: void 0
    },
    preventOverflow: {
      type: Boolean,
      default: void 0
    },
    overflowPadding: {
      type: [Number, String],
      default: void 0
    },
    arrowPadding: {
      type: [Number, String],
      default: void 0
    },
    arrowOverflow: {
      type: Boolean,
      default: void 0
    },
    flip: {
      type: Boolean,
      default: void 0
    },
    shift: {
      type: Boolean,
      default: void 0
    },
    shiftCrossAxis: {
      type: Boolean,
      default: void 0
    },
    noAutoFocus: {
      type: Boolean,
      default: void 0
    },
    disposeTimeout: {
      type: Number,
      default: void 0
    }
  },
  emits: {
    show: () => true,
    hide: () => true,
    "update:shown": (e) => true,
    "apply-show": () => true,
    "apply-hide": () => true,
    "close-group": () => true,
    "close-directive": () => true,
    "auto-hide": () => true,
    resize: () => true
  },
  computed: {
    finalTheme() {
      return this.theme ?? this.$options.vPopperTheme;
    }
  },
  methods: {
    getTargetNodes() {
      return Array.from(this.$el.children).filter((e) => e !== this.$refs.popperContent.$el);
    }
  }
});
function wt(e, t5, o, i, s, r) {
  const p = resolveComponent("PopperContent"), a = resolveComponent("Popper");
  return openBlock(), createBlock(a, mergeProps({ ref: "popper" }, e.$props, {
    theme: e.finalTheme,
    "target-nodes": e.getTargetNodes,
    "popper-node": () => e.$refs.popperContent.$el,
    class: [
      e.themeClass
    ],
    onShow: t5[0] || (t5[0] = () => e.$emit("show")),
    onHide: t5[1] || (t5[1] = () => e.$emit("hide")),
    "onUpdate:shown": t5[2] || (t5[2] = (l) => e.$emit("update:shown", l)),
    onApplyShow: t5[3] || (t5[3] = () => e.$emit("apply-show")),
    onApplyHide: t5[4] || (t5[4] = () => e.$emit("apply-hide")),
    onCloseGroup: t5[5] || (t5[5] = () => e.$emit("close-group")),
    onCloseDirective: t5[6] || (t5[6] = () => e.$emit("close-directive")),
    onAutoHide: t5[7] || (t5[7] = () => e.$emit("auto-hide")),
    onResize: t5[8] || (t5[8] = () => e.$emit("resize"))
  }), {
    default: withCtx(({
      popperId: l,
      isShown: u,
      shouldMountContent: L,
      skipTransition: D,
      autoHide: I,
      show: F,
      hide: v,
      handleResize: R,
      onResize: j,
      classes: V,
      result: Ee
    }) => [
      renderSlot(e.$slots, "default", {
        shown: u,
        show: F,
        hide: v
      }),
      createVNode(p, {
        ref: "popperContent",
        "popper-id": l,
        theme: e.finalTheme,
        shown: u,
        mounted: L,
        "skip-transition": D,
        "auto-hide": I,
        "handle-resize": R,
        classes: V,
        result: Ee,
        onHide: v,
        onResize: j
      }, {
        default: withCtx(() => [
          renderSlot(e.$slots, "popper", {
            shown: u,
            hide: v
          })
        ]),
        _: 2
      }, 1032, ["popper-id", "theme", "shown", "mounted", "skip-transition", "auto-hide", "handle-resize", "classes", "result", "onHide", "onResize"])
    ]),
    _: 3
  }, 16, ["theme", "target-nodes", "popper-node", "class"]);
}
const k = /* @__PURE__ */ B(gt, [["render", wt]]), Se = {
  ...k,
  name: "VDropdown",
  vPopperTheme: "dropdown"
};
({
  ...k
});
({
  ...k
});
defineComponent({
  name: "VTooltipDirective",
  components: {
    Popper: Q(),
    PopperContent: ee
  },
  mixins: [
    te
  ],
  inheritAttrs: false,
  props: {
    theme: {
      type: String,
      default: "tooltip"
    },
    html: {
      type: Boolean,
      default: (e) => S(e.theme, "html")
    },
    content: {
      type: [String, Number, Function],
      default: null
    },
    loadingContent: {
      type: String,
      default: (e) => S(e.theme, "loadingContent")
    },
    targetNodes: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      asyncContent: null
    };
  },
  computed: {
    isContentAsync() {
      return typeof this.content == "function";
    },
    loading() {
      return this.isContentAsync && this.asyncContent == null;
    },
    finalContent() {
      return this.isContentAsync ? this.loading ? this.loadingContent : this.asyncContent : this.content;
    }
  },
  watch: {
    content: {
      handler() {
        this.fetchContent(true);
      },
      immediate: true
    },
    async finalContent() {
      await this.$nextTick(), this.$refs.popper.onResize();
    }
  },
  created() {
    this.$_fetchId = 0;
  },
  methods: {
    fetchContent(e) {
      if (typeof this.content == "function" && this.$_isShown && (e || !this.$_loading && this.asyncContent == null)) {
        this.asyncContent = null, this.$_loading = true;
        const t5 = ++this.$_fetchId, o = this.content(this);
        o.then ? o.then((i) => this.onResult(t5, i)) : this.onResult(t5, o);
      }
    },
    onResult(e, t5) {
      e === this.$_fetchId && (this.$_loading = false, this.asyncContent = t5);
    },
    onShow() {
      this.$_isShown = true, this.fetchContent();
    },
    onHide() {
      this.$_isShown = false;
    }
  }
});
const Ht = h, kt = Se;
const logger = getLoggerBuilder().detectUser().setApp("@nextcloud/vue").build();
const isRtl = isRTL();
const _sfc_main$1$2 = defineComponent({
  name: "NcPopoverTriggerProvider",
  provide() {
    return {
      "NcPopover:trigger:shown": () => this.shown,
      "NcPopover:trigger:attrs": () => this.triggerAttrs
    };
  },
  props: {
    /**
     * Is the popover currently shown
     */
    shown: {
      type: Boolean,
      required: true
    },
    /**
     * ARIA Role of the popup
     */
    popupRole: {
      type: String,
      default: void 0
    }
  },
  computed: {
    triggerAttrs() {
      return {
        "aria-haspopup": this.popupRole,
        "aria-expanded": this.shown.toString()
      };
    }
  },
  render() {
    return this.$slots.default?.({
      attrs: this.triggerAttrs
    });
  }
});
const ncPopover = "_ncPopover_HjJ88";
const style0$1 = {
  "material-design-icon": "_material-design-icon_FKPyJ",
  ncPopover
};
const theme = "nc-popover-9";
Ht.themes[theme] = structuredClone(Ht.themes.dropdown);
const _sfc_main$9 = {
  name: "NcPopover",
  components: {
    Dropdown: kt,
    NcPopoverTriggerProvider: _sfc_main$1$2
  },
  props: {
    /**
     * Element to use for calculating the popper boundary (size and position).
     * Either a query string or the actual HTMLElement.
     */
    boundary: {
      type: [String, Object],
      default: ""
    },
    /**
     * Automatically hide the popover on click outside.
     *
     * @deprecated Use `no-close-on-click-outside` instead (inverted value)
     */
    closeOnClickOutside: {
      type: Boolean,
      // eslint-disable-next-line vue/no-boolean-default
      default: true
    },
    /**
     * Disable the automatic popover hide on click outside.
     */
    noCloseOnClickOutside: {
      type: Boolean,
      default: false
    },
    /**
     * Container where to mount the popover.
     * Either a select query or `false` to mount to the parent node.
     */
    container: {
      type: [Boolean, String],
      default: "body"
    },
    /**
     * Delay for showing or hiding the popover.
     *
     * Can either be a number or an object to configure different delays (`{ show: number, hide: number }`).
     */
    delay: {
      type: [Number, Object],
      default: 0
    },
    /**
     * Disable the popover focus trap.
     */
    noFocusTrap: {
      type: Boolean,
      default: false
    },
    /**
     * Where to place the popover.
     *
     * This consists of the vertical placement and the horizontal placement.
     * E.g. `bottom` will place the popover on the bottom of the trigger (horizontally centered),
     * while `buttom-start` will horizontally align the popover on the logical start (e.g. for LTR layout on the left.).
     * The `start` or `end` placement will align the popover on the left or right side or the trigger element.
     *
     * @type {'auto'|'auto-start'|'auto-end'|'top'|'top-start'|'top-end'|'bottom'|'bottom-start'|'bottom-end'|'start'|'end'}
     */
    placement: {
      type: String,
      default: "bottom"
    },
    /**
     * Class to be applied to the popover base
     */
    popoverBaseClass: {
      type: String,
      default: ""
    },
    /**
     * Events that trigger the popover on the popover container itself.
     * This is useful if you set `triggers` to `hover` and also want the popover to stay open while hovering the popover itself.
     *
     * It is possible to also pass an object to define different triggers for hide and show `{ show: ['hover'], hide: ['click'] }`.
     */
    popoverTriggers: {
      type: [Array, Object],
      default: null
    },
    /**
     * Popup role
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Attributes/aria-haspopup#values
     */
    popupRole: {
      type: String,
      default: void 0,
      validator: (value) => ["menu", "listbox", "tree", "grid", "dialog", "true"].includes(value)
    },
    /**
     * Set element to return focus to after focus trap deactivation
     *
     * @type {SetReturnFocus}
     */
    setReturnFocus: {
      default: void 0,
      type: [Boolean, HTMLElement, SVGElement, String, Function]
    },
    /**
     * Show or hide the popper
     */
    shown: {
      type: Boolean,
      default: false
    },
    /**
     * Events that trigger the popover.
     *
     * If you pass an empty array then only the `shown` prop can control the popover state.
     * Following events are available:
     * - `'hover'`
     * - `'click'`
     * - `'focus'`
     * - `'touch'`
     *
     * It is also possible to pass an object to have different events for show and hide:
     * `{ hide: ['click'], show: ['click', 'hover'] }`
     */
    triggers: {
      type: [Array, Object],
      default: () => ["click"]
    }
  },
  emits: [
    "afterShow",
    "afterHide",
    "update:shown"
  ],
  setup() {
    return {
      theme
    };
  },
  data() {
    return {
      internalShown: this.shown
    };
  },
  computed: {
    popperTriggers() {
      if (this.popoverTriggers && Array.isArray(this.popoverTriggers)) {
        return this.popoverTriggers;
      }
      return void 0;
    },
    popperHideTriggers() {
      if (this.popoverTriggers && typeof this.popoverTriggers === "object") {
        return this.popoverTriggers.hide;
      }
      return void 0;
    },
    popperShowTriggers() {
      if (this.popoverTriggers && typeof this.popoverTriggers === "object") {
        return this.popoverTriggers.show;
      }
      return void 0;
    },
    internalTriggers() {
      if (this.triggers && Array.isArray(this.triggers)) {
        return this.triggers;
      }
      return void 0;
    },
    hideTriggers() {
      if (this.triggers && typeof this.triggers === "object") {
        return this.triggers.hide;
      }
      return void 0;
    },
    showTriggers() {
      if (this.triggers && typeof this.triggers === "object") {
        return this.triggers.show;
      }
      return void 0;
    },
    internalPlacement() {
      if (this.placement === "start") {
        return isRtl ? "right" : "left";
      } else if (this.placement === "end") {
        return isRtl ? "left" : "right";
      }
      return this.placement;
    }
  },
  watch: {
    shown(value) {
      this.internalShown = value;
    },
    internalShown(value) {
      this.$emit("update:shown", value);
    }
  },
  mounted() {
    this.checkTriggerA11y();
  },
  beforeUnmount() {
    this.clearFocusTrap();
    this.clearEscapeStopPropagation();
  },
  methods: {
    /**
     * Check if the trigger has all required a11y attributes.
     * Important to check custom trigger button.
     */
    checkTriggerA11y() {
      if (window.OC?.debug) {
        const triggerContainer = this.getPopoverTriggerContainerElement();
        const requiredTriggerButton = triggerContainer.querySelector("[aria-expanded]");
        if (!requiredTriggerButton) {
          warn("It looks like you are using a custom button as a <NcPopover> or other popover #trigger. If you are not using <NcButton> as a trigger, you need to bind attrs from the #trigger slot props to your custom button. See <NcPopover> docs for an example.");
        }
      }
    },
    /**
     * Remove incorrect aria-describedby attribute from the trigger.
     *
     * @see https://github.com/Akryum/floating-vue/blob/8d4f7125aae0e3ea00ba4093d6d2001ab15058f1/packages/floating-vue/src/components/Popper.ts#L734
     */
    removeFloatingVueAriaDescribedBy() {
      const triggerContainer = this.getPopoverTriggerContainerElement();
      const triggerElements = triggerContainer.querySelectorAll("[data-popper-shown]");
      for (const el of triggerElements) {
        el.removeAttribute("aria-describedby");
      }
    },
    /**
     * @return {HTMLElement|undefined}
     */
    getPopoverContentElement() {
      return this.$refs.popover?.$refs.popperContent?.$el;
    },
    /**
     * @return {HTMLElement|undefined}
     */
    getPopoverTriggerContainerElement() {
      return this.$refs.popover?.$refs.popper?.$refs.reference;
    },
    /**
     * Add focus trap for accessibility.
     */
    async useFocusTrap() {
      await this.$nextTick();
      if (this.noFocusTrap) {
        return;
      }
      const el = this.getPopoverContentElement();
      el.tabIndex = -1;
      if (!el) {
        return;
      }
      this.$focusTrap = createFocusTrap(el, {
        // Prevents to lose focus using esc key
        // Focus will be release when popover be hide
        escapeDeactivates: false,
        allowOutsideClick: true,
        setReturnFocus: this.setReturnFocus,
        trapStack: getTrapStack(),
        fallBackFocus: el
      });
      this.$focusTrap.activate();
    },
    /**
     * Remove focus trap
     *
     * @param {object} options The configuration options for focusTrap
     */
    clearFocusTrap(options2 = {}) {
      try {
        this.$focusTrap?.deactivate(options2);
        this.$focusTrap = null;
      } catch (error) {
        logger.warn("[NcPopover] Failed to clear focus trap", { error });
      }
    },
    /**
     * Add stopPropagation for Escape.
     * It prevents global Escape handling after closing popover.
     *
     * Manual event handling is used here instead of v-on because there is no direct access to the node.
     * Alternative - wrap <template #popover> in a div wrapper.
     */
    addEscapeStopPropagation() {
      const el = this.getPopoverContentElement();
      el?.addEventListener("keydown", this.stopKeydownEscapeHandler);
    },
    /**
     * Remove stop Escape handler
     */
    clearEscapeStopPropagation() {
      const el = this.getPopoverContentElement();
      el?.removeEventListener("keydown", this.stopKeydownEscapeHandler);
    },
    /**
     * @param {KeyboardEvent} event - native keydown event
     */
    stopKeydownEscapeHandler(event) {
      if (event.type === "keydown" && event.key === "Escape") {
        event.stopPropagation();
      }
    },
    async afterShow() {
      this.getPopoverContentElement().addEventListener("transitionend", () => {
        this.$emit("afterShow");
      }, { once: true, passive: true });
      this.removeFloatingVueAriaDescribedBy();
      await this.$nextTick();
      await this.useFocusTrap();
      this.addEscapeStopPropagation();
    },
    afterHide() {
      this.getPopoverContentElement()?.addEventListener("transitionend", () => {
        this.$emit("afterHide");
      }, { once: true, passive: true });
      this.clearFocusTrap();
      this.clearEscapeStopPropagation();
    }
  }
};
function _sfc_render$8(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcPopoverTriggerProvider = resolveComponent("NcPopoverTriggerProvider");
  const _component_Dropdown = resolveComponent("Dropdown");
  return openBlock(), createBlock(_component_Dropdown, {
    ref: "popover",
    shown: $data.internalShown,
    "onUpdate:shown": [
      _cache[0] || (_cache[0] = ($event) => $data.internalShown = $event),
      _cache[1] || (_cache[1] = ($event) => $data.internalShown = $event)
    ],
    arrowPadding: 10,
    autoHide: !$props.noCloseOnClickOutside && $props.closeOnClickOutside,
    boundary: $props.boundary || void 0,
    container: $props.container,
    delay: $props.delay,
    distance: 10,
    handleResize: "",
    noAutoFocus: true,
    placement: $options.internalPlacement,
    popperClass: [_ctx.$style.ncPopover, $props.popoverBaseClass],
    popperTriggers: $options.popperTriggers,
    popperHideTriggers: $options.popperHideTriggers,
    popperShowTriggers: $options.popperShowTriggers,
    theme: $setup.theme,
    triggers: $options.internalTriggers,
    hideTriggers: $options.hideTriggers,
    showTriggers: $options.showTriggers,
    onApplyShow: $options.afterShow,
    onApplyHide: $options.afterHide
  }, {
    popper: withCtx((slotProps) => [
      renderSlot(_ctx.$slots, "default", normalizeProps(guardReactiveProps(slotProps)))
    ]),
    default: withCtx(() => [
      createVNode(_component_NcPopoverTriggerProvider, {
        shown: $data.internalShown,
        popupRole: $props.popupRole
      }, {
        default: withCtx((slotProps) => [
          renderSlot(_ctx.$slots, "trigger", normalizeProps(guardReactiveProps(slotProps)))
        ]),
        _: 3
      }, 8, ["shown", "popupRole"])
    ]),
    _: 3
  }, 8, ["shown", "autoHide", "boundary", "container", "delay", "placement", "popperClass", "popperTriggers", "popperHideTriggers", "popperShowTriggers", "theme", "triggers", "hideTriggers", "showTriggers", "onApplyShow", "onApplyHide"]);
}
const cssModules$1 = {
  "$style": style0$1
};
const NcPopover = /* @__PURE__ */ _export_sfc$1(_sfc_main$9, [["render", _sfc_render$8], ["__cssModules", cssModules$1]]);
const NC_ACTIONS_IS_SEMANTIC_MENU = /* @__PURE__ */ Symbol.for("NcActions:isSemanticMenu");
const NC_ACTIONS_CLOSE_MENU = /* @__PURE__ */ Symbol.for("NcActions:closeMenu");
const _sfc_main$1$1 = {
  name: "DotsHorizontalIcon",
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
const _hoisted_1$7 = ["aria-hidden", "aria-label"];
const _hoisted_2$6 = ["fill", "width", "height"];
const _hoisted_3$6 = { d: "M16,12A2,2 0 0,1 18,10A2,2 0 0,1 20,12A2,2 0 0,1 18,14A2,2 0 0,1 16,12M10,12A2,2 0 0,1 12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12M4,12A2,2 0 0,1 6,10A2,2 0 0,1 8,12A2,2 0 0,1 6,14A2,2 0 0,1 4,12Z" };
const _hoisted_4$5 = { key: 0 };
function _sfc_render$7(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon dots-horizontal-icon",
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
      createBaseVNode("path", _hoisted_3$6, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$5, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$6))
  ], 16, _hoisted_1$7);
}
const IconDotsHorizontal = /* @__PURE__ */ _export_sfc$1(_sfc_main$1$1, [["render", _sfc_render$7]]);
register(t4);
function isSlotPopulated(vnodes) {
  return Array.isArray(vnodes) && vnodes.some((node) => {
    if (node === null) {
      return false;
    } else if (typeof node === "object") {
      const vnode = node;
      if (vnode.type === Comment) {
        return false;
      } else if (vnode.type === Fragment && !isSlotPopulated(vnode.children)) {
        return false;
      } else if (vnode.type === Text && !vnode.children.trim()) {
        return false;
      }
    }
    return true;
  });
}
const focusableSelector = ".focusable";
const _sfc_main$8 = {
  name: "NcActions",
  components: {
    NcButton,
    NcPopover
  },
  provide() {
    return {
      /**
       * NcActions can be used as:
       * - Application menu (has menu role)
       * - Navigation (has no specific role, should be used an element with navigation role)
       * - Popover with plain text or text inputs (has no specific role)
       * Depending on the usage (used items), the menu and its items should have different roles for a11y.
       * Provide the role for NcAction* components in the NcActions content.
       *
       * @type {import('vue').ComputedRef<boolean>}
       */
      [NC_ACTIONS_IS_SEMANTIC_MENU]: computed(() => this.actionsMenuSemanticType === "menu"),
      [NC_ACTIONS_CLOSE_MENU]: this.closeMenu
    };
  },
  props: {
    /**
     * Specify the open state of the popover menu
     */
    open: {
      type: Boolean,
      default: false
    },
    /**
     * This disables the internal open management,
     * so the actions menu only respects the `open` prop.
     * This is e.g. necessary for the NcAvatar component
     * to only open the actions menu after loading it's entries has finished.
     */
    manualOpen: {
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
     * Force the name to show for single actions
     */
    forceName: {
      type: Boolean,
      default: false
    },
    /**
     * Specify the menu name
     */
    menuName: {
      type: String,
      default: null
    },
    /**
     * Apply primary styling for this menu
     */
    primary: {
      type: Boolean,
      default: false
    },
    /**
     * Icon to show for the toggle menu button
     * when more than one action is inside the actions component.
     * Only replace the default three-dot icon if really necessary.
     */
    defaultIcon: {
      type: String,
      default: ""
    },
    /**
     * Aria label for the actions menu.
     *
     * If `menuName` is defined this will not be used to prevent
     * any accessible name conflicts. This ensures that the
     * element can be activated via voice input.
     */
    ariaLabel: {
      type: String,
      default: t("Actions")
    },
    /**
     * Wanted direction of the menu
     */
    placement: {
      type: String,
      default: "bottom"
    },
    /**
     * DOM element for the actions' popover boundaries
     */
    boundariesElement: {
      type: Element,
      default: () => document.getElementById("content-vue") ?? document.querySelector("body")
    },
    /**
     * Selector for the actions' popover container
     */
    container: {
      type: [Boolean, String, Object, Element],
      default: "body"
    },
    /**
     * Disabled state of the main button (single action or menu toggle)
     */
    disabled: {
      type: Boolean,
      default: false
    },
    /**
     * Display x items inline out of the dropdown menu
     * Will be ignored if `forceMenu` is set
     */
    inline: {
      type: Number,
      default: 0
    },
    /**
     * Specifies the button variant used for trigger and single actions buttons.
     *
     * If left empty, the default button style will be applied.
     *
     * @since 8.23.0
     */
    variant: {
      type: String,
      validator(value) {
        return ["primary", "secondary", "tertiary", "tertiary-no-background", "tertiary-on-primary", "error", "warning", "success"].includes(value);
      },
      default: null
    },
    /**
     * Specify the size used for trigger and single actions buttons.
     *
     * If left empty, the default button size will be applied.
     */
    size: {
      type: String,
      default: "normal",
      validator(value) {
        return ["small", "normal", "large"].includes(value);
      }
    }
  },
  emits: [
    "click",
    "blur",
    "focus",
    "close",
    "closed",
    "open",
    "opened",
    "update:open"
  ],
  setup() {
    const randomId = createElementId();
    return {
      randomId
    };
  },
  data() {
    return {
      opened: this.open,
      focusIndex: 0,
      /**
       * @type {'menu'|'navigation'|'dialog'|'tooltip'|'unknown'}
       */
      actionsMenuSemanticType: "unknown"
    };
  },
  computed: {
    triggerButtonVariant() {
      return this.variant || (this.primary ? "primary" : this.menuName ? "secondary" : "tertiary");
    },
    /**
     * A11y roles and keyboard navigation configuration depending on the semantic type
     */
    config() {
      const configs = {
        menu: {
          popupRole: "menu",
          withArrowNavigation: true,
          withTabNavigation: false,
          withFocusTrap: false
        },
        navigation: {
          popupRole: void 0,
          withArrowNavigation: false,
          withTabNavigation: true,
          withFocusTrap: false
        },
        dialog: {
          popupRole: "dialog",
          withArrowNavigation: false,
          withTabNavigation: true,
          withFocusTrap: true
        },
        tooltip: {
          popupRole: void 0,
          withArrowNavigation: false,
          withTabNavigation: false,
          withFocusTrap: false
        },
        // Due to Vue limitations, we sometimes cannot determine the true type
        // As a fallback use both arrow navigation and focus trap
        unknown: {
          popupRole: void 0,
          role: void 0,
          withArrowNavigation: true,
          withTabNavigation: false,
          withFocusTrap: true
        }
      };
      return configs[this.actionsMenuSemanticType];
    },
    withFocusTrap() {
      return this.config.withFocusTrap;
    }
  },
  watch: {
    // Watch parent prop
    open(state) {
      if (state === this.opened) {
        return;
      }
      this.opened = state;
    },
    opened() {
      if (this.opened) {
        document.body.addEventListener("keydown", this.handleEscapePressed);
      } else {
        document.body.removeEventListener("keydown", this.handleEscapePressed);
      }
    }
  },
  created() {
    useTrapStackControl(() => this.opened, {
      disabled: () => this.config.withFocusTrap
    });
    if ("ariaHidden" in this.$attrs) {
      warn("[NcActions]: Do not set the ariaHidden attribute as the root element will inherit the incorrect aria-hidden.");
    }
  },
  methods: {
    /**
     * Get the name of the action component
     *
     * @param {import('vue').VNode} action - a vnode with a NcAction* component instance
     * @return {string} the name of the action component
     */
    getActionName(action) {
      return action?.type?.name;
    },
    /**
     * Do we have exactly one Action and
     * is it allowed as a standalone element?
     *
     * @param {import('vue').VNode} action The action to check
     * @return {boolean}
     */
    isValidSingleAction(action) {
      return ["NcActionButton", "NcActionLink", "NcActionRouter"].includes(this.getActionName(action));
    },
    isAction(action) {
      return this.getActionName(action)?.startsWith?.("NcAction");
    },
    /**
     * Check whether a icon prop value is an URL or not
     *
     * @param {string} url The icon prop value
     */
    isIconUrl(url) {
      try {
        return !!new URL(url, url.startsWith("/") ? window.location.origin : void 0);
      } catch {
        return false;
      }
    },
    // MENU STATE MANAGEMENT
    toggleMenu(state) {
      if (state) {
        this.openMenu();
      } else {
        this.closeMenu();
      }
    },
    openMenu() {
      if (this.opened) {
        return;
      }
      this.opened = true;
      this.$emit("update:open", true);
      this.$emit("open");
    },
    async closeMenu(returnFocus = true) {
      if (!this.opened) {
        return;
      }
      await this.$nextTick();
      this.opened = false;
      this.$refs.popover?.clearFocusTrap({ returnFocus });
      this.$emit("update:open", false);
      this.$emit("close");
      this.focusIndex = 0;
      if (returnFocus) {
        this.$refs.triggerButton?.$el.focus();
      }
    },
    /**
     * Called when popover is shown after the show delay
     */
    onOpened() {
      this.$nextTick(() => {
        this.focusFirstAction(null);
        this.$emit("opened");
      });
    },
    onClosed() {
      this.$emit("closed");
    },
    // MENU KEYS & FOCUS MANAGEMENT
    /**
     * @return {HTMLElement|null}
     */
    getCurrentActiveMenuItemElement() {
      return this.$refs.menu.querySelector("li.active");
    },
    /**
     * @return {NodeList<HTMLElement>}
     */
    getFocusableMenuItemElements() {
      return this.$refs.menu.querySelectorAll(focusableSelector);
    },
    /**
     * Dispatches the keydown listener to different handlers
     *
     * @param {object} event The keydown event
     */
    onKeydown(event) {
      if (event.key === "Tab") {
        if (this.config.withFocusTrap) {
          return;
        }
        if (!this.config.withTabNavigation) {
          this.closeMenu(true);
          return;
        }
        event.preventDefault();
        const focusList = this.getFocusableMenuItemElements();
        const focusIndex = [...focusList].indexOf(document.activeElement);
        if (focusIndex === -1) {
          return;
        }
        const newFocusIndex = event.shiftKey ? focusIndex - 1 : focusIndex + 1;
        if (newFocusIndex < 0 || newFocusIndex === focusList.length) {
          this.closeMenu(true);
        }
        this.focusIndex = newFocusIndex;
        this.focusAction();
        return;
      }
      if (this.config.withArrowNavigation) {
        if (event.key === "ArrowUp") {
          this.focusPreviousAction(event);
        }
        if (event.key === "ArrowDown") {
          this.focusNextAction(event);
        }
        if (event.key === "PageUp") {
          this.focusFirstAction(event);
        }
        if (event.key === "PageDown") {
          this.focusLastAction(event);
        }
      }
      this.handleEscapePressed(event);
    },
    onTriggerKeydown(event) {
      if (event.key === "Escape") {
        if (this.actionsMenuSemanticType === "tooltip") {
          this.closeMenu();
        }
      }
    },
    handleEscapePressed(event) {
      if (event.key === "Escape") {
        this.closeMenu();
        event.preventDefault();
      }
    },
    removeCurrentActive() {
      const currentActiveElement = this.$refs.menu.querySelector("li.active");
      if (currentActiveElement) {
        currentActiveElement.classList.remove("active");
      }
    },
    focusAction() {
      const focusElement = this.getFocusableMenuItemElements()[this.focusIndex];
      if (focusElement) {
        this.removeCurrentActive();
        const liMenuParent = focusElement.closest("li.action");
        focusElement.focus();
        if (liMenuParent) {
          liMenuParent.classList.add("active");
        }
      }
    },
    focusPreviousAction(event) {
      if (this.opened) {
        if (this.focusIndex === 0) {
          this.focusLastAction(event);
        } else {
          this.preventIfEvent(event);
          this.focusIndex = this.focusIndex - 1;
        }
        this.focusAction();
      }
    },
    focusNextAction(event) {
      if (this.opened) {
        const indexLength = this.getFocusableMenuItemElements().length - 1;
        if (this.focusIndex === indexLength) {
          this.focusFirstAction(event);
        } else {
          this.preventIfEvent(event);
          this.focusIndex = this.focusIndex + 1;
        }
        this.focusAction();
      }
    },
    focusFirstAction(event) {
      if (this.opened) {
        this.preventIfEvent(event);
        const firstCheckedIndex = [...this.getFocusableMenuItemElements()].findIndex((button) => {
          return button.getAttribute("aria-checked") === "true" && button.getAttribute("role") === "menuitemradio";
        });
        this.focusIndex = firstCheckedIndex > -1 ? firstCheckedIndex : 0;
        this.focusAction();
      }
    },
    focusLastAction(event) {
      if (this.opened) {
        this.preventIfEvent(event);
        this.focusIndex = this.getFocusableMenuItemElements().length - 1;
        this.focusAction();
      }
    },
    preventIfEvent(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
    },
    onFocus(event) {
      this.$emit("focus", event);
    },
    onBlur(event) {
      this.$emit("blur", event);
      if (this.actionsMenuSemanticType === "tooltip") {
        if (this.$refs.menu && this.getFocusableMenuItemElements().length === 0) {
          this.closeMenu(false);
        }
      }
    },
    onClick(event) {
      this.$emit("click", event);
    }
  },
  /**
   * The render function to display the component
   *
   * @return {object|undefined} The created VNode
   */
  render() {
    const actions = [];
    const findActions = (vnodes, actions2) => {
      vnodes.forEach((vnode) => {
        if (this.isAction(vnode)) {
          actions2.push(vnode);
          return;
        }
        if (vnode.type === Fragment) {
          findActions(vnode.children, actions2);
        }
      });
    };
    findActions(this.$slots.default?.(), actions);
    if (actions.length === 0) {
      return;
    }
    let validInlineActions = actions.filter(this.isValidSingleAction);
    if (this.forceMenu && validInlineActions.length > 0 && this.inline > 0) {
      warn("Specifying forceMenu will ignore any inline actions rendering.");
      validInlineActions = [];
    }
    const inlineActions = validInlineActions.slice(0, this.inline);
    const menuActions = actions.filter((action) => !inlineActions.includes(action));
    const menuItemsActions = ["NcActionButton", "NcActionButtonGroup", "NcActionCheckbox", "NcActionRadio"];
    const textInputActions = ["NcActionInput", "NcActionTextEditable"];
    const linkActions = ["NcActionLink", "NcActionRouter"];
    const hasTextInputAction = menuActions.some((action) => textInputActions.includes(this.getActionName(action)));
    const hasMenuItemAction = menuActions.some((action) => menuItemsActions.includes(this.getActionName(action)));
    const hasLinkAction = menuActions.some((action) => linkActions.includes(this.getActionName(action)));
    if (hasTextInputAction) {
      this.actionsMenuSemanticType = "dialog";
    } else if (hasMenuItemAction) {
      this.actionsMenuSemanticType = "menu";
    } else if (hasLinkAction) {
      this.actionsMenuSemanticType = "navigation";
    } else {
      const ncActions = actions.filter((action) => this.getActionName(action).startsWith("NcAction"));
      if (ncActions.length === actions.length) {
        this.actionsMenuSemanticType = "tooltip";
      } else {
        this.actionsMenuSemanticType = "unknown";
      }
    }
    const renderInlineAction = (action) => {
      const iconProp = action?.props?.icon;
      const icon = action?.children?.icon?.()?.[0] ?? (this.isIconUrl(iconProp) ? h$1("img", { class: "action-item__menutoggle__icon", src: iconProp, alt: "" }) : h$1("span", { class: ["icon", iconProp] }));
      const text = action?.children?.default?.()?.[0]?.children?.trim();
      const buttonText = this.forceName ? text : "";
      let title = action?.props?.title;
      if (!(this.forceName || title)) {
        title = text;
      }
      const propsToForward = { ...action?.props ?? {} };
      const type = ["submit", "reset"].includes(propsToForward.type) ? propsToForward.modelValue : "button";
      delete propsToForward.modelValue;
      delete propsToForward.type;
      return h$1(
        NcButton,
        mergeProps(
          propsToForward,
          {
            class: "action-item action-item--single",
            "aria-label": action?.props?.["aria-label"] || text,
            title,
            disabled: this.disabled || action?.props?.disabled,
            pressed: action?.props?.modelValue,
            size: this.size,
            type,
            // If it has a menuName, we use a secondary button
            variant: this.variant || (buttonText ? "secondary" : "tertiary"),
            onFocus: this.onFocus,
            onBlur: this.onBlur,
            // forward any pressed state from NcButton just like NcActionButton does
            "onUpdate:pressed": action?.props?.["onUpdate:modelValue"] ?? (() => {
            })
          }
        ),
        {
          default: () => buttonText,
          icon: () => icon
        }
      );
    };
    const renderActionsPopover = (actions2) => {
      const triggerIcon = isSlotPopulated(this.$slots.icon?.()) ? this.$slots.icon?.() : this.defaultIcon ? h$1("span", { class: ["icon", this.defaultIcon] }) : h$1(IconDotsHorizontal, { size: 20 });
      const triggerRandomId = `${this.randomId}-trigger`;
      return h$1(
        NcPopover,
        {
          ref: "popover",
          delay: 0,
          shown: this.opened,
          placement: this.placement,
          boundary: this.boundariesElement,
          autoBoundaryMaxSize: true,
          container: this.container,
          ...this.manualOpen && {
            triggers: []
          },
          noCloseOnClickOutside: this.manualOpen,
          popoverBaseClass: "action-item__popper",
          popupRole: this.config.popupRole,
          setReturnFocus: this.config.withFocusTrap ? this.$refs.triggerButton?.$el : void 0,
          noFocusTrap: !this.config.withFocusTrap,
          "onUpdate:shown": this.toggleMenu,
          onAfterShow: this.onOpened,
          onAfterClose: this.onClosed
        },
        {
          trigger: () => h$1(NcButton, {
            id: triggerRandomId,
            class: "action-item__menutoggle",
            disabled: this.disabled,
            size: this.size,
            variant: this.triggerButtonVariant,
            ref: "triggerButton",
            "aria-label": this.menuName ? null : this.ariaLabel,
            // 'aria-controls' should only present together with a valid aria-haspopup
            "aria-controls": this.opened && this.config.popupRole ? this.randomId : null,
            onFocus: this.onFocus,
            onBlur: this.onBlur,
            onClick: this.onClick,
            onKeydown: this.onTriggerKeydown
          }, {
            icon: () => triggerIcon,
            default: () => this.menuName
          }),
          default: () => h$1("div", {
            class: {
              open: this.opened
            },
            tabindex: "-1",
            onKeydown: this.onKeydown,
            ref: "menu"
          }, [
            h$1("ul", {
              id: this.randomId,
              tabindex: "-1",
              ref: "menuList",
              role: this.config.popupRole,
              // For most roles a label is required (dialog, menu), but also in general nothing speaks against labelling a list.
              // It is even recommended to do so.
              "aria-labelledby": triggerRandomId,
              "aria-modal": this.actionsMenuSemanticType === "dialog" ? "true" : void 0
            }, [
              actions2
            ])
          ])
        }
      );
    };
    if (actions.length === 1 && validInlineActions.length === 1 && !this.forceMenu) {
      return renderInlineAction(actions[0]);
    }
    this.$nextTick(() => {
      if (this.opened && this.$refs.menu) {
        const isAnyActive = this.$refs.menu.querySelector("li.active") || [];
        if (isAnyActive.length === 0) {
          this.focusFirstAction();
        }
      }
    });
    if (inlineActions.length > 0 && this.inline > 0) {
      return h$1(
        "div",
        {
          class: [
            "action-items",
            `action-item--${this.triggerButtonVariant}`
          ]
        },
        [
          // Render inline actions
          ...inlineActions.map(renderInlineAction),
          // render the rest within the popover menu
          menuActions.length > 0 ? h$1(
            "div",
            {
              class: [
                "action-item",
                {
                  "action-item--open": this.opened
                }
              ]
            },
            [renderActionsPopover(menuActions)]
          ) : null
        ]
      );
    }
    return h$1(
      "div",
      {
        class: [
          "action-item action-item--default-popover",
          `action-item--${this.triggerButtonVariant}`,
          {
            "action-item--open": this.opened
          }
        ]
      },
      [
        renderActionsPopover(actions)
      ]
    );
  }
};
const NcActions = /* @__PURE__ */ _export_sfc$1(_sfc_main$8, [["__scopeId", "data-v-5f7eed6b"]]);
register(t2);
const FEW_SECONDS_AGO = {
  long: t("a few seconds ago"),
  short: t("seconds ago"),
  // FOR TRANSLATORS: Shorter version of 'a few seconds ago'
  narrow: t("sec. ago")
  // FOR TRANSLATORS: If possible in your language an even shorter version of 'a few seconds ago'
};
function useFormatRelativeTime(timestamp = Date.now(), opts = {}) {
  let timeoutId;
  const date = computed(() => new Date(toValue(timestamp)));
  const options = computed(() => {
    const { language, relativeTime: relativeTime2, ignoreSeconds } = toValue(opts);
    return {
      ...language && { language },
      ...relativeTime2 && { relativeTime: relativeTime2 },
      ignoreSeconds: ignoreSeconds ? FEW_SECONDS_AGO[relativeTime2 || "long"] : false
    };
  });
  const relativeTime = ref("");
  watchEffect(() => updateRelativeTime());
  function updateRelativeTime() {
    relativeTime.value = formatRelativeTime(date.value, options.value);
    if (toValue(opts).update !== false) {
      const diff = Math.abs(Date.now() - new Date(toValue(timestamp)).getTime());
      const interval = diff > 12e4 || options.value.ignoreSeconds ? Math.min(diff / 60, 18e5) : 1e3;
      timeoutId = window.setTimeout(updateRelativeTime, interval);
    }
  }
  onUnmounted(() => timeoutId && window.clearTimeout(timeoutId));
  return readonly(relativeTime);
}
function useFormatTime(timestamp, opts) {
  const options = computed(() => ({
    locale: getCanonicalLocale(),
    format: { dateStyle: "short", timeStyle: "medium" },
    ...toValue(opts)
  }));
  const formatter = computed(() => new Intl.DateTimeFormat(options.value.locale, options.value.format));
  return computed(() => formatter.value.format(toValue(timestamp)));
}
window.OCP?.Accessibility?.disableKeyboardShortcuts?.();
function checkIfDarkTheme(el = document.body) {
  const backgroundInvertIfDark = window.getComputedStyle(el).getPropertyValue("--background-invert-if-dark");
  if (backgroundInvertIfDark !== void 0) {
    return backgroundInvertIfDark === "invert(100%)";
  }
  return false;
}
checkIfDarkTheme();
const isFullscreen = ref(checkIfIsFullscreen());
window.addEventListener("resize", () => {
  isFullscreen.value = checkIfIsFullscreen();
});
function checkIfIsFullscreen() {
  return window.outerHeight === window.screen.height;
}
register(t19, t36);
function loadState(app, key, fallback) {
  const selector = `#initial-state-${app}-${key}`;
  if (window._nc_initial_state?.has(selector)) {
    return window._nc_initial_state.get(selector);
  } else if (!window._nc_initial_state) {
    window._nc_initial_state = /* @__PURE__ */ new Map();
  }
  const elem = document.querySelector(selector);
  if (elem === null) {
    if (fallback !== void 0) {
      return fallback;
    }
    throw new Error(`Could not find initial state ${key} of ${app}`);
  }
  try {
    const parsedValue = JSON.parse(atob(elem.value));
    window._nc_initial_state.set(selector, parsedValue);
    return parsedValue;
  } catch (error) {
    console.error("[@nextcloud/initial-state] Could not parse initial state", { key, app, error });
    if (fallback !== void 0) {
      return fallback;
    }
    throw new Error(`Could not parse initial state ${key} of ${app}`, { cause: error });
  }
}
const svg = `<svg
	xmlns="http://www.w3.org/2000/svg"
	viewBox="0 0 24 12">
	<path d="M17,1H7A5,5 0 0,0 2,6 5,5 0 0,0 7,11H17A5,5 0 0,0 22,6 5,5 0 0,0 17,1Z" />
	<circle
		cy="6"
		r="3"
		fill="var(--color-main-background)" />
</svg>`;
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "NcIconToggleSwitch",
  props: {
    checked: { type: Boolean },
    size: { default: 34 },
    inline: { type: Boolean, default: false }
  },
  setup(__props) {
    useCssVars((_ctx) => ({
      "6bd152af": color.value,
      "16fd8ca9": cx.value
    }));
    const color = computed(() => __props.checked ? "var(--color-primary-element)" : "var(--color-text-maxcontrast)");
    const cx = computed(() => __props.checked ? "calc(17 / 24 * 100%)" : "calc(7 / 24 * 100%)");
    return (_ctx, _cache) => {
      return openBlock(), createBlock(NcIconSvgWrapper, {
        class: normalizeClass(_ctx.$style.iconToggleSwitch),
        svg,
        size: _ctx.size,
        inline: _ctx.inline
      }, null, 8, ["class", "size", "inline"]);
    };
  }
});
const iconToggleSwitch = "_iconToggleSwitch_WgcOx";
const style0 = {
  "material-design-icon": "_material-design-icon_ZYrc5",
  iconToggleSwitch
};
const cssModules = {
  "$style": style0
};
const NcIconToggleSwitch = /* @__PURE__ */ _export_sfc$1(_sfc_main$7, [["__cssModules", cssModules]]);
/*!
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
const INSIDE_RADIO_GROUP_KEY = /* @__PURE__ */ Symbol.for("insideRadioGroup");
function useInsideRadioGroup() {
  return inject(INSIDE_RADIO_GROUP_KEY, void 0);
}
const _sfc_main$6 = {
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
const _hoisted_1$6 = ["aria-hidden", "aria-label"];
const _hoisted_2$5 = ["fill", "width", "height"];
const _hoisted_3$5 = { d: "M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V19H5V5H19Z" };
const _hoisted_4$4 = { key: 0 };
function _sfc_render$6(_ctx, _cache, $props, $setup, $data, $options) {
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
      createBaseVNode("path", _hoisted_3$5, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$4, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$5))
  ], 16, _hoisted_1$6);
}
const CheckboxBlankOutline = /* @__PURE__ */ _export_sfc$1(_sfc_main$6, [["render", _sfc_render$6]]);
const _sfc_main$5 = {
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
const _hoisted_1$5 = ["aria-hidden", "aria-label"];
const _hoisted_2$4 = ["fill", "width", "height"];
const _hoisted_3$4 = { d: "M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" };
const _hoisted_4$3 = { key: 0 };
function _sfc_render$5(_ctx, _cache, $props, $setup, $data, $options) {
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
      createBaseVNode("path", _hoisted_3$4, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$3, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$4))
  ], 16, _hoisted_1$5);
}
const CheckboxMarked = /* @__PURE__ */ _export_sfc$1(_sfc_main$5, [["render", _sfc_render$5]]);
const _sfc_main$4 = {
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
const _hoisted_1$4 = ["aria-hidden", "aria-label"];
const _hoisted_2$3 = ["fill", "width", "height"];
const _hoisted_3$3 = { d: "M17,13H7V11H17M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" };
const _hoisted_4$2 = { key: 0 };
function _sfc_render$4(_ctx, _cache, $props, $setup, $data, $options) {
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
      createBaseVNode("path", _hoisted_3$3, [
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$2, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$3))
  ], 16, _hoisted_1$4);
}
const MinusBox = /* @__PURE__ */ _export_sfc$1(_sfc_main$4, [["render", _sfc_render$4]]);
const _sfc_main$3 = {
  name: "RadioboxBlankIcon",
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
const _hoisted_2$2 = ["fill", "width", "height"];
const _hoisted_3$2 = { d: "M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" };
const _hoisted_4$1 = { key: 0 };
function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon radiobox-blank-icon",
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
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4$1, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$2))
  ], 16, _hoisted_1$3);
}
const RadioboxBlank = /* @__PURE__ */ _export_sfc$1(_sfc_main$3, [["render", _sfc_render$3]]);
const _sfc_main$2 = {
  name: "RadioboxMarkedIcon",
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
const _hoisted_2$1 = ["fill", "width", "height"];
const _hoisted_3$1 = { d: "M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7Z" };
const _hoisted_4 = { key: 0 };
function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", mergeProps(_ctx.$attrs, {
    "aria-hidden": $props.title ? null : "true",
    "aria-label": $props.title,
    class: "material-design-icon radiobox-marked-icon",
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
        $props.title ? (openBlock(), createElementBlock("title", _hoisted_4, toDisplayString($props.title), 1)) : createCommentVNode("", true)
      ])
    ], 8, _hoisted_2$1))
  ], 16, _hoisted_1$2);
}
const RadioboxMarked = /* @__PURE__ */ _export_sfc$1(_sfc_main$2, [["render", _sfc_render$2]]);
const TYPE_CHECKBOX = "checkbox";
const TYPE_RADIO = "radio";
const TYPE_SWITCH = "switch";
const TYPE_BUTTON = "button";
const _sfc_main$1 = {
  name: "NcCheckboxContent",
  components: {
    NcLoadingIcon,
    NcIconToggleSwitch
  },
  props: {
    /**
     * Class for the icon element
     */
    iconClass: {
      type: [String, Object],
      default: null
    },
    /**
     * Class for the text element
     */
    textClass: {
      type: [String, Object],
      default: null
    },
    /**
     * Type of the input. checkbox, radio, switch, or button.
     *
     * Only use button when used in a `tablist` container and the
     * `tab` role is set.
     *
     * @type {'checkbox'|'radio'|'switch'|'button'}
     */
    type: {
      type: String,
      default: "checkbox",
      validator: (type) => [
        TYPE_CHECKBOX,
        TYPE_RADIO,
        TYPE_SWITCH,
        TYPE_BUTTON
      ].includes(type)
    },
    /**
     * Toggle the alternative button style
     */
    buttonVariant: {
      type: Boolean,
      default: false
    },
    /**
     * True if the entry is checked
     */
    isChecked: {
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
     * Loading state
     */
    loading: {
      type: Boolean,
      default: false
    },
    /**
     * Icon size
     */
    iconSize: {
      type: Number,
      default: 24
    },
    /**
     * Label id attribute
     */
    labelId: {
      type: String,
      required: true
    },
    /**
     * Description id attribute
     */
    descriptionId: {
      type: String,
      required: true
    }
  },
  computed: {
    isButtonType() {
      return this.type === TYPE_BUTTON;
    },
    isSwitchType() {
      return this.type === TYPE_SWITCH;
    },
    /**
     * Returns the proper Material icon depending on the select case
     *
     * @return {object}
     */
    checkboxRadioIconElement() {
      if (this.type === TYPE_RADIO) {
        if (this.isChecked) {
          return RadioboxMarked;
        }
        return RadioboxBlank;
      }
      if (this.indeterminate) {
        return MinusBox;
      }
      if (this.isChecked) {
        return CheckboxMarked;
      }
      return CheckboxBlankOutline;
    }
  }
};
const _hoisted_1$1 = {
  key: 0,
  class: "checkbox-content__wrapper"
};
const _hoisted_2 = ["id"];
const _hoisted_3 = ["id"];
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcLoadingIcon = resolveComponent("NcLoadingIcon");
  const _component_NcIconToggleSwitch = resolveComponent("NcIconToggleSwitch");
  return openBlock(), createElementBlock("span", {
    class: normalizeClass(["checkbox-content", {
      ["checkbox-content-" + $props.type]: true,
      "checkbox-content--button-variant": $props.buttonVariant,
      "checkbox-content--has-text": !!_ctx.$slots.default
    }])
  }, [
    createBaseVNode("span", {
      class: normalizeClass(["checkbox-content__icon", {
        "checkbox-content__icon--checked": $props.isChecked,
        "checkbox-content__icon--has-description": !$options.isButtonType && _ctx.$slots.description,
        [$props.iconClass]: true
      }]),
      "aria-hidden": true,
      inert: ""
    }, [
      renderSlot(_ctx.$slots, "icon", {
        checked: $props.isChecked,
        loading: $props.loading
      }, () => [
        $props.loading ? (openBlock(), createBlock(_component_NcLoadingIcon, { key: 0 })) : $options.isSwitchType ? (openBlock(), createBlock(_component_NcIconToggleSwitch, {
          key: 1,
          checked: $props.isChecked,
          size: $props.iconSize,
          inline: ""
        }, null, 8, ["checked", "size"])) : !$props.buttonVariant ? (openBlock(), createBlock(resolveDynamicComponent($options.checkboxRadioIconElement), {
          key: 2,
          size: $props.iconSize
        }, null, 8, ["size"])) : createCommentVNode("", true)
      ], true)
    ], 2),
    _ctx.$slots.default || _ctx.$slots.description ? (openBlock(), createElementBlock("span", _hoisted_1$1, [
      _ctx.$slots.default ? (openBlock(), createElementBlock("span", {
        key: 0,
        id: $props.labelId,
        class: normalizeClass(["checkbox-content__text", $props.textClass])
      }, [
        renderSlot(_ctx.$slots, "default", {}, void 0, true)
      ], 10, _hoisted_2)) : createCommentVNode("", true),
      !$options.isButtonType && _ctx.$slots.description ? (openBlock(), createElementBlock("span", {
        key: 1,
        id: $props.descriptionId,
        class: "checkbox-content__description"
      }, [
        renderSlot(_ctx.$slots, "description", {}, void 0, true)
      ], 8, _hoisted_3)) : createCommentVNode("", true)
    ])) : createCommentVNode("", true)
  ], 2);
}
const NcCheckboxContent = /* @__PURE__ */ _export_sfc$1(_sfc_main$1, [["render", _sfc_render$1], ["__scopeId", "data-v-a060196e"]]);
register();
const _sfc_main = {
  name: "NcCheckboxRadioSwitch",
  components: {
    NcCheckboxContent
  },
  // We need to pass attributes to the input element
  inheritAttrs: false,
  props: {
    /**
     * Unique id attribute of the input
     */
    id: {
      type: String,
      default: () => "checkbox-radio-switch-" + createElementId(),
      validator: (id) => id.trim() !== ""
    },
    /**
     * Unique id attribute of the wrapper element
     */
    wrapperId: {
      type: String,
      default: null
    },
    /**
     * Input name. Required for radio, optional for checkbox, and ignored
     * for button.
     */
    name: {
      type: String,
      default: null
    },
    /**
     * Required if no text is set.
     * The aria-label is forwarded to the input or button.
     */
    ariaLabel: {
      type: String,
      default: ""
    },
    /**
     * Type of the input. checkbox, radio, switch, or button.
     *
     * Only use button when used in a `tablist` container and the
     * `tab` role is set.
     *
     * @type {'checkbox'|'radio'|'switch'|'button'}
     */
    type: {
      type: String,
      default: "checkbox",
      validator: (type) => [
        TYPE_CHECKBOX,
        TYPE_RADIO,
        TYPE_SWITCH,
        TYPE_BUTTON
      ].includes(type)
    },
    /**
     * Toggle the alternative button style
     *
     * @deprecated - Use `NcRadioGroup` instead
     */
    buttonVariant: {
      type: Boolean,
      default: false
    },
    /**
     * Are the elements are all direct siblings?
     * If so they will be grouped horizontally or vertically
     *
     * @type {'no'|'horizontal'|'vertical'}
     * @deprecated - Use `NcRadioGroup` instead
     */
    buttonVariantGrouped: {
      type: String,
      default: "no",
      validator: (v) => ["no", "vertical", "horizontal"].includes(v)
    },
    /**
     * Checked state. To be used with `v-model:value`
     */
    modelValue: {
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
     * Required state
     */
    required: {
      type: Boolean,
      default: false
    },
    /**
     * Loading state
     */
    loading: {
      type: Boolean,
      default: false
    },
    /**
     * Wrapping element tag
     *
     * When `type` is set to `button` this will be ignored
     *
     * Defaults to `span`
     */
    wrapperElement: {
      type: String,
      default: null
    },
    /**
     * The class(es) to pass to the wrapper / root element of the component
     */
    class: {
      type: [String, Array, Object],
      default: ""
    },
    /**
     * The style to pass to the wrapper / root element of the component
     */
    style: {
      type: [String, Array, Object],
      default: ""
    },
    /**
     * Description
     *
     * This is unsupported when using button has type.
     */
    description: {
      type: String,
      default: null
    }
  },
  emits: ["update:modelValue"],
  setup(props, { emit }) {
    const radioGroup = useInsideRadioGroup();
    onMounted(() => radioGroup?.value.register(false));
    const internalType = computed(() => radioGroup?.value ? TYPE_RADIO : props.type);
    const internalModelValue = computed({
      get() {
        if (radioGroup?.value) {
          return radioGroup.value.modelValue;
        }
        return props.modelValue;
      },
      set(value) {
        if (radioGroup?.value) {
          radioGroup.value.onUpdate(value);
        } else {
          emit("update:modelValue", value);
        }
      }
    });
    return {
      internalType,
      internalModelValue,
      labelId: createElementId(),
      descriptionId: createElementId()
    };
  },
  computed: {
    isButtonType() {
      return this.internalType === TYPE_BUTTON;
    },
    computedWrapperElement() {
      if (this.isButtonType) {
        return "button";
      }
      if (this.wrapperElement !== null) {
        return this.wrapperElement;
      }
      return "span";
    },
    listeners() {
      if (this.isButtonType) {
        return {
          click: this.onToggle
        };
      }
      return {
        change: this.onToggle
      };
    },
    iconSize() {
      return this.internalType === TYPE_SWITCH ? 36 : 20;
    },
    cssIconSize() {
      return this.iconSize + "px";
    },
    cssIconHeight() {
      return this.internalType === TYPE_SWITCH ? "16px" : this.cssIconSize;
    },
    /**
     * Return the input type.
     * Switch is not an official type
     *
     * @return {string}
     */
    inputType() {
      const nativeTypes = [
        TYPE_CHECKBOX,
        TYPE_RADIO,
        TYPE_BUTTON
      ];
      if (nativeTypes.includes(this.internalType)) {
        return this.internalType;
      }
      return TYPE_CHECKBOX;
    },
    /**
     * Check if that entry is checked
     * If value is defined, we use that as the checked value
     * If not, we expect true/false in this.checked
     *
     * @return {boolean}
     */
    isChecked() {
      if (this.value !== null) {
        if (Array.isArray(this.internalModelValue)) {
          return [...this.internalModelValue].indexOf(this.value) > -1;
        }
        return this.internalModelValue === this.value;
      }
      return this.internalModelValue === true;
    },
    hasIndeterminate() {
      return [
        TYPE_CHECKBOX,
        TYPE_RADIO
      ].includes(this.inputType);
    }
  },
  mounted() {
    if (this.name && this.internalType === TYPE_CHECKBOX) {
      if (!Array.isArray(this.internalModelValue)) {
        throw new Error("When using groups of checkboxes, the updated value will be an array.");
      }
    }
    if (this.name && this.internalType === TYPE_SWITCH) {
      throw new Error("Switches are not made to be used for data sets. Please use checkboxes instead.");
    }
    if (typeof this.internalModelValue !== "boolean" && this.internalType === TYPE_SWITCH) {
      throw new Error("Switches can only be used with boolean as modelValue prop.");
    }
  },
  methods: {
    t,
    n: n$1,
    onToggle(event) {
      if (this.disabled || event.target.tagName.toLowerCase() === "a") {
        return;
      }
      if (this.internalType === TYPE_RADIO) {
        this.internalModelValue = this.value;
        return;
      }
      if (this.internalType === TYPE_SWITCH) {
        this.internalModelValue = !this.isChecked;
        return;
      }
      if (typeof this.internalModelValue === "boolean") {
        this.internalModelValue = !this.internalModelValue;
        return;
      }
      const values = this.getInputsSet().filter((input) => input.checked).map((input) => input.value);
      if (values.includes(this.value)) {
        this.internalModelValue = values.filter((v) => v !== this.value);
      } else {
        this.internalModelValue = [...values, this.value];
      }
    },
    /**
     * Get the input set based on this name
     *
     * @return {Node[]}
     */
    getInputsSet() {
      return [...document.getElementsByName(this.name)];
    }
  }
};
const __injectCSSVars__ = () => {
  useCssVars((_ctx) => ({
    "1d6eb36d": _ctx.cssIconSize,
    "698a3993": _ctx.cssIconHeight
  }));
};
const __setup__ = _sfc_main.setup;
_sfc_main.setup = __setup__ ? (props, ctx) => {
  __injectCSSVars__();
  return __setup__(props, ctx);
} : __injectCSSVars__;
const _hoisted_1 = ["id", "aria-labelledby", "aria-describedby", "aria-label", "disabled", "type", "value", "checked", ".indeterminate", "required", "name"];
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_NcCheckboxContent = resolveComponent("NcCheckboxContent");
  return openBlock(), createBlock(resolveDynamicComponent($options.computedWrapperElement), mergeProps({
    id: $props.wrapperId ?? ($options.isButtonType ? $props.id : null),
    "aria-label": $options.isButtonType && $props.ariaLabel ? $props.ariaLabel : void 0,
    class: ["checkbox-radio-switch", [
      _ctx.$props.class,
      {
        ["checkbox-radio-switch-" + $setup.internalType]: $setup.internalType,
        "checkbox-radio-switch--checked": $options.isChecked,
        "checkbox-radio-switch--disabled": $props.disabled,
        "checkbox-radio-switch--indeterminate": $options.hasIndeterminate ? $props.indeterminate : false,
        "checkbox-radio-switch--button-variant": $props.buttonVariant,
        "checkbox-radio-switch--button-variant-v-grouped": $props.buttonVariant && $props.buttonVariantGrouped === "vertical",
        "checkbox-radio-switch--button-variant-h-grouped": $props.buttonVariant && $props.buttonVariantGrouped === "horizontal",
        "button-vue": $options.isButtonType
      }
    ]],
    style: $props.style,
    type: $options.isButtonType ? "button" : null
  }, $options.isButtonType ? _ctx.$attrs : {}, toHandlers($options.isButtonType ? $options.listeners : {})), {
    default: withCtx(() => [
      !$options.isButtonType ? (openBlock(), createElementBlock("input", mergeProps({
        key: 0,
        id: $props.id,
        "aria-labelledby": !$options.isButtonType && !$props.ariaLabel ? $setup.labelId : null,
        "aria-describedby": !$options.isButtonType && ($props.description || _ctx.$slots.description) ? $setup.descriptionId : null,
        "aria-label": $props.ariaLabel || void 0,
        class: "checkbox-radio-switch__input",
        disabled: $props.disabled,
        type: $options.inputType,
        value: $props.value,
        checked: $options.isChecked,
        ".indeterminate": $options.hasIndeterminate ? $props.indeterminate : null,
        required: $props.required,
        name: $props.name
      }, _ctx.$attrs, toHandlers($options.listeners, true)), null, 48, _hoisted_1)) : createCommentVNode("", true),
      createVNode(_component_NcCheckboxContent, {
        id: !$options.isButtonType ? `${$props.id}-label` : void 0,
        class: "checkbox-radio-switch__content",
        iconClass: "checkbox-radio-switch__icon",
        textClass: "checkbox-radio-switch__text",
        type: $setup.internalType,
        indeterminate: $options.hasIndeterminate ? $props.indeterminate : false,
        buttonVariant: $props.buttonVariant,
        isChecked: $options.isChecked,
        loading: $props.loading,
        labelId: $setup.labelId,
        descriptionId: $setup.descriptionId,
        iconSize: $options.iconSize,
        onClick: $options.onToggle
      }, createSlots({
        icon: withCtx(() => [
          renderSlot(_ctx.$slots, "icon", {}, void 0, true)
        ]),
        _: 2
      }, [
        _ctx.$slots.description || $props.description ? {
          name: "description",
          fn: withCtx(() => [
            renderSlot(_ctx.$slots, "description", {}, () => [
              createTextVNode(toDisplayString($props.description), 1)
            ], true)
          ]),
          key: "0"
        } : void 0,
        !!_ctx.$slots.default ? {
          name: "default",
          fn: withCtx(() => [
            renderSlot(_ctx.$slots, "default", {}, void 0, true)
          ]),
          key: "1"
        } : void 0
      ]), 1032, ["id", "type", "indeterminate", "buttonVariant", "isChecked", "loading", "labelId", "descriptionId", "iconSize", "onClick"])
    ]),
    _: 3
  }, 16, ["id", "aria-label", "class", "style", "type"]);
}
const NcCheckboxRadioSwitch = /* @__PURE__ */ _export_sfc$1(_sfc_main, [["render", _sfc_render], ["__scopeId", "data-v-6808cde4"]]);
export {
  t24 as $,
  generateUrl as A,
  NcCheckboxRadioSwitch as B,
  createRouter as C,
  createWebHistory as D,
  t26 as E,
  t33 as F,
  getGettextBuilder as G,
  checkIfDarkTheme as H,
  getBaseUrl as I,
  getRootUrl as J,
  t3 as K,
  t51 as L,
  t11 as M,
  NcButton as N,
  NC_ACTIONS_CLOSE_MENU as O,
  NC_ACTIONS_IS_SEMANTIC_MENU as P,
  t10 as Q,
  IconDotsHorizontal as R,
  useFormatTime as S,
  useFormatRelativeTime as T,
  t17 as U,
  t18 as V,
  t8 as W,
  imagePath as X,
  t45 as Y,
  t40 as Z,
  _export_sfc$1 as _,
  t as a,
  t25 as a0,
  t42 as a1,
  t32 as a2,
  t19 as a3,
  t12 as a4,
  routerKey as a5,
  RouterLink as a6,
  t22 as a7,
  NcPopover as a8,
  NcIconSvgWrapper as b,
  _export_sfc as c,
  loadState as d,
  t27 as e,
  isLegacy as f,
  createElementId as g,
  useRoute as h,
  isRtl as i,
  cancelableClient as j,
  generateOcsUrl as k,
  logger as l,
  axios as m,
  NcLoadingIcon as n,
  t20 as o,
  createFocusTrap as p,
  getTrapStack as q,
  register as r,
  t14 as s,
  t30 as t,
  useIsMobile as u,
  t21 as v,
  t50 as w,
  t23 as x,
  NcActions as y,
  t46 as z
};
//# sourceMappingURL=NcCheckboxRadioSwitch-BMsPx74L-CGnJCDi8.chunk.mjs.map
