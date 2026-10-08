<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

class OC_Defaults {
	public function __construct() {
	}

	/**
	 * Returns the base URL
	 * @return string URL
	 */
	public function getBaseUrl() {
	}

	/**
	 * Returns the URL where the sync clients are listed
	 * @return string URL
	 */
	public function getSyncClientUrl() {
	}

	/**
	 * Returns the URL to the App Store for the iOS Client
	 * @return string URL
	 */
	public function getiOSClientUrl() {
	}

	/**
	 * Returns the AppId for the App Store for the iOS Client
	 * @return string AppId
	 */
	public function getiTunesAppId() {
	}

	/**
	 * Returns the URL to Google Play for the Android Client
	 * @return string URL
	 */
	public function getAndroidClientUrl() {
	}

	/**
	 * Returns the URL to Google Play for the Android Client
	 * @return string URL
	 */
	public function getFDroidClientUrl() {
	}

	/**
	 * Returns the documentation URL
	 * @return string URL
	 */
	public function getDocBaseUrl() {
	}

	/**
	 * Returns the title
	 * @return string title
	 */
	public function getTitle() {
	}

	/**
	 * Returns the short name of the software
	 * @return string title
	 */
	public function getName() {
	}

	/**
	 * Returns the short name of the software containing HTML strings
	 * @return string title
	 */
	public function getHTMLName() {
	}

	/**
	 * Returns entity (e.g. company name) - used for footer, copyright
	 * @return string entity name
	 */
	public function getEntity() {
	}

	/**
	 * Returns slogan
	 * @return string slogan
	 */
	public function getSlogan(?string $lang = null) {
	}

	/**
	 * Returns short version of the footer
	 * @return string short footer
	 */
	public function getShortFooter() {
	}

	/**
	 * Returns long version of the footer
	 * @return string long footer
	 */
	public function getLongFooter() {
	}

	/**
	 * @param string $key
	 * @return string URL to doc with key
	 */
	public function buildDocLinkToKey($key) {
	}

	/**
	 * Returns primary color
	 * @return string
	 */
	public function getColorPrimary() {
	}

	/**
	 * Returns primary color
	 * @return string
	 */
	public function getColorBackground() {
	}

	/**
	 * @return array scss variables to overwrite
	 */
	public function getScssVariables() {
	}

	public function shouldReplaceIcons() {
	}

	/**
	 * Themed logo url
	 *
	 * @param bool $useSvg Whether to point to the SVG image or a fallback
	 * @return string
	 */
	public function getLogo($useSvg = true) {
	}

	/**
	 * Raw logo image data (raster, not SVG) for embedding directly into emails,
	 * so mail clients don't have to fetch it from the internet.
	 *
	 * @return array{content: string, mimeType: string}|null null when unavailable
	 */
	public function getLogoImage(): ?array {
	}

	public function getTextColorPrimary() {
	}

	public function getProductName() {
	}
}
