<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Theming;

use OCA\Theming\AppInfo\Application;
use OCA\Theming\Service\BackgroundService;
use OCP\App\AppPathNotFoundException;
use OCP\App\IAppManager;
use OCP\AppFramework\Services\IAppConfig;
use OCP\Config\IUserConfig;
use OCP\Files\NotFoundException;
use OCP\Files\SimpleFS\ISimpleFile;
use OCP\ICacheFactory;
use OCP\IL10N;
use OCP\INavigationManager;
use OCP\IURLGenerator;
use OCP\IUserSession;

class ThemingDefaults extends \OC_Defaults {

	/**
	 * ThemingDefaults constructor.
	 */
	public function __construct(private readonly IAppConfig $appConfig, private readonly IUserConfig $userConfig, private readonly IL10N $l, private readonly IUserSession $userSession, private readonly IURLGenerator $urlGenerator, private readonly ICacheFactory $cacheFactory, private readonly Util $util, private readonly ImageManager $imageManager, private readonly IAppManager $appManager, private readonly INavigationManager $navigationManager, private readonly BackgroundService $backgroundService)
 {
 }

	#[\Override]
 public function getName()
 {
 }

	#[\Override]
 public function getHTMLName()
 {
 }

	#[\Override]
 public function getTitle()
 {
 }

	#[\Override]
 public function getEntity()
 {
 }

	#[\Override]
 public function getProductName(): string
 {
 }

	#[\Override]
 public function getBaseUrl()
 {
 }

	/**
	 * We pass a string and sanitizeHTML will return a string too in that case
	 * @psalm-suppress InvalidReturnStatement
	 * @psalm-suppress InvalidReturnType
	 */
	#[\Override]
 public function getSlogan(?string $lang = null): string
 {
 }

	public function getImprintUrl(): string
 {
 }

	public function getPrivacyUrl(): string
 {
 }

	#[\Override]
 public function getDocBaseUrl(): string
 {
 }

	#[\Override]
 public function getShortFooter()
 {
 }

	/**
	 * Color that is used for highlighting elements like important buttons
	 * If user theming is enabled then the user defined value is returned
	 */
	#[\Override]
 public function getColorPrimary(): string
 {
 }

	/**
	 * Color that is used for the page background (e.g. the header)
	 * If user theming is enabled then the user defined value is returned
	 */
	#[\Override]
 public function getColorBackground(): string
 {
 }

	/**
	 * Return the default primary color - only taking admin setting into account
	 */
	public function getDefaultColorPrimary(): string
 {
 }

	/**
	 * Default background color only taking admin setting into account
	 */
	public function getDefaultColorBackground(): string
 {
 }

	/**
	 * Themed logo url
	 *
	 * @param bool $useSvg Whether to point to the SVG image or a fallback
	 * @return string
	 */
	#[\Override]
 public function getLogo($useSvg = true): string
 {
 }

	#[\Override]
 public function getLogoImage(): ?array
 {
 }

	/**
	 * Themed background image url
	 *
	 * @param bool $darkVariant if the dark variant (if available) of the background should be used
	 * @return string
	 */
	public function getBackground(bool $darkVariant = false): string
 {
 }

	/**
	 * @return string
	 */
	#[\Override]
 public function getiTunesAppId()
 {
 }

	/**
	 * @return string
	 */
	#[\Override]
 public function getiOSClientUrl()
 {
 }

	/**
	 * @return string
	 */
	#[\Override]
 public function getAndroidClientUrl()
 {
 }

	/**
	 * @return string
	 */
	#[\Override]
 public function getFDroidClientUrl()
 {
 }

	/**
	 * @return array scss variables to overwrite
	 * @deprecated since Nextcloud 22 - https://github.com/nextcloud/server/issues/9940
	 */
	#[\Override]
 public function getScssVariables()
 {
 }

	/**
	 * Check if the image should be replaced by the theming app
	 * and return the new image location then
	 *
	 * @param string $app name of the app
	 * @param string $image filename of the image
	 * @return bool|string false if image should not replaced, otherwise the location of the image
	 */
	public function replaceImagePath($app, $image)
 {
 }

	protected function getCustomFavicon(): ?ISimpleFile
 {
 }

	/**
	 * Increases the cache buster key
	 */
	public function increaseCacheBuster(): void
 {
 }

	/**
	 * Update setting in the database
	 *
	 * @param string $setting
	 * @param string $value
	 */
	public function set($setting, $value): void
 {
 }

	/**
	 * Revert all settings to the default value
	 */
	public function undoAll(): void
 {
 }

	/**
	 * Revert admin settings to the default value
	 *
	 * @param string $setting setting which should be reverted
	 * @return string default value
	 */
	public function undo($setting): string
 {
 }

	/**
	 * Color of text in the header menu
	 *
	 * @return string
	 */
	public function getTextColorBackground()
 {
 }

	/**
	 * Color of text on primary buttons and other elements
	 *
	 * @return string
	 */
	#[\Override]
 public function getTextColorPrimary()
 {
 }

	/**
	 * Color of text in the header and primary buttons
	 *
	 * @return string
	 */
	public function getDefaultTextColorPrimary()
 {
 }

	/**
	 * Has the admin disabled user customization
	 */
	public function isUserThemingDisabled(): bool
 {
 }
}
