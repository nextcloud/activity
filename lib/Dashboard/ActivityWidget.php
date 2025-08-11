<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2021 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Dashboard;

use OCA\Activity\AppInfo\Application;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\Dashboard\IAPIWidget;
use OCP\Dashboard\IButtonWidget;
use OCP\Dashboard\IIconWidget;
use OCP\Dashboard\IOptionWidget;
use OCP\Dashboard\IReloadableWidget;
use OCP\Dashboard\Model\WidgetButton;
use OCP\Dashboard\Model\WidgetItem;
use OCP\Dashboard\Model\WidgetItems;
use OCP\Dashboard\Model\WidgetOptions;
use OCP\IDateTimeFormatter;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Util;

class ActivityWidget implements IAPIWidget, IButtonWidget, IIconWidget, IReloadableWidget, IOptionWidget {
	private Data $data;
	private IL10N $l10n;
	private GroupHelper $helper;
	private UserSettings $settings;
	private IDateTimeFormatter $dateTimeFormatter;
	private IURLGenerator $urlGenerator;

	public function __construct(IL10N $l10n,
		Data $data,
		GroupHelper $helper,
		UserSettings $settings,
		IURLGenerator $urlGenerator,
		IDateTimeFormatter $dateTimeFormatter) {
		$this->data = $data;
		$this->l10n = $l10n;
		$this->helper = $helper;
		$this->settings = $settings;
		$this->dateTimeFormatter = $dateTimeFormatter;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getId(): string {
		return Application::APP_ID;
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getTitle(): string {
		return $this->l10n->t('Recent activity');
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getOrder(): int {
		return 20;
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getIconClass(): string {
		return 'icon-activity';
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getIconUrl(): string {
		return $this->urlGenerator->getAbsoluteURL(
			$this->urlGenerator->imagePath(Application::APP_ID, 'activity-dark.svg')
		);
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getUrl(): ?string {
		return $this->urlGenerator->getAbsoluteURL(
			$this->urlGenerator->linkToRoute(Application::APP_ID . '.Activities.index')
		);
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function load(): void {
		Util::addStyle('activity', 'style');
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getItems(string $userId, ?string $since = null, int $limit = 7): array {
		// we set the limit to 50 here because data->get might return less activity entries
		// in the end we take the first 7 of'em
		$activities = $this->data->get(
			$this->helper,
			$this->settings,
			$userId,
			$since ? (int)$since : 0,
			50,
			'desc',
			'by',
			'',
			0
		);
		return array_map(function (array $activity) {
			return new WidgetItem(
				$activity['subject'],
				$this->dateTimeFormatter->formatTimeSpan($activity['timestamp']),
				$activity['link'],
				$activity['icon'],
				(string)$activity['activity_id']
			);
		}, array_slice($activities['data'], 0, $limit));
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getItemsV2(string $userId, ?string $since = null, int $limit = 7): WidgetItems {
		// we set the limit to 50 here because data->get might return less activity entries
		// in the end we take the first 7 of'em
		$activities = $this->data->get(
			$this->helper,
			$this->settings,
			$userId,
			$since ? (int)$since : 0,
			50,
			'desc',
			'by',
			'',
			0
		);
		$items = array_map(function (array $activity) {
			$userAvatarUrl = '';
			if ($activity['user'] !== '') {
				$userAvatarUrl = $this->urlGenerator->getAbsoluteURL(
					$this->urlGenerator->linkToRoute('core.avatar.getAvatar', [
						'userId' => $activity['user'],
						'size' => 512,
					])
				);
			}

			return new WidgetItem(
				$activity['subject'],
				$this->dateTimeFormatter->formatTimeSpan($activity['timestamp']),
				$activity['link'],
				$userAvatarUrl,
				(string)$activity['activity_id'],
				$activity['icon'],
			);
		}, array_slice($activities['data'], 0, $limit));
		return new WidgetItems(
			$items,
			empty($items) ? $this->l10n->t('No activities') : '',
		);
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getWidgetButtons(string $userId): array {
		return [
			new WidgetButton(
				WidgetButton::TYPE_MORE,
				$this->urlGenerator->getAbsoluteURL(
					$this->urlGenerator->linkToRoute(Application::APP_ID . '.Activities.index')
				),
				$this->l10n->t('More activities')
			),
		];
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getReloadInterval(): int {
		return 30;
	}

	/**
	 * @inheritDoc
	 */
	#[\Override]
	public function getWidgetOptions(): WidgetOptions {
		return new WidgetOptions(true);
	}
}
