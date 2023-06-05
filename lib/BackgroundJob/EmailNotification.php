<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Morris Jobke <hey@morrisjobke.de>
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

namespace OCA\Activity\BackgroundJob;

use OC\BackgroundJob\TimedJob;
use OCA\Activity\MailQueueHandler;

/**
 * Class EmailNotification
 *
 * @package OCA\Activity\BackgroundJob
 */
class EmailNotification extends TimedJob {
	/** @var MailQueueHandler */
	protected $queueHandler;

	/** @var bool */
	protected $isCLI;

	public function __construct(MailQueueHandler $mailQueueHandler,
		bool $isCLI) {
		// Run everytime cron is executed, so the batching doesn't delay too much
		$this->setInterval(1);

		$this->queueHandler = $mailQueueHandler;
		$this->isCLI = $isCLI;
	}

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
