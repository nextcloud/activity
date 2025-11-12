<?php

/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Activity\Tests\Mock;

use OCP\Activity\ISetting;

/**
 * @since 11.0.0
 */
class Setting1 implements ISetting {
	public function getIdentifier(): string {
		return 'type1';
	}

	public function getName(): string {
		return 'type1';
	}

	public function getPriority(): int {
		return 100;
	}

	public function canChangeStream(): bool {
		return false;
	}

	public function isDefaultEnabledStream(): bool {
		return true;
	}

	public function canChangeMail(): bool {
		return false;
	}

	public function isDefaultEnabledMail(): bool {
		return false;
	}
}
