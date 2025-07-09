<?php

/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\BackgroundJob;

use GuzzleHttp\Exception\ClientException;
use OCA\Activity\Extension\Files;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\QueuedJob;
use OCP\Federation\ICloudId;
use OCP\Federation\ICloudIdManager;
use OCP\Http\Client\IClientService;

class RemoteActivity extends QueuedJob {
	public function __construct(
		ITimeFactory $timeFactory,
		private readonly IClientService $clientService,
		private readonly ICloudIdManager $cloudIdManager,
	) {
		parent::__construct($timeFactory);
	}

	#[\Override]
	protected function run($argument) {
		call_user_func_array([$this, 'sendActivity'], $argument);
	}

	protected function sendActivity($target, $token, $path, $internalType, $time, $actor, $secondPath = '') {
		$client = $this->clientService->newClient();

		$cloudId = $this->cloudIdManager->resolveCloudId($target);
		$type = $this->translateType($internalType, $secondPath);

		$fields = [
			'@context' => 'https://www.w3.org/ns/activitystreams',
			'to' => [
				'type' => 'Person',
				'name' => $cloudId->getUser(),
			],
			'actor' => [
				'type' => 'Person',
				'name' => $actor,
			],
			'type' => $type,
			'updated' => date(\DateTime::W3C, $time),
		];

		if ($type === 'Move') {
			$fields['target'] = [
				'type' => 'Document',
				'name' => $path,
			];
			$fields['origin'] = [
				'type' => 'Document',
				'name' => $secondPath,
			];
		} else {
			$fields['object'] = [
				'type' => 'Document',
				'name' => $path,
			];
		}

		try {
			$client->post(
				$this->getServerURL($cloudId, $token), [
					'body' => $fields,
					'timeout' => 10,
					'connect_timeout' => 10,
				]
			);
		} catch (ClientException $e) {
		}
	}

	/**
	 * @param ICloudId $cloudId
	 * @param string $token
	 * @return string
	 */
	protected function getServerURL(ICloudId $cloudId, $token) {
		$remote = $cloudId->getRemote();
		if (strpos($remote, 'http') !== 0) {
			$remote = 'https://' . $remote;
		}

		return rtrim($remote, '/') . '/ocs/v2.php/apps/activity/api/v2/remote/' . $token;
	}

	/**
	 * @param string $internalType
	 * @param string $secondPath
	 * @return string
	 */
	protected function translateType($internalType, $secondPath) {
		switch ($internalType) {
			case Files::TYPE_SHARE_CREATED:
			case Files::TYPE_SHARE_RESTORED:
				return 'Create';
			case Files::TYPE_FILE_CHANGED:
				if ($secondPath !== '') {
					return 'Move';
				}
				return 'Update';
			case Files::TYPE_SHARE_DELETED:
				return 'Delete';
		}
		return '';
	}
}
