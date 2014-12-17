<?php

/**
 * ownCloud
 *
 * @author Joas Schilling
 * @copyright 2014 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Tests\Controller;

use OCA\Activity\Controller\Settings;
use OCA\Activity\Tests\TestCase;

class SettingsTest extends TestCase {
	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $config;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $data;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $userSettings;

	/** @var \OCP\IL10N */
	protected $l10n;

	/** @var Settings */
	protected $controller;

	protected function setUp() {
		parent::setUp();

		$this->data = $this->getMockBuilder('OCA\Activity\Data')
			->disableOriginalConstructor()
			->getMock();

		$this->userSettings = $this->getMockBuilder('OCA\Activity\UserSettings')
			->disableOriginalConstructor()
			->getMock();

		$this->config = $this->getMock('OCP\IConfig');
		$this->l10n = \OCP\Util::getL10N('activity', 'en');

		$this->controller = new Settings(
			'activity',
			$this->getMock('OCP\IRequest'),
			$this->config,
			$this->getMock('OCP\Security\ISecureRandom'),
			$this->getMock('OCP\IURLGenerator'),
			$this->data,
			$this->userSettings,
			$this->l10n,
			'test'
		);
	}

	public function testTypeTable() {
		$this->data->expects($this->any())
			->method('getNotificationTypes')
			->willReturn(['NotificationTestTypeShared' => 'Share description']);

		$renderedResponse = $this->controller->displayPanel()->render();
		$this->assertContains('<form id="activity_notifications" class="section">', $renderedResponse);

		// Checkboxes for the type
		$this->assertContains('<label for="NotificationTestTypeShared_email">', $renderedResponse);
		$this->assertContains('<label for="NotificationTestTypeShared_stream">', $renderedResponse);

		// Description of the type
		$cleanedResponse = str_replace(["\n", "\t"], '', $renderedResponse);
		$this->assertContains('<td class="activity_select_group" data-select-group="NotificationTestTypeShared">Share description</td>', $cleanedResponse);
	}

	public function emailWarningData() {
		return [
			['', true],
			['test@localhost', false],
		];
	}

	/**
	 * @dataProvider emailWarningData
	 *
	 * @param string $email
	 * @param bool $containsWarning
	 */
	public function testEmailWarning($email, $containsWarning) {
		$this->data->expects($this->any())
			->method('getNotificationTypes')
			->willReturn([]);
		$this->config->expects($this->any())
			->method('getUserValue')
			->willReturn($email);

		$renderedResponse = $this->controller->displayPanel()->render();
		$this->assertContains('<form id="activity_notifications" class="section">', $renderedResponse);

		if ($containsWarning) {
			$this->assertContains('You need to set up your email address before you can receive notification emails.', $renderedResponse);
		} else {
			$this->assertNotContains('You need to set up your email address before you can receive notification emails.', $renderedResponse);
		}
	}

	public function emailSendBatchSettingData() {
		return [
			[0, 0, 'Hourly'],
			['foobar', 0, 'Hourly'],
			[3600, 0, 'Hourly'],
			[3600 * 24, 1, 'Daily'],
			[3600 * 24 * 7, 2, 'Weekly'],
		];
	}

	/**
	 * @dataProvider emailSendBatchSettingData
	 *
	 * @param string $setting
	 * @param int $selectedValue
	 * @param string $selectedLabel
	 */
	public function testEmailSendBatchSetting($setting, $selectedValue, $selectedLabel) {
		$this->data->expects($this->any())
			->method('getNotificationTypes')
			->willReturn([]);
		$this->userSettings->expects($this->any())
			->method('getUserSetting')
			->willReturn($setting);

		$renderedResponse = $this->controller->displayPanel()->render();
		$this->assertContains('<form id="activity_notifications" class="section">', $renderedResponse);

		$this->assertContains('<option value="' . $selectedValue . '" selected="selected">' . $selectedLabel . '</option>', $renderedResponse);
	}
}
