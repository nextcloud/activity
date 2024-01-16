<?php

declare(strict_types=1);

namespace OCA\Activity\Tests\Mock;

use OCP\IRequest;

// We need this as we need to mock ::$server
// which can not be accessed on PHP 8.2 due to deprecation of dynamic properties.
interface Request extends IRequest {
	public function __get(string $prop): mixed;
}
