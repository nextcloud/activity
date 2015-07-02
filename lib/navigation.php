<?php

/**
 * ownCloud - Activity App
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Activity;

use \OCP\Activity\IManager;
use \OCP\IURLGenerator;
use \OCP\Template;

/**
 * Class Navigation
 *
 * @package OCA\Activity
 */
class Navigation {
	/** @var \OC_L10N */
	protected $l;

	/** @var \OCP\Activity\IManager */
	protected $activityManager;

	/** @var \OCP\IURLGenerator */
	protected $URLGenerator;

	/** @var UserSettings */
	protected $userSettings;

	/** @var string */
	protected $active;

	/** @var string */
	protected $user;

	/** @var string */
	protected $rssLink;

	/**
	 * Construct
	 *
	 * @param \OC_L10N $l
	 * @param \OCP\Activity\IManager $manager
	 * @param \OCP\IURLGenerator $URLGenerator
	 * @param UserSettings $userSettings
	 * @param string $user
	 * @param string $rssToken
	 * @param null|string $active Navigation entry that should be marked as active
	 */
	public function __construct(\OC_L10N $l,
								IManager $manager,
								IURLGenerator $URLGenerator,
								UserSettings $userSettings,
								$user,
								$rssToken,
								$active = 'all') {
		$this->l = $l;
		$this->activityManager = $manager;
		$this->URLGenerator = $URLGenerator;
		$this->userSettings = $userSettings;
		$this->user = $user;
		$this->active = $active;

		if ($rssToken) {
			$this->rssLink = $this->URLGenerator->getAbsoluteURL(
				$this->URLGenerator->linkToRoute('activity.Feed.show', array('token' => $rssToken))
			);
		} else {
			$this->rssLink = '';
		}
	}

	/**
	 * Get the users we want to send an email to
	 *
	 * @param null|string $forceActive Navigation entry that should be marked as active
	 * @return \OCP\Template
	 */
	public function getTemplate($forceActive = null) {
		$active = $forceActive ?: $this->active;

		$template = new Template('activity', 'stream.app.navigation', '');
		$entries = $this->getLinkList();

		if (sizeof($entries['apps']) === 1) {
			// If there is only the files app, we simply do not show it,
			// as it is the same as the 'all' filter.
			$entries['apps'] = array();
		}

		$template->assign('activeNavigation', $active);
		$template->assign('navigations', $entries);
		$template->assign('rssLink', $this->rssLink);

		return $template;
	}

	/**
	 * Get all items for the users we want to send an email to
	 *
	 * @return array Notification data (user => array of rows from the table)
	 */
	public function getLinkList() {
		$topEntries = [
			[
				'id' => 'all',
				'name' => (string) $this->l->t('All Activities'),
				'url' => $this->URLGenerator->linkToRoute('activity.Activities.showList'),
			],
		];

		if ($this->user && $this->userSettings->getUserSetting($this->user, 'setting', 'self')) {
			$topEntries[] = [
				'id' => 'self',
				'name' => (string) $this->l->t('Activities by you'),
				'url' => $this->URLGenerator->linkToRoute('activity.Activities.showList', array('filter' => 'self')),
			];
			$topEntries[] = [
				'id' => 'by',
				'name' => (string) $this->l->t('Activities by others'),
				'url' => $this->URLGenerator->linkToRoute('activity.Activities.showList', array('filter' => 'by')),
			];
		}

		$additionalEntries = $this->activityManager->getNavigation();
		$topEntries = array_merge($topEntries, $additionalEntries['top']);

		return array(
			'top'		=> $topEntries,
			'apps'		=> $additionalEntries['apps'],
		);
	}
}
