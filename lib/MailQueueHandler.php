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

use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\Defaults;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IDBConnection;
use OCP\ILogger;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IMailer;
use OCP\RichObjectStrings\IValidator;
use OCP\Util;

/**
 * Class MailQueueHandler
 * Gets the users from the database and
 *
 * @package OCA\Activity
 */
class MailQueueHandler {

	const CLI_EMAIL_BATCH_SIZE = 500;

	const WEB_EMAIL_BATCH_SIZE = 25;

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

	/** @var IValidator */
	protected $richObjectValidator;

	/** @var IConfig */
	protected $config;

	/** @var ILogger */
	protected $logger;

	public function __construct(IDateTimeFormatter $dateFormatter,
								IDBConnection $connection,
								IMailer $mailer,
								IURLGenerator $urlGenerator,
								IUserManager $userManager,
								IFactory $lFactory,
								IManager $activityManager,
								IValidator $richObjectValidator,
								IConfig $config,
								ILogger $logger) {
		$this->dateFormatter = $dateFormatter;
		$this->connection = $connection;
		$this->mailer = $mailer;
		$this->urlGenerator = $urlGenerator;
		$this->userManager = $userManager;
		$this->lFactory = $lFactory;
		$this->activityManager = $activityManager;
		$this->richObjectValidator = $richObjectValidator;
		$this->config = $config;
		$this->logger = $logger;
	}

	/**
	 * Send an email to {$limit} users
	 *
	 * @param int $limit Number of users we want to send an email to
	 * @param int $sendTime The latest send time
	 * @param bool $forceSending Ignores latest send and just sends all emails
	 * @param null|int $restrictEmails null or one of UserSettings::EMAIL_SEND_*
	 * @return int Number of users we sent an email to
	 */
	public function sendEmails($limit, $sendTime, $forceSending = false, $restrictEmails = null) {
		// Get all users which should receive an email
		$affectedUsers = $this->getAffectedUsers($limit, $sendTime, $forceSending, $restrictEmails);
		if (empty($affectedUsers)) {
			// No users found to notify, mission abort
			return 0;
		}

		$userLanguages = $this->config->getUserValueForUsers('core', 'lang', $affectedUsers);
		$userTimezones = $this->config->getUserValueForUsers('core', 'timezone', $affectedUsers);
		$userEmails = $this->config->getUserValueForUsers('settings', 'email', $affectedUsers);
		$userEnabled = $this->config->getUserValueForUsers('core', 'enabled', $affectedUsers);

		// Send Email
		$default_lang = $this->config->getSystemValue('default_language', 'en');
		$defaultTimeZone = date_default_timezone_get();

		$deleteItemsForUsers = [];
		$this->activityManager->setRequirePNG(true);
		foreach ($affectedUsers as $user) {
			if (isset($userEnabled[$user]) && $userEnabled[$user] === 'false') {
				$deleteItemsForUsers[] = $user;
				continue;
			}

			if (empty($userEmails[$user])) {
				// The user did not setup an email address
				// So we will not send an email :(
				$this->logger->debug("Couldn't send notification email to user '{user}' (email address isn't set for that user)", ['user' => $user, 'app' => 'activity']);
				$deleteItemsForUsers[] = $user;
				continue;
			}

			$language = (!empty($userLanguages[$user])) ? $userLanguages[$user] : $default_lang;
			$timezone = (!empty($userTimezones[$user])) ? $userTimezones[$user] : $defaultTimeZone;
			try {
				if ($this->sendEmailToUser($user, $userEmails[$user], $language, $timezone, $sendTime)) {
					$deleteItemsForUsers[] = $user;
				} else {
					$this->logger->debug("Failed sending activity email to user '{user}'.", ['user' => $user, 'app' => 'activity']);
				}
			} catch (\Exception $e) {
				$this->logger->logException($e, [
					'message' => 'Failed sending activity email to user "{user}"',
					'user' => $user,
					'app' => 'activity',
				]);
				// continue;
			}
		}
		$this->activityManager->setRequirePNG(false);

		// Delete all entries we dealt with
		$this->deleteSentItems($deleteItemsForUsers, $sendTime);

		return count($affectedUsers);
	}

	/**
	 * Get the users we want to send an email to
	 *
	 * @param int|null $limit
	 * @param int $latestSend
	 * @param bool $forceSending
	 * @param int|null $restrictEmails
	 * @return array
	 */
	protected function getAffectedUsers($limit, $latestSend, $forceSending, $restrictEmails) {
		$query = $this->connection->getQueryBuilder();
		$query->select('amq_affecteduser')
			->selectAlias($query->createFunction('MIN(' . $query->getColumnName('amq_latest_send') . ')'), 'amq_trigger_time')
			->from('activity_mq')
			->groupBy('amq_affecteduser')
			->orderBy('amq_trigger_time', 'ASC');

		if ($limit > 0) {
			$query->setMaxResults($limit);
		}

		if ($forceSending) {
			$query->where($query->expr()->lt('amq_timestamp', $query->createNamedParameter($latestSend)));
		} else {
			$query->where($query->expr()->lt('amq_latest_send', $query->createNamedParameter($latestSend)));
		}

		if ($restrictEmails !== null) {
			if ($restrictEmails === UserSettings::EMAIL_SEND_HOURLY) {
				$query->where($query->expr()->eq('amq_timestamp', $query->func()->subtract('amq_latest_send', $query->expr()->literal(3600))));
			} else if ($restrictEmails === UserSettings::EMAIL_SEND_DAILY) {
				$query->where($query->expr()->eq('amq_timestamp', $query->func()->subtract('amq_latest_send', $query->expr()->literal(3600 * 24))));
			} else if ($restrictEmails === UserSettings::EMAIL_SEND_WEEKLY) {
				$query->where($query->expr()->eq('amq_timestamp', $query->func()->subtract('amq_latest_send', $query->expr()->literal(3600 * 24 * 7))));
			} else if ($restrictEmails === UserSettings::EMAIL_SEND_ASAP) {
				$query->where($query->expr()->eq('amq_timestamp', 'amq_latest_send'));
			}
		}

		$result = $query->execute();

		$affectedUsers = array();
		while ($row = $result->fetch()) {
			$affectedUsers[] = $row['amq_affecteduser'];
		}
		$result->closeCursor();

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
		$query = $this->connection->getQueryBuilder();
		$query->select('*')
			->from('activity_mq')
			->where($query->expr()->lte('amq_timestamp', $query->createNamedParameter($maxTime)))
			->andWhere($query->expr()->eq('amq_affecteduser', $query->createNamedParameter($affectedUser)))
			->orderBy('amq_timestamp', 'ASC')
			->setMaxResults($maxNumItems);
		$result = $query->execute();

		$activities = [];
		while ($row = $result->fetch()) {
			$activities[] = $row;
		}
		$result->closeCursor();

		if (isset($activities[$maxNumItems - 1])) {
			// Reached the limit, run a query to get the actual count.
			$query = $this->connection->getQueryBuilder();
			$query->selectAlias($query->func()->count('*'), 'actual_count')
				->from('activity_mq')
				->where($query->expr()->lte('amq_timestamp', $query->createNamedParameter($maxTime)))
				->andWhere($query->expr()->eq('amq_affecteduser', $query->createNamedParameter($affectedUser)));
			$result = $query->execute();
			$row = $result->fetch();
			$result->closeCursor();

			return [$activities, $row['actual_count'] - $maxNumItems];
		}

		return [$activities, 0];
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
	 * @throws \UnexpectedValueException
	 */
	protected function sendEmailToUser($userName, $email, $lang, $timezone, $maxTime) {
		$user = $this->userManager->get($userName);
		if (!$user instanceof IUser) {
			return true;
		}

		list($mailData, $skippedCount) = $this->getItemsForUser($userName, $maxTime);

		$l = $this->getLanguage($lang);
		$this->activityManager->setCurrentUserId($userName);

		$activityEvents = [];
		foreach ($mailData as $activity) {
			$event = $this->activityManager->generateEvent();
			try {
				$event->setApp((string) $activity['amq_appid'])
					->setType((string) $activity['amq_type'])
					->setTimestamp((int) $activity['amq_timestamp'])
					->setSubject((string) $activity['amq_subject'], (array) json_decode($activity['amq_subjectparams'], true))
					->setObject((string) $activity['object_type'], (int) $activity['object_id']);
			} catch (\InvalidArgumentException $e) {
				continue;
			}

			$relativeDateTime = $this->dateFormatter->formatDateTimeRelativeDay(
				(int) $activity['amq_timestamp'],
				'long', 'short',
				new \DateTimeZone($timezone), $l
			);

			try {
				$event = $this->parseEvent($lang, $event);
			} catch (\InvalidArgumentException $e) {
				continue;
			}

			$activityEvents[] = [
				'event' => $event,
				'relativeDateTime' => $relativeDateTime
			];
		}

		$template = $this->mailer->createEMailTemplate('activity.Notification', [
			'displayname' => $user->getDisplayName(),
			'url' => $this->urlGenerator->getAbsoluteURL('/'),
			'activityEvents' => $activityEvents,
			'skippedCount' => $skippedCount,
		]);
		$template->setSubject($l->t('Activity notification for %s', $this->getSenderData('name')));
		$template->addHeader();
		$template->addHeading($l->t('Hello %s',[$user->getDisplayName()]), $l->t('Hello %s,',[$user->getDisplayName()]));

		$homeLink = '<a href="' . $this->urlGenerator->getAbsoluteURL('/') . '">' . htmlspecialchars($this->getSenderData('name')) . '</a>';
		$template->addBodyText(
			$l->t('There was some activity at %s', [$homeLink]),
			$l->t('There was some activity at %s', [$this->urlGenerator->getAbsoluteURL('/')])
		);

		foreach ($activityEvents as $activity) {
			/** @var IEvent $event */
			$event = $activity['event'];
			$relativeDateTime = $activity['relativeDateTime'];

			$template->addBodyListItem($this->getHTMLSubject($event), $relativeDateTime, $event->getIcon(), $event->getParsedSubject());
		}

		if ($skippedCount) {
			$template->addBodyListItem($l->n('and %n more ', 'and %n more ', $skippedCount));
		}

		$template->addFooter();

		$message = $this->mailer->createMessage();
		$message->setTo([$email => $user->getDisplayName()]);
		$message->useTemplate($template);
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
	 * @param IEvent $event
	 * @return string
	 */
	protected function getHTMLSubject(IEvent $event): string {
		if ($event->getRichSubject() === '') {
			return htmlspecialchars($event->getParsedSubject());
		}

		$placeholders = $replacements = [];
		foreach ($event->getRichSubjectParameters() as $placeholder => $parameter) {
			$placeholders[] = '{' . $placeholder . '}';

			if ($parameter['type'] === 'file') {
				$replacement = $parameter['path'];
			} else {
				$replacement = $parameter['name'];
			}

			if (isset($parameter['link'])) {
				$replacements[] = '<a href="' . $parameter['link'] . '">' . htmlspecialchars($replacement) . '</a>';
			} else {
				$replacements[] = '<strong>' . htmlspecialchars($replacement) . '</strong>';
			}
		}

		return str_replace($placeholders, $replacements, $event->getRichSubject());
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

		try {
			$this->richObjectValidator->validate($event->getRichSubject(), $event->getRichSubjectParameters());
		} catch (InvalidObjectExeption $e) {
			$this->logger->logException($e);
			$event->setRichSubject('Rich subject or a parameter for "' . $event->getRichSubject() . '" is malformed', []);
			$event->setParsedSubject('Rich subject or a parameter for "' . $event->getRichSubject() . '" is malformed');
		}

		if ($event->getRichMessage()) {
			try {
				$this->richObjectValidator->validate($event->getRichMessage(), $event->getRichMessageParameters());
			} catch (InvalidObjectExeption $e) {
				$this->logger->logException($e);
				$event->setRichMessage('Rich message or a parameter is malformed', []);
				$event->setParsedMessage('Rich message or a parameter is malformed');
			}
		}

		if (!$event->getParsedSubject()) {
			$this->logger->debug('Activity "' . $event->getRichSubject() . '" was not parsed by any provider');
			throw new \InvalidArgumentException('Activity "' . $event->getRichSubject() . '" was not parsed by any provider');
		}

		return $event;
	}

	/**
	 * Delete all entries we dealt with
	 *
	 * @param array $affectedUsers
	 * @param int $maxTime
	 */
	protected function deleteSentItems(array $affectedUsers, $maxTime) {
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
