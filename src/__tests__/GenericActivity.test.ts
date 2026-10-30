/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
import type { IFile } from '@nextcloud/files'
import type { IRawActivity } from '../models/types.ts'

import { mount } from '@vue/test-utils'
import { beforeEach, describe, expect, it, vi } from 'vitest'
import GenericActivity from '../components/activities/GenericActivity.vue'
import wsData from '../__mocks__/@nextcloud/activity_ws.json'
import ActivityModel from '../models/ActivityModel.js'

const viewer = vi.hoisted(() => ({ canView: vi.fn(), open: vi.fn() }))
vi.mock('@nextcloud/viewer', () => ({
	canView: viewer.canView,
	getViewer: () => ({ open: viewer.open }),
}))

/**
 * Show an activity with a preview of a file of alice's
 */
function mountWithPreview() {
	const activity = new ActivityModel({
		...wsData.ocs.data[1],
		previews: [{
			link: 'http://localhost/index.php/f/776',
			source: 'http://localhost/core/preview?fileId=776',
			mimeType: 'image/jpeg',
			isMimeTypeIcon: false,
			fileId: 776,
			filePath: '/alice/files/Photos/beach.jpg',
			view: 'files',
			filename: 'beach.jpg',
		}],
	} as unknown as IRawActivity)
	return mount(GenericActivity, { props: { activity, showPreviews: true } })
}

beforeEach(() => {
	viewer.canView.mockReset()
	viewer.open.mockReset()
	viewer.open.mockResolvedValue(undefined)
})

describe('a preview clicked', () => {
	it('opens the file alone in the viewer', async () => {
		viewer.canView.mockReturnValue(true)
		const wrapper = mountWithPreview()

		await wrapper.find('a.activity-entry__preview').trigger('click')

		expect(viewer.open).toHaveBeenCalledOnce()
		const [files, file] = viewer.open.mock.calls[0]! as [IFile[], IFile]
		expect(files).toEqual([file])
		expect(file.fileid).toBe(776)
		expect(file.path).toBe('/Photos/beach.jpg')
		expect(file.mime).toBe('image/jpeg')
	})

	it('follows its link when the viewer does not show the file', async () => {
		viewer.canView.mockReturnValue(false)
		const wrapper = mountWithPreview()

		await wrapper.find('a.activity-entry__preview').trigger('click')

		expect(viewer.open).not.toHaveBeenCalled()
	})
})
