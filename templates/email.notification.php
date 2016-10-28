<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

/** @var \OCP\IL10N $l */
/** @var array $_ */
$l = $_['overwriteL10N'];

print_unescaped($l->t('Hello %s,', array($_['username'])));
p("\n");
p("\n");

print_unescaped($l->t('You are receiving this email because the following things happened at %s', array($_['installation'])));
p("\n");
p("\n");

foreach ($_['activities'] as $activityData) {
	print_unescaped($l->t('* %1$s - %2$s', $activityData));
	p("\n");
}
if ($_['skippedCount']) {
	print_unescaped($l->n('* and %n more ', '* and %n more ', $_['skippedCount']));
	p("\n");
}
p("\n");
