<?php
/**
 * @copyright Copyright (c) 2018 Tinko Bartels <mail@tinkobartels.de>
 *
 * @author Tinko Bartels <mail@tinkobartels.de>
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

use OCP\IConfig;
use OCP\ILogger;

class WebhookHelper {

	/** @var IConfig */
	protected $config;

	/** @var ILogger */
	protected $logger;

	/**
	 * @param IConfig $config
	 * @param ILogger $logger
	 */
	public function __construct(IConfig $config, ILogger $logger) {
		$this->config = $config;
		$this->logger = $logger;
	}

	/**
	 * @param array $content
	 */
	public function sendWebhookRequest($content) {
		$url = $this->config->getAppValue('activity', 'webhook_url', '');
		$token = $this->config->getAppValue('activity', 'webhook_token', '');
		$ssl_verification = $this->config->getAppValue('activity', 'webhook_ssl_verification_enabled', 'yes') === 'yes';
		$content_string = json_encode($content);                                                                                   
		$headers = array( 'X-Nextcloud-Token: '.$token, 'Content-Type: application/json', 'Content-Length: '.strlen($content_string) );
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content_string);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $ssl_verification?2:0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verification);
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		if(curl_error($ch)) {
    			$error_msg = curl_error($ch);
			$this->logger->error("Webhook-Error: ".$error_msg);
		}
		if($httpCode>=400) {
			$this->logger->error("Webhook-Error: HTTP code ".$httpCode);
		}
		curl_close($ch);
	}

}
