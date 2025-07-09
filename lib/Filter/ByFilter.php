<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Filter;

use OCP\Activity\IFilter;
use OCP\IL10N;
use OCP\IURLGenerator;

class ByFilter implements IFilter {
	/** @var IL10N */
	protected $l;

	/** @var IURLGenerator */
	protected $url;

	/**
	 * @param IL10N $l
	 * @param IURLGenerator $url
	 */
	public function __construct(IL10N $l, IURLGenerator $url) {
		$this->l = $l;
		$this->url = $url;
	}

	/**
	 * @return string Lowercase a-z only identifier
	 * @since 9.2.0
	 */
	#[\Override]
	public function getIdentifier() {
		return 'by';
	}

	/**
	 * @return string A translated string
	 * @since 9.2.0
	 */
	#[\Override]
	public function getName() {
		return $this->l->t('By others');
	}

	/**
	 * @return int
	 * @since 9.2.0
	 */
	#[\Override]
	public function getPriority() {
		return 2;
	}

	/**
	 * @return string Full URL to an icon, empty string when none is given
	 * @since 9.2.0
	 */
	#[\Override]
	public function getIcon() {
		return $this->url->getAbsoluteURL($this->url->imagePath('core', 'places/contacts.svg'));
	}

	/**
	 * @param string[] $types
	 * @return string[] An array of allowed apps from which activities should be displayed
	 * @since 9.2.0
	 */
	#[\Override]
	public function filterTypes(array $types) {
		return $types;
	}

	/**
	 * @return string[] An array of allowed apps from which activities should be displayed
	 * @since 9.2.0
	 */
	#[\Override]
	public function allowedApps() {
		return [];
	}
}
