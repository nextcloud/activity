/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { mount } from '@vue/test-utils'
import { describe, expect, test } from 'vitest'
import FileRichArgument from '../components/richArgumentsTypes/FileRichArgument.vue'

describe('FileRichArgument', () => {
	test('renders home icon with aria-label and role for root path', () => {
		const wrapper = mount(FileRichArgument, {
			props: {
				name: 'Home',
				path: '',
				link: 'http://localhost/files',
			},
		})

		const icon = wrapper.find('.icon-home')
		expect(icon.exists()).toBe(true)
		expect(icon.attributes('role')).toBe('img')
		expect(icon.attributes('aria-label')).toBe('Home')
		expect(wrapper.find('a').exists()).toBe(false)
	})

	test('renders plain link for file in root directory', () => {
		const wrapper = mount(FileRichArgument, {
			props: {
				name: 'photo.jpg',
				path: '/photo.jpg',
				link: 'http://localhost/files/photo.jpg',
			},
		})

		const link = wrapper.find('a')
		expect(link.exists()).toBe(true)
		expect(link.text()).toBe('photo.jpg')
		expect(link.attributes('title')).toBeUndefined()
		expect(wrapper.find('.icon-home').exists()).toBe(false)
	})

	test('renders link with path title for file in subdirectory', () => {
		const wrapper = mount(FileRichArgument, {
			props: {
				name: 'photo.jpg',
				path: '/Documents/Photos/photo.jpg',
				link: 'http://localhost/files/Documents/Photos/photo.jpg',
			},
		})

		const link = wrapper.find('a')
		expect(link.exists()).toBe(true)
		expect(link.text()).toBe('photo.jpg')
		expect(link.attributes('title')).toContain('/Documents/Photos')
		expect(link.attributes('aria-label')).toContain('/Documents/Photos')
	})
})
