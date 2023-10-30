<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2021 Jakob Röhrl <jakob.roehrl@web.de>
 *
 * @author Jakob Röhrl <jakob.roehrl@web.de>
 * @author Richard Steinmetz <richard@steinmetz.cloud>
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity\Dashboard;

use OCA\Activity\AppInfo\Application;
use OCA\Activity\Data;
use OCA\Activity\GroupHelper;
use OCA\Activity\UserSettings;
use OCP\Dashboard\IAPIWidget;
use OCP\Dashboard\IButtonWidget;
use OCP\Dashboard\IIconWidget;
use OCP\Dashboard\IReloadableWidget;
use OCP\Dashboard\Model\WidgetButton;
use OCP\Dashboard\Model\WidgetItem;
use OCP\Dashboard\Model\WidgetItems;
use OCP\IDateTimeFormatter;
use OCP\IL10N;
use OCP\IURLGenerator;

class ActivityWidget implements IAPIWidget, IButtonWidget, IIconWidget, IReloadableWidget {
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
	public function getId(): string {
		return Application::APP_ID;
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): string {
		return $this->l10n->t('Recent activity');
	}

	/**
	 * @inheritDoc
	 */
	public function getOrder(): int {
		return 20;
	}

	/**
	 * @inheritDoc
	 */
	public function getIconClass(): string {
		return 'icon-activity';
	}

	/**
	 * @inheritDoc
	 */
	public function getIconUrl(): string {
		return $this->urlGenerator->getAbsoluteURL(
			$this->urlGenerator->imagePath(Application::APP_ID, 'activity-dark.svg')
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getUrl(): ?string {
		return $this->urlGenerator->getAbsoluteURL(
			$this->urlGenerator->linkToRoute(Application::APP_ID . '.Activities.index')
		);
	}

	/**
	 * @inheritDoc
	 */
	public function load(): void {
	}

	/**
	 * @inheritDoc
	 */
	public function getItems(string $userId, ?string $since = null, int $limit = 7): array {
		// we set the limit to 50 here because data->get might return less activity entries
		// in the end we take the first 7 of'em
		$activities = $this->data->get(
			$this->helper,
			$this->settings,
			$userId,
			$since ? (int) $since : 0,
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
				(string) $activity['activity_id']
			);
		}, array_slice($activities['data'], 0, $limit));
	}

	/**
	 * @inheritDoc
	 */
	public function getItemsV2(string $userId, ?string $since = null, int $limit = 7): WidgetItems {
		// we set the limit to 50 here because data->get might return less activity entries
		// in the end we take the first 7 of'em
		$activities = $this->data->get(
			$this->helper,
			$this->settings,
			$userId,
			$since ? (int) $since : 0,
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
				(string) $activity['activity_id'],
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
	public function getReloadInterval(): int {
		return 30;
	}
}
