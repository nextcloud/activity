<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCP\Activity\IManager;
use OCP\IL10N;
use OCP\RichObjectStrings\IValidator;
use Psr\Log\LoggerInterface;

class GroupHelperDisabled extends GroupHelper {
	public function __construct(IL10N $l,
		IManager $activityManager,
		IValidator $richObjectValidator,
		LoggerInterface $logger) {
		parent::__construct($l,
			$activityManager,
			$richObjectValidator,
			$logger);
		$this->allowGrouping = false;
	}
}
