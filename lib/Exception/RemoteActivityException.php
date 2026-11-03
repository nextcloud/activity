<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Exception;

use Exception;

class RemoteActivityException extends Exception {
	public function __construct(
		string $message = '',
		int $code = 0,
		?\Throwable $previous = null,
		private readonly array $response = [],
	) {
		parent::__construct($message, $code, $previous);
	}

	public function getResponse(): array {
		return $this->response;
	}
}
