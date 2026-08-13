<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2016 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Filter;

use OCP\Activity\IFilter;
use OCP\IL10N;
use OCP\IURLGenerator;

class FocusedFilter implements IFilter {
	public function __construct(
		protected IL10N $l,
		protected IURLGenerator $url
	) {
	}

	#[\Override]
	public function getIdentifier(): string {
		return 'focused';
	}

	#[\Override]
	public function getName(): string {
		return $this->l->t('Focused');
	}

	#[\Override]
	public function getPriority(): int {
		return 1;
	}

	#[\Override]
	public function getIcon(): string {
		return $this->url->getAbsoluteURL($this->url->imagePath('activity', 'activity-dark.svg'));
	}

	/**
	 * @param string[] $types
	 * @return string[] An array of allowed apps from which activities should be displayed
	 */
	#[\Override]
	public function filterTypes(array $types): array {
		return $types;
	}

	/**
	 * @return string[] An array of allowed apps from which activities should be displayed
	 */
	#[\Override]
	public function allowedApps(): array {
		return [];
	}
}
