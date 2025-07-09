<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Capabilities\ICapability;

/**
 * Class Capabilities
 *
 * @package OCA\Activity
 */
class Capabilities implements ICapability {
	/**
	 * Return this classes capabilities
	 */
	#[\Override]
	public function getCapabilities() {
		return [
			'activity' => [
				'apiv2' => [
					'filters',
					'filters-api',
					'previews',
					'rich-strings',
				],
			],
		];
	}
}
