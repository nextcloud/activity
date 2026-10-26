<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\GroupFolders\ACL;

use OCA\GroupFolders\ACL\UserMapping\IUserMapping;
use OCP\Constants;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Rule implements XmlSerializable, XmlDeserializable, \JsonSerializable {
	public const ACL = '{http://nextcloud.org/ns}acl';
	public const PERMISSIONS = '{http://nextcloud.org/ns}acl-permissions';
	public const MASK = '{http://nextcloud.org/ns}acl-mask';
	public const MAPPING_TYPE = '{http://nextcloud.org/ns}acl-mapping-type';
	public const MAPPING_ID = '{http://nextcloud.org/ns}acl-mapping-id';
	public const MAPPING_DISPLAY_NAME = '{http://nextcloud.org/ns}acl-mapping-display-name';

	public const PERMISSIONS_MAP = [
		'read' => Constants::PERMISSION_READ,
		'write' => Constants::PERMISSION_UPDATE,
		'create' => Constants::PERMISSION_CREATE,
		'delete' => Constants::PERMISSION_DELETE,
		'share' => Constants::PERMISSION_SHARE,
	];

	/**
	 * @param int $mask for every permission type a rule can either allow, deny or inherit
	 *                  these 3 values are stored as 2 bitmaps, one that masks out all inherit values (1 -> set permission, 0 -> inherit)
	 *                  and one that specifies the permissions to set for non inherited values (1-> allow, 0 -> deny)
	 */
	public function __construct(
		private readonly IUserMapping $userMapping,
		private readonly int $fileId,
		private int $mask,
		int $permissions,
	) {
	}

	public function getUserMapping(): IUserMapping {
	}

	public function getFileId(): int {
	}

	public function getMask(): int {
	}

	public function getPermissions(): int {
	}

	/**
	 * Apply this rule to an existing permission set, returning the resulting permissions
	 *
	 * All permissions included in the current mask will overwrite the existing permissions
	 */
	public function applyPermissions(int $permissions): int {
	}

	/**
	 * Apply the deny permissions this rule to an existing permission set, returning the resulting permissions
	 *
	 * Only the deny permissions included in the current mask will overwrite the existing permissions
	 */
	public function applyDenyPermissions(int $permissions): int {
	}

	#[\Override]
	public function xmlSerialize(Writer $writer): void {
	}

	/**
	 * @return array{
	 *     mapping: array{
	 *         type: 'user'|'group'|'dummy'|'circle',
	 *     	   id: string,
	 *     },
	 *     mask: int,
	 *     permissions: int,
	 * }
	 */
	#[\Override]
	public function jsonSerialize(): array {
	}

	#[\Override]
	public static function xmlDeserialize(Reader $reader): Rule {
	}

	/**
	 * merge multiple rules that apply on the same file where allow overwrites deny
	 * @param Rule[] $rules
	 */
	public static function mergeRules(array $rules): Rule {
	}

	/**
	 * apply a new rule on top of the existing
	 *
	 * All non-inherit fields of the new rule will overwrite the current permissions
	 */
	public function applyRule(Rule $rule): void {
	}

	/**
	 * Create a default, no-op rule
	 */
	public static function defaultRule(): Rule {
	}

	public static function formatRulePermissions(int $mask, int $permissions): string {
	}

	public function formatPermissions(): string {
	}
}
