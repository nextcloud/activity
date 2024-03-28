<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Lukas Reschke <lukas@statuscode.ch>
 * @author Thomas Citharel <nextcloud@tcit.fr>
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
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\Headers\AutoSubmitted;
use OCP\Mail\IMailer;
use OCP\RichObjectStrings\IValidator;
use OCP\Util;
use Psr\Log\LoggerInterface;

/**
 * Class MailQueueHandler
 * Gets the users from the database and
 *
 * @package OCA\Activity
 */
class MailQueueHandler {
	public const CLI_EMAIL_BATCH_SIZE = 500;
	public const WEB_EMAIL_BATCH_SIZE = 25;
	/** Number of entries we want to list in the email */
	public const ENTRY_LIMIT = 200;

	protected array $languages;
	protected string $senderAddress;
	protected string $senderName;

	public function __construct(
		protected IDateTimeFormatter $dateFormatter,
		protected IDBConnection $connection,
		protected IMailer $mailer,
		protected IURLGenerator $urlGenerator,
		protected IUserManager $userManager,
		protected IFactory $lFactory,
		protected IManager $activityManager,
		protected IValidator $richObjectValidator,
		protected IConfig $config,
		protected LoggerInterface $logger,
		protected Data $data,
		protected GroupHelper $groupHelper,
		protected UserSettings $userSettings,
	) {
	}

	/**
	 * Send an email to {$limit} users
	 *
	 * @param $limit Number of users we want to send an email to
	 * @param $sendTime The latest send time
	 * @param $forceSending Ignores latest send and just sends all emails
	 * @param $restrictEmails null or one of UserSettings::EMAIL_SEND_*
	 * @return int Number of users we sent an email to
	 */
	public function sendEmails(int $limit, int $sendTime, bool $forceSending = false, ?int $restrictEmails = null): int {
		// Get all users which should receive an email
		$affectedUsers = $this->getAffectedUsers($limit, $sendTime, $forceSending, $restrictEmails);
		if (empty($affectedUsers)) {
			// No users found to notify, mission abort
			return 0;
		}

		$userLanguages = $this->config->getUserValueForUsers('core', 'lang', $affectedUsers);
		$userTimezones = $this->config->getUserValueForUsers('core', 'timezone', $affectedUsers);
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

			$userObject = $this->userManager->get($user);
			$email = $userObject ? $userObject->getEMailAddress() : '';
			if (empty($email)) {
				// The user did not setup an email address
				// So we will not send an email :(
				$this->logger->debug("Couldn't send notification email to user '{user}' (email address isn't set for that user)", ['user' => $user, 'app' => 'activity']);
				$deleteItemsForUsers[] = $user;
				continue;
			}

			$language = (!empty($userLanguages[$user])) ? $userLanguages[$user] : $default_lang;
			$timezone = (!empty($userTimezones[$user])) ? $userTimezones[$user] : $defaultTimeZone;
			try {
				if ($this->sendEmailToUser($user, $email, $language, $timezone, $sendTime)) {
					$deleteItemsForUsers[] = $user;
				} else {
					$this->logger->warning("Failed sending activity email to user '{user}'.", ['user' => $user, 'app' => 'activity']);
				}
			} catch (\Exception $e) {
				$this->logger->error('Failed creating activity email for user "{user}"', [
					'exception' => $e,
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
	 */
	protected function getAffectedUsers(?int $limit, int $latestSend, bool $forceSending, ?int $restrictEmails): array {
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
			} elseif ($restrictEmails === UserSettings::EMAIL_SEND_DAILY) {
				$query->where($query->expr()->eq('amq_timestamp', $query->func()->subtract('amq_latest_send', $query->expr()->literal(3600 * 24))));
			} elseif ($restrictEmails === UserSettings::EMAIL_SEND_WEEKLY) {
				$query->where($query->expr()->eq('amq_timestamp', $query->func()->subtract('amq_latest_send', $query->expr()->literal(3600 * 24 * 7))));
			} elseif ($restrictEmails === UserSettings::EMAIL_SEND_ASAP) {
				$query->where($query->expr()->eq('amq_timestamp', 'amq_latest_send'));
			}
		}

		$result = $query->execute();

		$affectedUsers = [];
		while ($row = $result->fetch()) {
			$affectedUsers[] = $row['amq_affecteduser'];
		}
		$result->closeCursor();

		return $affectedUsers;
	}

	/**
	 * Get all items for the user we want to send an email to
	 *
	 * @return array [data of the first max. 200 entries, total number of entries]
	 */
	protected function getItemsForUser(string $affectedUser, int $maxTime, int $maxNumItems = self::ENTRY_LIMIT): array {
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

	public function purgeItemsForUser(string $affectedUser): void {
		$queryBuilder = $this->connection->getQueryBuilder();

		$queryBuilder->delete('activity_mq')
			->where($queryBuilder->expr()->eq('amq_affecteduser', $queryBuilder->createNamedParameter($affectedUser)));
		$queryBuilder->executeStatement();
	}

	/**
	 * Get a language object for a specific language
	 *
	 * @param string $lang Language identifier
	 * @return IL10N Language object of $lang
	 */
	protected function getLanguage(string $lang): IL10N {
		if (!isset($this->languages[$lang])) {
			$this->languages[$lang] = $this->lFactory->get('activity', $lang);
		}

		return $this->languages[$lang];
	}

	/**
	 * Get the sender data
	 * @param string $setting Either `email` or `name`
	 */
	protected function getSenderData(string $setting): string {
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
	 * @return bool True if the entries should be removed, false otherwise
	 * @throws \UnexpectedValueException
	 */
	protected function sendEmailToUser(string $userName, string $email, string $lang, string $timezone, int $maxTime): bool {
		$user = $this->userManager->get($userName);
		if (!$user instanceof IUser) {
			return true;
		}

		if (!$this->mailer->validateMailAddress($email)) {
			$this->logger->warning('Notification for user "{user}" not sent because the email address "{email}" is invalid.', ['user' => $userName, 'email' => $email]);
			return true;
		}

		[$mailData, $skippedCount] = $this->getItemsForUser($userName, $maxTime);

		$l = $this->getLanguage($lang);
		$this->activityManager->setCurrentUserId($userName);

		$this->groupHelper->resetEvents();
		$this->groupHelper->setL10n($l);

		foreach ($mailData as $activity) {
			$event = $this->activityManager->generateEvent();
			try {
				$event->setApp((string) $activity['amq_appid'])
					->setType((string) $activity['amq_type'])
					->setAffectedUser((string) $activity['amq_affecteduser'])
					->setTimestamp((int) $activity['amq_timestamp'])
					->setSubject((string) $activity['amq_subject'], (array) json_decode($activity['amq_subjectparams'], true))
					->setObject((string) $activity['object_type'], (int) $activity['object_id']);
			} catch (\InvalidArgumentException $e) {
				continue;
			}

			$this->groupHelper->addEvent($activity['mail_id'], $event);
		}

		$activityEvents = array_map(
			function ($event) use ($timezone, $l) {
				return [
					'event' => $event,
					'relativeDateTime' => $this->dateFormatter->formatDateTimeRelativeDay(
						$event->getTimestamp(),
						'long', 'short',
						new \DateTimeZone($timezone), $l
					)
				];
			},
			$this->groupHelper->getEvents()
		);

		$template = $this->mailer->createEMailTemplate('activity.Notification', [
			'displayname' => $user->getDisplayName(),
			'url' => $this->urlGenerator->getAbsoluteURL('/'),
			'activityEvents' => $activityEvents,
			'skippedCount' => $skippedCount,
		]);
		$template->setSubject($l->t('Activity at %s', $this->getSenderData('name')));
		$template->addHeader();
		$template->addHeading($l->t('Hello %s', [$user->getDisplayName()]), $l->t('Hello %s,', [$user->getDisplayName()]));

		$homeLink = '<a href="' . $this->urlGenerator->getAbsoluteURL('/') . '">' . htmlspecialchars($this->getSenderData('name')) . '</a>';
		$template->addBodyText(
			$l->t('There was some activity at %s', [$homeLink]),
			$l->t('There was some activity at %s', [$this->urlGenerator->getAbsoluteURL('/')])
		);

		foreach ($activityEvents as $activity) {
			$event = $activity['event'];
			$relativeDateTime = $activity['relativeDateTime'];

			$template->addBodyListItem($this->getHTMLSubject($event), $relativeDateTime, $event->getIcon(), $event->getParsedSubject());
		}

		if ($skippedCount) {
			$template->addBodyListItem($l->n('and %n more ', 'and %n more ', $skippedCount));
		}

		$template->addFooter('', $lang);

		$message = $this->mailer->createMessage();
		$message->setTo([$email => $user->getDisplayName()]);
		$message->useTemplate($template);
		$message->setFrom([$this->getSenderData('email') => $this->getSenderData('name')]);

		// We don't want auto generated responses to autogenerated activity notifications
		$message->setAutoSubmitted(AutoSubmitted::VALUE_AUTO_GENERATED);

		try {
			$this->mailer->send($message);
		} catch (\Exception $e) {
			$this->logger->error('Failed sending activity email to user "{user}"', [
				'exception' => $e,
				'user' => $userName,
				'app' => 'activity',
			]);
			return false;
		}

		$this->activityManager->setCurrentUserId(null);
		return true;
	}

	protected function getHTMLSubject(IEvent $event): string {
		if ($event->getRichSubject() === '') {
			return htmlspecialchars($event->getParsedSubject());
		}

		$placeholders = $replacements = [];
		foreach ($event->getRichSubjectParameters() as $placeholder => $parameter) {
			$placeholders[] = '{' . $placeholder . '}';

			if ($parameter['type'] === 'file') {
				$replacement = (string) $parameter['path'];
			} else {
				$replacement = (string) $parameter['name'];
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
	 * Delete all entries we dealt with
	 */
	protected function deleteSentItems(array $affectedUsers, int $maxTime): void {
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
