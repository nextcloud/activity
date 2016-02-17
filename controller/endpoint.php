<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
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

namespace OCA\Activity\Controller;


use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

class EndPoint extends Controller {

	/** @var OCSEndPoint */
	protected $ocsEndPoint;

	/**
	 * OCSEndPoint constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param OCSEndPoint $ocsEndPoint
	 */
	public function __construct($appName,
								IRequest $request,
								OCSEndPoint $ocsEndPoint) {
		parent::__construct($appName, $request);
		$this->ocsEndPoint = $ocsEndPoint;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return JSONResponse
	 */
	public function getDefault() {
		$response = $this->ocsEndPoint->getDefault([]);
		return $this->ocsToJsonResponse($response);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $filter
	 * @return JSONResponse
	 */
	public function getFilter($filter) {
		$response = $this->ocsEndPoint->getFilter(['filter' => $filter]);
		return $this->ocsToJsonResponse($response);
	}

	/**
	 * @param \OC_OCS_Result $ocsResult
	 * @return JSONResponse
	 */
	protected function ocsToJsonResponse(\OC_OCS_Result $ocsResult) {
		$response = new JSONResponse(
			[
				'ocs' => [
					'meta' => [
						'status' => 'ok',
						'statuscode' => 100,
						'message' => null,
					],
					'data' => $ocsResult->getData(),
				],
			],
			$ocsResult->getStatusCode() === 100 ? Http::STATUS_OK : $ocsResult->getStatusCode()
		);
		$response->setHeaders(array_merge(
			$ocsResult->getHeaders(),
			[
				'Content-Type' => 'application/json; charset=utf-8'
			]
		));
		return $response;
	}
}
