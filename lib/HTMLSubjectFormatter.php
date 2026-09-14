<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\Activity;

use OCP\Activity\IEvent;

class HTMLSubjectFormatter {
	public static function format(IEvent $event): string {
		if ($event->getRichSubject() === '') {
			return htmlspecialchars($event->getParsedSubject());
		}

		$placeholders = $replacements = [];
		foreach ($event->getRichSubjectParameters() as $placeholder => $parameter) {
			$placeholders[] = '{' . $placeholder . '}';

			$replacement = $parameter['type'] === 'file'
				? (string)$parameter['path']
				: (string)$parameter['name'];

			if (isset($parameter['link'])) {
				$replacements[] = '<a href="' . $parameter['link'] . '">' . htmlspecialchars($replacement) . '</a>';
			} else {
				$replacements[] = '<strong>' . htmlspecialchars($replacement) . '</strong>';
			}
		}

		return str_replace($placeholders, $replacements, $event->getRichSubject());
	}
}
