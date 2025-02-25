<?php

/**
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity\Extension;

class Files {
	public const TYPE_SHARE_CREATED = 'file_created';
	public const TYPE_FILE_CHANGED = 'file_changed';
	public const TYPE_FAVORITE_CHANGED = 'file_favorite_changed';
	public const TYPE_SHARE_DELETED = 'file_deleted';
	public const TYPE_SHARE_RESTORED = 'file_restored';
}
