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
		$this->filesExtension = new Files(\OCP\Util::getL10N('activity', 'en'), $this->getMock('\OCP\IURLGenerator'));
	}

	public function dataGetNotificationTypes() {
		return [
			[Files::TYPE_SHARE_CREATED],
			[Files::TYPE_SHARE_CHANGED],
			[Files::TYPE_SHARE_DELETED],
			[Files::TYPE_SHARE_RESTORED],
			[Files::TYPE_SHARED],
		];
	}

	/**
	 * @dataProvider dataGetNotificationTypes
	 * @param string $typeKey
	 */
	public function testGetNotificationTypes($typeKey) {
		$this->assertArrayHasKey($typeKey, $this->filesExtension->getNotificationTypes('en'));
	}

	public function dataFilterNotificationTypes() {
		return [
			['shares', [Files::TYPE_SHARED, Files::TYPE_SHARE_CREATED], [Files::TYPE_SHARED]],
			['files', ['AnotherApp', Files::TYPE_SHARED], [Files::TYPE_SHARED]],
			['AnotherApp', [Files::TYPE_SHARED, Files::TYPE_SHARE_CREATED], false],
		];
	}

	/**
	 * @dataProvider dataFilterNotificationTypes
	 *
	 * @param string $filter
	 * @param array $types
	 * @param mixed $expected
	 */
	public function testFilterNotificationTypes($filter, $types, $expected) {
		$this->assertEquals($expected, $this->filesExtension->filterNotificationTypes($types, $filter));
	}

	public function dataGetDefaultTypes() {
		return [
			['email', [Files::TYPE_SHARED]],
			['stream', [
				Files::TYPE_SHARE_CREATED,
				Files::TYPE_SHARE_CHANGED,
				Files::TYPE_SHARE_DELETED,
				Files::TYPE_SHARE_RESTORED,
				Files::TYPE_SHARED,
			]],
			['AnotherType', false],
		];
	}

	/**
	 * @dataProvider dataGetDefaultTypes
	 *
	 * @param string $method
	 * @param mixed $expected
	 */
	public function testGetDefaultTypes($method, $expected) {
		$this->assertEquals($expected, $this->filesExtension->getDefaultTypes($method));
	}

	public function dataTranslate() {
		return [
			['AnotherApp', '', false],
			['files', 'AnotherApp', false],
			['files', 'created_self', true],
			['files', 'created_by', true],
			['files', 'created_public', true],
			['files', 'changed_self', true],
			['files', 'changed_by', true],
			['files', 'deleted_self', true],
			['files', 'deleted_by', true],
			['files', 'restored_self', true],
			['files', 'restored_by', true],
			['files', 'shared_user_self', true],
			['files', 'shared_group_self', true],
			['files', 'shared_with_by', true],
			['files', 'shared_link_self', true],
		];
	}

	/**
	 * @dataProvider dataTranslate
	 *
	 * @param string $app
	 * @param string $text
	 * @param bool $expected
	 */
	public function testTranslate($app, $text, $expected) {
		$return = $this->filesExtension->translate($app, $text, ['param1', 'param2'], false, false, 'en');
		$this->assertReturnIsNotFalse($return, $expected);
	}

	public function dataGetSpecialParameterList() {
		return [
			['AnotherApp', 'created_self', false],
			['files', 'AnotherApp', false],
			['files', 'created_self', ['file', 'username']],
			['files', 'created_by', ['file', 'username']],
			['files', 'created_public', ['file', 'username']],
			['files', 'changed_self', ['file', 'username']],
			['files', 'changed_by', ['file', 'username']],
			['files', 'deleted_self', ['file', 'username']],
			['files', 'deleted_by', ['file', 'username']],
			['files', 'restored_self', ['file', 'username']],
			['files', 'restored_by', ['file', 'username']],
			['files', 'shared_user_self', ['file', 'username']],
			['files', 'shared_group_self', ['file']],
			['files', 'shared_with_by', ['file', 'username']],
			['files', 'shared_link_self', ['file', 'username']],
		];
	}

	/**
	 * @dataProvider dataGetSpecialParameterList
	 *
	 * @param string $app
	 * @param string $subject
	 * @param mixed $expected
	 */
	public function testGetSpecialParameterList($app, $subject, $expected) {
		$this->assertEquals($expected, $this->filesExtension->getSpecialParameterList($app, $subject));
	}

	public function dataGetTypeIcon() {
		return [
			[Files::TYPE_SHARED, true],
			[Files::TYPE_SHARE_CREATED, true],
			[Files::TYPE_SHARE_CHANGED, true],
			[Files::TYPE_SHARE_DELETED, true],
			[Files::TYPE_SHARE_RESTORED, false],
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
		$this->assertReturnIsNotFalse($this->filesExtension->getTypeIcon($type), $expected);
	}


	public function dataGetGroupParameter() {
		return [
			['AnotherApp', 'created_self', false],
			['files', 'AnotherApp', false],
			['files', 'created_self', 0],
			['files', 'created_by', 0],
			['files', 'created_public', false],
			['files', 'changed_self', 0],
			['files', 'changed_by', 0],
			['files', 'deleted_self', 0],
			['files', 'deleted_by', 0],
			['files', 'restored_self', 0],
			['files', 'restored_by', 0],
			['files', 'shared_user_self', false],
			['files', 'shared_group_self', false],
			['files', 'shared_with_by', false],
			['files', 'shared_link_self', false],
		];
	}

	/**
	 * @dataProvider dataGetGroupParameter
	 *
	 * @param string $app
	 * @param string $subject
	 * @param mixed $expected
	 */
	public function testGetGroupParameter($app, $subject, $expected) {
		$this->assertSame($expected, $this->filesExtension->getGroupParameter(['app' => $app, 'subject' => $subject]));
	}

	public function testGetNavigation() {
		$navigation = $this->filesExtension->getNavigation();
		$this->assertNotEmpty($navigation);
		$this->assertArrayHasKey('top', $navigation);
		$this->assertNotEmpty($navigation['top']);
		$this->assertArrayHasKey('apps', $navigation);
		$this->assertNotEmpty($navigation['apps']);
	}

	public function dataIsFilterValid() {
		return [
			['files', true],
			['shares', true],
			['AnotherApp', false],
		];
	}

	/**
	 * @dataProvider dataIsFilterValid
	 *
	 * @param string $filter
	 * @param mixed $expected
	 */
	public function testIsFilterValid($filter, $expected) {
		$this->assertEquals($expected, $this->filesExtension->isFilterValid($filter));
	}

	public function dataGetQueryForFilter() {
		return [
			['files', true],
			['AnotherApp', false],
		];
	}

	/**
	 * @dataProvider dataGetQueryForFilter
	 * @param string $filter
	 * @param bool $expected
	 */
	public function testGetQueryForFilter($filter, $expected) {
		$this->assertReturnIsNotFalse($this->filesExtension->getQueryForFilter($filter), $expected);
	}

	/**
	 * @param mixed $return
	 * @param bool $expected
	 */
	protected function assertReturnIsNotFalse($return, $expected) {
		if ($expected) {
			$this->assertNotFalse($return);
		} else {
			$this->assertFalse($return);
		}
	}
}
