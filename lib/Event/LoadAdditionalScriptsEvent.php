<?php
/**
 * @copyright Copyright (c) 2023, Louis Chmn <louis@chmn.me>
 *
 * @author Louis Chmn <louis@chmn.me>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
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

	public function __construct(public string $filter) {
		parent::__construct();
	}
}
