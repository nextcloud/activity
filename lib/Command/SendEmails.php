<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Command;

use OC\Core\Command\Base;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\UserSettings;
use OCP\IConfig;
use Psr\Log\LoggerInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmails extends Base {
	/**
	 * @param MailQueueHandler $queueHandler
	 * @param IConfig $config
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		protected MailQueueHandler $queueHandler,
		protected IConfig $config,
		protected LoggerInterface $logger,
	) {
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
