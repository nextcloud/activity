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
use OCP\Federation\ICloudIdManager;
use OCP\Http\Client\IClientService;
use Psr\Log\LoggerInterface;

class RemoteActivity extends QueuedJob {
	public function __construct(
		ITimeFactory $timeFactory,
		private readonly IClientService $clientService,
		private readonly ICloudIdManager $cloudIdManager,
		private readonly LoggerInterface $logger,
	) {
		parent::__construct($timeFactory);
	}

	#[\Override]
	protected function run($argument) {
		if (is_array($argument) && array_key_exists('instances', $argument)) {
			foreach ($argument['instances'] as $instance => $entries) {
				$this->sendActivities($instance, $entries);
			}
		} else {
			call_user_func_array($this->sendActivity(...), $argument);
		}
	}

	protected function sendActivity($target, $token, $path, $internalType, $time, $actor, $secondPath = '') {
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

		$this->sendRemote($cloudId->getRemote(), $token, $fields);
	}

	protected function sendActivities(string $instance, array $entries) {
		if (count($entries) === 1) {
			$this->sendActivity(...$entries[0]);
			return;
		}

		$items = [];
		foreach ($entries as $entry) {
			[$target, $token, $path, $internalType, $time, $actor, $secondPath] = array_merge(array_fill(0, 6, ''), $entry);
			$cloudId = $this->cloudIdManager->resolveCloudId($target);

			$type = $this->translateType($internalType, $secondPath);

			$item = [
				'to' => [
					'type' => 'Person',
					'name' => $cloudId->getUser(),
				],
				'actor' => [
					'type' => 'Person',
					'name' => $actor,
				],
				'type' => $type,
				'share' => [
					'type' => 'Document',
					'token' => $token,
				],
				'updated' => date(\DateTime::W3C, (int)$time),
			];

			if ($type === 'Move') {
				$item['target'] = [
					'type' => 'Document',
					'name' => $path,
				];
				$item['origin'] = [
					'type' => 'Document',
					'name' => $secondPath,
				];
			} else {
				$item['object'] = [
					'type' => 'Document',
					'name' => $path,
				];
			}

			$items[] = $item;
		}

		$this->sendRemoteCollection($instance, $items);
	}

	public function sendRemoteCollection(string $instance, array $items): void {
		if (empty($items)) {
			return;
		}

		$collection = [
			'@context' => 'https://www.w3.org/ns/activitystreams',
			'type' => 'Collection',
			'totalItems' => count($items),
			'items' => $items
		];

		$this->sendRemote($instance, null, $collection);
	}

	public function sendRemote(string $instance, ?string $token, array $data): void {
		$client = $this->clientService->newClient();

		try {
			$client->post(
				$this->getServerURL($instance, $token), [
					'body' => $data,
					'timeout' => 10,
					'connect_timeout' => 10,
				]
			);
		} catch (ClientException $e) {
			$this->logger->warning('Failed to send remote activity', ['exception' => $e]);
		}
	}

	/**
	 * returns the url to reach remote activity apps ocs endpoint
	 *
	 * will use the receiveActivities() endpoint is token is NULL
	 */
	protected function getServerURL(string $remote, ?string $token): string {
		if (!str_starts_with($remote, 'http')) {
			$remote = 'https://' . $remote;
		}

		if ($token === null) {
			return rtrim($remote, '/') . '/ocs/v2.php/apps/activity/api/v2/remote';
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
