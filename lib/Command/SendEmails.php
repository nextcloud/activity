<?php
/**
 * @copyright Copyright (c) 2017 Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Command;

use OC\Core\Command\Base;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\UserSettings;
use OCP\IConfig;
use OCP\ILogger;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmails extends Base {
	/** @var MailQueueHandler */
	protected $queueHandler;

	/** @var IConfig */
	protected $config;

	/** @var ILogger */
	protected $logger;

	/**
	 * @param MailQueueHandler $queueHandler
	 * @param IConfig $config
	 * @param ILogger $logger
	 */
	public function __construct(MailQueueHandler $queueHandler,
								IConfig $config,
								ILogger $logger) {
		parent::__construct();

		$this->queueHandler = $queueHandler;
		$this->config = $config;
		$this->logger = $logger;
	}

	protected function configure() {
		$this
			->setName('activity:send-mails')
			->setDescription('Sends the activity notification mails')
			->addArgument(
				'restrict-batching',
				InputArgument::OPTIONAL,
				'Only sends the emails for users which have configured the mails: "hourly", "daily" or "weekly"',
				'all'
			)
			->addOption(
				'limit',
				'l',
				InputOption::VALUE_REQUIRED,
				'Only sends this amount of emails to give the email server some time to relax',
				'unlimited'
			)
		;
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
		// We don't use time() but "time() - 1" here, so we don't run into
		// runtime issues later and delete emails, which were created in the
		// same second, but were not collected for the emails.
		$sendTime = time() - 1;

		$restrictBatching = $input->getArgument('restrict-batching');
		if ($restrictBatching === 'hourly') {
			$restrictEmails = UserSettings::EMAIL_SEND_HOURLY;
		} elseif ($restrictBatching === 'daily') {
			$restrictEmails = UserSettings::EMAIL_SEND_DAILY;
		} elseif ($restrictBatching === 'weekly') {
			$restrictEmails = UserSettings::EMAIL_SEND_WEEKLY;
		} elseif ($restrictBatching === 'asap') {
			$restrictEmails = UserSettings::EMAIL_SEND_ASAP;
		} else {
			$restrictEmails = null;
		}

		$limit = $input->getOption('limit');

		if ($limit === 'unlimited') {
			do {
				$emails_sent = $this->queueHandler->sendEmails(MailQueueHandler::CLI_EMAIL_BATCH_SIZE, $sendTime, true, $restrictEmails);
			} while ($emails_sent === MailQueueHandler::CLI_EMAIL_BATCH_SIZE);
		} else {
			$this->queueHandler->sendEmails($limit, $sendTime, true, $restrictEmails);
		}

		return 0;
	}

	/**
	 * @param string $argumentName
	 * @param CompletionContext $context
	 * @return string[]
	 */
	public function completeArgumentValues($argumentName, CompletionContext $context) {
		if ($argumentName === 'restrict-batching') {
			return ['asap', 'hourly', 'daily', 'weekly'];
		}
		return [];
	}
}
