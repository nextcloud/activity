<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
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

namespace OCA\Activity\Controller;

use OCA\Activity\CurrentUser;
use OCA\Activity\Data;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IURLGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class Activities extends Controller {

	/** @var IConfig */
	protected $config;

	/** @var Data */
	protected $data;

	/** @var IURLGenerator */
	protected $urlGenerator;

	/** @var CurrentUser */
	protected $currentUser;

	/** @var EventDispatcherInterface */
	protected $eventDispatcher;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param IConfig $config
	 * @param Data $data
	 * @param IURLGenerator $urlGenerator
	 * @param CurrentUser $currentUser
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function __construct($appName,
								IRequest $request,
								IConfig $config,
								Data $data,
								IURLGenerator $urlGenerator,
								CurrentUser $currentUser,
								EventDispatcherInterface $eventDispatcher) {
		parent::__construct($appName, $request);
		$this->data = $data;
		$this->config = $config;
		$this->urlGenerator = $urlGenerator;
		$this->currentUser = $currentUser;
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $filter
	 * @return TemplateResponse
	 */
	public function showList($filter = 'all') {
		$filter = $this->data->validateFilter($filter);

		$event = new GenericEvent($filter);
		$this->eventDispatcher->dispatch('OCA\Activity::loadAdditionalScripts', $event);

		return new TemplateResponse('activity', 'stream.body', [
			'filter'		=> $filter,
			'feed_link'		=> $this->getFeedLink(),
		]);
	}

	protected function getFeedLink(): string {
		$rssToken = $this->config->getUserValue($this->currentUser->getUID(), 'activity', 'rsstoken');
		if (!$rssToken) {
			return '';
		}
		return $this->urlGenerator->linkToRouteAbsolute('activity.Feed.show', array('token' => $rssToken));
	}
}
