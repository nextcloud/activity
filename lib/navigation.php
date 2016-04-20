<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Roeland Jago Douma <rullzer@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
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


use OCP\Activity\IManager;
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

	/** @var string */
	protected $active;

	/** @var string */
	protected $user;

	/** @var string */
	protected $rssLink;

	/**
	 * Construct
	 *
	 * @param IL10N $l
	 * @param IManager $manager
	 * @param IURLGenerator $URLGenerator
	 * @param string $user
	 * @param string $rssToken
	 * @param null|string $active Navigation entry that should be marked as active
	 */
	public function __construct(IL10N $l,
								IManager $manager,
								IURLGenerator $URLGenerator,
								$user,
								$rssToken,
								$active = 'all') {
		$this->l = $l;
		$this->activityManager = $manager;
		$this->URLGenerator = $URLGenerator;
		$this->user = $user;
		$this->active = $active;

		if ($rssToken) {
			$this->rssLink = $this->URLGenerator->linkToRouteAbsolute('activity.Feed.show', array('token' => $rssToken));
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

		$additionalEntries = $this->activityManager->getNavigation();
		$topEntries = array_merge($topEntries, $additionalEntries['top']);

		return array(
			'top'		=> $topEntries,
			'apps'		=> $additionalEntries['apps'],
		);
	}
}
