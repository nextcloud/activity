<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\SetupChecks;

use OCA\Activity\AppInfo\Application;
use OCP\IAppConfig;
use OCP\IL10N;
use OCP\SetupCheck\ISetupCheck;
use OCP\SetupCheck\SetupResult;

class HasOverlyLongActivities implements ISetupCheck {

	public function __construct(
		private readonly IAppConfig $appConfig,
		private readonly IL10N $l10n,
	) {
	}

	#[\Override]
	public function getCategory(): string {
		return 'activity';
	}

	#[\Override]
	public function getName(): string {
		return $this->l10n->t('Check for overly long activities');
	}

	#[\Override]
	public function run(): SetupResult {
		$activities = $this->appConfig->getValueInt(Application::APP_ID, 'overly_long_activities', 0);
		if ($activities === 0) {
			return SetupResult::success($this->l10n->t('No overly long activities detected.'));
		}

		return SetupResult::warning($this->l10n->t('There are ' . $activities . ' activities that generated more than 2000 characters for their content. Please check the logs for more details.'));
	}
}
