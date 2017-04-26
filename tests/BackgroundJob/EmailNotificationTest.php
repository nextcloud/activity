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

namespace OCA\Activity\Tests\BackgroundJob;

use OCA\Activity\BackgroundJob\EmailNotification;
use OCA\Activity\MailQueueHandler;
use OCA\Activity\Tests\TestCase;
use OCP\ILogger;
use OCP\IConfig;
use OCP\BackgroundJob\IJobList;

/**
 * Class EmailNotificationTest
 *
 * @group DB
 * @package OCA\Activity\Tests\BackgroundJob
 */
class EmailNotificationTest extends TestCase {
	public function constructAndRunData() {
		return [
			[true],
			[false],
		];
	}

	/**
	 * @dataProvider constructAndRunData
	 *
	 * @param bool $isCLI
	 */
	public function testConstructAndRun($isCLI) {
		$backgroundJob = new EmailNotification(
			$this->createMock(MailQueueHandler::class),
			$this->createMock(IConfig::class),
			$this->createMock(ILogger::class),
			$isCLI
		);

		$jobList = $this->createMock(IJobList::class);

		/** @var \OC\BackgroundJob\JobList $jobList */
		$backgroundJob->execute($jobList);
		$this->assertTrue(true);
	}

	public function testRunStep() {
		$mailQueueHandler = $this->createMock(MailQueueHandler::class);
		$config = $this->createMock(IConfig::class);
		$backgroundJob = new EmailNotification(
			$mailQueueHandler,
			$config,
			$this->createMock(ILogger::class),
			true
		);

		$mailQueueHandler->expects($this->any())
			->method('getAffectedUsers')
			->with(2, 200)
			->willReturn([
				'test1',
				'test2',
			]);
		$mailQueueHandler->expects($this->once())
			->method('sendEmailToUser')
			->with('test1', 'test1@localhost', 'de', date_default_timezone_get(), $this->anything());
		$config->expects($this->any())
			->method('getUserValueForUsers')
			->willReturnMap([
				['settings', 'email', [
					'test1',
					'test2',
				], [
					'test1' => 'test1@localhost',
					'test2' => '',
				]],
				['core', 'lang', [
					'test1',
					'test2',
				], [
					'test1' => 'de',
					'test2' => 'en',
				]]
			]);

		$this->assertEquals(2, self::invokePrivate($backgroundJob, 'runStep', [2, 200]));
	}
}
