<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Activity\BackgroundJob;

use OCA\Activity\Data;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;
use OCP\IConfig;

class ExpireActivities extends TimedJob {
	/** @var Data */
	protected $data;
	/** @var IConfig */
	protected $config;

	public function __construct(ITimeFactory $time,
		Data $data,
		IConfig $config) {
		parent::__construct($time);

		// Run once per day
		$this->setInterval(60 * 60 * 24);
		$this->setTimeSensitivity(self::TIME_INSENSITIVE);

		$this->data = $data;
		$this->config = $config;
	}

	#[\Override]
	protected function run($argument): void {
		// Remove activities that are older then one year
		$expireDays = $this->config->getSystemValue('activity_expire_days', 365);
		$this->data->expire($expireDays);
	}
}
