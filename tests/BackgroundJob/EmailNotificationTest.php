<?php

declare(strict_types=1);
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

namespace OCA\Activity\Tests\BackgroundJob;

use OC\BackgroundJob\JobList;
use OCA\Activity\BackgroundJob\EmailNotification;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\Tests\TestCase;
use OCP\BackgroundJob\IJobList;
use OCP\ILogger;

/**
 * Class EmailNotificationTest
 *
 * @group DB
 * @package OCA\Activity\Tests\BackgroundJob
 */
class EmailNotificationTest extends TestCase {
	public function constructAndRunData(): array {
		return [
			[true],
			[false],
		];
	}

	/**
	 * @dataProvider constructAndRunData
	 * @param bool $isCLI
	 */
	public function testConstructAndRun(bool $isCLI): void {
		$backgroundJob = new EmailNotification(
			$this->createMock(MailQueueHandler::class),
			$isCLI
		);

		$jobList = $this->createMock(IJobList::class);
		$logger = $this->createMock(ILogger::class);

		/** @var JobList $jobList */
		$backgroundJob->execute($jobList, $logger);
		$this->assertTrue(true);
	}
}
