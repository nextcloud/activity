<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Activity\Tests;

use OCA\Activity\Exception\InvalidSearchCriteriaException;
use OCA\Activity\SearchCriteria;
use PHPUnit\Framework\Attributes\DataProvider;

class SearchCriteriaTest extends TestCase {
	public function testEmpty(): void {
		$criteria = SearchCriteria::empty();

		$this->assertNull($criteria->term);
		$this->assertNull($criteria->from);
		$this->assertNull($criteria->to);
		$this->assertNull($criteria->actor);
		$this->assertTrue($criteria->isEmpty());
		$this->assertFalse($criteria->hasTerm());
		$this->assertFalse($criteria->hasDateRange());
		$this->assertFalse($criteria->hasActor());
		$this->assertSame([], $criteria->toParameters());
	}

	public function testDefaultsAreEmpty(): void {
		$this->assertTrue(SearchCriteria::create()->isEmpty());
	}

	public static function dataTerm(): array {
		return [
			'absent' => ['', null],
			'blank' => ['   ', null],
			'trimmed' => ['  report  ', 'report'],
			'minimum length' => ['ab', 'ab'],
			'multibyte at minimum length' => ['äö', 'äö'],
			'maximum length' => [str_repeat('x', SearchCriteria::MAX_TERM_LENGTH), str_repeat('x', SearchCriteria::MAX_TERM_LENGTH)],
			'wildcards are kept literal' => ['100%_done', '100%_done'],
			'inner whitespace is kept' => ['quarterly report', 'quarterly report'],
		];
	}

	#[DataProvider('dataTerm')]
	public function testTerm(string $input, ?string $expected): void {
		$criteria = SearchCriteria::create($input);

		$this->assertSame($expected, $criteria->term);
		$this->assertSame($expected !== null, $criteria->hasTerm());
	}

	public static function dataInvalidTerm(): array {
		return [
			'single character' => ['a'],
			'single character with padding' => ['  a  '],
			'single multibyte character' => ['ä'],
			'too long' => [str_repeat('x', SearchCriteria::MAX_TERM_LENGTH + 1)],
		];
	}

	#[DataProvider('dataInvalidTerm')]
	public function testInvalidTerm(string $input): void {
		$this->expectException(InvalidSearchCriteriaException::class);

		SearchCriteria::create($input);
	}

	public static function dataDateRange(): array {
		return [
			'unset' => [0, 0, null, null],
			'negative is unset' => [-5, -5, null, null],
			'lower bound only' => [1000, 0, 1000, null],
			'upper bound only' => [0, 2000, null, 2000],
			'both bounds' => [1000, 2000, 1000, 2000],
			'single instant' => [1500, 1500, 1500, 1500],
			'lower bound clamped to the column maximum' => [PHP_INT_MAX, 0, SearchCriteria::MAX_TIMESTAMP, null],
			'upper bound clamped to the column maximum' => [0, PHP_INT_MAX, null, SearchCriteria::MAX_TIMESTAMP],
			'both clamped stays a valid range' => [PHP_INT_MAX, PHP_INT_MAX, SearchCriteria::MAX_TIMESTAMP, SearchCriteria::MAX_TIMESTAMP],
		];
	}

	#[DataProvider('dataDateRange')]
	public function testDateRange(int $from, int $to, ?int $expectedFrom, ?int $expectedTo): void {
		$criteria = SearchCriteria::create('', $from, $to);

		$this->assertSame($expectedFrom, $criteria->from);
		$this->assertSame($expectedTo, $criteria->to);
		$this->assertSame($expectedFrom !== null || $expectedTo !== null, $criteria->hasDateRange());
	}

	public function testInvertedDateRange(): void {
		$this->expectException(InvalidSearchCriteriaException::class);

		SearchCriteria::create('', 2000, 1000);
	}

	public function testClampingDoesNotCreateAnInvertedRange(): void {
		// The lower bound clamps down to the maximum, so it can never end up
		// above an upper bound that was also clamped
		$criteria = SearchCriteria::create('', PHP_INT_MAX, SearchCriteria::MAX_TIMESTAMP);

		$this->assertSame(SearchCriteria::MAX_TIMESTAMP, $criteria->from);
		$this->assertSame(SearchCriteria::MAX_TIMESTAMP, $criteria->to);
	}

	public function testCombinedCriteria(): void {
		$criteria = SearchCriteria::create('report', 1000, 2000, 'alice');

		$this->assertFalse($criteria->isEmpty());
		$this->assertTrue($criteria->hasTerm());
		$this->assertTrue($criteria->hasDateRange());
		$this->assertTrue($criteria->hasActor());
	}

	public static function dataActor(): array {
		return [
			'absent' => ['', null],
			'blank' => ['   ', null],
			'trimmed' => ['  alice  ', 'alice'],
			'single character is a valid account name' => ['a', 'a'],
			'maximum length' => [str_repeat('u', SearchCriteria::MAX_ACTOR_LENGTH), str_repeat('u', SearchCriteria::MAX_ACTOR_LENGTH)],
		];
	}

	#[DataProvider('dataActor')]
	public function testActor(string $input, ?string $expected): void {
		$criteria = SearchCriteria::create('', 0, 0, $input);

		$this->assertSame($expected, $criteria->actor);
		$this->assertSame($expected !== null, $criteria->hasActor());
	}

	public function testActorTooLong(): void {
		$this->expectException(InvalidSearchCriteriaException::class);

		SearchCriteria::create('', 0, 0, str_repeat('u', SearchCriteria::MAX_ACTOR_LENGTH + 1));
	}

	public function testActorAloneIsNotEmpty(): void {
		$criteria = SearchCriteria::create('', 0, 0, 'alice');

		$this->assertFalse($criteria->isEmpty());
		$this->assertFalse($criteria->hasTerm());
		$this->assertFalse($criteria->hasDateRange());
	}

	public static function dataToParameters(): array {
		return [
			'nothing set' => ['', 0, 0, '', []],
			'term only' => ['report', 0, 0, '', ['search' => 'report']],
			'lower bound only' => ['', 1000, 0, '', ['from' => 1000]],
			'upper bound only' => ['', 0, 2000, '', ['to' => 2000]],
			'actor only' => ['', 0, 0, 'alice', ['actor' => 'alice']],
			'everything' => ['report', 1000, 2000, 'alice', ['search' => 'report', 'from' => 1000, 'to' => 2000, 'actor' => 'alice']],
		];
	}

	#[DataProvider('dataToParameters')]
	public function testToParameters(string $term, int $from, int $to, string $actor, array $expected): void {
		$this->assertSame($expected, SearchCriteria::create($term, $from, $to, $actor)->toParameters());
	}
}
