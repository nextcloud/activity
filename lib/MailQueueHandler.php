<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Lukas Reschke <lukas@statuscode.ch>
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

namespace OCA\Activity;

use OCA\Activity\Extension\LegacyParser;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\Defaults;
use OCP\IDateTimeFormatter;
use OCP\IDBConnection;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IMailer;
use OCP\Template;
use OCP\Util;

/**
 * Class MailQueueHandler
 * Gets the users from the database and
 *
 * @package OCA\Activity
 */
class MailQueueHandler {
	/** Number of entries we want to list in the email */
	const ENTRY_LIMIT = 200;

	/** @var array */
	protected $languages;

	/** @var string */
	protected $senderAddress;

	/** @var string */
	protected $senderName;

	/** @var IDateTimeFormatter */
	protected $dateFormatter;

	/** @var DataHelper */
	protected $dataHelper;

	/** @var IDBConnection */
	protected $connection;

	/** @var IMailer */
	protected $mailer;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var IUserManager */
	protected $userManager;

	/** @var IFactory */
	protected $lFactory;

	/** @var IManager */
	protected $activityManager;

	/** @var LegacyParser */
	protected $legacyParser;

	/**
	 * Constructor
	 *
	 * @param IDateTimeFormatter $dateFormatter
	 * @param IDBConnection $connection
	 * @param DataHelper $dataHelper
	 * @param IMailer $mailer
	 * @param IURLGenerator $urlGenerator
	 * @param IUserManager $userManager
	 * @param IFactory $lFactory
	 * @param IManager $activityManager
	 * @param LegacyParser $legacyParser
	 */
	public function __construct(IDateTimeFormatter $dateFormatter,
								IDBConnection $connection,
								DataHelper $dataHelper,
								IMailer $mailer,
								IURLGenerator $urlGenerator,
								IUserManager $userManager,
								IFactory $lFactory,
								IManager $activityManager,
								LegacyParser $legacyParser) {
		$this->dateFormatter = $dateFormatter;
		$this->connection = $connection;
		$this->dataHelper = $dataHelper;
		$this->mailer = $mailer;
		$this->urlGenerator = $urlGenerator;
		$this->userManager = $userManager;
		$this->lFactory = $lFactory;
		$this->activityManager = $activityManager;
		$this->legacyParser = $legacyParser;
	}

	/**
	 * Get the users we want to send an email to
	 *
	 * @param int|null $limit
	 * @param int $latestSend
	 * @return array
	 */
	public function getAffectedUsers($limit, $latestSend) {
		$limit = (!$limit) ? null : (int) $limit;

		$query = $this->connection->prepare(
			'SELECT `amq_affecteduser`, MIN(`amq_latest_send`) AS `amq_trigger_time` '
			. ' FROM `*PREFIX*activity_mq` '
			. ' WHERE `amq_latest_send` < ? '
			. ' GROUP BY `amq_affecteduser` '
			. ' ORDER BY `amq_trigger_time` ASC',
			$limit);
		$query->execute(array($latestSend));

		$affectedUsers = array();
		while ($row = $query->fetch()) {
			$affectedUsers[] = $row['amq_affecteduser'];
		}

		return $affectedUsers;
	}

	/**
	 * Get all items for the user we want to send an email to
	 *
	 * @param string $affectedUser
	 * @param int $maxTime
	 * @param int $maxNumItems
	 * @return array [data of the first max. 200 entries, total number of entries]
	 */
	protected function getItemsForUser($affectedUser, $maxTime, $maxNumItems = self::ENTRY_LIMIT) {
		$query = $this->connection->prepare(
			'SELECT * '
			. ' FROM `*PREFIX*activity_mq` '
			. ' WHERE `amq_timestamp` <= ? '
			. ' AND `amq_affecteduser` = ? '
			. ' ORDER BY `amq_timestamp` ASC',
			$maxNumItems
		);
		$query->execute([(int) $maxTime, $affectedUser]);

		$activities = array();
		while ($row = $query->fetch()) {
			$activities[] = $row;
		}

		if (isset($activities[$maxNumItems - 1])) {
			// Reached the limit, run a query to get the actual count.
			$query = $this->connection->prepare(
				'SELECT COUNT(*) AS `actual_count`'
				. ' FROM `*PREFIX*activity_mq` '
				. ' WHERE `amq_timestamp` <= ? '
				. ' AND `amq_affecteduser` = ?'
			);
			$query->execute([(int) $maxTime, $affectedUser]);

			$row = $query->fetch();
			return [$activities, $row['actual_count'] - $maxNumItems];
		} else {
			return [$activities, 0];
		}
	}

	/**
	 * Get a language object for a specific language
	 *
	 * @param string $lang Language identifier
	 * @return \OCP\IL10N Language object of $lang
	 */
	protected function getLanguage($lang) {
		if (!isset($this->languages[$lang])) {
			$this->languages[$lang] = $this->lFactory->get('activity', $lang);
		}

		return $this->languages[$lang];
	}

	/**
	 * Get the sender data
	 * @param string $setting Either `email` or `name`
	 * @return string
	 */
	protected function getSenderData($setting) {
		if (empty($this->senderAddress)) {
			$this->senderAddress = Util::getDefaultEmailAddress('no-reply');
		}
		if (empty($this->senderName)) {
			$defaults = new Defaults();
			$this->senderName = $defaults->getName();
		}

		if ($setting === 'email') {
			return $this->senderAddress;
		}
		return $this->senderName;
	}

	/**
	 * Send a notification to one user
	 *
	 * @param string $userName Username of the recipient
	 * @param string $email Email address of the recipient
	 * @param string $lang Selected language of the recipient
	 * @param string $timezone Selected timezone of the recipient
	 * @param int $maxTime
	 * @return bool True if the entries should be removed, false otherwise
	 */
	public function sendEmailToUser($userName, $email, $lang, $timezone, $maxTime) {
		$user = $this->userManager->get($userName);
		if (!$user instanceof IUser) {
			return true;
		}

		list($mailData, $skippedCount) = $this->getItemsForUser($userName, $maxTime);

		$l = $this->getLanguage($lang);
		$this->dataHelper->setUser($userName);
		$this->dataHelper->setL10n($l);
		$this->activityManager->setCurrentUserId($userName);

		$activityList = array();
		foreach ($mailData as $activity) {
			$event = $this->activityManager->generateEvent();
			$event->setApp($activity['amq_appid'])
				->setType($activity['amq_type'])
				->setTimestamp((int) $activity['amq_timestamp'])
				->setSubject($activity['amq_subject'], json_decode($activity['amq_subjectparams'], true));

			$relativeDateTime = $this->dateFormatter->formatDateTimeRelativeDay(
				$activity['amq_timestamp'],
				'long', 'medium',
				new \DateTimeZone($timezone), $l
			);

			try {
				$event = $this->parseEvent($lang, $event);
			} catch (\InvalidArgumentException $e) {
				continue;
			}

			$activityList[] = array(
				$event->getParsedSubject(),
				$relativeDateTime,
			);
		}

		$alttext = new Template('activity', 'email.notification', '', false);
		$alttext->assign('username', $user->getDisplayName());
		$alttext->assign('activities', $activityList);
		$alttext->assign('skippedCount', $skippedCount);
		$alttext->assign('installation', $this->urlGenerator->getAbsoluteURL('/'));
		$alttext->assign('overwriteL10N', $l);
		$emailText = $alttext->fetchPage();

		$message = $this->mailer->createMessage();
		$message->setTo([$email => $user->getDisplayName()]);
		$message->setSubject((string) $l->t('Activity notification'));
		$message->setPlainBody($emailText);
		$message->setFrom([$this->getSenderData('email') => $this->getSenderData('name')]);

		try {
			$this->mailer->send($message);
		} catch (\Exception $e) {
			return false;
		}

		$this->activityManager->setCurrentUserId(null);
		return true;
	}

	/**
	 * @param string $lang
	 * @param IEvent $event
	 * @return IEvent
	 * @throws \InvalidArgumentException when the event could not be parsed
	 */
	protected function parseEvent($lang, IEvent $event) {
		foreach ($this->activityManager->getProviders() as $provider) {
			try {
				$this->activityManager->setFormattingObject($event->getObjectType(), $event->getObjectId());
				$event = $provider->parse($lang, $event);
				$this->activityManager->setFormattingObject('', 0);
			} catch (\InvalidArgumentException $e) {
			}
		}

		if (!$event->getParsedSubject()) {
			$this->activityManager->setFormattingObject($event->getObjectType(), $event->getObjectId());
			$event = $this->legacyParser->parse($lang, $event);
			$this->activityManager->setFormattingObject('', 0);
		}

		return $event;
	}

	/**
	 * Delete all entries we dealt with
	 *
	 * @param array $affectedUsers
	 * @param int $maxTime
	 */
	public function deleteSentItems(array $affectedUsers, $maxTime) {
		if (empty($affectedUsers)) {
			return;
		}

		$query = $this->connection->getQueryBuilder();
		$query->delete('activity_mq')
			->where($query->expr()->lte('amq_timestamp', $query->createNamedParameter($maxTime, IQueryBuilder::PARAM_INT)))
			->andWhere($query->expr()->in('amq_affecteduser', $query->createNamedParameter($affectedUsers, IQueryBuilder::PARAM_STR_ARRAY), IQueryBuilder::PARAM_STR));
		$query->execute();
	}
}
