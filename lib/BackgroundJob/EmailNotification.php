<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Activity\BackgroundJob;

use OCA\Activity\MailQueueHandler;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;

/**
 * Class EmailNotification
 *
 * @package OCA\Activity\BackgroundJob
 */
class EmailNotification extends TimedJob {

	public function __construct(
		ITimeFactory $time,
		protected MailQueueHandler $queueHandler,
		protected bool $isCLI,
	) {
		parent::__construct($time);

		// Run everytime cron is executed, so the batching doesn't delay too much
		$this->setInterval(1);
	}

	#[\Override]
	protected function run($argument) {
		// We don't use time() but "time() - 1" here, so we don't run into
		// runtime issues later and delete emails, which were created in the
		// same second, but were not collected for the emails.
		$sendTime = time() - 1;

		if ($this->isCLI) {
			do {
				// If we are in CLI mode, we keep sending emails
				// until we are done.
				$emails_sent = $this->queueHandler->sendEmails(MailQueueHandler::CLI_EMAIL_BATCH_SIZE, $sendTime);
			} while ($emails_sent === MailQueueHandler::CLI_EMAIL_BATCH_SIZE);
		} else {
			// Only send 25 Emails in one go for web cron
			$this->queueHandler->sendEmails(MailQueueHandler::WEB_EMAIL_BATCH_SIZE, $sendTime);
		}
	}
}
