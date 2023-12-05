<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2023 Joas Schilling <coding@schilljs.com>
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
