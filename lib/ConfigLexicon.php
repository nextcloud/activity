<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Config\Lexicon\Entry;
use OCP\Config\Lexicon\ILexicon;
use OCP\Config\Lexicon\Strictness;
use OCP\Config\ValueType;

class ConfigLexicon implements ILexicon {
	public const TRUSTED_SERVER_TTL = 'trusted_server_ttl';

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
			new Entry(key: self::TRUSTED_SERVER_TTL, type: ValueType::INT, defaultRaw: 600, definition: 'TTL on activities coming from a trusted server', lazy: true),
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
