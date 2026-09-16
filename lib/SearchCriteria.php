<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity;

use OCA\Activity\Exception\InvalidSearchCriteriaException;

/**
 * Immutable, validated set of additional restrictions for an activity query.
 *
 * Instances are only ever created through {@see self::create()} or
 * {@see self::empty()}, so consumers can rely on the values having been
 * normalised and range checked before they reach the query builder.
 *
 * @psalm-immutable
 */
final class SearchCriteria {
	/**
	 * Single characters match such a large share of a stream that the query
	 * degenerates into a scan of the whole user partition while still returning
	 * a useless result, so they are rejected rather than silently accepted.
	 */
	public const MIN_TERM_LENGTH = 2;

	/**
	 * Paths are stored in a varchar(4000) column, but no real search term comes
	 * close to that. The lower bound keeps a hostile request from building a
	 * multi-kilobyte LIKE pattern.
	 */
	public const MAX_TERM_LENGTH = 255;

	/**
	 * `activity`.`timestamp` is a 4 byte signed integer. Values beyond this are
	 * out of range for the column and make strict database platforms error out
	 * instead of simply matching nothing.
	 */
	public const MAX_TIMESTAMP = 2147483647;

	/**
	 * Longest account name we accept. Matches the `user` column, which is a
	 * varchar(64).
	 */
	public const MAX_ACTOR_LENGTH = 64;

	private function __construct(
		public readonly ?string $term,
		public readonly ?int $from,
		public readonly ?int $to,
		public readonly ?string $actor,
	) {
	}

	/**
	 * Criteria that restrict nothing.
	 *
	 * @psalm-pure
	 */
	public static function empty(): self {
		return new self(null, null, null, null);
	}

	/**
	 * Build criteria from raw request input.
	 *
	 * Absent values are expressed as the API defaults (an empty term, a zero
	 * timestamp) rather than null, so this can be fed directly from the
	 * controller arguments.
	 *
	 * @param string $term Substring to look for in the activity's file path
	 * @param int $from Only match activities at or after this Unix timestamp
	 * @param int $to Only match activities at or before this Unix timestamp
	 * @param string $actor Only match activities authored by this account
	 *
	 * @throws InvalidSearchCriteriaException when the input cannot be used
	 *
	 * @psalm-pure
	 */
	public static function create(string $term = '', int $from = 0, int $to = 0, string $actor = ''): self {
		$term = trim($term);
		if ($term === '') {
			$term = null;
		} else {
			$length = mb_strlen($term);
			if ($length < self::MIN_TERM_LENGTH) {
				throw new InvalidSearchCriteriaException(
					'Search term must be at least ' . self::MIN_TERM_LENGTH . ' characters long',
				);
			}
			if ($length > self::MAX_TERM_LENGTH) {
				throw new InvalidSearchCriteriaException(
					'Search term must be at most ' . self::MAX_TERM_LENGTH . ' characters long',
				);
			}
		}

		// Timestamps at or below zero mean "unbounded"; anything above the
		// column's range is clamped so an out-of-range request still behaves
		// like the widest possible bound instead of failing.
		$from = $from > 0 ? min($from, self::MAX_TIMESTAMP) : null;
		$to = $to > 0 ? min($to, self::MAX_TIMESTAMP) : null;

		if ($from !== null && $to !== null && $from > $to) {
			throw new InvalidSearchCriteriaException('Start of the date range must not be after its end');
		}

		$actor = trim($actor);
		if ($actor === '') {
			$actor = null;
		} elseif (mb_strlen($actor) > self::MAX_ACTOR_LENGTH) {
			throw new InvalidSearchCriteriaException(
				'Account name must be at most ' . self::MAX_ACTOR_LENGTH . ' characters long',
			);
		}

		return new self($term, $from, $to, $actor);
	}

	public function hasTerm(): bool {
		return $this->term !== null;
	}

	public function hasDateRange(): bool {
		return $this->from !== null || $this->to !== null;
	}

	public function hasActor(): bool {
		return $this->actor !== null;
	}

	public function isEmpty(): bool {
		return !$this->hasTerm() && !$this->hasDateRange() && !$this->hasActor();
	}

	/**
	 * The criteria as request parameters, omitting everything unset.
	 *
	 * Used to carry the active criteria over into the pagination `Link` header.
	 *
	 * @return array<string, string|int>
	 */
	public function toParameters(): array {
		$parameters = [];
		if ($this->term !== null) {
			$parameters['search'] = $this->term;
		}
		if ($this->from !== null) {
			$parameters['from'] = $this->from;
		}
		if ($this->to !== null) {
			$parameters['to'] = $this->to;
		}
		if ($this->actor !== null) {
			$parameters['actor'] = $this->actor;
		}
		return $parameters;
	}
}
