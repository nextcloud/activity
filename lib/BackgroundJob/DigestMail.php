<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2020 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\BackgroundJob;

use OCA\Activity\DigestSender;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;

class DigestMail extends TimedJob {

	public function __construct(
		ITimeFactory $timeFactory,
		protected DigestSender $digestSender,
	) {
		parent::__construct($timeFactory);
		// run hourly
		$this->setInterval(60 * 60);
	}

	#[\Override]
	protected function run($argument) {
		$this->digestSender->sendDigests($this->time->getTime());
	}
}
