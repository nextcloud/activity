<!--
  - SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<component
		:is="wrapperElement"
		:class="{
			'checkbox-radio-switch--checked': isChecked,
			'checkbox-radio-switch--disabled': disabled,
			'checkbox-radio-switch--indeterminate': indeterminate,
		}"
		:style="cssVars"
		class="checkbox-radio-switch checkbox-radio-switch-checkbox">
		<input
			:id="id"
			:checked="isChecked"
			:disabled="disabled"
			:indeterminate="indeterminate"
			:name="name"
			type="checkbox"
			:value="value"
			class="checkbox-radio-switch__input"
			@change="onToggle">

		<label :for="id" class="checkbox-radio-switch__label">
			<component
				:is="checkboxRadioIconElement"
				:size="size"
				class="checkbox-radio-switch__icon"
				aria-hidden="true" />
			<span class="sr-only">
				<slot />
			</span>
		</label>
	</component>
</template>

<script>
import CheckboxBlank from 'vue-material-design-icons/CheckboxBlank.vue'
import CheckboxBlankOutline from 'vue-material-design-icons/CheckboxBlankOutline.vue'
import CheckboxMarked from 'vue-material-design-icons/CheckboxMarked.vue'
import MinusBox from 'vue-material-design-icons/MinusBox.vue'

export default {
	name: 'CheckboxInput',

	props: {

		/**
		 * Unique id attribute of the input
		 */
		id: {
			type: String,
			required: true,
			validator: (id) => id.trim() !== '',
		},

		/**
		 * Checked state. To be used with `:value.sync`
		 */
		checked: {
			type: [Boolean, Array, String],
			default: false,
		},

		/**
		 * Value to be synced on check
		 */
		value: {
			type: String,
			default: null,
		},

		/**
		 * Disabled state
		 */
		disabled: {
			type: Boolean,
			default: false,
		},

		/**
		 * Indeterminate state
		 */
		indeterminate: {
			type: Boolean,
			default: false,
		},

		/**
		 * Wrapping element tag
		 */
		wrapperElement: {
			type: String,
			default: 'span',
		},

		/**
		 * Input name. Required for radio, optional for checkbox
		 */
		name: {
			type: String,
			default: null,
		},
	},

	emits: ['update:checked'],

	computed: {
		/**
		 * Icon size
		 */
		size() {
			return 24
		},

		/**
		 * Css local variables for this component
		 */
		cssVars() {
			return {
				'--icon-size': this.size + 'px',
			}
		},

		isChecked() {
			return [...this.checked].indexOf(this.value) > -1
		},

		/**
		 * Returns the proper Material icon depending on the select case
		 */
		checkboxRadioIconElement() {
			if (this.indeterminate) {
				return MinusBox
			}
			if (this.disabled && !this.isChecked) {
				return CheckboxBlank
			}
			if (this.isChecked) {
				return CheckboxMarked
			}
			return CheckboxBlankOutline
		},
	},

	methods: {
		onToggle() {
			if (this.disabled) {
				return
			}

			// If the initial value was a boolean, let's keep it that way
			if (typeof this.checked === 'boolean') {
				this.$emit('update:checked', !this.isChecked)
				return
			}

			// Dispatch the checked values as an array if multiple, or single value otherwise
			const values = this.getInputsSet()
				.filter((input) => input.checked)
				.map((input) => input.value)
			this.$emit('update:checked', values)
		},

		/**
		 * Get the input set based on this name
		 */
		getInputsSet() {
			return [...document.getElementsByName(this.name)]
		},
	},
}
</script>

<style lang="scss" scoped>
$spacing: 4px;

.checkbox-radio-switch {
	display: flex;

	&__input {
		position: fixed;
		z-index: -1;
		top: -5000px;
		left: -5000px;
		opacity: 0;
	}

	&__label {
		display: flex;
		align-items: center;
		user-select: none;
		height: 32px;
		width: 32px;
		border-radius: 44px;
		padding: 0;
		margin: 2px;

		&, * {
			cursor: pointer;
		}
	}

	&__icon {
		margin-right: $spacing;
		margin-left: $spacing;
		color: var(--color-primary-element);
		width: var(--icon-size);
		height: var(--icon-size);
	}

	&--disabled &__label {
		opacity: 0.7;
		.checkbox-radio-switch__icon {
			color: var(--color-text-light)
		}
	}

	&:not(&--disabled) &__input:hover + &__label,
	&:not(&--disabled) &__input:focus + &__label {
		background-color: var(--color-primary-element-light);
	}

	// Increase focus effect
	&:not(&--disabled) &__input:focus + &__label {
		box-shadow: 0 0 0 2px var(--color-primary-element);
	}

	// Switch specific rules
	&-switch:not(&--checked) &__icon {
		color: var(--color-text-lighter);
	}

	// If  switch is checked AND disabled, use the fade primary colour
	&-switch.checkbox-radio-switch--disabled.checkbox-radio-switch--checked &__icon {
		color: var(--color-primary-element-light);
	}

	.sr-only {
		position:absolute;
		left:-10000px;
		top:auto;
		width:1px;
		height:1px;
		overflow:hidden;
	}
}

</style>
