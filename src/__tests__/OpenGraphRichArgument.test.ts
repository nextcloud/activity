/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { mount } from '@vue/test-utils'
import { describe, expect, test } from 'vitest'
import OpenGraphRichArgument from '../components/richArgumentsTypes/OpenGraphRichArgument.vue'

describe('OpenGraphRichArgument', () => {
	test('renders basic opengraph card with link and text', () => {
		const wrapper = mount(OpenGraphRichArgument, {
			props: {
				id: 'test-1',
				name: 'Example Page',
				description: 'A test description',
				link: 'https://example.com',
				website: 'example.com',
			},
		})

		expect(wrapper.find('a').attributes('href')).toBe('https://example.com')
		expect(wrapper.find('.opengraph-name').text()).toBe('Example Page')
		expect(wrapper.find('.opengraph-description').text()).toBe('A test description')
		expect(wrapper.find('.opengraph-website').text()).toBe('example.com')
	})

	test('hides thumbnail when thumb prop is empty', () => {
		const wrapper = mount(OpenGraphRichArgument, {
			props: {
				id: 'test-2',
				name: 'Example Page',
				description: 'A test description',
				link: 'https://example.com',
				website: 'example.com',
				thumb: '',
			},
		})

		expect(wrapper.find('.opengraph-thumb').exists()).toBe(false)
	})

	test('shows thumbnail with aria-hidden when thumb is provided', () => {
		const wrapper = mount(OpenGraphRichArgument, {
			props: {
				id: 'test-3',
				name: 'Example Page',
				description: 'A test description',
				link: 'https://example.com',
				website: 'example.com',
				thumb: 'https://example.com/thumbnail.jpg',
			},
		})

		const thumb = wrapper.find('.opengraph-thumb')
		expect(thumb.exists()).toBe(true)
		expect(thumb.attributes('aria-hidden')).toBe('true')
		expect(thumb.attributes('style')).toContain('https://example.com/thumbnail.jpg')
	})

	test('applies opengraph-with-thumb class to name and description when thumb present', () => {
		const wrapper = mount(OpenGraphRichArgument, {
			props: {
				id: 'test-4',
				name: 'Example Page',
				description: 'A test description',
				link: 'https://example.com',
				website: 'example.com',
				thumb: 'https://example.com/thumbnail.jpg',
			},
		})

		expect(wrapper.find('.opengraph-name').classes()).toContain('opengraph-with-thumb')
		expect(wrapper.find('.opengraph-description').classes()).toContain('opengraph-with-thumb')
	})
})
