<?php
/**
 * ownCloud
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Tests;

use OCA\Activity\Data;
use OCA\Activity\Extension\Files;

class ExtensionFilesTest extends TestCase {
	/** @var \OCA\Activity\Extension\Files */
	protected $filesExtension;

	protected function setUp() {
		parent::setUp();
		$this->filesExtension = new Files();
	}

	public function testGetNotificationTypes() {
		$this->assertFalse($this->filesExtension->getNotificationTypes('en'));
	}

	public function testFilterNotificationTypes() {
		$this->assertFalse($this->filesExtension->filterNotificationTypes('', ''));
	}

	public function testGetDefaultTypes() {
		$this->assertFalse($this->filesExtension->getDefaultTypes(''));
	}

	public function testTranslate() {
		$this->assertFalse($this->filesExtension->translate('', '', [], false, false, 'en'));
	}

	public function testGetSpecialParameterList() {
		$this->assertFalse($this->filesExtension->getSpecialParameterList('', ''));
	}

	public function dataGetTypeIcon() {
		return [
			[Data::TYPE_SHARED, true],
			[Data::TYPE_SHARE_CREATED, true],
			[Data::TYPE_SHARE_CHANGED, true],
			[Data::TYPE_SHARE_DELETED, true],
			[Data::TYPE_SHARE_RESTORED, false],
			['AnotherApp', false],
		];
	}

	/**
	 * @dataProvider dataGetTypeIcon
	 *
	 * @param string $type
	 * @param bool $expected
	 */
	public function testGetTypeIcon($type, $expected) {
		if ($expected) {
			$this->assertNotFalse($this->filesExtension->getTypeIcon($type));
		} else {
			$this->assertFalse($this->filesExtension->getTypeIcon($type));
		}
	}

	public function testGetGroupParameter() {
		$this->assertFalse($this->filesExtension->getGroupParameter(''));
	}

	public function testGetNavigation() {
		$this->assertFalse($this->filesExtension->getNavigation(''));
	}

	public function testIsFilterValid() {
		$this->assertFalse($this->filesExtension->isFilterValid(''));
	}

	public function testGetQueryForFilter() {
		$this->assertFalse($this->filesExtension->getQueryForFilter(''));
	}
}
