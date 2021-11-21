<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author Roeland Jago Douma <roeland@famdouma.nl>
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

use OCP\Activity\IFilter;
use OCP\Activity\IManager;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Template;

/**
 * Class Navigation
 *
 * @package OCA\Activity
 */
class Navigation {
	/** @var IL10N */
	protected $l;

	/** @var IManager */
	protected $activityManager;

	/** @var IURLGenerator */
	protected $URLGenerator;

	/** @var IConfig */
	protected $config;

	/** @var CurrentUser */
	protected $currentUser;

	/**
	 * Construct
	 *
	 * @param IL10N $l
	 * @param IManager $manager
	 * @param IURLGenerator $URLGenerator
	 * @param IConfig $config
	 * @param CurrentUser $currentUser
	 */
	public function __construct(IL10N $l,
								IManager $manager,
								IURLGenerator $URLGenerator,
								IConfig $config,
								CurrentUser $currentUser) {
		$this->l = $l;
		$this->activityManager = $manager;
		$this->URLGenerator = $URLGenerator;
		$this->config = $config;
		$this->currentUser = $currentUser;
	}

	/**
	 * Get the users we want to send an email to
	 *
	 * @param null|string $forceActive Navigation entry that should be marked as active
	 * @return \OCP\Template
	 */
	public function getTemplate($forceActive = 'all') {
		$active = $forceActive ?: 'all';

		$template = new Template('activity', 'stream.app.navigation', '');

		$template->assign('activeNavigation', $active);
		$template->assign('navigations', $this->getLinkList());
		$template->assign('rssLink', $this->getRSSLink());
		$template->assign('personalSettingsLink', $this->getPersonalSettingsLink());

		return $template;
	}

	/**
	 * @return string
	 */
	protected function getRSSLink() {
		$rssToken = $this->config->getUserValue($this->currentUser->getUID(), 'activity', 'rsstoken');
		if ($rssToken) {
			return $this->URLGenerator->linkToRouteAbsolute('activity.Feed.show', ['token' => $rssToken]);
		} else {
			return '';
		}
	}

	/**
	 * Get all items for the users we want to send an email to
	 *
	 * @return array Notification data (user => array of rows from the table)
	 */
	public function getLinkList() {
		$filters = $this->activityManager->getFilters();
		usort($filters, static function (IFilter $a, IFilter $b) {
			if ($a->getPriority() === $b->getPriority()) {
				return (int) ($a->getIdentifier() > $b->getIdentifier());
			}

			return (int) ($a->getPriority() > $b->getPriority());
		});

		$entries = [];
		foreach ($filters as $filter) {
			$entries[] = [
				'id' => $filter->getIdentifier(),
				'icon' => $filter->getIcon(),
				'name' => $filter->getName(),
				'url' => $this->URLGenerator->linkToRoute('activity.Activities.showList', ['filter' => $filter->getIdentifier()]),
			];
		}

		return $entries;
	}

	/**
	 * @return string
	 */
	protected function getPersonalSettingsLink() {
		return $this->URLGenerator->linkToRouteAbsolute('settings.PersonalSettings.index', ['section' => 'notifications']);
	}
}
