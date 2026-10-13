<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Config\Lexicon\Entry;
use OCP\Config\Lexicon\ILexicon;
use OCP\Config\Lexicon\Strictness;
use OCP\Config\ValueType;

class ConfigLexicon implements ILexicon {
	public const THRUSTED_SERVER_TTL = 'thrusted_server_ttl';

	#[\Override]
	public function getStrictness(): Strictness {
		return Strictness::IGNORE;
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getAppConfigs(): array {
		return [
			new Entry(key: self::THRUSTED_SERVER_TTL, type: ValueType::INT, defaultRaw: 600, definition: 'TTL on activities coming from a thrusted server', lazy: true),
		];
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getUserConfigs(): array {
		return [
		];
	}
}
