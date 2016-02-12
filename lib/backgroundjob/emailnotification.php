<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Morris Jobke <hey@morrisjobke.de>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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
use OCA\Activity\AppInfo\Application;
use OCA\Activity\MailQueueHandler;
use OCP\IConfig;
use OCP\ILogger;

/**
 * Class EmailNotification
 *
 * @package OCA\Activity\BackgroundJob
 */
class EmailNotification extends TimedJob {
	const CLI_EMAIL_BATCH_SIZE = 500;
	const WEB_EMAIL_BATCH_SIZE = 25;

	/** @var MailQueueHandler */
	protected $mqHandler;

	/** @var IConfig */
	protected $config;

	/** @var ILogger */
	protected $logger;

	/** @var bool */
	protected $isCLI;

	/**
	 * @param MailQueueHandler $mailQueueHandler
	 * @param IConfig $config
	 * @param ILogger $logger
	 * @param bool|null $isCLI
	 */
	public function __construct(MailQueueHandler $mailQueueHandler = null,
								IConfig $config = null,
								ILogger $logger = null,
								$isCLI = null) {
		// Run all 15 Minutes
		$this->setInterval(15 * 60);

		if ($mailQueueHandler === null || $config === null || $logger === null || $isCLI === null) {
			$this->fixDIForJobs();
		} else {
			$this->mqHandler = $mailQueueHandler;
			$this->config = $config;
			$this->logger = $logger;
			$this->isCLI = $isCLI;
		}
	}

	protected function fixDIForJobs() {
		$application = new Application();

		$this->mqHandler = $application->getContainer()->query('MailQueueHandler');
		$this->config = \OC::$server->getConfig();
		$this->logger = \OC::$server->getLogger();
		$this->isCLI = \OC::$CLI;
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
				$emails_sent = $this->runStep(self::CLI_EMAIL_BATCH_SIZE, $sendTime);
			} while ($emails_sent === self::CLI_EMAIL_BATCH_SIZE);
		} else {
			// Only send 25 Emails in one go for web cron
			$this->runStep(self::WEB_EMAIL_BATCH_SIZE, $sendTime);
		}
	}

	/**
	 * Send an email to {$limit} users
	 *
	 * @param int $limit Number of users we want to send an email to
	 * @param int $sendTime The latest send time
	 * @return int Number of users we sent an email to
	 */
	protected function runStep($limit, $sendTime) {
		// Get all users which should receive an email
		$affectedUsers = $this->mqHandler->getAffectedUsers($limit, $sendTime);
		if (empty($affectedUsers)) {
			// No users found to notify, mission abort
			return 0;
		}

		$userLanguages = $this->config->getUserValueForUsers('core', 'lang', $affectedUsers);
		$userTimezones = $this->config->getUserValueForUsers('core', 'timezone', $affectedUsers);
		$userEmails = $this->config->getUserValueForUsers('settings', 'email', $affectedUsers);

		// Send Email
		$default_lang = $this->config->getSystemValue('default_language', 'en');
		$defaultTimeZone = date_default_timezone_get();
		foreach ($affectedUsers as $user) {
			if (empty($userEmails[$user])) {
				// The user did not setup an email address
				// So we will not send an email :(
				$this->logger->debug("Couldn't send notification email to user '" . $user . "' (email address isn't set for that user)", ['app' => 'activity']);
				continue;
			}

			$language = (!empty($userLanguages[$user])) ? $userLanguages[$user] : $default_lang;
			$timezone = (!empty($userTimezones[$user])) ? $userTimezones[$user] : $defaultTimeZone;
			$this->mqHandler->sendEmailToUser($user, $userEmails[$user], $language, $timezone, $sendTime);
		}

		// Delete all entries we dealt with
		$this->mqHandler->deleteSentItems($affectedUsers, $sendTime);

		return sizeof($affectedUsers);
	}
}
