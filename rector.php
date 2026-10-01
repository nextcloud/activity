<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
use Nextcloud\Rector\Set\NextcloudSets;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;

return RectorConfig::configure()
	->withPaths([
		__DIR__ . '/lib',
		__DIR__ . '/tests',
	])
	->withSkip([
		__DIR__ . '/tests/stubs',
		NullToStrictStringFuncCallArgRector::class,
	])
	->withPreparedSets(
		deadCode: true,
		typeDeclarations: true,
	)->withPhpSets(
		php82: true,
	)->withSets([
		NextcloudSets::NEXTCLOUD_35,
	])->withConfiguredRule(ClassPropertyAssignToConstructorPromotionRector::class, [
		'inline_public' => true,
		'rename_property' => true,
	]);
