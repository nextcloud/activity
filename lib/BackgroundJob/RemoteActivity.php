<?php
/**
 * @copyright Copyright (c) 2017 Joas Schilling <coding@schilljs.com>
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

use GuzzleHttp\Exception\ClientException;
use OC\BackgroundJob\QueuedJob;
use OCP\Federation\ICloudId;
use OCP\Federation\ICloudIdManager;
use OCP\Http\Client\IClientService;

class RemoteActivity extends QueuedJob {

	/** @var IClientService */
	protected $clientService;

	/** @var ICloudIdManager */
	protected $cloudIdManager;

	public function __construct(IClientService $clientService, ICloudIdManager $cloudIdManager) {
		$this->clientService = $clientService;
		$this->cloudIdManager = $cloudIdManager;
	}

	protected function run($arguments) {
		call_user_func_array([$this, 'sendActivity'], $arguments);
	}

	protected function sendActivity($target, $token, $path, $type, $time, $subject, $actor) {
		$client = $this->clientService->newClient();

		$cloudId = $this->cloudIdManager->resolveCloudId($target);

		$fields = [
			'target' => $cloudId->getUser(),
			'token' => $token,
			'path' => $path,
			'type' => $type,
			'time' => $time,
			'subject' => $subject,
			'actor' => $actor,
		];

		try {
			$response = $client->post(
				$this->getServerURL($cloudId), [
					'body' => $fields,
					'timeout' => 10,
					'connect_timeout' => 10,
				]
			);
		} catch (ClientException $e) {
			\OC::$server->getLogger()->warning($e->getResponse()->getBody());
			\OC::$server->getLogger()->logException($e);
		}
	}

	protected function getServerURL(ICloudId $cloudId) {
		$remote = $cloudId->getRemote();
		if (strpos($remote, 'http') !== 0) {
			$remote = 'https://' . $remote;
		}

		return rtrim($remote, '/') . '/ocs/v2.php/apps/activity/api/v2/remote';
	}
}
