/*!
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import type { IRawActivity } from '../models/types.ts'

import { describe, expect, it } from 'vitest'
import ActivityModel from '../models/ActivityModel.ts'

const mention = { type: 'user', id: 'bob', name: 'Bob' }

/**
 * Build a raw comment activity carrying the given rich message parameters.
 *
 * @param messageParameters - value of `message_rich[1]`
 */
function makeActivity(messageParameters: unknown): ActivityModel {
	return new ActivityModel({
		activity_id: 1,
		app: 'comments',
		type: 'comments',
		user: 'admin',
		subject: 'Admin commented',
		subject_rich: ['', []],
		message: 'Hello Bob',
		message_rich: ['Hello {mention-user1}', messageParameters],
		object_type: 'files',
		object_id: 1,
		object_name: '/welcome.md',
		link: '',
		icon: 'http://localhost/apps/comments/img/comments.svg',
		datetime: '2024-01-01T12:00:00+00:00',
	} as IRawActivity)
}

// PHP encodes an empty associative array as `[]` and a filled one as an object,
// so the getter has to normalise the empty-array case to an empty map.
describe('ActivityModel.messageRichObjects', () => {
	it('returns the parameters when they are set', () => {
		expect(makeActivity({ 'mention-user1': mention }).messageRichObjects).toEqual({ 'mention-user1': mention })
	})

	it('returns an empty map when there are none', () => {
		expect(makeActivity([]).messageRichObjects).toEqual({})
	})
})
