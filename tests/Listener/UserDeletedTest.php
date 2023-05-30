<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2021, Thomas Citharel <nextcloud@tcit.fr>
 *
 * @author Thomas Citharel <nextcloud@tcit.fr>
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

namespace OCA\Activity\Tests\Listener;

use OCA\Activity\Data;
use OCA\Activity\Listener\UserDeleted;
use OCA\Activity\MailQueueHandler;
use OCP\IUser;
use OCP\User\Events\UserDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserDeletedTest extends TestCase {
	/**
	 * @var Data|MockObject
	 */
	private $data;

	/**
	 * @var MailQueueHandler|MockObject
	 */
	private $mailQueueHandler;

	/**
	 * @var UserDeletedEvent
	 */
	private $event;

	/**
	 * @var UserDeleted
	 */
	private $listener;

	/** @var IUser|MockObject */
	private $user;

	public const UID = 'myuser';

	public function setUp(): void {
		parent::setUp();
		$this->data = $this->createMock(Data::class);
		$this->mailQueueHandler = $this->createMock(MailQueueHandler::class);
		$this->user = $this->createMock(IUser::class);
		$this->user->expects($this->exactly(2))->method('getUID')->with()->willReturn(self::UID);
		$this->event = new UserDeletedEvent($this->user);

		$this->listener = new UserDeleted($this->data, $this->mailQueueHandler);
	}

	public function testUserDeleted(): void {
		$this->data->expects($this->once())->method('deleteActivities')->with(['affecteduser' => self::UID]);
		$this->mailQueueHandler->expects($this->once())->method('purgeItemsForUser')->with(self::UID);
		$this->listener->handle($this->event);
	}
}
