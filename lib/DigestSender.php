<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2020 Robin Appelman <robin@icewind.nl>
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

namespace OCA\Activity;

use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Defaults;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Mail\IMailer;
use OCP\Util;
use Psr\Log\LoggerInterface;

class DigestSender {
	public const ACTIVITY_LIMIT = 20;

	public function __construct(
		private IConfig $config,
		private Data $data,
		private UserSettings $userSettings,
		private GroupHelper $groupHelper,
		private IMailer $mailer,
		private IManager $activityManager,
		private IUserManager $userManager,
		private IURLGenerator $urlGenerator,
		private Defaults $defaults,
		private IFactory $l10nFactory,
		private IDateTimeFormatter $dateTimeFormatter,
		private LoggerInterface $logger
	) {
	}

	public function sendDigests(int $now): void {
		$users = $this->getDigestUsers();
		$userLanguages = $this->config->getUserValueForUsers('core', 'lang', $users);
		$userTimezones = $this->config->getUserValueForUsers('core', 'timezone', $users);
		$digestDate = $this->config->getUserValueForUsers('activity', 'digest', $users);
		$defaultLanguage = $this->config->getSystemValue('default_language', 'en');
		$defaultTimeZone = date_default_timezone_get();
		$timezoneDigestDay = [];
		$this->activityManager->setRequirePNG(true);

		foreach ($users as $user) {
			$language = (!empty($userLanguages[$user])) ? $userLanguages[$user] : $defaultLanguage;
			$timezone = (!empty($userTimezones[$user])) ? $userTimezones[$user] : $defaultTimeZone;

			// Check if the user's timezone is after 6am already
			if (!isset($timezoneDigestDay[$timezone])) {
				$timezoneDate = new \DateTime('now', new \DateTimeZone($timezone));
				if ($timezoneDate->format('H') < 6) {
					// Still before 6am, so dont send yet.
					$timezoneDate->sub(new \DateInterval('P1D'));
				}
				$timezoneDigestDay[$timezone] = $timezoneDate->format('Y.m.d');
			}

			$userDigestDate = $digestDate[$user] ?? '';
			if ($userDigestDate === $timezoneDigestDay[$timezone]) {
				// User got todays digest already
				continue;
			}
			$userObject = $this->userManager->get($user);
			if (!$userObject->isEnabled()) {
				// User is disabled so do not send the email but update last sent since after enabling avoid flooding
				$this->updateLastSentForUser($userObject, $now);
				continue;
			}

			try {
				$this->sendDigestForUser($userObject, $now, $timezone, $language);
			} catch (\Throwable $e) {
				$this->logger->error('Exception occurred while sending user digest email', [
					'exception' => $e,
				]);
			}
			// We still update the digest time after an failed email,
			// so it hopefully works tomorrow
			$this->config->setUserValue($userObject->getUID(), 'activity', 'digest', $timezoneDigestDay[$timezone]);
		}

		$this->activityManager->setRequirePNG(false);
	}

	/**
	 * get all users who have activity digest enabled
	 *
	 * @return string[]
	 */
	private function getDigestUsers(): array {
		return $this->config->getUsersForUserValue('activity', 'notify_setting_activity_digest', '1');
	}

	private function getLastSendActivity(string $user, int $now): int {
		$lastSend = (int)$this->config->getUserValue($user, 'activity', 'activity_digest_last_send', 0);
		if ($lastSend > 0) {
			return $lastSend;
		}

		// Don't flood on first email with old news, just consider the last 24h
		return $this->data->getFirstActivitySince($user, $now - (24 * 60 * 60));
	}

	private function updateLastSentForUser(IUser $user, int $now): void {
		$uid = $user->getUID();
		$lastSend = $this->getLastSendActivity($uid, $now);

		['max' => $lastActivityId] = $this->data->getActivitySince($uid, $lastSend, true);
		$lastActivityId = (int)$lastActivityId;

		$this->config->setUserValue($uid, 'activity', 'activity_digest_last_send', (string)$lastActivityId);
	}

	public function sendDigestForUser(IUser $user, int $now, string $timezone, string $language) {
		$uid = $user->getUID();
		$l10n = $this->l10nFactory->get('activity', $language);
		$this->groupHelper->setL10n($l10n);
		$lastSend = $this->getLastSendActivity($uid, $now);
		if ($lastSend === 0) {
			return;
		}
		$this->activityManager->setCurrentUserId($uid);

		['count' => $count, 'max' => $lastActivityId] = $this->data->getActivitySince($uid, $lastSend, true);
		$count = (int)$count;
		$lastActivityId = (int)$lastActivityId;
		if ($count === 0) {
			return;
		}

		/** @var IEvent[] $activities */
		$activities = $this->data->get(
			$this->groupHelper,
			$this->userSettings,
			$uid,
			$lastSend,
			self::ACTIVITY_LIMIT,
			'asc',
			'by',
			'',
			0,
			true
		);
		$skippedCount = max(0, $count - self::ACTIVITY_LIMIT);

		$template = $this->mailer->createEMailTemplate('activity.Notification', [
			'displayname' => $user->getDisplayName(),
			'url' => $this->urlGenerator->getAbsoluteURL('/'),
			'activityEvents' => $activities,
			'skippedCount' => $skippedCount,
		]);
		$template->setSubject($l10n->t('Daily activity summary for %s', $this->defaults->getName()));
		$template->addHeader();

		foreach ($activities as $event) {
			$relativeDateTime = $this->dateTimeFormatter->formatDateTimeRelativeDay(
				$event->getTimestamp(),
				'long',
				'short',
				new \DateTimeZone($timezone),
				$l10n
			);

			$template->addBodyListItem($this->getHTMLSubject($event), $relativeDateTime, $event->getIcon(), $event->getParsedSubject());
		}

		if ($skippedCount) {
			$template->addBodyListItem($l10n->n('and %n more ', 'and %n more ', $skippedCount));
		}

		$template->addFooter('', $language);

		$message = $this->mailer->createMessage();
		$message->setTo([$user->getEMailAddress() => $user->getDisplayName()]);
		$message->useTemplate($template);
		$message->setFrom([Util::getDefaultEmailAddress('no-reply') => $this->defaults->getName()]);

		$this->activityManager->setCurrentUserId(null);
		try {
			$this->mailer->send($message);
			$this->config->setUserValue($uid, 'activity', 'activity_digest_last_send', (string)$lastActivityId);
		} catch (\Exception $e) {
			$this->logger->error($e->getMessage());
			return;
		}
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
				$replacement = (string)$parameter['path'];
			} else {
				$replacement = (string)$parameter['name'];
			}

			if (isset($parameter['link'])) {
				$replacements[] = '<a href="' . $parameter['link'] . '">' . htmlspecialchars($replacement) . '</a>';
			} else {
				$replacements[] = '<strong>' . htmlspecialchars($replacement) . '</strong>';
			}
		}

		return str_replace($placeholders, $replacements, $event->getRichSubject());
	}
}
