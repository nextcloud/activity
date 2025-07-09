<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\BackgroundJob;

use OCP\Activity\IExtension;
use OCP\Activity\IManager;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class RemoveFormerActivitySettings extends TimedJob {
	public function __construct(
		ITimeFactory $time,
		protected IManager $manager,
		protected IDBConnection $db,
	) {
		parent::__construct($time);
		$this->setInterval(24 * 60 * 60);
	}

	#[\Override]
	protected function run($argument): void {
		$preferencesToKeep = [
			'notify_setting_activity_digest',
			'notify_setting_batchtime',
			'notify_setting_self',
			'notify_setting_selfemail',
		];
		foreach ($this->manager->getSettings() as $setting) {
			if ($setting->canChangeMail()) {
				$preferencesToKeep[] = 'notify_' . IExtension::METHOD_MAIL . '_' . $setting->getIdentifier();
			}
			if ($setting->canChangeNotification()) {
				$preferencesToKeep[] = 'notify_' . IExtension::METHOD_NOTIFICATION . '_' . $setting->getIdentifier();
			}
		}

		$this->removeFormerPreference($preferencesToKeep);
	}

	protected function removeFormerPreference(array $preferencesToKeep): void {
		$query = $this->db->getQueryBuilder();
		$query->delete('preferences')
			->where($query->expr()->eq('appid', $query->createNamedParameter('activity')))
			->andWhere($query->expr()->like('configkey', $query->createNamedParameter($this->db->escapeLikeParameter('notify_') . '%')))
			->andWhere($query->expr()->notIn('configkey', $query->createNamedParameter($preferencesToKeep, IQueryBuilder::PARAM_STR_ARRAY)));
		$query->executeStatement();
	}
}
