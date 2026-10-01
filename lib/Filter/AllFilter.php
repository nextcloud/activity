<?php

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Filter;

use OCP\Activity\IFilter;
use OCP\IL10N;
use OCP\IURLGenerator;

class AllFilter implements IFilter {
	/** @var IL10N */
	protected $l;

	/** @var IURLGenerator */
	protected $url;

	public function __construct(IL10N $l, IURLGenerator $url) {
		$this->l = $l;
		$this->url = $url;
	}

	/**
	 * @return string Lowercase a-z only identifier
	 * @since 9.2.0
	 */
	#[\Override]
	public function getIdentifier(): string {
		return 'all';
	}

	/**
	 * @return string A translated string
	 * @since 9.2.0
	 */
	#[\Override]
	public function getName() {
		return $this->l->t('All activities');
	}

	/**
	 * @since 9.2.0
	 */
	#[\Override]
	public function getPriority(): int {
		return 0;
	}

	/**
	 * @return string Full URL to an icon, empty string when none is given
	 * @since 9.2.0
	 */
	#[\Override]
	public function getIcon() {
		return $this->url->getAbsoluteURL($this->url->imagePath('activity', 'activity-dark.svg'));
	}

	/**
	 * @param string[] $types
	 * @return string[] An array of allowed apps from which activities should be displayed
	 * @since 9.2.0
	 */
	#[\Override]
	public function filterTypes(array $types): array {
		return $types;
	}

	/**
	 * @return string[] An array of allowed apps from which activities should be displayed
	 * @since 9.2.0
	 */
	#[\Override]
	public function allowedApps(): array {
		return [];
	}
}
