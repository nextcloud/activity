<?php
/**
 * @copyright Copyright (c) 2016 Bjoern Schiessle <bjoern@schiessle.org>
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


namespace OCA\Activity\Tests\Formatter;


use OCA\Activity\Formatter\EmailFormatter;
use OCA\Activity\Tests\TestCase;
use OCP\Activity\IEvent;
use OCP\Contacts\IManager;

class EmailFormatterTest extends TestCase {

	/** @var IManager | \PHPUnit_Framework_MockObject_MockObject */
	private $contactsManager;

	/** @var IEvent | \PHPUnit_Framework_MockObject_MockObject */
	private $event;

	public function setUp() {
		parent::setUp();

		$this->contactsManager = $this->getMockBuilder('OCP\Contacts\IManager')
			->disableOriginalConstructor()->getMock();
		$this->event = $this->getMockBuilder('OCP\Activity\IEvent')
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * get instance of email formatter
	 *
	 * @param array $mockedMethods
	 * @return EmailFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function getEmailFormatter($mockedMethods = []) {
		if (empty($mockedMethods)) {
			return new EmailFormatter($this->contactsManager);
		}

		return $this->getMockBuilder('OCA\Activity\Formatter\EmailFormatter')
			->setConstructorArgs([$this->contactsManager])
			->setMethods($mockedMethods)
			->getMock();
	}

	public function testFormat() {
		$displayName = "John Doe";
		$email = 'john.doe@nextcloud.com';

		$formatter = $this->getEmailFormatter(['getDisplayName']);
		$formatter->expects($this->once())->method('getDisplayName')->with($email)
			->willReturn($displayName);

		$this->assertSame('<a href="mailto:' . $email . '">' . $displayName . '</a>',
			$formatter->format($this->event, $email)
			);
	}

	/**
	 * @dataProvider dataTestGetDisplayName
	 *
	 * @param array $results
	 * @param string $expected
	 */
	public function testGetDisplayName($results, $expected) {

		$email = 'john.doe@nextcloud.com';

		$formatter = $this->getEmailFormatter();

		$this->contactsManager->expects($this->once())->method('search')
			->with($email, ['EMAIL'])->willReturn($results);

		$this->assertSame($expected,
			$this->invokePrivate($formatter, 'getDisplayName', [$email])
		);
	}

	public function dataTestGetDisplayName() {
		return [
			[
				[
					['EMAIL' => ['user1@server1.com', 'user2@server2.com'], 'FN' => 'Full Name']
				], 'john.doe@nextcloud.com'
			],
			[
				[
					['EMAIL' => ['user1@server1.com', 'user2@server2.com'], 'FN' => 'Full Name'],
					['EMAIL' => ['john.doe@nextcloud.com', 'user2@server2.com'], 'FN' => 'Full Name']
				], 'Full Name'
			],
			[
				[
					['EMAIL' => ['user1@server1.com', 'user2@server2.com'], 'FN' => 'Full Name'],
					['EMAIL' => ['user1@server1.com', 'john.doe@nextcloud.com'], 'FN' => 'Full Name']
				], 'Full Name'
			],

		];
	}

}
