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
* You should have received a copy of the GNU Affero General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace OCA\Activity\Controller;

use OCA\Activity\Data;
use OCA\Activity\UserSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;

class Settings extends Controller {
	/** @var \OCP\IConfig */
	protected $config;

	/** @var \OCA\Activity\Data */
	protected $dataHelper;

	/** @var \OCP\IL10N */
	protected $l10n;

	/** @var string */
	protected $user;

	/**
	 * constructor of the controller
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param IConfig $config
	 * @param Data $data
	 * @param IL10N $l10n
	 * @param string $user
	 */
	public function __construct($appName, IRequest $request, IConfig $config, Data $data, IL10N $l10n, $user) {
		parent::__construct($appName, $request);
		$this->config = $config;
		$this->dataHelper = $data;
		$this->l10n = $l10n;
		$this->user = $user;
	}

	/**
	 * @NoAdminRequired
	 *
	 * @return DataResponse
	 */
	public function personal() {
		$types = $this->dataHelper->getNotificationTypes($this->l10n);

		foreach ($types as $type => $desc) {
			$this->config->setUserValue(
				$this->user, 'activity',
				'notify_email_' . $type,
				$this->request->getParam($type . '_email', false)
			);

			$this->config->setUserValue(
				$this->user, 'activity',
				'notify_stream_' . $type,
				$this->request->getParam($type . '_stream', false)
			);
		}

		$email_batch_time = 3600;
		if ($this->request->getParam('notify_setting_batchtime') == UserSettings::EMAIL_SEND_DAILY) {
			$email_batch_time = 3600 * 24;
		}
		if ($this->request->getParam('notify_setting_batchtime') == UserSettings::EMAIL_SEND_WEEKLY) {
			$email_batch_time = 3600 * 24 * 7;
		}

		$this->config->setUserValue(
			$this->user, 'activity',
			'notify_setting_batchtime',
			$email_batch_time
		);
		$this->config->setUserValue(
			$this->user, 'activity',
			'notify_setting_self',
			$this->request->getParam('notify_setting_self', false)
		);
		$this->config->setUserValue(
			$this->user, 'activity',
			'notify_setting_selfemail',
			$this->request->getParam('notify_setting_selfemail', false)
		);

		return new DataResponse(array(
			'status'	=>'success',
			'data'		=> array(
				'message'	=> $this->l10n->t('Your settings have been updated.'),
			),
		));
	}
}
