/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { VueWrapper } from '@vue/test-utils'

import { mount } from '@vue/test-utils'
import { describe, expect, it, vi } from 'vitest'
import ActivityFilterBar from '../components/ActivityFilterBar.vue'
import { daysAgo } from '../utils/dateRange.ts'

vi.mock(import('@vueuse/core'), async (importOriginal) => {
	const actual = await importOriginal()
	return {
		...actual,
		// Remove the debounce so search emits are observable synchronously
		useDebounceFn: vi.fn((fn: (...args: unknown[]) => unknown) => fn),
	}
})

// --- Component stubs ---

const NcTextFieldStub = {
	name: 'NcTextField',
	template: `<div class="text-field">
		<input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)">
		<button v-if="showTrailingButton" class="clear-search" @click="$emit('trailingButtonClick')" />
		<span class="helper">{{ helperText }}</span>
	</div>`,
	props: ['modelValue', 'showTrailingButton', 'helperText', 'label', 'type', 'trailingButtonLabel', 'trailingButtonIcon'],
	// Matches the real NcTextField, which emits camelCase
	emits: ['update:modelValue', 'trailingButtonClick'],
}

const NcActionsStub = {
	name: 'NcActions',
	template: '<div class="actions" :data-menu-name="menuName"><slot /></div>',
	props: ['menuName', 'forceMenu'],
}

const NcActionButtonStub = {
	name: 'NcActionButton',
	template: '<button class="action-button" :data-value="value" :data-selected="modelValue === value" @click="$emit(\'click\')"><slot /></button>',
	props: ['modelValue', 'value', 'type'],
	emits: ['click'],
}

const NcButtonStub = {
	name: 'NcButton',
	template: '<button class="nc-button" @click="$emit(\'click\')"><slot /></button>',
	props: ['variant'],
	emits: ['click'],
}

const NcSelectUsersStub = {
	name: 'NcSelectUsers',
	template: '<div class="select-users" :data-selected="modelValue ? modelValue.id : \'\'" :data-count="options.length" />',
	props: ['modelValue', 'options', 'inputId', 'labelOutside', 'placeholder'],
	emits: ['update:modelValue'],
}

const NcDateTimePickerNativeStub = {
	name: 'NcDateTimePickerNative',
	template: '<div class="date-picker" :data-label="label" />',
	props: ['modelValue', 'type', 'label', 'min', 'max'],
	emits: ['update:modelValue'],
}

const stubs = {
	NcTextField: NcTextFieldStub,
	NcActions: NcActionsStub,
	NcActionButton: NcActionButtonStub,
	NcButton: NcButtonStub,
	NcDateTimePickerNative: NcDateTimePickerNativeStub,
	NcSelectUsers: NcSelectUsersStub,
}

const ACTORS = [
	{ id: 'alice', displayName: 'Alice', user: 'alice' },
	{ id: 'bob', displayName: 'Bob', user: 'bob' },
]

/**
 * Mount the filter bar with the given active criteria.
 *
 * @param props - Overrides for the search term and date range
 */
function mountBar(props: Record<string, unknown> = {}): VueWrapper {
	return mount(ActivityFilterBar, {
		props: { search: '', from: null, to: null, actor: '', actorOptions: ACTORS, ...props },
		global: { stubs },
	})
}

/**
 * Click the preset entry with the given id.
 *
 * @param wrapper - The mounted filter bar
 * @param id - The preset identifier
 */
async function clickPreset(wrapper: VueWrapper, id: string) {
	await wrapper.find(`.action-button[data-value="${id}"]`).trigger('click')
}

describe('ActivityFilterBar search', () => {
	it('emits a normalised term once it is long enough', async () => {
		const wrapper = mountBar()
		await wrapper.find('input').setValue('  report  ')

		expect(wrapper.emitted('update:search')).toEqual([['report']])
	})

	it('does not emit a search for a term below the minimum length', async () => {
		const wrapper = mountBar()
		await wrapper.find('input').setValue('r')

		expect(wrapper.emitted('update:search')).toBeUndefined()
	})

	it('explains why a single character does not search yet', async () => {
		const wrapper = mountBar()
		await wrapper.find('input').setValue('r')

		expect(wrapper.find('.helper').text()).toContain('at least')
	})

	it('clears the term immediately when the trailing button is used', async () => {
		const wrapper = mountBar({ search: 'report' })
		await wrapper.find('.clear-search').trigger('click')

		expect(wrapper.emitted('update:search')).toEqual([['']])
	})

	it('reflects a term cleared from outside the component', async () => {
		const wrapper = mountBar({ search: 'report' })
		expect((wrapper.find('input').element as HTMLInputElement).value).toBe('report')

		await wrapper.setProps({ search: '' })
		expect((wrapper.find('input').element as HTMLInputElement).value).toBe('')
	})
})

describe('ActivityFilterBar date presets', () => {
	it('starts on "Any time" when no range is active', () => {
		const wrapper = mountBar()
		expect(wrapper.find('.actions').attributes('data-menu-name')).toBe('Any time')
	})

	it('emits an open ended range for "Last 7 days"', async () => {
		const wrapper = mountBar()
		await clickPreset(wrapper, 'week')

		expect(wrapper.emitted('update:from')).toEqual([[daysAgo(6)]])
		expect(wrapper.emitted('update:to')).toEqual([[null]])
	})

	it('emits the start of today for "Today"', async () => {
		const wrapper = mountBar()
		await clickPreset(wrapper, 'today')

		expect(wrapper.emitted('update:from')).toEqual([[daysAgo(0)]])
	})

	it('clears both bounds for "Any time"', async () => {
		const wrapper = mountBar({ from: daysAgo(6) })
		await clickPreset(wrapper, 'any')

		expect(wrapper.emitted('update:from')).toEqual([[null]])
		expect(wrapper.emitted('update:to')).toEqual([[null]])
	})

	it('recognises an incoming range as its matching preset', () => {
		const wrapper = mountBar({ from: daysAgo(29) })
		expect(wrapper.find('.actions').attributes('data-menu-name')).toBe('Last 30 days')
	})

	it('treats a range with an upper bound as custom', () => {
		const wrapper = mountBar({ from: new Date(2024, 0, 1), to: new Date(2024, 0, 31) })
		expect(wrapper.find('.date-picker').exists()).toBe(true)
	})

	it('reveals the date pickers only for a custom range', async () => {
		const wrapper = mountBar()
		expect(wrapper.find('.date-picker').exists()).toBe(false)

		await clickPreset(wrapper, 'custom')
		expect(wrapper.findAll('.date-picker')).toHaveLength(2)
	})

	it('does not refetch when the active preset is picked again', async () => {
		const wrapper = mountBar({ from: daysAgo(6) })
		await clickPreset(wrapper, 'week')

		expect(wrapper.emitted('update:from')).toBeUndefined()
		expect(wrapper.emitted('update:to')).toBeUndefined()
	})

	it('keeps the existing range when switching to custom', async () => {
		const wrapper = mountBar({ from: daysAgo(6) })
		await clickPreset(wrapper, 'custom')

		expect(wrapper.emitted('update:from')).toBeUndefined()
		expect(wrapper.emitted('update:to')).toBeUndefined()
	})
})

describe('ActivityFilterBar account', () => {
	it('offers every known account', () => {
		const wrapper = mountBar()
		expect(wrapper.find('.select-users').attributes('data-count')).toBe('2')
	})

	it('is hidden when no accounts are known yet', () => {
		const wrapper = mountBar({ actorOptions: [] })
		expect(wrapper.find('.select-users').exists()).toBe(false)
	})

	it('emits the account name when one is picked', async () => {
		const wrapper = mountBar()
		wrapper.findComponent(NcSelectUsersStub).vm.$emit('update:modelValue', ACTORS[1])
		await wrapper.vm.$nextTick()

		expect(wrapper.emitted('update:actor')).toEqual([['bob']])
	})

	it('emits an empty account name when the selection is cleared', async () => {
		const wrapper = mountBar({ actor: 'alice' })
		wrapper.findComponent(NcSelectUsersStub).vm.$emit('update:modelValue', null)
		await wrapper.vm.$nextTick()

		expect(wrapper.emitted('update:actor')).toEqual([['']])
	})

	it('shows an account restored from the URL before its activities have loaded', () => {
		const wrapper = mountBar({ actor: 'carol', actorOptions: [] })
		// The select is hidden with no options, but the synthesised selection
		// still has to survive so the filter is not silently dropped
		expect(wrapper.find('.nc-button').exists()).toBe(true)
	})
})

describe('ActivityFilterBar clear all', () => {
	it('is hidden when nothing is filtered', () => {
		expect(mountBar().find('.nc-button').exists()).toBe(false)
	})

	it('resets every criterion at once', async () => {
		const wrapper = mountBar({ search: 'report', from: new Date(2024, 0, 1), to: new Date(2024, 0, 31), actor: 'alice' })
		await wrapper.find('.nc-button').trigger('click')

		expect(wrapper.emitted('update:search')).toEqual([['']])
		expect(wrapper.emitted('update:from')).toEqual([[null]])
		expect(wrapper.emitted('update:to')).toEqual([[null]])
		expect(wrapper.emitted('update:actor')).toEqual([['']])
	})
})
