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

use OCA\Activity\Data;
use OCA\Activity\Event\LoadAdditionalScriptsEvent;
use OCA\Activity\Navigation;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;

class ActivitiesController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		protected IConfig $config,
		protected Data $data,
		protected Navigation $navigation,
		protected IEventDispatcher $eventDispatcher,
		private IL10N $l10n
	) {
		parent::__construct($appName, $request);
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

		$event = new LoadAdditionalScriptsEvent($filter);
		$this->eventDispatcher->dispatchTyped($event);
		$this->eventDispatcher->dispatch(LoadAdditionalScriptsEvent::EVENT_ENTITY, $event);

		return new TemplateResponse('activity', 'stream.body', [
			'appNavigation' => $this->navigation->getTemplate($filter),
			'avatars' => $this->config->getSystemValue('enable_avatars', true) ? 'yes' : 'no',
			'filter' => $filter,
			'pageTitle' => $this->l10n->t('Activity')
		]);
	}
}
