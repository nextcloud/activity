/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

export const randHash = () => Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 10)
