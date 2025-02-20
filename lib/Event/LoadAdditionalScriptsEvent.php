<?php

/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Activity\Event;

use OCP\EventDispatcher\Event;

/**
 * @since 28.0.0 Dispatched as a typed event
 */
class LoadAdditionalScriptsEvent extends Event {
	/**
	 * @deprecated 28.0.0 - Listen to the typed event instead.
	 */
	public const EVENT_ENTITY = 'OCA\Activity::loadAdditionalScripts';

	public function __construct(
		public string $filter,
	) {
		parent::__construct();
	}
}
